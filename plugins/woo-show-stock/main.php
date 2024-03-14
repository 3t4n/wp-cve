<?php
/**
 * Plugin Name: Show Stock Status for WooCommerce
 * Description: Show the “Stock Quantity” for each product in the shop, category and archive pages.
 * Author: Bright Plugins
 * Version: 1.0.4
 * Author URI: https://brightplugins.com/
 * Text Domain: woo-show-stock
 * Domain Path:  /languages/
 * Requires PHP: 7.2.0
 * Requires at least: 4.9
 * Tested up to: 5.9
 * WC tested up to: 6.2
 * WC requires at least: 3.4
 */

function brightvessel_woocommerce_show_stock() {
	global $product;

	$low_stock_notify = wc_get_low_stock_amount( $product ); // get low stock from product

	// check if product type is not variable
	if ( !$product->is_type( 'variable' ) ) {
		if ( $product->get_manage_stock() ) { // if manage stock is enabled

			if ( !$product->is_in_stock() ) {
				echo "<div class='remaining-out-stock'>" . esc_html__( ' Out of stock', 'woo-show-stock' ) . "</div>";
				return;
			}

			$stockNum = $product->get_stock_quantity();
			if ( $stockNum <= $low_stock_notify ) { // if stock is low
				return printf(
					/* translators: number of stock . */

					__( "<div class='remaining-low-stock'> Only %s left in stock</div>", 'woo-show-stock' ),
					$stockNum
				);
			}
			return printf(
				/* translators: number of stock . */
				__( "<div class='remaining'> %s left in stock </div>", 'woo-show-stock' ),
				$stockNum
			);
		}
	} else {
		if ( $product->get_manage_stock() ) { // if manage stock is enabled
			$product_variations = $product->get_available_variations();
			$stock              = $product->get_stock_quantity();
			foreach ( $product_variations as $variation ) {
				//check if variant controles stock
				$is_stock_manage = get_post_meta( $variation['variation_id'] ?? 0, '_manage_stock', true );
				if ( 'yes' == $is_stock_manage ) {
					if ( empty( $variation['max_qty'] ) ) {
						$variation['max_qty'] = 0;
					}
					$stock += absint( $variation['max_qty'] );
				}
			}

			if ( $stock > 0 ) {
				if ( $stock <= $low_stock_notify ) { // if stock is low

					return printf(
						/* translators: number of stock . */
						__( "<div class='remaining-low-stock bpss-low-stock'>Only %s left in stock </div>", 'woo-show-stock' ),
						$stock
					);
				}

				return printf(
					/* translators: number of stock . */
					__( "<div class='remaining bpss-remaining'> %s left in stock </div>", 'woo-show-stock' ),
					$stock
				);
			}
		} else {

			$product_variations = $product->get_available_variations();
			$stock              = 0; //$product->get_stock_quantity();
			foreach ( $product_variations as $variation ) {
				//check if variant controles stock
				$is_stock_manage = get_post_meta( $variation['variation_id'] ?? 0, '_manage_stock', true );
				if ( 'yes' == $is_stock_manage ) {
					if ( empty( $variation['max_qty'] ) ) {
						$variation['max_qty'] = 0;
					}
					$stock += absint( $variation['max_qty'] );
				}
			}

			if ( $stock > 0 ) {
				if ( $stock <= $low_stock_notify ) { // if stock is low

					return printf(
						/* translators: number of stock . */
						__( "<div class='remaining-low-stock bpss-low-stock'>Only %s left in stock</div>", 'woo-show-stock' ),
						$stock
					);
				}

				return printf(
					/* translators: number of stock . */
					__( "<div class='remaining bpss-remaining'> %s left in stock</div>", 'woo-show-stock' ),
					$stock
				);
			}
		}
	}
}

if ( 'yes' === get_option( 'wc_always_show_stock' ) && null !== get_option( 'wc_show_stock_where' ) ) {
	add_action( get_option( 'wc_show_stock_where' ), 'brightvessel_woocommerce_show_stock', 10 );
}

// Add settings to the specific section we created before

add_filter( 'woocommerce_get_settings_products', 'brightvessel_woocommerce_show_stock_all_settings', 10, 2 );
/**
 * @param $settings
 * @param $current_section
 * @return mixed
 */
