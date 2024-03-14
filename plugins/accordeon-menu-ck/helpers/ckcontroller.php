<?php
Namespace Accordeonmenuck;
/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) since 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.ceikay.com
 */
// No direct access
defined('CK_LOADED') or die;

class CKController {

	protected $input;

	protected $model;

	function __construct() {
		$this->input = new CKInput();
	}

	/**
	 * Check if you have the latest version
	 * 
	 * @return boolean, true if outdated
	 */
	// public static function is_outdated() {
		// return version_compare(self::get_latest_version(), self::get_current_version() ) > 0;
	// }

	public function getModel($name, $base = '\Accordeonmenuck\CKModel') {
		if (empty($this->model)) {
			require_once(ACCORDEONMENUCK_PATH . '/helpers/ckmodel.php');
			require_once(ACCORDEONMENUCK_PATH . '/models/' . strtolower($name) . '.php');
			$className = ucfirst($base) . ucfirst($name);
			$this->model = new $className;
		}
		return $this->model;
	}

	/*
	 * Due to issues when uninstalling and reinstalling, we need to propose a manual way to create the tables
	 */
	public function fixSqlTable() {

	}

	/**
	 * Get the file and store it on the server
	 * 
	 * @return mixed, the method return
	 */
	public function ajaxAddPicture() {
		require_once ACCORDEONMENUCK_PATH . '/helpers/ckbrowse.php';
		CKBrowse::ajaxAddPicture();
	}

	/**
	 * Load the backup file
	 * 
	 * @return string, the html content
	 */
	public function ajaxDoRestoration() {
		// security check
		CKFof::checkAjaxToken();

		$input = new CKInput();
		$id = $input->get('id', 0, 'int');
		$name = $input->get('name','', 'string');
		$isLocked = $input->get('isLocked', 0, 'int');
		$filename = ($isLocked ? 'locked' : 'backup') . '_' . $id . '_' . $name . '.tck3';
		$path = ACCORDEONMENUCK_PATH . '/backup/' . $id . '_bak';
		$content = file_get_contents($path . '/' . $filename);
		$backup = json_decode($content);

		echo str_replace('|CKURIROOT|', ACCORDEONMENUCK_URI_ROOT, $content);
		exit();
	}

	public function edit() {
		$editIds = $this->input->get('cid', null, 'array');
		if (count($editIds)) {
			$editId = (int) $editIds[0];
		} else {
			$editId = (int) $this->input->get('id', null, 'int');
		}

		// Redirect to the edit screen.
		CKFof::redirect(ACCORDEONMENUCK_ADMIN_EDIT_MENU_URL . '&view=' . $this->view . '&layout=edit&id=' . $editId);
	}

	public function copy() {
		$editIds = $this->input->get('cid', null, 'array');
		if (count($editIds)) {
			$id = (int) $editIds[0];
		} else {
			$id = (int) $this->input->get('id', null, 'int');
		}
		$model = $this->getModel($this->view);
		if ($model->copy($id)) {
			CKFof::enqueueMessage('Item copied with success');
		} else {
			CKFof::enqueueMessage('Error : Item not copied', 'error');
		}

		// Redirect to the edit screen.
//		CKFof::redirect(ACCORDEONMENUCK_ADMIN_GENERAL_URL);
	}

	public function delete() {
		$editIds = $this->input->get('cid', null, 'array');
		if (count($editIds)) {
			$id = (int) $editIds[0];
		} else {
			$id = (int) $this->input->get('id', null, 'int');
		}
		$model = $this->getModel($this->view);
		if ($model->delete($id)) {
			CKFof::enqueueMessage('Item deleted with success');
		} else {
			CKFof::enqueueMessage('Error : Item not deleted', 'error');
		}

		// Redirect to the edit screen.
//		CKFof::redirect(ACCORDEONMENUCK_ADMIN_GENERAL_URL);
	}
}
