<?php
namespace Layered\IfWidget;

class Admin {

	public static function start() {
		return new static;
	}

	protected function __construct() {
		add_action('admin_enqueue_scripts', [$this, 'assets']);
		add_action('admin_menu', [$this, 'menu']);
		add_action('admin_notices', [$this, 'notices']);
		add_filter('plugin_action_links_if-widget/if-widget.php', [$this, 'actionLinks']);
	}

	public function assets() {
		if (get_current_screen()->id === 'appearance_page_if-widget') {
			wp_enqueue_style('if-widget', plugins_url('assets/if-widget.css', dirname(__FILE__)), [], '0.1');
		}
	}

	public function menu() {
		add_submenu_page('themes.php', __('If Widget Options', 'if-widget'), __('If Widget', 'if-widget'), 'manage_options', 'if-widget', [$this, 'page']);
	}

	public function notices() {
		$notices = [];

		if (isset($_REQUEST['if-widget-alert'])) {
			$notices[] = [
				'type'			=>	isset($_REQUEST['alert-type']) ? $_REQUEST['alert-type'] : 'success',
				'message'		=>	$_REQUEST['if-widget-alert'],
				'dismissable'	=>	true
			];
		}

		foreach ($notices as $notice) {
			?>
			<div class="notice notice-<?php echo esc_attr($notice['type']) ?> <?php if (isset($notice['dismissable']) && $notice['dismissable'] === true) echo 'is-dismissible' ?>">
				<p><?php echo wp_kses($notice['message'], ['a' => ['href' => [], 'title' => []], 'strong' => []]) ?></p>
			</div>
			<?php
		}
	}

	public function actionLinks(array $links) {
		return array_merge([
			'settings'	=>	'<a href="' . menu_page_url('if-widget', false) . '">' . __('Settings', 'if-widget') . '</a>'
		], $links);
	}

	public function page() {
		$options = get_option('if-widget');
		?>

		<div class="wrap about-wrap if-widget-wrap">
			<a href="<?php echo admin_url('widgets.php') ?>" class="button button-secondary if-widget-float-right"><?php _e('Manage Widgets', 'if-widget') ?></a>
			<h1>If Widget</h1>
			<p class="about-text"><?php printf(__('Thanks for using the %s plugin! Now you can display tailored widgets to each visitor, based on visibility rules. Here are a few examples:', 'if-widget'), '<strong>If Widget</strong>') ?></p>
			<ul class="list">
				<li><?php _e('Display Logout link for logged-in users:', 'if-widget') ?> <code><?php _e('Show widget if', 'if-widget') ?> <span class="if-widget-color"><?php _e('User <u>is</u> logged in', 'if-widget') ?></span></code></li>
				<li><?php _e('Hide Login or Register widget for logged-in users:', 'if-widget') ?> <code><?php _e('Show widget if', 'if-widget') ?> <span class="if-widget-color"><?php _e('User <u>is not</u> logged in', 'if-widget') ?></span></code></li>
				<li><?php _e('Show a widget on phones:', 'if-widget') ?> <code><?php _e('Show widget if', 'if-widget') ?> <span class="if-widget-color"><?php _e('<u>Is</u> mobile device', 'if-widget') ?></span></code></li>
				<li><?php _e('Show widget for users in US and Canada:', 'if-widget') ?> <code><?php _e('Show widget if', 'if-widget') ?> <span class="if-widget-color"><?php _e('User <u>is</u> from country: <u>US, CA</u>', 'if-widget') ?></span></code></li>
				<li><?php _e('Show widget for visitors browsing in English or Spanish:', 'if-widget') ?> <code><?php _e('Show widget if', 'if-widget') ?> <span class="if-widget-color"><?php _e('Language <u>is</u>: English, Spanish', 'if-widget') ?></span></code></li>
				<li><?php _e('Show widget only on Contact page:', 'if-widget') ?> <code><?php _e('Show widget if', 'if-widget') ?> <span class="if-widget-color"><?php _e('URL <u>contains</u> <u>/contact</u>', 'if-widget') ?></span></code></li>
			</ul>
			<p><?php printf(__('Visibility rules can be added to widgets by activating the "%s" option when editing any widget.', 'if-widget'), '<strong>' . __('Show widget if', 'if-widget') . '</strong>') ?></p>

			<hr class="wp-header-end">

			<div class="feature-section two-col">
				<div class="col">
					<h3><?php _e('<strong>If Widget</strong> plugin', 'if-widget') ?></h3>
					
					<ul>
						<li>
							<?php _e('User visibility rules', 'if-widget') ?>
							<ul>
								<li><?php _e('Visitor is logged in or out', 'if-widget') ?></li>
								<li><?php _e('Is Admin, Editor, Author or Shop Manager', 'if-widget') ?></li>
								<li><?php _e('Is registration allowed', 'if-widget') ?></li>
							</ul>
						</li>
						<li>
							<?php _e('Page visibility rules', 'if-widget') ?>
							<ul>
								<li><?php _e('Page - is Front or Blog page', 'if-widget') ?></li>
								<li><?php _e('Page - is Archive page', 'if-widget') ?></li>
								<li><?php _e('Page - current URL starts with or matches "keyword"', 'if-widget') ?></li>
								<li><?php _e('Visitor device - detect mobile or desktop', 'if-widget') ?></li>
							</ul>
						</li>
						<li><?php _e('Support on WordPress forum', 'if-widget') ?></li>
					</ul>
				</div>

				<?php do_action('admin_more_visibility_rules') ?>
			</div>

			<br><hr>

			<p>
				<strong>If Widget</strong>:
				<a href="https://wordpress.org/plugins/if-widget/#faq" target="wpplugins"><?php _e('FAQs', 'if-widget') ?></a> &middot;
				<a href="https://wordpress.org/support/plugin/if-widget" target="wpplugins"><?php _e('Support forum', 'if-widget') ?></a> &middot;
				<span class="dashicons dashicons-star-filled" style="color: #ffb900"></span> <a href="https://wordpress.org/plugins/if-widget/#reviews" target="wpplugins"><?php _e('Leave a review', 'if-widget') ?></a>
			</p>
		</div>
		<?php
	}

}
