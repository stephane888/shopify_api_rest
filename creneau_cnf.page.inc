<?php

/**
 * @file
 * Contains creneau_cnf.page.inc.
 *
 * Page callback for Creneau cnf entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Creneau cnf templates.
 *
 * Default template: creneau_cnf.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_creneau_cnf(array &$variables) {
  // Fetch CreneauCnf Entity Object.
  $creneau_cnf = $variables['elements']['#creneau_cnf'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
