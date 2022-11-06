<?php

namespace Drupal\shopify_api_rest;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Access token entity.
 *
 * @see \Drupal\shopify_api_rest\Entity\AccessToken.
 */
class AccessTokenAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\shopify_api_rest\Entity\AccessTokenInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished access token entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published access token entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit access token entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete access token entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add access token entities');
  }


}
