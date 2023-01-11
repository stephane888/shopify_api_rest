<?php

namespace Drupal\shopify_api_rest\Services;

use Drupal\Core\Controller\ControllerBase;
use Drupal\shopify_api_rest\Entity\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Stephane888\WbuShopify\ApiRest\Authentification\IntegrationToken;
use Stephane888\WbuShopify\Exception\WbuShopifyException;
use Drupal\Component\Serialization\Json;
use Stephane888\WbuShopify\ApiRest\Metafields\MetafieldsToken;

/**
 *
 * @author stephane
 *        
 */
class ManageAccessToken extends ControllerBase {
  protected $entity_type_id = 'access_token';
  protected $IntegrationToken;
  protected $access_token;
  protected $MetafieldsToken;
  
  /**
   *
   * @param IntegrationToken $IntegrationToken
   * @param MetafieldsToken $MetafieldsToken
   */
  public function __construct(IntegrationToken $IntegrationToken, MetafieldsToken $MetafieldsToken) {
    $this->IntegrationToken = $IntegrationToken;
    $this->MetafieldsToken = $MetafieldsToken;
  }
  
  /**
   * Permet d'obtenir l'entity token à partir du nom de la boutique.
   *
   * @return NULL|AccessToken
   */
  public function getEntityByShopDomain($shop_domain) {
    $query = $this->entityTypeManager()->getStorage($this->entity_type_id)->getQuery();
    $query->condition('shop_domain', $shop_domain);
    $ids = $query->execute();
    if ($ids) {
      return $this->entityTypeManager()->getStorage($this->entity_type_id)->load(reset($ids));
    }
    return null;
  }
  
  /**
   * --
   */
  public function setAppConfig($configs) {
    $this->IntegrationToken->setConfigs($configs);
  }
  
  /**
   * 1: on effcetue la validation de la requete.
   * 2: on verifie si on a deja un token.
   *
   * @param Request $Request
   */
  public function run(Request $Request, $grantOptions) {
    $params = $Request->query->all();
    $this->IntegrationToken->ValidationRequest($Request);
    //
    if (empty($params['shop']))
      throw new WbuShopifyException('Un parametre de la boutique est manquant');
    
    if (empty($this->getToken($Request))) {
      // Demande d'autorisation
      $this->IntegrationToken->AskAuthorization($Request, $grantOptions);
    }
  }
  
  /**
   *
   * @param Request $Request
   * @return mixed
   */
  public function getToken(Request $Request) {
    $params = $Request->query->all();
    if (!$this->access_token) {
      /**
       *
       * @var \Drupal\shopify_api_rest\Entity\AccessToken $entityToken
       */
      $entityToken = $this->getEntityByShopDomain($params['shop']);
      if ($entityToken) {
        $data = $entityToken->get('access_token')->value;
        if (!empty($data)) {
          $data = Json::decode($data);
          if (!empty($data['access_token']))
            $this->access_token = $data['access_token'];
        }
      }
    }
    return $this->access_token;
  }
  
  /**
   * Permet d'enregistrer les metafields.
   */
  public function saveMetafields(string $endPoint, array $metafields, array $configs) {
    $this->MetafieldsToken->setConfigs($configs);
    $this->MetafieldsToken->requestEndPoint = $endPoint;
    $this->MetafieldsToken->authentificationXShopify();
    return $this->MetafieldsToken->save($metafields);
  }
  
  /**
   * Permet de charger les metafields.
   */
  public function loadMetafields(string $endPoint, array $metafields, array $configs) {
    $this->MetafieldsToken->setConfigs($configs);
    $this->MetafieldsToken->requestEndPoint = $endPoint;
    $this->MetafieldsToken->authentificationXShopify();
    return $this->MetafieldsToken->get();
  }
  
  /**
   * Permet d'enregistrer le "authorization_code";
   * On doit egalement verifier que tous les droits nous sont accordées et les
   * enregistrées.
   *
   * @param Request $Request
   */
  public function SaveAuthorization(Request $Request, array $grantOptions, $with_token = true) {
    $params = $Request->query->all();
    $this->IntegrationToken->ValidationRequest($Request);
    //
    if (!empty($params['code']) && !empty($params['shop'])) {
      $values = [
        'shop_domain' => $params['shop'],
        'authorization_code' => $params['code']
      ];
      if ($with_token) {
        $values['access_token'] = $this->IntegrationToken->GetTokenAccess($Request);
      }
      $entity = $this->getEntityByShopDomain($params['shop']);
      if ($entity) {
        foreach ($values as $name => $value) {
          $entity->set($name, $value);
        }
      }
      else
        $entity = $this->entityTypeManager()->getStorage($this->entity_type_id)->create($values);
      //
      $entity->save();
    }
  }
  
}