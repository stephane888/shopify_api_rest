<?php

namespace Drupal\shopify_api_rest;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Creneau cnf entity.
 *
 * @see \Drupal\shopify_api_rest\Entity\CreneauCnf.
 */
class CreneauCnfAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\shopify_api_rest\Entity\CreneauCnfInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished creneau cnf entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published creneau cnf entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit creneau cnf entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete creneau cnf entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add creneau cnf entities');
  }


}
