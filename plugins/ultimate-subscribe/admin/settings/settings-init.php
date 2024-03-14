<?php 
function ultimate_subscribe_popup_settings(){
    require plugin_dir_path( __FILE__ ).'/sections/popup-options.php';
}

function ultimate_subscribe_social_settings(){
    require plugin_dir_path( __FILE__ ).'/sections/social-options.php';
}

function ultimate_subscribe_general_settings(){
    
}
function ultimate_subscribe_mail_settings(){
    require plugin_dir_path( __FILE__ ).'/sections/mail-options.php';
}

function ultimate_subscribe_form_settings(){
    require plugin_dir_path( __FILE__ ).'/sections/form-options.php';
}

function ultimate_subscribe_api_settings(){
    require plugin_dir_path( __FILE__ ).'/sections/api-options.php';
}



add_action('ultimate_subscribe_option_settings', 'ultimate_subscribe_general_settings');
add_action('ultimate_subscribe_option_settings', 'ultimate_subscribe_social_settings');
add_action('ultimate_subscribe_option_settings', 'ultimate_subscribe_popup_settings');
add_action('ultimate_subscribe_option_settings', 'ultimate_subscribe_mail_settings');
add_action('ultimate_subscribe_option_settings', 'ultimate_subscribe_form_settings');
add_action('ultimate_subscribe_option_settings', 'ultimate_subscribe_api_settings');




?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <div class="ultimate-subscribe-panel">
        <ul class="panel-nav-tabs" role="tablist">
            <li id="form-tabc" class="active"><a href="#form-options" class="tab-heading" role="tab" data-toggle="tab"><?php esc_html_e( 'Subscriber Form Settings','ultimate-subscribe'); ?></a></li>
            <li id="mail-tabc"><a href="#mail-options" class="tab-heading" role="tab" data-toggle="tab"><?php esc_html_e( 'Mail Options','ultimate-subscribe'); ?></a></li>
            <li id="popup-tabc"><a href="#popup-options" class="tab-heading" role="tab" data-toggle="tab"><?php esc_html_e( 'Popup Options','ultimate-subscribe'); ?></a></li>
            <li id="social-tabc"><a href="#social-options" class="tab-heading" role="tab" data-toggle="tab"><?php esc_html_e( 'Social Options','ultimate-subscribe'); ?></a></li>
            <li id="api-tabc"><a href="#api-options" class="tab-heading" role="tab" data-toggle="tab"><?php esc_html_e( 'API Options','ultimate-subscribe'); ?></a></li>
        </ul>
        <div class="panel-tab-content">
            <form action="options.php" method="post">
                <?php 
                    settings_fields('ultimate-subscribe-options');
                    do_settings_sections('ultimate-subscribe-options');
                ?>
                <?php 
                    do_action('ultimate_subscribe_option_settings');
                ?>
                <div class="field-submit">
                    <?php submit_button('Save Settings'); ?>
                </div>
            </form>
        </div>
    </div>
</div>