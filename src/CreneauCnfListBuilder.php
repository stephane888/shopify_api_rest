<?php

namespace Drupal\shopify_api_rest;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Creneau cnf entities.
 *
 * @ingroup shopify_api_rest
 */
class CreneauCnfListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Creneau cnf ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var \Drupal\shopify_api_rest\Entity\CreneauCnf $entity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.creneau_cnf.edit_form',
      ['creneau_cnf' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}
