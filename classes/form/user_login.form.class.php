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
			$c = new ContentPage("user-login-success", $this->config);
			$return = $c->ReturnRenderedContent(); 
			
			// redirect to previous page
			if (isset($_SESSION['HTTP_REFERER'])) {
				header("refresh:2 " . $_SESSION['HTTP_REFERER']);
			}
			
			// Clear the REFERER 
			unset($_SESSION['HTTP_REFERER']);
			
		} else {

			$c_values["{error-message}"] .= "Error: Authentication failure! <br />";
			
			$c = new ContentPage("user-login-error", $this->config);
			$f = new Form(($this->form_id), $this->config);
			
			unset($_POST['submit']);
			
			$return = $c->ReturnRenderedContent($c_values);
			$return = $return . $f->ReturnRenderedContent();	
		}
		
		return $return;
		
	}
	
}

?>