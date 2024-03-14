<?php
/**
 * Gets option from database and return it, or default if the option could not be found.
 *
 * @link       http://linkpizza.com
 * @since      1.0.0
 *
 * @package    linkPizza-manager
 * @subpackage linkPizza-manager/includes
 */
class linkPizza_manager_Option {

	/**
	 * Get an option
	 *
	 * Looks to see if the specified setting exists, returns default if not.
	 *
	 * @since   1.0.0
	 * @param string         $key Name of the option.
	 * @param string|boolean $default The default value of the option, false if not used.
	 * @return mixed $value  The value from the database or default if the key does not exist.
	 */
	public static function get_option( $key, $default = false ) {

		if ( empty( $key ) ) {
			return $default;
		}

		$plugin_options = get_option( 'linkPizza_Manager_settings', array() );

		$value = isset( $plugin_options[ $key ] ) ? $plugin_options[ $key ] : $default;

		return $value;
	}
}