function brightvessel_woocommerce_show_stock_all_settings( $settings, $current_section ) {
	// Check the current section is what we want
	if ( 'inventory' === $current_section ) {
		$settings[] = [
			'name' => __( 'Stock Settings', 'woo-show-stock' ),
			'type' => 'title',
			'desc' => __( 'The following options are used to configure how to show your stock', 'woo-show-stock' ),
			'id'   => 'stockoptions',
		];

		$settings[] = [
			'name' => __( 'Always show stock', 'woo-show-stock' ),
			'type' => 'checkbox',
			'desc' => __( 'Always show available stock', 'woo-show-stock' ),
			'id'   => 'wc_always_show_stock',
		];

		$settings[] = [
			'name'    => __( 'Stock position', 'woo-show-stock' ),
			'type'    => 'select',

			'options' => [
				'woocommerce_after_shop_loop_item'        => __( 'After shop loop (recommended)', 'woo-show-stock' ),
				'woocommerce_after_shop_loop_item_title'  => __( 'After title', 'woo-show-stock' ),
				'woocommerce_before_shop_loop_item_title' => __( 'Before title', 'woo-show-stock' ),
			],
			'desc'    => __( 'Where the actual stock should be displayed', 'woo-show-stock' ),
			'id'      => 'wc_show_stock_where',
		];
		// todo: add new settings for for hook priroty
		$settings[] = [
			'type' => 'sectionend',
			'id'   => 'wc_settings_tab_stock',
		];
	}

	return $settings;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ) . '', 'bpwcssPluginMeta' );
/**
 * links in Plugin Meta
 *
 * @param  [array] $links
 * @return void
 */
function bpwcssPluginMeta( $links ) {
	$row_meta = array(
		'settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=products&section=inventory#stockoptions-description' ) . '">Stock Settings</a>',
		'support'  => '<a style="color:red;" target="_blank" href="' . esc_url( 'https://brightplugins.com/support' ) . '">Support</a>',

	);
	return array_merge( $links, $row_meta );
}

register_activation_hook( __FILE__, 'bpwcssPluginActivation' );
function bpwcssPluginActivation() {
	// set plugin instalation date
	$installed = get_option( 'bpwcss_installed' );
	if ( !$installed ) {
		update_option( 'bpwcss_installed', date( "Y/m/d" ) );
	}
}

function brightvessel_woocommerce_show_stock_check_notice() {
	if ( isset( $_GET['bvsclose'] ) && 'true' === $_GET['bvsclose'] ) {
		add_option( 'bvsclose', 1 );
	}
	if ( 1 !== (int) ( get_option( 'bvsclose' ) ) ) {
		add_action( 'admin_notices', 'brightvessel_woocommerce_show_stock_check_notice' );
	}
	// for review dismiss / check
	if ( isset( $_GET['bpwss-review-dismiss'] ) && $_GET['bpwss-review-dismiss'] == 1 ) {
		update_option( 'bpwss-review-dismiss', 1 );
	}
	if ( isset( $_GET['bpwss-later-dismiss'] ) && $_GET['bpwss-later-dismiss'] == 1 ) {
		set_transient( 'bpwss-later-dismiss', 1, 2 * DAY_IN_SECONDS );
	}
}
add_action( 'admin_init', 'brightvessel_woocommerce_show_stock_check_notice' );

if ( time() > strtotime( get_option( 'bpwcss_installed' ) . ' + 3 Days' ) ) {
	add_action( 'admin_notices', 'bpShowStockReview' );
}

/**
 * @return null
 */
function bpShowStockReview() {
	$dismissPram     = array( 'bpwss-review-dismiss' => '1' );
	$bpwssMaybeLater = array( 'bpwss-later-dismiss' => '1' );

	if ( get_option( 'bpwss-review-dismiss' ) || get_transient( 'bpwss-later-dismiss' ) ) {
		return;
	}?>
        <div class="notice ciplugin-review">
        <p style="font-size:15px;"><img draggable="false" class="emoji" alt="🎉" src="https://s.w.org/images/core/emoji/11/svg/1f389.svg"><strong style="font-size: 19px; margin-bottom: 5px; display: inline-block;" ><?php echo __( 'Thanks for using Show stock for WooCommerce.', 'wpgs' ); ?></strong><br> <?php _e( 'If you can spare a minute, please help us by leaving a 5 star review on WordPress.org.', 'wpgs' );?></p>
        <p class="dfwc-message-actions">
            <a style="margin-right:5px;" href="https://wordpress.org/support/plugin/woo-show-stock/reviews/#new-post" target="_blank" class="button button-primary button-primary"><?php _e( 'Happy To Help', 'wpgs' );?></a>
            <a style="margin-right:5px;" href="<?php echo esc_url( add_query_arg( $bpwssMaybeLater ) ); ?>" class="button button-alt"><?php _e( 'Maybe later', 'wpgs' );?></a>
            <a href="<?php echo esc_url( add_query_arg( $dismissPram ) ); ?>" class="dfwc-button-notice-dismiss button button-link"><?php _e( 'Hide Notification', 'wpgs' );?></a>
        </p>
        </div>
        <?php
}
