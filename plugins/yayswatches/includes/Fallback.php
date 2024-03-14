<?php

defined( 'ABSPATH' ) || exit;
add_action(
	'admin_notices',
	function() {
		if ( current_user_can( 'activate_plugins' ) ) {
			?>
				<div class="notice notice-error is-dismissible">
				<p>
					<strong><?php esc_html_e( 'It looks like you have another YaySwatches version installed, please delete it before activating this new version. All of the settings and data are still preserved.', 'yay-swatches' ); ?>
					<a href="https://yaycommerce.com/"><?php esc_html_e( 'Read more details.', 'yay-swatches' ); ?></a>
					</strong>
				</p>
				</div>
			<?php
			if ( isset( $_GET['activate'] ) ) { //phpcs:ignore
				unset( $_GET['activate'] ); //phpcs:ignore
			}
		}
	}
);
