<?php

namespace Drupal\shopify_api_rest\Controller;

use Drupal\Core\Controller\ControllerBase;
use Stephane888\DrupalUtility\HttpResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\shopify_api_rest\Services\ManageConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\shopify_api_rest\Entity\CreneauCnf;
use Stephane888\Debug\ExceptionExtractMessage;
use Stephane888\WbuShopify\ApiRest\Authentification\IntegrationToken;

/**
 * Returns responses for shopify_api_rest routes.
 */
class RequestShopifyController extends ControllerBase {
  
  /**
   * Builds the response.
   */
  public function SaveCreneauxMetafields(Request $Request) {
    try {
      $params = $Request->query->all();
      if (!empty($params['shop'])) {
        $IntegrationToken = new IntegrationToken();
        /**
         *
         * @var CreneauCnf
         */
        $creneau_cnf = $this->ManageConfig->loadEntityConfig($params);
        if ($creneau_cnf) {
          return HttpResponse::response($creneau_cnf->get('datas')->value);
        }
        return HttpResponse::response([]);
      }
      else
        throw new \Exception(" Parametre manquant. ");
    }
    catch (\Exception $e) {
      return HttpResponse::response(ExceptionExtractMessage::errorAll($e), 400, $e->getMessage());
    }
    catch (\Error $e) {
      return HttpResponse::response(ExceptionExtractMessage::errorAll($e), 400, $e->getMessage());
    }
  }
  
}