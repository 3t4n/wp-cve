<?php
/*
Plugin Name: WooCommerce MailChimp NewsLetter Discount
Plugin URI: https://zetamatic.com/downloads/woocommerce-mailchimp-newsletter-discount/?utm_src=woo-mailchimp-newsletter-discount/
Description: This plugin allows your users to get discounts when they subscribe to your mailchimp newsletter
Version: 0.3.5
Author: zetamatic
Author URI: https://zetamatic.com/?utm_src=woo-mailchimp-newsletter-discount/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wc_mailchimp_newsletter_discount
Domain Path: /languages/
Tested up to: 5.8.3
WC tested up to: 6.1.0
*/

define(	'WCMND', __FILE__	);
define( 'WCMND_PATH', plugin_dir_path(__FILE__) );
define( 'WCMND_BASE', plugin_basename(__FILE__) );
define( 'WCMND_OPTIONS_FRAMEWORK_DIRECTORY',  plugins_url( '/inc/', __FILE__ ) );
define( 'WCMND_OPTIONS_FRAMEWORK_PATH',   dirname( __FILE__ ) . '/inc/' );
define( 'WCMND_PLUGIN_NAME',   'WooCommerce MailChimp NewsLetter Discount' );
define( 'WCMND_PLUGIN_VERSION', '0.3.5' );
define("PLUGIN_URL_SHORT" , plugins_url());
define('WCMND_PLUGIN_PATH', dirname(__FILE__));
define('WCMND_PLUGIN_URL', plugins_url('', __FILE__));

if(!function_exists('wcmnd_activate')) {
	function wcmnd_activate() {
		if(function_exists('wcmnd_activate_pro')) {
			require(WCMND_PLUGIN_PATH . "/admin/plugin-activation-error.php");
			exit;
		  }
	  update_option("wcmnd_activated_on", time());
	}
	register_activation_hook( __FILE__, 'wcmnd_activate' );
  }
  
add_action('plugins_loaded', 'wc_mailchimp_newsletter_discounts_text_domain');

function wc_mailchimp_newsletter_discounts_text_domain() {
	load_plugin_textdomain('wc_mailchimp_newsletter_discounts', false, basename( dirname( __FILE__ ) ) . '/lang' );
}

//check for plugin dependency
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

} else {
	$plugin = plugin_basename( __FILE__ );

	function wcmnd_free_check_plugin_depenedency(){
		?>
		<div id="message" class="error">
			<p> <?php echo WCMND_PLUGIN_NAME .__(' requires ','wc_mailchimp_newsletter_discount');?><a href="https://wordpress.org/plugins/woocommerce/" target="_blank"><?php echo __(' WooCommerce ', 'wc_mailchimp_newsletter_discount');?></a><?php echo __('to be activated in order to work. Please install and activate first.', 'wc_mailchimp_newsletter_discount' );?></p>
      	</div>
		<?php
	}
	add_action( 'admin_notices', 'wcmnd_free_check_plugin_depenedency' );

	if( !function_exists('deactivate_plugins') ){
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
	
	deactivate_plugins($plugin);
	return;
}

require_once dirname( __FILE__ ) . '/inc/options-framework.php';

// Mailchimp API Wrapper
if( !class_exists('WCMNDMailChimp') ) {
	require_once dirname( __FILE__ ) . '/inc/api/class-mailchimp.php';
}

require_once dirname( __FILE__ ) . '/inc/wc-mailchimp-newsletter-discount.php';

new WC_MailChimp_Newsletter_Discount();

//review given
  if(!function_exists('wcmnd_review_request_notice')) {
	function wcmnd_review_request_notice() {
	  ?>
	  <script type="text/javascript">
		jQuery(function () {
		  jQuery('body').on('click', '.wcmnd-review-notice .notice-dismiss', function () {
			jQuery('.wcmnd-review-notice .wcmnd-review-later').trigger('click');
		  });
		  jQuery('body').on('click', '.wcmnd-review-action', function () {
			var $self = jQuery(this);
			var wcmnd_action = $self.data('wcmndAction');
			jQuery('.wcmnd-review-notice').css('opacity', 0.5);
			jQuery.ajax({
			  url: ajaxurl,
			  type: 'post',
			  data: {
				action: wcmnd_action
			  },
			  success: function () {
				jQuery('.wcmnd-review-notice').fadeOut();
			  }
			});
		  });
		});
	  </script>
	  <div class="notice notice-success is-dismissible wcmnd-review-notice">
		<p><?php _e('We are glad that you are finding <strong>" WooCommerce MailChimp NewsLetter Discount "</strong> useful - that\'s awesome!'); ?> <br> <?php _e('If you have a moment, please help us spread the word by reviewing the plugin on WordPress.'); ?></p>
		<p><em><?php _e('~ Team ZetaMatic'); ?></em></p>
		<p>
		
		  <a href="https://wordpress.org/support/plugin/woo-mailchimp-newsletter-discount/reviews/#new-post" target="_blank"><?php _e('Sure, I\'ll write a review!'); ?></a><span style="color: #DDD;"> | </span>
		  <a href="javascript:void(0);" class="wcmnd-review-action wpp-review-done" data-wcmnd-action="wcmnd_review_done"><?php _e('I\'ve already reviewed this plugin!'); ?></a><span style="color: #DDD;"> | </span>
		  <a href="javascript:void(0);" class="wcmnd-review-action wcmnd-review-later" data-wcmnd-action="wcmnd_review_later"><?php _e('Maybe later!'); ?></a>
		</p>
	  </div>
	  <?php
	}
  }
  if(!function_exists('wcmnd_review_later')) {
	function wcmnd_review_later() {
	  $days_to_remind_after = 7;
	  update_option("wcmnd_review_later_time", time() + round($days_to_remind_after * 24 * 3600));
	}
	add_action( 'wp_ajax_wcmnd_review_later', 'wcmnd_review_later' );
	remove_all_actions('admin_notices');
  }
  if(!function_exists('wcmnd_review_done')) {
	function wcmnd_review_done() {
	  update_option("wcmnd_review_done", 1);
	}
	add_action( 'wp_ajax_wcmnd_review_done', 'wcmnd_review_done' );
  }
  /* Register script */
  if(!function_exists('wcmnd_register_scripts')) {
	function wcmnd_register_scripts() {
		$wcmnd_activated_on = get_option('wcmnd_activated_on');
		if(!$wcmnd_activated_on) {
			update_option("wcmnd_activated_on", time());
		}

		$wcmnd_edits = get_option("wcmnd_edits", 0);
		$wcmnd_review_done = get_option("wcmnd_review_done", 0);
		$wcmnd_review_later_time = get_option("wcmnd_review_later_time", 0);
		$days_to_first_review = 7;
		$wcmnd_first_review = $wcmnd_activated_on + round($days_to_first_review * 24 * 3600);

		if(!$wcmnd_review_done && time() > $wcmnd_review_later_time  && time() >= $wcmnd_first_review) {
			add_action('admin_notices', 'wcmnd_review_request_notice');
		}

		if(!current_user_can('manage_options'))
			return;
	}
	add_action('admin_init', 'wcmnd_register_scripts');
  }
  