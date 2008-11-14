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

	private function GetTableType() {
		
		$table_type = 10;
		$db = new Database($this->config->GetDatabaseConfig());	
		$result = $db->ExecuteQuery("SELECT t.`table_type` FROM `sqd_tables` t WHERE t.`table_alias` = '$this->table_alias'; ");
		if ($row = $db->FetchRow($result)) {
			$table_type = $row[0];
		}
		unset($db);

		return $table_type;		
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
		
	private function RenderInnerClasses($rendered_table) {
		
		$template = new Template($rendered_table, 'string');
		$content_classes = $this->CreateContentClasses($template->GetContents());
		$rendered_classes = $this->RenderContentClasses($content_classes);
		return $template->ParseTemplate($rendered_classes);
		
	}
	
	public function ReturnRenderedContent() {

		$rendered_content = "";

		$table_template = new TableTemplate($this->GetTableTemplateID(), 'dbase', $this->config);
		$rendered_content = $table_template->ParseTemplate($this->GetTableContentsArray());
		unset($table_template);

		if ((($this->GetTableType())%10) == 5) {
			// Process inner classes
			$rendered_content = $this->RenderInnerClasses($rendered_content);
		}
		
		return $rendered_content;

	}

	public function ReturnAdminPage() {
		if (isset($_GET['config'])) {
			switch ($_GET['config']) {
				case 'add':
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
		$row_s = preg_split("/\[row\]/", $this->GetTemplate(), 2);
		$row_e = preg_split("/\[\/row\]/", $row_s[1], 2);
		return $row_e[0];
	}
	
	private function GetTemplateCell() {
		$cell_s = preg_split("/\[cell\]/", $this->GetTemplate(), 2);
		$cell_e = preg_split("/\[\/cell\]/", $cell_s[1], 2);
		return $cell_e[0];	
	}
	
	private function RenderCells(&$cells) {
		
		$return_row = "";
		$rendered_cells = "";
		
		$template_cell = $this->GetTemplateCell();
		
		foreach ($cells as $cell) {
			$rendered_cells = $rendered_cells . preg_replace("/\{cell\}/", $cell, $template_cell);
		}
		
		$cell_header = preg_split("/\[cell\]/", $this->GetTemplateRow(), 2);
		$cell_footer = preg_split("/\[\/cell\]/", $this->GetTemplateRow(), 2);
		
		$return_row = $cell_header[0] . $rendered_cells . $cell_footer[1];
		
		return $return_row;
		
	}
	
	public function ParseTemplate(&$table_content) {
		
		$return_template = "";
		$rendered_rows = "";
		
		foreach ($table_content as $cells) {
			$rendered_rows = $rendered_rows . $this->RenderCells($cells);
		}
		
		$row_header = preg_split("/\[row\]/", $this->GetTemplate(), 2);
		$row_footer = preg_split("/\[\/row\]/", $this->GetTemplate(), 2);
		
		$return_template = $row_header[0] . $rendered_rows . $row_footer[1];
		
		return $return_template;
		
	}

}

?>