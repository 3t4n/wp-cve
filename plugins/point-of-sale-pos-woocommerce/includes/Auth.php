<?php

namespace ZPOS;

class Auth
{
	public function __construct()
	{
		add_action('after_setup_theme', [$this, 'change_layout']);
	}

	public function change_layout(): void
	{
		remove_action('woocommerce_auth_page_header', 'woocommerce_output_auth_header');
		add_action('woocommerce_auth_page_header', [$this, 'render_header']);
	}

	public function render_header(): void
	{
		?>
		<!DOCTYPE html>
		<html <?php language_attributes(); ?>>
			<head>
				<meta name="viewport" content="width=device-width" />
				<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
				<meta name="robots" content="noindex, nofollow" />
				<title>
					<?php echo esc_html__('Application authentication request', 'woocommerce'); ?>
				</title>
				<?php wp_admin_css('install', true); ?>
				<link
					rel="stylesheet"
					href="<?php echo esc_url(
     	str_replace(['http:', 'https:'], '', WC()->plugin_url()) . '/assets/css/auth.css'
     ); ?>"
					type="text/css"
				/>
			</head>
			<body class="wc-auth wp-core-ui">
				<h1 id="wc-logo">
					<img
						src="<?php echo esc_url(Login::get_logo_url()); ?>"
						alt="<?php echo esc_attr__('App logo', TEXTDOMAIN); ?>"
						style="max-height: 84px;"
					/>
				</h1>
				<div class="wc-auth-content">
		<?php
	}
}
