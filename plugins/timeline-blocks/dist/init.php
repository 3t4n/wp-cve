<?php

/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package Timeline Blocks for Gutenberg
 */
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

//PHP version compare
if (!version_compare(PHP_VERSION, '5.6', '>=')) {
    add_action('admin_notices', 'tb_fail_php_version');
} else {
    require_once TB_DIR . "src/tb-helper/class-tb-loader.php";
}

/**
 * PHP version fail error
 *
 * @since 1.0.0
 * @package Post Layouts for Gutenberg
 */
function tb_fail_php_version() {
    /* translators: %s: PHP version */
    $message = sprintf(esc_html__('Timeline Block for Gutenberg requires PHP version %s+, plugin is currently NOT RUNNING.', PL_DOMAIN), '5.6');
    $html_message = sprintf('<div class="error">%s</div>', wpautop($message));
    echo wp_kses_post($html_message);
}

/**
 * Enqueue assets for frontend and backend
 *
 * @since   1.0.0
 * @package Timeline Blocks for Gutenberg
 */
function timeline_block_assets() {

    // Load the compiled styles
    wp_enqueue_style('tb-block-style-css', plugins_url('dist/blocks.style.build.css', dirname(__FILE__)), 
        array(), filemtime(plugin_dir_path(__FILE__) . 'blocks.style.build.css'));

    // Load the FontAwesome icon library
    wp_enqueue_style('tb-block-fontawesome', plugins_url('dist/assets/fontawesome/css/all.css', dirname(__FILE__)), array(), filemtime(plugin_dir_path(__FILE__) . 'assets/fontawesome/css/all.css'));
}
add_action('enqueue_block_assets', 'timeline_block_assets');

/**
 * Enqueue assets for backend editor
 *
 * @since 1.0.0
 * @package Timeline Blocks for Gutenberg
 */
function timeline_block_editor_assets() {

    // Load the compiled blocks into the editor
    wp_enqueue_script('tb-block-js', plugins_url('/dist/blocks.build.js', dirname(__FILE__)), 
        array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor', 'wp-api' ), 
        filemtime(plugin_dir_path(__FILE__) . 'blocks.build.js'));

    // Load the compiled styles into the editor
    wp_enqueue_style('tb-block-editor-css', plugins_url('dist/blocks.editor.build.css', dirname(__FILE__)), array(), filemtime(plugin_dir_path(__FILE__) . 'blocks.editor.build.css'));

       if (function_exists('wp_set_script_translations')) {
             wp_add_inline_script(
                'timeline-blocks', sprintf(
                'var timeline_blocks = { localeData: %s };', json_encode(wp_set_script_translations('timeline_blocks', TB_DOMAIN))
                ), 'before'
             );
       } elseif (function_exists('gutenberg_set_script_translations')) {
             wp_add_inline_script(
                'timeline-blocks', sprintf(
                'var timeline_blocks = { localeData: %s };', json_encode(gutenberg_set_script_translations('timeline_blocks', TB_DOMAIN))
                ), 'before'
             );
            }
}
add_action('enqueue_block_editor_assets', 'timeline_block_editor_assets');

// Add custom block category
add_filter('block_categories_all', function( $categories, $post ) {
    return array_merge(
        $categories, array(
            array(
                'slug' => 'timeline-blocks',
                'title' => __('Timeline Blocks by Techeshta', TB_DOMAIN),
            ),
        )
    );
}, 10, 2);
