<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('rest_api_init', 'epeken_courier_complete_order_end_point');
function epeken_courier_complete_order_end_point() {
  register_rest_route('epeken/v1','/completeorder',
	array(
		'methods' => 'POST',
		'callback' => 'epeken_courier_rest_api_complete_order'
 	));
}

function epeken_courier_validate_license_key ($data) {
  $lic_key = sanitize_text_field($data['license_key']);
  if (empty($lic_key))
    return false;
 
  $license_key = sanitize_text_field(get_option('epeken_wcjne_license_key'));
  if ($lic_key === $license_key)
    return true;
  else
    return false;
}

function epeken_courier_rest_api_complete_order($data) {
 if (!epeken_courier_validate_license_key ($data)) 
    return new WP_Error('ERROR','Invalid Epeken Plugin License Key');
  
 $order = wc_get_order($data['id']);
 
 if ($order === false)
    return new WP_Error('ERROR', 'Invalid order number');
 
 $order -> update_status('completed');
 echo 'Order '.$data['id'].' is completed successfully.';
}

?>
