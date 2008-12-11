<?php

/*
 * This file should be only used with AJAX requests
 * where a full rendered site is not required.
 */

require_once("classes/interfaces.php");

function __autoload($class_name) {

	$class_files[] = "classes/" . strtolower($class_name) . ".class.php";

	if ($handle = opendir("classes/")) {
	    while (false !== ($file = readdir($handle))) {
	        if ($file != "." && $file != "..") {
	            if (is_dir("classes/$file")) {
					$class_files[] = "classes/$file/" . strtolower($class_name) . ".$file.class.php";
	            }
	        }
	    }
	    closedir($handle);
	}

	foreach ($class_files as $class_file) {
		if (is_file($class_file)) {
			require_once ($class_file);
		}
	}

}

$config = new Config("config/remote.config.xml");
$ajax_page = new ContentPage("body", $config);

print $ajax_page->ReturnRenderedContent();

?>