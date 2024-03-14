<?php

namespace InspireLabs\WoocommerceInpost;

class Geowidget_v5 {

    //const GEOWIDGET_PRODUCTION_JS_SRC = 'https://geowidget.inpost.pl/inpost-geowidget.js';
    //const GEOWIDGET_SANDBOX_JS_SRC = 'https://sandbox-easy-geowidget-sdk.easypack24.net/inpost-geowidget.js';
    //const GEOWIDGET_PRODUCTION_CSS_SRC = 'https://geowidget.inpost.pl/inpost-geowidget.css';
    //const GEOWIDGET_SANDBOX_CSS_SRC = 'https://sandbox-easy-geowidget-sdk.easypack24.net/inpost-geowidget.css';

	const GEOWIDGET_WIDTH = 800;

	const GEOWIDGET_HEIGHT = 600;

	private $environment;
	private $assets_js_uri;

	public function __construct() {
		$this->environment   = EasyPack::get_environment();
		$this->assets_js_uri = EasyPack::get_assets_js_uri();
	}

	public function init_assets() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ], 76 );
		//add_action( 'admin_footer', [ $this, 'admin_footer' ], 76 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 76 );
		//add_action( 'wp_footer', [ $this, 'frontFooter' ], 76 );

	}

	public
	function enqueue_scripts() {
        if( is_checkout() ) {
            wp_enqueue_style('geowidget-css', $this->get_geowidget_css_src());
            wp_enqueue_script('geowidget-inpost', $this->get_geowidget_js_src() );
        }
	}

	function enqueue_admin_scripts() {

        if( EasyPack_Helper()->is_required_pages_for_modal() ) {
            wp_enqueue_script('easypack-admin-geowidget-settings',
                $this->assets_js_uri . 'admin-geowidget-settings.js',
                ['jquery']);
            wp_localize_script('easypack-admin-geowidget-settings',
                'easypackAdminGeowidgetSettings',
                [
                    'width' => self::GEOWIDGET_WIDTH,
                    'height' => self::GEOWIDGET_HEIGHT,
                    'token' => $this->get_token(),
                    'title' => __('Select parcel locker', 'woocommerce-inpost')
                ]
            );

            wp_enqueue_style('geowidget-css', $this->get_geowidget_css_src());
        }

        $current_screen = get_current_screen();

        // only on settings page
        if ( is_a( $current_screen, 'WP_Screen' ) && 'woocommerce_page_wc-settings' === $current_screen->id ) {
            if( isset( $_GET['tab'] ) && $_GET['tab'] == 'easypack_general') {
                // color picker on settings page
                wp_enqueue_style( 'wp-color-picker' );
                wp_enqueue_script( 'wp-color-picker' );
                add_action( 'admin_footer', array( $this, 'easypack_color_picker_script'), 99 );

            }
        }

        if ( is_a( $current_screen, 'WP_Screen' ) && 'shop_order' === $current_screen->id
            || 'woocommerce_page_wc-orders' === $current_screen->id ) {
            if (isset($_GET['action']) && $_GET['action'] == 'edit') {
                wp_enqueue_script('geowidget-inpost', $this->get_geowidget_js_src() );
            }
        }

        if ( is_a( $current_screen, 'WP_Screen' ) && 'woocommerce_page_wc-settings' === $current_screen->id ) {
            if (isset($_GET['tab']) && $_GET['tab'] == 'easypack_general') {
                wp_enqueue_script('geowidget-inpost', $this->get_geowidget_js_src() );
            }
        }

	}

    /**
     * @return string
     */
    private function get_geowidget_js_src(): string {
        return EasyPack::ENVIRONMENT_SANDBOX === $this->environment
            ? EasyPack()->getPluginJs() . 'sandbox-inpost-geowidget.js'
            : EasyPack()->getPluginJs() . 'inpost-geowidget.js';
    }

    /**
     * @return string
     */
    private function get_geowidget_css_src(): string {
        return EasyPack::ENVIRONMENT_SANDBOX === $this->environment
            ? EasyPack()->getPluginCss() . 'inpost-geowidget.css'
            : EasyPack()->getPluginCss() . 'inpost-geowidget.css';
    }

	function admin_footer() {
		$current_screen = get_current_screen();
        if ( is_a( $current_screen, 'WP_Screen' ) && 'shop_order' === $current_screen->id
            || 'woocommerce_page_wc-orders' === $current_screen->id ) {
            if (isset($_GET['action']) && $_GET['action'] == 'edit') {
				
				printf( '<script async src="%s"></script>',
					$this->get_geowidget_js_src() );
					
			}
		}

        if ( is_a( $current_screen, 'WP_Screen' ) && 'woocommerce_page_wc-settings' === $current_screen->id ) {
            if (isset($_GET['tab']) && $_GET['tab'] == 'easypack_general') {
                printf( '<script async src="%s"></script>',
                    $this->get_geowidget_js_src() );
            }
        }
	}

	public function frontFooter() {
        if( is_checkout() ) {
            printf('<script async src="%s"></script>',
                $this->get_geowidget_js_src());
        }
	}

	public function get_token(): string {
		return EasyPack::ENVIRONMENT_SANDBOX === $this->environment
			? get_option( 'easypack_geowidget_sandbox_token' )
			: get_option( 'easypack_geowidget_production_token' );
	}


	/**
	 * @param string $shipping_method_id
	 *
	 * @return string
	 */
	public function get_pickup_delivery_configuration( string $shipping_method_id
	): string {

        switch ( $shipping_method_id ) {
            case 'easypack_parcel_machines':
                return 'parcelCollect';

            case 'easypack_parcel_machines_economy':
                return 'parcelCollect';

            case 'easypack_parcel_machines_economy_cod':
                return 'parcelCollectPayment';

            case 'easypack_parcel_machines_cod':
                return 'parcelCollectPayment';

            case 'easypack_shipping_courier_c2c':
                return 'parcelSend';

            case 'easypack_parcel_machines_weekend':
                return 'parcelCollect247';

        }

	}


	/**
	 * @return string
	 */
	public function get_pickup_point_configuration(): string {
		return 'parcelSend';
	}

    // color picker on settings page
    public function easypack_color_picker_script(){
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function($){
                $('#easypack_custom_button_css').wpColorPicker({
                    defaultColor: false,
                    change: function(event, ui){ },
                    clear: function(){ },
                    hide: true,
                    palettes: true
                });
            });
        </script>

        <?php
    }
}
