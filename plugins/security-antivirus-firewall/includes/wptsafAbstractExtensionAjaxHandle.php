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

abstract class wptsafAbstractExtensionAjaxHandle{
	protected $extension;
	protected $response;
	protected $logHeader;
	protected $logRowHeader;

	public function __construct(wptsafAbstractExtension $extension){
		$this->extension = $extension;
		$this->response = new wptsafAjaxResponse();
	}

	public function widget(){
		$this->response->setResponse($this->extension->createWidget()->content());
		$this->response->addJsCallback('wptsafCallback.updateWidgetContent');
		return $this->response;
	}

	public function dialog($name, $popup=0){
		$name = basename($name);
		$view = new wptsafView();
		$content = $view->content(
			$this->extension->getExtensionDir() . "dialogs/{$name}.php",
			array(
				'extensionName' => $this->extension->getName(),
				'extensionTitle' => $this->extension->getTitle()
			)
		);

		$this->response->setResponse($content);
		if($popup){
			$this->response->addJsCallback('wptsafCallback.popupShowContent');
		} else {
			$this->response->addJsCallback('wptsafCallback.dialogShowContent');
		}

		return $this->response;
	}

	public function setEnable($isEnabled){
		$isEnabled = (bool)$isEnabled;
		$settings = $this->extension->getSettings();

		if ($settings->get('is_enabled') !== $isEnabled) {
			$settings->set('is_enabled', $isEnabled);
			$settings->save();
		}

		$this->response->setResponse($this->extension->createWidget()->content());
		$this->response->addJsCallback('wptsafCallback.updateWidgetContent');

		return $this->response;
	}

	public function settings(){
		$view = new wptsafView();
		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/settings.php',
			array(
				'extensionTitle' => $this->extension->getTitle(),
				'errors' => array(),
				'settings' => $this->extension->getSettings()->get()
			)
		);

		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.popupShowContent');

		return $this->response;
	}

	abstract public function settingsSave();

	public function log(){
		$view = new wptsafView();
		$rows = $this->extension->getLog()->getRows(WPTSAF_LOG_LIMIT);
		$limitMessage = null;

		if (is_wp_error($rows)) {
			$this->response->addError($rows);
			return $this->response;
		}

		if (
			WPTSAF_LOG_LIMIT == count($rows)
			&& $this->extension->getLog()->getRows(1, WPTSAF_LOG_LIMIT)
		) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addWarningMessage(
				$this->extension,
				__('The number of entries in the log exceeds the display limit', 'wptsaf_security')
			);
			$limitMessage = __('The number of entries in the log exceeds the display limit', 'wptsaf_security');
		}

		$row = reset($rows);
		if (isset($row['client_data'])) {
			foreach ($rows as $i => $row) {
				$fieldToString = '';
				foreach ($row['client_data'] as $name => $value) {
					$fieldToString .= "<p>{$name}: {$value}</p>";
				}

				$rows[$i]['client_data'] = $fieldToString;
			}
		}

		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/log.php',
			array(
				'extensionName' => $this->extension->getName(),
				'extensionTitle' => $this->extension->getTitle(),
				'limitMessage' => $limitMessage,
				'header' => $this->logHeader,
				'rows' => $rows
			)
		);

		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.popupShowContent');

		return $this->response;
	}

	public function logRow($id){
		$row = $this->extension->getLog()->getRow($id);
		$view = new wptsafView();

		if (is_wp_error($row)) {
			$this->response->addError($row);
			return $this->response;
		}

		if (isset($row['client_data'])) {
			$fieldToString = '';
			foreach ($row['client_data'] as $name => $value) {
				$fieldToString .= "<p>{$name}: {$value}</p>";
			}

			$row['client_data'] = $fieldToString;
		}

		$response = $view->content(
			$this->extension->getExtensionDir() . 'template/log-row.php',
			array(
				'extensionName' => $this->extension->getName(),
				'extensionTitle' => $this->extension->getTitle(),
				'header' => $this->logRowHeader,
				'row' => $row
			)
		);
		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.formShowContent');

		return $this->response;
	}

	public function logAskClear(){
		$view = new wptsafView();
		$response = $view->content(
			'includes/template/log-ask-clear.php',
			array(
				'extensionName' => $this->extension->getName(),
				'extensionTitle' => $this->extension->getTitle()
			)
		);
		$this->response->setResponse($response);
		$this->response->addJsCallback('wptsafCallback.dialogShowContent');

		return $this->response;
	}

	public function logClear(){
		$result = $this->extension->getLog()->clear();

		if (is_wp_error($result)) {
			$this->response->addError(__('Log is not cleared', 'wptsaf_security'));
		} else {
			$this->response->addMessage(__('Log is cleared', 'wptsaf_security'), wptsafAjaxResponse::MESSAGE_TYPE_SUCCESS);
		}
		$this->response->addJsCallback('wptsafCallback.dialogHide');
		$this->response->addJsCallback('wptsafCallback.popupHide');
		$this->response->addJsCallback(array('wptsafCallback.updateWidget', $this->extension->getName()));

		return $this->response;

	}

	public function logExport(){
		$fileName = $this->extension->getName() . '-' . date('Y-m-d') . '.txt';

		header('Content-Description: File Transfer');
		header('Content-type: text/plain; charset=utf-8');
		header('Content-Disposition: attachment; filename="' . $fileName . '"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');

		echo $this->extension->getLog()->export();
	}
}
