<?php

//return id
function isSeoActiveOnPostType(){
    global $post;

    $active_post_types = sseo_get_active_post_type();
    
    /*
    echo $post->post_type;
    echo "a";
    echo is_singular();
    echo "b";
    echo is_category();
    echo "c";
    echo is_page();
    echo "d";
    echo is_shop();
    echo "e";
    echo is_tag() ;
    echo "f";
    echo is_post_type_archive($post->post_type);
    echo "g";
    echo is_post_type_archive('post');
    echo "h";
    echo is_home();
    echo "j";
    echo is_product_category();
    echo "k";
    //print_r(  get_queried_object());
    echo "l";
    echo is_tax();
    */

    $id = -1;

    if ($post === null){
        $returnVal=new stdClass();
        $returnVal->type= null;
        $returnVal->id= $id;
        return $returnVal;
    }
    
    $type = is_tax() ? "tax" : "post";
    
    if (in_array($post->post_type, $active_post_types)) {

    switch ($post->post_type){

        // Posts
        case "post" :
        if (is_category()) $type = "tax";
        if (is_singular()) $id = $post->ID;
        if (!is_singular()) $id = get_queried_object_id();
        
        break;

        //product
        case "product" :
        if (is_shop())$id = woocommerce_get_page_id('shop');
        if (!is_singular()) $id = get_queried_object_id();
        break;

        // Page Types
        case "page" :
        $id = get_queried_object_id();
        break;

        // Custom Post Types
        default:
            if (is_singular()) $id = $post->ID;
            if (is_post_type_archive($post->post_type))$id = -1;
            if (is_tax())$id = get_queried_object_id();
        }
    }
    $returnVal=new stdClass();
    $returnVal->type= $type;
    $returnVal->id= $id;
    return $returnVal;
}


// GET POST TYPES
//-----------------------------------------------------------------------

function sseo_get_post_types() {
    $post_types = get_post_types( array( 'public' => true, 'publicly_queryable' => true), 'objects', 'or' );
    foreach($post_types as $key => $type){
    if($type->name === 'attachment') { // Removed attachments
        unset($post_types[$key]);
        }
    }
    return $post_types;
}
    
function sseo_get_active_post_type() {
    $sseo_post_types = sseo_get_post_types();
    $active_post_types = array();
    if ( $sseo_post_types ) {
    foreach ( $sseo_post_types as $post_type ) {
    $option_name = 'sseo_activate_type_'.$post_type->name;
    $isSet = esc_attr(get_option( $option_name ));
    if($isSet){
    array_push($active_post_types, $post_type->name);
    }
    }
    }
    return $active_post_types;
}
    

?>