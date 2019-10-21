<?php

namespace Drupal\mynotify\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Serialization\Json;


/**
 * Class MynotifySettingsForm
 * @package Drupal\mynotify\Form
 */
class MynotifySettingsForm extends ConfigFormBase implements ContainerInjectionInterface {


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'mynotify_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'mynotify.settings',
      'mynotify.mail',
    ];
  }


  protected function getFieldNames() {
    return [
      'name',
      'mail',
      'phone',
      'text',
      'submit',
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $mail_config = $this->config('mynotify.mail');
    $settings_config = $this->config('mynotify.settings');
    $form['settings'] = [
      '#type' => 'vertical_tabs',
      '#title' => $this->t('Settings'),
    ];
    // Вызов попап формы
    $form['load_popup'] = [
      '#type' => 'details',
      '#title' => 'Вызов попап-формы',
      '#group' => 'settings',
      '#tree' => TRUE,
    ];
    $form['load_popup']['addtocart'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Replace button add to cart'),
      '#default_value' => $settings_config->get('load_popup.addtocart'),
      '#description' => $this->t('If the item is out of stock or the price is zero, replace the add to cart button with the pre-order button.'),
    ];

    // Письмо админу о новом запросе
    $form['popup'] = [
      '#type' => 'details',
      '#title' => 'Попап',
      '#group' => 'settings',
      '#tree' => TRUE,
    ];
    $form['popup']['dialog_options'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Dialog options'),
      '#default_value' => $settings_config->get('popup.dialog_options'),
      '#size' => 120,
    ];
    $form['popup']['button_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Button name'),
      '#default_value' => $settings_config->get('popup.button_name'),
    ];
    $form['popup']['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#default_value' => $settings_config->get('popup.title'),
      '#element_validate' => ['token_element_validate'],
      '#token_types' => ['commerce_product'],
    ];
    // Add the token tree UI.
    $form['popup']['token_tree'] = array(
      '#theme' => 'token_tree_link',
      '#token_types' => array('commerce_product'),
      '#show_restricted' => TRUE,
      '#show_nested' => FALSE,
      '#weight' => 90,
    );
    // Форма
    $form['form'] = [
      '#type' => 'details',
      '#title' => 'Форма',
      '#group' => 'settings',
      '#tree' => TRUE,
    ];
    $field_names = $this->getFieldNames();
    foreach ($field_names as $field_name) {
      $form['form']['labels'][$field_name] = [
        '#type' => 'textfield',
        '#title' => $this->t('Label for field:') . ' "' . $field_name . '"',
        '#default_value' => $settings_config->get('form.labels.' . $field_name),
        '#size' => 60,
      ];
    }


    $form['email'] = [
      '#type' => 'vertical_tabs',
      '#title' => $this->t('Email settings'),
    ];
    // Письмо админу о новом запросе
    $form['mynotify_add_to_admin'] = [
      '#type' => 'details',
      '#title' => 'Письмо админу о новом запросе',
      '#description' => 'Пользователь подал запрос на уведомление когда товар появится в наличии',
      '#group' => 'email',
    ];
    $form['mynotify_add_to_admin']['mynotify_add_to_admin_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => $mail_config->get('mynotify_add_to_admin.subject'),
      '#maxlength' => 220,
      '#element_validate' => ['token_element_validate'],
      '#token_types' => ['mynotify'],
    ];
    $form['mynotify_add_to_admin']['mynotify_add_to_admin_body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Body'),
      '#default_value' => $mail_config->get('mynotify_add_to_admin.body'),
      '#rows' => 15,
      '#element_validate' => ['token_element_validate'],
      '#token_types' => ['mynotify'],
    ];
    // Письмо клиенту о поступлении товара на склад
    $form['mynotify_notified_add_to_client'] = [
      '#type' => 'details',
      '#title' => 'Письмо клиенту о поступлении товара на склад',
      '#description' => 'Товар появится в наличии, отправляем письмо тем кто подавал запрос на уведомление',
      '#group' => 'email',
    ];
    $form['mynotify_notified_add_to_client']['mynotify_notified_add_to_client_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => $mail_config->get('mynotify_notified_add_to_client.subject'),
      '#maxlength' => 220,
      '#element_validate' => ['token_element_validate'],
      '#token_types' => ['mynotify'],
    ];
    $form['mynotify_notified_add_to_client']['mynotify_notified_add_to_client_body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Body'),
      '#default_value' => $mail_config->get('mynotify_notified_add_to_client.body'),
      '#rows' => 15,
      '#element_validate' => ['token_element_validate'],
      '#token_types' => ['mynotify'],
    ];
    // Письмо админу о поступлении товара на склад
    $form['mynotify_notified_add_to_admin'] = [
      '#type' => 'details',
      '#title' => 'Письмо админу о поступлении товара на склад',
      '#description' => 'Товар появится в наличии, отправляем письмо админу, с даными кто просил уведомления',
      '#group' => 'email',
    ];
    $form['mynotify_notified_add_to_admin']['mynotify_notified_add_to_admin_subject'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => $mail_config->get('mynotify_notified_add_to_admin.subject'),
      '#maxlength' => 220,
      '#element_validate' => ['token_element_validate'],
      '#token_types' => ['mynotify'],
    ];
    $form['mynotify_notified_add_to_admin']['mynotify_notified_add_to_admin_body'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Body'),
      '#default_value' => $mail_config->get('mynotify_notified_add_to_admin.body'),
      '#rows' => 15,
      '#element_validate' => ['token_element_validate'],
      '#token_types' => ['mynotify'],
    ];
    // Add the token tree UI.
    $form['email']['token_tree'] = array(
      '#theme' => 'token_tree_link',
      '#token_types' => array('mynotify'),
      '#show_restricted' => TRUE,
      '#show_nested' => FALSE,
      '#weight' => 90,
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $popup = $form_state->getValue('popup');
    $dialog_options = $popup['dialog_options'];
    $dialog_options = Json::decode($dialog_options);
    if (!is_array($dialog_options)) {
      $form_state->setErrorByName('popup][dialog_options', 'Dialog options: Не верный Json формат');
    }
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $mail_config = $this->config('mynotify.mail');
    $mail_config
      ->set('mynotify_add_to_admin.subject', $form_state->getValue('mynotify_add_to_admin_subject'))
      ->set('mynotify_add_to_admin.body', $form_state->getValue('mynotify_add_to_admin_body'));
    $mail_config
      ->set('mynotify_notified_add_to_client.subject', $form_state->getValue('mynotify_notified_add_to_client_subject'))
      ->set('mynotify_notified_add_to_client.body', $form_state->getValue('mynotify_notified_add_to_client_body'))
      ->set('mynotify_notified_add_to_admin.subject', $form_state->getValue('mynotify_notified_add_to_admin_subject'))
      ->set('mynotify_notified_add_to_admin.body', $form_state->getValue('mynotify_notified_add_to_admin_body'));
    $mail_config->save();

    $settings_config = $this->config('mynotify.settings');
    $settings_config
      ->set('load_popup.addtocart', $form_state->getValue('load_popup')['addtocart'])
      ->set('popup.dialog_options', $form_state->getValue('popup')['dialog_options'])
      ->set('popup.button_name', $form_state->getValue('popup')['button_name'])
      ->set('popup.title', $form_state->getValue('popup')['title']);
    $field_names = $this->getFieldNames();
    $labels = $form_state->getValue('form')['labels'];
    foreach ($field_names as $field_name) {
      $settings_config->set('form.labels.' . $field_name, $labels[$field_name]);
    }
    $settings_config->save();
    parent::submitForm($form, $form_state);
  }


}
