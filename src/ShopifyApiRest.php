<?php

namespace Drupal\shopify_api_rest;

/**
 *
 * @author stephane
 *        
 */
class ShopifyApiRest {
  
  /**
   *
   * @see https://shopify.dev/api/usage/access-scopes
   * @return string[]
   */
  static public function listGrantOption() {
    return [
      "read_all_orders" => "read_all_orders",
      "write_products" => "write_products",
      "read_products" => "read_products",
      "read_shipping" => "read_shipping",
      "read_content" => "read_content",
      "write_content" => "write_content",
      "read_checkouts" => "read_checkouts",
      "write_checkouts" => "write_checkouts"
    ];
  }
  
}