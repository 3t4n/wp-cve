<?php

defined( 'ABSPATH' ) or die(':)');


function vp_gs_checkbox_validation($option){
    if( $option == 1 ){
        return $option;
    }else{
        return '';
    }
}

function vp_al_display_validation($option){
    $array = array('homepage', 'frontpage', 'entire', 'custom');

    if( in_array($option, $array) ){
        return $option;
    }else{
        return '';
    }
}

function vp_gs_hex_code_validation($option){
    if( preg_match('/^#[a-f0-9]{6}$/i', $option) ){
        $sanitize_text_field = sanitize_text_field($option);
        return $sanitize_text_field;
    }else{
        return '';
    }
}

function vp_al_number_validation($option){
    if( is_numeric($option) or empty($option) ){
        return $option;
    }else{
        return '';
    }
}


function vp_al_align_validation($option){
    $array = array('none', 'left', 'right');

    if( in_array($option, $array) ){
        return $option;
    }else{
        return '';
    }
}


function video_popup_add_general_settings(){
    add_settings_section('vp_gs_section', false, false, 'vp_gs_options');
    add_settings_section('vp_al_section', false, false, 'vp_al_options');

    add_settings_field(
        "vp_gs_op_editor_style",
        __('Disable Editor Style', 'video-popup'),
        "vp_gs_op_editor_style_cb",
        "vp_gs_options",
        "vp_gs_section",
        array('label_for' => 'vp_gs_op_editor_style')
    );
    register_setting( 'vp_gs_section', 'vp_gs_op_editor_style', 'vp_gs_checkbox_validation' );

    add_settings_field(
        "vp_al_op_video_url",
        __('Video URL', 'video-popup'),
        "vp_al_op_video_url_cb",
        "vp_al_options",
        "vp_al_section",
        array('label_for' => 'vp_al_op_video_url')
    );
    register_setting( 'vp_al_section', 'vp_al_op_video_url', 'esc_url_raw' );

    add_settings_field(
        "vp_al_op_autoplay",
        __('Autoplay', 'video-popup'),
        "vp_al_op_autoplay_cb",
        "vp_al_options",
        "vp_al_section",
        array('label_for' => 'vp_al_op_autoplay')
    );
    register_setting( 'vp_al_section', 'vp_al_op_autoplay', 'vp_gs_checkbox_validation' );

    add_settings_field(
        "vp_al_op_display",
        __('Display Options', 'video-popup'),
        "vp_al_op_display_cb",
        "vp_al_options",
        "vp_al_section"
    );
    register_setting( 'vp_al_section', 'vp_al_op_display', 'vp_al_display_validation' );
    register_setting( 'vp_al_section', 'vp_al_op_display_custom', 'vp_al_number_validation' );

    if( get_option('vp_al_op_display') === false ){
        update_option('vp_al_op_display', 'entire');
    }

    add_settings_field(
        "vp_al_op_cookie",
        __('Display Time', 'video-popup'),
        "vp_al_op_cookie_cb",
        "vp_al_options",
        "vp_al_section",
        array('label_for' => 'vp_al_op_cookie')
    );
    register_setting( 'vp_al_section', 'vp_al_op_cookie', 'vp_al_number_validation' );

    if( get_option('vp_al_op_cookie') === false ){
        update_option('vp_al_op_cookie', '1');
    }

    add_settings_field(
        "vp_al_op_logged_in",
        __('Exclude Users', 'video-popup'),
        "vp_al_op_logged_in_cb",
        "vp_al_options",
        "vp_al_section",
        array('label_for' => 'vp_al_op_logged_in')
    );
    register_setting( 'vp_al_section', 'vp_al_op_logged_in', 'vp_gs_checkbox_validation' );

    register_setting( 'vp_al_section', 'vp_al_op_yt_mute', 'vp_gs_checkbox_validation' );

    add_settings_field(
        "vp_gs_op_remove_boder",
        __('Remove Border', 'video-popup'),
        "vp_gs_op_remove_boder_callback",
        "vp_gs_options",
        "vp_gs_section",
        array('label_for' => 'vp_gs_op_remove_boder')
    );
    register_setting( 'vp_gs_section', 'vp_gs_op_remove_boder', 'vp_gs_checkbox_validation' );

    add_settings_field(
        "vp_gs_op_width_size",
        __('Width Size', 'video-popup'),
        "vp_gs_op_width_size_callback",
        "vp_gs_options",
        "vp_gs_section",
        array('label_for' => 'vp_gs_op_width_size')
    );
    register_setting( 'vp_gs_section', 'vp_gs_op_width_size', 'vp_al_number_validation' );

    add_settings_field(
        "vp_gs_op_height_size",
        __('Height Size', 'video-popup'),
        "vp_gs_op_height_size_callback",
        "vp_gs_options",
        "vp_gs_section",
        array('label_for' => 'vp_gs_op_height_size')
    );
    register_setting( 'vp_gs_section', 'vp_gs_op_height_size', 'vp_al_number_validation' );

    add_settings_field(
        "vp_gs_op_overlay_color",
        __('Color of Overlay', 'video-popup'),
        "vp_gs_op_overlay_color_callback",
        "vp_gs_options",
        "vp_gs_section",
        array('label_for' => 'vp_gs_op_overlay_color')
    );
    register_setting( 'vp_gs_section', 'vp_gs_op_overlay_color', 'vp_gs_hex_code_validation' );

    add_settings_field(
        "vp_al_op_align",
        __('Alignment Options', 'video-popup'),
        "vp_al_op_align_options_callback",
        "vp_al_options",
        "vp_al_section"
    );

    add_settings_field(
        "vp_al_op_yt_dis_rel_videos",
        __('YouTube Options', 'video-popup'),
        "vp_al_op_youtube_options_callback",
        "vp_al_options",
        "vp_al_section"
    );

    add_settings_field(
        "vp_al_op_design_w",
        __('Design Options', 'video-popup'),
        "vp_al_op_design_options_callback",
        "vp_al_options",
        "vp_al_section"
    );
    register_setting( 'vp_al_section', 'vp_al_op_d_remove_border', 'vp_gs_checkbox_validation' );
}
add_action('admin_init', 'video_popup_add_general_settings', 1);


