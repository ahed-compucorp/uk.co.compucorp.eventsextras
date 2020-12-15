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
    $pendingParticipantExpirationDefault = SettingsManager::SETTING_FIELDS['PENDING_PARTICIPANT_EXPIRATION_DEFAULT'];
    $settings = [$showPendingParticipantExpiration, $pendingParticipantExpirationDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showPendingParticipantExpiration] == 0) {
      $defaults['expiration_time'] = $settingValues[$pendingParticipantExpirationDefault];
      $this->hideField('expiration_time');
    }

    $showAllowSelfServiceAction = SettingsManager::SETTING_FIELDS['ALLOW_SELF_SERVICE'];
    $allowSelfServiceActionDefault = SettingsManager::SETTING_FIELDS['ALLOW_SELF_SERVICE_DEFAULT'];
    $timeLimit = SettingsManager::SETTING_FIELDS['ALLOW_SELF_SERVICE_DEFAULT_TIME_LIMIT'];
    $settings = [$showAllowSelfServiceAction, $allowSelfServiceActionDefault, $timeLimit];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showAllowSelfServiceAction] == 0) {
      $defaults['allow_selfcancelxfer'] = $settingValues[$allowSelfServiceActionDefault];
      $defaults['selfcancelxfer_time'] = $settingValues[$timeLimit];
      $this->hideField('allow_selfcancelxfer');
      $this->hideField('selfcancelxfer_time');
    }

    $showRegisterMultipleParticipants = SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS'];
    $registerMultipleParticipantsDefault = SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT'];
    $maximumParticipant = SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT_MAXIMUM_PARTICIPANT'];
    $allowSameParticipantEmailsDefault = SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_ALLOW_SAME_PARTICIPANT_EMAILS_DEFAULT'];
    $settings = [$showRegisterMultipleParticipants, $registerMultipleParticipantsDefault, $maximumParticipant, $allowSameParticipantEmailsDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showRegisterMultipleParticipants] == 0) {
      $defaults['is_multiple_registrations'] = $settingValues[$registerMultipleParticipantsDefault];
      $defaults['max_additional_participants'] = $settingValues[$maximumParticipant];
      $defaults['allow_same_participant_emails'] = $settingValues[$allowSameParticipantEmailsDefault];
      $this->hideField('is_multiple_registrations');
      $this->hideField('max_additional_participants');
      $this->hideField('allow_same_participant_emails');
    }

    $form->setDefaults($defaults);

  }

}
