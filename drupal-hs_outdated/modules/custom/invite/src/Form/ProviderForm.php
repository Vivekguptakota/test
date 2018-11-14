<?php

namespace Drupal\invite\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\hs_sms\Controller\hsSendSms;


use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\InvokeCommand;

/**
 * Class ProviderForm.
 *
 * @package Drupal\invite\Form
 */
class ProviderForm extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'provider_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    global $base_url;
    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', "group_type");
    $tids = $query->execute();
    $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);
    $service_types = [0 => 'Type'];
    foreach($terms as $term_key => $term_value){
      $service_types[$term_value->get('tid')->value] = strip_tags($term_value->get('description')->value);
    }
    $form['#cache']['max-age'] = 0;
    $form['#attached']['library'][] = 'core/drupal.ajax';
    $form['#attached']['library'][] = 'core/drupal.dialog';
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $form['#attached']['library'][] = 'invite/invite.provider_form.invite';

    $form['app_type'] = [
      '#type' => 'select',
      '#options' => $service_types,
    ];

    $form['recipient'] = [
      '#type' => 'textfield',
      '#prefix' => '<div id="user-email-result"></div>',
      '#size' => 1000,
      '#maxlength' => 1000,
      '#attributes' => [
        'placeholder' => t('Enter recipient email or cell'),
      ],
    ];

    $form['date'] = [
      '#type' => 'date',
      '#size' => 10,
      '#min' => 'now',
    //  '#default_value' => $app_date,
       '#attributes' => [
        'placeholder' => t('Date'),
      ],
    ];

    $form['starttime'] = [
      '#type' => 'textfield',
      '#maxlength' => 10,
      '#size' => 10,
    //  '#default_value' => $app_time,
      '#attributes' => [
        'placeholder' => t('Time'),
      ],
    ];

    $form['endtime'] = [
      '#type' => 'textfield',
      '#maxlength' => 10,
      '#size' => 10,
    ];

    $form['duration'] = [
      '#type' => 'textfield',
      '#maxlength' => 10,
      '#size' => 10,
      '#default_value'=> 15,
    ];

    if (\Drupal::currentUser()->isAnonymous()) {
      $html= '<a id="code" href="'. $base_url .'/user/login" class="use-ajax button button btn-primary btn hidden" data-dialog-type="modal">Send invite</a>';
    }
    else{
      $html= '<a id="code" href="'. $base_url .'/form/send-invite" class="use-ajax button btn-primary btn inactive hidethis" data-dialog-type="modal">Send invite</a>';
    }
    $html .= '<div id="cust_fname" class="hidethis"></div>';
    $html .= '<div id="cust_lname" class="hidethis"></div>';
    $html .= '<div id="cust_dob" class="hidethis"></div>';
    $html .= '<div id="cust_mrn" class="hidethis"></div>';

    $form['submit'] = [
      '#type' => 'button',
      '#value' => t('Send'),
      '#ajax' => array(
        'callback' => '::checkUserMailAjax',
        'wrapper' => 'user-email-result',
      ),
      '#attributes' => [
        'class' => array('provider-form', 'use-ajax'),
      ],
      '#suffix' => $html,
    ];
    return $form;
  }


