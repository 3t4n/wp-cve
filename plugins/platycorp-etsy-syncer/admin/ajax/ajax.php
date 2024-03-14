<?php
use platy\etsy\EtsySyncer;
use platy\etsy\EtsyDataService;

function platy_syncer_posts_from_wp_query(){
    global $wp_query;
    add_filter("edit_posts_per_page", function($ppp){
        return -1;
    });
    wp_edit_posts_query();
    $posts = $wp_query->posts;
    $ret = [];
    foreach($posts as $post){
        
        $ret[] = is_int($post) ? $post : $post->ID;
    }
    return $ret;
}

add_action('admin_init', function(){
    global $typenow;
    if($_REQUEST['action'] == 'platy-syncer-etsy' && $_REQUEST['plty-select-everything']){
        // this enables woocommerce filters to apply
        // such as search by sku, etc...
        $typenow = "product";
        set_current_screen( "edit-product" );
    }
        
});

add_action( "wp_ajax_platy-syncer-etsy", function(){
    if(!check_ajax_referer( "platy-syncer", "nonce" )){
        wp_die("couldnt verify nonce");
    }

    
    $syncer = new EtsySyncer();
    $data = EtsyDataService::get_instance();

    if(!$data->has_current_shop()){
        wp_send_json_error( ["ERROR" => "No etsy shop set"], 400);
    }

    if(!$data->is_shop_authenticated()){
        wp_send_json_error( ["ERROR" => "Please reauthenticate your shop"], 400);
    }

    /**
     * first invalidate the database to make sure the
     * user hasnt deleted anything important
     */
    try{
        $syncer->invalidate();
    }catch(Exception $e){
        wp_send_json_error( ["ERROR" => $e->getMessage()], 400);
    }

    if(!$data->has_default_shipping_template()){
        wp_send_json_error( ["ERROR" => "No default shipping template found"], 400);
    }

    if(!$data->has_default_taxonomy()){
        wp_send_json_error( ["ERROR" => "No default etsy category found"], 400);
    }
    
    if($_REQUEST['plty-select-everything']){
        $posts = platy_syncer_posts_from_wp_query();
    }else{
        $posts = empty($_REQUEST['post']) ? [] : array_filter($_REQUEST['post'], function($post){return $post;});
    }
    $ret = [];
    foreach($posts as $post_id){
        $product = wc_get_product($post_id);
        $ret[] = [
            'id' => $post_id , 
            "title" => $product->get_title(),
             "icon" => get_the_post_thumbnail_url($post_id), 
            'edit_link' => get_edit_post_link($post_id, "etsy")
        ];
    }

    wp_send_json( $ret ) ;
} );