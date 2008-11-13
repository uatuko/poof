<?php

class Page {
	
	public function __construct($content_id, $config) {
		echo "constructor called...";
	}
	
	public function __destruct() {
		echo "destructor called...";
	}
	
}

?>