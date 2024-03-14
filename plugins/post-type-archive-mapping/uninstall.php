<?php
/**
 * Uninstall script for Post Type Archive Mapping.
 *
 * @package Post Type Archive Mapping
 */

if ( ! defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
delete_option( 'post-type-archive-mapping' );
