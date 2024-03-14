<?php
/*
 * Plugin Name:  Gallery in columns
 * Plugin URI: https://wordpress.org/plugins/gallery-masonry-editor
 * Description: Transforms the Gutenberg gallery into a beautifull gallery. When you create a gallery on guttenberg do not check the box "Crop images". This is only a css overload.
 * Author: Fabien Picard
 * Version:1.0
 */
add_action( 'init', 'launch_gallerycolumnsCC');
function launch_gallerycolumnsCC(){
    add_action('wp_enqueue_scripts', 'gallerycolumnsCC_css_enqueue_style');
    function gallerycolumnsCC_css_enqueue_style(){
        wp_enqueue_style('gallerycolumnsCC_style', plugins_url('styles.css',__FILE__), false);
    }
    add_action('admin_print_styles', 'gallerycolumnsCC_admin_css_enqueue_style', 11);
    function gallerycolumnsCC_admin_css_enqueue_style(){
        wp_enqueue_style('gallerycolumnsCC_style', plugins_url('styles.css', __FILE__), false);
    }
}
