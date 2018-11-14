<?php

/**
 * @file
 * Contains \Drupal\timezone_detect\Form\TimezoneDetectAdminForm.
 */

namespace Drupal\timezone_detect\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

class TimezoneDetectAdminForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'timezone_detect_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('timezone_detect.settings');

    foreach (Element::children($form) as $variable) {
      $config->set($variable, $form_state->getValue($form[$variable]['#parents']));
    }
    $config->save();

    if (method_exists($this, '_submitForm')) {
      $this->_submitForm($form, $form_state);
    }

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['timezone_detect.settings'];
  }

  public function buildForm(array $form, \Drupal\Core\Form\FormStateInterface $form_state) {

    $options = [
      TIMEZONE_DETECT_MODE_DEFAULT => t("Set timezone on login only if it is not yet set (recommended)"),
      TIMEZONE_DETECT_MODE_LOGIN => t("Update timezone on every login"),
      TIMEZONE_DETECT_MODE_ALWAYS => t("Update timezone whenever it changes"),
    ];
    // @FIXME
    // Could not extract the default value because it is either indeterminate, or
    // not scalar. You'll need to provide a default value in
    // config/install/timezone_detect.settings.yml and config/schema/timezone_detect.schema.yml.
    $form['timezone_detect_mode'] = [
      '#type' => 'radios',
      '#title' => t("When to set a user's timezone automatically"),
      '#default_value' => \Drupal::config('timezone_detect.settings')->get('timezone_detect_mode'),
      '#options' => $options,
      '#description' => t("By default, Timezone Detect sets a user's timezone on login if it is not yet set. Alternatively, you can have the module update the user's timezone automatically on every login or whenever their timezone changes; be aware that these later settings will overwrite any manual timezone selection that the user may make."),
    ];

    $form['timezone_detect_success_watchdog'] = [
      '#type' => 'checkbox',
      '#title' => t("Log successful events in watchdog"),
      '#default_value' => \Drupal::config('timezone_detect.settings')->get('timezone_detect_success_watchdog'),
      '#description' => t("By default, Timezone Detect will create a log entry every time it sets a user's timezone. This can create unnecessary noise in your log files so you are likely to want to disable this once you are confident the feature works."),
    ];

    return parent::buildForm($form, $form_state);
  }

}
