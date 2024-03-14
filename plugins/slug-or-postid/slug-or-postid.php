<?php
/*
Plugin Name: Slug or PostID
Plugin URI: http://unimakura.jp/wordpress/slug-or-postid.html
Description: 投稿記事のURL(PostName)にスラッグまたはPostIDを使用します。(This plugin uses Slug or PostID for PostName.) Windows live writerなどからの投稿に関しては、WordPress3.3以降で対応しています。(If you would like to post from other application for example "windows live writer", you must use to WordPress 3.3 or higher.) 強制的にPostIDを利用する際は、スラッグに「postid」と入力してください。(If you want to use PostId forcibly, please enter "postid" to slug.)
Version: 1.0
Author: unimakura
Author URI: http://unimakura.jp
*/


/**
 * SlugOrPostIdSavePre
 */
function SlugOrPostIdSavePre($data) {
    // Get postID.
    $postID = GetPostID($data);
    
    // If 'post_name' is null or 'postid', put $postID in 'post_name'.
    if ((!$data['post_name'] || preg_match("/^post.?id[\-]?[\d]*$/i", $data['post_name'])) && $postID) {
        $data['post_name'] = $postID;
    }
    return $data;
}

/**
 * SlugOrPostId
 */
function SlugOrPostId($slug) {
    $postID = GetPostID();
    $slug = ($postID && !$slug) ? $postID : $slug;
    return $slug;
}

/**
 * GetPostID
 */
function GetPostID($data=null) {
    $postID = null;
    if (isset($_POST['post_ID'])) {
        $postID = $_POST['post_ID'];
    }
    // If it can't get $postID, attempts to get it from $data['guid'].
    else if (is_array($data) && 
             isset($data['guid']) && 
             preg_match('/p=([0-9]+)$/', $data['guid'], $matches)) {
        $postID = $matches[1];
    }
    return $postID;
}

// Add filter to WordPress
add_filter('name_save_pre', 'SlugOrPostId');
add_filter('wp_insert_post_data','SlugOrPostIdSavePre');
?>
