<?php

defined( 'ABSPATH' ) || exit;

global $wp_version;

add_action(
	'admin_notices',
	function() {
		if ( current_user_can( 'activate_plugins' ) ) {
			?>
<div class="notice notice-error is-dismissible">
	<p>
		<strong>
			<?php
			printf( esc_html__( 'CatFolders requires WordPress 5.2.0 to work and does not support your current WordPress version %1$s.', 'catf' ), esc_html( $wp_version ) )
			?>
			<a
				href="https://wpmediafolders.com/documentation/#requirements"><?php esc_html_e( 'Read more details.', 'catf' ); ?></a>
		</strong>
	</p>
</div>
			<?php
		}
	}
);
