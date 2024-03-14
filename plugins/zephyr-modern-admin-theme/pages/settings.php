<?php

if (!defined('ABSPATH')) die;

$themes = zat_get_themes();
$fonts = [
	'Roboto',
	'Open Sans',
	'Montserrat',
	'Lobster'
];
?>

<?php $settings = zat_get_settings(); ?>

<div id="zephyr-admin-theme-settings" class="zephyr-wrap">
	<h3 class="zat-page-title"><?php _e('Zephyr Admin Theme Settings', 'zephyr-admin-theme'); ?></h3>
	<form id="zat-settings-form" method="POST" class="zephyr-panel">
		<label class="zat-label"><?php echo _e('Themes', 'zephyr-admin-theme') ?></label>
		<div id="zat-theme-palettes">

			<?php foreach ($themes as $theme) : ?>
				<?php $gradient_css = "background: " . $theme['gradient_end'] . "; background: -moz-linear-gradient(-45deg, " . $theme['gradient_end'] . " 0%, " . $theme['gradient_start'] . " 100%); background: -webkit-linear-gradient(-60deg, " . $theme['gradient_start'] . " 0%," . $theme['gradient_end'] . " 100%); background: linear-gradient(135deg, " . $theme['gradient_end'] . " 0%," . $theme['gradient_start'] . " 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $theme['gradient_start'] . "', endColorstr='" . $theme['gradient_start'] . "',GradientType=1 );"; ?>
				<div class="zat-theme-palette" data-primary-color="<?php echo $theme['primary_color']; ?>" data-background-color="<?php echo $theme['background_color']; ?>" data-secondary-color="<?php echo $theme['secondary_color']; ?>" data-gradient-start="<?php echo $theme['gradient_start']; ?>" data-gradient-end="<?php echo $theme['gradient_end']; ?>" data-button-primary="<?php echo $theme['button_primary']; ?>" data-button-hover="<?php echo $theme['button_hover']; ?>" data-text-color="<?php echo $theme['text_color']; ?>">
					<div class="zat-theme-palette__preview" style="border-color: <?php echo $theme['button_primary']; ?>; background-color: <?php echo $theme['background_color']; ?>;">
						<div class="zat-theme-palette__sidebar" style="<?php echo $gradient_css; ?>"><span class="zat-theme-palette__item" style="background-color: <?php echo $theme['primary_color']; ?>"></span></div>
						<div class="zat-theme-palette__content">
							<span class="zat-theme-palette__button" style="border-color: <?php echo $theme['button_primary']; ?> !important;"></span>
						</div>
						<div class="zat-theme-palette__title"><?php echo isset($theme['title']) ? $theme['title'] : _e('Custom Theme', 'zephyr-admin-theme'); ?></div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<label class="zat-label zat-label-block"><?php _e('Primary Color', 'zephyr-admin-theme');  ?></label>
		<input name="zat-primary-color" id="zpm-primary-color" class="zat-color-picker" value="<?php esc_attr_e($settings['primary_color']); ?>" />

		<label class="zat-label zat-label-block"><?php _e('Secondary Color', 'zephyr-admin-theme');  ?></label>
		<input name="zat-secondary-color" id="zpm-secondary-color" class="zat-color-picker" value="<?php esc_attr_e($settings['secondary_color']); ?>" />

		<label class="zat-label zat-label-block"><?php _e('Main Background Color', 'zephyr-admin-theme');  ?></label>
		<input name="zat-background-color" id="zpm-background-color" class="zat-color-picker" value="<?php esc_attr_e($settings['background_color']); ?>" />

		<label class="zat-label zat-label-block"><?php _e('Gradient Start Color', 'zephyr-admin-theme');  ?></label>
		<input name="zat-gradient-start-color" id="zpm-gradient-start" class="zat-color-picker" value="<?php esc_attr_e($settings['gradient_start']); ?>" />

		<label class="zat-label zat-label-block"><?php _e('Gradient End Color', 'zephyr-admin-theme');  ?></label>
		<input name="zat-gradient-end-color" id="zpm-gradient-end" class="zat-color-picker" value="<?php esc_attr_e($settings['gradient_end']); ?>" />

		<label class="zat-label zat-label-block"><?php _e('Button Primary Color', 'zephyr-admin-theme');  ?></label>
		<input name="zat-primary-button-color" id="zpm-button-primary" class="zat-color-picker" value="<?php esc_attr_e($settings['button_primary']); ?>" />

		<label class="zat-label zat-label-block"><?php _e('Button Hover Color', 'zephyr-admin-theme');  ?></label>
		<input name="zat-primary-button-hover-color" id="zpm-button-hover" class="zat-color-picker" value="<?php esc_attr_e($settings['button_hover']); ?>" />

		<label class="zat-label zat-label-block"><?php _e('Menu Text Color', 'zephyr-admin-theme');  ?></label>
		<input name="zat-text-color" id="zat-text-color" class="zat-color-picker" value="<?php esc_attr_e($settings['text_color']); ?>" />

		<label class="zat-label zat-label-block"><?php _e('Select Font', 'zephyr-admin-theme');  ?></label>
		<select name="zat-font" id="zat-font-select">
			<?php foreach ($fonts as $font) : ?>
				<option value="<?php echo $font; ?>" <?php echo $font == $settings['font'] ? 'selected' : ''; ?>><?php echo $font; ?></option>
			<?php endforeach; ?>
		</select>

		<div class="zat-form-section">
			<label class="zat-label"><?php _e('Add Bottom Shadow to Admin Bar', 'zephyr-admin-theme');  ?></label>
			<input type="checkbox" class="zat-label-checkbox" name="zat-admin-bar-shadow" <?php echo $settings['admin_bar_shadow'] ? 'checked' : ''; ?> />
		</div>

		<div class="zat-form-section">
			<label class="zat-label zat-label-block"><?php _e('Theme Mode', 'zephyr-admin-theme');  ?></label>
			<input type="radio" id="zat-light-mode-switch" class="zat-label-checkbox" name="zat-theme-mode" <?php echo $settings['theme_mode'] == "light" ? 'checked' : ''; ?> value="light" />
			<label class="zat-label"><?php _e('Light', 'zephyr-admin-theme'); ?></label>

			<input type="radio" id="zat-dark-mode-switch" class="zat-label-checkbox" name="zat-theme-mode" <?php echo $settings['theme_mode'] == "dark" ? 'checked' : ''; ?> value="dark" />
			<label class="zat-label"><?php _e('Dark (Beta)', 'zephyr-admin-theme'); ?></label>
		</div>

		<div class="zat-form-section">
			<label class="zat-label"><?php _e('Hide Login Logo', 'zephyr-admin-theme');  ?></label>
			<input type="checkbox" class="zat-label-checkbox" name="zat-hide-login-logo" <?php echo $settings['hide_login_logo'] ? 'checked' : ''; ?> />
		</div>

		<div class="zat-form-section">
			<label class="zat-label zat-label-block"><?php _e('Custom Login Logo', 'zephyr-admin-theme');  ?></label>
			<span class="zat-login-logo-image" style="<?php echo $settings['login_logo'] !== '' ? 'background-image: url(' . $settings['login_logo'] . ');' : 'background-image: url(' . includes_url() . 'images/w-logo-blue.png' . ')'; ?>"></span>
			<a id="zat-login-logo-button" href="javascript:void(0);" class="button"><?php _e('Upload Logo', 'zephyr-admin-theme'); ?></a>
			<a id="zat-login-logo-reset-button" href="javascript:void(0);" class="button" data-reset-logo="<?php echo includes_url() . 'images/w-logo-blue.png'; ?>"><?php _e('Reset Logo', 'zephyr-admin-theme'); ?></a>
			<input type="hidden" name="zat-login-logo" id="zat-login-logo-value" value="<?php echo $settings['login_logo'] !== '' ? $settings['login_logo'] : ''; ?>" />
		</div>

		<div class="zat-form-section">
			<label class="zat-label zat-label-block"><?php _e('Custom Dashboard Logo', 'zephyr-admin-theme');  ?></label>
			<span class="zat-dashboard-logo-image" style="<?php echo $settings['dashboard_logo'] !== '' ? 'background-image: url(' . $settings['dashboard_logo'] . ');' : ''; ?>"></span>
			<a id="zat-dashboard-logo-button" href="javascript:void(0);" class="button"><?php _e('Upload Logo', 'zephyr-admin-theme'); ?></a>
			<a id="zat-dashboard-logo-reset-button" href="javascript:void(0);" class="button" data-reset-logo=""><?php _e('Reset Logo', 'zephyr-admin-theme'); ?></a>
			<input type="hidden" name="zat-dashboard-logo" id="zat-dashboard-logo-value" value="<?php echo $settings['dashboard_logo'] !== '' ? $settings['dashboard_logo'] : ''; ?>" />
		</div>

		<label class="zat-label zat-label-block"><?php _e('Custom Login Redirect Page', 'zephyr-admin-theme');  ?></label>

		<div id="zat-redirect-url-section">
			<span id="zat-redirect-prefix"><?php echo home_url() . '/'; ?></span>
			<input type="text" id="zat-redirect-suffix" name="zat-login-redirect" value="<?php echo $settings['login_redirect']; ?>" placeholder="<?php _e('Custom Login URL', 'zephyr-admin-theme') ?>" />
		</div>

		<input type="hidden" id="zat-theme-title" name="theme-title" value="<?php _e('Custom Theme', 'zephyr-admin-theme'); ?>">
		<button class="button zat-submit-button" name="zat-submit-settings"><?php _e('Save Settings', 'zephyr-admin-theme'); ?></button>
		<button class="button zat-submit-button" name="zat-save-custom-theme-template"><?php _e('Save Custom Theme Template', 'zephyr-admin-theme'); ?></button>
	</form>
</div>