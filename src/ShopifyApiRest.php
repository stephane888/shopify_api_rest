<?php

namespace Drupal\shopify_api_rest;

/**
 *
 * @author stephane
 *        
 */
class ShopifyApiRest {
  
  static public function listGrantOption() {
    return [
      "write_products" => "write_products",
      "read_shipping" => "read_shipping"
    ];
  }
  
}