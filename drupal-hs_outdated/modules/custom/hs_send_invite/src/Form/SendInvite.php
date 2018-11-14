<?php

namespace Drupal\hs_send_invite\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SendInvite.
 */
class SendInvite extends FormBase {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'send_invite';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['email_cell_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Email/Cell Number'),
      '#maxlength' => 64,
      '#size' => 64,
    ];
    $form['appointment_date'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Appointment Date'),
    ];
    $form['appointment_end_time'] = [
      '#type' => 'datetime',
      '#title' => $this->t('Appointment End Time'),
    ];
    $form['appointment_duration'] = [
      '#type' => 'select',
      '#title' => $this->t('Appointment Duration'),
      '#options' => ['15' => $this->t('15'), '30' => $this->t('30'), '45' => $this->t('45'), '60' => $this->t('60')],
      '#size' => 5,
    ];
    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First Name'),
      '#maxlength' => 64,
      '#size' => 64,
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last Name'),
      '#maxlength' => 64,
      '#size' => 64,
    ];
    $form['dob'] = [
      '#type' => 'date',
      '#title' => $this->t('DOB'),
    ];
    $form['mrn'] = [
      '#type' => 'textfield',
      '#title' => $this->t('MRN'),
      '#maxlength' => 64,
      '#size' => 64,
    ];
    $form['message'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Message'),
    ];
    $form['notify_email_address'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Notify Email address'),
      '#maxlength' => 64,
      '#size' => 64,
    ];
    $form['include_session_link'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Include session link'),
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
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
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }

  }

}
