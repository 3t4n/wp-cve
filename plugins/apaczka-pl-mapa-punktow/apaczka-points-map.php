<?php
/**
 * Plugin Name: Apaczka.pl Mapa Punktów
 * Description: Wtyczka pozwoli Ci w prosty sposób skonfigurować i wyświetlić mapę punktów dla twoich metod dostawy tak aby twój Klient mógł wybrać punkt, z którego chce odebrać przesyłkę.
 * Version:     1.3.4
 * Text Domain: apaczka-pl-mapa-punktow
 * Author:      Inspire Labs
 * Author URI:  https://inspirelabs.pl/

 * Domain Path: /languages
 *
 * WC tested up to: 8.6.1
 *
 * Copyright 2020 Inspire Labs sp. z o.o.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package Mapa Punktów WooCommerce
 */

namespace Apaczka_Points_Map;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'APACZKA_POINTS_MAP_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'APACZKA_POINTS_MAP_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Main plugin helper.
 */
class Points_Map_Plugin {
	/**
	 * Maps_Plugin constructor.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Init Hooks.
	 */
	public function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front_scripts' ) );
		add_action( 'woocommerce_integrations_init', array( $this, 'include_wc_integration_class' ) );
		add_filter( 'woocommerce_integrations', array( $this, 'add_integration_filter' ) );
		add_action( 'init', array( $this, 'include_class' ) );
		add_action( 'init', array( $this, 'include_translations' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'plugin_action_links' ) );

