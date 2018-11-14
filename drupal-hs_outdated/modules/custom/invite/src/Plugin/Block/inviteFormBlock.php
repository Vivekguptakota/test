<?php

namespace Drupal\invite\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;
use \Drupal\Core\Link;

/**
 * Provides a 'Send invites' Block.
 *
 * @Block(
 *   id = "provider_form",
 *   admin_label = @Translation("Invite Block"),
 * )
 */
class inviteFormBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\invite\Form\ProviderForm');
    return $form;
  }
}
