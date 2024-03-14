<?php
/**
 * Plugin Name:       AutoFastindex
 * Plugin URI:        https://firstpageranker.com
 * Description:       Make Your indexing faster, Now you can Give your indexing responsibily to us, without overthinking about google search console and bing webster , We are using latest Google and bing plugin for indexing site faster than normal speed.
 * Version:           2.10.7
 * Author:            AutoFastindex
 * License:           GPLv2 or later
 */


if (!defined('ABSPATH')) {
    die("You Can not Access this file directly");
}
//If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}


add_filter(
    'upload_mimes',
    function ($types) {
        return array_merge($types, array('json' => 'text/plain'));
    }
);


function autoin_media_uploader_enqueue()
{
    wp_enqueue_media();
    wp_register_script('media-uploader', plugins_url('media-uploader.js', __FILE__), array('jquery'));
    wp_enqueue_script('media-uploader');
}

add_action('admin_enqueue_scripts', 'autoin_media_uploader_enqueue');

function autoin_send_notification($id, $post_obj)
{

    include('inc/autoindex.php');

}

add_action('publish_post', 'autoin_send_notification', 10, 2);
add_action( 'publish_product',  'autoin_send_notification', 10, 2);

function autoin_register_menu_pages()
{
    add_menu_page('AutoFast Index', 'AutoFast', 'manage_options', 'AutoFastindex', 'autoin_setting', 'dashicons-thumbs-up');
    //  add_submenu_page('my-menu', 'Submenu Page Title', 'Settings', 'manage_options', 'Settings','my' );
    add_submenu_page('AutoFastindex', 'Manual Indexing', 'Manual Indexing', 'manage_options', 'Manual_Indexing', 'autoin_manual');
    add_submenu_page('AutoFastindex', 'Success logs', 'Success Logs', 'manage_options', 'successlogs', 'autoin_successlog');
    add_submenu_page('AutoFastindex', 'Lisense', 'Lisense', 'manage_options', 'lisense', 'autoin_lisense');
    add_submenu_page('AutoFastindex', 'Notices', 'Notices', 'manage_options', 'notice', 'autoin_notice');

}


$upload_dir = wp_upload_dir();
$autoindex_dirname = $upload_dir['basedir'].'/autoindex_do_not_delete_this_file';
if ( ! file_exists( $autoindex_dirname ) ) {
    wp_mkdir_p( $autoindex_dirname );
}


define('autoindex_upload',$autoindex_dirname);


add_action('admin_menu', 'autoin_register_menu_pages');

//notification


function autoin_setting()
{
    add_action('wp-ajax_nopriv_public_ajax_request', 'handle_ajax_request_public');
    include_once('inc/setting.php');

}

include('inc/main.php');


function autoin_successlog()
{

    include('inc/success.php');

}

function autoin_lisense()
{
    include('inc/lisense.php');
}

function autoin_notice()
{
    include('inc/notice.php');

}

function autoin_manual()
{
    include('inc/manualIndex.php');

}

?>