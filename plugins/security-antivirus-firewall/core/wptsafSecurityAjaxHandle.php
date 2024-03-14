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

class wptsafSecurityAjaxHandle extends wptsafAbstractExtensionAjaxHandle{

	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);
	}

	public function settingsSave(){
		$validator = wptsafValidator::getInstance();
		$settings = $this->extension->getSettings();
		$request = array();
		$errors = array();

		$request['log_rotation'] = is_numeric($_POST['log_rotation'])
			? intval($_POST['log_rotation'])
			: strip_tags($_POST['log_rotation']);
		$errors['log_rotation'] = $validator->validate('positive_integer', $request['log_rotation']);

		$request['notification_emails'] = isset($_POST['notification_emails'])
			? strip_tags($_POST['notification_emails'])
			: '';
		$errors['notification_emails'] = $validator->validate('required', $request['notification_emails']);

		$request['notification_emails'] = array_map('trim', explode("\n", $request['notification_emails']));
		$errors['notification_emails'] || $errors['notification_emails'] = $validator->validate('email_list', $request['notification_emails']);

		$errors = array_filter($errors);
		if (empty($errors)) {
			$request['notification_emails'] = array_filter($request['notification_emails']);
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
			$this->response->addMessage(__('Settings are updated', 'security-antivirus-firewall'), wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS);
		}
		return $this->response;
	}

	public function hideWizard( $type = 0 ){

		update_option( "wpsaf_hide_wizard", 99 );

		/*if(!$type){
			$this->response->addMessage( 
				__("Wizard will be disabled until publication of the new functions", 'security-antivirus-firewall'), 
				wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS
			);
			$this->response->setResponse($response);
		}*/

		if($type){
			$this->response->addJsCallback('wptsafCallback.dialogHide');
			$this->response->addJsCallback('wptsafCallback.popupHide');
		} else {
			$this->response->addJsCallback('wptsafCallback.pageRefresh');
		}
		return $this->response;
	}


	public function activateModulle( $module ){
		$report = $this->extension->getExtension($module);
		$settings = $report->getSettings();
		if ($settings->get('is_enabled') !== true) {
			$settings->set('is_enabled', true);
			$settings->save();
		}
	}

	public function wizardStepOne(){

		$this->activateModulle('404-detection');
		$this->activateModulle('easy-password');
		//$this->activateModulle('file-change');
		$this->activateModulle('google-captcha');
		$this->activateModulle('login-brute-force');
		$this->activateModulle('network-monitor');
		$this->activateModulle('report');

		delete_option("wpsaf_hide_wizard");
		add_option( "wpsaf_hide_wizard", 99);

		$view = new wptsafView();

		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/wizard2.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'state' => '',
			)
		);

		$this->response->setResponse($response);
		//$this->response->addJsCallback('wptsafCallback.dialogHide');
		//$this->response->addJsCallback('wptsafCallback.popupShowContent');
		$this->response->addJsCallback('wptsafCallback.dialogShowContent');

		return $this->response;
	}


	public function wizardStepTwo(){
		$view = new wptsafView();
		
		delete_option("wpsaf_hide_wizard");
		add_option( "wpsaf_hide_wizard", 2);

		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/wizard2.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'state' => '',
			)
		);

		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.dialogShowContent');

		return $this->response;
	}
}
