<?php

namespace Drupal\hs_helper\Utility;

/**
 * Provides helper to operate on arrays.
 */
class HsRestHelper {

  /**
   *  returns the user profile information.
   *
   * @param int $uid
   *   user id
   *
   * @return string
   *   The array of user profile information.
   */
  public static function getUser($uid) {
    if(is_array($uid)){
      $user_info = \Drupal::service('entity_type.manager')->getStorage('user')->loadMultiple($uid);
    }else{
      $user_info = \Drupal::service('entity_type.manager')->getStorage('user')->load($uid);  
    }
    
    
  }
  
  public static function getUserData($uid) {
    $user_data = array();
    $load_user = \Drupal::entityTypeManager()->getStorage('user')->load($uid);
    if($load_user){
      $first_name = $load_user->field_first_name->value;   
      $last_name = $load_user->field_last_name->value;
      $user_data['first_name'] = $first_name;
      $user_data['last_name'] = $last_name;    
    }
      return $user_data;  
  }
  /**
   * Create a node of 'appointment calendar'
   *
   * @param timestamp $start_date
   *      appoinment start date along with time
   * @param timestamp $end_date
   *    appointment end date along with time
   * @param int $host
   *    user id
   * @param int $attendees
   *    user id
   * @param text $message
   *    message for user
   * @param array $notify_user
   *    array of user ids
   *
   * @return int
   *   node id if node saved.
   */
  public function createApp($start_date,$end_date,$host,$attendees,$duration,$message = null,$notify_user = null){
    $language = \Drupal::languageManager()->getCurrentLanguage()->getId();
    if($notify_user){
      $video_link = TRUE;
    }else{
      $video_link = FALSE;
    }
    $node = \Drupal\node\Entity\Node::create(array(
    'type' => 'appointment_calendar',
    'title' => 'Appointment for '.$start_date."==".$end_date,
    'langcode' => $language,
    'uid' => \Drupal::currentUser()->id(),
    'status' => 1,
    'field_appointment_start_date' => [$start_date],
    'field_appointment_end_date' => [$end_date],
    'field_appointment_duration' => array($duration),
    'field_appointment_attendee_list' => $attendees,
    'field_appointment_notify_user' => $notify_user,
    'field_appointment_video_link' => $video_link,
    'field_appointment_message' => $message,
  ));
  $node->save();
  return $node->id();
  }
  
  function sendScheduleAppMail(){
    
  }
  
  public function getAttendeeList($node_id){
    $app_node = \Drupal::entityTypeManager()->getStorage('node')->load($node_id);
    if($app_node){
    $patient_list = $app_node->get('field_appointment_attendee_list')->getValue();      
    }else{
      $patient_list = [];
    }
    return $patient_list;    
  }
  
  public function phoneNumberExists($phone){
     \Drupal::service('page_cache_kill_switch')->trigger();
    $user_ids = \Drupal::entityTypeManager()
              ->getStorage('user')
              ->loadByProperties(['field_phone' => $phone]);                
    $user_data = array_keys($user_ids);
    return $user_data;
  }

}
