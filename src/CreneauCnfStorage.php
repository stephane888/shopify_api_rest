<?php

namespace Drupal\shopify_api_rest;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\shopify_api_rest\Entity\CreneauCnfInterface;

/**
 * Defines the storage handler class for Creneau cnf entities.
 *
 * This extends the base storage class, adding required special handling for
 * Creneau cnf entities.
 *
 * @ingroup shopify_api_rest
 */
class CreneauCnfStorage extends SqlContentEntityStorage implements CreneauCnfStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(CreneauCnfInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {creneau_cnf_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {creneau_cnf_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

}
