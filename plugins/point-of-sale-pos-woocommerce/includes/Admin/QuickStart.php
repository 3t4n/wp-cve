<?php

namespace ZPOS\Admin;

use ZPOS\Admin\Tabs\Connection;
use const ZPOS\PLUGIN_ROOT_FILE;
use const ZPOS\PLUGIN_VERSION;

class QuickStart
{
	public function __construct()
	{
		global $pagenow;

		$is_pos_page =
			'edit.php' === $pagenow &&
			isset($_GET['post_type']) &&
			'pos-station' === sanitize_text_field(wp_unslash($_GET['post_type']));
		$is_plugins_page = 'plugins.php' === $pagenow;
		$is_cloud_connected = Connection::is_cloud_connected();
		$is_ui_active = Connection::is_ui_active();

		if (($is_pos_page || $is_plugins_page) && !$is_cloud_connected && !$is_ui_active) {
			add_action('admin_notices', [$this, 'render']);
			add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
		}
	}

	public function enqueue_assets()
	{
		wp_enqueue_style(
			'zpos_quick_start',
			plugins_url('assets/quick-start/quick-start.css', PLUGIN_ROOT_FILE),
			[],
			PLUGIN_VERSION
		);

		wp_enqueue_script(
			'zpos_sso_handler',
			plugins_url('assets/quick-start/window-handler.js', PLUGIN_ROOT_FILE),
			[],
			PLUGIN_VERSION
		);
	}

	public function render()
	{
		?>
		<div class="zpos-qnotice notice is-dismissible">
			<div class="zpos-qnotice__header">
				<span class="dashicons dashicons-warning"></span>
				<h4>
					<?php echo esc_html__(
     	'Setup is almost complete, but one last step is still required.',
     	'zpos-wp-api'
     ); ?>
				</h4>
			</div>
			<div class="zpos-qnotice__body">
				<div class="zpos-qnotice__img">
					<img
						src="<?php echo plugins_url('assets/quick-start/logo.png', PLUGIN_ROOT_FILE); ?>"
						alt="<?php echo esc_html__('Quick start logo'); ?>"
					>
					<img
						src="<?php echo plugins_url('assets/quick-start/hero.png', PLUGIN_ROOT_FILE); ?>"
						alt="<?php echo esc_html__('Quick start logo'); ?>"
					>
				</div>
				<div class="zpos-qnotice__content">
					<h5>
						<?php echo esc_html__('Meet Jovvie, Your New Point Of Sale', 'zpos-wp-api'); ?>
					</h5>
					<p>
						<?php echo esc_html__(
      	'Jovvie POS was built to feel like a natural extension of WooCommerce and WordPress.',
      	'zpos-wp-api'
      ); ?>
					</p>
					<p>
						<?php echo esc_html__(
      	'By connecting your store, your products, customers, orders, inventory and settings will import automatically and communicate in real time to always stay up to date.',
      	'zpos-wp-api'
      ); ?>
					</p>

        <p>
						<?php echo esc_html__(
      	'Finish setup now. In less than 5 minutes, Jovvie will be ready to handle all aspects of your store’s in-person transactions.',
      	'zpos-wp-api'
      ); ?>
					</p>
					
					<p class="zpos-qnotice__btns">
							<button id="ssoRegisterBtn" class="zpos-qnotice__btn primary" type="button">
								<?php echo esc_html__('New? Claim Your Free Account ⟶', 'zpos-wp-api'); ?>
							</button>

							<button id="ssoLoginBtn" class="zpos-qnotice__btn secondary" type="button">
								<?php echo esc_html__('Connect Your Existing Account', 'zpos-wp-api'); ?>
							</button>

							<a class="zpos-qnotice__link-connect" href="<?php echo esc_url(
       	get_admin_url(null, 'edit.php?post_type=pos-station&page=pos#/connection')
       ); ?>">
								<?php echo esc_html__('Setup self-hosted connection', 'zpos-wp-api'); ?>
							</a>
					</p>
				</div>
			</div>
		</div>
		<?php
	}
}
