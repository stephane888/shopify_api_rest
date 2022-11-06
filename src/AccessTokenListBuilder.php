<?php

namespace Drupal\shopify_api_rest;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Access token entities.
 *
 * @ingroup shopify_api_rest
 */
class AccessTokenListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Access token ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\shopify_api_rest\Entity\AccessToken $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.access_token.edit_form',
      ['access_token' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
