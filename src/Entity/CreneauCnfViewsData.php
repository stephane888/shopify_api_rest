<?php

namespace Drupal\shopify_api_rest\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Creneau cnf entities.
 */
class CreneauCnfViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
