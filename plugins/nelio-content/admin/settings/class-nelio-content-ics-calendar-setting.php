<?php
/**
 * This file contains the setting for exporting post data to other calendar
 * tools using an iCal feed.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class represents the setting for exporting post data to other calendar
 * tools using an iCal feed.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin/settings
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.4.2
 */
class Nelio_Content_ICS_Calendar_Setting extends Nelio_Content_Abstract_Setting {

	protected $value;

	public function __construct() {
		parent::__construct( 'use_ics_subscription' );
	}//end __construct()

	public function set_value( $value ) {
		$this->value = $value;
	}//end set_value()

	// @Implements
	// phpcs:ignore
	public function display() {

		$id   = str_replace( '_', '-', $this->name );
		$name = $this->option_name . '[' . $this->name . ']';
		$desc = _x( 'Export your calendar posts to Google Calendar or any other calendar tool.', 'command', 'nelio-content' );

		printf(
			'<p><input type="checkbox" id="%s" name="%s" %s  /> %s</p>',
			esc_attr( $id ),
			esc_attr( $name ),
			checked( $this->value, true, false ),
			esc_html( $desc )
		);

	}//end display()

	// @Implements
	// phpcs:ignore
	public function sanitize( $input ) {

		$checked = false;

		if ( isset( $input[ $this->name ] ) ) {

			if ( 'on' === $input[ $this->name ] ) {
				$checked = true;
			} elseif ( true === $input[ $this->name ] ) {
				$checked = true;
			}//end if
		}//end if

		// Manage key option when needed.
		$ics_secret_key = get_option( 'nc_ics_key', false );
		if ( $checked ) {

			if ( ! $ics_secret_key ) {
				update_option( 'nc_ics_key', wp_generate_password() );
			}//end if
		} else {
			delete_option( 'nc_ics_key' );
		}//end if

		$input[ $this->name ] = $checked;

		return $input;

	}//end sanitize()

}//end class
