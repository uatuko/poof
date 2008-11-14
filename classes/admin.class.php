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

	private function GetDefaultAdminPage() {
		$c = new ContentPage("admin-default", $this->config);
		return $c->ReturnRenderedContent();
	}

	private function GetModulesAdminPage($module_str) {
		$module = split("/", $module_str, 2);
		if (!$module[0]) {
			$t = new Table("admin-modules", $this->config);
			return $t->ReturnRenderedContent();
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
			$return_html = $this->GetDefaultAdminPage();
		} else {
			switch ($admin_page[1]) {
				case 'modules':
					$return_html = $this->GetModulesAdminPage($admin_page[2]);
					break;
				default:
					$return_html = $this->content_id.":: Admin content...";
			}
		}

		return $return_html;
	}

}
?>