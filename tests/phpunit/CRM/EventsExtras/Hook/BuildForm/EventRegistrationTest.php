<?php


use CRM_EventsExtras_Test_Fabricator_Setting as SettingFabricator;
use CRM_EventsExtras_SettingsManager as SettingsManager;

require_once __DIR__ . '/../../../../BaseHeadlessTest.php';

/**
 * Class CRM_EventsExtras_Hook_BuildForm_EventRegistrationTest
 *
 * @group headless
 */
class CRM_EventsExtras_Hook_BuildForm_EventRegistrationTest extends BaseHeadlessTest {

  /**
   * @var CRM_Event_Form_ManageEvent_EventRegistration
   */
  private $eventRegistrationForm;

  public function setUp() {
    $formController = new CRM_Core_Controller();

    $insertEvent = "
INSERT INTO `civicrm_event` (`id`, `title` , `summary` , `description` , `event_type_id` , `participant_listing_id` , `is_public` , `start_date` , `end_date` , `max_participants` , `event_full_text` , `is_map` , `is_active` , `default_role_id` , `has_waitlist` , `is_template` , `created_id` , `created_date` , `campaign_id` , `is_share` ) VALUES (1, 'Event Sample' ,  NULL ,  NULL ,  3 ,  NULL ,  0 ,  20201201 ,  NULL ,  NULL , 'This event is currently full.' ,  0 ,  1 ,  2 ,  0 ,  0 ,  1 ,  20201214103800 ,  NULL ,  0 );
";

    CRM_Core_DAO::executeQuery($insertEvent);

    $this->eventRegistrationForm = new CRM_Event_Form_ManageEvent_Registration();
    $this->eventRegistrationForm->controller = $formController;
    $this->eventRegistrationForm->set('id', 1);
    $this->eventRegistrationForm->buildForm();
  }

  public function testSetDefault() {

    SettingFabricator::fabricate([
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS'] => 0,
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT'] => 1,
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_DEFAULT_MAXIMUM_PARTICIPANT'] => 5,
      SettingsManager::SETTING_FIELDS['REGISTER_MULTIPLE_PARTICIPANTS_ALLOW_SAME_PARTICIPANT_EMAILS_DEFAULT'] => 1,
    ]);

    $eventRegistrationFormHook = new CRM_EventsExtras_Hook_BuildForm_EventRegistration();
    $eventRegistrationFormHook->handle('CRM_Event_Form_ManageEvent_Registration', $this->eventRegistrationForm);

    $this->assertEquals(1, $this->eventRegistrationForm->_defaultValues['is_multiple_registrations']);
    $this->assertEquals(5, $this->eventRegistrationForm->_defaultValues['max_additional_participants']);

    $this->assertEquals(1, $this->eventRegistrationForm->_defaultValues['allow_same_participant_emails']);
  }

}
