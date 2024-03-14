<?php

/**
 * The Exclude Pages From Menu Admin defines all functionality for the dashboard
 * of the plugin.
 *
 * This class defines the meta box used to display the post meta data and registers
 * the style sheet responsible for styling the content of the meta box.
 *
 * @package EPFM
 * @since    1.0
 */
class Exclude_Pages_From_Menu_Admin {

	/**
	 * Global plugin option.
	 */
	public $options;

	/**
	 * A reference to the version of the plugin that is passed to this class from the caller.
	 *
	 * @access private
	 * @var    string    $version    The current version of the plugin.
	 */
	private $version;


	/**
	 * are we network activated?
	 */
	private $networkactive;

	/**
	 * Initializes this class and stores the current version of this plugin.
	 *
	 * @param    string    $version    The current version of this plugin.
	 */
	public function __construct( $version ) {
		$this->version = $version;
		$this->options = get_option( 'exclude_pages_from_menu' );
		$this->networkactive = ( is_multisite() && array_key_exists( plugin_basename( __FILE__ ), (array) get_site_option( 'active_sitewide_plugins' ) ) );
	}

	/**
	 * Loads plugin javascript and stylesheet files in the admin area
	 *
	 */
	function exclude_pages_from_menu_load_admin_assets(){

		wp_register_script( 'exclude-pages-from-menu-scripts', plugins_url( '/js/exclude-pages-from-menu-admin.js', __FILE__ ), array( 'jquery' ), '1.0', true  );

		wp_localize_script( 'exclude-pages-from-menu-scripts', 'exclude_pages_from_menu', array(
			'ajax_url' => admin_url( 'admin-ajax.php' )
		) );

		// Enqueued script with localized data.
		wp_enqueue_script( 'exclude-pages-from-menu-scripts' );
	}

	/**
	 * Add a link to the settings page to the plugins list
	 *
	 * @param array  $links array of links for the plugins, adapted when the current plugin is found.
	 * @param string $file  the filename for the current plugin, which the filter loops through.
	 *
	 * @return array $links
	 */
	function exclude_pages_from_menu_settings_link( $links, $file ) {

		if ( false !== strpos( $file, 'exclude-pages-from-menu' ) ) {
			$mylinks = array(
				'<a href="https://wordpress.org/support/plugin/exclude-pages-from-menu/">' . esc_html__( 'Get Support', 'exclude-pages-from-menu' ) . '</a>'
			);
			$links = array_merge( $mylinks, $links );
		}
		return $links;
	}

	/**
	 * Displays plugin configuration notice in admin area
	 *
	 */
	function exclude_pages_from_menu_setup_notice(){

		if ( strpos( get_current_screen()->id, 'settings_page_exclude_pages_from_menu' ) === 0 )
			return;

		$hascaps = $this->networkactive ? is_network_admin() && current_user_can( 'manage_network_plugins' ) : current_user_can( 'manage_options' );

		if ( $hascaps ) {
			$url = is_network_admin() ? network_site_url() : site_url( '/' );
			echo '<div class="notice notice-info is-dismissible exclude-pages-from-menu"><p>' . sprintf( __( 'To use <em>Exclude Pages From Menu plugin</em> please visit page edit screen and to get plugin support contact us on <a href="%1$s" target="_blank">plugin support forum</a>.', 'exclude-pages-from-menu'), 'https://wordpress.org/support/plugin/exclude-pages-from-menu/' ) . '</p></div>';
		}
	}

	/**
	 * Handles plugin notice dismiss functionality using AJAX
	 *
	 */
	function exclude_pages_from_menu_notice_dismiss() {

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$options = $this->options;
			$options['dismiss_admin_notices'] = 1;
			update_option( 'exclude_pages_from_menu', $options );
		}
		die();
	}

	/**
	 * Adds the meta box container.
	 */
	public function exclude_pages_from_menu_add_meta_box( $post_type ) {

		add_meta_box('epfm_meta_box',
			__( 'Exclude pages from menu :', 'exclude-pages-from-menu' ),
			array( $this, 'epfm_render_meta_box_content' ),
			'page',
			'side',
			'low'
		);
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function epfm_save( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['epfm_meta_box_nonce'] ) ) {
			return $post_id;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['epfm_meta_box_nonce'], 'epfm_meta_box' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, it's safe for us to save the data now. */

		// Update the meta field.
		update_post_meta( $post_id, '_epfm_meta_box_field', sanitize_text_field( $_POST['epfm_meta_box_field'] ) );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function epfm_render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'epfm_meta_box', 'epfm_meta_box_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, '_epfm_meta_box_field', true );

		// Display the form, using the current value.
		?>
		<input type="checkbox" id="epfm_meta_box_field" name="epfm_meta_box_field" value="epfm_meta_box_value" <?php checked( $value, 'epfm_meta_box_value' ); ?> />
		<label for="epfm_meta_box_field">
		    <?php esc_html_e( 'Exclude this page from menu.', 'exclude-pages-from-menu' ); ?>
		</label>
		<br /><br />
		<a href="#" id="epfm-help"><?php esc_html_e( 'Help', 'exclude-pages-from-menu' ); ?></a><a href="https://wordpress.org/support/plugin/exclude-pages-from-menu/" class="alignright" target="_blank"><?php esc_html_e( 'Get Support', 'exclude-pages-from-menu' ); ?></a>
		<br /><br />
		<div id="epfm-help-wrapper" style="display: none;">
			<?php esc_html_e( 'Enabling this option will remove the page from navigation menu in the front end of site.', 'exclude-pages-from-menu' ); ?>
			<br /><br />
			<?php esc_html_e( 'If you have any question then feel free to get free support on clicking the above displayed Get Support link.', 'exclude-pages-from-menu' ); ?>
		</div>
		<?php
	}

}