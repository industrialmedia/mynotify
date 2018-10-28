<?php

namespace Drupal\mynotify;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a Mynotify entity.
 * @ingroup mynotify
 */
interface MynotifyInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {


  /**
   * Gets the mynotify creation timestamp.
   *
   * @return int
   *   Creation timestamp of the mynotify.
   */
  public function getCreatedTime();


  /**
   * Gets the product_id.
   *
   * @return int
   *   product_id of the mynotify.
   */
  public function getProductId();


  /**
   * Sets the mynotify product_id.
   *
   * @param int $product_id
   *   The mynotify product_id.
   *
   * @return \Drupal\mynotify\MynotifyInterface
   *   The called mynotify entity.
   */
  public function setProductId($product_id);



  /**
   * Gets the Product.
   *
   * @return \Drupal\commerce_product\Entity\ProductInterface
   *   Product of the mynotify.
   */
  public function getProduct();


  /**
   * Gets the mame.
   *
   * @return string
   *   mame of the mynotify.
   */
  public function getName();


  /**
   * Sets the mynotify mame.
   *
   * @param string $name
   *   The mynotify mame.
   *
   * @return \Drupal\mynotify\MynotifyInterface
   *   The called mynotify entity.
   */
  public function setName($name);


  /**
   * Gets the mail.
   *
   * @return string
   *   mail of the mynotify.
   */
  public function getMail();

  /**
   * Sets the mynotify mail.
   *
   * @param string $mail
   *   The mynotify mail.
   *
   * @return \Drupal\mynotify\MynotifyInterface
   *   The called mynotify entity.
   */
  public function setMail($mail);



  /**
   * Gets the phone.
   *
   * @return string
   *   phone of the mynotify.
   */
  public function getPhone();


  /**
   * Sets the mynotify phone.
   *
   * @param string $phone
   *   The mynotify phone.
   *
   * @return \Drupal\mynotify\MynotifyInterface
   *   The called mynotify entity.
   */
  public function setPhone($phone);



  /**
   * Gets the notified.
   *
   * @return int
   *   notified of the mynotify.
   */
  public function getNotified();


  /**
   * Sets the mynotify notified.
   *
   * @param int $notified
   *   The mynotify notified.
   *
   * @return \Drupal\mynotify\MynotifyInterface
   *   The called mynotify entity.
   */
  public function setNotified($notified);




  /**
   * Gets the text.
   *
   * @return string
   *   text of the mynotify.
   */
  public function getText();



  /**
   * Sets the mynotify text.
   *
   * @param string $text
   *   The mynotify text.
   *
   * @return \Drupal\mynotify\MynotifyInterface
   *   The called mynotify entity.
   */
  public function setText($text);




}


