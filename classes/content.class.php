<?php

class Content implements RenderInterface, AdminInterface {

	protected $content_id;
	protected $db;
	protected $config;

	public function __construct($content_id, $config) {
		$this->content_id = $content_id;
		$this->db = new Database($config->GetDatabaseConfig());
		$this->config = $config;
	}

	public function __destruct() {
		unset($this->db);
	}

	protected function CreateContentClasses($contents) {
		
		$regx = "/[a-zA-Z0-9_]+\:[a-zA-Z0-9_-]+|[a-zA-Z0-9_-]+/";
			
		$content_classes = array();
		
		foreach($contents as $content) {
			if (preg_match($regx, $content, $matches)) {
				$arr = split(":", $matches[0]);
				
				if (!$arr[1]) {
					$arr[1] = $arr[0];
					$arr[0] = "Content";
				}
				
				if (class_exists($arr[0])) {
					$content_classes[$content] = new $arr[0]($arr[1], $this->config);				
				}
			}
		}
		return $content_classes;
	}
	
	protected function RenderContentClasses(&$content_classes) {
		
		$rendered_classes = array();
		
		foreach(array_keys($content_classes) as $content_key) {
			if ($content_classes[$content_key] instanceof RenderInterface) {
				$rendered_classes[$content_key] = $content_classes[$content_key]->ReturnRenderedContent();
			}
		}
		
		return $rendered_classes;
	}
	
	public function ReturnRenderedContent($process_inner = false) {

		$return_html = "";
		$db_prefix = $this->db->GetDBPrefix();

		if ($this->db->ExecuteMultiQuery("CALL ".$db_prefix."getContent('$this->content_id')")) {
			if ($result = $this->db->MultiQueryFetchResults()) {
				if ($row = $this->db->FetchRow($result)) {
					switch ($row[0]%10) {
						case 1:
							$return_html = fread($fp = fopen($row[1], 'r'), filesize($row[1]));
							fclose($fp);
							break;
						case 2:
							$return_html = $row[1];
							break;
						default:
							$return_html = $this->content_id;
					}
				}

			}
		} else $return_html = $this->content_id;

		if ($process_inner && ($return_html != $this->content_id)) {
			
			$template = new Template($return_html, 'string');
			$content_classes = array();
			$rendered_classes = array();
			
			$content_classes = $this->CreateContentClasses($template->GetContents());
			$rendered_classes = $this->RenderContentClasses($content_classes);
			
			$return_html = $template->ParseTemplate($rendered_classes);
		}
		
		return $return_html;
	}

	public function ReturnAdminPage() {

		if (isset($_GET['config'])) {
			switch ($_GET['config']) {
				case 'add':
					$f = new Form("content-add-new", $this->config);
					return $f->ReturnRenderedContent();
					break;
				case 'edit': 
					return "Edit screen";
					break;
			}
		}
		
		$c = new ContentPage("content-default", $this->config);
		return $c->ReturnRenderedContent();
	}

}

?>
