<?php
add_action('wp_ajax_acf_flm_layout_duplicate',         'acf_flm_layout_duplicate');
add_action('wp_ajax_nopriv_acf_flm_layout_duplicate',  'acf_flm_layout_duplicate');
function acf_flm_layout_duplicate(){
    
    if( (!isset($_POST['position'])) || (!isset($_POST['post_id'])) || (!isset($_POST['flexible'])) )
        wp_send_json_error();

    $position 	= $_POST['position'];

    if( (!is_numeric($position)) ||  (!$post_id = sanitize_key($_POST['post_id'])) || (!$flexible = sanitize_key($_POST['flexible'])) )
        wp_send_json_error();

    remove_filter('acf_the_content', 'do_shortcode', 11);
    
    if(!$data = get_field($flexible, $post_id, false))
        wp_send_json_error();

    $data[] = $data[$position];

    if(!update_field($flexible, $data, $post_id))
        wp_send_json_error();

    wp_send_json_success("OK");

}