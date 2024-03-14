<?php

namespace Photonic_Plugin\Core;

use Photonic_Plugin\Platforms\Instagram;

class Cron {
	public function __construct() {
		global $photonic_instagram_access_token;
		$to = get_option('admin_email');
		$headers = array('Content-Type: text/html; charset=UTF-8');

		if (!empty($to)) {
			$subject = sprintf(esc_attr__('[%s - Photonic] Access Credentials Expiring Soon!', 'photonic'), get_bloginfo('name'));
			$body = sprintf(esc_html__('You are using %1$s on your website at %2$s. You have set it up to authenticate against the following platforms, for which your credentials have expired or will expire soon: ', 'photonic'), '<a href="https://wordpress.org/plugins/photonic/">Photonic</a>', site_url());

			$module_body = '';
			if (!empty($photonic_instagram_access_token)) {
				require_once PHOTONIC_PATH . "/Platforms/Instagram.php";
				$module = Instagram::get_instance();
				$soon = $module->is_token_expiring_soon(10);
				if (!empty($soon)) {
					$module_body .= sprintf(esc_html__('%1$s%2$sInstagram%3$s - Instagram credentials expire every 60 days. Facebook requires you to reauthenticate to continue using application functionality. Please reauthenticate Photonic for Instagram from your dashboard (%4$s).%5$s', 'photonic'), '<li>', '<strong>', '</strong>', admin_url('admin.php?page=photonic-auth'), '</li>');
				}
			}

			if (!empty($module_body)) {
				$body .= '<ul>' . $module_body . '</ul>';
				wp_mail($to, $subject, $body, $headers);
			}
		}
	}
}
