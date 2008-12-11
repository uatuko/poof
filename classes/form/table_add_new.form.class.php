<?php

class table_add_new implements FormSubmitInterface {

	private $form_id;
	private $config;

	public function __construct($form_id, $config) {
		$this->form_id = $form_id;
		$this->config = $config;
	}
	
	public function ReturnSubmitForm() {
		
		$return = "Error Saving: ";
		$blnError = true;
		
		$c_values = array("{error-message}" => "We are sorry but your request cannot be processed due to errors. <br /><br />");
		$f_values = array("{alias-error}" => "no-error", "{alias-value}" => $_POST['alias'],
						"{type-error}" => "no-error", 
						"{type-10-selected}" => "", "{type-15-selected}" => "",
						"{sql-query-error}" => "no-error", "{sql-query-value}" => $_POST['sql-query']);
		
		switch ($_POST['table-type']) {
			case '10':
				$f_values["{type-10-selected}"] = 'selected="selected"';
				break;
			case '15':
				$f_values["{type-15-selected}"] = 'selected="selected"';
				break;				
		}

		$db = new Database($this->config->GetDatabaseConfig());

		if ($db->ExecuteMultiQuery("CALL ".$db->GetDBPrefix()."addTable('".$_POST['alias']."', '".$_POST['table-type']."', '".$_POST['sql-query']."', @x); SELECT @x;")) {
			$db->MultiQueryNextResult();
			if ($result = $db->MultiQueryFetchResults()) {
				$row = $db->FetchRow($result);
				if ($row[0] != 0) {
					switch ($row[0]) {
						case 10:
							$f_values["{alias-error}"] = "error";
							$c_values["{error-message}"] .= "- Error: [$row[0]] <br />";
							break;
						case 20:
							$f_values["{sql-query-error}"] = "error";
							$c_values["{error-message}"] .= "- Error: [$row[0]] <br />";
							break;
					}
				} else {
					$blnError = false;
				}
			}
		}
			
		if ($blnError) {
			$c = new ContentPage("table-error-message", $this->config);
			$f = new Form(($this->form_id . "-error"), $this->config);
			
			unset($_POST['submit']);
			
			$return = $c->ReturnRenderedContent($c_values);
			$return = $return . $f->ReturnRenderedContent($f_values);
		} else {
			$c = new ContentPage("table-save-success", $this->config);
			$return = $c->ReturnRenderedContent();
		}
		
		return $return;
	}
	
}

?>