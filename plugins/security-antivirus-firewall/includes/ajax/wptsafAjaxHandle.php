<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

class wptsafAjaxHandle{

	public function __construct(){
		add_action('wp_ajax_wptsaf_security', array( $this, 'handle'));
	}

	public function handle(){
		$extension = isset($_REQUEST['extension']) && is_string($_REQUEST['extension'])
			? wptsafSecurity::getInstance()->getExtension($_REQUEST['extension'])
			: null;
		$ajaxHandler = $extension ? $extension->createAjaxHandler() : null;
		$method = isset($_REQUEST['method']) && is_string($_REQUEST['method']) ? $_REQUEST['method'] : '';
		$args = isset($_REQUEST['args']) && is_array($_REQUEST['args']) ? $_REQUEST['args'] : array();
		$error = null;

		if (false === check_ajax_referer(WPTSAF_NONCE, 'nonce', false)) {
			$error = new WP_Error(
				'wptsaf-security-failed-nonce',
				__( 'A nonce security check failed, preventing the request from completing as expected. Please try reloading the page and trying again.', 'wptsaf-security')
			);
		} elseif (!wptsafSecurity::currentUserCanManage()) {
			$error = new WP_Error(
				'wptsaf-security-insufficient-privileges',
				__( 'A permissions security check failed, preventing the request from completing as expected. The currently logged in user does not have sufficient permissions to make this request. Please try reloading the page and trying again.', 'wptsaf-security' )
			);
		} elseif (!$extension || !$ajaxHandler) {
			$error = new WP_Error(
				'wptsaf-security-wrong-ajax-handler',
				__( 'The server did not receive a valid request. The "extension" argument is wrong. Please try again.', 'wptsaf-security')
			);
		} elseif (!$method || !method_exists($ajaxHandler, $method)) {
			$error = new WP_Error(
				'wptsaf-security-wrong-method',
				__( 'The server did not receive a valid request. The "method" argument is wrong. Please try again.', 'wptsaf-security')
			);
		}

		if ($error) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				$extension ? $extension : wptsafSecurity::getInstance(),
				$error->get_error_message()
				. "====dump===\n\n" .
				wptsafExtensionSystemLog::getInstance()->getLog()->formatRow(wptsafEnv::getInstance()->getClientData())
			);

			$response = new wptsafAjaxResponse();
			$response->addError($error);
			$response->sendJson();
			die();
		}

		$response = $this->invokeMethod($ajaxHandler, $method, $args);
		if ($response) {
			$response->sendJson();
		}
		die();
	}

	protected function invokeMethod($ajaxHandler, $method, $args){
		$reflection = new \ReflectionClass($ajaxHandler);
		$method = $reflection->getMethod($method);
		$params = array();

		foreach ($method->getParameters() as $param) {
			/** @var ReflectionParam $param */
			if (isset($args[$param->name])) {
				$params[$param->name] = $args[$param->name];
			} elseif ($param->isDefaultValueAvailable()) {
				$params[$param->name] = $param->getDefaultValue();
			}
		}

		return $method->invokeArgs($ajaxHandler, $params);
	}
}
