<?php

/**
 * @package Channelize Shopping
 */




namespace Includes\Pages;

defined('ABSPATH') || exit;

use Includes\Base\CHLSBaseController;

use Includes\Api\CHLSSettingsApi;

class CHLSAdmin extends CHLSBaseController
{
	public $settings;
	public static $url = "https://channelize.io/rest/channelize/V1/api/verify/private";

	public function __construct()
	{
		$this->settings = new CHLSSettingsApi();
	}

	public function register()
	{
		$this->settings->register();
	}
}
