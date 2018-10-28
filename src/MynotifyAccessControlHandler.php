<?php

namespace Drupal\mynotify;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;


/**
 * Class MynotifyAccessControlHandler
 * @package Drupal\mynotify
 */
class MynotifyAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    switch ($operation) {
      case 'view':
        return AccessResult::allowedIfHasPermission($account, 'view mynotify entity');
      case 'edit':
        return AccessResult::allowedIfHasPermission($account, 'edit mynotify entity');
      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete mynotify entity');
    }
    return AccessResult::allowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add mynotify entity');
  }

}

?>