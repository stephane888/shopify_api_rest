<?php

namespace Drupal\shopify_api_rest\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for shopify_api_rest routes.
 */
class ShopifyApiRestController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
