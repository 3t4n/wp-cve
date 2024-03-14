<?php
declare(strict_types=1);
/**
 *
 * @link              https://saschapaukner.de
 * @since             1.0.0
 * @package           Responsive_Block_Control
 *
 * @wordpress-plugin
 * Plugin Name:       Responsive Block Control
 * Description:       Responsive Block Control adds responsive toggles to a "Visibility" panel of the block editor to hide blocks according to screen width.
 * Version:           1.2.9
 * Author:            Sascha Paukner
 * Author URI:        https://saschapaukner.de
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       responsive-block-control
 * Domain Path:       /languages
 **/

namespace ResponsiveBlockControl;

// Exit if accessed directly.
use WP_Block_Type_Registry;

if (!defined('ABSPATH')) {
	exit;
}

function activate()
{
	$options = [];

	$breakPoints = [];
	$breakPoints['base'] = 0;
	$breakPoints['mobile'] = 320;
	$breakPoints['tablet'] = 740;
	$breakPoints['desktop'] = 980;
	$breakPoints['wide'] = 1480;

	$options['breakPoints'] = $breakPoints;
	$options['addCssToHead'] = true;

	add_option('responsiveBlockControl', $options);
}

function deactivate()
{
	delete_option('responsiveBlockControl');
}

/**
 * Loading ResponsiveBlockControl
 *
 * @return void
 */
function init()
{
	// initiate instance
	$ResponsiveBlockControl = new ResponsiveBlockControl();
	// call the register function
	$ResponsiveBlockControl->register();
}

register_activation_hook(__FILE__, 'ResponsiveBlockControl\activate');
register_deactivation_hook(__FILE__, 'ResponsiveBlockControl\deactivate');
add_action('plugins_loaded', 'ResponsiveBlockControl\init');

class ResponsiveBlockControl
{

	protected $plugin_name = 'responsive-block-control';

	protected $domain = 'responsive-block-control';

	protected $version = '1.2.9';

	/**
	 * Registers our plugin with WordPress.
	 */
	public static function register()
	{
		$plugin = new self();

		// Actions
		add_action('plugins_loaded', [$plugin, 'load_textdomain']);
		add_action('wp_enqueue_scripts', [$plugin, 'load_frontend_assets']);
		add_action('enqueue_block_assets', [$plugin, 'load_gutenberg_assets']);
		add_filter('render_block', [$plugin, 'addClasses'], 10, 2);
		add_action('wp_loaded', [$plugin, 'add_attributes_to_registered_blocks'], 999);
		add_filter('rest_pre_dispatch', [$plugin, 'conditionally_remove_attributes'], 10, 3);
	}

	public function load_textdomain()
	{
		load_plugin_textdomain(
			$this->domain,
			false,
			dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
		);
	}

	public function load_frontend_assets()
	{
		// js
		wp_enqueue_script(
			$this->plugin_name,
			plugin_dir_url(__FILE__) . 'build/js/responsive-block-control-public.js',
			['jquery'],
			$this->version,
			false
		);

		$options = get_option('responsiveBlockControl');

		// add fallback in case no options are set
		if (!$options) {
			$options = [];

			$breakPoints = [];
			$breakPoints['base'] = 0;
			$breakPoints['mobile'] = 320;
			$breakPoints['tablet'] = 740;
			$breakPoints['desktop'] = 980;
			$breakPoints['wide'] = 1480;

			$options['breakPoints'] = $breakPoints;
			$options['addCssToHead'] = true;
		}

		$new_options['breakPoints'] = apply_filters('responsive_block_control_breakpoints', $options['breakPoints']);
		$new_options['addCssToHead'] = apply_filters('responsive_block_control_addcss', $options['addCssToHead']);
		$options = array_replace_recursive($options, $new_options);

		wp_localize_script($this->plugin_name, 'responsiveBlockControlOptions', $options);
	}

