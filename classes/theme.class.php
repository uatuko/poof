<?php

class Theme {

	private $db;

	public function __construct($config) {
		$this->db = new Database($config->GetDatabaseConfig());
	}

	public function __destruct() {
		unset($this->db);
	}

	public function GetThemeName($site) {

		$db_prefix = $this->db->GetDBPrefix();

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