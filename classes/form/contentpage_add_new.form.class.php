<?php

class contentpage_add_new implements FormSubmitInterface {

	private $form_id;
	private $config;

	public function __construct($form_id, $config) {
		$this->form_id = $form_id;
		$this->config = $config;
	}

	public function ReturnSubmitForm() {

		$return = "Error Saving: ";

		if ($_POST['template'] == "") {
			$c = new ContentPage("contentpage-save-error-message", $this->config);
			$return = $c->ReturnRenderedContent();
		} else {
			$db = new Database($this->config->GetDatabaseConfig());

			if ($db->ExecuteMultiQuery("CALL sqd_amendNamedContentPage('".$_POST['alias']."', '".$_POST['page-name']."', '".$_POST['template']."', @x); SELECT @x;")) {
				$db->MultiQueryNextResult();
				if ($result = $db->MultiQueryFetchResults()) {
					$row = $db->FetchRow($result);
					if ($row[0] != 0) {
						$return = "Error: Couldn't save [$row[0]] <br />";
						unset($_POST['submit']);
						$f = new Form($this->form_id, $this->config);
						$return = $return . $f->ReturnRenderedContent();
						unset($f);
					} else {
						$c = new ContentPage("contentpage-save-success", $this->config);
						$return = $c->ReturnRenderedContent();
					}
				}
			}

			unset($db);
		}

		return $return;
	}

}

?>