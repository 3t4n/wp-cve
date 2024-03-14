<?php
/**
 * API Save design settings
 */
add_action("wp_ajax_ms_wp_save_design_settings", "ms_wp_save_design_settings");

function ms_wp_save_design_settings() {
    ms_protect_ajax_route();
    $designOptions = ms_get_options();
    foreach (DESIGN_OPTIONS as $DESIGN_OPTION){
        if(isset($_REQUEST[$DESIGN_OPTION])){
            $designOptions[$DESIGN_OPTION] = $_REQUEST[$DESIGN_OPTION];
        }
    }
    msSaveDesignOptions($designOptions);
    print_r(json_encode(["success" => true]));
    wp_die();
}
add_action("wp_ajax_ms_wp_get_design_settings", "ms_wp_get_design_settings");

function ms_wp_get_design_settings() {
    ms_protect_ajax_route();

    print_r(json_encode(["success" => true, "options" => msGetDesignOptions()]));
    wp_die();
}