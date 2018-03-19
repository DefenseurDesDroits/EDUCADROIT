<?php

/**
 * @file
 * Contains \Drupal\gaya_editorial\Entity\GayaEditorialEntity.
 */

namespace Drupal\gaya_editorial\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\gaya_editorial\GayaEditorialEntityInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Gaya editorial entity entity.
 *
 * @ingroup gaya_editorial
 *
 * @ContentEntityType(
 *   id = "gaya_editorial_entity",
 *   label = @Translation("Gaya editorial entity"),
 *   bundle_label = @Translation("Gaya editorial entity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\gaya_editorial\GayaEditorialEntityListBuilder",
 *     "views_data" = "Drupal\gaya_editorial\Entity\GayaEditorialEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\gaya_editorial\Form\GayaEditorialEntityForm",
 *       "add" = "Drupal\gaya_editorial\Form\GayaEditorialEntityForm",
 *       "edit" = "Drupal\gaya_editorial\Form\GayaEditorialEntityForm",
 *       "delete" = "Drupal\gaya_editorial\Form\GayaEditorialEntityDeleteForm",
 *     },
 *     "access" = "Drupal\gaya_editorial\GayaEditorialEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\gaya_editorial\GayaEditorialEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "gaya_editorial_entity",
 *   admin_permission = "administer gaya editorial entity entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/gaya_editorial_entity/{gaya_editorial_entity}",
 *     "add-form" = "/admin/structure/gaya_editorial_entity/add",
 *     "edit-form" = "/admin/structure/gaya_editorial_entity/{gaya_editorial_entity}/edit",
 *     "delete-form" = "/admin/structure/gaya_editorial_entity/{gaya_editorial_entity}/delete",
 *     "collection" = "/admin/structure/gaya_editorial_entity",
 *   },
 *   bundle_entity_type = "gaya_editorial_entity_type",
 *   field_ui_base_route = "entity.gaya_editorial_entity_type.edit_form"
 * )
 */
class GayaEditorialEntity extends ContentEntityBase implements GayaEditorialEntityInterface {
  use EntityChangedTrait;
  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->bundle();
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
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Gaya editorial entity entity.'))
      ->setReadOnly(TRUE);
    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setDescription(t('The Gaya editorial entity type/bundle.'))
      ->setSetting('target_type', 'gaya_editorial_entity_type')
      ->setRequired(TRUE);
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Gaya editorial entity entity.'))
      ->setReadOnly(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Gaya editorial entity entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title backoffice'))
      ->setDescription(t('This <strong><em>Title</em></strong> is only showed in the backoffice.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setRequired(TRUE)
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Gaya editorial entity is published.'))
      ->setDefaultValue(TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code for the Gaya editorial entity entity.'))
      ->setDisplayOptions('form', array(
        'type' => 'language_select',
        'weight' => 10,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
