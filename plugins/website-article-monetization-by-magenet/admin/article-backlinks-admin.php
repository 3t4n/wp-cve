<?php

defined( 'ABSPATH' ) || die( 'Bye.' );

if ( ! is_admin() ) {
	die( 'Bye.' );
}

register_activation_hook( ABP_PLUGIN_FILE, 'abp_activate' );
register_uninstall_hook( ABP_PLUGIN_FILE, 'abp_uninstall' );
register_deactivation_hook( ABP_PLUGIN_FILE, 'abp_deactivate' );

function abp_activate() {
	global $wpdb;
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$charset_collate = $wpdb->get_charset_collate();

	$sql_create_table = "
                CREATE TABLE IF NOT EXISTS `" . $wpdb->prefix . ABP_TABLE_NAME . "` (
                `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `identifier` INT(11) NOT NULL DEFAULT '0',
                `post_id` INT(11) NOT NULL DEFAULT '0',
                `user_id` INT(11) NOT NULL DEFAULT '0',
                `created_at` INT(11) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`),
                UNIQUE INDEX `adp_post_id` (`post_id`),
                INDEX `adp_identifier` (`identifier`)
                )" . $charset_collate . ";";

	dbDelta( $sql_create_table );

	$abp_auth_key = get_option( 'abp_auth_key', '' );
	if ( ! empty( $abp_auth_key ) && strlen( $abp_auth_key ) === 32 && abp_exist_api() ) {
		Requests::post( ABP_MAGENET_API_URL . '/article/active_plugin', array(), array(
				'auth_key'          => $abp_auth_key,
				'host'              => ABP_HOST_SITE,
				'version'           => ABP_VERSION_PLUGIN,
				'version_php'       => ABP_VERSION_PHP,
				'version_wordpress' => ABP_VERSION_WORDPRESS
			)
		);
	}
}

function abp_uninstall() {
	delete_option( 'abp_author_id' );
	delete_option( 'abp_auth_key' );
	delete_option( 'abp_categories' );
}

function abp_deactivate() {
	$abp_auth_key = get_option( 'abp_auth_key', '' );
	if ( ! empty( $abp_auth_key ) && strlen( $abp_auth_key ) === 32 && abp_exist_api() ) {
		Requests::post( ABP_MAGENET_API_URL . '/article/deactivate_plugin', array(), array(
				'auth_key' => $abp_auth_key,
				'host'     => ABP_HOST_SITE
			)
		);
	}
}

add_action( 'upgrader_process_complete', 'wp_upe_upgrade_completed', 10, 2 );
function wp_upe_upgrade_completed( $upgrader_object, $options ) {
	$our_plugin = plugin_basename( ABP_PLUGIN_FILE );
	if ( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
		foreach ( $options['plugins'] as $plugin ) {
			if ( $plugin == $our_plugin ) {
				$abp_auth_key = get_option( 'abp_auth_key', '' );
				if ( empty( $abp_auth_key ) ) {
					return;
				}
				if ( ! function_exists( 'get_plugin_data' ) ) {
					require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
				}
				$plugin_data    = get_plugin_data( ABP_PLUGIN_FILE, false, false );
				$plugin_version = isset( $plugin_data['Version'] ) ? $plugin_data['Version'] : 1;

				Requests::post( ABP_MAGENET_API_URL . '/article/upgrade_plugin', array(), array(
						'auth_key' => $abp_auth_key,
						'host'     => ABP_HOST_SITE,
						'version'  => $plugin_version,
					)
				);
			}
		}
	}
}

add_action( 'admin_menu', 'abp_Add_My_Admin_Link' );
function abp_Add_My_Admin_Link() {
	add_menu_page(
		'Article Plugin',
		'Article Plugin',
		'manage_options',
		plugin_dir_path( __FILE__ ) . '/abp-page.php',
		'',
		'dashicons-admin-links'
	);
}


add_action( 'plugin_action_links_' . ABP_PLUGIN_FILE_PATH, 'abp_links_action' );
function abp_links_action( $links ) {
	return array_merge( array(
		'<a href="' . admin_url( 'admin.php?page=' . plugin_basename( plugin_dir_path( ABP_PLUGIN_FILE_PATH ) . '/admin/abp-page.php' ) ) . '">' . __( 'Settings', 'textdomain' ) . '</a>'
	), $links );
}

if ( isset( $_POST['action'] ) && $_POST['action'] === 'update_settings' ) {
	update_option( 'abp_author_id', (int) $_POST['abp_author_id'] );
	update_option( 'abp_auth_key', sanitize_text_field( $_POST['abp_auth_key'] ) );
	$post_category = isset( $_POST['post_category'] ) ? (array) $_POST['post_category'] : array();
	$post_category = array_map( 'intval', $post_category );
	update_option( 'abp_categories', $post_category );
	update_option( "abp_cache_time", time() );

	Requests::post( ABP_MAGENET_API_URL . '/article/active_plugin', array(), array(
			'auth_key'          => sanitize_text_field( $_POST['abp_auth_key'] ),
			'host'              => ABP_HOST_SITE,
			'version'           => ABP_VERSION_PLUGIN,
			'version_php'       => ABP_VERSION_PHP,
			'version_wordpress' => ABP_VERSION_WORDPRESS
		)
	);
}

if ( isset( $_POST['action'] ) && $_POST['action'] === 'activate_plugin' ) {
	if ( (int) $abp_author_id > 0 && ! empty( $abp_auth_key ) && strlen( $abp_auth_key ) === 32 ) {
		Requests::post( ABP_MAGENET_API_URL . '/article/active_plugin', array(), array(
				'auth_key'          => $abp_auth_key,
				'host'              => ABP_HOST_SITE,
				'version'           => ABP_VERSION_PLUGIN,
				'version_php'       => ABP_VERSION_PHP,
				'version_wordpress' => ABP_VERSION_WORDPRESS
			)
		);
	}
}

$abp_auth_key_admin = get_option( 'abp_auth_key', '' );
if ( empty( $abp_auth_key_admin ) || (int) get_option( 'abp_author_id', 0 ) == 0 ) {
	add_action( 'admin_notices', 'abp_plugin_notice' );
	function abp_plugin_notice() {
		?>
        <div class="notice notice-info is-dismissible">
            <p style="margin-top: 15px; margin-bottom: 15px;">To place paid articles on your website, go to your <a
                        href="<?php echo admin_url( 'admin.php?page=' . plugin_basename( plugin_dir_path( ABP_PLUGIN_FILE_PATH ) . '/admin/abp-page.php' ) ); ?>">Settings</a>
                and set up the <a
                        href="<?php echo admin_url( 'admin.php?page=' . plugin_basename( plugin_dir_path( ABP_PLUGIN_FILE_PATH ) . '/admin/abp-page.php' ) ); ?>">Article
                    Plugin</a>.</p>
        </div>
		<?php
	}
}
