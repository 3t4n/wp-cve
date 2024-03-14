<?php
/**
 * FA Product class.
 *
 * @version 1.0.1
 *
 * @package FA WP
 * @author  Virgial Berveling
 * @updated 2019-01-26
 * 
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if ( ! class_exists( 'FA_Product', false ) ) {

   class FA_Product
   {
      private $item_name;
      private $slug;
      private $api_url;
      private $sku;
      private $text_domain;
      private $license_page_url;
      private $tracking_url;

      
      public function __construct($product)
      {

         $this->item_name = isset($product['item_name'])?$product['item_name']:'';
         $this->slug = isset($product['slug'])?$product['slug']:'';
         $this->api_url = isset($product['api_url'])?$product['api_url']:'';
         $this->sku = isset($product['sku'])?$product['sku']:'';
         $this->text_domain = isset($product['text_domain'])?$product['text_domain']:'';
         $this->license_page_url = isset($product['license_page_url'])?$product['license_page_url']:'';
         $this->tracking_url = isset($product['tracking_url'])?$product['tracking_url']:'';
      }


      public function get_item_name()
      {
         return $this->item_name;
      }

      public function get_text_domain()
      {
         return $this->text_domain;
      }

      public function get_license_page_url()
      {
         return $this->license_page_url;
      }

      public function get_api_url()
      {
         return $this->api_url;
      }

      public function get_sku()
      {
         return $this->sku;
      }

      public function get_tracking_url( $c )
      {
         switch($c):
            case 'activate-license-notice':
            break;
         endswitch;

         return $this->license_page_url;
      }

      public function get_extension_url( $c )
      {
         switch($c):
            case 'license-nearing-limit-notice':
            case 'license-expiring-notice':
            case 'license-at-limit-notice':
            case 'license-expired-notice':
            break;
         endswitch;

         return $this->license_page_url;
      }

   }
}