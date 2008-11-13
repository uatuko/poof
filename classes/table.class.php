<?php

class Table implements RenderInterface, AdminInterface {

	protected $table_alias;
	protected $config;

	public function __construct($content_id, $config) {
		$this->table_alias = $content_id;
		$this->config = $config;
	}

	public function __destruct() {
		// Destructor
	}

	private function GetTableContentsArray() {

		$table_contents = array();
		$db = new Database($this->config->GetDatabaseConfig());

		$table_result = $db->ExecuteQuery("SELECT `table_query` FROM `sqd_tables` WHERE `table_alias` = '$this->table_alias';");
		if ($table_query = $db->FetchRow($table_result)) {
			$sql_query = $table_query[0];
			$table_contents = $db->ExecuteSQLQuery($sql_query);
		}
		unset($db);

		return $table_contents;

	}

	private function GetTableTemplateID() {

		$template_id = 0;

		$db = new Database($this->config->GetDatabaseConfig());
		$result = $db->ExecuteQuery("SELECT t.`template_id` FROM `sqd_table_templates` t INNER JOIN `sqd_tables` s ON t.`table_id` = s.`table_id` WHERE s.`table_alias` = '$this->table_alias'; ");
		if ($row = $db->FetchRow($result)) {
			$template_id = $row[0];
		}
		unset($db);

		return $template_id;

	}

	public function ReturnRenderedContent() {

		$rendered_content = "";

		$table_template = new TableTemplate($this->GetTableTemplateID(), 'dbase', $this->config);
		$rendered_content = $table_template->ParseTemplate($rendered_content);

		unset($table_template);

		return $rendered_content;
		// query and get all the table rows
		// load a new table template
		// parse template with query results
		// return parsed template

	}

	public function ReturnAdminPage() {
		if (isset($_GET['config'])) {
			switch ($_GET['config']) {
				case 'add':
					return $this->ReturnRenderedContent();
					return "Add new table...";
					break;
			}
		}

		$c = "<a href=\"/squad17.com/admin/modules/Table/?config=add\">Add new table</a>";
		return $c;
	}

}

class TableTemplate extends Template {

	private $config;

	public function __construct($tpl_id, $tpl_location, $config = null) {
		parent::__construct($tpl_id, $tpl_location, $config);
		$this->config = $config;
	}

	private function GetTemplateRow() {
		$template = $this->GetTemplate();
		
		$s = strstr($template, "[row]");
		$end_pos = strpos($s, '[/row]');
		
		return substr($s, 5, ($end_pos - 5));
	}
	
	private function FillCellData(&$cells) {
		//
	}
	
	public function ParseTemplate(&$table_content) {
		
		$return_template = "";

		foreach ($table_content as $row) {
			foreach ($row as $cells) {
				
			}
		}
		return $this->GetTemplateRow();
		
		return $this->GetTemplate();
	}

}

?>