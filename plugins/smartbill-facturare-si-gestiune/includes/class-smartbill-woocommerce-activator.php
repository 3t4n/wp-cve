<?php
/**
 * Fired during plugin activation.
 *
 * @package    smartbill-facturare-si-gestiune
 * @subpackage Smartbill_Woocommerce/includes
 * @link       http://www.smartbill.ro
 * @since      1.0.0
 */

/**
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @copyright  Intelligent IT SRL 2018
 * @author     Intelligent IT SRL <vreauapi@smartbill.ro>
 */
class Smartbill_Woocommerce_Activator {
	/**
	 * Initialize smartbill settings
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		if ( ! check_smartbill_compatibility() ) {
			show_smartbill_version_err();
		}

		if ( ! get_option( 'smartbill_plugin_options' ) ) {
			update_option( 'smartbill_plugin_options', '' );
		}
		$settings = get_option( 'smartbill_plugin_options_settings' );
		if ( ! get_option( 'smartbill_plugin_options_settings' ) ) {
			update_option( 'smartbill_plugin_options_settings', '' );
		}else{
			if( isset( $settings['automatically_issue_document'] ) && '0' == $settings['automatically_issue_document'] ){
				unset($settings['automatically_issue_document']);
				$settings['order_status'] = []; 
				update_option( 'smartbill_plugin_options_settings', $settings );
			}
		}

		update_option( 'smartbill_set_toast', false );

	}

}
