<?php

class OfficeGuyPluginSetup
{
	public static function Init($File)
	{
		register_activation_hook($File, 'OfficeGuyPluginSetup::ActivateHook');
		add_action('admin_init', 'OfficeGuyPluginSetup::ActivationRedirect');
		add_filter('plugin_action_links_' . plugin_basename($File), 'OfficeGuyPluginSetup::ActionLinks', 10, 4);
	}

	public static function ActivateHook()
	{
		add_option('officeguy_plugin_do_activation_redirect', true);
	}

	public static function ActivationRedirect()
	{
		if (!get_option('officeguy_plugin_do_activation_redirect', false))
			return;

		delete_option('officeguy_plugin_do_activation_redirect');
		if (!isset($_GET['activate-multi']))
			wp_redirect(admin_url('admin.php?page=wc-settings&tab=checkout&section=officeguy'));
	}

	public static function ActionLinks($actions, $plugin_file, $plugin_data, $context)
	{
		$new = array(
			'settings' => sprintf(
				'<a href="%s">%s</a>',
				esc_url(admin_url('admin.php?page=wc-settings&tab=checkout&section=officeguy')),
				esc_html__('Settings', 'officeguy')
			)
		);

		return array_merge($new, $actions);
	}
}
