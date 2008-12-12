<?php

class SystemUser {

	protected $user_id;
	
	public function __construct() {
		$this->user_id = 0;
	}
	
	public function AuthenticateUser($user, $pass) {
		// check database
		// return user id	
	}
	
	public function GetUserName($user_id = null) {
		
		if (!$user_id) $user_id = $this->user_id;
		
		// Check for First Name last name
		// If null then return user name
		// else return First name last name combination
		
	}
	
}

?>