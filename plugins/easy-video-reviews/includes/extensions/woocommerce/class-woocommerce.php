<?php
/**
 * Handled all hooks related to WooCommerce for Easy Video Reviews
 *
 * @package EasyVideoReviews
 */

namespace EasyVideoReviews;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );


if ( ! class_exists( __NAMESPACE__ . '\WooCommerce' ) ) {

	/**
	 * Handled all hooks related to WooCommerce for Easy Video Reviews
	 *
	 * @package EasyVideoReviews
	 */
	class WooCommerce extends \EasyVideoReviews\Base\Controller {

		// Use Utilities trait.
		use \EasyVideoReviews\Traits\Utilities;

		/**
		 * Register the hooks
		 *
		 * @return void
		 */
		public function register_hooks() {
			$this->init_extension();
			$this->add_actions();
			$this->add_filters();
		}

		/**
		 * Initialize the extension
		 *
		 * @return mixed
		 */
		public function init_extension() {
			require_once __DIR__ . '/class-woocommerce-extension.php';

			// Initialize the extension.
			\EasyVideoReviews\Extensions\WooCommerce::init();
		}

		/**
		 * Add action hooks
		 *
		 * @return mixed
		 */
		public function add_actions() {
			// Check if the user has premium access.
			// Modifying this section will cause the plugin to break.
			if ( ! $this->client()->has_premium_access() ) {
				return false;
			}

			//add_action('woocommerce_email_footer', [ $this, 'add_recorder_to_woocommerce_email_footer' ], 0);
			add_action('woocommerce_order_item_meta_start', [ $this, 'add_review_link' ], 12, 3);
		}

		/**
		 * Add filter hooks
		 *
		 * @return mixed
		 */
		public function add_filters() {
			// Check if the user has premium access.
			// Modifying this section will cause the plugin to break.
			// if ( ! $this->client()->has_premium_access() ) {
			//  return false;
			// }
			add_filter('woocommerce_product_tabs', [ $this, 'add_custom_product_tab' ], 99);
		}



		/**
		 * Adds custom tab to WooCommerce Single Product Page
		 *
		 * @param array $tabs WooCommerce Tabs.
		 * @return array
		 */
		public function add_custom_product_tab( $tabs ) {
			$woo_settings = (array) $this->option()->get('woocommerce');

			if ( ! $woo_settings ) {
				return $tabs;
			}

			// Adds a custom tab.
			if ( $woo_settings['single_tab'] ) {
				$tabs['evr_tab'] = [
					'title'    => wp_sprintf( '%s', $woo_settings['single_tab_label'] ),
					'priority' => 50,
					'callback' => [ $this, 'custom_single_product_tab_callback' ],
				];
			}

			// Hides the default reviews tab.
			if ( true === wp_validate_boolean( $woo_settings['hide_default_reviews_tab'] ) ) {
				unset($tabs['reviews']);
			}

			return $tabs;
		}

		/**
		 * Adds product review link in oder details table
		 *
		 * @return mixed
		 */
		public function add_review_link( $item_id, $item, $order ) {
			$woo = (array) $this->option()->get('woocommerce');
			if ( ! isset( $woo['order_complete_button'] ) || '1' !== $woo['order_complete_button'] ) {
				return false;
			}
			$porduct_url = get_permalink($item->get_product_id()) . '?review=evr';
			?>
				<?php if ( is_checkout() && ! empty( is_wc_endpoint_url('order-received') ) ) : ?>
					<span class="evr-single-product-reveiw">
						<a href="<?php echo esc_url($porduct_url); ?>" target="_blank">Leave a Review</a>
					</span>

					<style>
						.woocommerce-table__product-name.product-name {
							position: relative;
						}
						.evr-single-product-reveiw a {
							color: #1636aa;
							text-decoration: underline !important;
						}
						.evr-single-product-reveiw{
							position: absolute;
							right: 15px;
							top: 50%;
							transform: translateY(-50%);
						}
					</style>
				<?php else : ?>
					<div class="evr-eamil-review">
						<span class="evr-single-product-reveiw">
							<a href="<?php echo esc_url($porduct_url); ?>" target="_blank">Leave a Review</a>
						</span>
					</div>

					<style>
						.evr-single-product-reveiw a {
							color: #1636aa;
							text-decoration: underline !important;
							display: inline-block;
						}
						.evr-eamil-review{
							display: block;
							margin-top: 15px;
						}
					</style>
				<?php endif; ?>
			<?php
		}


		/**
		 * Adds custom tab to WooCommerce Single Product Page
		 *
		 * @return mixed
		 */
		public function custom_single_product_tab_callback() {
			global $product;

			$product_name = str_replace(' ','-',$product->get_name());

			if ( get_option('evr_onbarding_on_update', false) ) {
				$folder = wp_sprintf('product-%s', $product->get_id());
			} else {
				$folder = wp_sprintf('product-%s-%s', $product_name, $product->get_id());
			}

			$woo_settings = (array) $this->option()->get('woocommerce');

			if ( ! $woo_settings ) {
				return false;
			}

			echo ( '<div class="woocommerce">' );

			if ( $woo_settings['single_tab_showcase'] ) {
				$shortcode = wp_sprintf('[reviews cols="3" order="%s" orderBy="%s" folder=%s][/reviews]',
					esc_attr( $woo_settings['single_order'] ),
					esc_attr( $woo_settings['single_order_by'] ),
					esc_attr( $folder )
				);

				echo wp_kses_post(do_shortcode($shortcode));
			}

			if ( $woo_settings['single_button'] ) {
				echo '<div style="margin-top: 20px;">';
				$shortcode = wp_sprintf('[recorder folder=%s][/recorder]', esc_attr( $folder ));

				echo wp_kses_post( do_shortcode( $shortcode ) );
				echo '</div>';
			}

			echo '</div>';
		}

		/**
		 * Adds recorder to WooCommerce email footer
		 *
		 * @return mixed
		 */
		// public function add_recorder_to_woocommerce_email_footer() {
		//  $woo = (array) $this->option()->get('woocommerce');
		//  if ( ! $woo['order_complete_email'] ) {
		//      return false;
		//  }

		//  echo wp_kses_post($this->globals()->get_email_append_message());
		// }
	}

	// Initialize the class.
	WooCommerce::init();
}
