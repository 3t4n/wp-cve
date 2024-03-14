<?php
function lgcrm_is_send_to_crm(){
    return lgcrm_get_setting('send_to_crm');
}

function lgcrm_get_default_cf7_post_settings(){
    $wsl_settings = lgcrm_get_settings();
    if(is_array($wsl_settings)){
        return $wsl_settings;
    }else{
        return array(
            'send_to_crm' => 1,
            'sent_to_company' => 0
        );
    }
}

function lgcrm_get_cf7_post_settings($post_id){
    $meta = get_post_meta($post_id,'_wsl_settings',TRUE);
    //var_dump($meta);
    if(!is_array($meta)){
        return lgcrm_get_default_cf7_post_settings();
    }
    return array_merge(lgcrm_get_default_cf7_post_settings(),$meta);
}

function lgcrm_get_cf7_post_setting($name,$post_id){
    $meta = lgcrm_get_cf7_post_settings($post_id);
    if(isset($meta[$name])){
        return $meta[$name];
    }else{
        return false;
    }
}

function lgcrm_get_default_settings(){
    $default = array(
        'api_key' => '',
        'send_to_crm' => 1,
        'send_to_company' => 0,
    );
    return $default;
}

function lgcrm_get_settings(){
    $settings = get_option('wsl_settings',lgcrm_get_default_settings());
    return $settings;
}

function lgcrm_update_setting($name,$value){
    $settings = get_option('wsl_settings',lgcrm_get_default_settings());
    $settings[$name] = $value;
    update_option('wsl_settings', $settings);
}

function lgcrm_get_setting($name){
    $settings = get_option('wsl_settings',lgcrm_get_default_settings());
    return isset($settings[$name])?$settings[$name] : false;
}

function lgcrm_check_default_company_exists($companies){
    $company_websites = array_column(array_map('get_object_vars', $companies), 'website','id');
    $id = array_search(site_url(), $company_websites);
    if($id){
        return $id;
    }else{
        return 0;
    }
}

function lgcrm_typeform_script() {
    wp_register_script( "my_custom_script", plugin_dir_url( __FILE__ ).'assets/custom.js', array('jquery'), NULL, TRUE );
    wp_localize_script( 'my_custom_script', 'localize', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'my_custom_script' );
}
add_action( 'wp_enqueue_scripts', 'lgcrm_typeform_script' );

function lgcrm_typeform_api($response_id){

    $get_settings = lgcrm_get_settings();
    $token = $get_settings["typeform_api_key"];

    $api_obj = new Wsl_Api();
    $call_init = $api_obj->callTypeFormAuth('https://api.typeform.com/forms', 'Bearer ' . $token, $method = 'GET');

    $getItems = json_decode(json_encode($call_init), true);

    foreach($getItems["items"] as $key=>$v){
        $ch = $api_obj->callTypeFormAuth('https://api.typeform.com/forms/'.$v["id"].'/responses?included_response_ids='.$response_id, 'Bearer ' . $token, $method = 'GET');
        $totalItems = json_decode(json_encode($ch), true);
        if($totalItems["total_items"] > 0){
            $data_items = $totalItems["items"];
            return $data_items;
        }else{
            return 'Response: false';
        }
    }
}

function lgcrm_sanitize_data($data){
    if(is_array($data)){
        foreach($data as $key=>$rec){
            if(!is_array($rec)){
                $data[$key] = sanitize_text_field($rec);
            }else{
                $data[$key] = $rec;
            }
        }
        return $data;
    }else{
        return sanitize_text_field($data);
    }
    

}