function video_popup_general_settings_callback(){
    do_action('video_popup_gs_cb_action');
}


function video_popup_general_settings_free(){
    ?>
        <div class="wrap">
            <div class="vp-clear-fix">


                <div class="vp-left-col">
                    <?php if( !get_option('vp_green_bg_menu') ) : ?>
                        <?php update_option('vp_green_bg_menu', 'true'); ?>
                        <style type="text/css">
                            body a.toplevel_page_video_popup_general_settings{
                                background: #0073aa !important;
                            }
                        </style>
                    <?php endif; ?>

                    <h1 style="margin-bottom: 20px !important;"><span><?php _e('Video PopUp General Settings', 'video-popup'); ?></span></h1>

                    <h2><?php _e("General settings will applied to all the video popup's.", 'video-popup'); ?></h2>

                    <?php
                        if( isset($_GET['settings-updated']) and $_GET['settings-updated'] == 'true' ){
                            ?>
                                <div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
                                     <p><strong><?php _e('Settings saved.'); ?></strong></p>
                                    <button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php _e('Dismiss this notice.'); ?></span></button>
                                </div>
                            <?php
                        }
                    ?>

                    <form method="post" action="options.php">
                        <?php
                            settings_fields("vp_gs_section");
                            do_settings_sections("vp_gs_options");
                            submit_button();
                        ?>
                    </form>

                </div>

                <div class="vp-right-col">

                    <div class="postbox vp-no-premium-ext">
                    <h2 style="border-bottom: 1px solid #eee !important;padding: 12px !important;margin: 0 !important;"><span><?php _e('Get The Premium Extension!', 'video-popup'); ?></span></h2>
                    <div class="inside" style="padding: 12px !important;margin: 0 !important;">

                        <div class="main">

                            <p style="margin: 0 !important;"><?php _e("Get it at a low price! Unlock all the features. Easy to use, download it, install it, activate it, and enjoy! Get it now!", 'video-popup'); ?></p>

                            <p style="margin-bottom: 0 !important;"><a href="https://wp-plugins.in/Get-VP-Premium-Extension" class="vp-settings-btn vp-get-premium-su" target="_blank"><?php _e('Get The Premium Extension', 'video-popup'); ?></a></p>

                        </div>

                    </div>
                </div>


                <div class="postbox">
                    <h2 style="border-bottom: 1px solid #eee !important;padding: 12px !important;margin: 0 !important;"><span><?php _e('Explanation of Use', 'video-popup'); ?></span></h2>
                    <div class="inside" style="padding: 12px !important;margin: 0 !important;">

                        <div class="main">

                            <p style="margin: 0 !important;"><?php _e('Need help? Support? Questions? Read the Explanation of Use.', 'video-popup'); ?></p>

                            <p style="margin-bottom: 0 !important;"><a href="https://wp-plugins.in/VideoPopUp-Usage" class="vp-settings-btn vp-read-expofuse-su" target="_blank"><?php _e('Explanation of Use', 'video-popup'); ?></a></p>

                        </div>

                    </div>
                </div>

                </div>


            </div>
        </div>
    <?php
}
add_action('video_popup_gs_cb_action', 'video_popup_general_settings_free');


