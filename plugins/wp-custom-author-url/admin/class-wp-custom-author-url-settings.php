<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://chrisjallen.com
 * @since 1.0.0
 *
 * @package    Wp_Custom_Author_Url
 * @subpackage Wp_Custom_Author_Url/admin
 */

/**
 * The admin settings page of the plugin.
 *
 * Defines the options page
 *
 * @package    Wp_Custom_Author_Url
 * @subpackage Wp_Custom_Author_Url/admin
 * @author     Chris Allen <me@chrisjallen.com>
 */
class Wp_Custom_Author_Url_Settings {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * This function introduces the theme options into the 'Appearance' menu and into a top-level
	 * 'WP Dev Flag' menu.
	 */
	public function setup_plugin_settings_menu() {

		// This page will be under "Settings"
			add_options_page(
				'Settings Admin',
				'Custom Author URL',
				'manage_options',
				'wp-custom-author-url-settings',
				array( $this, 'render_settings_page_content' )
			);

	}

	/**
	 * Renders a simple page to display the plugin settings.
	 */
	public function render_settings_page_content() {
		?>
		<!-- Create a header in the default WordPress 'wrap' container -->
		<div class="wrap">

			<h2><?php _e( 'WP Custom Author URL Global Options', 'wp-custom-author-url-plugin' ); ?></h2>

			<form method="post" action="options.php">
		<?php

		settings_fields( 'wp_custom_author_url_global_options' );
		do_settings_sections( 'wp_custom_author_url_global_options' );
		submit_button( 'Update Settings' );

		?>
			</form>

		</div><!-- /.wrap -->
		<?php
	}

	/**
	 * This function provides a simple description for the Global Options page.
	 *
	 * It's called from the 'initialize_global_options' function by being passed as a parameter
	 * in the add_settings_section function.
	 */
	public function global_options_callback() {
		$options = get_option( 'wp_custom_author_url_global_options' );

		$markup = '<p>These settings will affect <em>all</em> author links on your website. Please use with care. For user-specific settins, see the \'Users\' section.</p>';

		echo $markup;

	} // end general_options_callback

	/**
	 * Initializes the theme's display options page by registering the Sections,
	 * Fields, and Settings.
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function initialize_global_options() {

		// First, we add a settings section to contain all the settings fields.
		add_settings_section(
			'global_settings_section',                     // ID used to identify this section and with which to register options
			'', // Title to be displayed on the administration page
			array( $this, 'global_options_callback' ),     // Callback used to render the description of the section
			'wp_custom_author_url_global_options'          // Page on which to add this section of options
		);

		// The checkbox to determine if you want to redirect all authors on the site.
		add_settings_field(
			'redirect_all_authors',                                   // ID used to identify the field throughout the theme
			__( 'Redirect All Author URLs?', 'wp-custom-author-url-plugin' ), // The label to the left of the option interface element
			array( $this, 'redirect_all_authors_callback' ),          // The name of the function responsible for rendering the option interface
			'wp_custom_author_url_global_options',                          // The page on which this option will be displayed
			'global_settings_section',                             // The name of the section to which this field belongs
			array(                                                  // The array of arguments to pass to the callback. In this case, just a description.
				__( 'This will redirect all author names & author pages to the URL specified below.', 'wp-custom-author-url-plugin' ),
			)
		);

		// A text field to enter the redirect URL you want to use.
		add_settings_field(
			'redirect_url',                           // ID used to identify the field throughout the theme
			__( 'Redirect URL', 'wp-custom-author-url-plugin' ), // The label to the left of the option interface element
			array( $this, 'redirect_url_callback' ),  // The name of the function responsible for rendering the option interface
			'wp_custom_author_url_global_options',               // The page on which this option will be displayed
			'global_settings_section',                  // The name of the section to which this field belongs
			array(                                                  // The array of arguments to pass to the callback. In this case, just a description.
				__( 'This URL will be set as the destination for all Author links.', 'wp-custom-author-url-plugin' ),
			)
		);

		// The checkbox to determine if you want to set the current environment as development.
		add_settings_field(
			'override_individual_authors',                                   // ID used to identify the field throughout the theme
			__( 'Override Individual Authors?', 'wp-custom-author-url-plugin' ), // The label to the left of the option interface element
			array( $this, 'override_individual_authors_callback' ),          // The name of the function responsible for rendering the option interface
			'wp_custom_author_url_global_options',                          // The page on which this option will be displayed
			'global_settings_section',                             // The name of the section to which this field belongs
			array(                                                  // The array of arguments to pass to the callback. In this case, just a description.
				__( 'This will redirect <strong><em>all</em></strong> authors, regardless of their individual settings. Use with caution.', 'wp-custom-author-url-plugin' ),
			)
		);

		// Finally, we register the fields with WordPress
		register_setting(
			'wp_custom_author_url_global_options',
			'wp_custom_author_url_global_options',
			array( $this, 'sanitize_global_options' )
		);

	} // end initialize_global_options

	/**
	 * Adds the user fields to their profile page
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function render_user_profile_fields( $user ) {
		?>
		<h3><?php _e( 'Custom Author URL', 'wp-custom-author-url' ); ?></h3>

		<div class="custom-author-url">
			<table class="form-table">
			<tr>
				<th><label for="use_custom_author_url"><?php _e( 'Use a custom author URL?' ); ?></label></th>
				<td>
					<input type="checkbox" name="use_custom_author_url" id="use_custom_author_url" class="regular-text" <?php echo ( esc_attr( get_the_author_meta( 'use_custom_author_url', $user->ID ) ) ) ? 'checked="checked"' : ''; ?>/>
					<span class="description"><?php _e( 'This will replace your author page with the link below.' ); ?></span>
				</td>
			</tr>
			<tr>
				<th><label for="custom_author_url"><?php _e( 'Custom author URL' ); ?></label></th>
				<td>
					<input type="text" name="custom_author_url" id="custom_author_url" placeholder="https://twitter.com/chrisjimallen" value="<?php echo esc_attr( get_the_author_meta( 'custom_author_url', $user->ID ) ); ?>" class="regular-text" />
					<p class="description"><?php _e( 'Please enter your custom author URL.' ); ?></p>
				</td>
			</tr>
			</table>
		</div>
		<?php
	}

	/**
	 * Saves the user profile fields
	 *
	 * This function is registered with the 'admin_init' hook.
	 */
	public function save_user_profile_fields( $user_id ) {

		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}

