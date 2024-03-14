<?php
/*
Plugin Name: Checkout Address AutoFill For WooCommerce
Plugin URI: https://zetamatic.com/?utm_src=checkout-address-autofill-for-woocommerce
Description: This plugin allows you to fill the checkout form automatically by using google's address autocomplete API.
Version: 1.1.8
Author: zetamatic
Author URI: https://zetamatic.com/?utm_src=checkout-address-autofill-for-woocommerce
Text Domain: checkout_address_autofill_for_woocommerce
Tested up to: 5.8.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit;
// defining basename
define( 'WCGAAW_BASE', plugin_basename( __FILE__ ) );
define( 'WCGAAW_PLUGIN_PATH', dirname(__FILE__) );
define( 'WCGAAW_PLUGIN_DIR', plugin_dir_url( __DIR__ ) );
define( 'WCGAAW_PLUGIN_VERSION', '1.1.8' );
define('WCGAAW_PLUGIN_URL', plugins_url('', __FILE__));



if ( ! class_exists( 'WC_GAAInstallCheck' ) ) {
	//Restrict installation without woocommerce
	class WC_GAAInstallCheck {
		static function install() {
		/**
		* Check if WooCommerce  are active
		**/
			if ( ! class_exists( 'WooCommerce' ) ) {
				apply_filters( 'active_plugins', get_option( 'active_plugins' ) );
				// Deactivate the plugin
				deactivate_plugins(__FILE__);

				// Throw an error in the wordpress admin console
				$error_message = __( 'This plugin requires <a href="http://wordpress.org/extend/plugins/woocommerce/">WooCommerce</a>  plugins to be active!', 'woocommerce' );
				die($error_message);
			}
			else {
				if(class_exists('WC_CheckoutAddressAutocompletePro')) {
            	require(WCGAAW_PLUGIN_PATH . "/inc/plugin-activation-error.php");
            	exit;
        }
				//updating default options
				$url = plugin_dir_url( __FILE__ ).'/assets/images/location.png';
				update_option( 'wc_af_location_image', $url );
				update_option( 'wc_af_image_height', 50 );
				update_option( 'wc_af_image_width', 50 );
			}
		}
	}
}

register_activation_hook( __FILE__, array( 'WC_GAAInstallCheck', 'install' ) );

$location_picker_url = plugin_dir_url( __FILE__ ).'/assets/images/location-picker.png';

update_option( 'wc_af_location_picker_image_for_testing', $location_picker_url );

if(get_option("wcgaaw_disable_pro_notice") != "YES"){
	add_action( 'admin_notices', 'wcgaaw_download_pro_plugin' );
}
add_action( 'wp_ajax_wcgaaw_hide_pro_notice', 'wcgaaw_hide_pro_notice' );

