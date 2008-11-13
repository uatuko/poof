<?php

class Config {

	private $file_default = "config/default.config.xml";
	private $db_config;
	private $site_config;
	
	public function __construct($xml_file = null) {
		$this->db_config = new DatabaseConfig();
		$this->site_config = new SiteConfig();
		
		if (is_null($xml_file)) {
			$xml_file = $this->file_default;
		}
		$this->LoadDatabaseConfigDOM($xml_file);
		$this->LoadSiteConfigDOM($xml_file);
	}
	
	public function __destruct() {
		unset($this->db_config);
		unset($this->site_config);
	}
	
	private function LoadDatabaseConfig(&$xml) {
		if ($xml->getName() == "database") {
			foreach($xml->children() as $child) {
				if ($child->getName() == "mysql") {
					foreach($child->children() as $s_child) {
						switch ($s_child->getName()) {
							case "server":
								$this->db_config->server = $s_child;
								break;
							case "port":
								$this->db_config->server_port = $s_child;
								break;
							case "username":
								$this->db_config->user = $s_child;
								break;
							case "password":
								$this->db_config->password = $s_child;
								break;
							case "dbase":
								$this->db_config->dbase = $s_child;
								break;	
							case "db_prefix":
								$this->db_config->db_prefix = $s_child;							
						}
					}
				}
			}	
		}
	}
	
	private function LoadDatabaseConfigDOM($xml_file) {
		$dom = new DOMDocument();
		$dom->load($xml_file);
		
		$mysql_configs = $dom->getElementsByTagName("mysql");
		foreach($mysql_configs as $mysql_config) {
			$this->db_config->server = $dom->getElementsByTagName("server")->item(0)->nodeValue;
			$this->db_config->server_port = $dom->getElementsByTagName("port")->item(0)->nodeValue;
			$this->db_config->user = $dom->getElementsByTagName("username")->item(0)->nodeValue;
			$this->db_config->password = $dom->getElementsByTagName("password")->item(0)->nodeValue;
			$this->db_config->dbase = $dom->getElementsByTagName("dbase")->item(0)->nodeValue;
			$this->db_config->db_prefix = $dom->getElementsByTagName("db_prefix")->item(0)->nodeValue;
		}
	}
	
	private function LoadSiteConfigDOM($xml_file) {
		$dom = new DOMDocument();
		$dom->load($xml_file);
		
		$site_configs = $dom->getElementsByTagName("site");
		foreach($site_configs as $site_config) {
			 $this->site_config->site_name = $dom->getElementsByTagName("name")->item(0)->nodeValue;
		}
	}
	
	/* PUBLIC Functions and PROPERTIES */
	public function getDatabaseConfig() { return $this->db_config; }
	public function getSiteName() { return $this->site_config->site_name; }
	
}

class DatabaseConfig {

	public $server;
	public $server_port;
	public $user;
	public $password;
	public $dbase;
	public $db_prefix;
	
	public function server_string() {
		return $this->server . ":" . $this->server_port;
	}
	
}

class SiteConfig {
	
	public $site_name;
	
}

?>
