<?php // phpcs:ignore WordPress.NamingConventions
/**
 * Frontend class
 *
 * @author  YITH <plugins@yithemes.com>
 * @package YITH\FrequentlyBoughtTogether
 * @version 1.0.0
 */

if ( ! defined( 'YITH_WFBT' ) ) {
	exit;
} // Exit if accessed directly.

if ( ! class_exists( 'YITH_WFBT_Frontend' ) ) {
	/**
	 * Frontend class.
	 * The class manage all the frontend behaviors.
	 *
	 * @since 1.0.0
	 */
	class YITH_WFBT_Frontend {

		/**
		 * Single instance of the class
		 *
		 * @since 1.0.0
		 * @var YITH_WFBT_Frontend
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @since 1.0.0
		 * @var string
		 */
		public $version = YITH_WFBT_VERSION;

		/**
		 * Returns single instance of the class
		 *
		 * @since 1.0.0
		 * @return YITH_WFBT_Frontend
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {
			// enqueue scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            add_action( 'plugins_loaded', array( $this, 'display_frequently_bought_together_form' ), 20 );

			add_shortcode( 'ywfbt_form', array( $this, 'wfbt_shortcode' ) );
		}

		/**
		 * Enqueue scripts
		 *
		 * @since  1.0.0
		 */
		public function enqueue_scripts() {

			wp_enqueue_style( 'yith-wfbt-style', YITH_WFBT_ASSETS_URL . '/css/yith-wfbt.css', array(), YITH_WFBT_VERSION );

			$background       = get_option( 'yith-wfbt-button-color' );
			$background_hover = get_option( 'yith-wfbt-button-color-hover' );
			$text_color       = get_option( 'yith-wfbt-button-text-color' );
			$text_color_hover = get_option( 'yith-wfbt-button-text-color-hover' );

			$inline_css = "
                .yith-wfbt-submit-block .yith-wfbt-submit-button {
                        background: {$background};
                        color: {$text_color};
                }
                .yith-wfbt-submit-block .yith-wfbt-submit-button:hover {
                        background: {$background_hover};
                        color: {$text_color_hover};
                }";

			wp_add_inline_style( 'yith-wfbt-style', $inline_css );
		}

		/**
		 * Form Template
		 *
		 * @since  1.0.0
		 */
		public function add_bought_together_form( $product_id = false, $return = false ) {

            if( ! $product_id ) {
                global $product;
                $product_id = yit_get_base_product_id( $product );
            }

            $content = do_shortcode( '[ywfbt_form product_id="' . $product_id . '"]' );


            if( $return ) {
                return $content;
            } else {
                echo $content;
            }
        }


		/**
		 * Frequently Bought Together Shortcode
		 *
		 * @since  1.0.5
		 * @param array $atts Shortcode attributes.
		 * @return string
		 */
		public function wfbt_shortcode( $atts ) {

			$atts = shortcode_atts(
				array(
					'product_id' => 0,
				),
				$atts
			);

			extract( $atts ); //phpcs:ignore WordPress.PHP.DontExtract

			$product_id = intval( $product_id );
			$product    = wc_get_product( $product_id );

			if ( ! $product ) {
				// get global.
				global $product;
			}

			// if also global is empty return.
			if ( ! $product ) {
				return '';
			}

			// get meta for current product.
			$group = $product->get_meta( YITH_WFBT_META, true );
			if ( empty( $group ) || $product->is_type( array( 'grouped', 'external' ) ) ) {
				return '';
			}

			if ( $product->is_type( 'variable' ) ) {

				$variations = $product->get_children();

				if ( empty( $variations ) ) {
					return '';
				}
				// get first product variation.
				$product_id = array_shift( $variations );
				$product    = wc_get_product( $product_id );
			}

			$products[] = $product;
			foreach ( $group as $the_id ) {
				$current = wc_get_product( $the_id );
				if ( ! $current || ! $current->is_purchasable() || ! $current->is_in_stock() ) {
					continue;
				}
				// add to main array.
				$products[] = $current;
			}

			ob_start();

			wc_get_template( 'yith-wfbt-form.php', array( 'products' => $products ), '', YITH_WFBT_DIR . 'templates/' );

			return ob_get_clean();
		}

        /**
         * Add frequently bought together form in case Woo Blocks are used.
         *
         * @param string     $html Block content.
         * @param array      $pars_block The full block, including name and attributes.
         * @param WP_Block   $block The block instance.
         *
         * @return string
         */
        public function wc_block_display_bought_together_form( $html, $pars_block, $block ) {

            global $post;

            $product_id = $block->context['postId'] ?? $post->ID;
            $form = '<div class="yith-wcfbt-content alignwide">';
            $form.= $this->add_bought_together_form( $product_id, true );
            $form.= '</div>';
            return $form . $html;
        }

        /**
         * Check if the plugin use WC Blocks for display the Frequently Bought Together form.
         *
         * @return void
         */
        public function display_frequently_bought_together_form() {
            if( yith_plugin_fw_wc_is_using_block_template_in_single_product() ) {

                add_filter( 'render_block_woocommerce/product-details', array( $this, 'wc_block_display_bought_together_form' ), 10, 3 );

            } else {
                add_action( 'woocommerce_after_single_product_summary', array( $this, 'add_bought_together_form' ), 1 );
            }
        }
	}
}
/**
 * Unique access to instance of YITH_WFBT_Frontend class
 *
 * @since 1.0.0
 * @return YITH_WFBT_Frontend
 */
function YITH_WFBT_Frontend() { // phpcs:ignore WordPress.NamingConventions
	return YITH_WFBT_Frontend::get_instance();
}
