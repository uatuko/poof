<?php

class Form implements RenderInterface, AdminInterface {

	protected $content_id;
	protected $config;

	public function __construct($content_id, $config) {
		$this->content_id = $content_id;
		$this->config = $config;
	}

	public function __destruct() {
		// Destructor
	}

	public function ReturnRenderedContent() {

		$return = "";
		$db = new Form_Database($this->config);

		if (!isset($_POST['submit'])) {
			$return = $db->GetFormTemplate($this->content_id);
		} else {
			$class_name = $db->GetFormSubmitClass($this->content_id);

			if (class_exists($class_name)) {
				$s = new $class_name($this->content_id, $this->config);
				if ($s instanceof FormSubmitInterface) {
					$return = $s->ReturnSubmitForm();
				} else $return = "Error: Unsupported interface for the form submit class.";
			} else $return = "Error: Form submit class not recognised.";
		}

		unset($db);
		return $return;
	}

	public function ReturnAdminPage() {
		if (isset($_GET['config'])) {
			switch ($_GET['config']) {
				case 'add':
					return "Add new form...";
					break;
			}
		}

		$c = new ContentPage("form-default", $this->config);
		return $c->ReturnRenderedContent();
	}

}

class Form_Database extends Database {

	public function __construct($config) {
		if ($config instanceof Config) {
			parent::__construct($config->GetDatabaseConfig());
		} else if ($config instanceof DatabaseConfig) {
			parent::__construct($config);
		} else throw new Exception("Invalid argument");
	}

	public function __destruct() {
		parent::__destruct();
	}

	public function GetFormTemplate($form_name) {

		$return = false;

		if ($result = $this->ExecuteQuery("SELECT t.`template` FROM `sqd_forms` f INNER JOIN `sqd_form_templates` ft ON
			f.`form_id` = ft.`form_id` INNER JOIN `sqd_templates` t ON
	    	ft.`template_id` = t.`template_id` WHERE f.`form_name` = '$form_name';")) {
			if ($row = $this->FetchRow($result)) {
				$return = $row[0];
			}
		}

		return $return;

	}

	public function GetFormSubmitClass($form_name) {
		$return = false;
		if ($result = $this->ExecuteQuery("SELECT f.`form_submit_class` FROM `sqd_forms` f WHERE f.`form_name` = '$form_name';")) {
			if ($row = $this->FetchRow($result)) {
				$return = $row[0];
			}
		}
		return $return;
	}

}

?>