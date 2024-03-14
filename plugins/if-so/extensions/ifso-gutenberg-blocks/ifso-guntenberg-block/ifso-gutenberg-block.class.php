<?php
/**
 * This block provides the basic if-so functionality (rendering triggers) in a gutenberg block
 *
 * @since      1.5.1
 * @package    IfSo
 * @subpackage IfSo/extensions/IfSoGutenbergBlock
 * @author Nick Martianov
 */
namespace IfSo\Extensions\IfSoGutenbergBlock;

require_once IFSO_PLUGIN_BASE_DIR . 'extensions/ifso-gutenberg-blocks/ifso-gutenberg-block-base.class.php';

class IfSoTriggerGutenbergBlock extends IfSoGutenbergBlockBase {
    public function enqueue_block_assets(){

        if($this->gutenberg_exists){
            wp_register_script(
                'ifso-gutenberg-block',
                plugin_dir_url( __FILE__ ) . './ifso-gutenberg-block.js',
                array( 'wp-blocks', 'wp-element', 'wp-data')
            );

            wp_register_style(
                'ifso-gutenberg-block',
                plugin_dir_url( __FILE__ ) . './ifso-gutenberg-block.css',
                array()
            );

            register_block_type('ifso/ifso-block',array(
                'editor_script'=>'ifso-gutenberg-block',
                'editor_style'=>'ifso-gutenberg-block',
                'render_callback'=>[$this,'render_ifso_block']
            ));
        }


    }

    public function enqueue_block_styles(){
        if($this->gutenberg_exists){
            wp_enqueue_style(
                'ifso-gutenberg-block',
                plugin_dir_url( __FILE__ ) . './ifso-gutenberg-block.css',
                array()
            );
        }
    }

    public function render_ifso_block($atts,$content){
        if (isset($atts['selected']) && $atts['selected'] > 0){
            return do_shortcode(sprintf( '[ifso id="%1$d"]', $atts['selected']));
        }
    }


}