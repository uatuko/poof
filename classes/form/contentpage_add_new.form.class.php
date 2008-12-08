<?php

class contentpage_add_new implements FormSubmitInterface {

	private $form_id;
	private $config;

	public function __construct($form_id, $config) {
		$this->form_id = $form_id;
		$this->config = $config;
	}

	private function GetTemplateErrorForm() {

		$return = "";
		
		$c = new ContentPage("contentpage-save-error-message", $this->config);
		$f = new Form(($this->form_id . "-error"), $this->config);
		
		$c_values = array("{error-message}" => "Template cannot be blank");
		$f_values = array("{alias-error}" => "no-error",
						"{alias-value}" => $_POST['alias'],
						"{name-error}" => "no-error",
						"{name-value}" => $_POST['page-name'],
						"{template-error}" => "error",
						"{template-value}" => $_POST['template']);	

		unset($_POST['submit']);
		
		$return = $c->ReturnRenderedContent($c_values);
		$return = $return . $f->ReturnRenderedContent($f_values);
		
		return $return;
		
	}
	
	public function ReturnSubmitForm() {

		$return = "Error Saving: ";

		if ($_POST['template'] == "") {
			$return = $this->GetTemplateErrorForm();
		} else {
			$db = new Database($this->config->GetDatabaseConfig());

			if ($db->ExecuteMultiQuery("CALL ".$db->GetDBPrefix()."amendNamedContentPage('".$_POST['alias']."', '".$_POST['page-name']."', '".$_POST['template']."', @x); SELECT @x;")) {
				$db->MultiQueryNextResult();
				if ($result = $db->MultiQueryFetchResults()) {
					$row = $db->FetchRow($result);
					if ($row[0] != 0) {
						$c = new ContentPage("contentpage-save-error-message", $this->config);
						$f = new Form(($this->form_id . "-error"), $this->config);
						
						$c_values = array("{error-message}" => "Error: [$row[0]] <br /> Unable to save");
						$f_values = array("{alias-error}" => "no-error",
										"{alias-value}" => $_POST['alias'],
										"{name-error}" => "no-error",
										"{name-value}" => $_POST['page-name'],
										"{template-error}" => "no-error",
										"{template-value}" => $_POST['template']);				
						
						unset($_POST['submit']);
						
						switch ($row[0]) {
							case 10:
								$f_values["{alias-error}"] = "error";
								break;
							case 20:
								$f_values["{name-error}"] = "error";
								break;
						}
						
						$return = $c->ReturnRenderedContent($c_values);
						$return = $return . $f->ReturnRenderedContent($f_values);
						unset($f);
						unset($c);
						
					} else {
						$c = new ContentPage("contentpage-save-success", $this->config);
						$return = $c->ReturnRenderedContent();
					}
				}
			}

			unset($db);
		}

		return $return;
	}

}

?>