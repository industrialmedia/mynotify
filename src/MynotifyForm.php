<?php

namespace Drupal\mynotify;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the mynotify entity edit forms.
 *
 * @ingroup mynotify
 */
class MynotifyForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var \Drupal\mynotify\Entity\Mynotify $entity */
    $form = parent::buildForm($form, $form_state);
    /* @var \Drupal\mynotify\MynotifyForm $callback_object */
    $callback_object = $form_state->getBuildInfo()['callback_object'];
    $operation = $callback_object->getOperation();
    if ($operation == 'edit') {
      $form['product_id']['#disabled'] = 'disabled';
    }
    elseif ($operation == 'add_page') {
      $form['product_id']['#disabled'] = 'disabled';

    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    /* @var $mynotify \Drupal\mynotify\Entity\Mynotify */
    $mynotify = $this->entity;
    $product = $mynotify->getProduct();
    if ($status == SAVED_UPDATED) {
      $this->messenger()->addStatus($this->t('The notify has been updated.'));
      $form_state->setRedirect('entity.mynotify.collection');
    }
    else {
      $this->messenger()->addStatus('Ваше сообщение отправлено.');
      $mail_config = $this->config('mynotify.mail');
      $myapi_config = $this->config('myapi.settings');
      $module = 'mynotify';
      $key = 'mynotify_add_to_admin';
      $to = $myapi_config->get('mail_to');
      $params['body'] = $mail_config->get('mynotify_add_to_admin.body');
      $params['subject'] = $mail_config->get('mynotify_add_to_admin.subject');
      $langcode = \Drupal::languageManager()->getCurrentLanguage()->getId();
      $params['mynotify'] = $mynotify;
      \Drupal::service('plugin.manager.mail')
        ->mail($module, $key, $to, $langcode, $params);
      $form_state->setRedirectUrl($product->toUrl());
    }
    return $status;
  }
}

