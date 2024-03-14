<?php

/**
 * class LmsPermission
 *
 * @link       https://appcheap.io
 * @since      2.5.0
 *
 * @author     AppCheap <ngocdt@rnlab.io>
 */

namespace AppBuilder\Lms;

use AppBuilder\Lms\MasterStudy\Hooks;

class LmsHooks {
	public function __construct() {
		new Hooks();
	}
}
