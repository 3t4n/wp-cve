<?php

// Shortcodes
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/shortcode-newsletter.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/shortcode-eli_option_value.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'shortcodes/shortcode-post_content.php';

function eli_shortcodes_view($view_file = '', $element = '', $print = false)
{
    if(empty($view_file)) return false;
    $file = false;
    
    if(is_child_theme() && file_exists(get_stylesheet_directory().'/elementor-elementinvader_addons_for_elementor/views/'.$view_file.'.php'))
    {
        $file = get_stylesheet_directory().'/elementor-elementinvader_addons_for_elementor/views/'.$view_file.'.php';
    }
    elseif(file_exists(get_template_directory().'/elementor-elementinvader_addons_for_elementor/shortcodes/views/'.$view_file.'.php'))
    {
        $file = get_template_directory().'/elementor-elementinvader_addons_for_elementor/shortcodes/views/'.$view_file.'.php';
    }
    elseif(file_exists(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'shortcodes/views/'.$view_file.'.php'))
    {
        $file = ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'shortcodes/views/'.$view_file.'.php';
    }

    if($file)
    {
        extract($element);
        if($print) {
            include $file;
        } else {
            ob_start();
            include $file;
            return ob_get_clean();
        }
    }
    else
    {
        if($print) {
            echo 'View file not found in: '.esc_html(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'shortcodes/views/'.$view_file.'.php');
        } else {
            return 'View file not found in: '.esc_html(ELEMENTINVADER_ADDONS_FOR_ELEMENTOR_PATH.'shortcodes/views/'.$view_file.'.php');
        } 
    }
}

?>