function vp_gs_op_editor_style_cb(){
    $dis_ed_style = get_option('vp_gs_op_editor_style');
    ?>
        <label for="vp_gs_op_editor_style" style="display:block;"><input type="checkbox" id="vp_gs_op_editor_style" name="vp_gs_op_editor_style" value="1" <?php checked($dis_ed_style, 1, true); ?>><?php _e('Disable the background color of the Video PopUp links in the editor.', 'video-popup'); ?></label>
    <?php
}


function vp_al_op_video_url_cb(){
    $video_url = get_option('vp_al_op_video_url');
    ?>
        <label for="vp_al_op_video_url" style="display:block;"><input style="width:100%;" class="regular-text" type="text" id="vp_al_op_video_url" name="vp_al_op_video_url" value="<?php echo esc_url($video_url); ?>"><p class="description"><?php _e('Enter a video URL to display it as "Video Popup" when page loading. Enter YouTube, Vimeo, or MP4 video link only. SoundCloud is not supported.', 'video-popup'); ?><br><span style="font-weight: bold;"><?php _e('To disable "On Page Loading" feature, leave this field blank.', 'video-popup'); ?></span></p></label>
    <?php
}


function vp_al_op_autoplay_cb(){
    $vp_onp_autoplay_note = sprintf( __('Autoplay. Please read <a href="%1$s" target="_blank">this note</a> about the Autoplay for the "On Page Load" feature.', 'video-popup'), 'https://wp-plugins.in/37VT3Z0' );
    $autoplay = get_option('vp_al_op_autoplay');
    ?>
        <label for="vp_al_op_autoplay" style="display:block;"><input type="checkbox" id="vp_al_op_autoplay" name="vp_al_op_autoplay" value="1" <?php checked($autoplay, 1, true); ?>><?php echo $vp_onp_autoplay_note; ?></label>
    <?php
}


