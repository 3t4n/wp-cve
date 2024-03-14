<?php
/**
 * Handles Popup Post Setting metabox HTML
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $wpdb;

// Taking some variables
$prefix			= POPUPAOC_META_PREFIX; // Metabox prefix
$enable			= popupaoc_get_option( 'enable' );
$default_meta	= popupaoc_popup_default_meta();
$selected_tab	= get_post_meta( $post->ID, $prefix.'tab', true );
$popup_goal		= get_post_meta( $post->ID, $prefix.'popup_goal', true );
$display_type	= get_post_meta( $post->ID, $prefix.'display_type', true );
$advance		= popupaoc_get_meta( $post->ID, $prefix.'advance' );
$popup_goal		= ! empty( $popup_goal )	? popupaoc_clean( $popup_goal )		: 'announcement';
$display_type	= ! empty( $display_type )	? popupaoc_clean( $display_type )	: 'modal';
$enable_link	= add_query_arg( array('post_type' => POPUPAOC_POST_TYPE, 'page' => 'popupaoc-settings', 'tab' => 'general'), admin_url('edit.php') );

// Add general reminder when module is disabled from setting page
if( ! $enable ) { ?>
	<div class="paoc-error paoc-no-margin paoc-no-radius">
		<i class="dashicons dashicons-warning"></i> 
		<?php esc_html_e('Popup Anything is disabled from plugin setting page.', 'popup-anything-on-click'); ?>
		<?php if ( current_user_can( 'manage_options' ) ) {
			echo sprintf( esc_html__('Kindly %senable%s it from plugin general settings to use it.', 'popup-anything-on-click'), '<a target="_blank" href="'.esc_url( $enable_link ).'">', '</a>' );
		} ?>
	</div>
<?php } ?>

<div class="paoc-vtab-wrap paoc-cnt-wrap paoc-clearfix">
	<ul class="paoc-vtab-nav-wrap">
		<li class="paoc-vtab-nav paoc-active-vtab">
			<a href="#paoc_behaviour_sett"><i class="dashicons dashicons-welcome-view-site" aria-hidden="true"></i> <?php esc_html_e('Behaviour', 'popup-anything-on-click'); ?></a>
		</li>

		<li class="paoc-vtab-nav">
			<a href="#paoc_content_sett"><i class="dashicons dashicons-text-page" aria-hidden="true"></i> <?php esc_html_e('Content', 'popup-anything-on-click'); ?></a>
		</li>

		<li class="paoc-vtab-nav">
			<a href="#paoc_design_sett"><i class="dashicons dashicons-admin-customizer" aria-hidden="true"></i> <?php esc_html_e('Design', 'popup-anything-on-click'); ?></a>
		</li>

		<li class="paoc-vtab-nav">
			<a href="#paoc_advance_sett"><i class="dashicons dashicons-admin-settings" aria-hidden="true"></i> <?php esc_html_e('Advance', 'popup-anything-on-click'); ?></a>
		</li>

		<li class="paoc-vtab-nav">
			<a href="#paoc_css_sett"><i class="dashicons dashicons-editor-code" aria-hidden="true"></i> <?php esc_html_e('Custom CSS', 'popup-anything-on-click'); ?></a>
		</li>

		<li class="paoc-pro-feature paoc-vtab-nav">
			<a href="#paoc_form_fields_sett"><i class="dashicons dashicons-id-alt" aria-hidden="true"></i> <?php esc_html_e('Form Fields', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></a>
		</li>

		<li class="paoc-pro-feature paoc-vtab-nav">
			<a href="#paoc_social_sett"><i class="dashicons dashicons-share" aria-hidden="true"></i> <?php esc_html_e('Social Profile', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></a>
		</li>

		<li class="paoc-pro-feature paoc-vtab-nav">
			<a href="#paoc_notification_sett"><i class="dashicons dashicons-email-alt" aria-hidden="true"></i> <?php esc_html_e('Notification', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></a>
		</li>

		<li class="paoc-pro-feature paoc-vtab-nav">
			<a href="#paoc_integration_sett"><i class="dashicons dashicons-networking" aria-hidden="true"></i> <?php esc_html_e('Integration', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></a>
		</li>

		<li class="paoc-pro-feature paoc-vtab-nav">
			<a href="#paoc_referrer_sett"><i class="dashicons dashicons-megaphone" aria-hidden="true"></i> <?php esc_html_e('Referrer Popup', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></a>
		</li>

		<li class="paoc-pro-feature paoc-vtab-nav">
			<a href="#paoc_utm_sett"><i class="dashicons dashicons-admin-links" aria-hidden="true"></i> <?php esc_html_e('UTM Popup', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></a>
		</li>

		<li class="paoc-pro-feature paoc-vtab-nav">
			<a href="#paoc_cookie_sett"><i class="dashicons dashicons-art" aria-hidden="true"></i> <?php esc_html_e('Cookie Popup', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></a>
		</li>

		<li class="paoc-pro-feature paoc-vtab-nav">
			<a href="#paoc_analytics_sett"><i class="dashicons dashicons-chart-bar" aria-hidden="true"></i> <?php esc_html_e('Google Analytics', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></a>
		</li>

		<li class="paoc-pro-feature paoc-vtab-nav">
			<a href="#paoc_campaign_sett"><i class="dashicons dashicons-randomize" aria-hidden="true"></i> <?php esc_html_e('A/B Testing', 'popup-anything-on-click'); ?> <span class="paoc-pro-tag"><?php esc_html_e('PRO','popup-anything-on-click');?></span></a>
		</li>

		<!-- Pro Feature - Button -->
		<li class="paoc-pro-tab-wrap">
			<a href="<?php echo POPUPAOC_PLUGIN_LINK_UNLOCK; ?>" target="_blank" class="paoc-pro-upgrade-link">
				<i class="dashicons dashicons-money-alt"></i>
				<?php esc_html_e('Upgrade to Premium', 'popup-anything-on-click'); ?>				
			</a>
		</li>
		<li class="paoc-pro-tab-wrap">			
				<em>Use all pro features with <br /><span>Risk-Free 30-day money back guarantee</span>. If you are not happy, we will refund your purchase. <span>No questions asked!</span></em>
		
		</li>
	</ul>

	<div class="paoc-vtab-cnt-wrp">
		<?php
			// Behaviour Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/behaviour-metabox.php' );

			// Content Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/content-metabox.php' );

			// Design Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/design-metabox.php' );

			// Advance Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/advance-metabox.php' );

			// Custom CSS Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/css-metabox.php' );

			// Form Fields Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/form-fields-metabox.php' );

			// Social Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/social-metabox.php' );

			// Notification Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/notification-metabox.php' );

			// Integration Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/integration-metabox.php' );

			// Referer Popup Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/referrer-metabox.php' );

			// UTM Popup Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/utm-metabox.php' );

			// Cookie Popup Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/cookie-metabox.php' );

			// Google Analytic Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/analytics-metabox.php' );

			// A/B Testing Settings
			include_once( POPUPAOC_DIR . '/includes/admin/metabox/campaign-metabox.php' );
		?>
	</div>
	<input type="hidden" value="<?php echo esc_attr( $selected_tab ); ?>" class="paoc-selected-tab" name="<?php echo esc_attr( $prefix ); ?>tab" />
</div>

<div class="paoc-meta-notify paoc-hide"><?php esc_html_e('Changing the Popup Bahaviour or Popup Type will enable some settings in Content and Designs tab.', 'popup-anything-on-click'); ?></div>

<?php popupaoc_preview_popup( array(
	'preview_link'			=> add_query_arg( array( 'paoc_preview_nonce' => wp_create_nonce( 'paoc-popup-preview-nonce' ) ), POPUPAOC_PREVIEW_LINK ),
	'title'					=> esc_html__('Popup Anything On Click - Preview', 'popup-anything-on-click'),
	'info'					=> esc_html__("Some setting options will not work here like 'When Popup Appear?', 'Cookie Expiry Time', 'Advance Settings' and etc for better user experience and preview restriction.", 'popup-anything-on-click')
) );

// Popup Tags File
include( POPUPAOC_DIR . '/includes/admin/popup-tags/tags.php' );