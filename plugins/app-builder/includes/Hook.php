<?php

/**
 * Hook
 *
 * @link       https://appcheap.io
 * @since      1.0.0
 *
 */

namespace AppBuilder;

use AppBuilder\Hooks\AdsHook;
use AppBuilder\Hooks\CaptchaHook;
use AppBuilder\Hooks\CategoryHook;
use AppBuilder\Hooks\DigitsHook;
use AppBuilder\Hooks\ShortcodeHook;
use AppBuilder\Hooks\TemplateHook;
use AppBuilder\Hooks\UserHook;
use AppBuilder\Hooks\VendorHook;
use AppBuilder\Hooks\WooHook;
use AppBuilder\Hooks\WpHook;
use AppBuilder\Hooks\WpmlHook;
use AppBuilder\Hooks\AvatarHook;
use AppBuilder\Hooks\StoreHook;
use AppBuilder\Lms\LmsHooks;

defined( 'ABSPATH' ) || exit;

class Hook {
	public function __construct() {
		new WooHook();
		new WpmlHook();
		new UserHook();
		new DigitsHook();
		new TemplateHook();
		new ShortcodeHook();
		new CategoryHook();
		new AvatarHook();
		new StoreHook();
		new WpHook();
		new AdsHook();
		new LmsHooks();
		new VendorHook();
		new CaptchaHook();
	}
}
