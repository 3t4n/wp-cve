<?php 

defined( 'ABSPATH' ) || exit;

//Check if the shortcode function exists, as users may want to create their own to override the button
if(!function_exists('tims_nso_nextcloud_login_button')){
    function tims_nso_nextcloud_login_button($atts, $content = "") {
        global $GLOBALS, $wp;

        if(is_user_logged_in()){
            // no need to show it
            return '';
        }
        $atts = shortcode_atts( array(
            'class' => 'btn',
            'style' => ''
        ), $atts, 'nextcloud_login' );

        $url = esc_url_raw(wp_login_url().'?nc-sso=redirect');

        if(get_option('tims_nso_login_type') == 'redirect_back'){
            // if on wp login page, it may already have redirect_to in the URL
            if(isset($GLOBALS['pagenow']) && $GLOBALS['pagenow'] === 'wp-login.php'){
                if(isset($_GET['redirect_to']) && $_GET['redirect_to'] && filter_var($_GET['redirect_to'], FILTER_VALIDATE_URL)){
                    $url = $url.'&redirect_to='.urlencode(esc_url_raw($_GET['redirect_to']));
                }
            }else{
                // we are somewhere else, try to get the url
                global $wp;
                $current_url = home_url( add_query_arg( array(), $wp->request ) );

                $url = $url.'&redirect_to='.urlencode(esc_url_raw($current_url));
            }
        }

        $url = apply_filters('tims_nso_nextcloud_login_button_url', $url);

        return '<a href="'.$url.'" class="'.esc_attr($atts['class']).'" style="'.esc_attr($atts['style']).'">'.esc_attr($content).'</a>';
    }
}

add_shortcode( 'nextcloud_login', 'tims_nso_nextcloud_login_button');


//Check if the shortcode function exists, as users may want to create their own to override the link
if(!function_exists('tims_nso_nextcloud_login_link')){
    function tims_nso_nextcloud_login_link($atts, $content = "") {
        $url = esc_url_raw(wp_login_url().'?nc-sso=redirect');
        $url = apply_filters('tims_nso_nextcloud_login_button_url', $url);
        return $url;
    }
}

add_shortcode( 'nextcloud_login_link', 'tims_nso_nextcloud_login_link');