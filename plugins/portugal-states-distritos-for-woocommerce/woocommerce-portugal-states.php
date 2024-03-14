<?php
/*
 * Plugin Name: Portugal States (Distritos) for WooCommerce
 * Plugin URI: https://www.webdados.pt/wordpress/plugins/portugal-states-distritos-woocommerce-wordpress/
 * Description: This plugin adds the Portuguese "States", known as "Distritos", to WooCommerce and sets the correct address format for Portugal
 * Version: 3.5
 * Author: PT Woo Plugins (by Webdados)
 * Author URI: https://ptwooplugins.com
 * Text Domain: portugal-states-distritos-for-woocommerce
 * Domain Path: /lang
 * Requires at least: 5.4
 * Requires PHP: 7.0
 * WC requires at least: 5.4
 * WC tested up to: 8.4
*/

/* WooCommerce CRUD not needed */
/* WooCommerce HPOS not needed - https://github.com/woocommerce/woocommerce/wiki/High-Performance-Order-Storage-Upgrade-Recipe-Book */
/* WooCommerce block-based Cart and Checkout ready */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/* Init */
add_action( 'plugins_loaded', 'woocommerce_portugal_states_init' );
function woocommerce_portugal_states_init() {
	if ( class_exists( 'WooCommerce' ) && defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '4.0', '>=' ) ) {
		//Localization
		load_plugin_textdomain( 'portugal-states-distritos-for-woocommerce' );
		//Load the class
		$GLOBALS['WC_Webdados_Distritos'] = WC_Webdados_Distritos();
	}
}


/* Main class */
function WC_Webdados_Distritos() {
	return WC_Webdados_Distritos::instance(); 
}

final class WC_Webdados_Distritos {

	/* Single instance */
	protected static $_instance = null;

	/* Constructor */
	public function __construct() {
		//Hooks
		$this->init_hooks();
	}

	/* Ensures only one instance of our plugin is loaded or can be loaded */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/* Hooks */
	private function init_hooks() {
		//The States/Distritos
		add_filter( 'woocommerce_states', array( $this, 'woocommerce_states' ) );
		//Localization
		add_filter( 'woocommerce_get_country_locale', array( $this, 'woocommerce_get_country_locale' ) );
		//Correct portuguese address format
		add_filter(
			'woocommerce_localisation_address_formats',
			array( $this, 'woocommerce_localisation_address_formats' ),
			apply_filters( 'woocommerce_portugal_localisation_address_formats_priority', -1 )
		);
	}

	/* Add the states */
	public function woocommerce_states( $states ) {
		$states['PT'] = array(
			'AC' => __( 'Azores',           'portugal-states-distritos-for-woocommerce' ),
			'AV' => __( 'Aveiro',           'portugal-states-distritos-for-woocommerce' ),
			'BJ' => __( 'Beja',             'portugal-states-distritos-for-woocommerce' ),
			'BR' => __( 'Braga',            'portugal-states-distritos-for-woocommerce' ),
			'BG' => __( 'Bragança',         'portugal-states-distritos-for-woocommerce' ),
			'CB' => __( 'Castelo Branco',   'portugal-states-distritos-for-woocommerce' ),
			'CM' => __( 'Coimbra',          'portugal-states-distritos-for-woocommerce' ),
			'EV' => __( 'Évora',            'portugal-states-distritos-for-woocommerce' ),
			'FR' => __( 'Faro',             'portugal-states-distritos-for-woocommerce' ),
			'GD' => __( 'Guarda',           'portugal-states-distritos-for-woocommerce' ),
			'LR' => __( 'Leiria',           'portugal-states-distritos-for-woocommerce' ),
			'LS' => __( 'Lisbon',           'portugal-states-distritos-for-woocommerce' ),
			'MD' => __( 'Madeira',          'portugal-states-distritos-for-woocommerce' ),
			'PR' => __( 'Portalegre',       'portugal-states-distritos-for-woocommerce' ),
			'PT' => __( 'Oporto',           'portugal-states-distritos-for-woocommerce' ),
			'ST' => __( 'Santarém',         'portugal-states-distritos-for-woocommerce' ),
			'SB' => __( 'Setúbal',          'portugal-states-distritos-for-woocommerce' ),
			'VC' => __( 'Viana do Castelo', 'portugal-states-distritos-for-woocommerce' ),
			'VR' => __( 'Vila Real',        'portugal-states-distritos-for-woocommerce' ),
			'VS' => __( 'Viseu',            'portugal-states-distritos-for-woocommerce' ),
		);
		return $states;
	}

	/* Country local settings */
	public function woocommerce_get_country_locale( $countries ) {
		$countries['PT'] = array(
			'postcode_before_city' => true,
			'postcode'             => array(
				'priority' => apply_filters( 'woocommerce_portugal_postcode_priority', 65 ), //Like Spain
				'class'    => apply_filters( 'woocommerce_portugal_postcode_class', array( 'form-row-first' ) ), //From 3.0 onwards
			),
			'city'                => array(
				'label'    => apply_filters( 'woocommerce_portugal_city_label', __( 'Postcode City', 'portugal-states-distritos-for-woocommerce' ) ),
				'class'    => apply_filters( 'woocommerce_portugal_city_class', array( 'form-row-last' ) ), //From 3.0 onwards
			),
			'state'                => array(
				'label'    => apply_filters( 'woocommerce_portugal_state_label', __( 'District', 'portugal-states-distritos-for-woocommerce' ) ),
				'required' => apply_filters( 'woocommerce_portugal_state_required', true ),
			),
		);
		return $countries;
	}

	/* Address format */
	public function woocommerce_localisation_address_formats( $formats ) {
		//For Portugal
		$formats['PT'] = "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{country}";
		//Include District? - It makes no sense, but we had it before 2.0, so we’ll keep the filter
		if ( apply_filters( 'woocommerce_portugal_address_format_include_state', false ) ) {
			$formats['PT'] = "{name}\n{company}\n{address_1}\n{address_2}\n{postcode} {city}\n{state}\n{country}";
		}
		return $formats;
	}

}

/* HPOS Compatible */
add_action( 'before_woocommerce_init', function() {
	if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
	}
} );


/* Portuguese Postcodes nag */
add_action( 'admin_init', function() {
	if (
		( ! defined( 'WEBDADOS_PORTUGUESE_POSTCODES_NAG' ) )
		&&
		( ! function_exists( '\Webdados\PortuguesePostcodesWooCommerce\init' ) )
		&&
		empty( get_transient( 'webdados_portuguese_postcodes_nag' ) )
	) {
		define( 'WEBDADOS_PORTUGUESE_POSTCODES_NAG', true );
		require_once( 'webdados_portuguese_postcodes_nag/webdados_portuguese_postcodes_nag.php' );
	}
} );

/* If you’re reading this you must know what you’re doing ;-) Greetings from sunny Portugal! */
