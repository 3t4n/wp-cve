<?php
add_action( 'wp_ajax_ms_get_media', 'ms_get_media' );


function ms_get_media(){
//    ms_protect_ajax_route();
    $query = array(
        'post_type'      => 'attachment',
//        'post_mime_type' => 'video',
        'post_status'    => 'inherit',
        'posts_per_page' => 30,
        'paged' => isset($_REQUEST["page"]) ? sanitize_text_field($_REQUEST["page"]) : 1,
    );

    $toSearch = isset($_REQUEST["search"]) ? sanitize_text_field($_REQUEST["search"]) : false;

    // Filter query clauses to include filenames.
    if ( $toSearch ) {
        add_filter( 'posts_clauses', '_filter_query_attachment_filenames' );
        $query["s"] = $toSearch;
    }

    $query_images = new WP_Query( $query );
    $images = array();
    foreach ( $query_images->posts as $image ) {
        $imageUrl = wp_get_attachment_image_src($image->ID, "full");
        $thumbnail = wp_get_attachment_image_src($image->ID, "thumbnail");
        $meta = wp_get_attachment_metadata($image->ID);
        $imageToSend = [];
        if(is_array($imageUrl) && count($imageUrl) >= 3){
            $images[$image->ID] = [
                "downloadURL" => $imageUrl[0],
                "thumbnail" => is_array($thumbnail) && isset($thumbnail[0]) ? $thumbnail[0] : $imageUrl[0],
                "alt" => get_post_meta($image->ID, '_wp_attachment_image_alt', TRUE),
                "width" => $imageUrl[1],
                "height" => $imageUrl[2],
                "type" => "image",
             ];
        }else{
            $images[$image->ID] = [
                "downloadURL" => wp_get_attachment_url($image->ID),
                "thumbnail" => get_the_post_thumbnail_url($image->ID),
                "type" => "video",
             ];
        }
    }
    header( 'Content-Type: application/json');
    echo json_encode($images);
    die();
}

add_action("wp_ajax_ms_image_proxy", "ms_image_proxy");
function ms_image_proxy(){
    $url = isset($_REQUEST['url'] ) ? sanitize_url($_REQUEST['url']) : false;
    if (!$url) {
        die('Please, inform URL');
    }
    $imgInfo = getimagesize( $url );
    if (stripos($imgInfo['mime'], 'image/') === false) {
        die('Invalid image file');
    }
    header("Content-type: ".$imgInfo['mime']);
    readfile($url);
    die();
}