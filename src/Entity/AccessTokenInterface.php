<?php

namespace Drupal\shopify_api_rest\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Access token entities.
 *
 * @ingroup shopify_api_rest
 */
interface AccessTokenInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Access token name.
   *
   * @return string
   *   Name of the Access token.
   */
  public function getName();

  /**
   * Sets the Access token name.
   *
   * @param string $name
   *   The Access token name.
   *
   * @return \Drupal\shopify_api_rest\Entity\AccessTokenInterface
   *   The called Access token entity.
   */
  public function setName($name);

  /**
   * Gets the Access token creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Access token.
   */
  public function getCreatedTime();

  /**
   * Sets the Access token creation timestamp.
   *
   * @param int $timestamp
   *   The Access token creation timestamp.
   *
   * @return \Drupal\shopify_api_rest\Entity\AccessTokenInterface
   *   The called Access token entity.
   */
  public function setCreatedTime($timestamp);

}
