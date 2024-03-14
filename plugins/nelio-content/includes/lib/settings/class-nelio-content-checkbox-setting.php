<?php
/**
 * This file contains the Checkbox Setting class.
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
 * This class represents a checkbox setting.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Checkbox_Setting extends Nelio_Content_Abstract_Setting {

	/**
	 * Whether this checkbox is checked or not.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    boolean
	 */
	protected $checked;

	/**
	 * Sets whether this checkbox is checked or not.
	 *
	 * @param string $option_name The name of an option to sanitize and save.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_option_name( $option_name ) {
		$this->option_name = $option_name;
	}//end set_option_name()

	/**
	 * Sets whether this checkbox is checked or not.
	 *
	 * @param boolean $value Whether this checkbox is checked or not.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_value( $value ) {

		$this->checked = $value;

	}//end set_value()

	// @Implements
	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable, PHPMD.ShortVariableName ) */
	public function display() { // phpcs:ignore

		// Preparing data for the partial.
		$id      = str_replace( '_', '-', $this->name );
		$name    = $this->option_name . '[' . $this->name . ']';
		$desc    = $this->desc;
		$more    = $this->more;
		$checked = $this->checked;
		include nelio_content()->plugin_path . '/includes/lib/settings/partials/nelio-content-checkbox-setting.php';

	}//end display()

	// @Implements
	public function sanitize( $input ) { // phpcs:ignore

		$value = false;

		if ( isset( $input[ $this->name ] ) ) {

			if ( 'on' === $input[ $this->name ] ) {
				$value = true;
			} elseif ( true === $input[ $this->name ] ) {
				$value = true;
			}//end if
		}//end if

		$input[ $this->name ] = $value;

		return $input;

	}//end sanitize()

	// @Override
	protected function generate_label() { // phpcs:ignore

		return $this->label;

	}//end generate_label()

}//end class
