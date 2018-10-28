<?php

namespace Drupal\mynotify\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\mynotify\MynotifyInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Mynotify entity.
 *
 * @ingroup mynotify
 *
 * @ContentEntityType(
 *   id = "mynotify",
 *   label = @Translation("Mynotify entity"),
 *   handlers = {
 *     "storage" = "Drupal\mynotify\MynotifyStorage",
 *     "storage_schema" = "Drupal\mynotify\MynotifyStorageSchema",
 *     "list_builder" = "Drupal\mynotify\MynotifyListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add_page" = "Drupal\mynotify\MynotifyForm",
 *       "add" = "Drupal\mynotify\MynotifyForm",
 *       "edit" = "Drupal\mynotify\MynotifyForm",
 *       "delete" = "Drupal\mynotify\Form\MynotifyDeleteForm",
 *     },
 *     "access" = "Drupal\mynotify\MynotifyAccessControlHandler",
 *   },
 *   fieldable = FALSE,
 *   translatable = TRUE,
 *   base_table = "mynotify",
 *   data_table = "mynotify_field_data",
 *   admin_permission = "administer mynotify entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/content/mynotify/{mynotify}/edit",
 *     "edit-form" = "/admin/content/mynotify/{mynotify}/edit",
 *     "delete-form" = "/admin/content/mynotify/{mynotify}/delete",
 *     "collection" = "/mynotify/list"
 *   },
 *   field_ui_base_route = "entity.mynotify.collection",
 * )
 *
 * The 'links':
 * entity.<entity-name>.<link-name>
 * Example: 'entity.mynotify.canonical'
 *
 *  *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 */
class Mynotify extends ContentEntityBase implements MynotifyInterface {

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'uid' => \Drupal::currentUser()->id(),
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setChangedTime($timestamp) {
    $this->set('changed', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTimeAcrossTranslations() {
    $changed = $this->getUntranslated()->getChangedTime();
    /* @var \Drupal\Core\Language\Language $language */
    foreach ($this->getTranslationLanguages(FALSE) as $language) {
      $translation_changed = $this->getTranslation($language->getId())
        ->getChangedTime();
      $changed = max($translation_changed, $changed);
    }
    return $changed;
  }


  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getProductId() {
    return $this->get('product_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setProductId($product_id) {
    $this->set('product_id', $product_id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProduct() {
    return $this->get('product_id')->entity;
  }


  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getMail() {
    return $this->get('mail')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setMail($mail) {
    $this->set('mail', $mail);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getPhone() {
    return $this->get('phone')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setPhone($phone) {
    $this->set('phone', $phone);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getNotified() {
    return $this->get('notified')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setNotified($notified) {
    $this->set('notified', $notified);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public function getText() {
    return $this->get('text')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setText($text) {
    $this->set('text', $text);
    return $this;
  }


  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => 0,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['mail'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'email_default',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['phone'] = BaseFieldDefinition::create('telephone')
      ->setLabel(t('Phone'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['text'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Text'))
      ->setDisplayOptions('form', [
        'type' => 'text_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE);


    $fields['notified'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Notified'))
      ->setDefaultValue(FALSE);


    $fields['product_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Product'))
      ->setSetting('target_type', 'commerce_product')
      ->setRequired(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'entity_reference',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Name'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default');

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'));


    return $fields;

  }
}