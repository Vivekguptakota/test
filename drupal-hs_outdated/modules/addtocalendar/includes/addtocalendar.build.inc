<?php

/**
 * @file
 * Contains preprocess helper function.
 */

use \Drupal\Core\Entity\Entity\EntityViewDisplay;
use \Drupal\Core\Datetime\DrupalDateTime;

/**
 * Helper function to preprocess datetime field.
 */
function _addtocalendar_preprocess_field(&$variables) {
  $entity = $variables['element']['#object'];
  $view_mode = $variables['element']['#view_mode'];
  $field_name = $variables['element']['#field_name'];

  // Get the field formatter settings...
  $entity_display = EntityViewDisplay::collectRenderDisplay($entity, $view_mode);
  $field_display = $entity_display->getComponent($field_name);

  if (!empty($field_display['third_party_settings']['addtocalendar'])) {
    $build['addtocalendar'] = [];
    $settings = $field_display['third_party_settings']['addtocalendar'];
    $style = [];
    if ($settings['addtocalendar_show']) {
      $token_service = \Drupal::token();
      $token_options = ['langcode' => $entity->language()->getId(), 'callback' => '', 'clear' => TRUE];
      $timeZone = date_default_timezone_get();
      $values = $entity->{$field_name}->getValue();
      $delta = !empty($settings['addtocalendar_settings']['delta']) ? $settings['addtocalendar_settings']['delta'] : 0;
      if (empty($values[$delta]['value'])) {
        return;
      }

      // Unsetting values other than delta if single value is selected.
      if ($settings['addtocalendar_settings']['multiple_value'] == 1) {
        $temp = $values[$delta];
        unset($values);
        $values = array($delta => $temp);
        unset($temp);
      }
      foreach ($values as $index => $date_val) {
        $date = new DrupalDateTime(preg_replace('/T/', ' ', $values[$index]['value']), 'UTC');
        if (!empty($values[$index]['end_value']) && isset($values[$index]['end_value'])) {
          $end_date = new DrupalDateTime(preg_replace('/T/', ' ', $values[$index]['end_value']), 'UTC');
        }

        $build['addtocalendar'][$index]['atc_date_start'] = [
          '#type' => 'html_tag',
          '#tag' => 'var',
          '#value' => $date->format('Y-m-d H:i:s', ['timezone' => $timeZone]),
          '#attributes' => [
            'class' => 'atc_date_start',
          ],
        ];
        $info = [
          'atc_date_end',
          'atc_title',
          'atc_description',
          'atc_location',
          'atc_organizer',
          'atc_organizer_email',
        ];
        foreach ($info as $value) {
          switch ($settings['addtocalendar_settings'][$value]['field']) {
            case 'token':
              $class_value = $settings['addtocalendar_settings'][$value]['tokenized'];
              $class_value = $token_service->replace($class_value, array('node' => $entity), $token_options);
              break;

            case 'title':
              $class_value = $entity->getTitle();
              break;

            default:
              $field = $settings['addtocalendar_settings'][$value]['field'];
              if (strip_tags($entity->{$field}->getFieldDefinition()
                  ->getType()) == 'daterange'
              ) {
                $class_value = strip_tags($entity->{$field}->end_value);
              }
              else {
                $class_value = strip_tags($entity->{$field}->value);
              }
              break;
          }
          $build['addtocalendar'][$index][$value] = [
            '#type' => 'html_tag',
            '#tag' => 'var',
            '#value' => $class_value,
            '#attributes' => [
              'class' => $value,
            ],
          ];
        }

        // Assign end date the value of start date if no end date is present and its not a daterange field.
        if (empty($end_date)) {
          $build['addtocalendar'][$index]['atc_date_end']['#value'] = (!empty($build['addtocalendar'][$index]['atc_date_end']['#value'])) ? $build['addtocalendar'][$index]['atc_date_end']['#value'] : $build['addtocalendar'][$index]['atc_date_start']['#value'];
          $build['addtocalendar'][$index]['atc_date_end']['#value'] = (!empty($build['addtocalendar'][$index]['atc_date_end']['#value'])) ? $build['addtocalendar'][$index]['atc_date_start']['#value'] : $date->format('Y-m-d H:i:s', ['timezone' => $timeZone]);
        }
        // Assign end date the value of end date if no end date is present and its a daterange field.
        else {
          $end_date_val = $end_date->__toString();
          $build['addtocalendar'][$index]['atc_date_end']['#value'] = (!empty($build['addtocalendar'][$index]['atc_date_end']['#value'])) ? $build['addtocalendar'][$index]['atc_date_end']['#value'] : $end_date_val;
          $build['addtocalendar'][$index]['atc_date_end']['#value'] = $end_date->format('Y-m-d H:i:s', ['timezone' => $timeZone]);
        }

        $build['addtocalendar'][$index]['atc_timezone'] = [
          '#type' => 'html_tag',
          '#tag' => 'var',
          '#value' => $timeZone,
          '#attributes' => [
            'class' => 'atc_timezone',
          ],
        ];

        $build['addtocalendar'][$index]['atc_privacy'] = [
          '#type' => 'html_tag',
          '#tag' => 'var',
          '#value' => $settings['addtocalendar_settings']['atc_privacy'],
          '#attributes' => [
            'class' => 'atc_privacy',
          ],
        ];

        $build['addtocalendar'][$index] = [
          '#type' => 'html_tag',
          '#tag' => 'span',
          '#value' => '<var class="atc_event">' . render($build['addtocalendar'][$index]) . '</var>',
          '#attributes' => [
            'class' => [
              'addtocalendar',
            ],
          ],
        ];

        if ($settings['addtocalendar_settings']['data_calendars']) {
          $value = '';
          foreach ($settings['addtocalendar_settings']['data_calendars'] as $key => $set) {
            if ($set) {
              $value .= $key . ', ';
            }
          }
          if ($value) {
            $build['addtocalendar'][$index]['#attributes']['data-calendars'] = $value;
          }
        }

        $build['addtocalendar'][$index]['#attributes']['data-secure'] = $settings['addtocalendar_settings']['data_secure'];
        // Styling.
        switch ($settings['addtocalendar_settings']['style']) {
          case 'blue':
            $style['class'] = 'atc-style-blue';
            $style['library'] = 'addtocalendar/blue';
            break;

          case 'glow_orange':
            $style['class'] = 'atc-style-glow-orange';
            $style['library'] = 'addtocalendar/glow_orange';
            break;
        }
        if (!empty($style)) {
          $build['addtocalendar'][$index]['#attributes']['class'][] = $style['class'];
          $variables['#attached']['library'][] = $style['library'];
        }
      }
    }
    // Setting #markup for all the date or daterange fields if multivalue is selected.
    if ($settings['addtocalendar_settings']['multiple_value'] == 2) {
      foreach ($variables['items'] as $k => $content) {
        if (isset($variables['items'][$k]['content']['#markup'])) {
          $variables['items'][$k]['content']['#markup'] .= render($build['addtocalendar'][$k]);
        }
        elseif (isset($variables['items'][$k]['content'])) {
          unset($variables['items'][$k]['content']['#theme']);
          $variables['items'][$k]['content']['#markup'] = $variables['items'][$k]['content']['#text'] . render($build['addtocalendar'][$k]);
        }
        $variables['#attached']['library'][] = 'addtocalendar/base';
      }
    }
    // Setting #markup for all the date or daterange fields if single value is selected.
    else {
      if (isset($variables['items'][$delta]['content']['#markup'])) {
        $variables['items'][$delta]['content']['#markup'] .= render($build['addtocalendar']);
      }
      elseif (isset($variables['items'][$delta]['content'])) {
        unset($variables['items'][$delta]['content']['#theme']);
        $variables['items'][$delta]['content']['#markup'] = $variables['items'][$delta]['content']['#text'] . render($build['addtocalendar']);
      }
      $variables['#attached']['library'][] = 'addtocalendar/base';
    }
  }
}
