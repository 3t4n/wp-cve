<?php
	
	defined( 'ABSPATH' ) or die( 'Keep Silent' );
	
	if ( ! class_exists( 'Variation_Duplicator_For_Woocommerce_Backend', false ) ):
		class Variation_Duplicator_For_Woocommerce_Backend {
			
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
				
				do_action( 'woo_variation_duplicator_backend_loaded', $this );
			}
			
			public function includes() {
				require_once dirname( __FILE__ ) . '/class-variation-duplicator-for-woocommerce-variation-image-clone.php';
				require_once dirname( __FILE__ ) . '/class-variation-duplicator-for-woocommerce-variation-clone.php';
			}
			
			public function hooks() {
				add_filter( 'plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 2 );
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			}
			
			public function init() {
				Variation_Duplicator_For_Woocommerce_Variation_Clone::instance();
				Variation_Duplicator_For_Woocommerce_Variation_Image_Clone::instance();
			}
			
			// start
			
			public function admin_enqueue_scripts() {
				
				$screen    = get_current_screen();
				$screen_id = $screen ? $screen->id : '';
				$suffix    = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
				
				if ( in_array( $screen_id, array( 'product' ) ) ) {
					
					wp_enqueue_style( 'variation-duplicator-for-woocommerce', esc_url( variation_duplicator_for_woocommerce()->assets_url( "/css/variation-duplicator-for-woocommerce{$suffix}.css" ) ), array(), variation_duplicator_for_woocommerce()->assets_version( "/css/variation-duplicator-for-woocommerce{$suffix}.css" ) );
					
					wp_enqueue_script( 'variation-duplicator-for-woocommerce', esc_url( variation_duplicator_for_woocommerce()->assets_url( "/js/variation-duplicator-for-woocommerce{$suffix}.js" ) ), array(
						'jquery',
						'wp-util',
						'select2'
					),                 variation_duplicator_for_woocommerce()->assets_version( "/js/variation-duplicator-for-woocommerce{$suffix}.js" ), true );
					
					$clone_limit = absint( apply_filters( 'woo_variation_duplicator_clone_limit', 9 ) );
					$translation = array(
						'noCheckedText' => esc_html__( 'Select a variation to duplicate.', 'variation-duplicator-for-woocommerce' ),
						'limitText'     => sprintf( esc_html__( "Set how many times each variation should clone. \nDefault value is 1. Limit is %d.", 'variation-duplicator-for-woocommerce' ), $clone_limit ),
						'limit'         => $clone_limit,
					);
					
					wp_localize_script( 'variation-duplicator-for-woocommerce', 'WooVariationDuplicator', $translation );
				}
			}
			
			public function plugin_row_meta( $links, $file ) {
				if ( variation_duplicator_for_woocommerce()->plugin_basename() !== $file ) {
					return $links;
				}
				
				$report_url        = esc_url( 'https://getwooplugins.com/tickets/' );
				$documentation_url = esc_url( 'https://getwooplugins.com/documentation/variation-duplicator-for-woocommerce/' );
				
				$row_meta[ 'docs' ] = sprintf( '<a target="_blank" href="%1$s" title="%2$s">%2$s</a>', esc_url( $documentation_url ), esc_html__( 'View Documentation', 'variation-duplicator-for-woocommerce' ) );
				$row_meta[ 'help' ] = sprintf( '<a target="_blank" href="%1$s">%2$s</a>', esc_url( $report_url ), esc_html__( 'Help &amp; Support', 'variation-duplicator-for-woocommerce' ) );
				
				return array_merge( $links, $row_meta );
			}
		}
	endif;