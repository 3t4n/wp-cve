<?php

require_once dirname(__DIR__). '/functions/common.php';


class Notices {
	public static function init() {
		add_action( 'admin_notices', __CLASS__ . '::shipday_api_key_notice' );
        add_action('admin_notices', __CLASS__. '::rest_api_key_notice');
//        add_action('admin_notices', __CLASS__. '::demo_notice');
	}

	public static function shipday_api_key_notice() {
		$api_key         = get_option( 'wc_settings_tab_shipday_api_key' );
		$shipday_tab_url = 'admin.php?page=wc-settings&tab=settings_tab_shipday';
		if ( empty( $api_key ) ) {
			?>
            <div class='notice notice-warning is-dismissible'>
                <p>Your Shipday API Key Field is blank. To set up API Key, <a href="<?php echo $shipday_tab_url; ?>" target="_top">Click
                        Here</a>.</p>
            </div>";
			<?php
		}
	}

	public static function rest_api_key_notice() {
        if ( is_plugin_active( 'dokan-lite/dokan.php' ) ||
            is_plugin_active( 'wc-multivendor-marketplace/wc-multivendor-marketplace.php' )
        ) return;

		$rest_api_section_url = 'admin.php?page=wc-settings&tab=advanced&section=keys&create-key=1';
		$shipday_tab_url = 'admin.php?page=wc-settings&tab=settings_tab_shipday';
		if (!WooCommerce_REST_API::is_consumer_secret_valid(get_option('wc_settings_tab_shipday_rest_api_consumer_secret'))){
            shipday_logger('info', 'Rest api key: consumer secret does not exist during notice');
            delete_option('wc_settings_tab_shipday_registered_uuid');
        }

		if ( empty( get_option( 'wc_settings_tab_shipday_registered_uuid' ) ) ) {
			?>
                <div class='notice notice-warning is-dismissible'>
                    <p>REST API key is essential for order status update in WooCommerce dashboard. To add REST API Key,
                        <a href="<?php echo $rest_api_section_url; ?>" style="color: red">Click here</a> and take note of consumer key and consumer secret.
                        Then enter the keys in shipday settings tab <a href="<?php echo $shipday_tab_url; ?>"> here</a>.</p>
                </div>
			<?php
		}
	}

	public static function demo_notice() {
		$rest_api_section_url = 'admin.php?page=wc-settings&tab=advanced&section=keys';
		$uuid                 = get_option( 'shipday_registered_uuid' );
        $option = get_option('wc_settings_tab_shipday_order_manage');
        $consumer_secret = get_option('wc_settings_tab_shipday_rest_api_consumer_secret');
        $consumer_key = get_option('wc_settings_tab_shipday_rest_api_consumer_key');
        ?>
        <div class='notice notice-warning is-dismissible'>
            <p>You have set <?php echo $consumer_secret.'.'.$consumer_key; ?>.</p>
        </div>
        <?php
	}
}