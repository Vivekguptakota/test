<?php

namespace Drupal\hs_cal\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class HsCalender.
 */
class HsCalender extends ControllerBase {

  /**
   * Fullview.
   *
   * @return string
   *   Return Hello string.
   */
  public function fullview() {
    return [
      '#theme' => 'hs_cal',
      '#newtest' => 'hello this is custom'
    ];
  }

}
