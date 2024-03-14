<?php
/**
 * This file contains the Text Area Setting class.
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
 * This class represents a text area setting.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Text_Area_Setting extends Nelio_Content_Abstract_Setting {

	/**
	 * The concrete value of this field.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $value;

	/**
	 * A placeholder text to be displayed when the field is empty.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    string
	 */
	protected $placeholder;

	/**
	 * Creates a new instance of this class.
	 *
	 * @param string $name        The name that identifies this setting.
	 * @param string $desc        A text that describes this field.
	 * @param string $more        A link pointing to more information about this field.
	 * @param string $placeholder A placeholder text to be displayed when the field is empty.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct( $name, $desc, $more, $placeholder = '' ) {
		parent::__construct( $name, $desc, $more );
		$this->placeholder = $placeholder;
	}//end __construct()

	/**
	 * Sets the value of this field to the given string.
	 *
	 * @param string $value The value of this field.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_value( $value ) {
		$this->value = $value;
	}//end set_value()

	// @Implements
	/** . @SuppressWarnings( PHPMD.UnusedLocalVariable, PHPMD.ShortVariableName ) */
	public function display() { // phpcs:ignore

		// Preparing data for the partial.
		$id          = str_replace( '_', '-', $this->name );
		$name        = $this->option_name . '[' . $this->name . ']';
		$desc        = $this->desc;
		$more        = $this->more;
		$value       = $this->value;
		$placeholder = $this->placeholder;
		include nelio_content()->plugin_path . '/includes/lib/settings/partials/nelio-content-text-area-setting.php';

	}//end display()

	// @Implements
	public function sanitize( $input ) { // phpcs:ignore

		if ( ! isset( $input[ $this->name ] ) ) {
			$input[ $this->name ] = $this->value;
		}//end if

		$value                = $this->sanitize_text( $input[ $this->name ] );
		$input[ $this->name ] = $value;

		return $input;

	}//end sanitize()

	/**
	 * This function sanitizes the input value.
	 *
	 * @param string $value The current value that has to be sanitized.
	 *
	 * @return string The input text properly sanitized.
	 *
	 * @see    sanitize_text_field
	 * @since  1.0.0
	 * @access private
	 */
	private function sanitize_text( $value ) {
		return sanitize_textarea_field( wp_unslash( $value ) );
	}//end sanitize_text()

}//end class
