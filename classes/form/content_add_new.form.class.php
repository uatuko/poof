<?php

class content_add_new implements FormSubmitInterface {
	
	private $form_id;
	private $config;
	
	public function __construct($form_id, $config) {
		$this->form_id = $form_id;
		$this->config = $config;
	}
	
	public function ReturnSubmitForm() {
		
		$return = "We are sorry but your request cannot be processed due to errors. <br /><br />";
		$blnError = true;
		
		$c_values = array("{error-message}" => "We are sorry but your request cannot be processed due to errors. <br /><br />");
		$f_values = array("{alias-error}" => "no-error", "{alias-value}" => $_POST['alias'],
						"{type-error}" => "no-error", 
						"{type-11-selected}" => "", "{type-12-selected}" => "",
						"{path-error}" => "no-error", "{path-value}" => $_POST['content-local-path'],
						"{template-error}" => "no-error", "{template-value}" => $_POST['template']);
		
		switch ($_POST['content-type']) {
			case '11':
				$f_values["{type-11-selected}"] = 'selected="selected"';
				break;
			case '12':
				$f_values["{type-12-selected}"] = 'selected="selected"';
				break;				
		}
		
		if ($_POST["alias"] == "") {
			$f_values["{alias-error}"] = "error";
			$c_values["{error-message}"] .= "- Content Alias cannot be blank <br />";
		} else {
			$db = new Database($this->config->GetDatabaseConfig());
	
			if ($db->ExecuteMultiQuery("CALL ".$db->GetDBPrefix()."addContent('".$_POST['alias']."', '".$_POST['content-type']."', '".$_POST['content-local-path']."', '".$_POST['template']."', @x); SELECT @x;")) {
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
								$f_values["{path-error}"] = "error";
								$c_values["{error-message}"] .= "- Error: [$row[0]] <br />";
								break;
							case 30:
								$f_values["{template-error}"] = "error";
								$c_values["{error-message}"] .= "- Error: [$row[0]] <br />";
								break;
						}
					} else {
						$blnError = false;
					}
				}
			}	
		}
		
		if ($blnError) {
			$c = new ContentPage("content-save-error-message", $this->config);
			$f = new Form(($this->form_id . "-error"), $this->config);
			
			unset($_POST['submit']);
			
			$return = $c->ReturnRenderedContent($c_values);
			$return = $return . $f->ReturnRenderedContent($f_values);
		} else {
			$c = new ContentPage("content-save-success", $this->config);
			$return = $c->ReturnRenderedContent();
		}
		
		return $return;
		
	}
}

?>