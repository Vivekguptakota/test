<?php

namespace Drupal\hs_helper;
use Drupal\Component\Serialization\Json;

/**
 * Class HsLogin.
 */
class HsLogin {

  /**
   * Drupal\Component\Serialization\Json definition.
   *
   * @var \Drupal\Component\Serialization\Json
   */
  protected $serializationJson;
  /**
   * Constructs a new HsLogin object.
   */
  public function __construct(Json $serialization_json) {
    $this->serializationJson = $serialization_json;
  }

}