define( 'WCGAAW_PLUGIN_NAME', 'Checkout Address AutoFill For WooCommerce' );
function wcgaaw_download_pro_plugin() {
	$class = 'notice notice-warning is-dismissible wcgaaw-notice-buy-pro';
	$plugin_url = 'https://zetamatic.com/downloads/checkout-address-autofill-for-woocommerce-pro/';
	$message = 'Glad to know that you are already using our '.WCGAAW_PLUGIN_NAME.'. Do you want to activate the map location picker feature? Then please visit <a href="'.$plugin_url.'?utm_src='.WCGAAW_PLUGIN_NAME.'" target="_blank">here</a> for custom fields.';
	$dont_show = __( "Don't show this message again!", 'checkout_address_autofill_for_woocommerce' );
	printf( '<div class="%1$s"><p>%2$s</p><p><a href="javascript:void(0);" class="wcgaaw-hide-pro-notice">%3$s</a></p></div>
	<script type="text/javascript">
		(function () {
			jQuery(function () {
				jQuery("body").on("click", ".wcgaaw-hide-pro-notice", function () {
					jQuery(".wcgaaw-notice-buy-pro").hide();
					jQuery.ajax({
						"type": "post",
						"dataType": "json",
						"url": ajaxurl,
						"data": {
							"action": "wcgaaw_hide_pro_notice"
						},
						"success": function(response){
						}
					});
				});
			});
		})();
		</script>', esc_attr( $class ), $message, $dont_show );
}

function wcgaaw_hide_pro_notice() {
  update_option("wcgaaw_disable_pro_notice", "YES");
  echo json_encode(["status" => "success"]);
  wp_die();
}

//load textdomain
add_action('plugins_loaded', 'wcgaaw_load_textdomain');

function wcgaaw_load_textdomain() {
	load_plugin_textdomain( 'checkout_address_autofill_for_woocommerce', false, dirname( plugin_basename( __FILE__ ) ). '/languages/' );
}

//Get required files
require_once dirname( __FILE__ ) . '/checkout-address-autofill-template.php';

new WC_CheckoutAddressAutocomplete();

// Google api key setting--------------------------
require_once dirname( __FILE__ ) . '/includes/class-google-api-key-setting.php';

new WCAF_GoogleApiKeySetting();

// Checkout billing field setting-------------------------

require_once dirname( __FILE__ ) . '/includes/class-billing-field-setting.php';

new WCAF_BillingFieldSetting();


// Checkout shipping field setting------------------
require_once dirname( __FILE__ ) . '/includes/class-shipping-field-setting.php';

new WCAF_ShippingFieldSetting();


// Checkout common field setting---------------------------

require_once dirname( __FILE__ ) . '/includes/class-common-field-setting.php';

new WCAF_CommonFieldSetting();


// Woocommerce checkout block--------------------------------------

require_once dirname( __FILE__ ) . '/includes/class-checkout-block-setting.php';

new WCAF_CheckoutBlockSetting();

// save all the default billing checkout field
function wcaf_checkout_fields_priority_free(){
    
	// WooCommerce Checkout Billing Fields
	$wcaf_billing_field_priority = array(
		'0'                 => 'billing_first_name',
		'25'                => 'billing_company',
		'35'                => 'billing_country', 
		'45'                => 'billing_address_1', 
		'55'                => 'billing_address_2', 
		'65'                => 'billing_city', 
		'75'                => 'billing_state', 
		'85'                => 'billing_postcode', 
		'95'                => 'billing_phone', 
		'105'               => 'billing_email', 
	);

	update_option( 'wc_af_checkout_billing_fields_priority', $wcaf_billing_field_priority);

	// WooCommerce Checkout Shipping Fields
	$wcaf_shipping_field_priority = array(
		'5'                => 'shipping_first_name',
		'25'                => 'shipping_company',
		'35'                => 'shipping_country', 
		'45'                => 'shipping_address_1', 
		'55'                => 'shipping_address_2	', 
		'65'                => 'shipping_city', 
		'75'                => 'shipping_state', 
		'85'                => 'shipping_postcode',
	);

	update_option( 'wc_af_checkout_shipping_fields_priority', $wcaf_shipping_field_priority);

	//language list for google autofill
	$laguage_list_for_google_autofill = array(
		''       => 'Select Language', 
		'af'     => 'Afrikaans',
		'sq'     => 'Albanian',
		'am'     => 'Amharic',
		'ar'     => 'Arabic',
		'hy'     => 'Armenian',
		'az'     => 'Azerbaijani',
		'eu'     => 'Basque',
		'be'     => 'Belarusian',
		'bn'     => 'Bengali',
		'bs'     => 'Bosnian',
		'bg'     => 'Bulgarian',
		'my'     => 'Burmese',
		'ca'     => 'Catalan',
		'zh'     => 'Chinese',
		'zh-CN'  => 'Chinese (Simplified)',
		'zh-HK'  => 'Chinese (Hong Kong)',
		'zh-TW'  => 'Chinese (Traditional)',
		'hr'     => 'Croatian',
		'cs'     => 'Czech',
		'da'     => 'Danish',
		'nl'     => 'Dutch',
		'en'     => 'English',
		'en-AU'  => 'English (Australian)',
		'en-GB'  => 'English (Great Britain)',
		'et'     => 'Estonian',
		'fa'     => 'Farsi',
		'fi'     => 'Finnish',
		'fil'    => 'Filipino',
		'fr'     => 'French',
		'fr-CA'  => 'French (Canada)',
		'gl'     => 'Galician',
		'ka'     => 'Georgian',
		'de'     => 'German',
		'el'     => 'Greek',
		'gu'     => 'Gujarati',
		'iw'     => 'Hebrew',
		'hi'     => 'Hindi',
		'hu'     => 'Hungarian',
		'is'     => 'Icelandic',
		'id'     => 'Indonesian',
		'it'     => 'Italian',
		'ja'     => 'Japanese',
		'kn'     => 'Kannada',
		'kk'     => 'Kazakh',
		'km'     => 'Khmer',
		'ko'     => 'Korean',
		'ky'     => 'Kyrgyz',
		'lo'     => 'Lao',
		'lv'     => 'Latvian',
		'lt'     => 'Lithuanian',
		'mk'     => 'Macedonian',
		'ms'     => 'Malay',
		'ml'     => 'Malayalam',
		'mr'     => 'Marathi',
		'mn'     => 'Mongolian',
		'ne'     => 'Nepali',
		'no'     => 'Norwegian',
		'pl'     => 'Polish',
		'pt'     => 'Portuguese',
		'pt-BR'  => 'Portuguese (Brazil)',
		'pt-PT'  => 'Portuguese (Portugal)',
		'pa'     => 'Punjabi',
		'ro'     => 'Romanian',
		'ru'     => 'Russian',
		'sr'     => 'Serbian',
		'si'     => 'Sinhalese',
		'sk'     => 'Slovak',
		'sl'     => 'Slovenian',
		'es'     => 'Spanish',
		'es-419' => 'Spanish (Latin America)',
		'sw'     => 'Swahili',
		'sv'     => 'Swedish',
		'ta'     => 'Tamil',
		'te'     => 'Telugu',
		'th'     => 'Thai',
		'tr'     => 'Turkish',
		'uk'     => 'Ukrainian',
		'ur'     => 'Urdu',
		'uz'     => 'Uzbek',
		'vi'     => 'Vietnamese',
		'zu'     => 'Zulu',

	);

	update_option( 'laguage_list_for_google_autofill', $laguage_list_for_google_autofill);
}
	


add_action('wp_loaded','wcaf_checkout_fields_priority_free');

if(!function_exists('wcaa_review_request_notice')) {
	function wcaa_review_request_notice() {
	  ?>
	  <script type="text/javascript">
		jQuery(function () {
		  jQuery('body').on('click', '.wcaa-review-notice .notice-dismiss', function () {
			jQuery('.wcaa-review-notice .wcaa-review-later').trigger('click');
		  });
		  jQuery('body').on('click', '.wcaa-review-action', function () {
			var $self = jQuery(this);
			var wcaa_action = $self.data('wcaaAction');
			jQuery('.wcaa-review-notice').css('opacity', 0.5);
			jQuery.ajax({
			  url: ajaxurl,
			  type: 'post',
			  data: {
				action: wcaa_action
			  },
			  success: function () {
				jQuery('.wcaa-review-notice').fadeOut();
			  }
			});
		  });
		});
	  </script>
	  <div class="notice notice-success is-dismissible wcaa-review-notice">
		<p><?php _e('We are glad that you are finding <strong>" Checkout Address AutoFill For WooCommerce Pro "</strong> useful - that\'s awesome!'); ?> <br> <?php _e('If you have a moment, please help us spread the word by reviewing the plugin on WordPress.'); ?></p>
		<p><em><?php _e('~ Team ZetaMatic'); ?></em></p>
		<p>
		  <a href="https://wordpress.org/support/plugin/checkout-address-autofill-for-woocommerce/reviews/#new-post" target="_blank"><?php _e('Sure, I\'ll write a review!'); ?></a><span style="color: #DDD;"> | </span>
		  <a href="javascript:void(0);" class="wcaa-review-action wpp-review-done" data-wcaa-action="wcaa_review_done"><?php _e('I\'ve already reviewed this plugin!'); ?></a><span style="color: #DDD;"> | </span>
		  <a href="javascript:void(0);" class="wcaa-review-action wcaa-review-later" data-wcaa-action="wcaa_review_later"><?php _e('Maybe later!'); ?></a>
		</p>
	  </div>
	  <?php
	}
  }
  if(!function_exists('wcaa_review_later')) {
	function wcaa_review_later() {
	  $days_to_remind_after = 7;
	  update_option("wcaa_review_later_time", time() + round($days_to_remind_after * 24 * 3600));
	}
	add_action( 'wp_ajax_wcaa_review_later', 'wcaa_review_later' );
	remove_all_actions('admin_notices');
  }
  if(!function_exists('wcaa_review_done')) {
	function wcaa_review_done() {
	  update_option("wcaa_review_done", 1);
	}
	add_action( 'wp_ajax_wcaa_review_done', 'wcaa_review_done' );
  }
  /* Register script */
  if(!function_exists('wcaa_register_scripts')) {
	function wcaa_register_scripts() {
	  $wcaa_activated_on = get_option('wcaa_activated_on');
	  if(!$wcaa_activated_on) {
		update_option("wcaa_activated_on", time());
	  }
	  $wcaa_edits = get_option("wcaa_edits", 0);
	  $wcaa_review_done = get_option("wcaa_review_done", 0);
	  $wcaa_review_later_time = get_option("wcaa_review_later_time", 0);
	  $days_to_first_review = 7;
	  $wcaa_first_review = $wcaa_activated_on + round($days_to_first_review * 24 * 3600);
     	// 	print_r("for first review-" . date("F d, Y h:i:s A", $wcaa_first_review));echo "<br/>";  
	 
	  if(!$wcaa_review_done && time() > $wcaa_review_later_time  && time() >= $wcaa_first_review) {
		add_action('admin_notices', 'wcaa_review_request_notice');
	  }
	  if(!current_user_can('manage_options'))
		return;
	}
	add_action('admin_init', 'wcaa_register_scripts');
  }
