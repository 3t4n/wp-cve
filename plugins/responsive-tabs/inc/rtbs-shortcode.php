<?php

// Create the Responsive Tabs shortcode
function rtbs_sc($atts)
{
    extract(shortcode_atts([
        'name' => '',
    ], $atts));

    global $post;

    $args = ['post_type' => 'rtbs_tabs', 'name' => $name];
    $custom_posts = get_posts($args);
    $output = '';

    foreach ($custom_posts as $post) {
        setup_postdata($post);

        // get data
        $entries = get_post_meta($post->ID, '_rtbs_tabs_head', true);

        // get settings
        (get_post_meta($post->ID, '_rtbs_tbg', true)) ? $rtbs_tbg = get_post_meta($post->ID, '_rtbs_tbg', true) : $rtbs_tbg = 'transparent';
        $original_font = get_post_meta($post->ID, '_rtbs_original_font', true);
        $original_font && 'no' != $original_font ? $ori_f = 'rtbs_tab_ori' : $ori_f = '';
        $rtbs_breakpoint = get_post_meta($post->ID, '_rtbs_breakpoint', true);
        $rtbs_color = get_post_meta($post->ID, '_rtbs_tabs_bg_color', true);

        // output settings in invisible divs
        $output = '<div class="rtbs '.esc_attr($ori_f).' rtbs_'.esc_attr($name).'">';
        $output .= '<div class="rtbs_slug" style="display:none">'.esc_attr($name).'</div>';
        $output .= '<div class="rtbs_inactive_tab_background" style="display:none">'.esc_attr($rtbs_tbg).'</div>';
        $output .= '<div class="rtbs_breakpoint" style="display:none">'.esc_attr($rtbs_breakpoint).'</div>';
        $output .= '<div class="rtbs_color" style="display:none">'.esc_attr($rtbs_color).'</div>';

        $output .= '
        <div class="rtbs_menu">
            <ul>
                <li class="mobile_toggle">&zwnj;</li>';
        foreach ($entries as $key => $tabs) {
            if (0 == $key) {
                $output .= '<li class="current">';
                $output .= '<a style="background:'.esc_attr($rtbs_color).'" class="active '.esc_attr($name).'-tab-link-'.$key.'" href="#" data-tab="#'.esc_attr($name).'-tab-'.$key.'">';

                (!empty($tabs['_rtbs_title'])) ?
                    $output .= esc_attr($tabs['_rtbs_title']) :
                    $output .= '&nbsp;';

                $output .= '</a>';
                $output .= '</li>';
            } else {
                $output .= '<li>';
                $output .= '<a href="#" data-tab="#'.esc_attr($name).'-tab-'.$key.'" class="'.esc_attr($name).'-tab-link-'.$key.'">';
                (!empty($tabs['_rtbs_title'])) ?
                    $output .= esc_attr($tabs['_rtbs_title']) :
                    $output .= '&nbsp;';

                $output .= '</a>';
                $output .= '</li>';
            }
        }
        $output .= '
            </ul>
        </div>';

        foreach ($entries as $key => $tabs) {
            if (0 == $key) {
                $output .= '<div style="border-top:7px solid '.esc_attr($rtbs_color).';" id="'.esc_attr($name).'-tab-'.$key.'" class="rtbs_content active">';
                $output .= do_shortcode(wp_kses_post(wpautop($tabs['_rtbs_content'])));
                $output .= '<div style="margin-top:30px; clear:both;"></div></div>';
            } else {
                $output .= '<div style="border-top:7px solid '.esc_attr($rtbs_color).';" id="'.esc_attr($name).'-tab-'.$key.'" class="rtbs_content">';
                $output .= do_shortcode(wp_kses_post(wpautop($tabs['_rtbs_content'])));
                $output .= '<div style="margin-top:30px; clear:both;"></div></div>';
            }
        }
        $output .= '
    </div>
    ';
    }
    wp_reset_postdata();

    return $output;
}

add_shortcode('rtbs', 'rtbs_sc');
