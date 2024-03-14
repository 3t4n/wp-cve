<?php
/**
 * Plugin Name: Simple Matomo Tracking Code
 * Plugin URI: http://www.rolandbaer.ch/software/wordpress/simple-matomo-tracking-code/
 * Description: This plugin makes it simple to add Matomo Web Analytics code to your WebSite.
 * Version: 1.1.0
 * Author: Roland Bär
 * Author URI: http://www.rolandbaer.ch/
 * Text Domain: simple-matomo-tracking-code
 * License: GPLv3
 * 
 * Based on Jules Stuifbergen's Piwik Analytics plugin
 */

 // If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*
 * Admin User Interface
 */

if ( ! class_exists( 'SMTC_Admin' ) ) {

	class SMTC_Admin {

		static function add_config_page() : void {
			global $wpdb;
			if ( function_exists('add_options_page') ) {
				add_options_page(
					__('Simple Matomo Tracking Code Configuration', 'simple-matomo-tracking-code'),
					__('Simple Matomo Tracking Code', 'simple-matomo-tracking-code'),
					'manage_options',
					basename(__FILE__),
					array('SMTC_Admin','config_page')
				);
			}
		}

		static function restore_defaults() : void {
			$options['siteid'] = 1;
			$options['matomo_host'] = '';
			$options['matomo_baseurl'] = '/matomo/';
			$options['admintracking'] = false;
			$options['dltracking'] = true;
			update_option('MatomoAnalyticsPP',$options);
		}

		function init() : void {
			$options  = get_option('MatomoAnalyticsPP');
			if ( empty($options) ) {
				SMTC_Admin::restore_defaults();
			}
		}

		static function sanitize_siteid( string $value ) : int {
			// remove invalid characters
			$value = preg_replace( '$[^0-9]*$', '', $value );
			return (int) $value;
		}

		static function config_page() : void {
			if ( isset($_GET['reset']) && $_GET['reset'] == "true" ) {
				SMTC_Admin::restore_defaults();
			}

			if ( isset($_POST['submit']) ) {
				if ( ! current_user_can('manage_options') ) die(__('You cannot edit the Simple Matomo Tracking Code options.', 'simple-matomo-tracking-code'));
				check_admin_referer('analyticspp-config');
				$siteid = SMTC_Admin::sanitize_siteid($_POST['siteid']);
				if( $siteid> 0 ) {
					$options['siteid'] = $siteid;
				}

				if ( isset($_POST['matomo_baseurl']) ) {
					$options['matomo_baseurl'] = strtolower(sanitize_text_field($_POST['matomo_baseurl']));
				}

				if ( isset($_POST['matomo_host']) ) {
					$options['matomo_host'] = strtolower(sanitize_text_field($_POST['matomo_host']));
				}

				if ( isset($_POST['dltracking']) ) {
					$options['dltracking'] = true;
				} else {
					$options['dltracking'] = false;
				}

				if ( isset($_POST['admintracking']) ) {
					$options['admintracking'] = true;
				} else {
					$options['admintracking'] = false;
				}

				update_option('MatomoAnalyticsPP', $options);
			}

			$options  = get_option('MatomoAnalyticsPP');
			?>
			<div class="wrap">
				<h2><?php _e('Simple Matomo Tracking Code', 'simple-matomo-tracking-code'); ?></h2>
				<form action="" method="post" id="analytics-conf">
					<?php
					if ( function_exists('wp_nonce_field') )
						wp_nonce_field('analyticspp-config');
					?>

					<p>
						<?php _e("Matomo, formerly known as Piwik, is a downloadable web analytics software platform free of charge under the GPL license.", 'simple-matomo-tracking-code'); ?>
						<br />
						<?php _e('If you don\'t have Matomo installed, you can get it at <a href="https://matomo.org/">matomo.org</a>.', 'simple-matomo-tracking-code'); ?>
					</p>

					<table class="form-table" style="width:100%;">
					<tr>
						<th scope="row" valign="top">
							<label for="siteid"><?php _e('Matomo site ID', 'simple-matomo-tracking-code'); ?></label>
						</th>
						<td>
							<input id="siteid" name="siteid" class="small-text" type="number" size="3" maxlength="4" value="<?php echo $options['siteid']; ?>" /><br/>
							<div id="expl">
								<p>
								<?php _e('In the Matomo interface, when you "Add Website" you are shown a piece of JavaScript that you are told to insert into the page, in that script is a unique string that identifies the website you just defined, that is your site ID (usually "1").', 'simple-matomo-tracking-code'); ?>
								</p>
								<p>
								<?php _e('Once you have entered your site ID in the box above your pages will be trackable by Matomo Web Analytics.', 'simple-matomo-tracking-code'); ?>
								</p>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="dltracking"><?php _e('Track downloads', 'simple-matomo-tracking-code'); ?></label><br/>
							<small><?php _e('(default is YES)', 'simple-matomo-tracking-code'); ?></small>
						</th>
						<td>
							<input type="checkbox" id="dltracking" name="dltracking" <?php if ( $options['dltracking'] ) echo ' checked="unchecked" '; ?>/> 
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="matomo_host"><?php _e('Hostname of the matomo server (optional)', 'simple-matomo-tracking-code'); ?></label>
						</th>
						<td>
							<input id="matomo_host" name="matomo_host" type="text" size="40" maxlength="99" value="<?php echo $options['matomo_host']; ?>" /><br/>
							<div id="expl3">
								<p>
								<?php _e('Example: www.yourdomain.com -- Leave blank (default) if this is the same as your website. Do NOT include the http(s):// bit.', 'simple-matomo-tracking-code'); ?>
								</p>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="matomo_baseurl"><?php _e('Base URL path of matomo installation', 'simple-matomo-tracking-code'); ?></label>
						</th>
						<td>
							<input id="matomo_baseurl" name="matomo_baseurl" type="text" size="40" maxlength="99" value="<?php echo $options['matomo_baseurl']; ?>" /><br/>
							<div id="expl2" style="display:none;">
								<p>
								<?php _e("The URL path to the matomo installation. E.g. /matomo/ or /stats/. Don't forget the trailing slash!", 'simple-matomo-tracking-code'); ?>
								</p>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row" valign="top">
							<label for="admintracking"><?php _e('Track the admin user too', 'simple-matomo-tracking-code'); ?></label><br/>
							<small><?php _e('(default is not to)', 'simple-matomo-tracking-code'); ?></small>
						</th>
						<td>
							<input type="checkbox" id="admintracking" name="admintracking" <?php if ( $options['admintracking'] ) echo ' checked="checked" '; ?>/> 
						</td>
					</tr>
					</table>
					<p style="border:0;" class="submit"><input type="submit" name="submit" value="<?php echo esc_html__('Save settings', 'simple-matomo-tracking-code'); ?>" /></p>
				</form>
				<p>
				<?php
				$matomo_url = SMTC_Admin::build_matomo_url($options);
				printf(
					/* translators: %s: URL of the Matomo installation */
					__('All options set? Then <a href="%s" title="Matomo admin url" target="_blank">check out your stats</a>!', 'simple-matomo-tracking-code'),
					$matomo_url
				);
				?>
			</div>
			<?php
			if ( isset($options['siteid']) ) {
				if ( $options['siteid'] == "" ) {
					add_action('admin_footer', array('SMTC_Admin','warning'));
				} else {
					if ( isset($_POST['submit']) ) {
						add_action('admin_footer', array('SMTC_Admin','success'));
					}
				}
			} else {
				add_action('admin_footer', array('SMTC_Admin','warning'));
			}
		}

		static function success() : void {
			echo "
			<div id='analytics-warning' class='updated'><p><strong>";
			_e('Simple Matomo Tracking Code Configuration successfully updated.', 'simple-matomo-tracking-code');
			echo "</strong></p></div>";
		}

		static function warning() : void {
			echo "
			<div id='analytics-warning' class='notice notice-warning'><p><strong>";
			_e('Matomo Web Analytics is not active.', 'simple-matomo-tracking-code');
			echo "</strong> ";
			_e('You must enter your site ID for it to work.', 'simple-matomo-tracking-code');
			echo "</p></div>";
		}

		static function build_matomo_url(array $options) : string {
			if ( $options['matomo_host'] ) {
				$matomo_url = "//" . $options['matomo_host'];
				$matomo_url = rtrim($matomo_url, '/') . '/';
				$matomo_url = $matomo_url . ltrim($options['matomo_baseurl'], '/');
				$matomo_url = rtrim($matomo_url, '/') . '/';
			} else {
				$matomo_url = $options['matomo_baseurl'];
				$matomo_url = rtrim($matomo_url, '/') . '/';
			}

			return $matomo_url;
		}
	}
}


