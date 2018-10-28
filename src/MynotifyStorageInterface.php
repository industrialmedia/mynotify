<?php

namespace Drupal\mynotify;

use Drupal\Core\Entity\ContentEntityStorageInterface;


/**
 * Interface MynotifyStorageInterface
 * @package Drupal\mynotify
 */
interface MynotifyStorageInterface extends ContentEntityStorageInterface {


  /**
   * Gets Mynotify[] not notified
   *
   * @param null $product_id
   * @return \Drupal\mynotify\MynotifyInterface[]
   */
  public function getMynotifiesNotNotifiedByProductId($product_id = NULL);


}
