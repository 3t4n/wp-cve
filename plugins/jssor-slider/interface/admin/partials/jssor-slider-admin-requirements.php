<?php
// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();
?>
<!-- Requirements & Recommendations -->
<div data-u="jssor-slider-requirements-view" class="wjssc-dash-block wjssl-requirements-widget" style="display:none;">
    <?php
$dir = wp_upload_dir();
$upload_max_filesize = ini_get('upload_max_filesize');
$upload_max_filesize_byte = WP_Jssor_Slider_Utils::get_upload_max_filesize_byte();
$post_max_size = ini_get('post_max_size');
$post_max_size_byte = WP_Jssor_Slider_Utils::get_post_max_size_byte();

$writeable_boolean = WP_Jssor_Slider_Utils::get_upload_folder_writable();
$can_connect = true;
$upload_max_filesize_byte_boolean = ($upload_max_filesize_byte < WP_Jssor_Slider_Globals::REQUIREMENTS_MIN_UPLOAD_FILE_SIZE); //2M
$post_max_size_byte_boolean = ($post_max_size_byte < WP_Jssor_Slider_Globals::REQUIREMENTS_MIN_POST_FILE_SIZE);  //8M
$dash_rr_status = ($writeable_boolean==true && $can_connect==true && $upload_max_filesize_byte_boolean==false && $post_max_size_byte_boolean==false) ? 'wjssc-status-ok' : 'wjssc-status-problem';

$has_gd = WP_Jssor_Slider_Utils::get_gd_library_installed();

    ?>
    <div data-u="requirements-view-title" class="wjssc-dash-title <?php echo esc_attr($dash_rr_status); ?>">
        <div class="wjssc-dash-title-text">
            <?php _ex("System Requirements","noun", 'jssor-slider'); ?>
        </div>
        <div class="wjssc-dash-title-signal wjssc-dash-title-signal-problem">
            <span class="wjssc-iconm wjssc-iconm-problem"></span>&nbsp;&nbsp;<?php _e("Problem Found",'jssor-slider'); ?>
        </div>
        <div class="wjssc-dash-title-signal wjssc-dash-title-signal-ok">
            <span class="wjssc-iconm wjssc-iconm-ok"></span>&nbsp;&nbsp;
            <span class="wjssc-dash-title-signal-text">
                <?php _e("No Problems",'jssor-slider'); ?>
            </span>
        </div>
    </div>

    <!-- check if uploads folder can be written into -->
    <div class="wjssc-dash-content">

        <!-- uploads folder writable -->
        <div class="wjssc-dash-label" style="width:195px;">
            <?php _e('Uploads folder writable', 'jssor-slider'); ?>
        </div>
        <div data-u="uploads-folder-writable-status-icon" class="wjssc-icons <?php echo $writeable_boolean ? 'wjssc-icons-correct' : 'wjssc-icons-error' ?>"></div>

        <!-- check php GD library -->
        <div style="height:3px;"></div>
        <div class="wjssc-dash-label" style="width:195px;">
            <?php _ex('PHP GD library is installed', "GD is a PHP library.", 'jssor-slider'); ?>
        </div>
        <div data-u="gd-library-installed-status-icon" class="wjssc-icons <?php echo $has_gd ? 'wjssc-icons-correct' : 'wjssc-icons-error' ?>"></div>

        <!-- check media browser accessibility -->
        <div style="display:none;height:3px;"></div>
        <div class="wjssc-dash-label" style="display:none;width:195px;">
            <?php _e('Media Browser Accessibility', 'jssor-slider'); ?>
        </div>
        <div data-u="media-browser-accessibility-status-icon" class="wjssc-icons wjssc-icons-correct" style="display:none;"></div>
        <span data-u="media-browser-accessibility-status-notice" class="wjssc-dash-emphasis" style="display:none;margin-left:20px;">
            <a data-u="command" data-command="media_browser_accessibility_fix_problem" href="#"><?php _ex("Fix Problem", "noun", 'jssor-slider'); ?></a>
        </span>

        <!-- check max upload file size -->
        <div style="height:3px;"></div>
        <span class="wjssc-dash-label" style="width:195px;">
            <?php _ex('Upload Max. Filesize', "noun", 'jssor-slider'); ?>
        </span>
        <div data-u="upload-max-file-size-status-icon" class="wjssc-icons <?php echo !$upload_max_filesize_byte_boolean ? 'wjssc-icons-correct' : 'wjssc-icons-error' ?>"></div>
        <span class="wjssc-dash-emphasis" style="margin-left:20px">
            <?php printf(__('Currently: %d', 'jssor-slider'), $upload_max_filesize); ?>
        </span>
        <span data-u="upload-max-file-size-status-notice" class="wjssc-dash-highlight" style="margin-left:20px;<?php echo !$upload_max_filesize_byte_boolean ? 'display:none;' : '' ?>">(min:2M)</span>


        <!-- check max post size -->
        <div style="height:3px;"></div>
        <span class="wjssc-dash-label" style="width:195px;">
            <?php _ex('Max. Post Size', "noun", 'jssor-slider'); ?>
        </span>
        <div data-u="post-max-size-status-icon" class="wjssc-icons <?php echo !$post_max_size_byte_boolean ? 'wjssc-icons-correct' : 'wjssc-icons-error' ?>"></div>
        <span class="wjssc-dash-emphasis" style="margin-left:20px">
            <?php printf(__('Currently: %d', 'jssor-slider'), $post_max_size); ?>
        </span>
        <span data-u="post-max-size-status-notice" class="wjssc-dash-highlight" style="margin-left:20px;<?php echo !$post_max_size_byte_boolean ? 'display:none;' : '' ?>">(min:8M)</span>
        
        <!-- check www.jssor.com server connection -->
        <div style="height:3px;"></div>
        <span class="wjssc-dash-label" style="width:195px;">
            <?php _ex('Contact www.jssor.com Server', "a section text", 'jssor-slider'); ?>
        </span>
        <div data-u="connect-jssor-com-server-status-icon" class="wjssc-icons <?php echo $can_connect ?  'wjssc-icons-correct' : 'wjssc-icons-error'?>"></div>
        <span data-u="command" data-command="connect_jssor_com_server" class='wjssc-dash-detect-btn' style="margin-left:16px">
            <a href="#"><?php _ex("Check Now", 'verb', 'jssor-slider'); ?></a>
        </span>
    </div>
</div>
<!-- END OF Requirements & Recommendations -->
