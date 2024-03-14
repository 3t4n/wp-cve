<?php

/**
 * Plugin Name:     Button Group
 * Description:     Create Two Buttons To Be Stacked Together
 * Version:         1.2.5
 * Author:          WPDeveloper
 * Author URI:      https://wpdeveloper.net
 * License:         GPL-3.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:     button-group
 *
 * @package         button-group
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */

require_once __DIR__ . '/includes/font-loader.php';
require_once __DIR__ . '/includes/post-meta.php';
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/lib/style-handler/style-handler.php';

function create_block_button_group_block_init() {
    define( 'BUTTONGROUP_BLOCK_VERSION', "1.2.5" );
    define( 'BUTTONGROUP_BLOCK_ADMIN_URL', plugin_dir_url( __FILE__ ) );
    define( 'BUTTONGROUP_BLOCK_ADMIN_PATH', dirname( __FILE__ ) );

    $script_asset_path = BUTTONGROUP_BLOCK_ADMIN_PATH . "/dist/index.asset.php";
    if ( ! file_exists( $script_asset_path ) ) {
        throw new Error(
            'You need to run `npm start` or `npm run build` for the "block/testimonial" block first.'
        );
    }
    $index_js         = BUTTONGROUP_BLOCK_ADMIN_URL . 'dist/index.js';
    $script_asset     = require $script_asset_path;
    $all_dependencies = array_merge( $script_asset['dependencies'], [
        'wp-blocks',
        'wp-i18n',
        'wp-element',
        'wp-block-editor',
        'buttongroup-block-controls-util',
        'essential-blocks-eb-animation'
    ] );

    wp_register_script(
        'create-block-buttongroup-block-editor-script',
        $index_js,
        $all_dependencies,
        $script_asset['version'],
        true
    );

    $load_animation_js = BUTTONGROUP_BLOCK_ADMIN_URL . 'assets/js/eb-animation-load.js';
    wp_register_script(
        'essential-blocks-eb-animation',
        $load_animation_js,
        [],
        BUTTONGROUP_BLOCK_VERSION,
        true
    );

    $animate_css = BUTTONGROUP_BLOCK_ADMIN_URL . 'assets/css/animate.min.css';
    wp_register_style(
        'essential-blocks-animation',
        $animate_css,
        [],
        BUTTONGROUP_BLOCK_VERSION
    );

    $fontawesome_css = BUTTONGROUP_BLOCK_ADMIN_URL . 'lib/resources/css/font-awesome5.css';
    wp_register_style(
        'fontawesome-frontend-css',
        $fontawesome_css,
        [],
        BUTTONGROUP_BLOCK_VERSION
    );

    wp_register_style(
        'fontpicker-default-theme',
        BUTTONGROUP_BLOCK_ADMIN_URL . 'lib/resources/css/fonticonpicker.base-theme.react.css',
        [],
        BUTTONGROUP_BLOCK_VERSION,
        'all'
    );

    wp_register_style(
        'fontpicker-material-theme',
        BUTTONGROUP_BLOCK_ADMIN_URL . 'lib/resources/css/fonticonpicker.material-theme.react.css',
        [],
        BUTTONGROUP_BLOCK_VERSION,
        'all'
    );

    $style_css = BUTTONGROUP_BLOCK_ADMIN_URL . 'dist/style.css';
    //Editor Style
    wp_register_style(
        'create-block-buttongroup-block-editor-style',
        $style_css,
        [
            'fontawesome-frontend-css',
            'fontpicker-default-theme',
            'fontpicker-material-theme',
            'essential-blocks-animation'
        ],
        BUTTONGROUP_BLOCK_VERSION
    );
    //Frontend Style
    wp_register_style(
        'create-block-buttongroup-block-frontend-style',
        $style_css,
        ['fontawesome-frontend-css', 'essential-blocks-animation'],
        BUTTONGROUP_BLOCK_VERSION
    );

    if ( ! WP_Block_Type_Registry::get_instance()->is_registered( 'essential-blocks/button-group' ) ) {
        register_block_type(
            Button_Group_Helper::get_block_register_path( "button-group/button-group", BUTTONGROUP_BLOCK_ADMIN_PATH ),
            [
                'editor_script'   => 'create-block-buttongroup-block-editor-script',
                'editor_style'    => 'create-block-buttongroup-block-editor-style',
                'render_callback' => function ( $attributes, $content ) {
                    if ( ! is_admin() ) {
                        wp_enqueue_style( 'create-block-buttongroup-block-frontend-style' );
                        wp_enqueue_script( 'essential-blocks-eb-animation' );
                    }
                    return $content;
                }
            ]
        );
    }
}
add_action( 'init', 'create_block_button_group_block_init' );
