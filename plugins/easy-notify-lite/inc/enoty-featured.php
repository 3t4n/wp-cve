<?php

if ( ! defined( 'ABSPATH' ) ) exit;

function easynotify_featured_init() {
    $easynotify_featured_page = add_submenu_page('edit.php?post_type=easynotify', 'Premium Plugins', __('Premium Plugins', 'easy-notify-lite'), 'edit_posts', 'enoty_featured_plugins', 'easynotify_featured_page');
}
add_action( 'admin_menu', 'easynotify_featured_init' );


function easynotify_featured_page() {
	ob_start(); ?>
	<div class="wrap" id="ghozy-featured">
		<h2>
			<?php _e( 'GhozyLab Premium Plugins', 'easy-notify-lite' ); ?>
		</h2>
		<p><?php _e( 'These plugins available on Lite and Pro version. You can download the trial version <a href="'.admin_url( 'edit.php?post_type=easynotify&page=easynotify_free_plugins' ).'">here</a>', 'easy-notify-lite' ); ?></p>
		<?php echo easynotify_get_feed(); ?>
	</div>
	<?php
	echo ob_get_clean();
}


function easynotify_get_feed() {
	if ( false === ( $cache = get_transient( 'easynotify_featured_feed' ) ) ) {
		$feed = wp_remote_get( 'http://content.ghozylab.com/feed.php?c=featuredplugins', array( 'sslverify' => false ) );
		if ( ! is_wp_error( $feed ) ) {
			if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
				$cache = wp_remote_retrieve_body( $feed );
				set_transient( 'easynotify_featured_feed', $cache, 3600 );
			}
		} else {
			$cache = '<div class="error"><p>' . __( 'There was an error retrieving the list from the server. Please try again later.', 'easy-notify-lite' ) . '</div>';
		}
	}
	return $cache;
}