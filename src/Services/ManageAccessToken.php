<?php

namespace Drupal\shopify_api_rest\Services;

use Drupal\Core\Controller\ControllerBase;
use Drupal\shopify_api_rest\Entity\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Stephane888\WbuShopify\ApiRest\Authentification\IntegrationToken;
use Stephane888\WbuShopify\Exception\WbuShopifyException;

/**
 *
 * @author stephane
 *        
 */
class ManageAccessToken extends ControllerBase {
  protected $entity_type_id = 'access_token';
  protected $IntegrationToken;
  protected $access_token;
  
  public function __construct(IntegrationToken $IntegrationToken) {
    $this->IntegrationToken = $IntegrationToken;
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
  public function retriveAutorisationToken() {
    //
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
  
  public function getToken(Request $Request) {
    $params = $Request->query->all();
    if (!$this->access_token) {
      /**
       *
       * @var \Drupal\shopify_api_rest\Entity\AccessToken $entityToken
       */
      $entityToken = $this->getEntityByShopDomain($params['shop']);
      if ($entityToken) {
        $this->access_token = $entityToken->get('access_token')->value;
      }
    }
    return $this->access_token;
  }
  
  /**
   * Permet d'enregistrer le "authorization_code";
   * On doit egalement verifier que tous les droits nous sont accordées et les
   * enregistrées.
   *
   * @param Request $Request
   */
  public function SaveAuthorization(Request $Request, array $grantOptions, $with_token = true, array $confs = []) {
    $params = $Request->query->all();
    $this->IntegrationToken->ValidationRequest($Request);
    //
    if (!empty($params['code']) && !empty($params['shop'])) {
      $values = [
        'shop_domain' => $params['shop'],
        'authorization_code' => $params['code']
      ];
      if ($with_token && !empty($confs)) {
        $values['access_token'] = $this->IntegrationToken->GetTokenAccess($Request, $confs);
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