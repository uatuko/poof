<?php

class Site {
	
	private $config;
	private $theme;
	private $page_template;
	private $db;
	
	public function __construct($default_config = null) {
		
		if (is_null($default_config)) {
			$this->config = new Config();
		} else {
			$this->config = $default_config;
		}
		
		$this->db = new Database($this->config->GetDatabaseConfig());
		$this->theme = new Theme($this->config);

		$this->page_template = new Template('themes/'.$this->theme->GetThemeName($this->config->GetSiteName()).'/templates/site.template.html', 'local');
	}

	public function __destruct() {
		unset($this->config);
		unset($this->db);	
		unset($this->theme);
		unset($this->page_template);
	}
	
	private function CreateContentClasses($contents) {
		
		$regx = "/[a-zA-Z0-9_]+\:[a-zA-Z0-9_]+|[a-zA-Z0-9_]+/";
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
	
	public function RenderHTML() {
		
		$content_classes = array();
		$rendered_classes = array();
		
		$content_classes = $this->CreateContentClasses($this->page_template->GetContents());
		$rendered_classes = $this->RenderContentClasses($content_classes);
		
		//print_r($this->theme);
		//print_r($content_classes);
		
		return ($this->page_template->ParseTemplate($rendered_classes));
	}
}

?>