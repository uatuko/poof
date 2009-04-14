<?php

class SystemUser {

	protected $user_id;
	protected $config;
	
	public function __construct($config) {
		$this->user_id = 0;
		$this->config = $config;
	}
	
	public function AuthenticateUser($user, $pass) {
		
		$db = new Database($this->config->GetDatabaseConfig());
		$db_prefix = $db->GetDBPrefix();
		
		$sql = "SELECT u.`user_id` FROM `" . $db_prefix . "user` u 
					WHERE u.`user_name` = '$user' AND u.`user_password` = MD5('$pass');";
		
		if ($result = $db->ExecuteQuery($sql)) {
			$row = $db->FetchRow($result);
			if (isset($row[0])) return $row[0];
		} else {
			// Failed - DB error
			return -1;
		}
		
		// Failed - user, pass didn't match
		return 0;
		
	}
	
	public function GetUserName($user_id = null) {
		
		if (!$user_id) $user_id = $this->user_id;
		
		// Check for First Name last name
		// If null then return user name
		// else return First name last name combination
		
		return "Firstname Lastname";
		
	}
	
}

?>