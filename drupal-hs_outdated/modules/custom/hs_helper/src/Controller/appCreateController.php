<?php

namespace Drupal\hs_helper\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hs_helper\Utility\HsRestHelper;
use Drupal\Core\Datetime;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Mail\MailManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class appCreateController  extends ControllerBase {
  
  private $hsRestHelper;
  public function __construct(HsRestHelper $hsRestHelper){
    $this->hsRestHelper = $hsRestHelper;
  }
  public function create_node(){
    /* start time*/
    $start_date = new \DateTime('2017-10-10 13:20:00');
    $start_date_timestamp = $start_date->getTimestamp();
    /* end time */
    $end_date = new \DateTime('2017-10-10 14:20:00');
    $end_date_timestamp = $end_date->getTimestamp();
    /* host */
    $host =  \Drupal::currentUser()->id();
    /* attendee */
    $attendee = [11,12];
    /* duration */
    $duration = 60;
    /* message */
    $message = 'Hi Testing Message';
    /* notify user */
    $notify_user = [];
    $data = $this->hsRestHelper->createApp($start_date_timestamp,$end_date_timestamp,$host,$attendee,$duration,$message,$notify_user);
     if($data){
      $this->sendEmailToUsers();
      return array("#markup" => "@@".$data);    
    }else{
      return array("#markup" => "node not created.");
    }
  }
  
  public function sendEmailToUsers(){
    $langcode = \Drupal::currentUser()->getPreferredLangcode();
    $mailManager = \Drupal::service('plugin.manager.mail');
    $module = 'hs_helper';
    $key = 'schedule_app';
    $to = 'aher.ujwala@gmail.com';//\Drupal::currentUser()->getEmail();
    $params['reply-to'] = $to;
    $params['message'] = 'testing';
    $send = true;
    $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);
  }
  public function displayData($id){
    $data = $this->hsRestHelper->getUserData($id);
    if($data){
      return array("#markup" => "@@".$id);
    }else{
      return array("#markup" => "user not exist.");
    }
  }
  
  public static function create(ContainerInterface $container){
    $hsRestHelper = $container->get('hs_helper.functions');
    return new static ($hsRestHelper);
  }
}
