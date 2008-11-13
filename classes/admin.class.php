<?php

class Admin implements RenderInterface {
	
	private $content_id;
	private $config;
	
	public function __construct($content_id, $config) {
		$this->content_id = $content_id;
		$this->config = $config;
	}
	
	public function __destruct() {
		// Destructor	
	}
	
	private function getDefaultAdminPage() {
		$c = new ContentPage("admin-default", $this->config);
		return $c->ReturnRenderedContent();		
	}
	
	private function getModulesAdminPage($module_str) {
		$module = split("/", $module_str, 2);
		if (!$module[0]) {
			//$c = new ContentPage("admin-modules", $this->config);
			//return $c->ReturnHTML();
			
			/*  --------------------------------
			 *   FIX ME: Proper Module editing 
			 *  --------------------------------
			 */
			$ret = "<ul>";
			$url_prefix = (new Content("url_prefix", $this->config));
			$url_prefix = $url_prefix->ReturnRenderedContent();
			
			$db = new Database($this->config->getDatabaseConfig());
			if ($result = $db->ExecuteQuery("SELECT * FROM ".$db->getDBPrefix()."modules;")) {
				while ($row = $db->FetchAssoc($result)) {
					$ret = $ret . "<li><a href=\"".$url_prefix."admin/modules/$row[module_name]\">" . $row['module_name'] . "</a></li>";
				}
			}
			$ret = $ret . "</ul>";
			return $ret;
			
			/*  ------------------------------------
			 *             END FIX ME
			 *  ------------------------------------
			 */
			
		} else {
			if (class_exists($module[0])) {
				$module_class = new $module[0]($this->content_id, $this->config);
				if ($module_class instanceof AdminInterface){
					return $module_class->ReturnAdminPage();
				} else return "$module[0]::No admin interface";
			} else return "$module[0]::Not a configurable module";
		}
	}
	
	public function ReturnRenderedContent() {
		
		$return_html = "";
		
		$admin_page = split("/", $_GET['q'], 3);
		
		if (!$admin_page[1]) {
			// default admin page
			$return_html = $this->getDefaultAdminPage();
		} else {
			switch ($admin_page[1]) {
				case 'modules':
					$return_html = $this->getModulesAdminPage($admin_page[2]);
					break;
				default:
					$return_html = $this->content_id.":: Admin content...";
			}
		}

		return $return_html;
	}
	
}
?>