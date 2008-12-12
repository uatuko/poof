<?php

class user_login implements FormSubmitInterface {
	
	private $form_id;
	private $config;
	
	public function __construct($form_id, $config) {
		$this->form_id = $form_id;
		$this->config = $config;
	}
	
	public function ReturnSubmitForm() {
		
		$return = "Error Loging in...";
		
		// Query database through SystemUser class
		// If success
			// set the session variables
			// display success message
		// else
			// display login failed message
			// redisplay the login form
		
		return $return;
		
	}
	
}

?>