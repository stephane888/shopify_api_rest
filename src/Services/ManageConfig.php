<?php

namespace Drupal\shopify_api_rest\Services;

use Drupal\Core\Controller\ControllerBase;
use Drupal\shopify_api_rest\Entity\CreneauCnf;

/**
 * --entity_type.manager
 *
 * @author stephane
 *        
 */
class ManageConfig extends ControllerBase {
  protected $entity_type_id = 'creneau_cnf';
  
  public function save(array $values) {
    if (!empty($values['shop_domain']) && !empty($values['key'])) {
      $id = $this->loadConfigByKeys($values);
      if ($id) {
        /**
         *
         * @var CreneauCnf
         */
        $creneau_cnf = $this->entityTypeManager()->getStorage($this->entity_type_id)->load($id);
        foreach ($values as $key => $value) {
          $creneau_cnf->set($key, $value);
        }
      }
      else
        $creneau_cnf = $this->entityTypeManager()->getStorage($this->entity_type_id)->create($values);
      $creneau_cnf->save();
      return $creneau_cnf;
    }
    else
      throw new \Exception("Parametre manquant.");
  }
  
  /**
   * Cette approche necessite qu'on se rassure 'shop_domain' n'a pas été
   * modifié.
   *
   * @param array $values
   */
  function loadConfigByKeys(array $values) {
    $query = $this->entityTypeManager()->getStorage($this->entity_type_id)->getQuery();
    $query->condition('shop_domain', $values['shop_domain']);
    $query->condition('key', $values['key']);
    $ids = $query->execute();
    if ($ids) {
      return reset($ids);
    }
    return false;
  }
  
  function delete($id) {
    $CreneauCnf = CreneauCnf::load($id);
    $CreneauCnf->delete();
    return true;
  }
  
}
