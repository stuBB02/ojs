<?php

/**
 * @file plugins/generic/lucene/classes/form/LuceneSettingsForm.inc.php
 *
 * Copyright (c) 2003-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class LuceneSettingsForm
 * @ingroup plugins_generic_lucene_classes_form
 *
 * @brief Form to configure Lucene/Solr search.
 */


import('lib.pkp.classes.form.Form');

// These are the first few letters of an md5 of '##placeholder##'.
// FIXME: Any better idea how to prevent a password clash?
define('LUCENE_PLUGIN_PASSWORD_PLACEHOLDER', '##5ca39841ab##');

class LuceneSettingsForm extends Form {

	/** @var $plugin LucenePlugin */
	var $_plugin;

	/**
	 * Constructor
	 * @param $plugin LucenePlugin
	 */
	function LuceneSettingsForm(&$plugin) {
		$this->_plugin =& $plugin;
		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');

		$this->addCheck(new FormValidatorUrl($this, 'searchEndpoint', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.lucene.settings.searchEndpointRequired'));
		// The username is used in HTTP basic authentication and according to RFC2617 it therefore may not contain a colon.
		$this->addCheck(new FormValidatorRegExp($this, 'username', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.lucene.settings.usernameRequired', '/^[^:]+$/'));
		$this->addCheck(new FormValidator($this, 'password', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.lucene.settings.passwordRequired'));
		$this->addCheck(new FormValidator($this, 'instId', FORM_VALIDATOR_REQUIRED_VALUE, 'plugins.generic.lucene.settings.instIdRequired'));
	}


	//
	// Implement template methods from Form.
	//
	/**
	 * @see Form::initData()
	 */
	function initData() {
		$plugin =& $this->_plugin;
		foreach ($this->_getFormFields() as $fieldName) {
			$this->setData($fieldName, $plugin->getSetting(0, $fieldName));
		}
		// We do not echo back the real password.
		$this->setData('password', LUCENE_PLUGIN_PASSWORD_PLACEHOLDER);
	}

	/**
	 * @see Form::readInputData()
	 */
	function readInputData() {
		$this->readUserVars($this->_getFormFields());
		$request = PKPApplication::getRequest();
		$password = $request->getUserVar('password');
		if ($password === LUCENE_PLUGIN_PASSWORD_PLACEHOLDER) {
			$plugin =& $this->_plugin;
			$password = $plugin->getSetting(0, 'password');
		}
		$this->setData('password', $password);
	}

	/**
	 * @see Form::execute()
	 */
	function execute() {
		$plugin =& $this->_plugin;
		$formFields = $this->_getFormFields();
		$formFields[] = 'password';
		foreach($formFields as $formField) {
			$plugin->updateSetting(0, $formField, $this->getData($formField), 'string');
		}
	}


	//
	// Private helper methods
	//
	/**
	 * Return the field names of this form
	 * and the corresponding default settings.
	 * @return array
	 */
	function _getFormFields() {
		return array('searchEndpoint', 'username', 'instId');
	}
}

?>
