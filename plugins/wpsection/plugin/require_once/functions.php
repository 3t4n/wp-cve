<?php


//This code convert Shortcode in the Menu ARea
function wpse_shortcode_nav_menu($nav_menu, $args) {
    return preg_replace_callback('/<li [^>]+>(.+?)<\/li>/', function($matches) {
        return str_replace($matches[1], do_shortcode($matches[1]), $matches[0]);
    }, $nav_menu);
}
add_filter('wp_nav_menu', 'wpse_shortcode_nav_menu', 10, 2);




// Hook to add the admin menu  This code is for WPS Template all the Custom Post Hold
add_action('admin_menu', 'wpsection_template_menu');
// Function to create the admin menu
function wpsection_template_menu() {
    add_menu_page(
        'WPS Template',   // Page title
        'WPS Template',   // Menu title
        'manage_options',        // Capability
        'wpsection_template',    // Menu slug
        'wpsection_template_page', // Callback function to display the menu page
        'dashicons-layout',      // Icon URL or CSS class
        40                       // Position in the menu
    );



}
function wpsection_template_page() {
    echo '<div class="wrap">';
    echo '<h1>WPS Template</h1>';
    // Add content or settings for the main menu page here
    echo '</div>';
}
//End of custompost holder



// This is funcion to all the custom control Layout for style in shortcode section
if ( ! function_exists( 'wpsection_set' ) ) {

    function wpsection_set( $var, $key, $def = '' ) {
        /*if (!$var)
        return false;*/

        if ( is_object( $var ) && isset( $var->$key ) ) {
            return $var->$key;
        } elseif ( is_array( $var ) && isset( $var[ $key ] ) ) {
            return $var[ $key ];
        } elseif ( $def ) {
            return $def;
        } else {
            return false;
        }
    }

}


//This code for Slider loop

if (!function_exists('wpsection_elementor_template_')) {
    function wpsection_elementor_template_($type = null) {
        $args = [
            'post_type' => 'slider_templates',
            'posts_per_page' => -1,
        ];
        if ($type) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'slider_templates_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ];
        }
        $template = get_posts($args);
        $tpl = array();
        if (!empty($template) && !is_wp_error($template)) {
            foreach ($template as $post) {
                $tpl[$post->post_name] = $post->post_title;
            }
        }
        return $tpl;
    }
}



if (!function_exists('wpsection_elementor_template_')) {
    function wpsection_elementor_template_($type = null) {
        $args = [
            'post_type' => 'elementor_library',
            'posts_per_page' => -1,
        ];
        if ($type) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'elementor_library_type',
                    'field' => 'slug',
                    'terms' => $type,
                ],
            ];
        }
        $template = get_posts($args);
        $tpl = array();
        if (!empty($template) && !is_wp_error($template)) {
            foreach ($template as $post) {
                $tpl[$post->post_name] = $post->post_title;
            }
        }
        return $tpl;
    }
}



if (!function_exists('wpsection_elemntor_content')) {
    function wpsection_elemntor_content( $slug ) {

        $content_post = get_posts(array(
            'name' => $slug,
            'posts_per_page' => 1,
            'post_type' => 'elementor_library',
            'post_status' => 'publish'
        ));

        if (array_key_exists(0, $content_post) == true) {
            $id = $content_post[0]->ID;
            return $id;
        }
    }
}



