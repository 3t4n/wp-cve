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

class wptsafExtensionReportAjaxHandle extends wptsafAbstractExtensionAjaxHandle{

	public function settings(){
		$view = new wptsafView();
		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/settings.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'frequencyRunning' => $this->extension->getSettings()->getFrequencyRunning(),
				'errors' => array(),
				'settings' => $this->extension->getSettings()->get()
			)
		);
		$this->response->setResponse($response);

		$this->response->addJsCallback('wptsafCallback.popupShowContent');

		return $this->response;
	}

	public function settingsSave(){
		$validator = wptsafValidator::getInstance();
		$settings = $this->extension->getSettings();
		$request = array();
		$errors = array();

		$request['is_enabled'] = isset($_POST['is_enabled'])
			? ((bool)$_POST['is_enabled'])
			: false;

		$request['frequency_running'] = isset($_POST['frequency_running'])
			? strip_tags($_POST['frequency_running'])
			: false;

		$request['time_running']['h'] = isset($_POST['time_running']['h']) && is_numeric($_POST['time_running']['h'])
			? intval($_POST['time_running']['h'])
			: 0;
		$request['time_running']['m'] = isset($_POST['time_running']['m']) && is_numeric($_POST['time_running']['m'])
			? intval($_POST['time_running']['m'])
			: 0;

		$request['letter_title'] = isset($_POST['letter_title']) ? strip_tags($_POST['letter_title']) :'';
		$errors['letter_title'] = $validator->validate('required', $request['letter_title']);

		$errors = array_filter($errors);
		if (empty($errors)) {
			foreach ($request as $field => $value) {
				$settings->set($field, $value);
			}
			$settings->save();
			$request = $settings->get();
		}

		$view = new wptsafView();
		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/settings.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'frequencyRunning' => $this->extension->getSettings()->getFrequencyRunning(),
				'errors' => $errors,
				'settings' => $request
			)
		);
		$this->response->setResponse($response);

		if (empty($errors)) {
			$this->response->addMessage(__('Settings are updated', 'wptsaf_security'), wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS);
			$this->response->addJsCallback(array('wptsafCallback.updateWidget', $this->extension->getName()));
			$this->response->addJsCallback('wptsafCallback.popupHide');
		} else {
			$this->response->addJsCallback('wptsafCallback.popupShowContent');
		}

		return $this->response;
	}
}
