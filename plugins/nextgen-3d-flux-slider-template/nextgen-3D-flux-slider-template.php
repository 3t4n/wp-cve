<?php

/*
  Plugin Name: NextGEN 3D and 2D Animated Flux Slider Template
  Plugin URI: http://wpdevsnippets.com/nextgen-3d-2d-animated-flux-slider-template
  Description: Add a "3dfluxsliderview" template for the NextGen gallery. Use the shortcode [nggallery id=x template="3dfluxsliderview"] to display images as the slider.
  Author: Mohsin Rasool
  Author URI: http://wpdevsnippets.com
  Version: 1.1.1
 */

include 'admin-settings.php';

if (!class_exists('ngg3DFluxSliderview')) {

    class ngg3DFluxSliderview {

        var $plugin_name = null;

        function ngg3DFluxSliderview() {
            $this->plugin_name = '/' . plugin_basename(dirname(__FILE__)) . '/';
            add_action('wp_enqueue_scripts', array(&$this, 'load_scripts'));
            add_action('wp_enqueue_scripts', array(&$this, 'load_styles'));
            add_filter('ngg_render_template', array(&$this, 'add_template'), 10, 2);
        }

        function add_template($path, $template_name = false) {

            if ($template_name == 'gallery-3dfluxsliderview') {
                $path = WP_PLUGIN_DIR . $this->plugin_name . '/template-nggsliderview.php';
            }

            return $path;
        }

        function load_styles() {
            wp_enqueue_style('ngg3dfluxsliderview-css', plugins_url($this->plugin_name . 'css/style.css'), false, '1.0.1', 'screen');
        }

        function load_scripts() {
            wp_enqueue_script('ngg3dfluxsliderview', plugins_url($this->plugin_name . 'js/flux.min.js'), array('jquery'), '1.0');
        }

    }

    // Start this plugin once all other plugins are fully loaded
    add_action('plugins_loaded', create_function('', 'global $ngg3DFluxSliderview; $ngg3DFluxSliderview = new ngg3DFluxSliderview();'));

    // Plugin Activation Hook
    function ngg3DFluxSliderview_activate() {
        // Check if its a first install
        $transitions = get_option('ng_3dfluxslider_transitions');
        if (empty($transitions)) {
            add_option('ng_3dfluxslider_transitions', array('bars', 'blinds', 'blocks', 'blocks2', 'concentric', 'slide', 'warp', 'zip', 'bars3d', 'blinds3d', 'cube', 'tiles3d', 'turn'));
            add_option('ng_3dfluxslider_controls', '0');
            add_option('ng_3dfluxslider_pagination', '1');
            add_option('ng_3dfluxslider_caption', '0');
            add_option('ng_3dfluxslider_delay', '4');
        }
        else{
            foreach($transitions as $index=>$trans){
                if($trans=='bar')
                    $transitions[$index] = 'bars';
            }
            update_option('ng_3dfluxslider_transitions',$transitions);
        }
    }

    register_activation_hook(__FILE__, 'ngg3DFluxSliderview_activate');
}