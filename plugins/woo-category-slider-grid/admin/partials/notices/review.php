<?php
/**
 * The review admin notice.
 *
 * @since        1.2.1
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin/partials/notices
 * @author     ShapedPlugin<support@shapedplugin.com>
 */
class Woo_Category_Slider_Review {

	/**
	 * Constructor function the class
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'all_admin_notice' ) );
		add_action( 'wp_ajax_sp-woocatslider-never-show-review-notice', array( $this, 'dismiss_review_notice' ) );
		add_action( 'wp_ajax_dismiss_smart_brand_notice', array( $this, 'dismiss_smart_brand_notice' ) );
		add_action( 'wp_ajax_dismiss_product_slider_notice', array( $this, 'dismiss_product_slider_notice' ) );
	}

	/**
	 * Display all admin notice for backend.
	 *
	 * @return void
	 */
	public function all_admin_notice() {
		$this->display_admin_notice();
		$this->smart_brand_install_admin_notice();
		$this->woo_product_slider_install_admin_notice();
	}
	/**
	 * Display admin notice.
	 *
	 * @return void
	 */
	public function display_admin_notice() {
		// Show only to Admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Variable default value.
		$review = get_option( 'sp_woo_category_slider_review_notice_dismiss' );
		$time   = time();
		$load   = false;

		if ( ! $review ) {
			$review = array(
				'time'      => $time,
				'dismissed' => false,
			);
			add_option( 'sp_woo_category_slider_review_notice_dismiss', $review );
		} else {
			// Check if it has been dismissed or not.
			if ( ( isset( $review['dismissed'] ) && ! $review['dismissed'] ) && ( isset( $review['time'] ) && ( ( $review['time'] + ( DAY_IN_SECONDS * 3 ) ) <= $time ) ) ) {
				$load = true;
			}
		}

		// If we cannot load, return early.
		if ( ! $load ) {
			return;
		}
		?>
		<div id="sp-woocatslider-review-notice" class="sp-woocatslider-review-notice">
			<div class="sp-woocatslider-plugin-icon">
				<img src="<?php echo SP_WCS_URL . 'admin/img/wcs-notice.svg'; ?>" alt="Category Slider for Woocommerce">
			</div>
			<div class="sp-woocatslider-notice-text">
				<h3>Enjoying <strong>Category Slider for Woocommerce</strong>?</h3>
				<p>Hope that you had a good experience with the <strong>Category Slider for Woocommerce</strong>. Would you please show us a little love by rating us in the <a href="https://wordpress.org/support/plugin/woo-category-slider-grid/reviews/?filter=5#new-post" target="_blank"><strong>WordPress.org</strong></a>?
				Just a minute to rate the plugin. Thank you!</p>

				<p class="sp-woocatslider-review-actions" data-nonce="<?php echo wp_create_nonce( 'dismiss-review-notice' ) ?>">
					<a href="https://wordpress.org/support/plugin/woo-category-slider-grid/reviews/?filter=5#new-post" target="_blank" class="button button-primary notice-dismissed rate-woo-category-slider-grid">Ok, you deserve it</a>
					<a href="#" class="notice-dismissed remind-me-later"><span class="dashicons dashicons-clock"></span>Nope, maybe later
</a>
					<a href="#" class="notice-dismissed never-show-again"><span class="dashicons dashicons-dismiss"></span>Never show again</a>
				</p>
			</div>
		</div>

		<script type='text/javascript'>

			jQuery(document).ready( function($) {
				$(document).on('click', '#sp-woocatslider-review-notice.sp-woocatslider-review-notice .notice-dismissed', function( event ) {
					if ( $(this).hasClass('rate-woo-category-slider-grid') ) {
						var notice_dismissed_value = "1";
					}
					if ( $(this).hasClass('remind-me-later') ) {
						var notice_dismissed_value =  "2";
						event.preventDefault();
					}
					if ( $(this).hasClass('never-show-again') ) {
						var notice_dismissed_value =  "3";
						event.preventDefault();
					}
					var ajax_nonce =$(this).parent('.sp-woocatslider-review-actions').data('nonce');
					$.post( ajaxurl, {
						action: 'sp-woocatslider-never-show-review-notice',
						notice_dismissed_data : notice_dismissed_value,
						ajax_nonce : ajax_nonce
					});

					$('#sp-woocatslider-review-notice.sp-woocatslider-review-notice').hide();
				});
			});

		</script>
		<?php
	}

