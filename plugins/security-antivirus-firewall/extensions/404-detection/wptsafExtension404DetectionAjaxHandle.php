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

class wptsafExtension404DetectionAjaxHandle extends wptsafAbstractExtensionAjaxHandle{

	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->logHeader = array(
			'date_gmt' 		=> __('Date', 'wptsaf_security'),
			'uri' 			=> __('URI', 'wptsaf_security'),
			'ip' 			=> __('IP address', 'wptsaf_security'),
			'type' 			=> __('Type', 'wptsaf_security'),
			'client_data' 	=> __('Client Data', 'wptsaf_security'),
		);

		$this->logRowHeader = array(
			'date_gmt' 		=> __('Date', 'wptsaf_security'),
			'uri' 			=> __('URI', 'wptsaf_security'),
			'ip'		 	=> __('IP address', 'wptsaf_security'),
			'type' 			=> __('Type', 'wptsaf_security'),
			'client_data' 	=> __('Client Data', 'wptsaf_security'),
		);
	}


	public function setEnable($isEnabled){
		$isEnabled = (bool)$isEnabled;
		$settings = $this->extension->getSettings();

		if ($settings->get('is_enabled') !== $isEnabled) {
			$settings->set('is_enabled', $isEnabled);
			$settings->save();
		}

		if ($isEnabled) {
			$view  = new wptsafView();
			$this->response->setResponse($view->content(
				$this->extension->getExtensionDir() . 'dialogs/enable.php',
				array(
					'extensionTitle' => $this->extension->getTitle()
				)
			));
			$this->response->addJsCallback('wptsafCallback.dialogShowContent');
			$this->response->addJsCallback(array('wptsafCallback.updateWidget', $this->extension->getName()));
		} else {
			$this->response->setResponse($this->extension->createWidget()->content());
			$this->response->addJsCallback('wptsafCallback.updateWidgetContent');
		}

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

		$request['log_rotation'] = is_numeric($_POST['log_rotation'])
			? intval($_POST['log_rotation'])
			: strip_tags($_POST['log_rotation']);
		$errors['log_rotation'] = $validator->validate('log_rotation', $request['log_rotation']);

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
				'errors' => $errors,
				'settings' => $request
			)
		);
		$this->response->setResponse($response);

		if (empty($errors)) {
			$this->response->addMessage(__('Settings are updated', wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS));
			$this->response->addJsCallback( array('wptsafCallback.updateWidget', $this->extension->getName()) );
			$this->response->addJsCallback('wptsafCallback.popupHide');
		} else {
			$this->response->addJsCallback('wptsafCallback.popupShowContent');
		}

		return $this->response;
	}
}
