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
		$rendered_content = $table_template->ParseTemplate($this->GetTableContentsArray());

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
	
	private function GetTemplateCell() {
		$template = $this->GetTemplate();
		
		$s = strstr($template, "[cell]");
		$end_pos = strpos($s, '[/cell]');
		
		return substr($s, 6, ($end_pos - 6));		
	}
	
	private function RenderRow($cell_string) {

		$template_row = $this->GetTemplateRow();
		
		//$cell_start = strpos($template_row, "[cell]");
		//$cell_end = (strpos($template_row, '[/cell]') + 1);
		
		$cell_header = preg_split("/\[cell\]/", $template_row);
		$cell_footer = preg_split("/\[\/cell\]/", $template_row);
		
		//return str_replace(substr($template_row, $cell_start, $cell_end), $cell_string, $template_row);
		return $cell_header[0] . $cell_string . $cell_footer[1]; 
		
	}
	
	private function FillCellData(&$cells) {
		
		$return_row = "";
		$rendered_cells = "";
		
		$template_cell = $this->GetTemplateCell();
		
		foreach ($cells as $cell) {
			$rendered_cells = $rendered_cells . preg_replace("/\{cell\}/", $cell, $template_cell);
		}
		
		$return_row = $this->RenderRow($rendered_cells);
		
		return $return_row;
	}
	
	public function ParseTemplate(&$table_content) {
		
		$return_template = "";
		$rendered_rows = "";
		
		foreach ($table_content as $cells) {
			$rendered_rows = $rendered_rows . $this->FillCellData($cells);
		}
		
		//$row_start = strpos($this->GetTemplate(), "[row]");
		//$row_end = (strpos($this->GetTemplate(), '[/row]') + 1);
		
		$rs = preg_split("/\[row\]/", $this->GetTemplate());
		$re = preg_split("/\[\/row\]/", $this->GetTemplate());
		
		//$row_replace = substr($this->GetTemplate(), $row_start, $row_end);
		//$return_template = str_replace($row_replace, $rendered_rows, $this->GetTemplate());

		$return_template = $rs[0] . $rendered_rows . $re[1];
		
		return $return_template;
		
	}

}

?>