<?php
/**
 * Contains code for the setup wizard class.
 *
 * @package     Boxtal\BoxtalConnectWoocommerce\Init
 */

namespace Boxtal\BoxtalConnectWoocommerce\Init;

use Boxtal\BoxtalConnectWoocommerce\Notice\Notice_Controller;
use Boxtal\BoxtalConnectWoocommerce\Util\Auth_Util;
use Boxtal\BoxtalConnectWoocommerce\Util\Configuration_Util;

/**
 * Setup_Wizard class.
 *
 * Display setup wizard if needed.
 */
class Setup_Wizard {

	/**
	 * Is the plugin being activated
	 *
	 * @var bool
	 */
	private $activation;

	/**
	 * Construct function.
	 *
	 * @param bool $activation : is called on plugin activation.
	 * @void
	 */
	public function __construct( $activation = false ) {
		$this->activation = $activation;
	}

	/**
	 * Run class.
	 *
	 * @void
	 */
	public function run() {
		if ( $this->activation ) {
			if ( ! Auth_Util::is_plugin_paired() && ! Notice_Controller::has_notice( Notice_Controller::$setup_wizard ) ) {
				Notice_Controller::add_notice( Notice_Controller::$setup_wizard );
			}
		} else {
			if ( Auth_Util::is_plugin_paired() ) {
				if ( Notice_Controller::has_notice( Notice_Controller::$setup_wizard ) ) {
					Notice_Controller::remove_notice( Notice_Controller::$setup_wizard );
				}
				if ( Configuration_Util::has_configuration() && Notice_Controller::has_notice( Notice_Controller::$configuration_failure ) ) {
					Notice_Controller::remove_notice( Notice_Controller::$configuration_failure );
				} elseif ( ! Configuration_Util::has_configuration() && ! Notice_Controller::has_notice( Notice_Controller::$configuration_failure ) ) {
					Notice_Controller::add_notice( Notice_Controller::$configuration_failure );
				}
			} elseif ( Notice_Controller::has_notice( Notice_Controller::$configuration_failure ) ) {
				Notice_Controller::remove_notice( Notice_Controller::$configuration_failure );
			}
		}
	}
}
