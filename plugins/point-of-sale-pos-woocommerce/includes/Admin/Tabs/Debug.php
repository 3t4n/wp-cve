<?php

namespace ZPOS\Admin\Tabs;

use ZPOS\Admin\Setting\Box;

use ZPOS\Admin\Setting\Input\Checkbox;
use ZPOS\Admin\Setting\PageTab;
use ZPOS\Admin\Setting\Input\TextArea;
use ZPOS\Deactivate;
use ZPOS\Plugin;

class Debug extends PageTab
{
	public $exact = true;
	public $name = 'Debug';
	public $path = '/debug';

	public function getBoxes()
	{
		return [
			new Box(
				__('Uninstall options', 'zpos-wp-api'),
				null,

				new Checkbox(
					__('Delete Data and Reset', 'zpos-wp-api'),
					Plugin::RESET_OPTION,
					[$this, 'getResetMode'],
					__('Yes, when the plugin is uninstalled. Delete data and reset', 'zpos-wp-api')
				)
			),
			new Box(
				__('Debug', 'zpos-wp-api'),
				null,

				new Checkbox(
					null,
					'pos_debug_mode',
					[$this, 'getDebugMode'],
					__('Enable developer mode for debugging', 'zpos-wp-api')
				),

				new TextArea(null, null, [$this, 'getDebugValue'], ['readOnly' => true])
			),
		];
	}

	public function getResetMode()
	{
		return get_option(Plugin::RESET_OPTION);
	}

	public function getDebugMode()
	{
		return get_option('pos_debug_mode');
	}

	public function getDebugValue()
	{
		ob_start();
		print_r($_SERVER);
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}

	public function init()
	{
		register_setting('pos' . $this->path, 'pos_debug_mode', [
			'sanitize_callback' => function ($data) {
				return filter_var($data, FILTER_VALIDATE_BOOLEAN);
			},
			'default' => false,
		]);

		register_setting('pos' . $this->path, Plugin::RESET_OPTION, [
			'sanitize_callback' => function ($data) {
				return filter_var($data, FILTER_VALIDATE_BOOLEAN);
			},
			'default' => false,
		]);
	}

	public static function reset()
	{
		if (!did_action(Deactivate::class . '::resetSettings')) {
			return _doing_it_wrong(
				__METHOD__,
				'Reset POS settings should called by ' . Deactivate::class . '::resetSettings',
				'2.0.3'
			);
		}

		delete_option('pos_debug_mode');
		delete_option(Plugin::RESET_OPTION);
	}
}
