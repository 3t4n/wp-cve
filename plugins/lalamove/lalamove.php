<?php
/**
 * Plugin Name:       Lalamove
 * Plugin URI:        https://wordpress.org/plugins/lalamove
 * Description:       A 24/7 on-demand delivery app
 * Version:           1.0.7
 * Author:            partner.support@lalamove.com
 * Author URI:        https://lalamove.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( version_compare( PHP_VERSION, '7.0.0', '<' ) ) {
	function lalamoves_upgrade_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}
		$error_message      = sprintf(
			'<strong>Error:</strong> Current PHP version (%1$s) does not meet minimum requirements for Lalamove Plugin. The plugin requires PHP %2$s.',
			phpversion(),
			'7.0.0'
		);
		$php_update_message = sprintf(
			'<a href="%s">Learn more about updating PHP version for your WordPress</a>.',
			esc_url( 'https://wordpress.org/support/update-php/' )
		);
		printf( '<div class="error"><p>%s</p><p>%s</p></div>', $error_message, $php_update_message );
	}
	add_action( 'all_admin_notices', 'lalamoves_upgrade_notice' );
	return;
}

require_once dirname( __FILE__ ) . '/includes/class-lalamove-app.php';
require_once dirname( __FILE__ ) . '/includes/class-lalamove-widget-actions.php';
require_once dirname( __FILE__ ) . '/includes/lalamove-shipping-method.php';
require_once dirname( __FILE__ ) . '/includes/utility-functions.php';

define( 'LALAMOVE_PATH', dirname( ( __FILE__ ) ) );
define( 'LALAMOVE_ASSETS_URL', plugins_url() . '/' . basename( LALAMOVE_PATH ) );

function lalamove_menu() {
	$page_menu_display = 'Lalamove';
	add_menu_page(
		$page_menu_display,
		$page_menu_display,
		'manage_woocommerce',
		'Lalamove',
		'lalamove_web',
		LALAMOVE_ASSETS_URL . '/assets/images/lalamove-favicon.svg'
	);
}

function lalamove_web() {
	$token             = lalamove_gen_jwt();
	$lalamove_hcountry = lalamove_get_country_id();
	if ( ! $lalamove_hcountry ) {
		require_once dirname( __FILE__ ) . '/pages/not-support-dc.php';
		return;
	}
	if ( ! $token ) {
		$store_url            = get_option( 'siteurl' );
		$auth_return_url      = admin_url() . 'admin.php?page=' . Lalamove_App::$menu_slug;
		$params               = array(
			'app_name'     => Lalamove_App::$app_name,
			'scope'        => Lalamove_App::$wc_auth_scope,
			'user_id'      => $store_url,
			'return_url'   => $auth_return_url,
			'callback_url' => lalamove_get_auth_callback_url(),
		);
		$query_string         = http_build_query( $params );
		$woocommerce_auth_url = $store_url . '/wc-auth/v1/authorize?' . $query_string;
		require_once dirname( __FILE__ ) . '/pages/auth.php';
		return;
	}

	$sub_page = lalamove_get_current_plugin_param( 'sub-page' );

	if ( lalamove_get_current_plugin_param( 'success' ) ) {
		lalamove_track( 'plugin_authorized' );
	}

	if ( $sub_page === 'place-order' ) {
		$place_order_url = lalamove_get_service_url() . '/place-order?id=' . lalamove_get_current_plugin_param( 'id' ) . '&name=' . lalamove_get_current_user_name()
				. '&token=' . lalamove_gen_jwt() . '&hcountry=' . $lalamove_hcountry . '&store_admin_url=' . lalamove_get_current_admin_url()
				. '&version=' . lalamove_get_version();
		require_once dirname( __FILE__ ) . '/pages/place-order.php';
		return;
	}

	if ( $sub_page === 'order-detail' ) {
		$order_detail_url = lalamove_get_service_url() . '/order-detail?uuid=' . lalamove_get_current_plugin_param( 'uuid' ) .
				'&token=' . lalamove_gen_jwt() . '&hcountry=' . $lalamove_hcountry . '&store_admin_url=' . lalamove_get_current_admin_url()
				. '&version=' . lalamove_get_version();
		require_once dirname( __FILE__ ) . '/pages/order-detail.php';
		return;
	}

	$home_url = lalamove_get_service_url()
			. '/?token=' . lalamove_gen_jwt() . '&hcountry=' . $lalamove_hcountry . '&store_admin_url=' . lalamove_get_current_admin_url()
			. '&version=' . lalamove_get_version();
	require_once dirname( __FILE__ ) . '/pages/home.php';
}

function lalamove_get_version() {
	if ( is_admin() ) {
		$plugin_data = get_plugin_data( __FILE__ );
		if ( ! empty( $plugin_data['Version'] ) ) {
			return $plugin_data['Version'];
		}
	}
	return '0';
}

if ( ! class_exists( 'WC_Integration_Lalamove' ) ) {
	class WC_Integration_Lalamove {
		public function __construct() {
			if ( lalamove_check_is_woocommerce_active() ) {
				add_action( 'admin_menu', 'lalamove_menu' );
				add_action( 'add_meta_boxes', array( Lalamove_Widget_Actions::get_instance(), 'add_meta_box' ) );
				$this->add_lalamove_column_at_order_list();
				add_action( 'woocommerce_shipping_init', 'lalamove_shipping_method' );
				add_filter( 'woocommerce_shipping_methods', array( $this, 'add_shipping_method' ) );
				add_filter( 'bulk_actions-edit-shop_order', array( Lalamove_Widget_Actions::get_instance(), 'multi_stop_order' ) );
				add_filter( 'handle_bulk_actions-edit-shop_order', array( Lalamove_Widget_Actions::get_instance(), 'multi_stop_order_action' ), 10, 3 );
				$this->add_filter_when_hpos_open();
			} else {
				add_action( 'admin_notices', array( $this, 'notice_activate_wc' ) );
			}
			register_activation_hook( __FILE__, 'WC_Integration_Lalamove::activation_hook' );
			register_deactivation_hook( __FILE__, 'WC_Integration_Lalamove::deactivation_hook' );
			register_uninstall_hook( __FILE__, 'WC_Integration_Lalamove::uninstall_hook' );
		}

		static function activation_hook() {
			lalamove_track( 'plugin_activated' );
		}

		static function deactivation_hook() {
			lalamove_remove_rest_api_key();
			lalamove_track( 'plugin_deactivated' );
		}

		static function uninstall_hook() {
			lalamove_track( 'plugin_uninstalled' );
		}

		private function add_lalamove_column_at_order_list() {
			add_filter( 'manage_edit-shop_order_columns', array( $this, 'lalamove_add_column_in_order_list' ), 20 );
			add_action( 'manage_shop_order_posts_custom_column', array( $this, 'lalamove_order_column_fecth_data' ), 20, 2 );
		}

		private function add_filter_when_hpos_open() {
			add_filter( 'manage_woocommerce_page_wc-orders_columns', array( $this, 'lalamove_add_column_in_order_list' ), 20 );
			add_action( 'manage_woocommerce_page_wc-orders_custom_column', array( $this, 'lalamove_order_column_fecth_data_with_hpos' ), 20, 2 );
			add_filter( 'bulk_actions-woocommerce_page_wc-orders', array( Lalamove_Widget_Actions::get_instance(), 'multi_stop_order' ) );
			add_filter( 'handle_bulk_actions-woocommerce_page_wc-orders', array( Lalamove_Widget_Actions::get_instance(), 'multi_stop_order_action' ), 10, 3 );
		}

		function lalamove_add_column_in_order_list( $columns ) {
			$reordered_columns = array();

			foreach ( $columns as $key => $column ) {
				$reordered_columns[ $key ] = $column;
				if ( $key == 'order_status' ) {
					// Inserting after "Status" column
					$reordered_columns[ Lalamove_App::$llm_order_column_key ] = Lalamove_App::$llm_order_column_title;
				}
			}
			return $reordered_columns;
		}

		function lalamove_order_column_fecth_data( $column, $post_id ) {
			if ( $column === Lalamove_App::$llm_order_column_key ) {
				$llm_order_ids = lalamove_get_order_id( $post_id );
				$llm_order_id  = is_null( $llm_order_ids ) ? null : $llm_order_ids[0];
				if ( $llm_order_id ) {
					$order_detail              = lalamove_get_order_detail( $llm_order_id );
					$lalamove_order_display_id = $order_detail->order_display_id ?? null;
					$lalamove_order_status     = $order_detail->order_status ?? null;
				}
				if ( ! isset( $lalamove_order_status ) ) {
					$button_text       = 'Send with Lalamove';
					$button_background = 'background: #F16622;';
				} elseif ( in_array( $lalamove_order_status, lalamove_get_send_again_with_status() ) ) {
					$button_text       = 'Send Again with Lalamove';
					$button_background = 'background: #F16622;';
				} else {
					$llm_order_web_url = Lalamove_App::$wc_llm_web_app_host . '/orders/' . $llm_order_id;
					echo '<small><em><a target="_blank" href="' . $llm_order_web_url . '">' . $lalamove_order_display_id . '</a>' . ' ' . lalamove_get_order_status_string( $lalamove_order_status ) . '</em></small>';
					return;
				}
				$cta_button_href = lalamove_get_current_admin_url() . '?page=Lalamove&sub-page=place-order&id=' . $post_id;
				echo '<div class="send-with-container" style="margin-top: 10px">';
				echo '<a href="' . esc_html( $cta_button_href ) . '" class="button button-send-with" style="font-weight: bold;text-align: center;color: #FFFFFF;font-size: 14px;border-radius: 10px;display: inline-block;line-height: 40px;height: 40px;' . esc_html( $button_background ) . ';" >
				' . esc_html( $button_text ) . '</a>';
				echo '</div>';
			}
		}

		function lalamove_order_column_fecth_data_with_hpos( $column, $post ) {
			self::lalamove_order_column_fecth_data($column, $post->get_id());
		}


		public function add_shipping_method( $methods ) {
			$methods['LALAMOVE_CARRIER_SERVICE'] = 'WC_Lalamove_Shipping_Method';
			return $methods;
		}

		public function notice_activate_wc() { ?>
			<div class="error">
				<p>
					<?php
					printf( esc_html__( 'Please install and activate %1$sWooCommerce%2$s to use Lalamove!' ), '<a href="' . esc_url( admin_url( 'plugin-install.php?tab=search&s=WooCommerce&plugin-search-input=Search+Plugins' ) ) . '">', '</a>' );
					?>
				</p>
			</div>
			<?php
		}
	}
	$wc_integration_lalamove = new WC_Integration_Lalamove();
}
