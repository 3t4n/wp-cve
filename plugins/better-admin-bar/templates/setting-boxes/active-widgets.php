<?php
/**
 * Active widget template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="heatbox active-widgets-box">
	<h2>
		<?php _e( 'Quick Access Panel', 'better-admin-bar' ); ?>
		<span class="heatbox-tooltip has-image">
			<span class="dashicons dashicons-editor-help"></span>
			<span class="tooltip-content">
				<img src="<?php echo esc_url( SWIFT_CONTROL_PLUGIN_URL ); ?>/assets/images/quick-access-panel.gif">
				<span class="text">
					<?php _e( 'The Quick Access Panel is a better way to navigate WordPress. Quickly access all key areas of your website from a beautiful & convenient navigation panel.', 'better-admin-bar' ); ?>
				</span>
			</span>
		</span>
		<span class="saved-status">
			<?php _e( 'Updated', 'better-admin-bar' ); ?> 🚀
		</span>
	</h2>

	<ul id="active-items" class="widget-items active-items">
		<?php
		foreach ( $active_widgets as $widget_key ) {
			swift_control_settings_output_widget_item( $widget_key );
		}
		?>
	</ul>
</div>