function vp_al_op_display_cb(){
    $display = get_option('vp_al_op_display');
    $custom = get_option('vp_al_op_display_custom');
    ?>
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Display', 'video-popup'); ?> <?php _e('Options', 'video-popup'); ?></span></legend>

            <p><?php _e('Display the Video PopUp in:', 'video-popup'); ?></p>

            <label title="<?php esc_attr_e('Display the Video PopUp in the entire website. For example, in homepage, frontpage, posts, pages, categories, tags, attachment, etc..', 'video-popup'); ?>">
                <input type="radio" name="vp_al_op_display" value="entire" <?php checked( $display, 'entire' ); ?>><?php _e('The Entire Website.', 'video-popup'); ?>
            </label>
            <br>
            <label title="<?php esc_attr_e('Display the Video PopUp in Posts page only.', 'video-popup'); ?>">
                <input type="radio" name="vp_al_op_display" value="homepage" <?php checked( $display, 'homepage' ); ?>><?php _e('Posts page only.', 'video-popup'); ?>
            </label>
            <br>
            <label title="<?php esc_attr_e('Display the Video PopUp in Homepage only.', 'video-popup'); ?>">
                <input type="radio" name="vp_al_op_display" value="frontpage" <?php checked( $display, 'frontpage' ); ?>><?php _e('Homepage only.', 'video-popup'); ?> <?php _e('You should to set the homepage settings from', 'video-popup'); ?> <a target="_blank" href="<?php echo esc_url( admin_url('/options-reading.php') ); ?>"><?php _e('Reading Settings', 'video-popup'); ?></a>.
            </label>
            <br>
            <label title="<?php esc_attr_e('Display the Video PopUp in custom post/page ID. Select this option, then enter the post or page ID.', 'video-popup'); ?>" for="vp-display-custom">
                <input id="vp-display-custom" type="radio" name="vp_al_op_display" value="custom" <?php checked( $display, 'custom' ); ?>><?php _e('Custom Post/Page ID:', 'video-popup'); ?> <input class="small-text" type="text" name="vp_al_op_display_custom" style="width:100px;" value="<?php echo esc_attr($custom); ?>">
            </label>
     </fieldset>

     <p class="description"><?php _e('If you change the display options, please clear all the cache files each time you change the display options, just if you are use a caching plugin on your site, such as "WP Super Cache Plugin". If you are not use a caching plugin at the current time, so ignore this note.', 'video-popup'); ?></p>
    <?php
}


function vp_al_op_cookie_cb(){
    $cookie_time = get_option('vp_al_op_cookie');
    ?>
        <label for="vp_al_op_cookie" style="display:block;"><input style="width:100px;" class="small-text" type="text" id="vp_al_op_cookie" name="vp_al_op_cookie" value="<?php echo esc_attr($cookie_time); ?>"><p class="description"><?php _e('Enter the display time, for example enter number 24, which means that the Pop-up Video will be displayed once per visitor, and the Pop-up Video will be displayed again to the same visitor after the 24 hours. Default is "1" (1 = 1 hour). If you want to show the Pop-up Video to all visitors with each visit, leave this option blank.', 'video-popup'); ?> <a href="<?php echo esc_url( admin_url('/?vp_delete_cookie=1') ); ?>" title="<?php esc_attr_e('If you want to test your Video Popup, and you can not wait for hours, click on “Delete cookie” link to delete the Video Popup cookie in your browser.','video-popup'); ?>"><?php _e('(Delete cookie?)','video-popup'); ?></a></p></label>
    <?php
}


function vp_al_op_logged_in_cb(){
    $logged_in = get_option('vp_al_op_logged_in');
    ?>
        <label for="vp_al_op_logged_in" style="display:block;"><input type="checkbox" id="vp_al_op_logged_in" name="vp_al_op_logged_in" value="1" <?php checked($logged_in, 1, true); ?>><?php _e('If the current visitor is a logged in user, will be exclude it (whether he is an author, subscriber, editor, moderator, or administrator, etc..), the Pop-up Video will not be shown to him.', 'video-popup'); ?></label>
    <?php
}


function vp_al_op_align_options_callback(){
    do_action('vp_al_op_align_options_cb_action');
}


function vp_al_op_align_options_cb_free(){
    ?>
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Alignment Options', 'video-popup'); ?></span></legend>

            <p><?php _e('Display the Pop-up Video on page loading with the left or right video alignment:', 'video-popup'); ?></p>

            <label title="<?php esc_attr_e('Align the video to the left. This option for the Premium Extension.', 'video-popup'); ?>">
                <input type="radio" value="1" disabled><span style="color:#999 !important;"><?php _e('Left (Premium).', 'video-popup'); ?> <a href="https://wp-plugins.in/VP-On-Page-Load-Align" target="_blank"><?php _e('Live Demo', 'video-popup'); ?></a>.</span>
            </label>
            <br>
            <label title="<?php esc_attr_e('Align the video to the right. This option for the Premium Extension.', 'video-popup'); ?>">
                <input type="radio" value="2" disabled><span style="color:#999 !important;"><?php _e('Right (Premium).', 'video-popup'); ?></span>
            </label>
            <br>
            <label title="<?php esc_attr_e('The Pop-up video will be displayed with the default design.', 'video-popup'); ?>">
                <input type="radio" value="3" checked><?php _e('Disable Alignment.', 'video-popup'); ?>
            </label>

            <p><span style="color:#999 !important;"><?php _e("Tip: If you want to align the video on left or the right, it is recommended to use a small size of width and height (for example 440x220) in the Design Options or leave the Width and Height fields blank, and removing the White Border if you want, or change the Color of Overlay.<br>You can also use the YouTube Options with the alignment feature. All options on this page are compatible together.", 'video-popup'); ?></span></p>
     </fieldset>
    <?php
}
add_action('vp_al_op_align_options_cb_action', 'vp_al_op_align_options_cb_free');


