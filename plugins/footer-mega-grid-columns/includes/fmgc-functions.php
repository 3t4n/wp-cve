<?php
/**
 * Plugin generic functions file
 *
 * @package Footer Mega Grid Columns
 * @since 1.2
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Count number of widgets in a sidebar
 * 
 * @package Footer Mega Grid Columns
 * @since 1.2
 */
function fmgc_count_widgets( $sidebar_id ) {
	global $_wp_sidebars_widgets;

	$sidebars_widgets_count = $_wp_sidebars_widgets;
	if ( isset( $sidebars_widgets_count[ $sidebar_id ] ) ) {
		$widget_count = count( $sidebars_widgets_count[ $sidebar_id ] );
		$widget_classes = 'widget-count-' . count( $sidebars_widgets_count[ $sidebar_id ] );

		if ( $widget_count == 2 ) {
			$widget_classes .= ' fmgc-per-row-6';
		} elseif  ( $widget_count == 3 ) {
			$widget_classes .= ' fmgc-per-row-4';
		} elseif ( $widget_count == 4 ) {
			$widget_classes .= ' fmgc-per-row-3';
		}  elseif ( $widget_count == 5 ) {
			$widget_classes .= ' fmgc-per-row-5c';
		}  elseif ( $widget_count == 6 ) {
			$widget_classes .= ' fmgc-per-row-2';
		} else {
			$widget_classes .= ' fmgc-per-row-12';
		}
		return $widget_classes;
		}
}

/**
 * Display Columns
 * 
 * @package Footer Mega Grid Columns
 * @since 1.2
 */
function slbd_display_widgets(){
	if ( is_active_sidebar( 'fmgc-footer-widget' ) ) : ?>
	<div class="footer-mega-col">
		<div class="footer-mega-col-wrap">
			<?php  dynamic_sidebar( 'fmgc-footer-widget' ); ?> 
		</div>
	</div>
	<?php endif;
}