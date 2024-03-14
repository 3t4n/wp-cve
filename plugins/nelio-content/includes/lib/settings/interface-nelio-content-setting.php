<?php
/**
 * An interface that describes a single setting in our plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * The interface for a setting in our plugin.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
interface Nelio_Content_Setting {

	/**
	 * Sets the value of this setting to the given value.
	 *
	 * @param object $value The value of this setting.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_value( $value );

	/**
	 * Adds this setting as a Nelio_Content setting.
	 *
	 * @param string $label        The label of the field.
	 * @param string $page         The menu page on which to display this field.
	 * @param string $section      The section of the settings page in which to show the box .
	 * @param string $option_group A settings group name.
	 * @param string $option_name  The name of an option to sanitize and save.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function register( $label, $page, $section, $option_group, $option_name );

	/**
	 * Displays the setting in the settings screen, under the appropriate section.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function display();

	/**
	 * Sanitizes the setting's input before it's stored in the database.
	 *
	 * @param object $input the input to be sanitized.
	 *
	 * @return object the setting's input properly sanitized.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function sanitize( $input );

}//end interface
