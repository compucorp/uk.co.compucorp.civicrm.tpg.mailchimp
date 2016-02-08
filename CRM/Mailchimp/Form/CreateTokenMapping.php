<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Mailchimp_Form_CreateTokenMapping extends CRM_Core_Form {
  function buildQuickForm() {
    $this->add('text', 'mailchimp_token', 'Mailchimp Token', '', true);
    $this->add('text', 'civicrm_token', 'CiviCRM Token', '', true);

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  function postProcess() {
    $values = $this->exportValues();

    $mapping = new CRM_Mailchimp_BAO_TokenMapping();
    $mapping->civicrm_token = $values['civicrm_token'];
    $mapping->mailchimp_token = $values['mailchimp_token'];
    $mapping->save();

    CRM_Core_Session::setStatus(ts('Token mapping has been added'));
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/mailchimp/token-mappings'));

    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }
}
