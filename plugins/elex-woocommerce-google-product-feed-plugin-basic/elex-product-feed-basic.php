<?php

/*
  Plugin Name: ELEX WooCommerce Google Shopping (Google Product Feed) - Basic
  Plugin URI: https://elextensions.com/plugin/elex-woocommerce-google-product-feed-plugin-free/
  Description: Efficiently generate and manage Google Marketplace feeds for your WooCommerce Store.
  Version: 1.4.1
  WC requires at least: 2.6.0
  WC tested up to: 8.5
  Author: ELEXtensions
  Author URI: https://elextensions.com/
  Developer: ELEXtensions
  Developer URI: https://elextensions.com
  Text Domain: elex-product-feed
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'ELEX_PRODUCT_FEED_PLUGIN_PATH' ) ) {
	define( 'ELEX_PRODUCT_FEED_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'ELEX_PRODUCT_FEED_TEMPLATE_PATH' ) ) {
	define( 'ELEX_PRODUCT_FEED_TEMPLATE_PATH', ELEX_PRODUCT_FEED_PLUGIN_PATH . 'templates' );
}
if ( ! defined( 'ELEX_PRODUCT_FEED_MAIN_URL_PATH' ) ) {
	define( 'ELEX_PRODUCT_FEED_MAIN_URL_PATH', plugin_dir_url( __FILE__ ) );
}

// for Required functions
if ( ! function_exists( 'elex_gf_basic_is_woocommerce_active' ) ) {
	require_once  'elex-includes/elex-gf-functions.php' ;
}
// to check woocommerce is active
function woocommerce_activation_notice_in_basic_gf() {  ?>
	<div id="message" class="error">
		<p>
			<?php echo( esc_attr_e( 'WooCommerce plugin must be active for ELEX WooCommerce Google Shopping (Google Product Feed) - Basic to work.', 'elex-product-feed' ) ); ?>
		</p>
	</div>
	<?php
}
if ( ! ( elex_gf_basic_is_woocommerce_active() ) ) {
	add_action( 'admin_notices', 'woocommerce_activation_notice_in_basic_gf' );
	return;
} else {

	register_activation_hook( __FILE__, 'elex_gpf_activate_basic_plugin' );
	if ( ! function_exists( 'elex_gpf_activate_basic_plugin' ) ) {
		function elex_gpf_activate_basic_plugin() {
			if ( is_plugin_active( 'elex-woocommerce-google-product-feed-plugin/elex-woocommerce-google-product-feed-plugin.php' ) ) {
				deactivate_plugins( basename( __FILE__ ) );
				wp_die( esc_html_e( 'Oops! You tried installing the Basic version without deactivating the Premium version. Kindly deactivate ELEX Product Feed and then try again', 'elex-product-feed' ), '', array( 'back_link' => 1 ) );
			}
		}
	}
	add_action( 'admin_menu', 'elex_gpf_basic_add_menu' );
	function elex_gpf_basic_add_menu() {
		add_menu_page( 'Google Shopping Feed', 'Google Shopping Feed', 'manage_options', 'elex-product-feed-manage', 'elex_gpf_basic_sub_menu', esc_url( plugins_url() . '/elex-woocommerce-google-product-feed-plugin-basic/assets/icons/elex-logo.svg' ) );
		add_submenu_page( 'elex-product-feed-manage', 'Google Shopping Feed', 'Manage Feeds', 'manage_options', 'elex-product-feed-manage', 'elex_gpf_basic_sub_menu' );
		add_submenu_page( 'elex-product-feed-manage', 'Google Shopping Feed', 'Create Feed', 'manage_options', 'elex-product-feed', 'elex_gpf_basic_product_feed_actions' );
		add_submenu_page( 'elex-product-feed-manage', 'Google Shopping Feed', 'Settings', 'manage_options', 'elex-product-feed-settings', 'elex_gpf_basic_settings_tab_content' );
		add_submenu_page( 'elex-product-feed-manage', 'Product Feed', 'Go Premium!', 'manage_options', 'elex-product-feed-go-premium', 'elex_gpf_basic_go_premium' );
	}

	function elex_gpf_basic_settings_tab_content() {
		?>
	<h2 class='nav-tab-wrapper'>
		<a href='admin.php?page=elex-product-feed-manage' class='nav-tab  '><?php esc_html_e( 'Manage Feeds', 'elex-product-feed' ); ?></a>
		<a href='admin.php?page=elex-product-feed' class='nav-tab'><?php esc_html_e( 'Create Feed', 'elex-product-feed' ); ?></a>
		<a href='admin.php?page=elex-product-feed-settings' class='nav-tab nav-tab-active'><?php esc_html_e( 'Settings', 'elex-product-feed' ); ?></a>
		<a href="admin.php?page=elex-product-feed-go-premium" style="color:red;"  class="nav-tab"><?php esc_html_e( 'Go Premium!', 'elex-product-feed' ); ?></a>
	</h2>
	<?php
		include_once( 'templates/class-elex-settings-tab-fields.php' );
	}


	function elex_gpf_basic_sub_menu() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html_e( 'You do not have sufficient permissions to access this page.', 'elex-product-feed' ) );
		}
		include_once( 'includes/elex-manage-feeds-tab.php' );
	}


	function elex_gpf_basic_product_feed_actions() {
		include_once( 'includes/elex-settings-section.php' );
	}
	function elex_gpf_basic_go_premium() {
		?>
	<h2 class="nav-tab-wrapper">
		<a href="admin.php?page=elex-product-feed-manage" class="nav-tab  "><?php esc_html_e( 'Manage Feeds', 'elex-product-feed' ); ?></a>
		<a href="admin.php?page=elex-product-feed" class="nav-tab"><?php esc_html_e( 'Create Feed', 'elex-product-feed' ); ?></a>
		<a href='admin.php?page=elex-product-feed-settings' class='nav-tab '><?php esc_html_e( 'Settings', 'elex-product-feed' ); ?></a>
		<a href="admin.php?page=elex-product-feed-go-premium" style="color:red;"  class="nav-tab nav-tab-active"><?php esc_html_e( 'Go Premium!', 'elex-product-feed' ); ?></a>
	</h2>
	<br>
	<?php
		global $woocommerce;
		$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
		wp_enqueue_style( 'elex-gpf-bootstrap', ELEX_PRODUCT_FEED_MAIN_URL_PATH . 'resources/css/elex-market-styles.css', array(), $woocommerce_version );
		include_once( 'includes/market.php' );
	}

	function elex_gpf_basic_register_admin_page_menu() {
		$mypage = add_submenu_page(
			'exlude product',
			'ELEX Exclude Products',
			'ELEX Exclude Products',
			'manage_options',
			'elex-excluded-products',
			'elex_gpf_basic_admin_page_content'
		);
		add_action( 'load-' . $mypage, 'elex_gpf_basic_load_admin_page_menu' );
	}
	add_action( 'admin_menu', 'elex_gpf_basic_register_admin_page_menu' );

	function elex_gpf_basic_load_admin_page_menu() {
		global $woocommerce;
		$woocommerce_version = function_exists( 'WC' ) ? WC()->version : $woocommerce->version;
		wp_register_style( 'elex-exclude-product-page-style', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/css/elex-exclude-product-page-style.css', array(), $woocommerce_version );
		wp_enqueue_style( 'elex-exclude-product-page-style' );
		wp_register_script( 'elex-exclude-product-page-script', ELEX_PRODUCT_FEED_MAIN_URL_PATH . '/assets/js/elex-exclude-products-script.js', array(), $woocommerce_version );
		wp_enqueue_script( 'elex-exclude-product-page-script' );
	}

	function elex_gpf_basic_admin_page_content() {
		global $wpdb;
		$feed_id        = isset( $_GET['feed_id'] ) ? sanitize_text_field( $_GET['feed_id'] ) : '';
		$date_time      = isset( $_GET['date'] ) ? sanitize_text_field( $_GET['date'] ) : '';
		$prod_type      = isset( $_GET['type'] ) ? sanitize_text_field( $_GET['type'] ) : '';
		$table_name     = $wpdb->prefix . 'gpf_feeds';
		$meta_content   = elex_gpf_get_feed_data( $feed_id, 'report_data' );
		$report_data    = json_decode( $meta_content[0]['feed_meta_content'], true );
		$search = isset( $_GET['search'] ) ? sanitize_text_field( $_GET['search'] ) : '';
		$manage_feed_content = elex_gpf_get_feed_data( $feed_id, 'manage_feed_data' );
		$manage_feed_data = json_decode( $manage_feed_content[0]['feed_meta_content'], true );
		?>
	<!DOCTYPE html>
	<html>
	<div style="padding-top: 2%;"></div>
	<body>
	<ul class="header_banner">
	  <li class="elex-sub-header">
		  <ul  class="sub_header_banner">
			<li class="elex_delete_image_element">
				<?php
					echo esc_html__( 'List of Simple products excluded from', 'elex-product-feed' ) . ' ' . esc_html( $manage_feed_data ['name'] );
				?>
			</li>
			<li>
			</li>
		  </ul>
		  <?php echo esc_html__( ' Generated on', 'elex-product-feed' ) . ' ' . esc_html( $manage_feed_data ['modified_date'] ); ?>
		  <br>
		  <br>
		  <a href="admin.php?page=elex-product-feed-manage"><strong>Back to Manage Feeds</strong></a>
	  </li>
	  
	  <li class="elex-exc-prod-img" style="float:right"><img src="<?php echo esc_html( plugin_dir_url( __FILE__ ) . 'assets/images/product-image.jpg' ); ?>"></li>
	</ul>

	</body>
	</html>
	<div style="padding: 1% 2% 0% 75%;"><input value="<?php echo esc_html( $search ); ?>" style=" width: 65%;" type="text" id="elex_exclude_search_name"  placeholder="Search by Product name.." title="Type in a name"> <button onclick="elex_exclude_search_name_fun()" class="botton button-large button-primary">search</button><br></div>
	<table style="width: 95%;" id="elex_excluded_prods" class="wrap postbox ">
		<tr>
		   <th class='elex-gpf-exclude-id'>ID</th>
		   <th class='elex-gpf-exclude-name'>Name</th>
		   <th class='elex-gpf-exclude-reason'>Reason</th>
		</tr>
		<?php
		if ( ! isset( $_GET['feed_page'] ) ) {
			$current_page = 1;
		} else {
			$current_page = sanitize_text_field( $_GET['feed_page'] );
		}
		$max_pagination = 4;
		$products_each_iteration = 0;
		$start = ( $current_page - 1 ) * 100;
		$is_break = false;
		$counter = 0;

		if ( ! empty( $report_data[ $date_time ][ $prod_type ] ) ) {
			foreach ( $report_data[ $date_time ][ $prod_type ] as $google_attr => $prod_ids_array ) {
				foreach ( $prod_ids_array as  $id_prod_title ) {
					$prod_id = explode( '-', $id_prod_title )[0];
					$prod_title = str_replace( $prod_id . '-', '', $id_prod_title );
					if ( ! $search || strpos( strtolower( $prod_title ), strtolower( $search ) ) !== false ) {
						if ( $counter >= $start ) {
							if ( 100 == $products_each_iteration ) {
								$is_break = true;
								break;
							}
							$product = wc_get_product( $prod_id );
							if ( $product ) {
								$permalink = $product->get_permalink();
								?>
									<tr>
										<td class='elex-gpf-exclude-id'><a target="_blank" href="<?php echo esc_html( $permalink ); ?>"><?php echo esc_html( $prod_id ); ?></a></td>
										<td class='elex-gpf-exclude-name'><?php echo esc_html( htmlspecialchars_decode( $prod_title ) ); ?></td>
										<td class='elex-gpf-exclude-reason'><?php echo '<b>' . esc_html( $google_attr ) . '</b> is missing'; ?></td>
									</tr>
								<?php
								$products_each_iteration++;
							}
						}
						$counter++;
					}
				}
				if ( $is_break ) {
					break;
				}
			}
		}
		?>
	</table>
	<?php
	$pagination = 0;
		$type = 'total_' . $prod_type;
		if ( $products_each_iteration < 100 ) {
			$total_num = $counter;
		} else {
			$total_num = 0;
			if ( ! empty( $report_data[ $date_time ][ $prod_type ] ) ) {
				foreach ( $report_data[ $date_time ][ $prod_type ] as $key => $value ) {
					$total_num += count( $value );
				}
			}
		}
		if ( $total_num ) {
			$pagination = ceil( ( $total_num ) / 100 );
		}
		if ( $pagination > 1 ) {
			if ( $pagination == $max_pagination ) {
				$start = 1;
			} else {
				$start = $current_page;
				if ( $pagination == $current_page ) {
					$start = $current_page - 1;
				}
			}
			if ( 1 == $current_page ) {
				$left_disable = 'pag-disable';
				$left_href    = '#';
			} else {
				$left_disable = '';
				$left_href    = 'admin.php?page=elex-excluded-products&feed_id=' . $feed_id . '&date=' . $date_time . '&type=' . $prod_type . '&feed_page=' . ( $current_page - 1 ) . '&search=' . $search;
			}
			if ( $pagination == $current_page ) {
				$right_disable = 'pag-disable';
				$right_href    = '#';
			} else {
				$right_disable = '';
				$right_href    = 'admin.php?page=elex-excluded-products&feed_id=' . $feed_id . '&date=' . $date_time . '&type=' . $prod_type . '&feed_page=' . ( $current_page + 1 ) . '&search=' . $search;
			}
			?>
			<div style="padding-left: 73%;" class="pagination">
				<a href="<?php echo esc_html( $left_href ); ?> " class="<?php echo esc_html( $left_disable ); ?> ">&laquo;</a>
		<?php

			for ( $flag = $start; $flag <= $pagination; $flag++ ) {
				$active = '';
				if ( $flag == $current_page ) {
					$active = 'pag-active';
				}
				$href = 'admin.php?page=elex-excluded-products&feed_id=' . $feed_id . '&date=' . $date_time . '&type=' . $prod_type . '&feed_page=' . $flag . '&search=' . $search;
				?>
				<a href="<?php echo esc_html( $href ); ?>" class="<?php echo esc_html( $active ); ?> " > <?php echo esc_html( $flag ); ?> </a>
			<?php
				if ( $flag >= $start + $max_pagination - 1 ) {
					break;
				}
			}
			?>
				<a href="<?php echo esc_html( $right_href ); ?> " class="<?php echo esc_html( $right_disable ); ?> ">&raquo;</a>
			</div>
		<?php
		}

	}

	add_filter( 'cron_schedules', 'elex_gpf_basic_custom_schedules' );

	function elex_gpf_basic_custom_schedules( $schedules ) {
		$schedules['every_thirty_minutes'] = array(
			'interval' => 1800,
			'display' => __( 'Every thirty minutes', 'textdomain' ),
		);
		return $schedules;
	}

	if ( ! wp_next_scheduled( 'elex_run_every_thirty_minutes' ) ) {
		wp_schedule_event( time(), 'every_thirty_minutes', 'elex_run_every_thirty_minutes' );
	}
	add_action( 'elex_run_every_thirty_minutes', 'elex_gpf_basic_cron_function' );


	function elex_gpf_basic_cron_function() {
		include_once( 'includes/elex-cron-schedule.php' );
	}
	add_action( 'init', 'elex_gpf_basic_include_files' );

	function elex_gpf_basic_include_files() {
		include_once( 'includes/elex-ajax-functions.php' );
		include_once( 'includes/elex-manage-feed-ajax.php' );
		include_once( 'includes/elex-add-custom-fields.php' );
		include_once( 'includes/elex-save-settings-tab-fields.php' );
		include_once( 'includes/elex-plugin-install-functions.php' );
		include_once( 'includes/elex-plugin-public-functions.php' );
		include_once( 'includes/elex-previously-created-feeds.php' );
	}

	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'elex_gpf_basic_product_feed_action_links' );

	function elex_gpf_basic_product_feed_action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=elex-product-feed' ) . '">' . __( 'Settings', 'elex-product-feed' ) . '</a>',
			'<a href="https://elextensions.com/documentation/" target="_blank">' . __( 'Documentation', 'elex-product-feed' ) . '</a>',
			'<a href="https://elextensions.com/support/" target="_blank">' . __( 'Support', 'elex-product-feed' ) . '</a>',
		);
		return array_merge( $plugin_links, $links );
	}
	function elex_gpf_basic_load_plugin_textdomain() {
		load_plugin_textdomain( 'elex-product-feed', false, basename( dirname( __FILE__ ) ) . '/language/' );
	}
	add_action( 'plugins_loaded', 'elex_gpf_basic_load_plugin_textdomain' );

	// review component
	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once  ABSPATH . 'wp-admin/includes/plugin.php';
	}
	include_once __DIR__ . '/review_and_troubleshoot_notify/review-and-troubleshoot-notify-class.php';
	$data                      = get_plugin_data( __FILE__ );
	$data['name']              = $data['Name'];
	$data['basename']          = plugin_basename( __FILE__ );
	$data['rating_url']        = 'https://elextensions.com/plugin/elex-woocommerce-google-product-feed-plugin-free/#reviews/';
	$data['documentation_url'] = 'https://elextensions.com/knowledge-base/set-up-elex-google-product-feed-plugin/';
	$data['support_url']       = 'https://wordpress.org/support/plugin/elex-woocommerce-google-product-feed-plugin-basic/';

	new Elex_Review_Components( $data );
}
// High performance order tables compatibility.
add_action(
	'before_woocommerce_init',
	function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} 
);
