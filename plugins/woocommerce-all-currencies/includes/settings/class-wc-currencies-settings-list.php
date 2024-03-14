<?php
/**
 * WooCommerce All Currencies - List Country Section Settings
 *
 * @version 2.4.2
 * @since   2.0.0
 * @author  Algoritmika Ltd.
 * @author  WP Wham
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WC_All_Currencies_Settings_List' ) ) :

class Alg_WC_All_Currencies_Settings_List extends Alg_WC_All_Currencies_Settings_Section {
	
	public $id   = '';
	public $desc = '';
	
	/**
	 * Constructor.
	 *
	 * @version 2.1.1
	 * @since   2.0.0
	 */
	function __construct() {
		$this->id   = 'currencies_list';
		$this->desc = __( 'Country Currencies', 'woocommerce-all-currencies' );
		parent::__construct();
	}

	/**
	 * get_settings.
	 *
	 * @version 2.3.7
	 * @since   2.0.0
	 */
	public static function get_settings() {
		return alg_wcac_get_list_section_settings( 'country' );
	}

}

endif;

return new Alg_WC_All_Currencies_Settings_List();