function vp_al_op_youtube_options_callback(){
    do_action('vp_al_op_yt_options_cb_action');
}


function vp_al_op_youtube_options_cb_free(){
    $mute = get_option('vp_al_op_yt_mute');
    ?>
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('YouTube Options', 'video-popup'); ?></span></legend>
            <label title="<?php esc_attr_e('Mute the sound of video. This option for YouTube only.', 'video-popup'); ?>">
                <input type="checkbox" value="1" name="vp_al_op_yt_mute" <?php checked($mute, 1, true); ?>><span><?php _e('Mute', 'video-popup'); ?>.</span>
            </label>
            <br>
            <label title="<?php esc_attr_e('The behavior for the rel parameter is changing on or after September 25, 2018. The effect of the change is that you will not be able to disable related videos. Prior to the change, if you disabled the related videos, then the player does not show related videos. After the change, if you disabled the related videos, the player will show related videos that are from the same channel. This option for YouTube only. This option for the Premium Extension.', 'video-popup'); ?>">
                <input type="checkbox" value="1" disabled="true"><span style="color:#999 !important;"><?php _e('Disable Related Videos (Premium)', 'video-popup'); ?>.</span>
            </label>
            <br>
            <label title="<?php esc_attr_e('Disable YouTube player controls. This option for YouTube only. This option for the Premium Extension.', 'video-popup'); ?>">
                <input type="checkbox" value="1" disabled="true"><span style="color:#999 !important;"><?php _e('Disable Controls (Premium)', 'video-popup'); ?>.</span>
            </label>
            <br>
            <label title="<?php esc_attr_e('Disable video annotations. This option for YouTube only. This option for the Premium Extension.', 'video-popup'); ?>">
                <input type="checkbox" value="1" disabled="true"><span style="color:#999 !important;"><?php _e('Disable Annotations (Premium)', 'video-popup'); ?>.</span>
            </label>
            <br>
            <label title="<?php esc_attr_e('Enter the starting time for the video, for example enter "90" (1 minute + 30 seconds = 90), the video will be played in "1:30". Numbers only. This option for YouTube only. This option for the Premium Extension.', 'video-popup'); ?>">
                <span style="color:#999 !important;"><?php _e('Starting Time (Premium)', 'video-popup'); ?>:</span> <input type="text" style="width:100px;" value="" disabled="true">
            </label>
            <br>
            <label title="<?php esc_attr_e('The time offset at which the video should stop playing. The value is a positive integer that specifies the number of seconds into the video that the player stops playback. For example enter "90" (1 minute + 30 seconds = 90), now the video will be stopped playing in "1:30". This option for YouTube only. This option for the Premium Extension.', 'video-popup'); ?>">
                <span style="color:#999 !important;"><?php _e('Ending Time (Premium)', 'video-popup'); ?>:</span> <input type="text" style="width:100px;" value="" disabled="true">
            </label>
     </fieldset>
    <?php
}
add_action('vp_al_op_yt_options_cb_action', 'vp_al_op_youtube_options_cb_free');


function vp_al_op_design_options_callback(){
    do_action('vp_al_op_design_options_cb_action');
}


