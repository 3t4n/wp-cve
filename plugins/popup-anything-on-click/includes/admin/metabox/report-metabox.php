<?php
/**
 * Popup Report Metabox. Popup Click, Impression and Report Link
 * 
 * @package Popup Anything on Click
 * @since 2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$display_rule_link = add_query_arg( array( 'post_type' => POPUPAOC_POST_TYPE, 'page' => 'popupaoc-settings', 'tab' => 'display_rule'), admin_url('edit.php') );
?>

<div class="paoc-popup-report-sett paoc-cnt-wrap">
	<div class="paoc-preview-btn-wrp">
		<button type="button" class="button button-large button-primary paoc-btn paoc-btn-large paoc-popup-preview-btn paoc-show-popup-modal" data-popup-id="<?php echo esc_attr( $post->ID ); ?>" data-preview="1"><?php esc_html_e('Preview Popup', 'popup-anything-on-click'); ?></button>
		<br/><br/>
		<a class="button button-large button-primary paoc-btn paoc-btn-large" href="<?php echo esc_url( $display_rule_link ); ?>" target="_blank"><?php esc_html_e('Display Rule', 'popup-anything-on-click'); ?></a>
	</div>

	<div class="paoc-clearfix paoc-pro-feature paoc-center">
		<div class="paoc-stats-box-wrap">
			<div class="paoc-stats-box-title"><strong><?php esc_html_e('Impressions', 'popup-anything-on-click'); ?></strong></div>
			<div class="paoc-stats-box">
				<div class="paoc-report-title"><?php esc_html_e('Normal', 'popup-anything-on-click'); ?></div>
				<span class="paoc-report-no">0</span>
			</div>
			<div class="paoc-stats-box">
				<div class="paoc-report-title"><?php esc_html_e('Inline', 'popup-anything-on-click'); ?></div>
				<span class="paoc-report-no">0</span>
			</div>
		</div>

		<div class="paoc-stats-box-wrap">
			<div class="paoc-stats-box"><strong><?php esc_html_e('Clicks', 'popup-anything-on-click'); ?></strong></div>
			<div class="paoc-stats-box">
				<div class="paoc-report-title"><?php esc_html_e('Normal', 'popup-anything-on-click'); ?></div>
				<span class="paoc-report-no">0</span>
			</div>
			<div class="paoc-stats-box">
				<div class="paoc-report-title"><?php esc_html_e('Inline', 'popup-anything-on-click'); ?></div>
				<span class="paoc-report-no">0</span>
			</div>
		</div>
	</div>

	<?php if ( current_user_can( 'manage_options' ) ) { ?>
	<p class="paoc-popup-report-link paoc-pro-feature paoc-center">
		<a href="#" target="_blank" class="paoc-disabled"><?php esc_html_e('View Report', 'popup-anything-on-click'); ?></a> | 
		<a href="#" target="_blank" class="paoc-disabled"><?php esc_html_e('View Entries', 'popup-anything-on-click'); ?></a>
	</p>
	<hr/>

	<div class="paoc-pro-feature paoc-flush-report-wrp">
		<button type="button" class="button button-secondary paoc-disabled"><?php esc_html_e('Flush Stats', 'popup-anything-on-click'); ?></button>
		<span class="spinner paoc-spinner"></span>
		<hr/>
		<span class="description"><?php esc_html_e('Note : Flush Stats button will only flush the `Impressions` and `Clicks` for this post. The popup report will not be affected by this.', 'popup-anything-on-click'); ?></span>
	</div>
	<br/>
	<!-- Pro Notice -->
	<div class="paoc-pro-notice">
		<i class="dashicons dashicons-money-alt"></i>
		<?php echo sprintf( __( 'Utilize these <a href="%s" target="_blank">Premium Features with Risk-Free 30 days money back guarantee</a> to get best of this plugin with Annual or Lifetime bundle deal.', 'popup-anything-on-click'), POPUPAOC_PLUGIN_LINK_UNLOCK); ?>		
	</div>
	<?php } ?>
</div>