<?php

namespace ZPOS;

class Login
{
	public function __construct()
	{
		add_action('login_enqueue_scripts', [$this, 'enqueue_login_assets'], 0);
		add_action('login_enqueue_scripts', [$this, 'change_login_logo'], 0);
	}

	public static function maybe_redirect_to_login(): bool
	{
		if (is_user_logged_in()) {
			return false;
		}

		add_filter('login_url', function (string $login_url): string {
			return add_query_arg('redirect_to', get_the_permalink(), $login_url);
		});
		auth_redirect();

		return true;
	}

	public static function get_logo_url(): string
	{
		return Frontend::getLogo() ? Frontend::getLogo() : Plugin::getUrl('assets/admin/logo.png');
	}

	public function enqueue_login_assets(): void
	{
		wp_enqueue_script('login', Plugin::getAssetUrl('login.js', false), ['jquery']);
	}

	public function change_login_logo(): void
	{
		if (!strpos($_SERVER['REQUEST_URI'], '/pos/')) {
			return;
		} ?>
		<style>
			#login h1 a, .login h1 a {
				background-image: url(<?php echo self::get_logo_url(); ?>);
			}
		</style>
		<?php
	}
}
