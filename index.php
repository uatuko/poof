<?php

/* Include all the required class files */

require_once("classes/interfaces.php");
require_once("classes/site.class.php");
require_once("classes/content.class.php");
require_once("classes/contentpage.class.php");
require_once("classes/template.class.php");
require_once("classes/database.class.php");
require_once("classes/config.class.php");
require_once("classes/theme.class.php");

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
$site = new Site($config);

print $site->RenderHTML();

?>