<?php

if (!defined('ABSPATH')) exit;
include_once(ABSPATH . 'wp-admin/includes/plugin.php');
 if (is_plugin_active('woocommerce-product-vendors/woocommerce-product-vendors.php')) {

 add_action( 'init', 'epeken_wcpv_register_vendor_custom_fields' );
 function epeken_wcpv_register_vendor_custom_fields() {
  add_action( WC_PRODUCT_VENDORS_TAXONOMY . '_add_form_fields', 'epeken_wcpv_add_vendor_custom_fields' );
  add_action( WC_PRODUCT_VENDORS_TAXONOMY . '_edit_form_fields', 'epeken_wcpv_edit_vendor_custom_fields', 10 );
  add_action( 'edited_' . WC_PRODUCT_VENDORS_TAXONOMY, 'epeken_wcpv_save_vendor_custom_fields' );
  add_action( 'created_' . WC_PRODUCT_VENDORS_TAXONOMY, 'epeken_wcpv_save_vendor_custom_fields' );
 }

 function epeken_wcpv_edit_vendor_custom_fields($term) {
   epeken_add_customer_meta_fields($term);
 } 

 function epeken_wcpv_save_vendor_custom_fields($term_id) {
  $term = get_term_by ('id', $term_id, 'wcpv_product_vendors');
  epeken_save_customer_meta_fields($term); 
 }

 function epeken_wcpv_save_vendor_data($term_id, $args) {
  update_term_meta($term_id, 'epeken_vendor_data', $args); 
 }

 function wcpv_get_epeken_vendor_data($term_id){
  if(!term_exists($term_id, 'wcpv_product_vendors'))
     return null;

  $data = get_term_meta($term_id, 'epeken_vendor_data');
  return $data;
 }

}

?>
