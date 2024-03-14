<?php
/*
Plugin Name: Genesis Design Palette Pro - Freeform Style
Plugin URI: https://genesisdesignpro.com/
Description: Adds a setting space for freeform CSS
Author: Reaktiv Studios
Version: 1.0.8
Requires at least: 4.0
Author URI: https://genesisdesignpro.com
*/

/*
	Copyright 2018 Reaktiv Studios

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License (GPL v2) only.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Define the plugin basename.
if ( ! defined( 'GPCSS_BASE' ) ) {
	define( 'GPCSS_BASE', plugin_basename( __FILE__ ) );
}

// Define my plugin directory.
if ( ! defined( 'GPCSS_DIR' ) ) {
	define( 'GPCSS_DIR', dirname( __FILE__ ) );
}

// Define my plugin version number.
if ( ! defined( 'GPCSS_VER' ) ) {
	define( 'GPCSS_VER', '1.0.7' );
}

/**
 * Load up our class and let's get rolling.
 */
class GP_Pro_Freeform_CSS
{
	/**
	 * Call our hooks
	 */
	public function init() {
		add_action( 'plugins_loaded',                   array( $this, 'textdomain'                  )           );
		add_action( 'admin_enqueue_scripts',            array( $this, 'admin_scripts'               )           );
		add_action( 'admin_notices',                    array( $this, 'gppro_active_check'          ),  10      );
		add_action( 'gppro_before_save',                array( $this, 'save_custom_css'             )           );
		add_action( 'gppro_after_save',                 array( $this, 'remove_custom_css'           )           );
		add_filter( 'gppro_admin_block_add',            array( $this, 'freeform_block'              ),  81      );
		add_filter( 'gppro_sections',                   array( $this, 'freeform_section'            ),  10, 2   );
		add_filter( 'gppro_css_builder',                array( $this, 'freeform_builder'            ),  10, 3   );
	}

