<?php
/**
 * ThirdPartyIntegrationInterface.
 *
 * @link       https://etracker.com
 * @since      2.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Frontend\Hooks\ThirdParty;

/**
 * ThirdPartyIntegrationInterface for integrations with other plugin filters and hooks.
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
interface ThirdPartyIntegrationInterface {
	/**
	 * Returns an array of filters that this 3rdParty integration wants to listen to.
	 *
	 * The array key is the filter name. The value can be:
	 *
	 *  * An array with the component and method name
	 *  * An array with the component, method name, priority and number of accepted arguments
	 *
	 * For instance:
	 *
	 *  * array('hook_name' => array( 'component => 'class or object', 'callback' => 'method to be called' ))
	 *  * array('hook_name' => array(array( 'component => 'class1', 'callback' => 'method1' )), array( 'component => 'object2', 'callback' => 'method2', 'priority' => 10, 'accepted_args' => 1 )))
	 *
	 * @return array
	 */
	public static function get_subscribed_filters();
}
