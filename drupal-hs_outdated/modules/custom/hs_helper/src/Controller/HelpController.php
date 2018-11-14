<?php

namespace Drupal\hs_helper\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\hs_helper\Utility\HsRestHelper;
use Symfony\Component\DependencyInjection\ContainerInterface;

class HelpController  extends ControllerBase {
  
  private $hsRestHelper;
  public function __construct(HsRestHelper $hsRestHelper){
    $this->hsRestHelper = $hsRestHelper;
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
