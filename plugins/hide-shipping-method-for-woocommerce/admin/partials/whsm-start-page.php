<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$get_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );

if ( isset( $_POST['save_general_setting'] ) && ! empty( $_POST['save_general_setting'] ) ) {
	$admin_object = new Woo_Hide_Shipping_Methods_Admin( '', '' );
	$post_wpnonce         = filter_input( INPUT_POST, 'whsm_save_general_setting', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
	$post_retrieved_nonce = isset( $post_wpnonce ) ? sanitize_text_field( wp_unslash( $post_wpnonce ) ) : '';
	if ( ! wp_verify_nonce( $post_retrieved_nonce, 'whsm_save_general_setting_action' ) ) {
		$admin_object->whsma_updated_message( 'nonce_check', '' );
	} else {
		$get_hide_shipping_option      = filter_input( INPUT_POST, 'hide_shipping_option', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$get_whsm_hide_shipping_cart   = filter_input( INPUT_POST, 'whsm_hide_shipping_cart', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
		$get_hide_shipping_method_list = filter_input( INPUT_POST, 'hide_shipping_method_list', FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_REQUIRE_ARRAY );
		$hide_shipping_option      = ! empty( $get_hide_shipping_option ) ? sanitize_text_field( wp_unslash( $get_hide_shipping_option ) ) : '';
		$hide_shipping_method_list = ! empty( $get_hide_shipping_method_list ) ? array_map( 'sanitize_text_field', $get_hide_shipping_method_list ) : array();
		if ( isset( $hide_shipping_option ) && ! empty( $hide_shipping_option ) ) {
			update_option( 'hide_shipping_option', $hide_shipping_option );
		}
		if ( isset( $get_whsm_hide_shipping_cart ) ) {
			update_option( 'whsm_hide_shipping_cart', 'on' );
		} else {
			update_option( 'whsm_hide_shipping_cart', 'off' );
		}
		update_option( 'hide_shipping_method_list', $hide_shipping_method_list );
		$admin_object->whsma_updated_message( 'saved', '', '' );
	}
}
$hide_shipping_option = get_option( 'hide_shipping_option' );
$current_tab          = ! empty( $get_tab ) ? sanitize_title( $get_tab ) : 'general_setting';
require_once( dirname( __FILE__ ) . '/class-whsm-shipping-method-page.php' );
?>
<div class="wrap woocommerce">
	<form method="post" enctype="multipart/form-data">
		<?php
		switch ( $current_tab ) {
			case 'upgrade_dashboard':
				require_once( dirname( __FILE__ ) . '/dots-upgrade-dashboard.php' );
				break;
			case 'get_started':
				require_once( dirname( __FILE__ ) . '/whsm-get-started.php' );
				break;
			case 'quick_info':
				require_once( dirname( __FILE__ ) . '/whsm-information-page.php' );
				break;
			case 'woo_hide_shipping':
				$shipping_method_obj = new WHSM_Shipping_Method_Page();
				$shipping_method_obj->whsmsmp_sz_output();
				break;
            case 'whsm_import_export':
                if ( whsm_fs()->is__premium_only() && whsm_fs()->can_use_premium_code() ) {
                    require_once( dirname( __FILE__ ) . '/whsm-import-export.php' );
                }
				break;
			default:
				require_once( dirname( __FILE__ ) . '/whsm-general-setting.php' );
				break;
		}
		?>
		
	</form>
</div>