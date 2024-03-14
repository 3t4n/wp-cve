<?php
/**
 * Plugin Name:       TomS Image Slider
 * Description:       Simply Slider block.
 * Requires at least: 5.8
 * Requires PHP:      7.0
 * Version:           1.1.5
 * Author:            Tom Sneddon
 * Author URI:        https://toms-caprice.org
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       toms-image-slider
 * Domain Path:		  /languages
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TomSImageSlider{
    function __construct() {
        add_action( 'init', array($this, 'TomSImageSliderAdminAssets'));
        
        // 添加 TomS Blocks 分类到 Gutenberg
        $filter = version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ? 'block_categories_all' : 'block_categories';

        add_filter( $filter, array($this, 'TomSBlocksCategory'), 10, 2 );
    }
    function TomSBlocksCategory($block_categories, $editor_context){
        $checkTomSBlocks = wp_list_pluck( $block_categories, 'slug');

        if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' )&& in_array('tomsneddon', $checkTomSBlocks, true) === false ) {
            array_push(
                $block_categories,
                $TomSBlocks = array(
                    'slug'  => 'tomsneddon',
                    'title' => esc_html__( 'TomS Blocks', 'toms-image-slider' ),
                    'icon'  => null
                )
            );
        }elseif(in_array('tomsneddon', $checkTomSBlocks, true) === false){
            array_merge(
                $block_categories,
                [
                        [
                            'slug'  => 'tomsneddon',
                            'title' => esc_html__( 'TomS Blocks', 'toms-image-slider' ),
                            'icon'  => null
                        ],
                ]
            );
        }

        return $block_categories;

    }
    function TomSImageSliderAdminAssets() {

        load_plugin_textdomain( 'toms-image-slider', false, dirname(plugin_basename( __FILE__ )) . '/languages' );

        wp_register_script( 'toms-image-slider-js', plugin_dir_url( __FILE__ ) . 'build/index.js', array('wp-blocks', 'wp-element','wp-editor', 'wp-i18n', 'wp-components'));
        wp_register_style( 'toms-image-slider-style', plugin_dir_url( __FILE__ ) . 'build/index.css');
        
        wp_add_inline_script( 'toms-image-slider-js', 'var tomsSlieshowDefaultFromPHP = ' . json_encode( array(
            'defaultImage' => plugin_dir_url( __FILE__ ) . '/img/default.png',
        ) ), 'before' );

        wp_set_script_translations( 'toms-image-slider-js', 'toms-image-slider', plugin_dir_path( __FILE__ ) . '/languages' );

        register_block_type( 'tomsneddon-image-slider/toms-image-slider', array(
            'editor_script' => 'toms-image-slider-js',
            'editor_style' => 'toms-image-slider-style',
            'render_callback' => array($this, 'TomSImageSliderFrontendHTML')
        ) );
    }

    function TomSImageSliderFrontendHTML($attributes){
        wp_enqueue_script( 'toms-image-slider-frontendjs', plugin_dir_url( __FILE__ ) . 'build/frontend.js', array( 'wp-element' ,'wp-components'), false, true );
        wp_enqueue_style( 'toms-image-slider-frontendstyle', plugin_dir_url( __FILE__ ) . 'build/frontend.css');
        wp_add_inline_script( 'toms-image-slider-frontendjs', 'var tomsSlieshowDefaultFromPHP = ' . json_encode( array(
            'defaultImage' => plugin_dir_url( __FILE__ ) . '/img/default.png',
        ) ), 'before' );

        $html = '';

        ob_start(); ?>
            <div class="tomsneddon">
                <div id="toms-image-slider" class="toms-image-slider">
                    <pre style="display: none; opacity: 0;"><?php echo wp_json_encode($attributes); ?></pre>
                </div>
            </div>
       <?php /* return ob_get_clean();*/
       $html = ob_get_contents();
       ob_end_clean();

       return $html ;
    }
}

$TomSImageSlider = new TomSImageSlider();