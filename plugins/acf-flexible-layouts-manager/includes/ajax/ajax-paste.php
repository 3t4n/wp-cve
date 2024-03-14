<?php
add_action('wp_ajax_acf_flm_update_template_with_pasted_layout',           'acf_flm_update_template_with_pasted_layout');
add_action('wp_ajax_nopriv_acf_flm_update_template_with_pasted_layout',    'acf_flm_update_template_with_pasted_layout');
function acf_flm_update_template_with_pasted_layout(){

    if( (!isset($_POST['post_id'])) || (!isset($_POST['comportement'])) || (!isset($_POST['paste-code'])) || (!isset($_POST['flexible'])) || (!isset($_POST['key'])) )
        wp_send_json_error();

    if( (!$post_id = sanitize_key($_POST['post_id'])) || (!$comportement = sanitize_key($_POST['comportement'])) || (!$flexible = sanitize_key($_POST['flexible'])) || (!$key = sanitize_key($_POST['key'])) )
        wp_send_json_error();

    remove_filter('acf_the_content', 'do_shortcode', 11);
    
    if(!$data_actuel = get_field($flexible, $post_id, false))
        $data_actuel = false;

    $layouts = $_POST['paste-code'];
    
    if(!$data = json_decode(stripslashes($layouts), TRUE))
        wp_send_json_error();

    if( ($comportement == "add") && $data_actuel ){
        if(!update_field($flexible, array_merge($data_actuel, $data), $post_id))
            wp_send_json_error();
    }else {
        if(!update_field($flexible, $data, $post_id))
            wp_send_json_error();
    }

    wp_send_json_success("OK");
}
