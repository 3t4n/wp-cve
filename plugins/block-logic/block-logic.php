<?php
/**
 * Plugin Name: Block Logic
 * Description: Adds a conditional block attribute Block Logic to control block display to the "Advanced" control panel
 * Author: Sascha Paukner
 * Author URI: https://saschapaukner.de
 * Version: 1.0.8
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: block-logic
 *
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
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
function block_logic_add_attributes_to_registered_blocks()
{

	$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

	foreach ($registered_blocks as $name => $block) {
		$block->attributes['blockLogic'] = ['type' => 'string'];
	}
}

add_action('wp_loaded', 'block_logic_add_attributes_to_registered_blocks', 999);

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
function block_logic_conditionally_remove_attributes($result, $server, $request)
{ // phpcs:ignore

	if (strpos($request->get_route(), '/wp/v2/block-renderer') !== false) {

		if (isset($request['attributes']) && isset($request['attributes']['blockLogic'])) {

			$attributes = $request['attributes'];
			unset($attributes['blockLogic']);
			$request['attributes'] = $attributes;
		}
	}

	return $result;
}

add_filter('rest_pre_dispatch', 'block_logic_conditionally_remove_attributes', 10, 3);


// set up translation of scripts
function block_logic_set_script_translations()
{
	wp_set_script_translations('block-logic', 'block-logic');
}

add_action('init', 'block_logic_set_script_translations');

function block_logic_enqueue_block_editor_assets()
{
	if (is_admin()) {
		// Enqueue our script
		wp_enqueue_script(
			'block-logic',
			esc_url(plugins_url('/build/js/block-logic.js', __FILE__)),
			['wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor'],
			'1.0.8',
			false // Do not enqueue the script in the footer.
		);

		wp_enqueue_style(
			'block-logic',
			esc_url(plugins_url('/build/css/block-logic.css', __FILE__)),
		);
	}
}

// had to change action because of core bug
// see: https://github.com/WordPress/gutenberg/issues/9757#issuecomment-486088850
add_action('enqueue_block_assets', 'block_logic_enqueue_block_editor_assets');
//add_action( 'init', 'block_logic_enqueue_block_editor_assets', 9999 );

function block_logic_check_logic($logic)
{
	$logic = stripslashes(trim($logic));
	$logic = apply_filters('block_logic_eval_override', $logic);

	if (is_bool($logic)) {
		return $logic;
	}

	if ($logic === '') {
		return true;
	}

	if (stristr($logic, 'return') === false) {
		$logic = 'return (' . html_entity_decode($logic, ENT_COMPAT | ENT_HTML401 | ENT_QUOTES) . ');';
	}

	try {
		// Ignore phpcs since warning is available as description on this feature.
		$show_block = eval($logic); // phpcs:ignore
	} catch (Error $e) {
		trigger_error($e->getMessage(), E_USER_WARNING);

		$show_block = true;
	}

	return $show_block;
}

function block_logic_render($block_content, $block)
{
	if (!isset($block['attrs']['blockLogic'])) {
		return $block_content;
	}

	$show_block = block_logic_check_logic($block['attrs']['blockLogic']);

	if ($show_block) {
		return $block_content;
	} else {
		return '';
	}
}

add_filter('render_block', 'block_logic_render', 10, 2);
