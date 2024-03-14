<?php
/**
 * Admin page for the plugin
 */

/**
 * Settings Page
 */
function do_etfw_settings_page() {
	add_submenu_page( 'options-general.php', esc_html__( 'Easy Twitter Feed Widget', 'do-etfw' ), esc_html__( 'Easy Twitter Feed Widget', 'do-etfw' ), 'manage_options', 'do-etfw-options', 'do_etfw_settings_page_content' );
}
add_action( 'admin_menu', 'do_etfw_settings_page' );

/**
 * Settings Page Content
 */
function do_etfw_settings_page_content() {
	require DO_ETFW_DIR . 'inc/settings-page.php';
}

/**
 * Enqueue scripts and styles.
 */
function do_etfw_admin_scripts( $hook ) {

	if( 'settings_page_do-etfw-options' === $hook ) {

		/**
		 * Enqueue JS files
		 */

		// Cookie
		wp_enqueue_script( 'do-etfw-cookie', DO_ETFW_URI . 'js/cookie.js', array( 'jquery' ) );

		// Easytabs
		wp_enqueue_script( 'do-etfw-hashchange', DO_ETFW_URI . 'js/hashchange.js', array( 'jquery' ) );
		wp_enqueue_script( 'do-etfw-easytabs', DO_ETFW_URI . 'js/easytabs.js', array( 'jquery', 'do-etfw-hashchange' ) );

		// Admin JS
		wp_enqueue_script( 'do-etfw-admin', DO_ETFW_URI . 'js/admin.js', array( 'jquery' ) );

		/**
		 * Enqueue CSS files
		 */

		// Admin Style
		wp_enqueue_style( 'do-etfw-admin-style', DO_ETFW_URI . 'css/admin.css' );

	}

}
add_action( 'admin_enqueue_scripts', 'do_etfw_admin_scripts' );

/**
 * Contextual Help
 */
function do_etfw_contextual_help() {

	// Plugin Data
	$plugin    = do_etfw_plugin_data();
	$AuthorURI = $plugin['AuthorURI'];
	$PluginURI = $plugin['PluginURI'];
	$Name      = $plugin['Name'];

	// Current Screen
	$screen = get_current_screen();

	// Help Strings
	$content_support = '<p>';
	$content_support .= sprintf( esc_html__( '%1$s is a project of %2$s. You can reach us via contact page.', 'do-etfw' ), $Name, '<a href="https://designorbital.com/">DesignOrbital</a>' );
	$content_support .= '<p>';

	// Plugin reference help screen tab.
	$screen->add_help_tab( array (
			'id'         => 'do-etfw-support',
			'title'      => esc_html__( 'Plugin Support', 'do-etfw' ),
			'content'    => $content_support,
		)
	);

	// Help Sidebar
	$sidebar = '<p><strong>' . esc_html__( 'For more information:', 'do-etfw' ) . '</strong></p>';
	if ( ! empty( $AuthorURI ) ) {
		$sidebar .= '<p><a href="' . esc_url( $AuthorURI ) . '" target="_blank">' . esc_html__( 'Plugin Author', 'do-etfw' ) . '</a></p>';
	}
	if ( ! empty( $PluginURI ) ) {
		$sidebar .= '<p><a href="' . esc_url( $PluginURI ) . '" target="_blank">' . esc_html__( 'Plugin Official Page', 'do-etfw' ) . '</a></p>';
	}
	$screen->set_help_sidebar( $sidebar );

}
add_action( 'load-settings_page_do-etfw-options', 'do_etfw_contextual_help' );

/**
 * Plugin Settings
 */
function do_etfw_settings() {

	// Register plugin settings
	register_setting( 'do_etfw_options_group', 'do_etfw_options', 'do_etfw_options_validate' );

	/** Config Section */
	add_settings_section( 'do_etfw_section_config',     esc_html__( 'Configuration', 'do-etfw' ),       'do_etfw_section_config_cb',       'do_etfw_section_config_page' );
	add_settings_field( 'do_etfw_field_twitter_script', esc_html__( 'Load Twitter Script', 'do-etfw' ), 'do_etfw_field_twitter_script_cb', 'do_etfw_section_config_page', 'do_etfw_section_config' );

}
add_action( 'admin_init', 'do_etfw_settings' );

/**
 * Twitter Script Options
 */
function do_etfw_twitter_script_options() {
	return array (
		true  => esc_html__( 'yes', 'do-etfw' ),
		false => esc_html__( 'no',  'do-etfw' )
	);
}

/**
 * Plugin Settings Validation
 */
function do_etfw_options_validate( $input ) {

	// Enable
	if ( ! array_key_exists( $input['twitter_script'], do_etfw_twitter_script_options() ) ) {
		 $input['twitter_script'] = do_etfw_option_default( 'twitter_script' );
	}

	// return validated array
	return $input;

}

/**
 * Config Section Callback
 */
function do_etfw_section_config_cb() {
	echo '<div class="do-section-desc">
	  <p class="description">'. esc_html__( 'Configure twitter feed widget by using the following settings.', 'do-etfw' ) .'</p>
	</div>';
}

/* Twitter Script Callback */
function  do_etfw_field_twitter_script_cb() {

	$items = do_etfw_twitter_script_options();

	echo '<select id="twitter_script" name="do_etfw_options[twitter_script]">';
	foreach( $items as $key => $val ) {
	?>
    <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, do_etfw_option( 'twitter_script' ) ); ?>><?php echo esc_html( $val ); ?></option>
    <?php
	}
	echo '</select>';
	echo '<div><code>'. esc_html__( 'Select "no" if your theme loads Twitter script.', 'do-etfw' ) .'</code></div>';

}
