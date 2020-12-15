<?php

use CRM_EventsExtras_SettingsManager as SettingsManager;

/**
 * Class for Event Fee BuildForm Hook
 */
class CRM_EventsExtras_Hook_BuildForm_EventFee extends CRM_EventsExtras_Hook_BuildForm_BaseEvent {

  /**
   * CRM_EventsExtras_Hook_BuildForm_EventFee constructor.
   */
  public function __construct() {
    parent::__construct(SettingsManager::EVENT_FEE);
  }

  /**
   * Hides options on the Event Fee page
   *
   * @param string $formName
   * @param CRM_Event_Form_ManageEvent_Fee $form
   */
  public function handle($formName, &$form) {
    if (!$this->shouldHandle($formName, CRM_Event_Form_ManageEvent_Fee::class)) {
      return;
    }
    $this->buildForm($formName, $form);
  }

  private function buildForm($formName, &$form) {
    $this->setDefaults($form);
  }

  private function setDefaults(&$form) {
    $defaults = [];

    $showPaymentProcessor = SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION'];
    $paymentProcessorDefault = SettingsManager::SETTING_FIELDS['PAYMENT_PROCESSOR_SELECTION_DEFAULT'];
    $settings = [$showPaymentProcessor, $paymentProcessorDefault];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showPaymentProcessor] == 0) {
      $defaultSettingString = implode(CRM_Core_DAO::VALUE_SEPARATOR, $settingValues[$paymentProcessorDefault]);
      $paymentProcessorDefaultValue = (array_fill_keys(explode(CRM_Core_DAO::VALUE_SEPARATOR, $defaultSettingString), '1'));
      $defaults['payment_processor'] = $paymentProcessorDefaultValue;
      $this->hideField('payment_processor');
    }

    $showCurrency = SettingsManager::SETTING_FIELDS['CURRENCY'];
    $settings = [$showCurrency];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showCurrency] == 0) {
      $this->hideField('currency');
    }

    $showPayLater = SettingsManager::SETTING_FIELDS['PAY_LATER_OPTION'];
    $settings = [$showPayLater];
    $settingValues = SettingsManager::getSettingsValue($settings);
    if ($settingValues[$showPayLater] == 0) {
      $this->hideField('is_pay_later');
      $this->hideField('pay_later_text');
      $this->hideField('pay_later_receipt');
      $this->hideField('is_billing_required');
    }

    $form->setDefaults($defaults);

  }

}
