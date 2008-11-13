<?php

class Table implements RenderInterface, AdminInterface {
	
	protected $content_id;
	protected $config;
	
	public function __construct($content_id, $config) {
		$this->content_id = $content_id;
		$this->config = $config;
	}
	
	public function __destruct() {
		// Destructor
	}
	
	public function ReturnRenderedContent() {
		return $this->content_id;
		
		// query and get all the table rows
		// load a new table template
		// parse template with query results
		// return parsed template
		
	}
	
	public function ReturnAdminPage() {
		if (isset($_GET['config'])) {
			switch ($_GET['config']) {
				case 'add':
					$a = "<ul>[row]<a href=\"this is a url.?aspx&\"></a><li>[cell]{cell}[/cell]</li>[/row]</ul>";
					$b = "<ul>[row]sometext[/row]</ul>";
					preg_match_all("/\[row\]([\w\<\>\[\]\/\\\{\}\.\?&\"'\s=]+)\[\/row\]/", $a, $matches);
					print_r($matches);
					return "Add new table...";
					break;
			}
		}
		
		$c = "<a href=\"/squad17.com/admin/modules/Table/?config=add\">Add new table</a>";
		return $c;
	}
	
}

class TableTemplate extends Template {
	
	public function __construct($tpl_id, $tpl_location, $config = null) {
		parent::__construct($tpl_id, $tpl_location, $config);
	}
	
	public function ParseTemplate($table_content) {
		print_r($table_content);
	}
	
}

?>