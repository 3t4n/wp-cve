<?php
/**
 * This file contains the Checkbox Set Setting class.
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
 * This class represents a set of checkboxes setting.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/lib/settings
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */
class Nelio_Content_Checkbox_Set_Setting extends Nelio_Content_Abstract_Setting {

	/**
	 * List of checkboxes.
	 *
	 * In this particular case, the instantiated checkboxes are not directly
	 * registered. We register the whole set using this instance.
	 *
	 * @see Nelio_Content_Checkbox_Setting
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    array
	 */
	protected $checkboxes;

	/**
	 * Creates a new instance of this class.
	 *
	 * @param array $options A list with the required information for creating checkboxes.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __construct( $options ) {
		parent::__construct( 'aaa' );
		$this->checkboxes = array();

		foreach ( $options as $option ) {
			if ( isset( $option['more'] ) ) {
				$more = $option['more'];
			} else {
				$more = '';
			}//end if
			$checkbox = new Nelio_Content_Checkbox_Setting(
				$option['name'],
				$option['desc'],
				$more
			);

			$this->checkboxes[ $option['name'] ] = $checkbox;
		}//end foreach

	}//end __construct()

	/**
	 * Sets the value of this setting to the given value.
	 *
	 * @param array $tuple A tuple with the name of the specific checkbox and its concrete value.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function set_value( $tuple ) {

		$name  = $tuple['name'];
		$value = $tuple['value'];

		if ( isset( $this->checkboxes[ $name ] ) ) {
			$checkbox = $this->checkboxes[ $name ];
			$checkbox->set_value( $value );
		}//end if

	}//end set_value()

	// @Implements
	public function display() { // phpcs:ignore

		foreach ( $this->checkboxes as $checkbox ) {
			$checkbox->display();
		}//end foreach

	}//end display()

	// @Implements
	public function sanitize( $input ) { // phpcs:ignore

		foreach ( $this->checkboxes as $checkbox ) {
			$input = $checkbox->sanitize( $input );
		}//end foreach
		return $input;

	}//end sanitize()

	// @Overrides
	public function register( $label, $page, $section, $option_group, $option_name ) { // phpcs:ignore

		parent::register( $label, $page, $section, $option_group, $option_name );
		foreach ( $this->checkboxes as $checkbox ) {
			$checkbox->set_option_name( $option_name );
		}//end foreach

	}//end register()

}//end class

