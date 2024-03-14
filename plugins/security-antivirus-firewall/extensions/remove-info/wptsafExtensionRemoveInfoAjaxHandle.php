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

class wptsafExtensionRemoveInfoAjaxHandle extends wptsafAbstractExtensionAjaxHandle{

	public function settingsSave(){
		$settings = $this->extension->getSettings();
		$request = array();

		$request['is_remove_info'] = isset($_POST['is_remove_info'])
			? ((bool)$_POST['is_remove_info'])
			: false;


		foreach ($request as $field => $value) {
			$settings->set($field, $value);
		}
		$settings->save();
		$request = $settings->get();

		$view = new wptsafView();
		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/settings.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'settings' => $request
			)
		);
		$this->response->setResponse($response);
		$this->response->addMessage(__('Settings are updated', 'wptsaf_security'), wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS);
		$this->response->addJsCallback(array('wptsafCallback.updateWidget', $this->extension->getName()));
		$this->response->addJsCallback('wptsafCallback.popupHide');

		return $this->response;
	}
}
