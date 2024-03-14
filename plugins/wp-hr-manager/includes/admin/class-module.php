<?php
namespace WPHR\HR_MANAGER\Admin;

/**
 * Administration Module Class
 *
 * @package payroll
 */
class Admin_Module {

	function __construct() {
		$this->output();
	}

	function output() {
		require_once WPHR_VIEWS . '/module.php';
	}
}