		update_user_meta( $user_id, 'use_custom_author_url', $_POST['use_custom_author_url'] );
		update_user_meta( $user_id, 'custom_author_url', $_POST['custom_author_url'] );
	}

	/**
	 * This function renders the checkbox field to indicate whether you want to store the environment values with WordPress
	 *
	 */
	public function redirect_all_authors_callback( $args ) {

		// First, we read the options collection
		$options = get_option( 'wp_custom_author_url_global_options' );

		// Generate a checkbox and set its default checked/unchecked state.
		$html = '<input type="checkbox" id="redirect_all_authors" name="wp_custom_author_url_global_options[redirect_all_authors]" value="1" ' . ( ( isset( $options['redirect_all_authors'] ) ) ? 'checked="checked"' : '' ) . ' />';

		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="redirect_all_authors">&nbsp;' . $args[0] . '</label>';

		echo $html;

	} // end redirect_all_authors_callback

	/**
	 * This function renders the text field for storing the redirect URL.
	 *
	 */
	public function redirect_url_callback( $args ) {

		$options = get_option( 'wp_custom_author_url_global_options' );
		// Get the current environment and serialize it to add to the hidden field.
		$html = '<input type="text" id="redirect_url" name="wp_custom_author_url_global_options[redirect_url]" value="' . ( ( isset( $options['redirect_url'] ) ) ? $options['redirect_url'] : '' ) . '"/>';
		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="redirect_url">&nbsp;' . $args[0] . '</label>';

		echo $html;

	} // end redirect_url_callback

	/**
	 * This function renders the radio buttons for the horizontal positioning.
	 */
	public function override_individual_authors_callback( $args ) {

		// First, we read the options collection
		$options = get_option( 'wp_custom_author_url_global_options' );

		// Generate a checkbox and set its default checked/unchecked state.
		$html = '<input type="checkbox" id="override_individual_authors" name="wp_custom_author_url_global_options[override_individual_authors]" value="1" ' . ( ( isset( $options['override_individual_authors'] ) ) ? 'checked="checked"' : '' ) . ' />';

		// Here, we'll take the first argument of the array and add it to a label next to the checkbox
		$html .= '<label for="override_individual_authors">&nbsp;' . $args[0] . '</label>';

		echo $html;

	} // end override_individual_authors_callback

	/**
	 * Sanitization callback for the display options. Since some of the display options are text inputs,
	 * this function loops through the incoming option and strips all tags and slashes from the values
	 * before serializing it.
	 *
	 * @params $input  The unsanitized collection of options.
	 *
	 * @returns The collection of sanitized values.
	 */
	public function sanitize_global_options( $input ) {

		// Define the array for the updated options
		$output = array();

		// Loop through each of the options sanitizing the data
		foreach ( $input as $key => $val ) {

			if ( isset( $input[ $key ] ) ) {
				$output[ $key ] = esc_attr( $input[ $key ] );
			} // end if
		} // end foreach

		// Return the new collection
		return apply_filters( 'sanitize_global_options', $output, $input );

	} // end sanitize_display_options

}