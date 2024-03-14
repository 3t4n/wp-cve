<?php

if (!defined('ABSPATH')) {
	exit;
}

require ABSPATH . 'includes/mypos-page-functions.php';
require ABSPATH . 'includes/mypos-formatting-functions.php';

/**
 * Define a constant if it is not already defined.
 *
 * @param string $name Constant name.
 * @param mixed $value Value.
 */
function mypos_maybe_define_constant($name, $value)
{
	if (!defined($name)) {
		define($name, $value);
	}
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @param string $template_name Template name.
 * @param array $args Arguments. (default: array).
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 */
function mypos_get_template($template_name, $args = array(), $template_path = '', $default_path = '')
{
	$cache_key = sanitize_key(implode('-', array('template', $template_name, $template_path, $default_path)));
	$template = (string)wp_cache_get($cache_key, 'mypos');

	if (!$template) {
		$template = mypos_locate_template($template_name, $template_path, $default_path);

		// Don't cache the absolute path so that it can be shared between web servers with different paths.
		$cache_path = mypos_tokenize_path($template, mypos_get_path_define_tokens());

		mypos_set_template_cache($cache_key, $cache_path);
	} else {
		// Make sure that the absolute path to the template is resolved.
		$template = mypos_untokenize_path($template, mypos_get_path_define_tokens());
	}

	$action_args = array(
		'template_name' => $template_name,
		'template_path' => $template_path,
		'located' => $template,
		'args' => $args,
	);

	if (!empty($args) && is_array($args)) {
		extract($args);
	}

	do_action(
		'mypos_before_template_part',
		$action_args['template_name'],
		$action_args['template_path'],
		$action_args['located'],
		$action_args['args']
	);

	include $action_args['located'];

	do_action(
		'mypos_after_template_part',
		$action_args['template_name'],
		$action_args['template_path'],
		$action_args['located'],
		$action_args['args']
	);
}


/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 * yourtheme/$template_path/$template_name
 * yourtheme/$template_name
 * $default_path/$template_name
 *
 * @param string $template_name Template name.
 * @param string $template_path Template path. (default: '').
 * @param string $default_path Default path. (default: '').
 * @return string
 */
function mypos_locate_template($template_name, $template_path = '', $default_path = '')
{
	if (!$template_path) {
		$template_path = MyPOS()->template_path();
	}

	if (!$default_path) {
		$default_path = MyPOS()->plugin_path() . '/templates/';
	}


	if (empty($template)) {
		$template = locate_template(
			array(
				trailingslashit($template_path) . $template_name,
				$template_name,
			)
		);
	}

	// Get default template/.
	if (!$template || WC_TEMPLATE_DEBUG_MODE) {
		if (empty($cs_template)) {
			$template = $default_path . $template_name;
		} else {
			$template = $default_path . $cs_template;
		}
	}

	return $template;
}

/**
 * Given a path, this will convert any of the subpaths into their corresponding tokens.
 *
 * @param string $path The absolute path to tokenize.
 * @param array $path_tokens An array keyed with the token, containing paths that should be replaced.
 * @return string The tokenized path.
 */
function mypos_tokenize_path($path, $path_tokens)
{
	// Order most to least specific so that the token can encompass as much of the path as possible.
	uasort(
		$path_tokens,
		function ($a, $b) {
			$a = strlen($a);
			$b = strlen($b);

			if ($a > $b) {
				return -1;
			}

			if ($b > $a) {
				return 1;
			}

			return 0;
		}
	);

	foreach ($path_tokens as $token => $token_path) {
		if (0 !== strpos($path, $token_path)) {
			continue;
		}

		$path = str_replace($token_path, '{{' . $token . '}}', $path);
	}

	return $path;
}

/**
 * Given a tokenized path, this will expand the tokens to their full path.
 *
 * @param string $path The absolute path to expand.
 * @param array $path_tokens An array keyed with the token, containing paths that should be expanded.
 * @return string The absolute path.
 */
function mypos_untokenize_path($path, $path_tokens)
{
	foreach ($path_tokens as $token => $token_path) {
		$path = str_replace('{{' . $token . '}}', $token_path, $path);
	}

	return $path;
}

/**
 * Fetches an array containing all of the configurable path constants to be used in tokenization.
 *
 * @return array The key is the define and the path is the constant.
 */
function mypos_get_path_define_tokens()
{
	$defines = array(
		'ABSPATH',
		'WP_CONTENT_DIR',
		'WP_PLUGIN_DIR',
		'WPMU_PLUGIN_DIR',
		'PLUGINDIR',
		'WP_THEME_DIR',
	);

	$path_tokens = array();
	foreach ($defines as $define) {
		if (defined($define)) {
			$path_tokens[$define] = constant($define);
		}
	}

	return apply_filters('mypos_get_path_define_tokens', $path_tokens);
}

/**
 * Add a template to the template cache.
 *
 * @param string $cache_key Object cache key.
 * @param string $template Located template.
 */
function mypos_set_template_cache($cache_key, $template)
{
	wp_cache_set($cache_key, $template, 'mypos');

	$cached_templates = wp_cache_get('cached_templates', 'mypos');
	if (is_array($cached_templates)) {
		$cached_templates[] = $cache_key;
	} else {
		$cached_templates = array($cache_key);
	}

	wp_cache_set('cached_templates', $cached_templates, 'mypos');
}
