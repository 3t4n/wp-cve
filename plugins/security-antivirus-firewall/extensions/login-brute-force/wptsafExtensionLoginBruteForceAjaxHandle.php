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
 *//*  
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

class wptsafExtensionLoginBruteForceAjaxHandle extends wptsafAbstractExtensionAjaxHandle{

	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->logHeader = array(
			'date_gmt' => __('Date', 'wptsaf_security'),
			'ip' => __('IP address', 'wptsaf_security'),
			'username' => __('Username', 'wptsaf_security'),
			'status' => __('Status', 'wptsaf_security'),
			'description' => __('Description', 'wptsaf_security'),
			'client_data' => __('Details', 'wptsaf_security')
		);

		$this->logRowHeader = array(
			'date_gmt' => __('Date', 'wptsaf_security'),
			'ip' => __('IP address', 'wptsaf_security'),
			'username' => __('Username', 'wptsaf_security'),
			'status' => __('Status', 'wptsaf_security'),
			'description' => __('Description', 'wptsaf_security'),
			'client_data' => __('Details', 'wptsaf_security')
		);
	}

	public function settingsSave(){
		$validator = wptsafValidator::getInstance();
		$settings = $this->extension->getSettings();
		$request = array_intersect_key($_POST, $settings->get());
		$errors = array();

		if ($request) {
			$request['is_enabled'] = isset($request['is_enabled'])
				? ((bool)$request['is_enabled'])
				: false;

			$request['log_rotation'] = is_numeric($request['log_rotation'])
				? intval($request['log_rotation'])
				: strip_tags($request['log_rotation']);
			$errors['log_rotation'] = $validator->validate('log_rotation', $request['log_rotation']);


			$request['count_failed_login_first'] = is_numeric($request['count_failed_login_first'])
				? intval($request['count_failed_login_first'])
				: strip_tags($request['count_failed_login_first']);
			$errors['count_failed_login_first'] = $validator->validate('required', $request['count_failed_login_first']);
			$errors['count_failed_login_first'] || $errors['count_failed_login_first'] = $validator->validate('positive_integer', $request['count_failed_login_first']);

			$request['time_counting_login_first'] = is_numeric($request['time_counting_login_first'])
				? intval($request['time_counting_login_first'])
				: strip_tags($request['time_counting_login_first']);
			$errors['time_counting_login_first'] = $validator->validate('required', $request['time_counting_login_first']);
			$errors['time_counting_login_first'] || $errors['time_counting_login_first'] = $validator->validate('positive_integer', $request['time_counting_login_first']);

			$request['lock_time_first'] = is_numeric($request['lock_time_first'])
				? intval($request['lock_time_first'])
				: strip_tags($request['lock_time_first']);
			$errors['lock_time_first'] = $validator->validate('required', $request['lock_time_first']);
			$errors['lock_time_first'] || $errors['lock_time_first'] = $validator->validate('positive_integer', $request['lock_time_first']);

			$request['is_notify_admin_first'] = isset($request['is_notify_admin_first'])
				? ((bool)$request['is_notify_admin_first'])
				: false;


			$request['count_failed_login_second'] = is_numeric($request['count_failed_login_second'])
				? intval($request['count_failed_login_second'])
				: strip_tags($request['count_failed_login_second']);
			$errors['count_failed_login_second'] = $validator->validate('required', $request['count_failed_login_second']);
			$errors['count_failed_login_second'] || $errors['count_failed_login_second'] = $validator->validate('positive_integer', $request['count_failed_login_second']);

			$request['time_counting_login_second'] = is_numeric($request['time_counting_login_second'])
				? intval($request['time_counting_login_second'])
				: strip_tags($request['time_counting_login_second']);
			$errors['time_counting_login_second'] = $validator->validate('required', $request['time_counting_login_second']);
			$errors['time_counting_login_second'] || $errors['time_counting_login_second'] = $validator->validate('positive_integer', $request['count_failed_login_second']);

			$request['lock_time_second'] = is_numeric($request['lock_time_second'])
				? intval($request['lock_time_second'])
				: strip_tags($request['lock_time_second']);
			$errors['lock_time_second'] = $validator->validate('required', $request['lock_time_second']);
			$errors['lock_time_second'] || $errors['lock_time_second'] = $validator->validate('positive_integer', $request['count_failed_login_second']);

			$request['is_notify_admin_second'] = isset($request['is_notify_admin_second'])
				? ((bool)$request['is_notify_admin_second'])
				: false;


			$errors = array_filter($errors);
			if (empty($errors)) {
				foreach ($request as $field => $value) {
					$settings->set($field, $value);
				}
				$settings->save();
				$request = $settings->get();
			}
		} else {
			$request = $settings->get();
		}

		$view = new wptsafView();
		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/settings.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'errors' => $errors,
				'settings' => $request
			)
		);
		$this->response->setResponse($response);

		if (empty($errors)) {
			$this->response->addMessage(__('Settings are updated', wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS));
			$this->response->addJsCallback(array('wptsafCallback.updateWidget', $this->extension->getName()));
			$this->response->addJsCallback('wptsafCallback.popupHide');
		} else {
			$this->response->addJsCallback('wptsafCallback.popupShowContent');
		}

		return $this->response;
	}
}
