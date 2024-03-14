<?php


/*
 * Load Language files for frontend and backend.
 *
 * @since 1.0.0
 */
function chessgame_shizzle_load_lang() {
	load_plugin_textdomain( 'chessgame-shizzle', false, C_SHIZZLE_FOLDER . '/lang' );
}
add_action('plugins_loaded', 'chessgame_shizzle_load_lang');


/*
 * Add Settings link to the main plugin page.
 *
 * @since 1.0.1, active since 1.0.3.
 */
function chessgame_shizzle_links( $links, $file ) {
	if ( $file === plugin_basename( dirname(__FILE__) . '/chessgame-shizzle.php' ) ) {
		$links[] = '<a href="' . admin_url( 'edit.php?post_type=cs_chessgame&page=cs_settings' ) . '">' . esc_html__( 'Settings', 'chessgame-shizzle' ) . '</a>';
	}
	return $links;
}
add_filter( 'plugin_action_links', 'chessgame_shizzle_links', 10, 2 );


/*
 * Check if we need to install or upgrade.
 * Supports MultiSite since 1.0.8.
 *
 * @since 1.0.8
 */
function chessgame_shizzle_init() {

	global $wpdb;

	$current_version = get_option( 'chessgame_shizzle-version', false );

	if ($current_version && version_compare($current_version, C_SHIZZLE_VER, '<')) {
		// Upgrade, if this version differs from what the database says.

		if ( is_multisite() ) {
			$blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				chessgame_shizzle_set_defaults();
				restore_current_blog();
			}
		} else {
			chessgame_shizzle_set_defaults();
		}
	}
}
add_action( 'init', 'chessgame_shizzle_init' );


/*
 * Install new blog on MultiSite.
 * Deprecated action since WP 5.1.0.
 *
 * @since 1.0.8
 */
function chessgame_shizzle_activate_new_site( $blog_id ) {
	switch_to_blog( $blog_id );
	chessgame_shizzle_set_defaults();
	restore_current_blog();
}
add_action( 'wpmu_new_blog', 'chessgame_shizzle_activate_new_site' );


/*
 * Install new blog on MultiSite.
 * Used since WP 5.1.0.
 * Do not use wp_insert_site, since the options table doesn't exist yet...
 *
 * @since 1.1.3
 */
function chessgame_shizzle_wp_initialize_site( $blog ) {
	switch_to_blog( $blog->id );
	chessgame_shizzle_set_defaults();
	restore_current_blog();
}
add_action( 'wp_initialize_site', 'chessgame_shizzle_wp_initialize_site' );
