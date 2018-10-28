<?php


namespace Drupal\mynotify\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\mynotify\Entity\Mynotify;
use Drupal\commerce_product\Entity\Product;

/**
 * Class MynotifyController
 * @package Drupal\mynotify\Controller
 */
class MynotifyController extends ControllerBase {


  /**
   * {@inheritdoc}
   */
  public function viewPage(Product $commerce_product) {
    $entity = Mynotify::create();
    $entity->setProductId($commerce_product->id());
    $mynotify_form = \Drupal::service('entity.form_builder')
      ->getForm($entity, 'add_page');
    $noindex_nofollow = [
      '#tag' => 'meta',
      '#attributes' => [
        'name' => 'robots',
        'content' => 'noindex,nofollow',
      ],
    ];
    $mynotify_form['#attached']['html_head'][] = [
      $noindex_nofollow,
      'noindex_nofollow'
    ];
    return $mynotify_form;
  }

  /**
   * @param \Drupal\commerce_product\Entity\Product $commerce_product
   * @return string
   */
  public function viewPageTitle(Product $commerce_product) {
    $settings_config = \Drupal::config('mynotify.settings');
    $token_service = \Drupal::token();
    return $token_service->replace($settings_config->get('popup.title'), ['commerce_product' => $commerce_product]);
  }

}