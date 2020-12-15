<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for Event Online Registartion BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventRegistration extends CRM_EventsExtras_Hook_BuildForm_BaseEvent {

  /**
   * CRM_EventsExtras_Hook_BuildForm_EventRegistration constructor.
   */
  public function __construct() {
    parent::__construct(SettingsManager::EVENT_REGISTRATION);
  }

  /**
   * Hides options on the Event Registration page
   *
   * @param string $formName
   * @param CRM_Event_Form_ManageEvent_Registration $form
   */
  public function handle($formName, &$form) {
    if (!$this->shouldHandle($formName, CRM_Event_Form_ManageEvent_Registration::class)) {
      return;
    }
    $this->buildForm($formName, $form);
  }

  private function buildForm($formName, &$form) {
    $this->setDefaults($form);
  }

  private function setDefaults(&$form) {
    $defaults = [];

    $showPendingParticipantExpiration = SettingsManager::SETTING_FIELDS['PENDING_PARTICIPANT_EXPIRATION'];
    $settings = [$showPendingParticipantExpiration];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showPendingParticipantExpiration] == 0) {
      $this->hideField('expiration_time');
    }

    $showAllowSelfServiceAction = SettingsManager::SETTING_FIELDS['ALLOW_SELF_SERVICE'];
    $settings = [$showAllowSelfServiceAction];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showAllowSelfServiceAction] == 0) {
      $this->hideField('allow_selfcancelxfer');
      $this->hideField('selfcancelxfer_time');
    }

    $showRegisterMultipleParticipants = SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS'];
    $settings = [$showRegisterMultipleParticipants];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showRegisterMultipleParticipants] == 0) {
      $this->hideField('is_multiple_registrations');
      $this->hideField('max_additional_participants');
    }

    $form->setDefaults($defaults);

  }

}
