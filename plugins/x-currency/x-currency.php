<?php

defined( 'ABSPATH' ) || exit;

use XCurrency\WpMVC\App;
use XCurrency\App\Providers\ProVersionUpdateServiceProvider;

/**
 * Plugin Name:       X-Currency
 * Description:       Currency Switcher for WooCommerce custom currency, exchange rates, currency by country, pay in selected currency
 * Version:           1.5.2
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Tested up to:      6.4
 * Author:            DoatKolom
 * Author URI:        https://doatkolom.com/
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       x-currency
 * Domain Path:       /languages
 */

require_once __DIR__ . '/vendor/vendor-src/autoload.php';
require_once __DIR__ . '/app/Helpers/helper.php';

final class XCurrency {
    public static XCurrency $instance;

    public static function instance() {
        if ( empty( static::$instance ) ) {
            static::$instance = new self();
        }
        return static::$instance;
    }

    public function boot() {
        $application = App::instance();

        $application->boot( __FILE__, __DIR__ );

        x_currency_singleton( ProVersionUpdateServiceProvider::class )->boot();

        if ( ! $this->is_compatible() ) {
            add_action( 'admin_notices', [$this, 'admin_notice_missing_main_plugin'] );
            return;
        }

        /**
         * Fires once activated plugins have loaded.
         *
         */
        add_action(
            'plugins_loaded', function () use ( $application ): void {

                $stop = apply_filters( 'stop_load_x_currency', false );

                if ( $stop ) {
                    add_filter( 'stop_load_x_currency_pro', '__return_true' );
                    return;
                }

                do_action( 'before_load_x_currency' );

                $application->load();

                do_action( 'after_load_x_currency' );
            }, 11
        );

        add_action( 'plugins_loaded', [ $this, 'stop_load_pro' ], 5 );

        add_action(
            'init', function() : void {
                load_plugin_textdomain( "x-currency", false, __DIR__ . DIRECTORY_SEPARATOR . "languages" );
            } 
        );
    }
    
    public function stop_load_pro() : void {
        add_filter(
            'stop_load_x_currency_pro', function() {

                $plugin_data          = get_plugin_data( ABSPATH . DIRECTORY_SEPARATOR . PLUGINDIR . DIRECTORY_SEPARATOR . 'x-currency-pro/x-currency-pro.php' );
                $required_pro_version = '1.1.0';

                if ( -1 === version_compare( $plugin_data['Version'], $required_pro_version ) ) {
                    add_action( 'admin_notices', [ $this, 'action_admin_notices' ] );
                    return true;
                }
                return false;
            }
        );
    }

    public function action_admin_notices() {
        ?>
        <div class="notice notice-error" style="padding-top: 10px; padding-bottom: 10px;">
            Your current X-Currency-Pro is not compatible with X-Currency Free version. Please update the X-Currency-Pro plugin.
        </div>
        <?php
    }

    public function admin_notice_missing_main_plugin() {
        $btn = [];
        if ( file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
            $btn['label'] = esc_html__( 'Activate WooCommerce', 'x-currency' );
            $btn['url']   = wp_nonce_url( 'plugins.php?action=activate&plugin=woocommerce/woocommerce.php&plugin_status=all&paged=1', 'activate-plugin_woocommerce/woocommerce.php' );
        } else {
            $btn['label'] = esc_html__( 'Install WooCommerce', 'x-currency' );
            $btn['url']   = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=woocommerce' ), 'install-plugin_woocommerce' );
        }
        ?>
        <div class="notice notice-error is-dismissible" style="padding-bottom: 10px;">
            <p><?php esc_html_e( 'X-Currency requires the WooCommerce plugin, which is not currently running.', 'x-currency' )?></p>
            <a href="<?php echo esc_url( $btn['url'] ) ?>" class="button-primary"><?php echo esc_html( $btn['label'] )?></a>
        </div>
        <?php
    }

    public function is_compatible() {
        $plugin_path = trailingslashit( WP_PLUGIN_DIR ) . 'woocommerce/woocommerce.php';
        if ( in_array( $plugin_path, wp_get_active_and_valid_plugins() ) ) {
            return true;
        }
        return false;
    }
}

XCurrency::instance()->boot();