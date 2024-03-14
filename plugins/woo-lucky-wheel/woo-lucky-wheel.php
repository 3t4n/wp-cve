<?php
/**
 * Plugin Name: Lucky Wheel for WooCommerce
 * Description: Collect customers emails by letting them play interesting Lucky wheel game to get lucky discount coupon
 * Version: 1.1.2
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * Text Domain: woo-lucky-wheel
 * Domain Path: /languages
 * Copyright 2018-2023 VillaTheme.com. All rights reserved.
 * Requires at least: 5.0
 * Tested up to: 6.4.0
 * WC requires at least: 5.0
 * WC tested up to: 8.2.0
 * Requires PHP: 7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
define( 'VI_WOO_LUCKY_WHEEL_VERSION', '1.1.2' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce-lucky-wheel/woocommerce-lucky-wheel.php' ) ) {
	return;
}

define( 'VI_WOO_LUCKY_WHEEL_DIR', plugin_dir_path( __FILE__ ) );
define( 'VI_WOO_LUCKY_WHEEL_INCLUDES', VI_WOO_LUCKY_WHEEL_DIR . "includes" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_LUCKY_WHEEL_LANGUAGES', VI_WOO_LUCKY_WHEEL_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_LUCKY_WHEEL_ADMIN', VI_WOO_LUCKY_WHEEL_DIR . "admin" . DIRECTORY_SEPARATOR );
define( 'VI_WOO_LUCKY_WHEEL_FRONTEND', VI_WOO_LUCKY_WHEEL_DIR . "frontend" . DIRECTORY_SEPARATOR );
$plugin_url = plugins_url( '', __FILE__ );
$plugin_url = str_replace( '/includes', '', $plugin_url );
define( 'VI_WOO_LUCKY_WHEEL_CSS', $plugin_url . "/css/" );
define( 'VI_WOO_LUCKY_WHEEL_JS', $plugin_url . "/js/" );
define( 'VI_WOO_LUCKY_WHEEL_IMAGES', $plugin_url . "/images/" );

require_once VI_WOO_LUCKY_WHEEL_INCLUDES . "data.php";
require_once VI_WOO_LUCKY_WHEEL_INCLUDES . "functions.php";
require_once VI_WOO_LUCKY_WHEEL_INCLUDES . "mobile_detect.php";
require_once VI_WOO_LUCKY_WHEEL_INCLUDES . "support.php";

vi_include_folder( VI_WOO_LUCKY_WHEEL_ADMIN, 'VI_WOO_LUCKY_WHEEL_Admin_' );
vi_include_folder( VI_WOO_LUCKY_WHEEL_FRONTEND, 'VI_WOO_LUCKY_WHEEL_Frontend_' );


if ( ! class_exists( 'Woo_Lucky_Wheel' ) ):
	class Woo_Lucky_Wheel {
		protected $settings;

		public function __construct() {

			add_action( 'plugins_loaded', function () {
				if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
					include_once VI_WOO_LUCKY_WHEEL_INCLUDES . 'includes/support.php';
				}

				$environment = new \VillaTheme_Require_Environment( [
						'plugin_name'     => 'Lucky Wheel for WooCommerce',
						'php_version'     => '7.0',
						'wp_version'      => '5.0',
						'wc_version'      => '6.0',
						'require_plugins' => [
							[
								'slug' => 'woocommerce',
								'name' => 'WooCommerce',
							],
						]
					]
				);

				if ( $environment->has_error() ) {
					return;
				}
			} );
			add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );
			$this->settings = VI_WOO_LUCKY_WHEEL_DATA::get_instance();
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'init', array( $this, 'create_custom_post_type' ) );
			add_filter( 'manage_wlwl_email_posts_columns', array( $this, 'add_column' ), 10, 1 );
			add_action( 'manage_wlwl_email_posts_custom_column', array( $this, 'add_column_data' ), 10, 2 );
			add_filter(
				'plugin_action_links_woo-lucky-wheel/woo-lucky-wheel.php', array(
					$this,
					'settings_link'
				)
			);
		}

		public function before_woocommerce_init() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		}

		public function settings_link( $links ) {
			$settings_link = '<a href="' . admin_url( 'admin.php' ) . '?page=woo-lucky-wheel" title="' . esc_html__( 'Settings', 'woo-lucky-wheel' ) . '">' . esc_html__( 'Settings', 'woo-lucky-wheel' ) . '</a>';
			array_unshift( $links, $settings_link );

			return $links;
		}

		public function create_custom_post_type() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( post_type_exists( 'wlwl_email' ) ) {
				return;
			}
			$args = array(
				'labels'              => array(
					'name'               => esc_html__( 'Lucky Wheel Email', 'woo-lucky-wheel' ),
					'singular_name'      => esc_html__( 'Email', 'woo-lucky-wheel' ),
					'menu_name'          => esc_html_x( 'Emails', 'Admin menu', 'woo-lucky-wheel' ),
					'name_admin_bar'     => esc_html_x( 'Emails', 'Add new on Admin bar', 'woo-lucky-wheel' ),
					'view_item'          => esc_html__( 'View Email', 'woo-lucky-wheel' ),
					'all_items'          => esc_html__( 'Email Subscribe', 'woo-lucky-wheel' ),
					'search_items'       => esc_html__( 'Search Email', 'woo-lucky-wheel' ),
					'parent_item_colon'  => esc_html__( 'Parent Email:', 'woo-lucky-wheel' ),
					'not_found'          => esc_html__( 'No Email found.', 'woo-lucky-wheel' ),
					'not_found_in_trash' => esc_html__( 'No Email found in Trash.', 'woo-lucky-wheel' )
				),
				'description'         => esc_html__( 'Lucky Wheel for WooCommerce emails.', 'woo-lucky-wheel' ),
				'public'              => false,
				'show_ui'             => true,
				'capability_type'     => 'post',
				'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'        => true,
				'publicly_queryable'  => false,
				'exclude_from_search' => true,
				'show_in_menu'        => false,
				'hierarchical'        => false,
				'rewrite'             => false,
				'query_var'           => false,
				'supports'            => array( 'title' ),
				'show_in_nav_menus'   => false,
				'show_in_admin_bar'   => false,
			);
			register_post_type( 'wlwl_email', $args );
		}

		public function add_column( $columns ) {
			$columns['customer_name'] = esc_html__( 'Customer name', 'woo-lucky-wheel' );
			$columns['spins']         = esc_html__( 'Number of spins', 'woo-lucky-wheel' );
			$columns['last_spin']     = esc_html__( 'Last spin', 'woo-lucky-wheel' );
			$columns['label']         = esc_html__( 'Labels', 'woo-lucky-wheel' );
			$columns['coupon']        = esc_html__( 'Coupons', 'woo-lucky-wheel' );

			return $columns;
		}

		public function add_column_data( $column, $post_id ) {
			switch ( $column ) {
				case 'customer_name':
					if ( get_post( $post_id )->post_content ) {
						echo wp_kses_post( get_the_content( $post_id ) );
					}
					break;
				case 'spins':
					if ( get_post_meta( $post_id, 'wlwl_spin_times', true ) ) {
						echo esc_html( get_post_meta( $post_id, 'wlwl_spin_times', true )['spin_num'] );
					}
					break;
				case 'last_spin':
					if ( get_post_meta( $post_id, 'wlwl_spin_times', true ) ) {
						echo date( 'Y-m-d h:i:s', get_post_meta( $post_id, 'wlwl_spin_times', true )['last_spin'] );
					}
					break;

				case 'label':
					if ( get_post_meta( $post_id, 'wlwl_email_labels', true ) ) {
						$label = get_post_meta( $post_id, 'wlwl_email_labels', true );
						if ( sizeof( $label ) > 1 ) {
							for ( $i = sizeof( $label ) - 1; $i >= 0; $i -- ) {
								echo '<p>' . esc_html( $label[ $i ] ) . '</p>';
							}
						} else {
							echo esc_html( $label[0] );
						}
					}
					break;
				case 'coupon':
					if ( get_post_meta( $post_id, 'wlwl_email_coupons', true ) ) {
						$coupon = get_post_meta( $post_id, 'wlwl_email_coupons', true );
						if ( sizeof( $coupon ) > 1 ) {
							for ( $i = sizeof( $coupon ) - 1; $i >= 0; $i -- ) {
								echo '<p>' . esc_html( $coupon[ $i ] ) . '</p>';
							}
						} else {
							echo esc_html( $coupon[0] );
						}
					}
					break;
			}
		}

		function load_plugin_textdomain() {
			$locale = is_admin() && function_exists( 'get_user_locale' ) ? get_user_locale() : get_locale();
			$locale = apply_filters( 'plugin_locale', $locale, 'woo-lucky-wheel' );
			load_textdomain( 'woo-lucky-wheel', VI_WOO_LUCKY_WHEEL_LANGUAGES . "woo-lucky-wheel-$locale.mo" );
			load_plugin_textdomain( 'woo-lucky-wheel', false, VI_WOO_LUCKY_WHEEL_LANGUAGES );
			if ( class_exists( 'VillaTheme_Support' ) ) {
				new VillaTheme_Support(
					array(
						'support'    => 'https://wordpress.org/support/plugin/woo-lucky-wheel/',
						'docs'       => 'http://docs.villatheme.com/?item=woocommerce-lucky-wheel',
						'review'     => 'https://wordpress.org/support/plugin/woo-lucky-wheel/reviews/?rate=5#rate-response',
						'pro_url'    => 'https://1.envato.market/qXBNY',
						'css'        => VI_WOO_LUCKY_WHEEL_CSS,
						'image'      => VI_WOO_LUCKY_WHEEL_IMAGES,
						'slug'       => 'woo-lucky-wheel',
						'menu_slug'  => 'woo-lucky-wheel',
						'version'    => VI_WOO_LUCKY_WHEEL_VERSION,
						'survey_url' => 'https://script.google.com/macros/s/AKfycbzk5aLzLjO_zOfhCe07T6QURjhyFMMaQjLkBOrcekwuAhLSmtGhaxEMgh-afE7flfrK/exec'
					)
				);
			}
		}

		function notification() {
			?>
            <div id="message" class="error">
                <p><?php esc_html_e( 'Please install and activate WooCommerce to use Lucky Wheel for WooCommerce.', 'woo-lucky-wheel' ); ?></p>
            </div>
			<?php
		}
	}
endif;

new Woo_Lucky_Wheel();
