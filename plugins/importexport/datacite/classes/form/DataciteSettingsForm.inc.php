<?php

/**
 * @file plugins/importexport/datacite/classes/form/DataciteSettingsForm.inc.php
 *
 * Copyright (c) 2003-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class DataciteSettingsForm
 * @ingroup plugins_importexport_datacite_classes_form
 *
 * @brief Form for journal managers to setup the DataCite plug-in.
 */


import('plugins.importexport.datacite.classes.form.DOIExportSettingsForm');

class DataciteSettingsForm extends DOIExportSettingsForm {

	//
	// Constructor
	//
	/**
	 * Constructor
	 * @param $plugin DataciteExportPlugin
	 * @param $journalId integer
	 */
	function DataciteSettingsForm(&$plugin, $journalId) {
		// Configure the object.
		parent::DOIExportSettingsForm($plugin, $journalId);

		// Add form validation checks.
		// The username is used in HTTP basic authentication and according to RFC2617 it therefore may not contain a colon.
		$this->addCheck(new FormValidatorRegExp($this, 'username', FORM_VALIDATOR_OPTIONAL_VALUE, 'plugins.importexport.datacite.settings.form.usernameRequired', '/^[^:]+$/'));
	}


	//
	// Implement template methods from DOIExportSettingsForm
	//
	/**
	 * @see DOIExportSettingsForm::getFormFields()
	 */
	function getFormFields() {
		return array(
			'username' => 'string',
			'password' => 'string'
		);
	}
}

?>
