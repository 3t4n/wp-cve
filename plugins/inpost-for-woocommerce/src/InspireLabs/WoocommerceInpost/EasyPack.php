<?php

namespace InspireLabs\WoocommerceInpost;

use Exception;
use InspireLabs\WoocommerceInpost\EasyPack_Helper;
use InspireLabs\WoocommerceInpost\admin\Alerts;
use InspireLabs\WoocommerceInpost\admin\EasyPack_Product_Shipping_Method_Selector;
use InspireLabs\WoocommerceInpost\admin\EasyPack_Settings_General;
use InspireLabs\WoocommerceInpost\EmailFilters\NewOrderEmail;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_C2C;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_C2C_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Express;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Express_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Standard;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Local_Standard_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_LSE;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_LSE_COD;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Palette;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Method_Courier_Palette_COD;
use InspireLabs\WoocommerceInpost\shipping\Easypack_Shipping_Rates;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shippng_Parcel_Machines;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shippng_Parcel_Machines_COD;
use InspireLabs\WoocommerceInpost\shipx\services\courier_pickup\ShipX_Courier_Pickup_Service;
use InspireLabs\WoocommerceInpost\shipx\services\organization\ShipX_Organization_Service;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Price_Calculator_Service;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Service;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Status_Service;
use WC_Order;
use WC_Shipping_Method;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Parcel_Machines_Weekend;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Parcel_Machines_Economy;
use InspireLabs\WoocommerceInpost\shipping\EasyPack_Shipping_Parcel_Machines_Economy_COD;
use InspireLabs\WoocommerceInpost\EasyPackBulkOrders;
use function DebugQuickLook\Formatting\wrap_warning_types;
use Automattic\WooCommerce\Utilities\OrderUtil;


class EasyPack extends inspire_Plugin4 {

	const LABELS_DIRECTORY = __DIR__ . DIRECTORY_SEPARATOR . 'web' . DIRECTORY_SEPARATOR . 'labels';

	const CLASSES_DIRECTORY = __DIR__ . DIRECTORY_SEPARATOR . 'classes';

	const ATTRIBUTE_PREFIX = 'woo_inpost';

	const ENVIRONMENT_PRODUCTION = 'production';

	const ENVIRONMENT_SANDBOX = 'sandbox';


	public static $instance;

	public static $text_domain = 'woocommerce-inpost';

	protected $_pluginNamespace = "woocommerce-inpost";

	/**
	 * @var WC_Shipping_Method[]
	 */
	protected $shipping_methods = [];

	protected $settings;

	/**
	 * @var string
	 */
	private static $environment;

	private static $assets_js_uri;
	private static $assets_css_uri;


	/**
	 * @return string
	 */
	public static function getLabelsUri() {
		return plugins_url() . '/woo-inpost/web/labels/';
	}

	public function __construct() {
		parent::__construct();
		add_action( 'plugins_loaded', [ $this, 'init_easypack' ], 100 );
	}

	/**
	 * @return mixed
	 */
	public static function get_assets_js_uri() {
		return self::$assets_js_uri;
	}

	/**
	 * @return mixed
	 */
	public static function get_assets_css_uri() {
		return self::$assets_css_uri;
	}

