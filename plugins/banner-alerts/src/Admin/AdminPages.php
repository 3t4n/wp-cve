<?php
namespace Plugin\BannerAlerts;

class AdminPages
{
	private $pluginPath;

	private $defaultDisplayOptions = array(
		'display-title' => '1',
		'display-readmore' => '0',
		'display-mode' => '1',
		'display-dismiss' => '1',
		'display-styles' => ".banner-alerts {\n    max-width: 1100px; margin: 0 auto; padding: 5px;\n}\n",
		'display-speed' => '400',
		'use-slider' => '1',
	);

	public function __construct($pluginPath) {
		$this->pluginPath = $pluginPath;

		add_action('admin_menu', array($this, 'add_settings_menu'));
		add_action('admin_init', array($this, 'register_settings'));
	}

	function add_settings_menu() {
		add_submenu_page('options-general.php', 'Banner Alerts', 'Banner Alerts', 'manage_options', 'banner-alerts', array($this, 'render_settings'));
	}

	function register_settings() {
		register_setting('options-general_banner-alerts_display', 'options-general_banner-alerts_display');
		add_filter('plugin_action_links_' . plugin_basename($this->pluginPath), array($this, 'add_settings_link'));
	}

	function add_settings_link($links) {
		$new_links[] = '<a href="' . admin_url('/options-general.php?page=banner-alerts') . '">' . __('Settings') . '</a>';
		return array_merge($new_links, $links);
	}

	function render_settings() {
		?>
		<div class="wrap">
		<h1><?php echo __('Banner Alerts', 'banner-alerts'); ?></h1>

		<form method="post" action="options.php">
			<?php
			settings_fields('options-general_banner-alerts_display');
			do_settings_sections('options-general_banner-alerts_display');

			$displayOptions = get_option('options-general_banner-alerts_display', array());
			$displayOptions = wp_parse_args($displayOptions, $this->defaultDisplayOptions);
			?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row"><?php echo __('Display title?', 'banner-alerts'); ?></th>
						<td>
							<label>
								<?php echo __('Yes', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-title]" value="1" <?php echo ($displayOptions['display-title'] == "1") ? 'checked="checked"' : ''; ?> />
							</label>
							<label>
								<?php echo __('No', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-title]" value="0" <?php echo ($displayOptions['display-title'] == "0") ? 'checked="checked"' : ''; ?> />
							</label>
							<label>
								<?php echo __('Link', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-title]" value="2" <?php echo ($displayOptions['display-title'] == "2") ? 'checked="checked"' : ''; ?> />
							</label>
							<br/>
							<span class="description"><?php echo __('Display the title of alert in the banner alert popup?', 'banner-alerts'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo __('Display read more link?', 'banner-alerts'); ?></th>
						<td>
							<label>
								<?php echo __('Yes', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-readmore]" value="1" <?php echo ($displayOptions['display-readmore'] == "1") ? 'checked="checked"' : ''; ?> />
							</label>
							<label>
								<?php echo __('No', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-readmore]" value="0" <?php echo ($displayOptions['display-readmore'] == "0") ? 'checked="checked"' : ''; ?> />
							</label>
							<br/>
							<span class="description"><?php echo __('Display read more link in the banner alert popup?', 'banner-alerts'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo __('Display dismiss link?', 'banner-alerts'); ?></th>
						<td>
							<label>
								<?php echo __('Yes', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-dismiss]" value="1" <?php echo ($displayOptions['display-dismiss'] == "1") ? 'checked="checked"' : ''; ?> />
							</label>
							<label>
								<?php echo __('No', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-dismiss]" value="0" <?php echo ($displayOptions['display-dismiss'] == "0") ? 'checked="checked"' : ''; ?> />
							</label>
							<br/>
							<span class="description"><?php echo __('Allow alerts to be dismissed?', 'banner-alerts'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo __('Display content', 'banner-alerts'); ?></th>
						<td>
							<label>
								<?php echo __('None', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-mode]" value="0" <?php echo ($displayOptions['display-mode'] == "0") ? 'checked="checked"' : ''; ?> />
							</label>
							<label>
								<?php echo __('Full Message', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-mode]" value="1" <?php echo ($displayOptions['display-mode'] == "1") ? 'checked="checked"' : ''; ?> />
							</label>
							<label>
								<?php echo __('Excerpt', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-mode]" value="2" <?php echo ($displayOptions['display-mode'] == "2") ? 'checked="checked"' : ''; ?> />
							</label>
							<br/>
							<span class="description"><?php echo __('Choose the display setting for the alert message in the banner alert popup', 'banner-alerts'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo __('Open/Close Speed', 'banner-alerts'); ?></th>
						<td>
							<label>
								<?php echo __('Default', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-speed]" value="400" <?php echo ($displayOptions['display-speed'] == "400") ? 'checked="checked"' : ''; ?> />
							</label>
							<label>
								<?php echo __('Faster', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-speed]" value="200" <?php echo ($displayOptions['display-speed'] == "200") ? 'checked="checked"' : ''; ?> />
							</label>
							<label>
								<?php echo __('Slower', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-speed]" value="600" <?php echo ($displayOptions['display-speed'] == "600") ? 'checked="checked"' : ''; ?> />
							</label>
							<label>
								<?php echo __('Immediate', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[display-speed]" value="0" <?php echo ($displayOptions['display-speed'] == "0") ? 'checked="checked"' : ''; ?> />
							</label>
							<br/>
							<span class="description"><?php echo __('Choose the speed in which the alert is opened and closed', 'banner-alerts'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo __('Use Slider', 'banner-alerts'); ?></th>
						<td>
							<label>
								<?php echo __('Yes', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[use-slider]" value="1" <?php echo ($displayOptions['use-slider'] == "1") ? 'checked="checked"' : ''; ?> />
							</label>
							<label>
								<?php echo __('No', 'banner-alerts'); ?>
								<input type="radio" name="options-general_banner-alerts_display[use-slider]" value="0" <?php echo ($displayOptions['use-slider'] == "0") ? 'checked="checked"' : ''; ?> />
							</label>
							<br/>
							<span class="description"><?php echo __('Display alerts in a slider when multiple alerts are active?', 'banner-alerts'); ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo __('Display styles', 'banner-alerts'); ?></th>
						<td>
							<p><textarea name="options-general_banner-alerts_display[display-styles]" rows="10" cols="50" class="large-text code"><?php echo esc_attr($displayOptions['display-styles']); ?></textarea></p>
						</td>
					</tr>
				</tbody>
			</table>
			<?php submit_button(); ?>
		</form>
		</div>
		<?php
	}
}
