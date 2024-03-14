<?php
// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Cleanup saved options
delete_option('zbp_version');
delete_option('zbp_width');
delete_option('zbp_autostart');
delete_option('zbp_loop');
delete_option('zbp_download');
delete_option('zbp_id3');
delete_option('zbp_collect_mp3');
delete_option('zbp_initialvolume');
delete_option('zbp_show_name');
delete_option('zbp_animation');
delete_option('zbp_collect_field');
delete_option('zbp_native_mobile');
delete_option('zbp_native_desktop');

delete_option('zbp_bg_color');
delete_option('zbp_bg_left_color');
delete_option('zbp_icon_left_color');
delete_option('zbp_voltrack_color');
delete_option('zbp_volslider_color');
delete_option('zbp_bg_right_color');
delete_option('zbp_bg_right_hover_color');
delete_option('zbp_icon_right_color');
delete_option('zbp_icon_right_hover_color');
delete_option('zbp_loader_color');
delete_option('zbp_track_color');
delete_option('zbp_tracker_color');
delete_option('zbp_border_color');
delete_option('zbp_skip_color');
delete_option('zbp_text_color');

// old / already deleted options
delete_option('zbp_show_share');
delete_option('zbp_share');
