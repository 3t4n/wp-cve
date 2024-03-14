<?php
add_action('wp_ajax_acf_flm_update_with_custom_layout_selected',           'acf_flm_update_with_custom_layout_selected');
add_action('wp_ajax_nopriv_acf_flm_update_with_custom_layout_selected',    'acf_flm_update_with_custom_layout_selected');
function acf_flm_update_with_custom_layout_selected(){

    if( (!isset($_POST['post_id_cible'])) || (!isset($_POST['post_id_current'])) || (!isset($_POST['comportement'])) || (!isset($_POST['layout'])) || (!isset($_POST['flexible_content'])) )
        wp_send_json_error();

    if( (!$post_id_cible = sanitize_key($_POST['post_id_cible'])) || (!$post_id_current = sanitize_key($_POST['post_id_current'])) || (!$comportement = sanitize_key($_POST['comportement'])) || (!$flexible = sanitize_key($_POST['flexible_content'])) )
        wp_send_json_error();

    remove_filter('acf_the_content', 'do_shortcode', 11);

    if(!$data_actuel = get_field($flexible, $post_id_current, false))
        $data_actuel = false;

    if(!$data_cible = get_field($flexible, $post_id_cible, false))
        wp_send_json_error();

    $data = array();
    $layouts = $_POST['layout'];

    if(!is_array($layouts))
        wp_send_json_error();

    foreach($layouts as $layout):
        $data[] = $data_cible[$layout];
    endforeach;

    if( ($comportement == "add") && $data_actuel ){
        if(!update_field($flexible, array_merge($data_actuel, $data), $post_id_current))
            wp_send_json_error();
    }else {
        if(!update_field($flexible, $data, $post_id_current))
            wp_send_json_error();
    }

    wp_send_json_success('OK');
}

add_action('wp_ajax_acf_flm_get_all_posts_how_contains_current_flexible', 			'acf_flm_get_all_posts_how_contains_current_flexible');
add_action('wp_ajax_nopriv_acf_flm_get_all_posts_how_contains_current_flexible', 	'acf_flm_get_all_posts_how_contains_current_flexible');
function acf_flm_get_all_posts_how_contains_current_flexible(){


    if( (!isset($_POST['flexible'])) || (!isset($_POST['key'])) )
        wp_send_json_error();

    if( (!$flexible = sanitize_key($_POST['flexible'])) || (!$key = sanitize_key($_POST['key'])) )
        wp_send_json_error();

    $posts = get_posts(array(
        "posts_per_page" => -1,
        "post_type" => 'any',
        "fields"    => 'ids',
        /*"post__not_in" => array(get_the_ID()),*/
        'meta_query' => array(
            array(
                'key'     => $flexible,
                'value'   => false,
                'compare' => '!=',
            ),
        ),
    ));

    if(!$posts)
        wp_send_json_error();


    $data = array();

    foreach($posts as $post):
        $data[] = array(
            "post_id" => $post,
            "post_title" => get_the_title($post)
        );
    endforeach;

    wp_send_json_success($data);

}