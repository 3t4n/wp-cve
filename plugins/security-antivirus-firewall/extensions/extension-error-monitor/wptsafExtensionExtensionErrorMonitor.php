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

class wptsafExtensionExtensionErrorMonitor extends wptsafAbstractExtension{
	const DISABLE_ERROR_AMOUNT = 5;
	protected static $instance;

	public function __construct(){
		$this->name = 'extension-error-monitor';
		$this->title = __('S.A.F. Debug', 'wptsaf_security');
		$this->description = __("This tool control S.A.F. module to avoid internal errors of other modules. In the case if this module detect some errors in any other part of S.A.F. it's automatically deactivate this module and add notification message in log with details. Stay up to date with status of every element of S.A.F. system", 'wptsaf_security');

		parent::__construct();
	}

	public static function getInstance(){
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected function init(){
		add_action('wptsaf_security_admin_page_before_content', array($this, 'actionAdminPageBeforeContent'));
		add_action('wptsaf_security_extension_log_insert_row', array($this, 'actionErrorMonitor'), 10, 3);
	}

	public function actionAdminPageBeforeContent(){
		$messages = $this->log->getShowMessages();

		if (empty($messages)) {
			return;
		}

		$view = new wptsafView();
		$view->render(
			$this->getExtensionDir() . 'template/messages.php',
			array(
				'extensionTitle' => $this->getTitle(),
				'messages' => $messages
			)
		);
	}

	public function actionErrorMonitor(wptsafAbstractExtension $extension, array $fields, $insertId){
		if (
			$extension instanceof wptsafExtensionSystemLog
			&& (isset($fields['type']) && wptsafExtensionSystemLogLog::MESSAGE_TYPE_DANGER == $fields['type'])
		) {
			$errorExtensionTitle = isset($fields['extension']) ? $fields['extension'] : null;
			$errorExtension = $errorExtensionTitle
				? wptsafSecurity::getInstance()->getExtensionByTitle($errorExtensionTitle)
				: null;

			if ($errorExtension && $errorExtension instanceof wptsafSecurity) {
				return;
			}

			if (!$errorExtension) {
				wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
					wptsafSecurity::getInstance(),
					sprintf(__('Could not find extension by title "%s"', 'wptsaf_extension'), $errorExtensionTitle)
				);
			}

			if ($errorExtension) {
				$this->disableExtensionIfNeed($errorExtension);
			}
		}
	}


	protected function disableExtensionIfNeed(wptsafAbstractExtension $extension){

		$log = wptsafExtensionSystemLog::getInstance()->getLog();
		$messages = $log->getMessagesByExtension($extension);
		$count = 0;

		foreach ($messages as $message) {
			if (wptsafExtensionSystemLogLog::MESSAGE_TYPE_DANGER === $message['type']) {
				$count++;
			}
		}

		if (self::DISABLE_ERROR_AMOUNT > $count) {
			return;
		}

		wptsafExtensionSystemLog::getInstance()->getLog()->addWarningMessage(
			$extension,
			__('Module deactivated. Errors limit exceeded.', 'wptsaf_extension')
		);

		$settings = $extension->getSettings();
		if (null === $settings->get('is_enabled')) {
			return;
		}

		$settings->set('is_enabled', false);
		$settings->save();

		$this->log->insertRow(array(
			'extension' => $extension->getTitle(),
			'is_show' => 1,
			'hidden_by' => ''
		));
	}


	public function isEnabled(){
		return true;
	}
}
