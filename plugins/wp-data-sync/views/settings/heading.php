<?php
/**
 * Heading
 *
 * Admin settings heading.
 *
 * @since   1.3.4
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

printf( '<tr><td colspan="2"><h2>%s</h2></td></tr>', esc_html( $heading ) );
