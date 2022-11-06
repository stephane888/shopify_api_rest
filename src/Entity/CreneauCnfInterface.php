<?php

namespace Drupal\shopify_api_rest\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Creneau cnf entities.
 *
 * @ingroup shopify_api_rest
 */
interface CreneauCnfInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Creneau cnf name.
   *
   * @return string
   *   Name of the Creneau cnf.
   */
  public function getName();

  /**
   * Sets the Creneau cnf name.
   *
   * @param string $name
   *   The Creneau cnf name.
   *
   * @return \Drupal\shopify_api_rest\Entity\CreneauCnfInterface
   *   The called Creneau cnf entity.
   */
  public function setName($name);

  /**
   * Gets the Creneau cnf creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Creneau cnf.
   */
  public function getCreatedTime();

  /**
   * Sets the Creneau cnf creation timestamp.
   *
   * @param int $timestamp
   *   The Creneau cnf creation timestamp.
   *
   * @return \Drupal\shopify_api_rest\Entity\CreneauCnfInterface
   *   The called Creneau cnf entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Creneau cnf revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Creneau cnf revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\shopify_api_rest\Entity\CreneauCnfInterface
   *   The called Creneau cnf entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Creneau cnf revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Creneau cnf revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\shopify_api_rest\Entity\CreneauCnfInterface
   *   The called Creneau cnf entity.
   */
  public function setRevisionUserId($uid);

}
