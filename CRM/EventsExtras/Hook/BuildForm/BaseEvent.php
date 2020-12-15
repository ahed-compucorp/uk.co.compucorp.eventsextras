<?php

use CRM_EventsExtras_ExtensionUtil as E;
use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Abstract class for BuildForm Hook
 */
abstract class CRM_EventsExtras_Hook_BuildForm_BaseEvent {

  /**
   * Event tab to display on the form
   * @var eventTab
   */
  protected $eventTab;

  /**
   * Event tab and class map
   * @var eventTabAndClassMap
   */
  protected $eventTabAndClassMap = [
    SettingsManager::EVENT_INFO => 'crm-event-manage-eventinfo',
    SettingsManager::EVENT_FEE => 'crm-event-manage-fee',
    SettingsManager::EVENT_REGISTRATION => 'crm-event-manage-registration',
  ];

  /**
   * Constractor for BuildForm class
   *
   * @param string $eventTab
   *
   */
  protected function __construct($eventTab) {
    $this->eventTab = $eventTab;
  }

  /**
   * Hides options on the page
   *
   * @param string $formName
   * @param CRM_Core_Form $form
   */
  abstract public function handle($formName, &$form);

  /**
   * Checks if the hook should be handled.
   *
   * @param string $formName
   * @param class $formClass
   *
   * @return bool
   */
  protected function shouldHandle($formName, $formClass) {
    if ($formName === $formClass) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * fuction to hide fields based on settings
   *
   * @param string $id
   *
   */
  protected function hideField($id) {

    $class = $this->eventTabAndClassMap[$this->eventTab] . '-form-block-' . $id;
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $('tr[class={$class}').hide();
      });
    ");

  }

}
