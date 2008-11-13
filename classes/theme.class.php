<?php

class Theme {
	
	private $db;
	
	public function __construct($config) {
		$this->db = new Database($config->getDatabaseConfig()); 
	}
		
	public function __destruct() {
		unset($this->db);
	}
	
	public function getThemeName($site) {
		
		/*
		if ($result = $this->db->ExecuteQuery("SELECT t.`theme_name` FROM `sqd_config` c INNER JOIN `sqd_themes` t ON c.`theme` = t.`theme_id` WHERE c.`site` = 'default';")) {
			while ($row = $this->db->FetchResultsArray($result)) {
				print_r($row['theme_name']);
			}
		}
		*/
		
		$db_prefix = $this->db->getDBPrefix();
		
		if ($this->db->ExecuteMultiQuery("CALL `".$db_prefix."getSiteTheme`('$site');")) {
			if ($result = $this->db->MultiQueryFetchResults()) {
				$row = $this->db->FetchRow($result);
				return $row[0];
			}		
		}
		return 'default';
	}
}

?>