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


	public function ReturnRenderedContent() {

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

		return $return_html;
	}

	public function ReturnAdminPage() {

		if (isset($_GET['config'])) {
			switch ($_GET['config']) {
				case 'add':
					$f = new Form("content-add-new", $this->config);
					return $f->ReturnRenderedContent();
					//return "Form...";
					break;
			}
		}
		
		$c = new ContentPage("content-default", $this->config);
		return $c->ReturnRenderedContent();
	}

}

?>
