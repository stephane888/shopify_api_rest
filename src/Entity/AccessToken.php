<?php

namespace Drupal\shopify_api_rest\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Access token entity.
 *
 * @ingroup shopify_api_rest
 *
 * @ContentEntityType(
 *   id = "access_token",
 *   label = @Translation("Access token"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\shopify_api_rest\AccessTokenListBuilder",
 *     "views_data" = "Drupal\shopify_api_rest\Entity\AccessTokenViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\shopify_api_rest\Form\AccessTokenForm",
 *       "add" = "Drupal\shopify_api_rest\Form\AccessTokenForm",
 *       "edit" = "Drupal\shopify_api_rest\Form\AccessTokenForm",
 *       "delete" = "Drupal\shopify_api_rest\Form\AccessTokenDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\shopify_api_rest\AccessTokenHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\shopify_api_rest\AccessTokenAccessControlHandler",
 *   },
 *   base_table = "access_token",
 *   translatable = FALSE,
 *   admin_permission = "administer access token entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "shop_domain",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/access_token/{access_token}",
 *     "add-form" = "/admin/structure/access_token/add",
 *     "edit-form" = "/admin/structure/access_token/{access_token}/edit",
 *     "delete-form" = "/admin/structure/access_token/{access_token}/delete",
 *     "collection" = "/admin/structure/access_token",
 *   },
 *   field_ui_base_route = "access_token.settings"
 * )
 */
class AccessToken extends ContentEntityBase implements AccessTokenInterface {
  
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
  public function getShopDomain() {
    return $this->get('shop_domain')->value;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public function setShopDomain($name) {
    $this->set('shop_domain', $name);
    return $this;
  }
  
  public function getName() {
    return $this->getShopDomain();
  }
  
  public function setName($name) {
    $this->setShopDomain($name);
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
    
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')->setLabel(t('Authored by'))->setDescription(t('The user ID of author of the Access token entity.'))->setRevisionable(TRUE)->setSetting('target_type', 'user')->setSetting('handler', 'default')->setDisplayOptions('view', [
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
    
    $fields['shop_domain'] = BaseFieldDefinition::create('string')->setLabel(t(' Shop domain '))->setSettings([
      'max_length' => 50,
      'text_processing' => 0
    ])->setDisplayOptions('view', [
      'label' => 'above',
      'type' => 'string',
      'weight' => -4
    ])->setDisplayOptions('form', [
      'type' => 'string_textfield',
      'weight' => -4
    ])->setDisplayConfigurable('form', TRUE)->setDisplayConfigurable('view', TRUE)->setRequired(TRUE);
    
    $fields['access_token'] = BaseFieldDefinition::create('string_long')->setLabel(" access_token ")->setDisplayOptions('form', [
      'type' => 'string_textarea',
      'weight' => 25,
      'settings' => [
        'rows' => 4
      ]
    ])->setDisplayConfigurable('view', TRUE)->setDisplayConfigurable('form', true);
    
    $fields['authorization_code'] = BaseFieldDefinition::create('string_long')->setLabel(" authorization_code ")->setDisplayOptions('form', [
      'type' => 'string_textarea',
      'weight' => 25,
      'settings' => [
        'rows' => 4
      ]
    ])->setDisplayConfigurable('view', TRUE)->setDisplayConfigurable('form', true);
    
    $fields['grant_options'] = BaseFieldDefinition::create('list_string')->setLabel(" grant_options ")->setSetting('allowed_values_function', [
      '\Drupal\shopify_api_rest\ShopifyApiRest',
      'listGrantOption'
    ])->setDisplayConfigurable('form', true)->setDisplayConfigurable('view', TRUE)->setCardinality(-1);
    
    $fields['status']->setDescription(t(' A boolean indicating whether the Access token is published. '))->setDisplayOptions('form', [
      'type' => 'boolean_checkbox',
      'weight' => -3
    ]);
    
    $fields['created'] = BaseFieldDefinition::create('created')->setLabel(t('Created'))->setDescription(t('The time that the entity was created.'));
    
    $fields['changed'] = BaseFieldDefinition::create('changed')->setLabel(t('Changed'))->setDescription(t('The time that the entity was last edited.'));
    
    return $fields;
  }
  
}
