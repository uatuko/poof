<?php

class form_add_new implements FormSubmitInterface {
	
	private $form_id;
	private $config;

	public function __construct($form_id, $config) {
		$this->form_id = $form_id;
		$this->config = $config;
	}
	
	private function ReturnStage1() {
		
		$return = "Error Saving stage 1,";
		$blnError = true;
		
		$c_values = array("{error-message}" => "We are sorry but your request cannot be processed due to errors. <br /><br />");
		$f_values = array("{name-error}" => "no-error", "{name-value}" => $_POST['form-name'],
						"{type-error}" => "no-error", 
						"{type-10-selected}" => "", "{type-15-selected}" => "");
		
		switch ($_POST['form-type']) {
			case '10':
				$f_values["{type-10-selected}"] = 'selected="selected"';
				break;
			case '15':
				$f_values["{type-15-selected}"] = 'selected="selected"';
				break;				
		}

		if (session_id() == "") session_start();
		
		if ($_POST['form-name'] == "") {
			$f_values["{name-error}"] = "error";
			$c_values["{error-message}"] .= "- Form Name cannot be blank <br />";
			
			unset($_SESSION['form-name']);
			unset($_SESSION['form-type']);
		} else {			
			if (isset($_SESSION['form-name'])) {
				$c_values["{error-message}"] .= "- Possible session hijacking detected. <br />";
			} else {
				$_SESSION['form-name'] = $_POST['form-name'];
				$_SESSION['form-type'] = $_POST['form-type'];
				$blnError = false;
			}
		}
		
		if ($blnError) {
			$c = new ContentPage("form-error-message", $this->config);
			$f = new Form(($this->form_id . "-error"), $this->config);
			
			unset($_POST['submit']);
			
			$return = $c->ReturnRenderedContent($c_values);
			$return = $return . $f->ReturnRenderedContent($f_values);
		} else {
			unset($_POST['submit']);
			
			switch ($_SESSION['form-type']) {
				case 10:
					$f = new Form("form-add-new-stage-2", $this->config);
					$return = $f->ReturnRenderedContent();
					break;
				default:
					$c_values["{error-message}"] .= "- Cannot identify the Form Type [".$_SESSION['form-type']."] <br />";
					$c = new ContentPage("form-error-message", $this->config);
					$return = $c->ReturnRenderedContent($c_values); 
			}

		}
		
		return $return;
		
	}
	
	public function ReturnSubmitForm() {
		
		$return = "Error Saving: ";

		if (!isset($_POST['stage'])) {
			$_POST['stage'] = 1;
		}
		
		switch ($_POST['stage']) {
			case 1:
				$return = $this->ReturnStage1();
				break;
		}
		
		return $return;
		
	}
	
}

?>