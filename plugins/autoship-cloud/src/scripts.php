<?php

/**
 * Enqueues Autoship Scripts and Styles For the Backend
 */
function autoship_enqueue_admin_scripts() {

  // Main Autoship Admin Style sheet
	wp_enqueue_style( 'autoship-admin', plugin_dir_url( Autoship_Plugin_File ) . 'styles/admin-style.css', array(), Autoship_Version );

  // Main Autoship Script
	wp_enqueue_script( 'autoship-admin', plugin_dir_url( Autoship_Plugin_File ) . 'js/admin.js', array(), Autoship_Version );

  // Bulk Utilities Script
	wp_enqueue_script( 'autoship-batch', plugin_dir_url( Autoship_Plugin_File ) . 'js/batch.js', array('jquery'), Autoship_Version );

}
add_action( 'admin_enqueue_scripts', 'autoship_enqueue_admin_scripts' );

/**
 * Enqueues Autoship Scripts and Styles For the Frontend
 */
function autoship_enqueue_scripts() {

  // Main Autoship Style sheet
	wp_enqueue_style( 'autoship', plugin_dir_url( Autoship_Plugin_File ) . 'styles/style.css', array(), Autoship_Version );

  // Enqueue Dashicons if not loaded.
  wp_enqueue_style( 'dashicons' );

	wp_enqueue_script( 'autoship-product-schedule-options', plugin_dir_url( Autoship_Plugin_File ) . 'js/product-schedule-options.js', array(), Autoship_Version, true );
	wp_enqueue_script( 'autoship-schedule-options', plugin_dir_url( Autoship_Plugin_File ) . 'js/schedule-options.js', array( 'jquery' ), Autoship_Version, true );
	wp_enqueue_script( 'autoship-scheduled-orders', plugin_dir_url( Autoship_Plugin_File ) . 'js/scheduled-orders.js', array( 'jquery', 'jquery-tiptip' ), Autoship_Version, true );

  // Only Load if Dynamic Cart is Enabled
  if ( !empty( autoship_get_settings_fields('autoship_dynamic_cart', true ) ) ){
	wp_enqueue_script( 'autoship-schedule-cart', plugin_dir_url( Autoship_Plugin_File ) . 'js/schedule-cart.js', array(), Autoship_Version, true );
	wp_enqueue_script( 'autoship-select-frequency-dialog', plugin_dir_url( Autoship_Plugin_File ) . 'js/select-frequency-dialog.js', array(), Autoship_Version, true );
	wp_enqueue_script( 'autoship-select-next-occurrence-dialog', plugin_dir_url( Autoship_Plugin_File ) . 'js/select-next-occurrence-dialog.js', array(), Autoship_Version, true ); }

  // Only Load if Chat Bot is Enabled
  if ( !empty( autoship_get_settings_fields('autoship_webchat_directline_secret', true ) ) ){
	wp_enqueue_style( 'autoship-webchat', plugin_dir_url( Autoship_Plugin_File ) . 'WebChat/botchat.css', array(), Autoship_Version );
  wp_enqueue_script( 'autoship-webchat', plugin_dir_url( Autoship_Plugin_File ) . 'WebChat/botchat.js', array(), Autoship_Version, true ); }

  // WooCommerce Script Used for Frontend
  wp_enqueue_script( 'jquery-tiptip', WC()->plugin_url() . '/assets/js/jquery-tiptip/jquery.tipTip.min.js', array( 'jquery' ), WC_VERSION, true );

}
add_action( 'wp_enqueue_scripts', 'autoship_enqueue_scripts' );


/**
 * Disables the Tooltip as Modal on Mobile
 * @param int $val The current min-width
 * @return int 0 if the setting is off.
 */
function autoship_show_tooltip_as_modal_mobile( $val ){

  return  'yes' != autoship_get_settings_fields( 'autoship_product_info_mobile_tooltip', true ) ? 0 : $val;

}
add_filter( 'autoship_dialog_info_tooltip_min_browser_width', 'autoship_show_tooltip_as_modal_mobile', 10, 1 );

/**
 * Outputs Autoship Script Data in the Page Header
 * @see autoship_print_scripts_data()
 */
function autoship_head_scripts() {

	$data = array(
		'AUTOSHIP_SITE_URL' => site_url( '/' ),
		'AUTOSHIP_AJAX_URL' => admin_url( '/admin-ajax.php' ),
		'AUTOSHIP_MERCHANTS_URL' => autoship_get_merchants_url(),
		'AUTOSHIP_API_URL' => autoship_get_api_url(),
    'AUTOSHIP_DIALOG_TYPE' => autoship_get_settings_fields ( 'autoship_product_info_display', true ),
    'AUTOSHIP_DIALOG_TOOLTIP_MIN_WIDTH' => apply_filters( 'autoship_dialog_info_tooltip_min_browser_width', 1024 ),
    'AUTOSHIP_DIALOG_SIZE' => autoship_get_settings_fields ( 'autoship_product_info_modal_size', true ),
    'AUTOSHIP_DIALOG_SIZES' => autoship_get_info_modal_sizes()
	);
	autoship_print_scripts_data( $data );

  $default_autoship_template_data = apply_filters( 'autoship_default_template_data', array(
    'cartBtn'             => '.add_to_cart_button',
    'yesBtn'              => '.autoship-yes-radio',
    'noBtn'               => '.autoship-no-radio',
    'optionsCls'          => '.autoship-schedule-options',
    'discountPriceCls'    => '.autoship-percent-discount',
    'checkoutPriceCls'    => '.autoship-checkout-price',
    'discountStringCls'   => '.autoship-custom-percent-discount-str',
    'frequencyCls'        => '.autoship-frequency',
    'frequencySelectCls'  => '.autoship-frequency-select',
    'frequencyTypeValCls' => '.autoship-frequency-type-value',
    'frequencyValCls'     => '.autoship-frequency-value',
    'productCls'          => '.product',  // The class of the Main Product HTML Element on the shop page
    'cartItemCls'         => '.cart_item',
    'variationFormCls'    => '.variations_form',
    'variationIdCls'      => '.variation_id',
    'findProductFn'       => NULL, // Pluggable Function
    'findAutoshipOptions' => NULL, // Pluggable Function
    'retrieveProductIdFn' => NULL, // Pluggable Function
    'setVariationIdFn'    => NULL, // Pluggable Function
    'getVariationIdFn'    => NULL, // Pluggable Function
    'isSimpleProductFn'   => NULL, // Pluggable Function
    'isCartPageFn'        => NULL, // Pluggable Function
    'isCartPage'          => false
  ) );

  ob_start();
  ?><!-- Autoship Cloud Data Container -->
  <script>window['autoshipTemplateData']=window['autoshipTemplateData']||<?php echo json_encode( $default_autoship_template_data ); ?>;</script>
  <!-- End Autoship Cloud Data Container --><?php
  echo apply_filters('autoship_header_script_data', ob_get_clean() );

}
add_action( 'wp_head', 'autoship_head_scripts' );

/**
 * Outputs JS Directly to Page
 * @param array $data A Set of Key value pairs to add/output
 */
function autoship_print_scripts_data( $data ) {
	echo "<script>\r\n// <![CDATA[\r\n";
	foreach ( $data as $name => $value )
	printf( "var %s = %s;\r\n", $name, json_encode( $value ) );
	echo "// ]]>\r\n</script>\r\n";
}