/**
 * Code that actually inserts stuff into pages.
 */
if ( ! class_exists( 'SMTC_Filter' ) ) {
	class SMTC_Filter {

		/*
		 * Insert the tracking code into the page
		 */
		static function spool_analytics() : void {
			?><!-- Simple Matomo Tracking Code plugin active --><?php

			$script_template = "<!-- Matomo -->
<script type=\"text/javascript\">
  var _paq = window._paq = window._paq || [];
  _paq.push(['trackPageView']);
  {LINK_TRACKING}
  (function() {
    var u=\"{MATOMO_URL}\";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', {IDSITE}]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<!-- End Matomo Code -->";

			$options = get_option('MatomoAnalyticsPP');

			if ( $options["siteid"] != "" && (!current_user_can('edit_users') || $options["admintracking"]) && !is_preview() ) {
				$matomo_url = SMTC_Admin::build_matomo_url($options);

				$link_tracking = "";
				if ( $options["dltracking"] ) {
					$link_tracking = "_paq.push(['enableLinkTracking']);";
				}

				$transitions = array(
					"{MATOMO_URL}" => $matomo_url,
					"{IDSITE}" => $options["siteid"],
					"{LINK_TRACKING}" => $link_tracking);
				echo strtr($script_template, $transitions);
			}
		}
	}
}

// adds the menu item to the admin interface
add_action('admin_menu', array('SMTC_Admin','add_config_page'));

// adds the footer so the javascript is loaded
add_action('wp_footer', array('SMTC_Filter','spool_analytics'));

/**
 * Register the "book" custom post type
 */
function simple_matomo_tracking_code_setup_post_type() : void {
	register_post_type( 'book', ['public' => true ] );
}
add_action( 'init', 'simple_matomo_tracking_code_setup_post_type' );


/**
 * Activate the plugin.
 */
function simple_matomo_tracking_code_activate() : void { 
	$admin = new SMTC_Admin();
	$admin->init();
}

register_activation_hook( __FILE__, 'simple_matomo_tracking_code_activate' );
?>
