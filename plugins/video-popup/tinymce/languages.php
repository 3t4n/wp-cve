<?php

if ( ! defined( 'ABSPATH' ) )
    exit();


if ( ! class_exists( '_WP_Editors' ) )
    require( ABSPATH . WPINC . '/class-wp-editor.php' );


function video_popup_tinymce_plugin_translation() {
    $strings = array(
        'b_title' => __('Display YouTube, Vimeo, SoundCloud, and MP4 Video in PopUp', 'video-popup'),
        
        'e_alert' => __('Firstly select link or text!', 'video-popup'),

        'w_title' => __('Video PopUp', 'video-popup'),

        'o_l_text' => __('Text', 'video-popup'),

        'o_l_url' => __('URL', 'video-popup'),

        'o_t_url' => __('Enter YouTube, Vimeo, SoundCloud, or MP4 Video link only.', 'video-popup'),

        'o_l_title' => __('Title', 'video-popup'),

        'o_t_title' => __('This title appears when the mouse passes over the link.', 'video-popup'),

        'o_l_shortcode' => __('You can using this Shortcode', 'video-popup'),

        'o_t_shortcode' => __('Click on "Shortcode Usage" button for the explanation.', 'video-popup'),

        'o_l_relnofollow' => __('Rel Nofollow', 'video-popup'),

        'o_t_relnofollow' => __("Select this option if your URL is YouTube, Vimeo, or external MP4 video link, it's good for SEO. Do not select this option if your URL is locally MP4 video link or SoundCloud link.", 'video-popup'),

        'o_l_autoplay' => __('Autoplay', 'video-popup'),

        'o_t_autoplay' => __('Autoplay for YouTube, Vimeo, SoundCloud, and MP4 video (externally and locally).', 'video-popup'),

        'o_l_dis_rel_vid' => __('Disable Related Videos (Premium)', 'video-popup'),

        'o_t_dis_rel_vid' => __('The behavior for the rel parameter is changing on or after September 25, 2018. The effect of the change is that you will not be able to disable related videos. Prior to the change, if you disabled the related videos, then the player does not show related videos. After the change, if you disabled the related videos, the player will show related videos that are from the same channel. This option for YouTube only. This option for the Premium Extension.', 'video-popup'),

        'o_l_dis_rel_vid_prm' => __('Disable Related Videos', 'video-popup'),

        'o_t_dis_rel_vid_prm' => __('The behavior for the rel parameter is changing on or after September 25, 2018. The effect of the change is that you will not be able to disable related videos. Prior to the change, if you disabled the related videos, then the player does not show related videos. After the change, if you disabled the related videos, the player will show related videos that are from the same channel. This option for YouTube only.', 'video-popup'),

        'o_l_starting_time' => __('Starting Time (Premium)', 'video-popup'),

        'o_t_starting_time' => __('Enter the starting time for the video, for example enter "90" (1 minute + 30 seconds = 90), the video will be played in "1:30". Numbers only. This option for YouTube only. This option for the Premium Extension.', 'video-popup'),

        'o_l_starting_time_prm' => __('Starting Time', 'video-popup'),

        'o_t_starting_time_prm' => __('Enter the starting time for the video, for example enter "90" (1 minute + 30 seconds = 90), the video will be played in "1:30". Numbers only. This option for YouTube only.', 'video-popup'),

        'o_l_width' => __('Width Size (Premium)', 'video-popup'),

        'o_t_width' => __('Enter width size for the video, for example "1200". Numbers only. This option for the Premium Extension.', 'video-popup'),

        'o_l_width_prm' => __('Width Size', 'video-popup'),

        'o_t_width_prm' => __('Enter width size for the video, for example "1200". Numbers only.', 'video-popup'),

        'o_l_height' => __('Height Size (Premium)', 'video-popup'),

        'o_t_height' => __('Enter height size for the video, for example "600". Numbers only. This option for the Premium Extension.', 'video-popup'),

        'o_l_height_prm' => __('Height Size', 'video-popup'),

        'o_t_height_prm' => __('Enter height size for the video, for example "600". Numbers only.', 'video-popup'),

        'o_l_dis_wrap' => __('Remove Border (Premium)', 'video-popup'),

        'o_t_dis_wrap' => __("Removing the white border. This option for the Premium Extension.", 'video-popup'),

        'o_l_dis_wrap_prm' => __('Remove Border', 'video-popup'),

        'o_t_dis_wrap_prm' => __("Removing the white border.", 'video-popup'),

        'o_l_olcolor' => __('Color of Overlay (Premium)', 'video-popup'),

        'o_t_olcolor' => __('Enter the color of overlay, enter HEX code only, for example "#ffffff". Enter full HEX code such as "#ffffff", not shortened such as "#fff". Default is black "#000000". This option for the Premium Extension.', 'video-popup'),

        'o_l_olcolor_prm' => __('Color of Overlay', 'video-popup'),

        'o_t_olcolor_prm' => __('Enter the color of overlay, enter HEX code only, for example "#ffffff". Enter full HEX code such as "#ffffff", not shortened such as "#fff". Default is black "#000000".', 'video-popup'),

        'o_e_olcolor_alert_prm' => __('Please enter full HEX code such as "#ffffff".', 'video-popup'),

        'b_txt_prm_extension' => __('Get The Premium Extension!', 'video-popup'),

        'b_tol_prm_extension' => __("Get it at a low price! Unlock all the features. Easy to use, download it, install it, activate it, and enjoy! Get it now!", 'video-popup'),

        'b_txt_doc' => __('Explanation of Use', 'video-popup'),

        'b_tol_doc' => __('Need help? Support? Questions? Read the Explanation of Use.', 'video-popup'),

        'b_txt_rec_links' => __('Recommended Products:', 'video-popup'),

        'b_txt_why_see' => __('Why do you see "Recommended Products" in this plugin?', 'video-popup'),

        'b_tol_why_see' => __('We offer you professional WordPress plugins for free, so you will see "Recommended Products". No "Recommended Products" in the Premium Extension! Get it now.', 'video-popup'),

        'b_txt_divi' => __('Divi Theme', 'video-popup'),

        'b_tol_divi' => __('The Ultimate WordPress Theme & Visual Page Builder. Try it, a 30-Day Money Back Guarantee!', 'video-popup'),

        'b_txt_bluehost' => __('Bluehost', 'video-popup'),

        'o_l_dis_cont_vid' => __('Disable Controls (Premium)', 'video-popup'),

        'o_t_dis_cont_vid' => __('Disable YouTube player controls. This option for YouTube only. This option for the Premium Extension.', 'video-popup'),

        'o_l_dis_cont_vid_prm' => __('Disable Controls', 'video-popup'),

        'o_t_dis_cont_vid_prm' => __('Disable YouTube player controls. This option for YouTube only.', 'video-popup'),

        'o_l_display_yt_img' => __('Display YouTube Image (Premium)', 'video-popup'),

        'o_t_display_yt_img' => __('Display YouTube video image inside the Video PopUp link. This option for YouTube only. This option for the Premium Extension.', 'video-popup'),

        'o_l_display_yt_img_prm' => __('Display YouTube Image', 'video-popup'),

        'o_t_display_yt_img_prm' => __('Display YouTube video image inside the Video PopUp link. This option for YouTube only.', 'video-popup'),

        'o_l_ending_time' => __('Ending Time (Premium)', 'video-popup'),

        'o_t_ending_time' => __('The time offset at which the video should stop playing. The value is a positive integer that specifies the number of seconds into the video that the player stops playback. For example enter "90" (1 minute + 30 seconds = 90), now the video will be stopped playing in "1:30". This option for YouTube only. This option for the Premium Extension.', 'video-popup'),

        'o_l_ending_time_prm' => __('Ending Time', 'video-popup'),

        'o_t_ending_time_prm' => __('The time offset at which the video should stop playing. The value is a positive integer that specifies the number of seconds into the video that the player stops playback. For example enter "90" (1 minute + 30 seconds = 90), now the video will be stopped playing in "1:30". This option for YouTube only.', 'video-popup'),

        'o_l_image_link' => __('Image URL', 'video-popup'),

        'o_t_image_link' => __('Enter an image link to display an image as link for the Video Popup. It’s works with YouTube, Vimeo, SoundCloud, and MP4 video.', 'video-popup'),

        'o_l_shortcode_usage' => __('Shortcode Usage', 'video-popup'),

        'o_t_shortcode_usage' => __('Read Explanation of Use the Shortcode.', 'video-popup'),

        'o_l_gen_settings' => __('General Settings', 'video-popup'),

        'o_t_gen_settings' => __("General settings will applied to all the video popup's.", 'video-popup'),

        'o_l_auto_display' => __('Automatic Video Popup (Premium)', 'video-popup'),

        'o_t_auto_display' => __('Displaying the Video Popup automatically while opening the page or the post. Choose this option only once per page or post, for example, if you have an article with 3 pop-up videos, select this option only for a one video to be displayed automatically when opening the page or the post. This option for the Premium Extension.', 'video-popup'),

        'o_l_auto_display_prm' => __('Automatic Video Popup', 'video-popup'),

        'o_t_auto_display_prm' => __('Displaying the Video Popup automatically while opening the page or the post. Choose this option only once per page or post, for example, if you have an article with 3 pop-up videos, select this option only for a one video to be displayed automatically when opening the page or the post.', 'video-popup'),

        'o_l_dis_iv' => __('Disable Annotations (Premium)', 'video-popup'),

        'o_t_dis_iv' => __('Disable video annotations. This option for YouTube only. This option for the Premium Extension.', 'video-popup'),

        'o_l_dis_iv_prm' => __('Disable Annotations', 'video-popup'),

        'o_t_dis_iv_prm' => __('Disable video annotations. This option for YouTube only.', 'video-popup'),

        'o_l_on_pageload' => __('On Page Load', 'video-popup'),

        'o_t_on_pageload' => __("Display Pop-up Video on page loading.", 'video-popup')
    );

    $locale = _WP_Editors::$mce_locale;

    $translated = 'tinyMCE.addI18n("' . $locale . '.video_popup_translation_vars", ' . json_encode( $strings ) . ");\n";

    return $translated;
}

$strings = video_popup_tinymce_plugin_translation();