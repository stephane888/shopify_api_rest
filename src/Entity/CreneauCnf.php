<?php

namespace Drupal\shopify_api_rest\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EditorialContentEntityBase;
use Drupal\Core\Entity\RevisionableInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Creneau cnf entity.
 *
 * @ingroup shopify_api_rest
 *
 * @ContentEntityType(
 *   id = "creneau_cnf",
 *   label = @Translation("Creneau cnf"),
 *   handlers = {
 *     "storage" = "Drupal\shopify_api_rest\CreneauCnfStorage",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\shopify_api_rest\CreneauCnfListBuilder",
 *     "views_data" = "Drupal\shopify_api_rest\Entity\CreneauCnfViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\shopify_api_rest\Form\CreneauCnfForm",
 *       "add" = "Drupal\shopify_api_rest\Form\CreneauCnfForm",
 *       "edit" = "Drupal\shopify_api_rest\Form\CreneauCnfForm",
 *       "delete" = "Drupal\shopify_api_rest\Form\CreneauCnfDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\shopify_api_rest\CreneauCnfHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\shopify_api_rest\CreneauCnfAccessControlHandler",
 *   },
 *   base_table = "creneau_cnf",
 *   revision_table = "creneau_cnf_revision",
 *   revision_data_table = "creneau_cnf_field_revision",
 *   show_revision_ui = TRUE,
 *   translatable = FALSE,
 *   admin_permission = "administer creneau cnf entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "revision" = "vid",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_uid",
 *     "revision_created" = "revision_timestamp",
 *     "revision_log_message" = "revision_log"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/creneau_cnf/{creneau_cnf}",
 *     "add-form" = "/admin/structure/creneau_cnf/add",
 *     "edit-form" = "/admin/structure/creneau_cnf/{creneau_cnf}/edit",
 *     "delete-form" = "/admin/structure/creneau_cnf/{creneau_cnf}/delete",
 *     "version-history" = "/admin/structure/creneau_cnf/{creneau_cnf}/revisions",
 *     "revision" = "/admin/structure/creneau_cnf/{creneau_cnf}/revisions/{creneau_cnf_revision}/view",
 *     "revision_revert" = "/admin/structure/creneau_cnf/{creneau_cnf}/revisions/{creneau_cnf_revision}/revert",
 *     "revision_delete" = "/admin/structure/creneau_cnf/{creneau_cnf}/revisions/{creneau_cnf_revision}/delete",
 *     "collection" = "/admin/structure/creneau_cnf",
 *   },
 *   field_ui_base_route = "creneau_cnf.settings"
 * )
 */
class CreneauCnf extends EditorialContentEntityBase implements CreneauCnfInterface {
  
  use EntityChangedTrait;
  use EntityPublishedTrait;
  
  /**
   *
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id()
    ];
  }
  
  /**
   *
   * {@inheritdoc}
   */
  protected function urlRouteParameters($rel) {
    $uri_route_parameters = parent::urlRouteParameters($rel);
    
    if ($rel === 'revision_revert' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    elseif ($rel === 'revision_delete' && $this instanceof RevisionableInterface) {
      $uri_route_parameters[$this->getEntityTypeId() . '_revision'] = $this->getRevisionId();
    }
    
    return $uri_route_parameters;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    parent::preSave($storage);
    
    // If no revision author has been set explicitly,
    // make the creneau_cnf owner the revision author.
    if (!$this->getRevisionUser()) {
      $this->setRevisionUserId($this->getOwnerId());
    }
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);
    
    // Add the published field.
    $fields += static::publishedBaseFieldDefinitions($entity_type);
    
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')->setLabel(t('Authored by'))->setDescription(t('The user ID of author of the Creneau cnf entity.'))->setRevisionable(TRUE)->setSetting('target_type', 'user')->setSetting('handler', 'default')->setDisplayOptions('view', [
      'label' => 'hidden',
      'type' => 'author',
      'weight' => 0
    ])->setDisplayOptions('form', [
      'type' => 'entity_reference_autocomplete',
      'weight' => 5,
      'settings' => [
        'match_operator' => 'CONTAINS',
        'size' => '60',
        'autocomplete_type' => 'tags',
        'placeholder' => ''
      ]
    ])->setDisplayConfigurable('form', TRUE)->setDisplayConfigurable('view', TRUE);
    
    $fields['name'] = BaseFieldDefinition::create('string')->setLabel(t('Name'))->setDescription(t('The name of the Creneau cnf entity.'))->setRevisionable(TRUE)->setSettings([
      'max_length' => 50,
      'text_processing' => 0
    ])->setDefaultValue('')->setDisplayOptions('view', [
      'label' => 'above',
      'type' => 'string',
      'weight' => -4
    ])->setDisplayOptions('form', [
      'type' => 'string_textfield',
      'weight' => -4
    ])->setDisplayConfigurable('form', TRUE)->setDisplayConfigurable('view', TRUE)->setRequired(TRUE);
    
    $fields['shop_domain'] = BaseFieldDefinition::create('string')->setLabel(t(' Url de la boutique '))->setSettings([
      'max_length' => 50,
      'text_processing' => 0
    ])->setDefaultValue('')->setDisplayOptions('view', [
      'label' => 'above',
      'type' => 'string',
      'weight' => -4
    ])->setDisplayOptions('form', [
      'type' => 'string_textfield',
      'weight' => 2
    ])->setDisplayConfigurable('form', TRUE)->setDisplayConfigurable('view', TRUE)->setRequired(TRUE)->setTranslatable(true)->setRevisionable(false);
    
    $fields['key'] = BaseFieldDefinition::create('string')->setLabel(t(' Clée '))->setDescription(t(" Permettant d'identifier la donnée enregistrée. "))->setSettings([
      'max_length' => 50,
      'text_processing' => 0
    ])->setDefaultValue('')->setDisplayOptions('view', [
      'label' => 'above',
      'type' => 'string',
      'weight' => -4
    ])->setDisplayOptions('form', [
      'type' => 'string_textfield',
      'weight' => 2
    ])->setDisplayConfigurable('form', TRUE)->setDisplayConfigurable('view', TRUE)->setRequired(TRUE)->setTranslatable(true)->setRevisionable(false);
    
    $fields['datas'] = BaseFieldDefinition::create('string_long')->setLabel(" Données ")->setDisplayOptions('form', [
      'type' => 'string_textarea',
      'weight' => 25,
      'settings' => [
        'rows' => 4
      ]
    ])->setDisplayConfigurable('view', TRUE)->setDisplayConfigurable('form', true)->setRevisionable(false);
    
    $fields['status']->setDescription(t('A boolean indicating whether the Creneau cnf is published.'))->setDisplayOptions('form', [
      'type' => 'boolean_checkbox',
      'weight' => -3
    ]);
    
    $fields['created'] = BaseFieldDefinition::create('created')->setLabel(t('Created'))->setDescription(t('The time that the entity was created.'));
    
    $fields['changed'] = BaseFieldDefinition::create('changed')->setLabel(t('Changed'))->setDescription(t('The time that the entity was last edited.'));
    
    return $fields;
  }
  
}
