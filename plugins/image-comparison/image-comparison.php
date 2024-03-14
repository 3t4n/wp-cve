<?php

/**
 * Plugin Name:     Image Comparison
 * Plugin URI:      https://essential-blocks.com
 * Description:     Let the visitors compare images & make your website interactive.
 * Version:         1.3.6
 * Author:          WPDeveloper
 * Author URI:      https://wpdeveloper.net
 * License:         GPL-3.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:     image-comparison
 *
 * @package         image-comparison
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/font-loader.php';
require_once __DIR__ . '/lib/style-handler/style-handler.php';

function create_block_image_comparison_block_init() {

    define( 'EB_IMAGE_COMPARISON_BLOCKS_VERSION', "1.3.6" );
    define( 'EB_IMAGE_COMPARISON_BLOCKS_ADMIN_URL', plugin_dir_url( __FILE__ ) );
    define( 'EB_IMAGE_COMPARISON_BLOCKS_ADMIN_PATH', dirname( __FILE__ ) );

    $script_asset_path = EB_IMAGE_COMPARISON_BLOCKS_ADMIN_PATH . "/dist/index.asset.php";
    if ( ! file_exists( $script_asset_path ) ) {
        throw new Error(
            'You need to run `npm start` or `npm run build` for the "image-comparison/image-comparison" block first.'
        );
    }
    $index_js         = EB_IMAGE_COMPARISON_BLOCKS_ADMIN_URL . 'dist/index.js';
    $script_asset     = require $script_asset_path;
    $all_dependencies = array_merge( $script_asset['dependencies'], [
        'wp-blocks',
        'wp-i18n',
        'wp-element',
        'wp-block-editor',
				'lodash',
        'eb-image-comparison-blocks-controls-util',
        'essential-blocks-eb-animation'
    ] );

    wp_register_script(
        'image-comparison-block-editor-js',
        $index_js,
        $all_dependencies,
        $script_asset['version']
    );

    $load_animation_js = EB_IMAGE_COMPARISON_BLOCKS_ADMIN_URL . 'assets/js/eb-animation-load.js';
    wp_register_script(
        'essential-blocks-eb-animation',
        $load_animation_js,
        [],
        EB_IMAGE_COMPARISON_BLOCKS_VERSION,
        true
    );

    $animate_css = EB_IMAGE_COMPARISON_BLOCKS_ADMIN_URL . 'assets/css/animate.min.css';
    wp_register_style(
        'essential-blocks-animation',
        $animate_css,
        [],
        EB_IMAGE_COMPARISON_BLOCKS_VERSION
    );

    $frontend_js_path = include_once dirname( __FILE__ ) . "/dist/frontend/index.asset.php";
    $frontend_js      = "dist/frontend/index.js";
    wp_register_script(
        'eb-image-comparison-frontend',
        plugins_url( $frontend_js, __FILE__ ),
        ['wp-element', 'essential-blocks-eb-animation'],
        $frontend_js_path['version'],
        true
    );

    if ( ! WP_Block_Type_Registry::get_instance()->is_registered( 'essential-blocks/image-comparison' ) ) {
        register_block_type(
            Image_Comparison_Helper::get_block_register_path( 'image-comparison/image-comparison', EB_IMAGE_COMPARISON_BLOCKS_ADMIN_PATH ),
            [
                'editor_script'   => 'image-comparison-block-editor-js',
                'render_callback' => function ( $attributes, $content ) {
                    if ( ! is_admin() ) {
                        wp_enqueue_style( 'essential-blocks-animation' );
                        wp_enqueue_script( 'eb-image-comparison-frontend' );
                    }
                    return $content;
                }
            ],
        );
    }
}

add_action( 'init', 'create_block_image_comparison_block_init' );
