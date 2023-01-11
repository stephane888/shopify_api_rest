<?php

namespace Drupal\shopify_api_rest\Controller;

use Drupal\Core\Controller\ControllerBase;
use Stephane888\DrupalUtility\HttpResponse;
use Symfony\Component\HttpFoundation\Request;
use Drupal\shopify_api_rest\Services\ManageConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\shopify_api_rest\Entity\CreneauCnf;
use Stephane888\Debug\ExceptionExtractMessage;

/**
 * Returns responses for shopify_api_rest routes.
 */
class ShopifyApiRestController extends ControllerBase {
  /**
   *
   * @var ManageConfig
   */
  protected $ManageConfig;
  
  function __construct(ManageConfig $ManageConfig) {
    $this->ManageConfig = $ManageConfig;
  }
  
  /**
   *
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('shopify_api_rest.manage_config'));
  }
  
  /**
   * Charge la configuration,
   * Il permet de soit charger la configuration en local soit de charger celle
   * en prod.
   *
   * @deprecated
   */
  public function LoadConfig(Request $Request) {
    try {
      $params = $Request->query->all();
      if (!empty($params['key']) && !empty($params['shop_domain'])) {
        if (isset($params['load_from_prod']) && $params['load_from_prod'] == 1) {
          //
        }
        else {
          /**
           *
           * @var CreneauCnf
           */
          $creneau_cnf = $this->ManageConfig->loadEntityConfig($params);
          if ($creneau_cnf) {
            return HttpResponse::response($creneau_cnf->get('datas')->value);
          }
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
  
  /**
   * --
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function InitConfig() {
    $build = [];
    return HttpResponse::response($build);
  }
  
  /**
   * --
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function LoadBusyCreneaux() {
    $build = [];
    return HttpResponse::response($build);
  }
  
  /**
   * --
   *
   * @param Request $Request
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   */
  public function SaveCreneaux(Request $Request) {
    try {
      $params = $Request->query->all();
      if (!empty($params['key']) && !empty($params['shop_domain'])) {
        // return HttpResponse::response($this->ManageConfig->delete(1));
        //
        $values = [
          'key' => $params['key'],
          'shop_domain' => $params['shop_domain'],
          'name' => $params['shop_domain'],
          'datas' => $Request->getContent()
        ];
        /**
         *
         * @var CreneauCnf
         */
        $creneau_cnf = $this->ManageConfig->save($values);
        return HttpResponse::response($creneau_cnf->toArray());
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
