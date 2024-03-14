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

class wptsafValidator{

	protected static $instance;
	protected $validations = array();

	protected function __construct(){
		$this->addValidation('required', array($this, 'validateRequired'));
		$this->addValidation('positive_integer', array($this, 'validatePositiveInteger'));
		$this->addValidation('date', array($this, 'validateDate'));
		$this->addValidation('ip', array($this, 'validateIp'));
		$this->addValidation('log_rotation', array($this, 'validateLogRotation'));
		$this->addValidation('email_list', array($this, 'validateEmailList'));
		$this->addValidation('file_dir_list', array($this, 'validateFileDirList'));
		$this->addValidation('ignore_file_types', array($this, 'validateIgnoreFileTypes'));

		do_action('wptsaf_security_init_validator', $this);
	}

	protected function __clone() {}

	public static function getInstance(){
		if (!self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function validate($field, $value){
		if (!isset($this->validations[$field])) {
			wptsafExtensionSystemLog::getInstance()->getLog()->addDangerMessage(
				wptsafSecurity::getInstance(),
				sprintf(__('Could not find validation for field "%s"', 'security-antivirus-firewall'), $field)
			);
			return __('Could not find validation for field', 'security-antivirus-firewall');
		}

		foreach ($this->validations[$field] as $validationCallback) {
			if ($error = call_user_func($validationCallback, $value)) {
				return $error;
			}
		}
		return null;
	}

	public function addValidation($name, $callback){
		$this->validations[$name][] = $callback;
	}

	public function validateRequired($value){
		if ('' === $value) {
			return __('The field is required', 'security-antivirus-firewall');
		}
		return null;
	}

	public function validatePositiveInteger($value){
		if (!is_int($value)) {
			return __('The value has to be integer', 'security-antivirus-firewall');
		}
		if (0 > $value) {
			return __("The value has to be positive integer", 'security-antivirus-firewall');
		}

		return null;
	}

	public function validateDate($value){
		$date = DateTime::createFromFormat(WPTSAF_DATE_FORMAT, $value);
		if ($date) {
			$date = $date->getTimestamp();
		}

		if (date(WPTSAF_DATE_FORMAT, $date) != $value) {
			return __('Wrong format of date', 'security-antivirus-firewall');
		}

		return null;
	}

	public function validateIp($value){
		if (!preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $value)) {
			return __('Wrong IP address format', 'security-antivirus-firewall');
		}
		return null;
	}

	public function validateLogRotation($value){
		if ('' === $value) {
			return __('The field is required', 'security-antivirus-firewall');
		}
		if (!is_int($value)) {
			return __('The value has to be integer', 'security-antivirus-firewall');
		}
		if ($value < -1) {
			return __("The value doesn't have to be less then -1", 'security-antivirus-firewall');
		}

		return null;
	}

	public function validateEmailList($emails){
		$errors = array();

		if (!is_array($emails)) {
			$emails = array($emails);
		}
		foreach ($emails as $line => $email) {
			if (empty($email)) {
				continue;
			}

			if (!is_email($email)) {
				$errors[] = sprintf(__('Invalid email address in line #%s', 'security-antivirus-firewall'), $line + 1);
			}
		}

		return empty($errors) ? null : implode('<br />', $errors);
	}

	public function validateFileDirList($fileDirList){
		$errors = array();

		if (!is_array($fileDirList)) {
			$fileDirList = array($fileDirList);
		}
		foreach ($fileDirList as $line => $path) {
			if (empty($path)) {
				continue;
			}

			$absPath = rtrim(ABSPATH, '/') . $path;
			if (!file_exists($absPath)) {
				$errors[] = sprintf(__('Wrong path. File or directory is absent by path in line %d', 'security-antivirus-firewall'), $line + 1);
			}
		}

		return empty($errors) ? null : implode('<br />', $errors);
	}

	public function validateIgnoreFileTypes($types){
		$errors = array();

		if (!is_array($types)) {
			$types = array($types);
		}
		foreach ($types as $line => $type) {
			if (empty($type)) {
				continue;
			}

			if (!preg_match('/^[a-z0-9]+$/', $type)) {
				$errors[] = sprintf(__('Wrong file type in line %d', 'security-antivirus-firewall'), $line + 1);
			}
		}

		return empty($errors) ? null : implode('<br />', $errors);
	}
}
