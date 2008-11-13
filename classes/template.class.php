<?php

class Template {

	private $template;
	private $contents = array();
	
	public function __construct($tpl, $tpl_location, $config = null) {
		
		switch($tpl_location) {
			case 'local':
				$this->LoadTemplateLocal($tpl);
				break;
			case 'dbase':
				$this->LoadTemplateDB($tpl, $config);
				break;
			case 'string':
			default:
				$this->template = $tpl;
		}
		
		$this->FilterContents();
	}
		
	private function LoadTemplateLocal($tpl_file) {
		$this->template = fread($fp = fopen($tpl_file, 'r'), filesize($tpl_file));
		fclose($fp);
	}
	
	private function LoadTemplateDB($tpl_id, $config) {
		$db = new Database($config->getDatabaseConfig());
		
		if ($result = $db->ExecuteQuery("SELECT t.`template` FROM `".$db->getDBPrefix()."templates` t WHERE t.`template_id` = $tpl_id")) {
			if ($row = $db->FetchAssoc($result)) {
				$this->template = $row['template'];
			}
		}
		
		unset($db);
	}
	
	private function FilterContents() {
		$regx = "/\{[a-zA-Z0-9_]+\}|\{[a-zA-Z0-9_]+\:[a-zA-Z0-9_]+\}/";
		
		if (preg_match_all($regx, $this->template, $matches)) {
			$this->contents = $matches[0];
		}
	}
	

	/* PUBLIC Fucntions and PROPERTIES */
	
	public function getTemplate() {
		return $this->template;
	}
	
	public function getContents() {
		return $this->contents;
	}
	
	public function ParseTemplate($rendered_contents) {
		$parsed = $this->template;
		foreach ($this->contents as $content) {
			$parsed = str_replace($content, $rendered_contents[$content], $parsed);	
		}
		return $parsed;
	}
	
}

?>
