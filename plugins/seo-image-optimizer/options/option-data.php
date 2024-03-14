<?php
defined('ABSPATH') || die();

$wl_wsio_options = weblizar_wsio_get_options();
/*
 * General settings save
 */
if (isset($_POST['weblizar_wsio_settings_save_section_general'])) {
    if (!wp_verify_nonce($_POST['security'], 'wl_wsio_settings')) {
        die();
    }
    if ($_POST['weblizar_wsio_settings_save_section_general'] == 1) {
        foreach ($_POST as  $key => $value) {
            $wl_wsio_options[$key] = sanitize_text_field($_POST[$key]);
        }
        if (isset($_POST['wsio_override_alt_value'])) {
            echo esc_html($wl_wsio_options['wsio_override_alt_value'] = sanitize_text_field($_POST['wsio_override_alt_value']));
        } else {
            echo esc_html($wl_wsio_options['wsio_override_alt_value'] = "off");
        }
        
        if (isset($_POST['wsio_override_title_value'])) {
            echo esc_html($wl_wsio_options['wsio_override_title_value'] = sanitize_text_field($_POST['wsio_override_title_value']));
        } else {
            echo esc_html($wl_wsio_options['wsio_override_title_value'] = "off");
        }

        if (isset($_POST['wsio_override_alt_custom_value'])) {
            echo esc_html($wl_wsio_options['wsio_override_alt_custom_value'] = sanitize_text_field($_POST['wsio_override_alt_custom_value']));
        }
        
        $i = 0;
        foreach ($_POST['wsio_alt_attribute_value'] as $wsio_alt_attribute_value) {
            if ($wsio_alt_attribute_value != '') {
                $value_get[$i] = $wsio_alt_attribute_value;
            }
            $i++;
        }
        if (isset($wl_wsio_options['wsio_alt_attribute_value'])) {
            $wl_wsio_options['wsio_alt_attribute_value'] = $value_get;
        }


        $j = 0;
        foreach ($_POST['wsio_title_tag_value'] as $wsio_title_tag_value) {
            if ($wsio_title_tag_value != '') {
                $user_get_id[$j] = $wsio_title_tag_value;
            }
            $j++;
        }
           $wl_wsio_options['wsio_title_tag_value'] = $user_get_id;
        update_option('weblizar_wsio_options', stripslashes_deep($wl_wsio_options));
    }

    if ($_POST['weblizar_wsio_settings_save_section_general'] == 2) {
        wsio_general_setting();
    }
}

/**
 * Define Image Size options settings
 */

if (isset($_POST['weblizar_wsio_settings_save_image_size_option'])) {
    if (!wp_verify_nonce($_POST['security'], 'wl_wsio_img_settings')) {
        die();
    }
    if ($_POST['weblizar_wsio_settings_save_image_size_option'] == 1) {
        foreach ($_POST as  $key => $value) {
            $wl_wsio_options[$key] = sanitize_text_field($_POST[$key]);
        }
        if ($_POST['wsio_image_resize_yesno']) {
            echo esc_html($wl_wsio_options['wsio_image_resize_yesno'] = sanitize_text_field($_POST['wsio_image_resize_yesno']));
        } else {
            echo esc_html($wl_wsio_options['wsio_image_resize_yesno'] = "off");
        }
        if ($_POST['wsio_image_recompress_yesno']) {
            echo esc_html($wl_wsio_options['wsio_image_recompress_yesno'] = sanitize_text_field($_POST['wsio_image_recompress_yesno']));
        } else {
            echo esc_html($wl_wsio_options['wsio_image_recompress_yesno'] = "off");
        }
        update_option('weblizar_wsio_options', stripslashes_deep($wl_wsio_options));
    }
    if ($_POST['weblizar_wsio_settings_save_image_size_option'] == 2) {
        wsio_image_setting();
    }
}
