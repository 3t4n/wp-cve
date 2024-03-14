<?php
/*
Plugin Name: Amelia Shortcode Extended
Plugin URI: https://wpamelia.com/
Description: This is for Amelia extended using shortcode for calendar in the backend to be able to use in the frontend
Version: 1.6
Author: Laurince G. Quijano
Author URI: https://laurincequijano.com/
Text Domain: wpamelia
Domain Path: /languages
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

use AmeliaBooking\Domain\Services\Settings\SettingsService;
use AmeliaBooking\Infrastructure\WP\SettingsService\SettingsStorage;
use AmeliaBooking\Infrastructure\WP\Translations\BackendStrings; 

function amelia_calendar( $atts ){
    if ( ! function_exists( 'is_plugin_active' ) )
       require_once( ABSPATH . '/wp-admin/includes/plugin.php' );

    if ( !is_plugin_active( 'ameliabooking/ameliabooking.php' ) ) {
        return 'Amelia Booking need to be install first';
        exit();
    }

    wp_enqueue_script('amelia_polyfill', 'https://polyfill.io/v2/polyfill.js?features=Intl.~locale.en');

    // Enqueue Scripts
    wp_enqueue_script(
        'amelia_booking_scripts',
        AMELIA_URL . 'public/js/backend/amelia-booking.js',
        [],
        AMELIA_VERSION
    );

    if ($page === 'wpamelia-notifications') {
        wp_enqueue_script('amelia_paddle', AMELIA_URL . 'public/js/paddle/paddle.js');
    }

    // Enqueue Styles
    wp_enqueue_style(
        'amelia_booking_styles',
        AMELIA_URL . 'public/css/backend/amelia-booking.css',
        [],
        AMELIA_VERSION
    );


    wp_localize_script(
        'amelia_booking_scripts',
        'wpAmeliaLabels',
        array_merge(
            BackendStrings::getEntityFormStrings(),
            BackendStrings::getCommonStrings(),
            BackendStrings::getAppointmentStrings(),
            BackendStrings::getUserStrings(),
            BackendStrings::getCustomerStrings(),
            BackendStrings::getCalendarStrings(),
            BackendStrings::getPaymentStrings(),
            BackendStrings::getEventStrings(),
            BackendStrings::getRecurringStrings()
        )
    );
    
    $settingsService = new SettingsService(new SettingsStorage());

    wp_localize_script(
        'amelia_booking_scripts',
        'wpAmeliaSettings',
        $settingsService->getFrontendSettings()
    );

    wp_deregister_script('jquery'); ?>

    <!--suppress JSUnusedLocalSymbols -->
    <script>
        var localeLanguage = "en_US";
    </script>
    <script>
        var wpAmeliaUploadsAmeliaURL = '<?php echo UPLOADS_AMELIA_FILES_URL; ?>'
        var wpAmeliaUseUploadsAmeliaPath = '<?php echo UPLOADS_AMELIA_FILES_PATH_USE; ?>'
        var wpAmeliaPluginURL = '<?php echo AMELIA_URL; ?>'
        var wpAmeliaPluginAjaxURL = '<?php echo AMELIA_ACTION_URL; ?>'
        var wpAmeliaPluginStoreURL = '<?php echo AMELIA_STORE_API_URL; ?>'
        var wpAmeliaSiteURL = '<?php echo AMELIA_SITE_URL; ?>'
        var menuPage = 'wpamelia-calendar'
        var wpAmeliaSMSVendorId = '<?php echo AMELIA_SMS_VENDOR_ID; ?>'
        var wpAmeliaSMSProductId10 = '<?php echo AMELIA_SMS_PRODUCT_ID_10; ?>'
        var wpAmeliaSMSProductId20 = '<?php echo AMELIA_SMS_PRODUCT_ID_20; ?>'
        var wpAmeliaSMSProductId50 = '<?php echo AMELIA_SMS_PRODUCT_ID_50; ?>'
        var wpAmeliaSMSProductId100 = '<?php echo AMELIA_SMS_PRODUCT_ID_100; ?>'
        var wpAmeliaSMSProductId200 = '<?php echo AMELIA_SMS_PRODUCT_ID_200; ?>'
        var wpAmeliaSMSProductId500 = '<?php echo AMELIA_SMS_PRODUCT_ID_500; ?>'
    </script>

    <?php 
        return '<div id="amelia-app-backend" class="amelia-booking">
            <transition name="fade">
                <router-view></router-view>
            </transition>
        </div>';
}

add_shortcode( 'amelia_calendar', 'amelia_calendar' );