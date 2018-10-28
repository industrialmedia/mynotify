<?php

namespace Drupal\mynotify;

use Drupal\Core\Entity\ContentEntityTypeInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorageSchema;
use Drupal\Core\Field\FieldStorageDefinitionInterface;


/**
 * Class MynotifyStorageSchema
 * @package Drupal\mynotify
 */
class MynotifyStorageSchema extends SqlContentEntityStorageSchema {

  /**
   * {@inheritdoc}
   */
  protected function getEntitySchema(ContentEntityTypeInterface $entity_type, $reset = FALSE) {
    $schema = parent::getEntitySchema($entity_type, $reset);
    $schema['mynotify_field_data']['indexes'] += [
      'mynotify__product_id__notified' => ['product_id', 'notified'],
    ];
    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  protected function getSharedTableFieldSchema(FieldStorageDefinitionInterface $storage_definition, $table_name, array $column_mapping) {
    $schema = parent::getSharedTableFieldSchema($storage_definition, $table_name, $column_mapping);
    $field_name = $storage_definition->getName();
    if ($table_name == 'mynotify_field_data') {
      switch ($field_name) {
        case 'name':
        case 'mail':
        case 'product_id':
          $schema['fields'][$field_name]['not null'] = TRUE;
          break;
      }
      switch ($field_name) {
        case 'changed':
        case 'created':
        case 'notified':
          $this->addSharedTableFieldIndex($storage_definition, $schema, TRUE);
          break;
      }
    }
    return $schema;
  }

}