	/**
	 * Load textdomain for translations.
	 *
	 * @return void
	 */
	public function textdomain() {
		load_plugin_textdomain( 'gp-pro-freeform-style', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Check for GP Pro being active.
	 *
	 * @return void
	 */
	public function gppro_active_check() {

		// First make sure we have our function.
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		// Get the current screen.
		$screen = get_current_screen();

		// Bail if not on the plugins page.
		if ( empty( $screen ) || ! is_object( $screen ) || 'plugins.php' !== $screen->parent_file ) {
			return;
		}

		// Run the active check.
		$coreactive = class_exists( 'Genesis_Palette_Pro' ) ? Genesis_Palette_Pro::check_active() : false;

		// Active. bail.
		if ( $coreactive ) {
			return;
		}

		// Set my text.
		$text   = esc_html__( 'This plugin requires Genesis Design Palette Pro to function and cannot be activated.', 'gp-pro-freeform-style' );

		// Not active. show message.
		echo wp_kses_post( '<div id="message" class="error fade below-h2"><p><strong>' . $text . '</strong></p></div>' );

		// Hide activation method.
		unset( $_GET['activate'] );

		// Deactivate the plugin.
		deactivate_plugins( plugin_basename( __FILE__ ) );

		// And finish.
		return;
	}

	/**
	 * Call admin CSS and JS files.
	 *
	 * @return void
	 */
	public function admin_scripts() {

		// First make sure we have our function.
		if ( ! function_exists( 'get_current_screen' ) ) {
			return;
		}

		// Get the current screen.
		$screen = get_current_screen();

		// Bail if not on the plugins page.
		if ( empty( $screen ) || ! is_object( $screen ) || 'genesis_page_genesis-palette-pro' !== $screen->base ) {
			return;
		}

		// Load our stuff.
		wp_enqueue_style( 'gppro-freeform', plugins_url( 'lib/css/gppro.freeform.css', __FILE__ ), array(), GPCSS_VER, 'all' );
		wp_enqueue_script( 'textarea-size', plugins_url( 'lib/js/autosize.min.js', __FILE__ ), array( 'jquery' ), '1.18.2', true );
		wp_enqueue_script( 'gppro-freeform', plugins_url( 'lib/js/gppro.freeform.js', __FILE__ ), array( 'jquery' ), GPCSS_VER, true );
	}

	/**
	 * Add a new block to the sidebar.
	 *
	 * @param  array $blocks  Our existing blocks on the sidebar.
	 *
	 * @return array $blocks  Our updated array of blocks on the sidebar.
	 */
	public function freeform_block( $blocks ) {

		// Bail if on multisite and user does not have access.
		if ( is_multisite() && ! current_user_can( 'upload_files' ) ) {
			return $blocks;
		}

		// Create our new block.
		$blocks['freeform-css'] = array(
			'tab'       => __( 'Freeform CSS', 'gp-pro-freeform-style' ),
			'title'     => __( 'Freeform CSS', 'gp-pro-freeform-style' ),
			'intro'     => __( 'Enter any extra or unique CSS in the field below.', 'gp-pro-freeform-style' ),
			'slug'      => 'freeform_css',
		);

		// Return the updated array.
		return $blocks;
	}

	/**
	 * Add our new CSS section to the block we created.
	 *
	 * @param  array  $sections  The individual sections being set up.
	 * @param  string $class     The body class applied.
	 *
	 * @return array  $sections  The new array of individual sections being set up.
	 */
	public function freeform_section( $sections, $class ) {

		// Set up the 4 sections.
		$sections['freeform_css']   = array(
			'freeform-css-global-setup' => array(
				'title'     => __( 'Global CSS', 'gp-pro-freeform-style' ),
				'data'      => array(
					'freeform-css-global'   => array(
						'input'     => 'custom',
						'desc'      => __( 'This CSS will apply site-wide.', 'gp-pro-freeform-style' ),
						'viewport'  => 'global',
						'callback'  => array( $this, 'freeform_css_input' ),
					),
				),
			),
			'freeform-css-desktop-setup'    => array(
				'title'     => __( 'Desktop CSS', 'gp-pro-freeform-style' ),
				'data'      => array(
					'freeform-css-desktop'  => array(
						'input'     => 'custom',
						'desc'      => __( 'This CSS will apply to 1024px and above', 'gp-pro-freeform-style' ),
						'viewport'  => 'desktop',
						'callback'  => array( $this, 'freeform_css_input' ),
					),
				),
			),
			'freeform-css-tablet-setup' => array(
				'title'     => __( 'Tablet CSS', 'gp-pro-freeform-style' ),
				'data'      => array(
					'freeform-css-tablet'   => array(
						'input'     => 'custom',
						'desc'      => __( 'This CSS will apply to 768px and below', 'gp-pro-freeform-style' ),
						'viewport'  => 'tablet',
						'callback'  => array( $this, 'freeform_css_input' ),
					),
				),
			),
			'freeform-css-mobile-setup' => array(
				'title'     => __( 'Mobile CSS', 'gp-pro-freeform-style' ),
				'data'      => array(
					'freeform-css-mobile'   => array(
						'input'     => 'custom',
						'desc'      => __( 'This CSS will apply to 480px and below', 'gp-pro-freeform-style' ),
						'viewport'  => 'mobile',
						'callback'  => array( $this, 'freeform_css_input' ),
					),
				),
			),
		); // End the section.

		// Return the updated array.
		return $sections;
	}

	/**
	 * Create the input fields for the custom CSS entry.
	 *
	 * @param  array $field  The parameters of the defined field.
	 * @param  array $item   The array of data contained.
	 *
	 * @return mixed/HTML $input  The new input field.
	 */
	public static function freeform_css_input( $field, $item ) {

		// Get the standard field info.
		$id     = GP_Pro_Helper::get_field_id( $field );
		$name   = GP_Pro_Helper::get_field_name( $field );

		// Fetch the viewport field.
		$view   = ! empty( $item['viewport'] ) ? $item['viewport'] : 'global';

		// Get our custom data.
		$value  = self::get_custom_css( $view );

		// Start the field.
		$input  = '';

		// Set the field wrappers.
		$input .= '<div class="gppro-input gppro-freeform-input">';
			$input .= '<div class="gppro-input-wrap gppro-freeform-wrap">';

			// Show the description above the field.
			$input .= ! empty( $item['desc'] ) ? '<p class="description">' . esc_html( $item['desc'] ) . '</p>' :'';

			// Load the textarea itself.
			$input .= '<textarea name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" class="widefat code css-entry css-global">' . esc_html( $value ) . '</textarea>';

			// Load the viewport button.
			$input .= '<span data-viewport="' . esc_attr( $view ) . '" class="button button-secondary button-small gppro-button-right gppro-freeform-preview">'. __( 'Preview CSS', 'gp-pro-freeform-style' ).'</span>';

			// Close up the field wrapper.
			$input .= '</div>';
		$input .= '</div>';

		// Send it back.
		return $input;
	}

	/**
	 * Save the custom CSS if it exists.
	 *
	 * @param  array $choices  The data being passed.
	 *
	 * @return void
	 */
	public function save_custom_css( $choices = array() ) {

		// Set an empty array.
		$update = array();

		// Check for global.
		if ( ! empty( $choices['freeform-css-global'] ) ) {
			$update['global']   = wp_kses_post( stripslashes( $choices['freeform-css-global'] ) );
		}

		// Check for desktop.
		if ( ! empty( $choices['freeform-css-desktop'] ) ) {
			$update['desktop']  = wp_kses_post( stripslashes( $choices['freeform-css-desktop'] ) );
		}

		// Check for tablet.
		if ( ! empty( $choices['freeform-css-tablet'] ) ) {
			$update['tablet']   = wp_kses_post( stripslashes( $choices['freeform-css-tablet'] ) );
		}

		// Check for mobile.
		if ( ! empty( $choices['freeform-css-mobile'] ) ) {
			$update['mobile']   = wp_kses_post( stripslashes( $choices['freeform-css-mobile'] ) );
		}

		// Save our custom CSS.
		if ( ! empty( $update ) ) {
			update_option( 'gppro-custom-css', $update );
		} else {
			delete_option( 'gppro-custom-css' );
		}
	}

	/**
	 * Remove the custom CSS values from the global array.
	 *
	 * @param  array $updated The original array of data.
	 *
	 * @return void
	 */
	public function remove_custom_css( $updated = array() ) {

		// Check for global.
		if ( ! empty( $updated['freeform-css-global'] ) ) {
			unset( $updated['freeform-css-global'] );
		}

		// Check for desktop.
		if ( ! empty( $updated['freeform-css-desktop'] ) ) {
			unset( $updated['freeform-css-desktop'] );
		}

		// Check for tablet.
		if ( ! empty( $updated['freeform-css-tablet'] ) ) {
			unset( $updated['freeform-css-tablet'] );
		}

		// Check for mobile.
		if ( ! empty( $updated['freeform-css-mobile'] ) ) {
			unset( $updated['freeform-css-mobile'] );
		}

		// Save our custom CSS.
		if ( ! empty( $updated ) ) {
			update_option( 'gppro-settings', $updated );
		}
	}

	/**
	 * Add freeform CSS data to builder file.
	 *
	 * @param  string $setup  The CSS string.
	 * @param  array  $data   The data saved by a user.
	 * @param  string $class  The body class being applied.
	 *
	 * @return string $setup  The new CSS string.
	 */
	public function freeform_builder( $setup, $data, $class ) {

		// Our global CSS.
		if ( false !== $global = self::get_custom_css( 'global' ) ) {
			$setup .= self::escape_freeform_css( $global ) . "\n\n";
		}

		// Our desktop CSS.
		if ( false !== $desktop = self::get_custom_css( 'desktop' ) ) {
			$setup .= '@media only screen and (min-width: 1024px) {' . "\n";
			$setup .= self::escape_freeform_css( $desktop ) . "\n\n";
			$setup .= '}' . "\n\n";
		}

		// Our tablet CSS.
		if ( false !== $tablet = self::get_custom_css( 'tablet' ) ) {
			$setup .= '@media only screen and (max-width: 768px) {' . "\n";
			$setup .= self::escape_freeform_css( $tablet ) . "\n\n";
			$setup .= '}' . "\n\n";
		}

		// Our mobile CSS.
		if ( false !== $mobile = self::get_custom_css( 'mobile' ) ) {
			$setup .= '@media only screen and (max-width: 480px) {' . "\n";
			$setup .= self::escape_freeform_css( $mobile ) . "\n\n";
			$setup .= '}' . "\n\n";
		}

		// Return the new data to be written.
		return $setup;
	}

	/**
	 * Take the CSS data stored in the settings row and escape it for proper output.
	 *
	 * @param  string $data the sanitized CSS data stored.
	 *
	 * @return string $data the escaped and encoded CSS data to output.
	 */
	public static function escape_freeform_css( $data = '' ) {

		// Convert single quotes to double quotes.
		$data   = str_replace( '\'', '"', $data );

		// Escape it.
		$data   = esc_attr( $data );

		// Now decode it.
		$data   = html_entity_decode( $data );

		// And return it, filtered.
		return apply_filters( 'gppro_freeform_css_escaped', $data );
	}

	/**
	 * Retrieve the saved value if it exists.
	 *
	 * @param  string $viewport  The specific viewport being checked against.
	 *
	 * @return string or false
	 */
	public static function get_custom_css( $viewport = '' ) {

		// First check for custom CSS.
		$custom = get_option( 'gppro-custom-css' );

		// If data for that viewport exists, send it back.
		if ( ! empty( $custom[ $viewport ] ) ) {
			return $custom[ $viewport ];
		}

		// Check our legacy settings and if we have legacy data, return that.
		if ( false !== $legacy = self::get_legacy_css( $viewport ) ) {
			return $legacy;
		}

		// We have nothing, so just return false.
		return false;
	}

	/**
	 * Check for CSS data in global array (old method).
	 *
	 * @param  string $viewport  The specific viewport being checked against.
	 *
	 * @return string or false
	 */
	public static function get_legacy_css( $viewport = '' ) {

		// Set the viewport string to the old version.
		$viewport = 'freeform-css-' . $viewport;

		// Get our global settings.
		$data   = get_option( 'gppro-settings' );

		// If data for that viewport exists, send it back.
		if ( ! empty( $data[ $viewport ] ) ) {
			return $data[ $viewport ];
		}

		// Get our backup settings.
		$backup = get_option( 'gppro-settings-backup' );

		// If data for that viewport exists, send it back.
		if ( ! empty( $backup[ $viewport ] ) ) {
			return $backup[ $viewport ];
		}

		// Return false if we dont have it.
		return false;
	}
	// End my class.
}

// Instantiate our class.
$GP_Pro_Freeform_CSS = new GP_Pro_Freeform_CSS();
$GP_Pro_Freeform_CSS->init();
