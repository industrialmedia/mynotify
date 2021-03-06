<?php

namespace Drupal\mynotify;

use Drupal\Component\Utility\Html;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;


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

    // Alter field label
    $field_names = [
      'name',
      'mail',
      'phone',
      'text'
    ];
    foreach ($field_names as $field_name) {
      $label = $this->config('mynotify.settings')
        ->get('form.labels.' . $field_name);
      if (!empty($label) && isset($form[$field_name])) {
        $form[$field_name]['widget'][0]['value']['#title'] = $label;
      }
    }

    // Add default value
    if ($operation == 'add_page') {
      $current_user = $this->currentUser();
      if ($current_user->isAuthenticated()) {
        $form['name']['widget'][0]['value']['#default_value'] = $current_user->getDisplayName();
        $form['mail']['widget'][0]['value']['#default_value'] = $current_user->getEmail();
      }
    }

    $form['actions']['submit']['#value'] = $this->t('Submit');
    $label = $this->config('mynotify.settings')->get('form.labels.submit');
    if (!empty($label)) {
      $form['actions']['submit']['#value'] = $label;
    }

    // ajaxSubmit
    $form['#id'] = Html::getId($this->getFormId());
    $form['actions']['submit']['#ajax'] = [
      'callback' => '::ajaxSubmitCallback',
      'wrapper' => $form['#id'],
      'event' => 'click',
      'progress' => [
        'type' => 'throbber',
      ],
    ];

    return $form;
  }


  /**
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return array
   */
  public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state) {
    if ($form_state->hasAnyErrors()) {
      unset($form['#prefix']);
      unset($form['#suffix']);
      return $form;
    }
    $submitted_text = $this->config('mynotify.settings')
      ->get('form.submitted_text');
    return [
      '#markup' => '<div class="form-submitted-text">' . $submitted_text . '</div>'
    ];
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
      $this->messenger()->addStatus('???????? ?????????????????? ????????????????????.');
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

