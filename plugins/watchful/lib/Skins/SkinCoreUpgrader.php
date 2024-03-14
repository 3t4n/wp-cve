<?php
/**
 * Extension of WordPress WP_Upgrader_Skin class to extend the WP upgrader
 * for upgrading the WP core.
 *
 * @version   2016-12-20 11:41 UTC+01
 * @package   Watchful WP Client
 * @author    Watchful
 * @authorUrl https://watchful.net
 * @copyright Copyright (c) 2020 watchful.net
 * @license   GNU/GPL
 */

namespace Watchful\Skins;

/**
 * Core upgrader class.
 */
class SkinCoreUpgrader extends \WP_Upgrader_Skin {
	/**
	 * An array of messages occurred during the action this skin is used for.
	 *
	 * @var array
	 */
	public $feedback = array();

	/**
	 * The last WP_Error object encountered or null if no error was raised.
	 *
	 * @var \WP_Error|null $error
	 */
	public $error = null;

	/**
	 * Sets the WP_Error object.
	 *
	 * @param WP_Error $error The WP_Error object.
	 */
	public function error( $error ) {
		if ( is_string( $error ) ) {
			$this->error = new \WP_Error( 'unknown', $error );
			return;
		}

		$this->error = $error;
	}

	/**
	 * Sets the array of feedback messages.
	 *
     * @param string $string
     * @param mixed  ...$args Optional text replacements.
     *
     */
	public function feedback( $string, ...$args  ) {
        if ( isset( $this->upgrader->strings[ $string ] ) ) {
            $string = $this->upgrader->strings[ $string ];
        }

        if ( strpos( $string, '%' ) !== false ) {
            if ( $args ) {
                $args   = array_map( 'strip_tags', $args );
                $args   = array_map( 'esc_html', $args );
                $string = vsprintf( $string, $args );
            }
        }

        $this->feedback[] = $string;
	}

	/**
	 * Placeholder for the before method.
	 */
	public function before() {}

	/**
	 * Placeholder for the after method.
	 */
	public function after() {}

	/**
	 * Placeholder for the header method.
	 */
	public function header() {}

	/**
	 * Placeholder for the footer method.
	 */
	public function footer() {}

}
