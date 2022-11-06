<?php

namespace Drupal\shopify_api_rest;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface CreneauCnfStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Creneau cnf revision IDs for a specific Creneau cnf.
   *
   * @param \Drupal\shopify_api_rest\Entity\CreneauCnfInterface $entity
   *   The Creneau cnf entity.
   *
   * @return int[]
   *   Creneau cnf revision IDs (in ascending order).
   */
  public function revisionIds(CreneauCnfInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Creneau cnf author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Creneau cnf revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

}
