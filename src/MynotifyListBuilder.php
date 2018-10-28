<?php

namespace Drupal\mynotify;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for mynotify entity.
 * @ingroup mynotify
 */
class MynotifyListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function render() {
    $build['description'] = [
      '#markup' => 'Список уведомлений о поступлении товара',
    ];
    $build += parent::render();
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['mail'] = $this->t('Mail');
    $header['phone'] = $this->t('Phone');
    $header['notified'] = $this->t('Notified');
    $header['product_id'] = $this->t('Product');
    $header['uid'] = $this->t('Owner');
    $header['created'] = $this->t('Created');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $mynotify) {
    /* @var \Drupal\mynotify\Entity\Mynotify $mynotify */
    /* @var \Drupal\commerce_product\Entity\Product $product */
    $product = $mynotify->getProduct();
    $row = [];
    if ($product) {
      $account = $mynotify->getOwner();
      $row['id'] = $mynotify->id();
      $row['name'] = $mynotify->getName();
      $row['mail'] = $mynotify->getMail();
      $row['phone'] = $mynotify->getPhone();
      $row['notified'] = empty($mynotify->getNotified()) ? $this->t('No') : $this->t('Yes');
      $row['product_id'] = $product->toLink();
      $row['uid'] = $account->toLink();
      $row['created'] = \Drupal::service('date.formatter')->format($mynotify->getCreatedTime(), 'medium');
    }
    else {
      $mynotify->delete();
    }
    return $row + parent::buildRow($mynotify);
  }

}
