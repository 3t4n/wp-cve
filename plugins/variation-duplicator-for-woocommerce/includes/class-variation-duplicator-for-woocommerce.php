<?php
	
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
	
	if ( ! class_exists( 'Variation_Duplicator_For_Woocommerce' ) ):
		
		class Variation_Duplicator_For_Woocommerce {
			
			protected static $_instance = null;
			
			public static function instance() {
				if ( is_null( self::$_instance ) ) {
					self::$_instance = new self();
				}
				
				return self::$_instance;
			}
			
			public function __construct() {
				$this->includes();
				$this->hooks();
				$this->init();
				do_action( 'woo_variation_duplicator_loaded', $this );
			}
			
			public function includes() {
				require_once dirname( __FILE__ ) . '/class-variation-duplicator-for-woocommerce-backend.php';
				require_once dirname( __FILE__ ) . '/class-variation-duplicator-for-woocommerce-compatibility.php';
			}
			
			public function hooks() {
				add_action( 'init', array( $this, 'language' ), 1 );
			}
			
			public function init() {
				$this->get_backend();
				Variation_Duplicator_For_Woocommerce_Compatibility::instance();
			}
			
			// start
			public function get_backend() {
				return Variation_Duplicator_For_Woocommerce_Backend::instance();
			}
			
			public function version() {
				return esc_attr( VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_VERSION );
			}
			
			public function language() {
				load_plugin_textdomain( 'variation-duplicator-for-woocommerce', false, dirname( plugin_basename( VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_FILE ) ) . '/languages' );
			}
			
			public function basename() {
				return basename( dirname( VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_FILE ) );
			}
			
			public function plugin_basename() {
				return plugin_basename( VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_FILE );
			}
			
			public function plugin_dirname() {
				return dirname( plugin_basename( VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_FILE ) );
			}
			
			public function plugin_path() {
				return untrailingslashit( plugin_dir_path( VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_FILE ) );
			}
			
			public function plugin_url() {
				return untrailingslashit( plugins_url( '/', VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_FILE ) );
			}
			
			public function images_url( $file = '' ) {
				return untrailingslashit( plugin_dir_url( VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_FILE ) . 'images' ) . $file;
			}
			
			public function assets_url( $file = '' ) {
				return untrailingslashit( plugin_dir_url( VARIATION_DUPLICATOR_FOR_WOOCOMMERCE_PLUGIN_FILE ) . 'assets' ) . $file;
			}
			
			public function assets_path( $file = '' ) {
				return $this->plugin_path() . '/assets' . $file;
			}
			
			public function assets_version( $file ) {
				return filemtime( $this->assets_path( $file ) );
			}
			
			public function is_pro() {
				return false;
			}
		}
	endif;