<?php

class Database {

	private $mysqli;
	private $db_prefix;
		
	public function __construct($db_config) {
		$this->mysqli = new mysqli($db_config->server, $db_config->user, $db_config->password, 
									$db_config->dbase, $db_config->server_port);
		$this->db_prefix = $db_config->db_prefix;
	}
	
	public function __destruct() {
		$this->mysqli->close();
		unset($this->mysqli);
	}
	
	// PROPERTIES
	
	public function GetDBPrefix() {
		return $this->db_prefix;
	}
	
	/*
	 * FUNCTIONS to Abstract the data layer
	 * aka have a uniform set of functions regardless of 
	 * the database used. 
	 */
	
	public function ExecuteSQLQuery($sql_query) {
		
		$return_result = false;
		
		if ($this->mysqli->multi_query($sql_query)) {
			do {
				if ($result = $this->mysqli->store_result()) {
					while ($row = $result->fetch_row()) {
						$return_result[] = $row;
					}
				}
			} while ($this->mysqli->next_result());
		}
		
		return $return_result;
		
	}
	
	public function ExecuteQuery($sql) {
		return $this->mysqli->query($sql);
	}
	
	public function ExecuteMultiQuery($sql) {
		 return $this->mysqli->multi_query($sql);
	}
	
	public function MultiQueryFetchResults() {
		return $this->mysqli->store_result();
	}
	
	public function MultiQueryNextResult() {
		return $this->mysqli->next_result();	
	}
	
	public function FetchArray($result) {
		return $result->fetch_array();	
	}
	
	public function FetchAssoc($result) {
		return $result->fetch_assoc();
	}
	
	public function FetchRow($results) {
		return $results->fetch_row();
	}
	
	
	private function do_all($sql) {

		$mysqli = new mysqli("140.15.135.69", "sqdAdmin", "noa", "sqd17_oo" );
		$ivalue=1;
		$res = $mysqli->multi_query($sql);
		if( $res ) {
		  $results = 0;
		  do {
		    if ($result = $mysqli->store_result()) {
		      printf( "<b>Result #%u</b>:<br/>", ++$results );
		      while( $row = $result->fetch_row() ) {
		        foreach( $row as $cell ) echo $cell, "&nbsp;";
		      }
		      $result->close();
		      if( $mysqli->more_results() ) echo "<br/>";
		    }
		  } while( $mysqli->next_result() );
		}
		$mysqli->close(); 
		
	}
}

?>
