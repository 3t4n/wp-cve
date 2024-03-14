<?php

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_notices',
	function() {
		if ( current_user_can( 'activate_plugins' ) ) {
			?>
<div class="notice notice-error is-dismissible">
	<p>
		<strong>
			<?php
			printf( esc_html__( 'CatFolders requires PHP 7.2.0 to work and does not support your current PHP version %1$s. Please contact your host and request a PHP upgrade to the latest one.', 'catf' ), esc_html( phpversion() ) )
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
