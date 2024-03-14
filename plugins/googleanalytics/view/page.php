<?php
/**
 * Page view.
 *
 * @package GoogleAnalytics
 */

$optimize_code = get_option( 'googleanalytics_optimize_code' );
$universal          = get_option( 'googleanalytics_enable_universal_analytics', true );
$anonymization = get_option( 'googleanalytics_ip_anonymization', true );
$debug_mode    = get_option( 'googleanalytics_enable_debug_mode', 'off' );
$gdpr_config   = get_option( 'googleanalytics_gdpr_config' );
$sharethis_property = get_option( 'googleanalytics_sharethis_terms' );
$plugin_dir    = plugin_dir_path( __FILE__ );
$plugin_uri    = trailingslashit( get_home_url() ) . 'wp-content/plugins/googleanalytics/';
$has_code = filter_input(INPUT_GET, 'code');
$has_code = isset($has_code) ? $has_code : false;
$is_ua              = filter_input( INPUT_GET, 'ua' );
$is_ua              = true === isset( $is_ua ) ? 't' === $is_ua : false;
$has_property = get_option('googleanalytics-ga4-property');
$has_property = isset($has_property) ? $has_property : false;
$ga4_optimize = get_option('googleanalytics-ga4-optimize');
$ga4_optimize = isset($ga4_optimize) ? $ga4_optimize : false;
$ga4_exclude_roles = get_option('googleanalytics-ga4-exclude-roles');
$ga4_exclude_roles = isset($ga4_exclude_roles) ? $ga4_exclude_roles : false;
$ga4_demo = get_option('googleanalytics-ga4-demo');
$ga4_demo           = true === isset( $ga4_demo ) ? $ga4_demo : false;
$ga4_ip = get_option('googleanalytics-ga4-ip-anon');
$ga4_ip = isset($ga4_ip) ? $ga4_ip : false;
$ga4_gdpr = get_option('googleanalytics-ga4-gdpr');
$ga4_gdpr = isset($ga4_gdpr) ? $ga4_gdpr : false;
$ga_nonce = wp_create_nonce('ga4-setup');
$setup_done = false !== $has_property &&
			  (
				  false !== $ga4_gdpr ||
				  false !== $ga4_demo ||
				  false !== $ga4_exclude_roles ||
				  false !== $ga4_optimize ||
				  false !== $ga4_ip
			  );
?>
<?php echo wp_kses_post( $data['debug_modal'] ); ?>
	<div class="wrap ga-wrap do-flex">
		<div class="ga4-settings-wrap setting-tab-content st-notice-there engage
		<?php echo true === $setup_done ? ' normal-settings' : '';
		echo false !== $has_code && false === $is_ua ? ' engage' : '';
		?>">
			<?php include 'ga-ga4-settings.php'; ?>
		</div>
		<?php
		// If GDPR isn't enabled show ad otherwise show demo ad.
		if ( true === empty( $gdpr_config ) ) {
			include $plugin_dir . 'templates/sidebar/gdpr-ad.php';
		} else {
			// If Demo is not enabled show ad.
			if ( true === empty( get_option( 'googleanalytics_demographic' ) ) ) {
				include $plugin_dir . 'templates/sidebar/demo-ad.php';
			}
		}
		?>
		<p class="ga-love-text"><?php esc_html_e( 'Love this plugin?' ); ?> <a
					href="https://wordpress.org/support/plugin/googleanalytics/reviews/#new-post"><?php esc_html_e( ' Please help spread the word by leaving a 5-star review!' ); ?> </a>
		</p>
	</div>
	<script type="text/javascript">
		const GA_DISABLE_FEATURE_URL = '<?php echo esc_url( Ga_Helper::create_url( admin_url( Ga_Helper::GA_SETTINGS_PAGE_URL ), array( Ga_Controller_Core::ACTION_PARAM_NAME => 'ga_action_disable_all_features' ) ) ); ?>';
		const GA_ENABLE_FEATURE_URL = '<?php echo esc_url( Ga_Helper::create_url( admin_url( Ga_Helper::GA_SETTINGS_PAGE_URL ), array( Ga_Controller_Core::ACTION_PARAM_NAME => 'ga_action_enable_all_features' ) ) ); ?>';
		jQuery( document ).ready( function() {
			ga_switcher.init( '<?php echo esc_js( $data[ Ga_Admin::GA_DISABLE_ALL_FEATURES ] ); ?>' );
		} );
	</script>
<?php
require 'templates/demo-popup.php';
