<?php

defined('ABSPATH') or die('Cannot access pages directly.');

function gp_social_css()
{
    $options = get_option('gp_social_settings');
    $fb_color = isset($options['facebook_colour']) ? esc_attr($options['facebook_colour']) : gp_social_default_icon_color();
    $fb_color_hover = isset($options['facebook_hover_colour']) ? esc_attr($options['facebook_hover_colour']) : '#1e73be';
    $tw_color = isset($options['twitter_colour']) ? esc_attr($options['twitter_colour']) : gp_social_default_icon_color();
    $tw_color_hover = isset($options['twitter_hover_colour']) ? esc_attr($options['twitter_hover_colour']) : '#00acee';
    $li_color = isset($options['linkedin_colour']) ? esc_attr($options['linkedin_colour']) : gp_social_default_icon_color();
    $li_color_hover = isset($options['linkedin_hover_colour']) ? esc_attr($options['linkedin_hover_colour']) : '#0077b5';
    $pin_color = isset($options['pinterest_colour']) ? esc_attr($options['pinterest_colour']) : gp_social_default_icon_color();
    $pin_color_hover = isset($options['pinterest_hover_colour']) ? esc_attr($options['pinterest_hover_colour']) : '#c92228';
    $em_color = isset($options['email_colour']) ? esc_attr($options['email_colour']) : gp_social_default_icon_color();
    $em_color_hover = isset($options['email_hover_colour']) ? esc_attr($options['email_hover_colour']) : '#f1f1d4';
    $wa_color = isset($options['whatsapp_colour']) ? esc_attr($options['whatsapp_colour']) : gp_social_default_icon_color();
    $wa_color_hover = isset($options['whatsapp_hover_colour']) ? esc_attr($options['whatsapp_hover_colour']) : '#075e54';

    $custom_css = "
        #gp-social-share a.fb-share svg {
            fill: {$fb_color};
        }
        #gp-social-share a.fb-share:hover svg {
            fill: {$fb_color_hover};
        }
        #gp-social-share a.tw-share svg {
            fill: {$tw_color};
        }
        #gp-social-share a.tw-share:hover svg {
            fill: {$tw_color_hover};
        }
        #gp-social-share a.li-share svg {
            fill: {$li_color};
        }
        #gp-social-share a.li-share:hover svg {
            fill: {$li_color_hover};
        }
        #gp-social-share a.pt-share svg {
            fill: {$pin_color};
        }
        #gp-social-share a.pt-share:hover svg {
            fill: {$pin_color_hover};
        }
        #gp-social-share a.em-share svg {
            fill: {$em_color};
        }
        #gp-social-share a.em-share:hover svg {
            fill: {$em_color_hover};
        }
        #gp-social-share a.wa-share svg {
            fill: {$wa_color};
        }
        #gp-social-share a.wa-share:hover svg {
            fill: {$wa_color_hover};
        }
    ";
    wp_add_inline_style('social-share-css', $custom_css);
} // gp_social_css
add_action('wp_enqueue_scripts', 'gp_social_css');
