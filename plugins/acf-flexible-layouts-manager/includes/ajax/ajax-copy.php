<?php
add_action('wp_ajax_acf_flm_get_all_layout_templates',         'acf_flm_get_all_layout_templates');
add_action('wp_ajax_nopriv_acf_flm_get_all_layout_templates',  'acf_flm_get_all_layout_templates');
function acf_flm_get_all_layout_templates(){

    if( (!isset($_POST['postid'])) || (!isset($_POST['flexible'])) )
        wp_send_json_error();

    if( (!$post_id = sanitize_key($_POST['postid'])) || (!$flexible = sanitize_key($_POST['flexible'])) )
        wp_send_json_error();

    remove_filter('acf_the_content', 'do_shortcode', 11);
    
    if(!$data = get_field($flexible, $post_id, false))
        wp_send_json_error();

    wp_send_json_success($data);
}