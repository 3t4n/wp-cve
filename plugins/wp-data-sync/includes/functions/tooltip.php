<?php
/**
 * Tooltip
 *
 * Display a tooltip.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function toottip( $args ) {

	if ( ! empty( $args['info'] ) ) {
		printf( '<span class="wpds-tooltip" title="%s">%s</span>', esc_html( $args['info'] ), esc_html( '&#9432;' ) );
	}

}