	public static function EasyPack() {
		if ( self::$instance === null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init_easypack() {
		$this->setup_environment();
		self::$assets_js_uri  = $this->getPluginJs();
		self::$assets_css_uri = $this->getPluginCss();
		( new Geowidget_v5() )->init_assets();
		$this->init_alerts();
        $this->loadPluginTextDomain();

		add_filter( 'woocommerce_get_settings_pages', [ $this, 'woocommerce_get_settings_pages' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ], 75 );
		//add_action( 'woocommerce_checkout_after_order_review', [ $this, 'woocommerce_checkout_after_order_review' ] );
        add_filter( 'woocommerce_order_item_display_meta_value', [ $this, 'change_order_item_meta_value' ], 20, 3 );
        add_action('woocommerce_cart_item_removed', [ $this, 'clear_wc_shipping_cache' ] );
        add_action('woocommerce_add_to_cart', [ $this, 'clear_wc_shipping_cache' ] );
        add_action('woocommerce_after_cart_item_quantity_update', [ $this, 'clear_wc_shipping_cache' ] );
        add_action( 'woocommerce_before_checkout_form', [ $this, 'clear_wc_shipping_cache' ] );
        add_filter( 'woocommerce_locate_template', [ $this, 'easypack_woo_templates' ], 1, 3 );

        try {
			( new Easypack_Shipping_Rates() )->init();

            if( 'yes' === get_option('easypack_debug_mode') ) {

                if(  current_user_can('administrator') ) {
                    $this->init_shipping_methods();
                }

            } else {
                $this->init_shipping_methods();
            }
			
			( new EasyPackBulkOrders() )->hooks();
            ( new EasyPackCoupons() )->hooks();

			// integration with Woocommerce blocks start
			add_action(
                'woocommerce_blocks_checkout_block_registration',
                function( $integration_registry ) {
                    $integration_registry->register( new EasyPackWooBlocks() );
                }
            );

            add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_block_script' ], 100 );

            add_action('woocommerce_store_api_checkout_update_order_from_request', array( $this, 'block_checkout_save_parcel_locker_in_order_meta'), 10, 2 );
            // integration with Woocommerce blocks end

			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 75 );
			add_filter( 'woocommerce_shipping_methods', [ $this, 'woocommerce_shipping_methods' ], 1000 );

			add_filter( 'woocommerce_shipping_packages', [ $this, 'woocommerce_shipping_packages' ], 1000 );
			add_filter( 'woocommerce_package_rates', [ $this, 'filter_shipping_methods' ], PHP_INT_MAX );
			add_filter( 'woocommerce_get_order_item_totals', [ $this, 'show_parcel_machine_in_order_details' ], 2, 100 );
			$this->init_email_filters();
			( new EasyPack_Product_Shipping_Method_Selector )->handle_product_edit_hooks();
		} catch ( Exception $exception ) {
			error_log( $exception->getMessage() );
		}
	}

	public function woocommerce_checkout_after_order_review() {
		echo '<input type="hidden" id="parcel_machine_id"
                     name="parcel_machine_id" class="parcel_machine_id"/>
            <input type="hidden" id="parcel_machine_desc"
                   name="parcel_machine_desc" class="parcel_machine_desc"/>';
	}

	/**
	 * @return string
	 */
	public static function get_environment(): string {
		return self::$environment;
	}

	/**
	 * @return void
	 */
	private function setup_environment() {
		if ( self::ENVIRONMENT_SANDBOX === get_option( 'easypack_api_environment' ) ) {
			self::$environment = self::ENVIRONMENT_SANDBOX;
		} else {
			self::$environment = self::ENVIRONMENT_PRODUCTION;
		}
	}

	private function init_email_filters() {
		( new NewOrderEmail() )->init();
	}

	private function init_alerts() {
		$alerts = new Alerts();
	}

	/**
	 * @param array $items
	 *
	 * @param WC_Order $wcOrder
	 *
	 * @return array
	 */
    public function show_parcel_machine_in_order_details( $items, $wcOrder ) {
        $parcel_desc = html_entity_decode( $wcOrder->get_meta( '_parcel_machine_desc' ) );

        $parcel_machine_id = html_entity_decode( $wcOrder->get_meta( '_parcel_machine_id' ) );

        if ( isset( $items['shipping'] ) && ! empty( $parcel_machine_id ) && ! empty( $parcel_desc ) ) {
            $items['shipping']['value']
                .= '<br>'
                . sprintf( __( 'Selected parcel machine', 'woocommerce-inpost' )
                    . ': <br><span class="italic">%1s'
                    . '<br><span class="italic">%2s', $parcel_machine_id, $parcel_desc )
                . '</span>';
        }

        return $items;
    }


	public function init_shipping_methods() {

        $main_methods = [
            "inpost_locker_standard",
            "inpost_courier_palette",
            "inpost_courier_standard",
            "inpost_courier_c2c",
            "inpost_courier_express_1000",
            "inpost_courier_express_1200",
            "inpost_courier_express_1700",
            "inpost_locker_economy"
        ];

        $stored_organization = get_option( 'woo_inpost_organisation' );

        $servicesAllowed = [];

        // get Shipping methods from stored settings
        if( ! empty( $stored_organization ) && is_array( $stored_organization ) ) {
            if( isset( $stored_organization['services'] ) && is_array( $stored_organization['services'] ) ) {
                foreach ( $main_methods as $service ) {
                    if ( in_array( $service, $stored_organization['services'] ) ) {
                        $servicesAllowed[] = $service;
                    }                    
                }
            }
        }

        // trying to connect to API during 60 seconds and save data to settings or show special message
        if ( empty( $servicesAllowed ) || ! is_array( $servicesAllowed ) ) {

            $now = time();
            $limit_time_to_retry = 60 + (int) get_option( 'easypack_api_limit_connection', 0 ); // saved during saving API key

            if ( $limit_time_to_retry > $now ) {
                // try to connect to API only for 60 sec to avoid make website slow
                $this->get_or_update_data_from_api();
            } else {

                if ( ! empty( get_option('easypack_organization_id') )
                    && ! empty( get_option('easypack_token') )
                ) {

                    $alerts = new Alerts();
                    $error = sprintf( '%s <a target="_blank" href="https://inpost.pl/formularz-wsparcie">%s</a>',
                        __( 'We were unable to connect to the API within 60 seconds. Please try to re-save settings later or', 'woocommerce-inpost'),
                        __( 'contact to support', 'woocommerce-inpost' )
                    );
                    
                    $alerts->add_error($error);
                }
            }
        }

        if( is_array( $servicesAllowed ) && ! empty( $servicesAllowed ) ) {
            if (in_array(EasyPack_Shippng_Parcel_Machines::SERVICE_ID, $servicesAllowed)) {
                $easyPack_Shippng_Parcel_Machines = new EasyPack_Shippng_Parcel_Machines();
                $this->shipping_methods[] = $easyPack_Shippng_Parcel_Machines;

                $easyPack_Shippng_Parcel_Machines_Weekend = new EasyPack_Shipping_Parcel_Machines_Weekend();
                $this->shipping_methods[] = $easyPack_Shippng_Parcel_Machines_Weekend;


            }

            if (in_array(EasyPack_Shipping_Parcel_Machines_Economy::SERVICE_ID, $servicesAllowed)) {
                $easyPack_Shippng_Parcel_Machines_Economy = new EasyPack_Shipping_Parcel_Machines_Economy();
                $this->shipping_methods[] = $easyPack_Shippng_Parcel_Machines_Economy;

                $easyPack_Shippng_Parcel_Machines_Economy_COD = new EasyPack_Shipping_Parcel_Machines_Economy_COD();
                $this->shipping_methods[] = $easyPack_Shippng_Parcel_Machines_Economy_COD;
            }

            if (in_array(EasyPack_Shippng_Parcel_Machines_COD::SERVICE_ID, $servicesAllowed)) {
                $easyPack_Shippng_Parcel_Machines_Cod = new EasyPack_Shippng_Parcel_Machines_COD();
                $this->shipping_methods[] = $easyPack_Shippng_Parcel_Machines_Cod;

                if (in_array(EasyPack_Shipping_Method_Courier_Local_Standard::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier_local_standard = new EasyPack_Shipping_Method_Courier_Local_Standard();
                    $this->shipping_methods[] = $shipping_Method_Courier_local_standard;
                }

                if (in_array(EasyPack_Shipping_Method_Courier_LSE_COD::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier_LSE_COD = new EasyPack_Shipping_Method_Courier_LSE_COD();
                    $this->shipping_methods[] = $shipping_Method_Courier_LSE_COD;
                }


                if (in_array(EasyPack_Shipping_Method_Courier_COD::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier_COD = new EasyPack_Shipping_Method_Courier_COD();
                    $this->shipping_methods[] = $shipping_Method_Courier_COD;
                }

                if (in_array(EasyPack_Shipping_Method_Courier::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier = new EasyPack_Shipping_Method_Courier();
                    $this->shipping_methods[] = $shipping_Method_Courier;
                }

                if (in_array(EasyPack_Shipping_Method_Courier_LSE::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier_LSE = new EasyPack_Shipping_Method_Courier_LSE();
                    $this->shipping_methods[] = $shipping_Method_Courier_LSE;
                }

                if (in_array(EasyPack_Shipping_Method_Courier_Local_Standard_COD::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier_local_standard_cod = new EasyPack_Shipping_Method_Courier_Local_Standard_COD();
                    $this->shipping_methods[] = $shipping_Method_Courier_local_standard_cod;
                }

                if (in_array(EasyPack_Shipping_Method_Courier_Local_Express::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier_local_express = new EasyPack_Shipping_Method_Courier_Local_Express();
                    $this->shipping_methods[] = $shipping_Method_Courier_local_express;
                }

                if (in_array(EasyPack_Shipping_Method_Courier_Local_Express_COD::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier_local_express_cod = new EasyPack_Shipping_Method_Courier_Local_Express_COD();
                    $this->shipping_methods[] = $shipping_Method_Courier_local_express_cod;
                }

                if (in_array(EasyPack_Shipping_Method_Courier_Palette::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier_Palette = new EasyPack_Shipping_Method_Courier_Palette();
                    $this->shipping_methods[] = $shipping_Method_Courier_Palette;
                }

                if (in_array(EasyPack_Shipping_Method_Courier_Palette_COD::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier_Palette_Cod = new EasyPack_Shipping_Method_Courier_Palette_COD();
                    $this->shipping_methods[] = $shipping_Method_Courier_Palette_Cod;
                }

                if (in_array(EasyPack_Shipping_Method_Courier_C2C::SERVICE_ID, $servicesAllowed)) {
                    $shipping_Method_Courier_c2c = new EasyPack_Shipping_Method_Courier_C2C();
                    $this->shipping_methods[] = $shipping_Method_Courier_c2c;

                    $shipping_Method_Courier_c2c_cod = new EasyPack_Shipping_Method_Courier_C2C_COD();
                    $this->shipping_methods[] = $shipping_Method_Courier_c2c_cod;
                }
            }
        }

		EasyPack_Product_Shipping_Method_Selector::$inpost_methods = $this->shipping_methods;
	}

	public function woocommerce_shipping_methods( $methods ) {

		foreach ( $this->shipping_methods as $shipping_method ) {

			$methods[ $shipping_method->id ] = get_class( $shipping_method );
		}

		return $methods;
	}

	public function woocommerce_shipping_packages( $packages ) {

		if ( is_object( WC()->session ) ) {
			$cart = WC()->session->get( 'cart' );

			if ( empty( $cart ) ) {
				$methods_allowed_by_cart = [];
			} else {
				$methods_allowed_by_cart = ( new EasyPack_Product_Shipping_Method_Selector )->get_methods_allowed_by_cart( $cart );
			}

		}

		$rates         = $packages[0]['rates'];
		$rates_allowed = [];

		if( is_array( $rates ) && ! empty( $rates ) ) {
            foreach ( $rates as $k => $rate_object ) {
                // if Flexivble shipping is active we need check if some our Easypack methods is linked to FS
                if( EasyPack_Helper()->is_flexible_shipping_activated() ) {
                    $linked_method = '';
                    $instance_id = '';

                    if( stripos( $k, ':' ) ) {
                        $instance_id =  trim( explode(':', $k )[1] );
                    }
                    // check if some easypack method linked to Flexible Shipping
                    if( ! empty( $instance_id ) ) {
                        $linked_method = EasyPack_Helper()->get_method_linked_to_fs_by_instance_id( $instance_id );
                    }

                    if ( 0 === strpos( $k, 'flexible_shipping') ) {
                        // check if linked FS methods are allowed for all products in cart
                        if ( 0 === strpos( $linked_method, 'easypack_') ) {
                            if ( in_array( $k, $methods_allowed_by_cart ) ) {
                                $rates_allowed[$k] = $rate_object;
                            }
                        }
                    }
                }
                // if FS is not active or for not integrated methods
                if ( 0 === strpos( $k, 'easypack_' ) ) {
                    if ( in_array( $k, $methods_allowed_by_cart ) ) {
                        $rates_allowed[$k] = $rate_object;
                    }
                } else {
                    $rates_allowed[$k] = $rate_object;
                }
            }
        }

		if ( ! empty( $rates_allowed ) ) {
			$packages[0]['rates'] = $rates_allowed;
		}

		return $packages;
	}

	public	function woocommerce_get_settings_pages( $woocommerce_settings ) {
		new EasyPack_Settings_General;
		return $woocommerce_settings;
	}

	public function get_package_sizes() {
		return [
			'small'  => __( 'A 8 x 38 x 64 cm', 'woocommerce-inpost' ),
			'medium' => __( 'B 19 x 38 x 64 cm', 'woocommerce-inpost' ),
			'large'  => __( 'C 41 x 38 x 64 cm', 'woocommerce-inpost' ),
		];
	}

    public function get_package_sizes_xlarge() {
        return [
            'small'  => __( 'A 8 x 38 x 64 cm', 'woocommerce-inpost' ),
            'medium' => __( 'B 19 x 38 x 64 cm', 'woocommerce-inpost' ),
            'large'  => __( 'C 41 x 38 x 64 cm', 'woocommerce-inpost' ),
            'xlarge' => __( 'D 50 x 50 x 80 cm', 'woocommerce-inpost' ),
        ];
    }

	public function get_package_sizes_display() {
		return [
			'small'  => __( 'A', 'woocommerce-inpost' ),
			'medium' => __( 'B', 'woocommerce-inpost' ),
			'large'  => __( 'C', 'woocommerce-inpost' ),
		];
	}

	public function get_package_weights_parcel_machines() {
		return [
			'1'  => __( '1 kg', 'woocommerce-inpost' ),
			'2'  => __( '2 kg', 'woocommerce-inpost' ),
			'5'  => __( '5 kg', 'woocommerce-inpost' ),
			'10' => __( '10 kg', 'woocommerce-inpost' ),
			'15' => __( '15 kg', 'woocommerce-inpost' ),
			'20' => __( '20 kg', 'woocommerce-inpost' ),
		];
	}

	public function get_package_weights_courier() {
		return [
			'1'  => __( '1 kg', 'woocommerce-inpost' ),
			'2'  => __( '2 kg', 'woocommerce-inpost' ),
			'5'  => __( '5 kg', 'woocommerce-inpost' ),
			'10' => __( '10 kg', 'woocommerce-inpost' ),
			'15' => __( '15 kg', 'woocommerce-inpost' ),
			'20' => __( '20 kg', 'woocommerce-inpost' ),
			'25' => __( '25 kg', 'woocommerce-inpost' ),
		];
	}

	public function loadPluginTextDomain() {
		load_plugin_textdomain( 'woocommerce-inpost', false, 'inpost-for-woocommerce/languages' );
	}

	function getTemplatePathFull() {
		return implode( '/', [ $this->_pluginPath, $this->getTemplatePath() ] );
	}


	public function enqueue_scripts() {
        if( is_cart() || is_checkout() ) {
            wp_enqueue_style('easypack-front', $this->getPluginCss() . 'front.css');
        }

        if( is_checkout() ) {
            wp_enqueue_style('easypack-jbox-css', $this->getPluginCss() . 'jBox.all.min.css');
            wp_enqueue_script('easypack-jquery-modal', $this->getPluginJs() . 'jBox.all.min.js', ['jquery']);

            if( get_option( 'easypack_js_map_button' ) === 'yes' && ! has_block( 'woocommerce/checkout' ) ) {
                wp_enqueue_script('easypack-front-js', $this->getPluginJs() . 'front.js', ['jquery']);
                wp_localize_script(
                    'easypack-front-js',
                    'easypack_front_map',
                    array(
                        'button_text1'       => __( 'Select Parcel Locker', 'woocommerce-inpost' ),
                        'button_text2'       => __( 'Change Parcel Locker', 'woocommerce-inpost' ),
						'selected_text'      => __( 'Selected parcel locker:', 'woocommerce-inpost' ),
                        'geowidget_v5_token' => self::ENVIRONMENT_SANDBOX === self::get_environment()
                            ? get_option( 'easypack_geowidget_sandbox_token' )
                            : get_option( 'easypack_geowidget_production_token' )
                    )
                );
            }
        }
	}

	function enqueue_admin_scripts() {

        $current_screen = get_current_screen();

        if ( is_a( $current_screen, 'WP_Screen' ) && 'inpost_page_easypack_shipment' === $current_screen->id ) {
            wp_enqueue_style('easypack-admin', $this->getPluginCss() . 'admin.css');
        }

        //if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
        if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
            // HPOS usage is enabled.
            $post_id = isset( $_GET['id'] ) ? sanitize_text_field( $_GET['id'] ) : null;

            if( $post_id ) {
                $post_type = get_post_type($post_id);

                if ('shop_order_placehold' === $post_type) {
                    wp_enqueue_style('easypack-admin', $this->getPluginCss() . 'admin.css');
                }
            } else {

                if ( is_a( $current_screen, 'WP_Screen' ) && 'woocommerce_page_wc-orders' === $current_screen->id ) {
                    wp_enqueue_style('easypack-admin', $this->getPluginCss() . 'admin.css');
                }
            }

        } else {

            $post_id = isset( $_GET['post'] ) ? sanitize_text_field( $_GET['post'] ) : null;

            if( $post_id ) {
                $post_type = get_post_type($post_id);

                if ( 'shop_order' === $post_type ) {
                    wp_enqueue_style('easypack-admin', $this->getPluginCss() . 'admin.css');
                }
            }
        }

        if( EasyPack_Helper()->is_required_pages_for_modal() ) {
            wp_enqueue_style('easypack-admin-modal', $this->getPluginCss() . 'modal.css');
            wp_enqueue_style('easypack-jbox-css', $this->getPluginCss() . 'jBox.all.min.css');
        }

		wp_enqueue_script( 'easypack-admin', $this->getPluginJs() . 'admin.js', [ 'jquery' ] );
        wp_localize_script(
            'easypack-admin',
            'easypack_settings',
            array(
                'default_logo' => EasyPack()->getPluginImages() . 'logo/small/white.png'
            )
        );
		wp_enqueue_media(); //logo upload dependency

        if( EasyPack_Helper()->is_required_pages_for_modal() ) {
            wp_enqueue_script('easypack-admin-modal', $this->getPluginJs() . 'modal.js', ['jquery']);
            wp_enqueue_script('easypack-jquery-modal', $this->getPluginJs() . 'jBox.all.min.js', ['jquery']);
        }



        if ( is_a( $current_screen, 'WP_Screen' ) && 'woocommerce_page_wc-settings' === $current_screen->id ) {
            if( isset( $_GET['tab'] ) && $_GET['tab'] == 'easypack_general') {
                wp_register_script( 'easypack-admin-settings-page',
                    $this->getPluginJs() . 'admin-settings-page.js',
                    [ 'jquery' ],
                    '',
                    true );

                wp_localize_script(
                    'easypack-admin-settings-page',
                    'easypack_settings',
                    array(
                        'change_country_alert' => __( 'Are you sure to change the country?', 'woocommerce-inpost' ),
                        'debug_notice' =>  __( 'Does not work simultaneously with the option \'JS mode of map button\'',
                            'woocommerce-inpost' )
                    )
                );

                wp_enqueue_script( 'easypack-admin-settings-page' );

                wp_enqueue_style('easypack-admin', $this->getPluginCss() . 'admin.css');
            }
        }

        if ( is_a( $current_screen, 'WP_Screen' ) && 'woocommerce_page_wc-settings' === $current_screen->id ) {
            if( isset( $_GET['tab'] ) && $_GET['tab'] == 'shipping' && isset( $_GET['instance_id'] ) ) {
                wp_register_script( 'easypack-shipping-method-settings',
                    $this->getPluginJs() . 'shipping-settings-page.js',
                    [ 'jquery' ],
                    '',
                    true );

                wp_enqueue_script( 'easypack-shipping-method-settings' );

                wp_enqueue_style('easypack-admin', $this->getPluginCss() . 'admin.css');
            }
        }
	}


	/**
	 * @return ShipX_Shipment_Service
	 */
	public function get_shipment_service() {
		return new ShipX_Shipment_Service();
	}

	/**
	 * @return ShipX_Organization_Service
	 */
	public function get_organization_service() {
		return new ShipX_Organization_Service();
	}

	/**
	 * @return ShipX_Shipment_Price_Calculator_Service
	 */
	public function get_shipment_price_calculator_service() {
		return new ShipX_Shipment_Price_Calculator_Service();
	}

	/**
	 * @return ShipX_Courier_Pickup_Service
	 */
	public function get_courier_pickup_service() {
		return new ShipX_Courier_Pickup_Service();
	}

	/**
	 * @return ShipX_Shipment_Status_Service
	 */
	public function get_shipment_status_service() {
		return new ShipX_Shipment_Status_Service();
	}


    /**
     * Replace custom logo link of shipping method for correct view
     */
    public function change_order_item_meta_value( $value, $meta, $item ) {
		
		if ($item === null) {
			return $value; 
		}

        if( is_admin() && $item->get_type() === 'shipping' && $meta->key === 'logo' ) {
            if( !empty( $value ) ) {
                $value = '<img style="width: 60px; height: auto; background-size: cover;" src="' . esc_url( $value ) . '">';
            }
        }
        return $value;
    }

    /**
     * Clear WC shipping methods cache
     */
    public function clear_wc_shipping_cache() {
        \WC_Cache_Helper::get_transient_version( 'shipping', true );
    }

    /**
     * define path to Woocommerce templates in our plugin
     *
     */
    public function easypack_woo_templates( $template, $template_name, $template_path ) {
        global $woocommerce;
        $_template = $template;
        if ( ! $template_path ) {
            $template_path = $woocommerce->template_url;
        }

        $plugin_templates_path  = untrailingslashit( EasyPack()->getPluginFullPath() )  . '/resources/templates/';

        $template = locate_template(
            array(
                $template_path . $template_name,
                $template_name
            )
        );

        if( ! $template && file_exists( $plugin_templates_path . $template_name ) ) {
            $template = $plugin_templates_path . $template_name;
        }

        if ( ! $template ) {
            $template = $_template;
        }

        return $template;
    }


    public function get_package_sizes_gabaryt() {
        return [
            'small'  => __( 'Size A (8 x 38 x 64 cm)', 'woocommerce-inpost' ),
            'medium' => __( 'Size B (19 x 38 x 64 cm)', 'woocommerce-inpost' ),
            'large'  => __( 'Size C (41 x 38 x 64 cm)', 'woocommerce-inpost' ),
        ];
    }


    private function get_or_update_data_from_api() {
        try {
            $organization_service = EasyPack::EasyPack()->get_organization_service();
            $organization = $organization_service->query_organisation();
            if ( ! is_object( $organization ) ) {
                throw new Exception('Query organisation failed');
            }

        } catch(Exception $e) {
            error_log( $e->getMessage() );
        }
    }


	public function enqueue_block_script() {
        if( is_checkout() && has_block( 'woocommerce/checkout' )) {

            wp_enqueue_script('easypack-front-blocks-js', $this->getPluginJs() . 'front-blocks.js', ['jquery']);
            wp_localize_script(
                'easypack-front-blocks-js',
                'easypack_block',
                array(
                    'button_text1'       => __( 'Select Parcel Locker', 'woocommerce-inpost' ),
                    'button_text2'       => __( 'Change Parcel Locker', 'woocommerce-inpost' ),
                    'geowidget_v5_token' => self::ENVIRONMENT_SANDBOX === self::get_environment()
                        ? get_option( 'easypack_geowidget_sandbox_token' )
                        : get_option( 'easypack_geowidget_production_token' )
                )
            );

        }
    }


    public function block_checkout_save_parcel_locker_in_order_meta( $order, $request ) {

        if( ! $order ) {
            return;
        }

        $shipping_method_id = null;

        foreach( $order->get_items( 'shipping' ) as $item_id => $item ){
            $shipping_method_id          = $item->get_method_id();
            $shipping_method_instance_id = $item->get_instance_id();
        }

        $request_body = json_decode($request->get_body(), true);

        if( isset( $request_body['extensions']['inpost']['inpost-parcel-locker-id'] )
            && ! empty( $request_body['extensions']['inpost']['inpost-parcel-locker-id'] ) ) {

            $parcel_machine_id = sanitize_text_field( $request_body['extensions']['inpost']['inpost-parcel-locker-id'] );

            update_post_meta( $order->get_ID(), '_parcel_machine_id', $parcel_machine_id );
            $order->update_meta_data('_parcel_machine_id', $parcel_machine_id );
            $order->save();
        }
    }
	
	
	
	public function filter_shipping_methods( $rates ) {

        global $woocommerce;

        // API doesn't accept COD amount > 5000
        $methods_to_disable = [
            'easypack_parcel_machines_economy_cod',
            'easypack_parcel_machines_cod',
            'easypack_shipping_courier_c2c_cod',
            'easypack_cod_shipping_courier'
        ];

        $order_total_amount = floatval($woocommerce->cart->cart_contents_total) + floatval($woocommerce->cart->tax_total);

        if ( $order_total_amount > 5000 ) {
            foreach($rates as $rate_key => $rate) {
                if( in_array($rate->method_id, $methods_to_disable) ) {
                    unset($rates[$rate_key]);
                }

                if( EasyPack_Helper()->is_flexible_shipping_activated() ) {
                    $linked_method = '';
                    $instance_id = $rate->instance_id;
                    // check if some easypack method linked to Flexible Shipping
                    if( ! empty( $instance_id ) ) {
                        $linked_method = EasyPack_Helper()->get_method_linked_to_fs_by_instance_id( $instance_id );
                    }

                    if ( !empty($linked_method) && 0 === strpos( $rate_key, 'flexible_shipping') ) {
                        if ( 0 === strpos( $linked_method, 'easypack_') ) {
                            if( in_array($linked_method, $methods_to_disable) ) {
                                unset($rates[$rate_key]);
                            }
                        }
                    }
                }
            }
        }

        $methods_required_geowidget = [
            'easypack_parcel_machines_economy_cod',
            'easypack_parcel_machines_cod',
            'easypack_parcel_machines',
            'easypack_parcel_machines_weekend',
        ];

        if( !empty($rates) && is_array($rates) && count($rates) === 1 && has_block( 'woocommerce/checkout' ) ) {
            $single_rate = reset($rates);
            $linked_method = 'none';
            if( EasyPack_Helper()->is_flexible_shipping_activated() ) {
                $instance_id = $single_rate->instance_id;
                // check if some easypack method linked to Flexible Shipping
                if( ! empty( $instance_id ) ) {
                    $linked_method = EasyPack_Helper()->get_method_linked_to_fs_by_instance_id( $instance_id );
                }
            }

            if( is_checkout() ) {
                if( in_array($single_rate->method_id, $methods_required_geowidget)
                    || in_array($linked_method, $methods_required_geowidget)
                ) {
                    $config = 'parcelCollect';
                    if('easypack_parcel_machines_weekend' === $single_rate->method_id
                        || 'easypack_parcel_machines_weekend' === $linked_method
                    ) {
                        $config = 'parcelCollect247';
                    }

                    if('easypack_parcel_machines_cod' === $single_rate->method_id
                        || 'easypack_parcel_machines_economy_cod' === $single_rate->method_id
                        || 'easypack_parcel_machines_cod' === $linked_method
                        || 'easypack_parcel_machines_economy_cod' === $linked_method
                    ) {
                        $config = 'parcelCollectPayment';
                    }

                    wp_enqueue_script('easypack-single', $this->getPluginJs() . 'single.js', ['jquery']);
                    wp_localize_script(
                        'easypack-single',
                        'easypack_single',
                        array(
                            'need_map' => true,
                            'config'   => $config
                        )
                    );
                }
            }
        }

        return $rates;
    }


}




