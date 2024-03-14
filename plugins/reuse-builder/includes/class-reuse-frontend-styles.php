<?php
/**
 *
 */

namespace Reuse\Builder;

class Reuse_Builder_Frontend_Style {
    public function __construct() {
        add_action('wp_enqueue_scripts', array( $this , 're_load_styles' ), 20 );
    }

    public function re_load_styles(){
        wp_register_style('reuse-builder', REUSE_BUILDER_CSS.'reuse-builder.css', array(), $ver = false, $media = 'all');
        wp_enqueue_style('reuse-builder');
    }
}
