<?php

class Template {

	private $template;
	private $system_template = false;
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
				$this->system_template = true;
		}

		$this->FilterContents();
	}

	private function LoadTemplateLocal($tpl_file) {
		$this->template = fread($fp = fopen($tpl_file, 'r'), filesize($tpl_file));
		fclose($fp);
	}

	private function LoadTemplateDB($tpl_id, $config) {
		$db = new Database($config->GetDatabaseConfig());

		if ($result = $db->ExecuteQuery("SELECT t.`template`, t.`system_template` FROM `".$db->GetDBPrefix()."templates` t WHERE t.`template_id` = $tpl_id")) {
			if ($row = $db->FetchAssoc($result)) {
				$this->template = $row['template'];
				$this->system_template = (boolean) $row['system_template'];
			}
		}

		unset($db);
	}

	private function FilterContents() {
		$regx = "/\{[a-zA-Z0-9_]+\}|\{[a-zA-Z0-9_]+\:[a-zA-Z0-9_]+\}/";

		if ($this->system_template) {
			$regx = "/\{[a-zA-Z0-9_-]+\}|\{[a-zA-Z0-9_]+\:[a-zA-Z0-9_-]+\}/";
		}
		
		if (preg_match_all($regx, $this->template, $matches)) {
			$this->contents = $matches[0];
		}
	}


	/* PUBLIC Fucntions and PROPERTIES */

	public function GetTemplate() {
		return $this->template;
	}

	public function GetContents() {
		return $this->contents;
	}

	// by default replace all contents
	public function ParseTemplate($rendered_contents, $override = true) {
		$parsed = $this->template;
		foreach ($this->contents as $content) {
			if ($rendered_contents[$content] || $override) {
				$parsed = str_replace($content, $rendered_contents[$content], $parsed);
			}
		}
		return $parsed;
	}

}

?>