function checkUserMailAjax(array &$form, FormStateInterface &$form_state) {
  $ajax_response = new AjaxResponse();
  echo "<pre>";print_r($form_state->getValues());echo "</pre>";
  /*$tempstore = \Drupal::service('user.private_tempstore')->get('invite');
  $tempstore->set('app_recp', $form_state->getValue('recipient'));
  $tempstore->set('app_date', $form_state->getValue('date'));
  $tempstore->set('app_time', $form_state->getValue('starttime'));
  $tempstore->set('app_duration', ($form_state->getValue('duration'))?:15);
  $d = ($form_state->getValue('duration'))?:15;
  if(\Drupal::service('path.matcher')->isFrontPage()){
    $home = TRUE;
  }
  else{
    $home = FALSE;
  }
  user_cookie_save(array('home' => $home));

  $app_startdatetime = $form_state->getValue('date').' '.str_replace(' ', '', $form_state->getValue('starttime'));
  $app_startdate = date('Y-m-d', strtotime($form_state->getValue('date')));
  $app_starttime = $form_state->getValue('starttime');
  $app_enddate = date("Y-m-d", strtotime($app_startdatetime)+(60*$d));
  $app_endtime = date("g:i A", strtotime($app_startdatetime)+(60*$d));
  $request_time = \Drupal::time()->getCurrentTime();

  $valid = array();
  $invalid = array();
  $check_email = 1;
  $check_phone = 1;
  $check_date = 1;
  $check_time = 1;
  $mail_list_custom = explode(',', $form_state->getValue('recipient'));
  $custom_user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
  $mail_list = array();
  $cell_number = array();

  foreach ($mail_list_custom as $value) {
    $new_value = str_replace('+', '', $value);
    if(is_numeric($new_value)){
      $cell_number[] = trim($new_value);
    }
    else{
      $mail_list[] = trim($value);
    }
  }

  if($form_state->getValue('recipient')){
    foreach ($mail_list as $value) {
      $email = trim($value);
      if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $user = user_load_by_mail($email);
        $custom_email = $custom_user->get('mail')->value;
        if($user && ($user->hasRole('administrator') || $custom_email == $email)){
          $list[] = $email;
        }
      }
      else {
        $invalid[] = $email;
      }
    }
    if (!empty($invalid)) {
      $check_email = 0;
      $text = 'Invalid email address found.';
      $color = '#a00';
      $ajax_response->addCommand(new InvokeCommand(NULL, 'disableSubmit'));
    }
    elseif (!empty($list)) {
      $check_email = 0;
      $text = 'You can not sent invitation to -'.implode(',', $list). ".";
      $color = '#a00';
      $ajax_response->addCommand(new InvokeCommand(NULL, 'disableSubmit'));
    }
    else {
      if(!empty($cell_number)){
        foreach($cell_number as $p_key => $p_value){
          if(strlen($p_value) < 11){
            $check_phone = 0;
            $text .= 'Cell number must have 11 digit include country code.';
            $color = '#a00';
            $ajax_response->addCommand(new InvokeCommand(NULL, 'disableSubmit'));
          }
        }
      }
    }
  }
  else{
    $check_email = 0;
    $text = 'This is required field.';
    $color = '#a00';
    $ajax_response->addCommand(new InvokeCommand(NULL, 'disableSubmit'));
  }
  //echo "<pre>";print_r($form_state->getValues());die;
  if(!$form_state->getValue('date')){
    $check_date = 0;
    $text .= ' Select the session date';
    $ajax_response->addCommand(new InvokeCommand(NULL, 'disableSubmit'));
    $color = '#a00';
  }
  else{
    $check_date = 1;
  }
  if(!$form_state->getValue('starttime')){
    $check_time = 0;
    $text .= ' Set the session Time';
    $color = '#a00';
  }
  elseif($request_time > strtotime($app_startdatetime)){
    $check_time = 0;
    $text .= ' Time should be in future.';
    $color = '#a00';
  }
  else{
    $check_time = 1;
  }
  if($check_email == 1 && $check_date == 1 && $check_time == 1 && $check_phone == 1){ //die("--------");
    if(count($mail_list)==1){
      $user1 = user_load_by_mail($mail_list[0]);
      if($user1) {
        $first_name = $user1->get('field_first_name')->value;
        $last_name = $user1->get('field_last_name')->value;
        $date_of_birth = date('Y-m-d', strtotime($user1->get('field_date_of_birth')->value));
        $field_mrn = $user1->get('field_mrn')->value;

        $ajax_response->addCommand(new HtmlCommand('#cust_fname', $first_name));
        $ajax_response->addCommand(new HtmlCommand('#cust_lname', $last_name));
        $ajax_response->addCommand(new HtmlCommand('#cust_dob', $date_of_birth));
        $ajax_response->addCommand(new HtmlCommand('#cust_mrn', $field_mrn));
      }
    }
    $_SESSION['forced'] = TRUE;
    $ajax_response->addCommand(new InvokeCommand('#code', 'click'));
    $ajax_response->addCommand(new InvokeCommand(NULL, 'passDates', [$app_startdate, $app_starttime, $app_enddate, $app_endtime]));
  }
  else{
    $ajax_response->addCommand(new HtmlCommand('#user-email-result', $text));
    $ajax_response->addCommand(new InvokeCommand('#user-email-result', 'css', array('color', $color)));
    $ajax_response->addCommand(new InvokeCommand('#user-email-result', 'css', array('border', $color)));
  }

  return $ajax_response;*/
}

  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    global $base_url;

    parent::submitForm($form, $form_state);
    //hsSendSms::sendCustomSms('+918097760260', 'Invitation sent for the date: '. $form_state->getValue("date") .'at: ' .$form_state->getValue("time") . $l);
  }
}
