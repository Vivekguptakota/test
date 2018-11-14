<?php

namespace Drupal\rrg_test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
/**
 * Class rrg_test.
 */
class rrg_test extends ControllerBase {

  /**
   * Nodecheck.
   *
   * @return string
   *   Return Hello string.
   */
  public function nodecheck($key, $id) {
    $site_config = \Drupal::config('system.site');
    $siteapikey = $site_config->get('siteapikey');
    if($siteapikey == $key){
      $serializer = \Drupal::service('serializer');
      $node = Node::load($id);
      $data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);
      return [
        '#type' => 'markup',
        '#markup' => $data,
      ]; 
    }
    else{
      throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException(); 
    }
  }
}
