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

class wptsafExtensionNetworkMonitorAjaxHandle extends wptsafAbstractExtensionAjaxHandle{
	public function __construct(wptsafAbstractExtension $extension){
		parent::__construct($extension);

		$this->logHeader = array(
			'date_gmt' => __('Ban from', 'wptsaf_security'),
			'ip' => __('IP address', 'wptsaf_security'),
		);

		$this->logRowHeader = array(
			'ip' => __('IP address', 'wptsaf_security'),
			'date_gmt' => __('Ban from', 'wptsaf_security'),
			'lock_count' => __('Lock count', 'wptsaf_security'),
			'client_data' => __('Client Data', 'wptsaf_security'),
		);
	}

	public function settingsSave(){
		$validator = wptsafValidator::getInstance();
		$settings = $this->extension->getSettings();
		$request = array_intersect_key($_POST, $settings->get());
		$errors = array();

		if(!WPTSAF_PRO){
			$request['lock_duration'] = 3;
			$request['lock_duration_second'] = 10;
		}

		if ($request) {
			$request['is_enabled'] = isset($request['is_enabled'])
				? ((bool)$request['is_enabled'])
				: false;

			$request['log_rotation'] = is_numeric($request['log_rotation'])
				? intval($request['log_rotation'])
				: strip_tags($request['log_rotation']);
			$errors['log_rotation'] = $validator->validate('log_rotation', $request['log_rotation']);

			$request['lock_duration'] = is_numeric($request['lock_duration'])
				? intval($request['lock_duration'])
				: strip_tags($request['lock_duration']);
			$errors['lock_duration'] = $validator->validate('network_monitor_lock_duration', $request['lock_duration']);

			$request['lock_duration_second'] = is_numeric($request['lock_duration_second'])
				? intval($request['lock_duration_second'])
				: strip_tags($request['lock_duration_second']);
			$errors['lock_duration_second'] = $validator->validate('network_monitor_lock_duration', $request['lock_duration_second']);

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

	public function managerIp(){
		$view = new wptsafView();
		$rows = $this->extension->getManagerIp()->getRows(WPTSAF_LOG_LIMIT);
		$limitMessage = null;

		if (
			WPTSAF_LOG_LIMIT == count($rows)
			&& $this->extension->getManagerIp()->getRows(1, WPTSAF_LOG_LIMIT)
		) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addWarningMessage(
				$this->extension,
				__('The number of entries in the manager ip log exceeds the display limit', 'wptsaf_security')
			);
			$limitMessage = __('The number of entries in the manager ip log exceeds the display limit', 'wptsaf_security');
		}

		for ($i=0; $i < count($rows); $i++) { 
			if( isset($rows[$i]['ip']) )  $rows[$i]['ip'] = wpToolsSAFHelperClass::getIpInfo( $rows[$i]['ip'], 1);
		}
		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/manager-ip.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'limitMessage' => $limitMessage,
				'header' => array(
					'ip' => __('IP address', 'wptsaf_security'),
					'permanent' => __('Type', 'wptsaf_security'),
					'is_active' => __('Status', 'wptsaf_security'),
					'description' => __('Description', 'wptsaf_security'),
				),
				'rows' => $rows
			)
		);

		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.popupShowContent');

