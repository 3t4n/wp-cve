<?php

require_once plugin_dir_path(dirname(__FILE__)) . './includes/class-appointy-helper-functions.php';


add_action('wp_ajax_handle_appointy_post_message_callback', 'handle_appointy_post_message_callback');
add_action('wp_ajax_handle_setting_change_callback', 'handle_setting_change_callback');


function handle_appointy_post_message_callback()
{

    $helper = new Appointy_helper_functions();
    $plugin_public = new Appointy_appointment_scheduler_Admin('appointy_appointment_scheduler', '3.0.1', $helper);

    if (isset($_POST["code"])) {
        $plugin_public->handle_form_submit();
    }

}

function handle_setting_change_callback()
{
    if (isset($_POST["code"])) {

        // Fetch the data
        $code = esc_url_raw(trim($_POST["code"]));
        $lang = esc_textarea(trim($_POST["lang"]));
        $maxWidth = esc_textarea(trim($_POST["maxWidth"]));
        $maxHeight = esc_textarea(trim($_POST["maxHeight"]));
        $widget = esc_textarea(trim($_POST["widget"]));

        $helper = new Appointy_helper_functions();

        // make the settings string
        $setting = new AppointySettings($helper);
        $valid = $setting->ProcessFormSubmit($code, $lang, $maxWidth, $maxHeight, $widget);

        if ($valid) {
            $setting_str = $setting->GetSettingString();

            // insert into the db
            global $wpdb;
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "appointy_calendar set code = %s", $setting_str));
            $helper->set_iframe_val(str_replace("\\", "", $setting_str));

            print("{'status':true}");
        } else {
            print("{'status':false}");
        }
    }
}

function handle_url_update()
{
    if (isset($_POST["code"])) {
        // Fetch the data
        $code = esc_url_raw(trim($_POST["code"]));

        $helper = new Appointy_helper_functions();

        // make the settings string
        $setting = new AppointySettings($helper);
        $setting->ParseFromSettingString($this->helper->get_iframe_val());

        // If url is valid
        if ($setting->ValidateUrl($code)) {

            $url_components = parse_url($code);
            $setting->url = $url_components["scheme"] . "://" . $url_components["host"] . "/" . $this->RemovePathSlash($url_components["path"]);

            // update it into the database
            $setting_str = $setting->GetSettingString();

            // insert into the db
            global $wpdb;
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "appointy_calendar set code = %s", $setting_str));
            $helper->set_iframe_val(str_replace("\\", "", $setting_str));

            print("{'status':true}");
        } else {
            print("{'status':false}");
        }
    }
}