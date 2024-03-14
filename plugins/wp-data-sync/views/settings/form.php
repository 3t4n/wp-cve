<?php
/**
 * Page
 *
 * Admin settings page.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	return;
} ?>

<script>
	jQuery(document).ready(function($) {
		$( '.wpds-tooltip' ).tooltip({
			show: {
				effect: "slideDown",
				delay: 250
			}
		});
	});
</script>

<div class="wp-data-sync-settings wrap">

	<h1 class="wp-data-sync-admin-h1"><?php esc_html_e( 'WP Data Sync' ); ?></h1>

	<?php do_action( 'wp_data_sync_help_buttons' ); ?>

	<?php view( 'settings/admin-tabs', $args ); ?>

	<form method="post" action="options.php?active_tab=<?php esc_attr_e( $group ); ?>">

		<table class="form-table">

			<tbody>

				<?php settings_fields( $group ); ?>

				<?php do_settings_fields( WPDSYNC_CAP, $group ); ?>

			</tbody>

		</table>

		<?php submit_button(); ?>

	</form>

</div>