        // integration with Woocommerce blocks start
        add_action(
            'woocommerce_blocks_checkout_block_registration',
            function( $integration_registry ) {
                require_once APACZKA_POINTS_MAP_DIR . 'includes/class-woo-blocks-integration.php';
                $integration_registry->register( new ApaczkaMP_Woo_Blocks_Integration() );
            }
        );
        add_action('woocommerce_store_api_checkout_update_order_from_request', array( $this, 'save_shipping_point_in_order_meta'), 10, 2 );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_frontend_blocks_scripts' ] );
        add_filter( 'woocommerce_package_rates', [ $this, 'filter_shipping_methods' ], PHP_INT_MAX );
        // integration with Woocommerce blocks end
	}

	/**
	 * Includes front scripts.
	 */
	public function enqueue_front_scripts() {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin_data = get_plugin_data( __FILE__ );

		if( is_checkout() ) {
			wp_enqueue_style( 'apaczka-points-map-style', APACZKA_POINTS_MAP_DIR_URL . 'public/css/apaczka-points-map.css', '', $plugin_data['Version'] );
			wp_enqueue_script( 'apaczka-client-map-js', 'https://mapa.apaczka.pl/client/apaczka.map.js', '', $plugin_data['Version'], false );
			wp_enqueue_script( 'apaczka-points-map-js', APACZKA_POINTS_MAP_DIR_URL . 'public/js/apaczka-points-map.js', array( 'apaczka-client-map-js', 'jquery', 'wc-checkout' ), $plugin_data['Version'], false );

			$app_id = isset( WC()->integrations->integrations['woocommerce-maps-apaczka']->settings['app_id'] ) ? WC()->integrations->integrations['woocommerce-maps-apaczka']->settings['app_id'] : null;

            wp_localize_script(
                'apaczka-points-map-js',
                'apaczka_points_map',
                array(
                    'translation' => array(
                        'delivery_point' => __( 'Delivery Point', 'apaczka-pl-mapa-punktow' ),
                    ),
                    'app_id'      => $app_id,
                )
            );
		}
		

	}

	/**
	 * Include class integration with WooCommerce.
	 */
	public function include_wc_integration_class() {
		if ( ! class_exists( 'Maps_Integration' ) ) {
			require_once APACZKA_POINTS_MAP_DIR . 'includes/class-wc-settings-integration.php';
		}
	}

	/**
	 * WooCommerce integration init.
	 *
	 * @param array $integrations .
	 * @return mixed
	 */
	public function add_integration_filter( $integrations ) {
		$integrations[] = 'Apaczka_Points_Map\WC_Settings_Integration';
		return $integrations;
	}

	/**
	 * Include required class.
	 */
	public function include_class() {
		require_once APACZKA_POINTS_MAP_DIR . 'includes/sdk/api.class.php';
	}

	/**
	 * Include translations.
	 */
	public function include_translations() {
		load_plugin_textdomain( 'apaczka-pl-mapa-punktow', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Display plugin action links.
	 *
	 * @param array $links .
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$plugin_links = array(
			'<a href="' . admin_url( 'admin.php?page=wc-settings&tab=integration&section=woocommerce-maps-apaczka' ) . '">' . __( 'Settings', 'apaczka-pl-mapa-punktow' ) . '</a>',
		);

		return array_merge( $plugin_links, $links );
	}


    public function save_shipping_point_in_order_meta( $order, $request ) {

        if( ! $order ) {
            return;
        }

        $request_body = json_decode($request->get_body(), true);

        if( isset( $request_body['extensions']['apaczka']['apaczka-point'] )
            && ! empty( $request_body['extensions']['apaczka']['apaczka-point'] ) ) {

            $apaczka_delivery_point = json_decode($request_body['extensions']['apaczka']['apaczka-point'], true);

            $apaczka_delivery_point = array_map( 'sanitize_text_field', $apaczka_delivery_point );

            update_post_meta( $order->get_ID(), 'apaczka_delivery_point', $apaczka_delivery_point );

            if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                $order->update_meta_data('apaczka_delivery_point', $apaczka_delivery_point );
                $order->save();
            }
        }

    }


    public function enqueue_frontend_blocks_scripts() {

        if( is_checkout() ) {

            if( has_block( 'woocommerce/checkout' ) ) {
                $map_config = $this->get_map_config();

                wp_enqueue_script('apaczka-mp-front-blocks',
                APACZKA_POINTS_MAP_DIR_URL . 'public/js/blocks/front-blocks.js'
                );
                wp_localize_script(
                    'apaczka-mp-front-blocks',
                    'apaczka_block',
                    array(
                        'button_text1'  => __('Select a Delivery Point', 'apaczka-pl-mapa-punktow'),
                        'button_text2'  => __('Change a Delivery Point', 'apaczka-pl-mapa-punktow'),
                        'selected_text' => __( 'Selected Parcel Locker:', 'apaczka-pl-mapa-punktow' ),
                        'alert_text'    => __( 'Delivery point must be chosen!', 'apaczka-pl-mapa-punktow' ),
                        'map_config'    => $map_config
                    )
                );
            }
        }

    }


    private function get_map_config() {
        $config = [];
        // Get all your existing shipping zones IDS.
        $zone_ids                = array_keys( [ '' ] + \WC_Shipping_Zones::get_zones() );

        foreach ( $zone_ids as $zone_id ) {

            $shipping_zone = new \WC_Shipping_Zone( $zone_id );

            $shipping_methods = $shipping_zone->get_shipping_methods( true, 'values' );

            foreach ( $shipping_methods as $instance_id => $shipping_method ) {
                if ( isset( $shipping_method->instance_settings['display_apaczka_map'] ) && 'yes' === $shipping_method->instance_settings['display_apaczka_map'] ) {
                    $geowidget_supplier = $shipping_method->instance_settings['supplier_apaczka_map'];

                    if ( 'all' === $geowidget_supplier || 'ALL' === $geowidget_supplier  ) {
                        $config[$instance_id]['geowidget_supplier'] = array('DHL_PARCEL', 'DPD', 'INPOST', 'POCZTA', 'UPS', 'PWR');
                    } else {
                        $single_carrier           = $shipping_method->instance_settings['supplier_apaczka_map'];
                        $config[$instance_id]['geowidget_supplier'] = array($single_carrier);
                    }

                    $config[$instance_id]['geowidget_only_cod'] = $shipping_method->instance_settings['only_cod_apaczka_map'];
                }
            }
        }

        return $config;
    }


    public function filter_shipping_methods( $rates ) {

        if( !empty($rates) && is_array($rates) && count($rates) === 1 && has_block( 'woocommerce/checkout' ) ) {
            $single_rate = reset($rates);
            $instance_id = $single_rate->instance_id;

            if( is_checkout() ) {
                wp_enqueue_script('apaczka-single', APACZKA_POINTS_MAP_DIR_URL . 'public/js/blocks/single.js', ['jquery']);
                wp_localize_script(
                    'apaczka-single',
                    'apaczka_single',
                    array(
                        'need_map' => ! empty( $this->get_map_config() ) ? true : false,
                        'config'   => $this->get_map_config(),
                        'instance_id'   => $instance_id
                    )
                );
            }
        }

        return $rates;
    }
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
	new Points_Map_Plugin();
	require_once APACZKA_POINTS_MAP_DIR . 'includes/class-shipping-integration-helper.php';
	require_once APACZKA_POINTS_MAP_DIR . 'includes/class-wc-shipping-integration.php';
	require_once APACZKA_POINTS_MAP_DIR . 'includes/class-delivery-points-map.php';
	
	add_action( 'before_woocommerce_init', function() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	} );

	if ( in_array( 'flexible-shipping/flexible-shipping.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
		require_once APACZKA_POINTS_MAP_DIR . 'includes/class-flexible-shipping-integration.php';
	}
}
