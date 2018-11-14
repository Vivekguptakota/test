<?php /**
 * @file
 * Contains \Drupal\timezone_detect\Controller\DefaultController.
 */

namespace Drupal\timezone_detect\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Default controller for the timezone_detect module.
 */
class DefaultController extends ControllerBase {

  public function timezone_detect_set_timezone() {
    $user = \Drupal::currentUser();

    // If they are logged in, set some data.
    if (!\Drupal::currentUser()->isAnonymous()) {
      // Check for $_POST data.
    // Timezone should be an IANA/Olson timezone id provided via $_POST.
      if (!isset($_POST['timezone'])) {
        \Drupal::logger('timezone_detect')->error('Attempting to set timezone for user @uid, but no timezone found in $_POST data; aborting.', [
          '@uid' => $user->uid
          ]);
        return '';
      }
      // Make sure we have a valid session token to prevent cross-site request forgery.
      if (!isset($_POST['token']) || !drupal_valid_token($_POST['token'])) {
        \Drupal::logger('timezone_detect')->error('Attempting to set timezone for user @uid, but session token in $_POST data is empty or invalid; aborting.', [
          '@uid' => $user->uid
          ]);
        return '';
      }

      $timezone = \Drupal\Component\Utility\SafeMarkup::checkPlain($_POST['timezone']);

      // Keep track of the last submitted timezone in case it's not valid so that
      // we don't keep POSTing it on every request.
      $_SESSION['timezone_detect']['current_timezone'] = $timezone;

      // Check valid timezone id.
      $zone_list = timezone_identifiers_list();
      if (!in_array($timezone, $zone_list)) {
        \Drupal::logger('timezone_detect')->error('Attempting to set timezone for user @uid to @timezone, but that does not appear to be a valid timezone id; aborting.', [
          '@uid' => $user->uid,
          '@timezone' => $timezone,
        ]);
        return '';
      }

      // Save timezone to account.
      $account = \Drupal::entityManager()->getStorage('user')->load($user->uid);
      $edit['timezone'] = $timezone;
      // @FIXME
      // user_save() is now a method of the user entity.
      // user_save($account, $edit);

      if (\Drupal::config('timezone_detect.settings')->get('timezone_detect_success_watchdog')) {
        \Drupal::logger('timezone_detect')->notice('Set timezone for user @uid to @timezone.', [
          '@uid' => $user->uid,
          '@timezone' => $timezone,
        ]);
      }
    }

    // Unset session flag regarldess of whether they are logged in or not to avoid
    // repeated attempts at this process that are likely to fail.
    unset($_SESSION['timezone_detect']['update_timezone']);
  }

}