function vp_al_op_design_options_cb_free(){
    $dis_d_border = get_option('vp_al_op_d_remove_border');
    ?>
    <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Design Options', 'video-popup'); ?></span></legend>
            <label for="vp_al_op_d_remove_border"><input type="checkbox" id="vp_al_op_d_remove_border" name="vp_al_op_d_remove_border" value="1" <?php checked($dis_d_border, 1, true); ?>><span><?php _e("Removing the white border.", 'video-popup'); ?></span></label>
            <br>
            <label title="<?php esc_attr_e('Enter width size for the video, for example "1200". Numbers only. This option for the Premium Extension.', 'video-popup'); ?>">
                <span style="color:#999 !important;"><?php _e('Width Size (Premium)', 'video-popup'); ?>:</span> <input type="text" style="width:100px;" value="" disabled="true">
            </label>
            <br>
            <label title="<?php esc_attr_e('Enter height size for the video, for example "600". Numbers only. This option for the Premium Extension.', 'video-popup'); ?>">
                <span style="color:#999 !important;"><?php _e('Height Size (Premium)', 'video-popup'); ?>:</span> <input type="text" style="width:100px;" value="" disabled="true">
            </label>
            <br>
            <label title="<?php esc_attr_e('Enter the color of overlay, enter HEX code only, for example "#ffffff". Enter full HEX code such as "#ffffff", not shortened such as "#fff". Default is black "#000000". This option for the Premium Extension.', 'video-popup'); ?>">
                <span style="color:#999 !important;"><?php _e('Color of Overlay (Premium)', 'video-popup'); ?>:</span> <input type="text" style="width:100px;" value="" disabled="true">
            </label>
     </fieldset>
    <?php
}
add_action('vp_al_op_design_options_cb_action', 'vp_al_op_design_options_cb_free');


function vp_gs_op_remove_boder_callback(){
    $dis_border = get_option('vp_gs_op_remove_boder');
    ?>
        <label for="vp_gs_op_remove_boder" style="display:block;"><input type="checkbox" id="vp_gs_op_remove_boder" name="vp_gs_op_remove_boder" value="1" <?php checked($dis_border, 1, true); ?>><?php _e("Removing the white border.", 'video-popup'); ?></label>
    <?php
}


function vp_gs_op_width_size_callback(){
    do_action('vp_gs_op_width_size_cb_action');
}


function vp_gs_op_width_size_cb_free(){
    ?>
        <label title="<?php echo esc_attr__('Width Size (Premium)', 'video-popup'); ?>" style="display:block !important;"><input type="text" value="" disabled="true"><p style="color:#999 !important;" class="description"><?php _e('Enter width size for the video, for example "1200". Numbers only. This option for the Premium Extension.', 'video-popup'); ?></p></label>
    <?php
}
add_action('vp_gs_op_width_size_cb_action', 'vp_gs_op_width_size_cb_free');


function vp_gs_op_height_size_callback(){
    do_action('vp_gs_op_height_size_cb_action');
}


function vp_gs_op_height_size_cb_free(){
    ?>
        <label title="<?php echo esc_attr__('Height Size (Premium)', 'video-popup'); ?>" style="display:block !important;"><input type="text" value="" disabled="true"><p style="color:#999 !important;" class="description"><?php _e('Enter height size for the video, for example "600". Numbers only. This option for the Premium Extension.', 'video-popup'); ?></p></label>
    <?php
}
add_action('vp_gs_op_height_size_cb_action', 'vp_gs_op_height_size_cb_free');


function vp_gs_op_overlay_color_callback(){
    do_action('vp_gs_op_overlay_color_cb_action');
}


function vp_gs_op_overlay_color_cb_free(){
    ?>
        <label title="<?php echo esc_attr__('Color of Overlay (Premium)', 'video-popup'); ?>" style="display:block !important;"><input type="text" value="" disabled="true"><p style="color:#999 !important;" class="description"><?php _e('Enter the color of overlay, enter HEX code only, for example "#ffffff". Enter full HEX code such as "#ffffff", not shortened such as "#fff". Default is black "#000000". This option for the Premium Extension.', 'video-popup'); ?></p></label>
    <?php
}
add_action('vp_gs_op_overlay_color_cb_action', 'vp_gs_op_overlay_color_cb_free');