	/**
	 * Dismiss review notice
	 *
	 * @since  2.1.14
	 *
	 * @return void
	 **/
	public function dismiss_review_notice() {
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
		// Check the update permission and nonce verification.
		if ( ! current_user_can('manage_options') || ! wp_verify_nonce( $nonce, 'dismiss-review-notice' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Authorization failed!', 'woo-category-slider-grid' ) ), 401 );
		}

		$review = get_option( 'sp_woo_category_slider_review_notice_dismiss' );
		if ( ! $review ) {
			$review = array();
		}
		switch ( $_POST['notice_dismissed_data'] ) {
			case '1':
				$review['time']      = time();
				$review['dismissed'] = false;
				break;
			case '2':
				$review['time']      = time();
				$review['dismissed'] = false;
				break;
			case '3':
				$review['time']      = time();
				$review['dismissed'] = true;
				break;
		}
		update_option( 'sp_woo_category_slider_review_notice_dismiss', $review );
		die;
	}

	/**
	 * Product Slider for WooCommerce install notice for backend.
	 *
	 * @since 1.4.12
	 */
	public function woo_product_slider_install_admin_notice() {

		if ( is_plugin_active( 'woo-product-slider/main.php' ) ) {
			return;
		}
		if ( get_option( 'sp-wps-notice-dismissed' ) ) {
			return;
		}

		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;

		if ( current_user_can( 'install_plugins' ) && 'sp_wcslider' === $the_current_post_type ) {

			$plugins     = array_keys( get_plugins() );
			$slug        = 'woo-product-slider';
			$icon        = SP_WCS_URL . 'admin/img/product-review-notice.svg';
			$text        = esc_html__( 'Install', 'woo-category-slider-grid' );
			$button_text = esc_html__( 'Install Now', 'woo-category-slider-grid' );
			$install_url = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug ) );
			$arrow       = '<svg width="14" height="10" viewBox="0 0 14 10" fill="#2171B1" xmlns="http://www.w3.org/2000/svg">
			<path d="M13.8425 4.5226L10.465 0.290439C10.3403 0.138808 10.164 0.0428426 9.97274 0.0225711C9.7815 0.00229966 9.59007 0.0592883 9.43833 0.181617C9.29698 0.313072 9.20835 0.494686 9.18999 0.6906C9.17163 0.886513 9.22487 1.08246 9.33917 1.23966L11.7425 4.26263H0.723328C0.531488 4.26263 0.347494 4.3416 0.211843 4.4822C0.0761915 4.62279 0 4.81349 0 5.01232C0 5.21116 0.0761915 5.40182 0.211843 5.54241C0.347494 5.68301 0.531488 5.76202 0.723328 5.76202H11.7425L9.33917 8.78499C9.22616 8.94269 9.17373 9.13831 9.19206 9.33383C9.21038 9.52935 9.29815 9.71082 9.43833 9.84303C9.58951 9.96682 9.78128 10.0247 9.97296 10.0044C10.1646 9.98405 10.3411 9.88716 10.465 9.73421L13.8425 5.50204C13.9447 5.36535 14.0001 5.19731 14.0001 5.02439C14.0001 4.85147 13.9447 4.68347 13.8425 4.54677V4.5226Z"></path>
		</svg>';

			if ( in_array( 'woo-product-slider/main.php', $plugins, true ) ) {
				$text        = esc_html__( 'Activate', 'woo-category-slider-grid' );
				$button_text = esc_html__( 'Activate', 'woo-category-slider-grid' );
				$install_url = esc_url( self_admin_url( 'plugins.php?action=activate&plugin=' . urlencode( 'woo-product-slider/main.php' ) . '&plugin_status=all&paged=1&s&_wpnonce=' . urlencode( wp_create_nonce( 'activate-plugin_woo-product-slider/main.php' ) ) ) );
			}

			$popup_url = esc_url(
				add_query_arg(
					array(
						'tab'       => 'plugin-information',
						'plugin'    => $slug,
						'TB_iframe' => 'true',
						'width'     => '772',
						'height'    => '446',
					),
					admin_url( 'plugin-install.php' )
				)
			);
			$nonce =  wp_create_nonce( 'wps-notice' );

			echo sprintf( '<div class="wps-notice notice is-dismissible" data-nonce="%7$s"><img src="%1$s"/><div class="wps-notice-text">To Create <strong>Interactive Product Sliders</strong> in your Shop to <strong>Boost Sales</strong>, %4$s the <a href="%2$s" class="thickbox open-plugin-details-modal"><strong>Product Slider for WooCommerce</strong></a> <a href="%3$s" rel="noopener" class="wps-activate-btn">%5$s</a><a href="https://shapedplugin.com/woocommerce-product-slider/lite-version-demo/" target="_blank" class="wps-demo-button">See How It Works<span>%6$s</span></a></div></div>', esc_url( $icon ), esc_url( $popup_url ), esc_url( $install_url ), esc_html( $text ), esc_html( $button_text ), $arrow, esc_attr($nonce) );
		}

	}

	/**
	 * Notice for Smart Brands for WooCommerce.
	 *
	 * Recommendation notice to install the Smart Brands for WooCommerce.
	 *
	 * @since 1.4.12
	 */
	public function smart_brand_install_admin_notice() {

		if ( is_plugin_active( 'smart-brands-for-woocommerce/smart-brands-for-woocommerce.php' ) ) {
			return;
		}
		if ( get_option( 'sp-wsb-notice-dismissed' ) ) {
			return;
		}

		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;

		if ( current_user_can( 'install_plugins' ) && 'sp_wcslider' === $the_current_post_type ) {
			$plugins     = array_keys( get_plugins() );
			$slug        = 'smart-brands-for-woocommerce';
			$icon        = SP_WCS_URL . 'admin/img/smart-brands.svg';
			$text        = esc_html__( 'Install', 'woo-category-slider-grid' );
			$button_text = esc_html__( 'Install Now', 'woo-category-slider-grid' );
			$install_url = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug ) );
			$arrow       = '<svg width="14" height="10" viewBox="0 0 14 10" fill="#2171B1" xmlns="http://www.w3.org/2000/svg">
			<path d="M13.8425 4.5226L10.465 0.290439C10.3403 0.138808 10.164 0.0428426 9.97274 0.0225711C9.7815 0.00229966 9.59007 0.0592883 9.43833 0.181617C9.29698 0.313072 9.20835 0.494686 9.18999 0.6906C9.17163 0.886513 9.22487 1.08246 9.33917 1.23966L11.7425 4.26263H0.723328C0.531488 4.26263 0.347494 4.3416 0.211843 4.4822C0.0761915 4.62279 0 4.81349 0 5.01232C0 5.21116 0.0761915 5.40182 0.211843 5.54241C0.347494 5.68301 0.531488 5.76202 0.723328 5.76202H11.7425L9.33917 8.78499C9.22616 8.94269 9.17373 9.13831 9.19206 9.33383C9.21038 9.52935 9.29815 9.71082 9.43833 9.84303C9.58951 9.96682 9.78128 10.0247 9.97296 10.0044C10.1646 9.98405 10.3411 9.88716 10.465 9.73421L13.8425 5.50204C13.9447 5.36535 14.0001 5.19731 14.0001 5.02439C14.0001 4.85147 13.9447 4.68347 13.8425 4.54677V4.5226Z"></path>
		</svg>';

			if ( in_array( 'smart-brands-for-woocommerce/smart-brands-for-woocommerce.php', $plugins, true ) ) {
				$text        = esc_html__( 'Activate', 'gallery-slider-for-woocommerce' );
				$button_text = esc_html__( 'Activate', 'gallery-slider-for-woocommerce' );
				$install_url = esc_url( self_admin_url( 'plugins.php?action=activate&plugin=' . urlencode( 'smart-brands-for-woocommerce/smart-brands-for-woocommerce.php' ) . '&plugin_status=all&paged=1&s&_wpnonce=' . urlencode( wp_create_nonce( 'activate-plugin_smart-brands-for-woocommerce/smart-brands-for-woocommerce.php' ) ) ) );
			}

			$popup_url = esc_url(
				add_query_arg(
					array(
						'tab'       => 'plugin-information',
						'plugin'    => $slug,
						'TB_iframe' => 'true',
						'width'     => '772',
						'height'    => '446',
					),
					admin_url( 'plugin-install.php' )
				)
			);
			$nonce =  wp_create_nonce( 'wsb-notice' );

			echo sprintf( '<div class="wsb-notice notice is-dismissible" data-nonce="%7$s"><img src="%1$s"/><div class="wsb-notice-text">To <strong>Add Brands</strong> and <strong>Highlight the Brands of the Products</strong> you sell, %4$s the <a href="%2$s" class="thickbox open-plugin-details-modal"><strong>Smart Brands for WooCommerce</strong></a> <a href="%3$s" rel="noopener" class="wsb-activate-btn">%5$s</a><a href="https://demo.shapedplugin.com/smart-brands-for-woocommerce/" target="_blank" class="wsb-demo-button">See How It Works<span>%6$s</span></a></div></div>', esc_url( $icon ), esc_url( $popup_url ), esc_url( $install_url ), esc_html( $text ), esc_html( $button_text ), $arrow, esc_attr($nonce) );
		}

	}

	/**
	 * Dismiss Product Slider for WooCommerce install notice message for the backend.
	 *
	 * @since 1.4.12
	 *
	 * @return void
	 */
	public function dismiss_product_slider_notice() {
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
		// Check the update permission and nonce verification.
		if ( ! current_user_can( 'install_plugins' ) || ! wp_verify_nonce( $nonce, 'wps-notice' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Authorization failed!', 'woo-category-slider-grid' ) ), 401 );
		}
		update_option( 'sp-wps-notice-dismissed', 1 );
		die;
	}
	/**
	 * Dismiss smart brand install notice message
	 *
	 * @since 1.4.12
	 *
	 * @return void
	 */
	public function dismiss_smart_brand_notice() {
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
		// Check the update permission and nonce verification.
		if ( ! current_user_can( 'install_plugins' ) || ! wp_verify_nonce( $nonce, 'wsb-notice' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Authorization failed!', 'woo-category-slider-grid' ) ), 401 );
		}
		update_option( 'sp-wsb-notice-dismissed', 1 );
		die;
	}
}

new Woo_Category_Slider_Review();
