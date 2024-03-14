<?php
/**
 * Help Buttons
 *
 * Display help buttons on settings page
 *
 * @since   1.0.2
 *
 * @package WP_Data_Sync
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
} ?>

<p class="wpds-help-buttons">

	<a href="https://wpdatasync.com/products/?affid=admin" class="button-primary" target="_blank">
		<?php _e( 'Products', 'wp-data-sync' ); ?>
	</a>

	<a href="https://wpdatasync.com/support/?affid=admin" class="button-primary" target="_blank">
		<?php _e( 'Support', 'wp-data-sync' ); ?>
	</a>

	<a href="https://wpdatasync.com/documentation/getting-started/?affid=admin" class="button-primary" target="_blank">
		<?php _e( 'Documentation', 'wp-data-sync' ); ?>
	</a>

</p>