	public function load_gutenberg_assets()
	{
		if (is_admin()) {
			wp_enqueue_script(
				$this->plugin_name . '-gutenberg',
				plugin_dir_url(__FILE__) . 'build/js/responsive-block-control-gutenberg.js',
				[
					'wp-blocks',
					'wp-element',
					'wp-editor',
					'wp-i18n',
				],
				$this->version,
				false
			);

			wp_enqueue_style(
				$this->plugin_name . '-gutenberg',
				plugin_dir_url(__FILE__) . 'build/css/responsive-block-control-gutenberg.css',
			);
		}
	}

	// adds the classes based on $block['attrs']['responsiveBlockControl']
	public function addClasses($block_content, $block)
	{
		if (!isset($block_content)) {
			return null;
		}

		// trim the content as there seems to be whitespace
		// needed for preg_replace() further down
		$block_content = trim($block_content);

		// do nothing if we dont have any values
		if (!isset($block['attrs']['responsiveBlockControl'])) {
			return $block_content;
		}

		$responsiveBlockControl = $block['attrs']['responsiveBlockControl'];

		//
		// add classes
		//
		$pattern = '/(^<[a-z]+[\d]*\s*)([^>]*)(class=")(.*?)(")([^>]*)(>)/';
		preg_match($pattern, $block_content, $matches);
		$existingClasses = empty($matches) ? '' : $matches[4];

		// if no classes are present get <tag and closing >
		if (empty($existingClasses)) {
			$pattern = '/(^<[a-z]+[\d]*\s*)([^>]*)(>)/';
			preg_match($pattern, $block_content, $matches);
		}

		$classes = $existingClasses;
		foreach ($responsiveBlockControl as $breakpoint => $value) {
			if ($value === true) {
				$classes .= ' rbc-is-hidden-on-' . $breakpoint . ' ';
			}
		}

		// filter out duplicate classes
		$classes = implode(' ', array_unique(explode(' ', $classes)));

		// define replacement
		$replacement = '$1$2$3' . trim($classes) . '$5$6$7';

		// if no classes are present the replacement needs to be different
		if (empty($existingClasses)) {
			$replacement = '$1 class="' . trim($classes) . '" $2$3';
		}

		// actually replace in string
		$block_content = preg_replace($pattern, $replacement, $block_content);

		$content = $block_content;

		return $content;
	}

	/**
	 * This is needed to resolve an issue with blocks that use the
	 * ServerSideRender component. Registering the attributes only in js
	 * can cause an error message to appear. Registering the attributes in
	 * PHP as well, seems to resolve the issue. Ideally, this bug will be
	 * fixed in the future.
	 *
	 * Reference: https://github.com/WordPress/gutenberg/issues/16850
	 *
	 * @since 1.0.1
	 */
	function add_attributes_to_registered_blocks()
	{

		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		foreach ($registered_blocks as $name => $block) {
			$block->attributes['responsiveBlockControl'] = ['type' => 'object'];
		}
	}

	/**
	 * Fix REST API issue with blocks rendered server-side. Without this,
	 * server-side blocks will not load in the block editor when visibility
	 * controls have been added.
	 *
	 * Reference: https://github.com/phpbits/block-options/blob/f741344033a2c9455828d039881616f77ef109fe/includes/class-editorskit-post-meta.php#L82-L112
	 *
	 * @param mixed $result Response to replace the requested version with.
	 * @param object $server Server instance.
	 * @param object $request Request used to generate the response.
	 *
	 * @return array Returns updated results.
	 * @since 1.0.1
	 *
	 */
	function conditionally_remove_attributes($result, $server, $request)
	{ // phpcs:ignore

		if (strpos($request->get_route(), '/wp/v2/block-renderer') !== false) {

			if (isset($request['attributes']) && isset($request['attributes']['responsiveBlockControl'])) {

				$attributes = $request['attributes'];
				unset($attributes['responsiveBlockControl']);
				$request['attributes'] = $attributes;
			}
		}

		return $result;
	}
}
