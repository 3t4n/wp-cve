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
			printf( esc_html__( 'CatFolders Document Gallery requires WordPress 5.9.0 to work and does not support your current WordPress version %1$s.', 'catfolders-document-gallery' ), esc_html( $wp_version ) )
			?>
			<a
				href="https://wpmediafolders.com/docs/addons/document-gallery/"><?php esc_html_e( 'Read more details.', 'catfolders-document-gallery' ); ?></a>
		</strong>
	</p>
</div>
			<?php
		}
	}
);
