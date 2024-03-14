<?php
/*
Plugin Name: If Widget - Visibility control for Widgets
Plugin URI: https://layered.market/plugins/if-widget
Description: Control what widgets your site’s visitors see, with custom visibility rules
Version: 0.5
Text Domain: if-widget
Author: Layered
Author URI: https://layered.market
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
*/

require plugin_dir_path(__FILE__) . 'vendor/autoload.php';


// Prepare visibility rules (common in all IF plugins)

if (!function_exists('ifVisibilityRulesPrepare')) {
	function ifVisibilityRulesPrepare(array $rules) {
		return array_map(function(array $rule) {

			if (!isset($rule['type'])) {
				$rule['type'] = 'bool';
			}

			if (!isset($rule['group'])) {
				$rule['group'] = __('Other', 'if-widget');
			}

			return $rule;
		}, $rules);
	}
}


// start the plugin

add_filter('if_visibility_rules', 'ifVisibilityRulesPrepare', 500);
add_filter('if_visibility_rules', '\Layered\IfWidget\VisibilityRules::rules');

add_action('plugins_loaded', '\Layered\IfWidget\WidgetVisibility::start');
add_action('plugins_loaded', '\Layered\IfWidget\Admin::start');
add_action('plugins_loaded', '\Layered\IfWidget\Addon::start');
