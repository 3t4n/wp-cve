<?php
/**
 * Created by PhpStorm.
 * User: Gena
 * Date: 18.01.2019
 * Time: 14:23
 */
function dm_is_base64_encoded( $data ) {

    return (bool) ( preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data) );
};
function dm_comments_array( $comments_flat, $post_id ) {

    if( is_array( $comments_flat ) && count( $comments_flat ) ) {

        global $wpdb;

        $ids = [];
        foreach( $comments_flat as $item ) {

            $ids[] = $item->comment_ID;
        }

        $images = $wpdb->get_results(
            "SELECT * FROM {$wpdb->commentmeta} 
            WHERE meta_key = 'images' AND comment_id IN (" . implode(',', $ids) . ")"
        );

        $foo = [];

        if( $images ) foreach( $images as $image ) {
            $foo[ $image->comment_id ] = $image->meta_value;
        }

        $review = new \dm\dmReview();
        if( count($foo) ) foreach( $comments_flat as $i => $item ) {

            $layout  = '';
            $gallery = isset( $foo[ $item->comment_ID ] ) ? $review->get_gallery( $foo[ $item->comment_ID ], 'thumbnail' ) : false;

            if ( $gallery ) {

                $layout .= '<ul class="dm-gallery" style="display:flex">';

                foreach ( $gallery as $image )
                    $layout .= '<li style="margin-left:15px;list-style:none;"><a href="' . $image[ 'url' ] .'" data-lightbox="image-' . $item->comment_ID . '">'.
                        '<img src="' . $image[ 'thumbnail' ] . '" style="max-height:50px;" /></a></li>';

                $layout .= '</ul>';
            }

            $comments_flat[ $i ]->comment_content = $item->comment_content . $layout;
        }
    }
    return $comments_flat;
}

add_filter( 'comments_array', 'dm_comments_array', 10, 2 );