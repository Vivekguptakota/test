<?php

namespace Drupal\hs_calander\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class Test.
 */
class Test extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'hs_calander.test',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'test';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('hs_calander.test');
    $form['site_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site Api Key'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('site_api_key'),
    ];
    return parent::buildForm($form, $form_state);
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
    parent::submitForm($form, $form_state);

    $this->config('hs_calander.test')
      ->set('site_api_key', $form_state->getValue('site_api_key'))
      ->save();
  }

}
