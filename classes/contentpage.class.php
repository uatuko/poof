<?php

class ContentPage extends Content {
	
	protected $config;
	protected $theme;
	
	public function __construct($content_id, $config) {
		parent::__construct($content_id, $config);
		$this->config = $config;
		$this->theme = new Theme($config);
	}
	
	public function __destruct() {
		parent::__destruct();
		unset($this->theme);
	}
	
	private function GetContentPageTemplate() {

		$page_alias = split("/", $_GET['q'], 2);
		$r_template = false;
		$db_prefix = $this->db->GetDBPrefix();
		
		if (!$page_alias[0]) {
			$page_alias[0] = "home";
		}
		
		if ($results = $this->db->ExecuteSQLQuery("CALL ".$db_prefix."getContentPageTemplate('$page_alias[0]', '$this->content_id')")) {
			foreach ($results as $row) {
				if ($row[1] = 1) {	// content visibility = 1 (show content)
					if ($row[0] = 1) {	// content priority = 1 (give priority to local template)
						$tpl_file = "themes/".$this->theme->GetThemeName($this->config->GetSiteName())."/templates/contentpage/".$this->content_id."template.html";
						if (is_file($tpl_file)) {
							$r_template = new Template($tpl_file, 'local', $this->config);
						} else $r_template = new Template($row[2], 'dbase', $this->config);
					}
				} else $r_template = false;				 
			}
		}
		return $r_template;
		
	}

	private function CreateContentClasses($contents) {
		
		$regx = "/[a-zA-Z0-9_]+\:[a-zA-Z0-9_-]+|[a-zA-Z0-9_-]+/";
			
		$content_classes = array();
		
		foreach($contents as $content) {
			if (preg_match($regx, $content, $matches)) {
				$arr = split(":", $matches[0]);
				
				if (!$arr[1]) {
					$arr[1] = $arr[0];
					$arr[0] = "Content";
				}
				
				$content_classes[$content] = new $arr[0]($arr[1], $this->config);				
			}
		}
		return $content_classes;
	}
	
	private function RenderContentClasses(&$content_classes) {
		
		$rendered_classes = array();
		
		foreach(array_keys($content_classes) as $content_key) {
			$rendered_classes[$content_key] = $content_classes[$content_key]->ReturnRenderedContent();
		}
		
		return $rendered_classes;
	}

	
	public function ReturnRenderedContent() {

		if ($template = $this->GetContentPageTemplate()) {
			$content_classes = array();
			$rendered_classes = array();
			
			$content_classes = $this->CreateContentClasses($template->GetContents());
			$rendered_classes = $this->RenderContentClasses($content_classes);
			
			return $template->ParseTemplate($rendered_classes);
		}
		
	}
	
	public function ReturnAdminPage() {
		
		if (isset($_GET['config'])) {
			switch ($_GET['config']) {
				case 'add':
					if (isset($_GET['page']) && $_GET['page'] == 'page') {
						$f = new Form("contentpage-add-new", $this->config);
						return $f->ReturnRenderedContent();
					} else {
						$f = new Form("contentpage-add-new-named", $this->config);
						return $f->ReturnRenderedContent();
					}
					break;
			}
		}
		
		$c = new ContentPage("contentpage-default", $this->config);
		return $c->ReturnRenderedContent();

	}
	
}

?>