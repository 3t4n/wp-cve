<?php
function is_reserved_login_file( $filename ) {
	return in_array( $filename,
			array( 'index.php', 'wp-activate.php', 'wp-app.php', 'wp-atom.php', 'wp-blog-header.php',
					'wp-comments-post.php', 'wp-commentsrss2.php', 'wp-config.php', 'wp-config-sample.php', 'wp-cron.php',
					'wp-feed.php', 'wp-links-opml.php', 'wp-load.php', 'wp-login.php', 'wp-mail.php',
					'wp-pass.php', 'wp-rdf.php', 'wp-register.php', 'wp-rss.php', 'wp-rss2.php',
					'wp-settings.php', 'wp-signup.php', 'wp-trackback.php', 'xmlrpc.php' ) );
}
function login_file_path( $page ) {
	if ( strpos( $page, '/' ) !== false ) {
		$root_path = $_SERVER['DOCUMENT_ROOT'];
		if ( empty( $root_path ) ) {
			list( $scheme, $content_uri ) = explode( "://".$_SERVER["SERVER_NAME"], get_option( 'siteurl' ) );
			$root_path = preg_replace( '/'.str_replace( array( '-', '.', '/' ), array( '\\-', '\\.', '[\\/\\\\]' ), $content_uri ).'/u', '', untrailingslashit( ABSPATH ) );
		}
		$path = $root_path.'/'.ltrim( $page , '/' );
	} else
		$path = ABSPATH.$page;
	return $path;
}
function exists_delete( $page ) {
	if ( isset( $page ) && !empty( $page ) && !is_reserved_login_file( $page ) && @file_exists( login_file_path( $page ) ) )
		@unlink( login_file_path( $page ) );
}
function get_sites( $ignore_ids = null ) {
	if ( is_multisite() ) {
		global $wpdb;
		$query = "SELECT * FROM $wpdb->blogs WHERE 1=1";
		if ( !empty( $ignore_ids ) )
			$query .= " AND blog_id NOT IN (".implode( ',', (array)$ignore_ids ).")";
		return $wpdb->get_results( $query );
	} else
		return array();
}

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

define( 'LOGIN_REBUILDER_PROPERTIES', 'login-rebuilder' );
$properties = get_site_option( LOGIN_REBUILDER_PROPERTIES, array( 'deleted'=>true ) );
exists_delete( $properties['page'] );
exists_delete( $properties['page_subscriber'] );
if ( !isset( $properties['deleted'] ) )
	delete_site_option( LOGIN_REBUILDER_PROPERTIES );
if ( is_multisite() ) {
	$sites = get_sites( get_current_blog_id() );
	if ( is_array( $sites ) ) 	foreach ( $sites as $site ) {
		switch_to_blog( $site->blog_id );
		$properties = get_option( LOGIN_REBUILDER_PROPERTIES, '' );
		exists_delete( $properties['page'] );
		exists_delete( $properties['page_subscriber'] );
		delete_option( LOGIN_REBUILDER_PROPERTIES );
		restore_current_blog();
	}
}
?>