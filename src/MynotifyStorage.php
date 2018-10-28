<?php

namespace Drupal\mynotify;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;


/**
 * Class MynotifyStorage
 * @package Drupal\mynotify
 */
class MynotifyStorage extends SqlContentEntityStorage implements MynotifyStorageInterface {


  /**
   * {@inheritdoc}
   */
  public function getMynotifiesNotNotifiedByProductId($product_id = NULL) {
    $mynotifies = $this->loadByProperties([
      'product_id' => $product_id,
      'notified' => 0
    ]);
    return $mynotifies;
  }


}
