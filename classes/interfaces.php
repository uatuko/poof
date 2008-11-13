<?php

interface RenderInterface {
	public function __construct($content_id, $config);
	public function ReturnRenderedContent();
}

interface AdminInterface {
	/* Must have the RenderInterface Constructor */
	/* public function __construct($content_id, $config); */
	public function ReturnAdminPage();
}

interface FormInterface {
	public function ReturnForm();
}

interface FormSubmitInterface {
	public function __construct($form_id, $config);
	public function ReturnSubmitForm();	
}

interface FormFieldValidator {
	public function ValidateField($field_id);
}

?>