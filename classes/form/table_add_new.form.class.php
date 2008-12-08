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
		
		$c_values = array("{error-message}" => "Error");
		$f_values = array("{alias-error}" => "no-error", "{alias-value}" => $_POST['alias'],
						"{type-error}" => "no-error", 
						"{type-11-selected}" => "", "{type-12-selected}" => "",
						"{path-error}" => "no-error", "{path-value}" => $_POST['content-local-path'],
						"{template-error}" => "no-error", "{template-value}" => $_POST['template']);
				
		return $return;
	}
	
}

?>