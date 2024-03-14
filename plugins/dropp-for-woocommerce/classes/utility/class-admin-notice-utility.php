<?php

namespace Dropp\Utility;

use Dropp\Admin_Notice;

class Admin_Notice_Utility
{
	protected static array $items;

	public static function setup(): void
	{
		add_action('admin_footer', fn() => print(self::javascript()));
		add_action('admin_notices', fn() => print(self::render()));
	}

	public static function register(string $code, Admin_Notice $admin_notice): void
	{
		self::$items[$code] = $admin_notice;
	}

	public static function get(string $code): Admin_Notice
	{
		return self::$items[$code];
	}

	public static function update(): void
	{
		update_option(
			'dropp_for_woocommerce_admin_notices',
			self::make_options()
		);
	}

	public static function make_options(): array
	{
		$notice_options = [];
		/**
		 * @var string       $code
		 * @var Admin_Notice $item
		 */
		foreach (self::$items as $code => $item) {
			$notice_options [$code] = $item->get_options();
		}
		return $notice_options;
	}

	public static function render(): string
	{
		$active = self::get_active();
		$buffer = array_map(
			fn(Admin_Notice $notice, $code) => $notice->render($code),
			$active,
			array_keys($active)
		);
		return implode('', $buffer);
	}

	private static function javascript(): string
	{
		if (empty(self::get_active())) {
			return '';
		}
		$ajaxurl = admin_url('admin-ajax.php');
		return <<<HTML
<script>jQuery(
	function($) {
        console.log('done')
        console.log($('.dropp-admin-notice .notice-dismiss').length);
		$('.dropp-admin-notice').on('click', '.notice-dismiss', function () {
            console.log('yo');
			$.post('$ajaxurl', {
				action: 'dropp_dismiss_admin_notice',
				notice_code: $(this).closest('.dropp-admin-notice').prop('id'),
			});
		});
	}
);</script>
HTML;

	}

	public static function load_options(): void
	{
		$options = get_option('dropp_for_woocommerce_admin_notices', []);
		if (! is_array($options)) { return; }
		foreach ($options as $code => $notice_options) {
			$notice = self::$items[$code] ?? null;
			if (!$notice) {
				continue;
			}
			$notice->set_options($notice_options);

		}
	}

	private static function get_active(): array
	{
		return array_filter(
			self::$items,
			fn(Admin_Notice $notice) => $notice->is_enabled() && !$notice->is_dismissed()
		);
	}
}
