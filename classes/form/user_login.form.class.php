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
		
		$sysuser = new SystemUser($this->config);
		$user_id = $sysuser->AuthenticateUser($_POST['user-name'], $_POST['password']);
		
		if ($user_id > 0) {
			if (session_id() == "") session_start();
			$_SESSION['user'] = $user_id;
			
			// Display success message
			$return = "Success"; 
			
			// redirect to previous page
			if (isset($_SESSION['HTTP_REFERER'])) {
				header("refresh:2 " . $_SESSION['HTTP_REFERER']);
			}
			
			// Clear the REFERER 
			unset($_SESSION['HTTP_REFERER']);
			
		} else {
			// display login failed message
			// redisplay the login form
			$return = "Failed";			
		}
		
		return $return;
		
	}
	
}

?>