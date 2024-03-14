<?php
/**
 * Dashboard Main Layout
 *
 * @package ABSP
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
?>
	<div class="absp-settings-panel tools">
		<div class="absp-settings-panel__body">
			<div class="support-wrapper">
				<div class="tools-item">
					<img src="<?php absp_plugin_url('assets/images/tools.png' ) ?>" alt="Image">
					<h3><?php esc_html_e('Regenerate Assets', 'absolute-addons'); ?></h3>
					<p><?php esc_html_e('Absolute Addons style and scripts are save in Uploads folder. This option will clear all those generated files.', 'absolute-addons'); ?></p>
					<div class="tools-method">
						<a href="#"><?php esc_html_e('Regenerate Assets', 'absolute-addons'); ?></a>
					</div>
				</div>
			</div>
			<div class="support-wrapper">
				<div class="tools-item">
					<img src="<?php absp_plugin_url('assets/images/tools-2.png' ) ?>" alt="Image">
					<h3><?php esc_html_e('Asset Embed Method', 'absolute-addons'); ?></h3>
					<p><?php esc_html_e('Configure the Absolute Addons assets embed method. Keep it as default (recommended).', 'absolute-addons'); ?></p>
					<div class="tools-method">
						<h4><?php esc_html_e('CSS Print Method:', 'absolute-addons'); ?></h4>
						<span><?php esc_html_e('CSS Print Method is handled by Elementor Settings itself. Use External CSS files for better performance (recommended).', 'absolute-addons'); ?></span>
						<a href="#"><?php esc_html_e('Configure Settings', 'absolute-addons'); ?></a>
					</div>
					<div class="tools-method">
						<h4><?php esc_html_e('JS Print Method:', 'absolute-addons'); ?></h4>
						<span><?php esc_html_e('CSS Print Method is handled by Elementor Settings itself. Use External CSS files for better performance (recommended).', 'absolute-addons'); ?></span>
						<a href="#"><?php esc_html_e('External File', 'absolute-addons'); ?></a>
					</div>
				</div>
			</div>
		</div>
		<div class="absp-settings-panel__footer"></div>
	</div>
<?php
// End of file dashboard-tab-tools.php.