		return $this->response;
	}

	public function managerIpRow($id = null){
		$view = new wptsafView();
		$row = $id
			? $this->extension->getManagerIp()->getRow($id)
			: array(
				'ip' => '',
				'date_gmt' => date(WPTSAF_DATE_FORMAT, wptsafEnv::getInstance()->getDateGmt() + WPTSAF_DATE_GMT_OFFSET),
				'duration' => $this->extension->getSettings()->get('lock_duration'),
				'is_active' => 0,
				'lock_count' => 0,
				'description' => '',
				'change_log_comment' => ''
			);

		if (is_wp_error($row)) {
			$this->response->addError($row);
			$this->response->addJsCallback('wptsafCallback.formHide');
			return $this->response;
		}
		
		if ($row['ip']) {
			$row['lock_count'] = $this->extension->getLog()->getLockCount($row['ip']);
		} else {
			$row['lock_count'] = 0;
		}

		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/manager-ip-row.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'row' => $row
			)
		);
		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.formShowContent');

		return $this->response;
	}

	public function managerIpRowByIp($ip){
		$ip = preg_replace('/[^0-9\.]/', '', $ip);
		$ban = $this->extension->getManagerIp()->getBanByIp($ip);

		if (!$ban) {
			$view = new wptsafView();
			$row = array(
				'ip' => $ip,
				'date_gmt' => date(WPTSAF_DATE_FORMAT, wptsafEnv::getInstance()->getDateGmt() + WPTSAF_DATE_GMT_OFFSET),
				'duration' => $this->extension->getSettings()->get('lock_duration'),
				'is_active' => 0,
				'lock_count' => $this->extension->getLog()->getLockCount($ip),
				'description' => '',
				'change_log_comment' => ''
			);

			$response = $view->content(
				$this->extension->getExtensionDir() . 'template/manager-ip-row.php',
				array(
					'extensionTitle' => $this->extension->getTitle(),
					'row' => $row
				)
			);
			$this->response->setResponse($response);
			$this->response->addJsCallback('wptsafCallback.formShowContent');

			return $this->response;
		}

		return $this->managerIpRow($ban['id']);
	}

	public function managerIpRowSave(){
		$view = new wptsafView();
		$validator = wptsafValidator::getInstance();
		$request = array();
		$errors = array();


		$request['id'] = isset($_POST['id']) ? absint($_POST['id']) : null;

		$request['is_active'] = isset($_POST['is_active'])
			? ((bool)$_POST['is_active'])
			: false;

		$request['ip'] = strip_tags($_POST['ip']);
		$errors['ip'] = $validator->validate('required', $request['ip']);
		$errors['ip'] || $errors['ip'] = $validator->validate('ip', $request['ip']);

		$responseBanMyself = $request['is_active'] ? $this->isBanMyself($request['ip']) : false;
		if ($responseBanMyself) {
			return $responseBanMyself;
		}

		$ban = $this->extension->getManagerIp()->getBanByIp($request['ip']);
		if ($ban && $ban['id'] != $request['id']) {
			$this->response->addError(__("IP address already exists", 'wptsaf_security'));
			return $this->response;
		}

		$request['date_gmt'] = strip_tags($_POST['date_gmt']);
		$errors['date_gmt'] = $validator->validate('required', $request['date_gmt']);
		$errors['date_gmt'] || $errors['date_gmt'] = $validator->validate('date', $request['date_gmt']);

		$request['duration'] = is_numeric($_POST['duration'])
			? intval($_POST['duration'])
			: strip_tags($_POST['duration']);
		$errors['duration'] = $validator->validate('required', $request['duration']);
		$errors['duration'] || $errors['duration'] = $validator->validate('positive_integer', $request['duration']);

		$request['description'] = trim(strip_tags($_POST['description']));
		$request['change_log_comment'] = strip_tags($_POST['change_log_comment']);


		$errors = array_filter($errors);
		if (!empty($errors)) {
			$response = $view->content(
				$this->extension->getExtensionDir() . 'template/manager-ip-row.php',
				array(
					'extensionTitle' => $this->extension->getTitle(),
					'errors' => $errors,
					'row' => $request
				)
			);
			$this->response->setResponse($response);
			$this->response->addJsCallback('wptsafCallback.formShowContent');
			return $this->response;
		}

		if ($request['id']) {
			$row = $this->extension->getManagerIp()->getRow($request['id']);
			if (is_wp_error($row)) {
				$this->response->addError($row);
				return $this->response;
			}
		}

		$request['date_gmt'] = DateTime::createFromFormat(WPTSAF_DATE_FORMAT, $request['date_gmt'])->getTimestamp() - WPTSAF_DATE_GMT_OFFSET;
		if ($request['id']) {
			$result = $this->extension->getManagerIp()->updateRow($request);
		} else {
			$result = $this->extension->getManagerIp()->insertRow($request);
			$request['id'] = is_wp_error($result) ? null : $result;
		}
		if (is_wp_error($result)) {
			$this->response->addError($result);
			return $this->response;
		}
		
		$this->response->addMessage(
			$request['id'] ? __('Ban ip is updated', 'wptsaf_security') : __('Ban ip is created', 'wptsaf_security'),
			wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS
		);
		$this->response->addJsCallback(array('wptsafCallback.updateWidget', $this->extension->getName()));
		$this->response->addJsCallback(array(
			'wptsafCallback.popupUpdateDataTableRow',
			array_merge($this->extension->getManagerIp()->prepareRow($request), array('actions' => $request['id']))
		));
		$this->response->addJsCallback('wptsafCallback.formHide');
		return $this->response;
	}


	public function managerIpBanByIp($ip, $duration = null, $comment = ''){

		if ($response = $this->isBanMyself($ip)) {
			return $response;
		}

		$ip = preg_replace('/[^0-9\.]/', '', $ip);
		$comment = strip_tags($comment);
		$ban = $this->extension->getManagerIp()->getBanByIp($ip);

		if ($ban) {
			return $this->managerIpBanById($ban['id'], $duration, $comment);
		}	

		if ($response = $this->isBanMyself($ban['ip'])) {
			return $response;
		}

		$result = $this->extension->getManagerIp()->banByIp($ip, $duration, $comment);
		if (is_wp_error($result)) {
			$this->response->addError($result);
		} else {
			$this->response->addMessage(
				__('IP address is banned', 'wptsaf_security'),
				wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS
			);
		}
		$this->response->addJsCallback(array('wptsafCallback.updateWidget', $this->extension->getName()));

		return $this->response;
	}

	public function managerIpBanById($id, $duration = null, $comment = ''){
		$ban = $this->extension->getManagerIp()->getBanById($id);
		if (!$ban) {
			$this->response->addError(__("Could not find IP address", 'wptsaf_security'));
			return $this->response;
		}

		$result = $this->extension->getManagerIp()->banById($id, $duration, $comment);
		if (is_wp_error($result)) {
			$this->response->addError($result);
		} else {
			$this->response->addMessage(
				$ban['is_active'] ? __('IP address already is banned', 'wptsaf_security') : __('IP address is banned', 'wptsaf_security'),
				wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS
			);
		}
		$this->response->addJsCallback(array('wptsafCallback.updateWidget', $this->extension->getName()));

		return $this->response;
	}

	public function managerIpSecondBanByIp($ip, $comment = ''){
		$duration = $this->extension->getSettings()->get('lock_duration_second');
		return $this->managerIpBanByIp($ip, $duration, $comment);
	}

	public function managerIpSecondBanById($id, $comment = ''){
		$duration = $this->extension->getSettings()->get('lock_duration_second');
		return $this->managerIpBanById($id, $duration, $comment);
	}

	public function managerIpDisableBanById($id){
		$ban = $this->extension->getManagerIp()->getBanById($id);

		if (!$ban) {
			$this->response->addError(__("Could not find IP address", 'wptsaf_security'));
			return $this->response;
		}

		if ($ban['is_active']) {
			$ban['is_active'] = 0;
			$this->extension->getManagerIp()->updateRow($ban);

			$this->response->addMessage(
				__('IP address is unbanned', 'wptsaf_security'),
				wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS
			);
		} else {
			$this->response->addError(__("Could not disable ban IP address", 'wptsaf_security'));
		}

		$this->response->addJsCallback(array('wptsafCallback.updateWidget', $this->extension->getName()));
		return $this->response;
	}

	public function managerIpChangeLog($id){
		$view = new wptsafView();
		$rows = $this->extension->getManagerIpChangeLog()->getRowsByManagerIpId($id);

		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/manager-ip-change-log.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'header' => array(
					'date_gmt' => __('Date', 'wptsaf_security'),
					'comment' => __('Comment', 'wptsaf_security')
				),
				'rows' => $rows
			)
		);
		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.targetShowContent');

		return $this->response;
	}

	public function managerIpExportLog(){
		$fileName = $this->extension->getName() . '-manager-' . date('Y-m-d') . '.txt';

		header('Content-Description: File Transfer');
		header('Content-type: text/plain; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $fileName . '"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		echo $this->extension->getManagerIp()->export();
	}


	protected function isBanMyself($ip){
		if (wptsafEnv::getInstance()->getIp() !== $ip) {
			return false;
		}

		$view  = new wptsafView();
		$this->response->setResponse($view->content(
			$this->extension->getExtensionDir() . 'dialogs/cannot-ban-yourself.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
			)
		));
		$this->response->addJsCallback('wptsafCallback.dialogShowContent');
		return $this->response;
	}
}
