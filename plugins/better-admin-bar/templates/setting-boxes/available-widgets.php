<?php
/**
 * Available widget template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="sidebar-heatbox available-widgets-box">
	<h2>
		<?php _e( 'Widgets', 'better-admin-bar' ); ?>
		<span class="heatbox-tooltip">
			<span class="dashicons dashicons-editor-help"></span>
			<span class="tooltip-content"><?php _e( 'Drag & drop widgets over into the Quick Access Panel to make them available.', 'better-admin-bar' ); ?></span>
		</span>
	</h2>

	<?php
	/**
	 * We echo the `ul` opening and closing tag so when there's no `li`,
	 * We can get `ul` without whitespace inside so we can use :empty css selector.
	 */
	echo '<ul id="available-items" class="widget-items available-items">';

	foreach ( $available_widgets as $widget_key ) {
		swift_control_settings_output_widget_item( $widget_key );
	}

	echo '</ul>';
	?>
</div>
