<?php

// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

if (defined('CCPUZ_DEBUG'))
    ccpuz_log(">metabox.php");

function ccpuz_applet() {
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log(">ccpuz_applet");
    $filename = 'crosswordCompiler.js';
    $upload_dir = wp_upload_dir();
    $file = $upload_dir['basedir'] . '/ccpuz/' . $filename;
    $uploadVersion = true; //file_exists($file);

    if ($uploadVersion) {
        //upload with overwrite to latest if user has already uploaded the applet file to server
        $filename = 'crosswordCompiler.js';
        $response = wp_remote_get('https://uk.wordwebsoftware.com/applet/' . $filename . '?v11');
        $js = wp_remote_retrieve_body($response);
        $path = $upload_dir['basedir'] . '/ccpuz';

        if (!empty($js) && wp_mkdir_p($path)) {
            @file_put_contents($file, $js);
        } else {
            if (defined('CCPUZ_DEBUG'))
                ccpuz_log("<ccpuz_applet ERROR file = $file");
            wp_die('Could not upload applet file', '<strong>Error</strong>: Could not upload applet file');
        }
    }
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log("<ccpuz_applet file = $file");
}

add_action('save_post', 'ccpuz_save_postdata');

function ccpuz_save_postdata($post_id) {

    if (defined('CCPUZ_DEBUG'))
        ccpuz_log(">ccpuz_save_postdata post_id = $post_id");


    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (isset($wp_query->query['post_type'])) {
        $post_type = $wp_query->query['post_type'];

        if ($post_type == 'page') {
            if (!current_user_can('edit_page', $post_id->ID))
                return;
        } else {
            if (!current_user_can('edit_post', $post_id->ID))
                return;
        }
    }

    if (get_post_type($post_id) == 'post' || get_post_type($post_id) == 'page') {

        $should_be = 0;
        if (substr_count(get_post($post_id)->post_content, '[crossword]') > 0) {
            $should_be = 1;
        }
        if (defined('CCPUZ_DEBUG'))
            ccpuz_log("1.ccpuz_save_postdata post_id = $post_id, should_be = $should_be");

        if (isset($_POST['crossword_method'])) {
            update_post_meta($post_id, 'crossword_method', $_POST['crossword_method']);
        }
        if (isset($_POST['crossword_method']) && $_POST['crossword_method'] == 'url') {
            if (defined('CCPUZ_DEBUG'))
                ccpuz_log("2.ccpuz_save_postdata post_id = $post_id, should_be = $should_be");
            if ($_POST['ccpuz_url_upload_field']) {
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("3.ccpuz_save_postdata post_id = $post_id, should_be = $should_be");
                if (preg_match('#(crossword|crossword\-puzzle|crosswordpuzzle|crucigrama|cruciverb|karebulmaca|kruiswoordraadsel|motscroise|palavrascruzadas|pussel).info#', $_POST['ccpuz_url_upload_field'])) {
                    //var_dump(file_get_contents( $_POST['ccpuz_file_upload_field'] ));
                    $response = wp_remote_get($_POST['ccpuz_url_upload_field']);
                    $file_source = wp_remote_retrieve_body($response);
                    if (defined('CCPUZ_DEBUG'))
                        ccpuz_log("4.ccpuz_save_postdata post_id = $post_id, size = " . strlen($file_source), "\nurl = " . $_POST['ccpuz_url_upload_field']);
                    preg_match('/src="[^"]+"/i', $file_source, $arr);
                    if (defined('CCPUZ_DEBUG'))
                        ccpuz_log("5.ccpuz_save_postdata post_id = $post_id, arr:\n" . print_r($arr, true));

                    $res_command = get_string_between($file_source, '$(function(){', '})');

                    $id = get_string_between($res_command, '$("', '")');
                    $res_command = str_replace($id, '#CrosswordCompilerPuz', $res_command);
                    $res_command = preg_replace('/$\("#[^"]+"\)/i', '$("#SSS")', $res_command);
                    $res_command = preg_replace('/ROOTIMAGES:[^,]+"/i', '', $res_command);
                    $res_command = preg_replace('/PROGRESS:[^,]+"/i', '', $res_command);

                    foreach ($arr as $single) {
                        if (substr_count($single, '_xml.js') > 0) {
                            $js_url = 'https://crossword.info' . str_replace('src=', '', str_replace('"', '', $single));
                            $upload_dir = wp_upload_dir();
                            $filename = sanitize_file_name(basename($js_url));

                            if (wp_mkdir_p($upload_dir['path'])) {
                                $file = $upload_dir['path'] . '/' . $filename;
                                $url = $upload_dir['url'] . '/' . $filename;
                            } else {
                                $file = $upload_dir['basedir'] . '/' . $filename;
                                $url = $upload_dir['baseurl'] . '/' . $filename;
                            }

                            $response = wp_remote_get($js_url);
                            $image_data = wp_remote_retrieve_body($response);
                            if (defined('CCPUZ_DEBUG'))
                                ccpuz_log("6.ccpuz_save_postdata post_id = $post_id, size = " . strlen($image_data), "\nurl:\n" . $js_url . "\nfile:\n" . $file);
                            @file_put_contents($file, $image_data);
                        }
                    }
                }

                if (!isset($url) && !get_post_meta($post_id, 'ccpuz_url_upload_field', true) && $should_be == 1) {
                    if (defined('CCPUZ_DEBUG'))
                        ccpuz_log("<ccpuz_save_postdata ERROR #1 post_id = $post_id");
                    wp_die('Oops, something wrong: must give full crossword.info puzzle URL.', '<strong>Error</strong>: Something went wrong.');
                }
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("7.ccpuz_save_postdata post_id = $post_id");

                ### adding custom fields
                $str_parent = ' jQuery(".entry-content").attr( "class", "entry-content puzzle"); ';
                ccpuz_applet();
                update_post_meta($post_id, 'ccpuz_js_url', $url);
                update_post_meta($post_id, 'ccpuz_js_run', '<script>jQuery(document).ready(function($) { ' . $str_parent . ' ' . $res_command . ' });</script>');
                update_post_meta($post_id, 'ccpuz_url_upload_field', $_POST['ccpuz_url_upload_field']);
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("8.ccpuz_save_postdata post_id = $post_id\n" . '<script>jQuery(document).ready(function($) { ' . $str_parent . ' ' . $res_command . ' });</script>');
            }
        }

        if (isset($_POST['crossword_method']) && $_POST['crossword_method'] == 'local') {
            if (defined('CCPUZ_DEBUG'))
                ccpuz_log("10.ccpuz_save_postdata post_id = $post_id\n" . print_r($_FILES, true));
            if ($_FILES["ccpuz_html_file"]["tmp_name"]) {
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("11.ccpuz_save_postdata post_id = $post_id");
                $file_source = file_get_contents($_FILES["ccpuz_html_file"]["tmp_name"]);
                $res_command = get_string_between($file_source, '$(function(){', '})');
                $res_command = preg_replace('/ROOTIMAGES: "[^"]+"/i', 'ROOTIMAGES: ""', $res_command);
                $res_command = str_replace('ROOTIMAGES: "', 'ROOTIMAGES: "' . plugins_url('inc/CrosswordCompilerApp/CrosswordImages/', __FILE__), $res_command);
                $str_parent = ' $(".entry-content").attr( "class", "entry-content puzzle"); ';
                if (!$res_command) {
                    wp_die('Oops, Something wrong with html file.', '<strong>Error</strong>: Something wrong with puzzle html file.');
                }
                update_post_meta($post_id, 'ccpuz_js_run', '<script>jQuery(document).ready(function($) { ' . $str_parent . ' ' . $res_command . ' });</script>');
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("11.ccpuz_save_postdata post_id = $post_id\n" . '<script>jQuery(document).ready(function($) { ' . $str_parent . ' ' . $res_command . ' });</script>');
            } else {

                if (get_post_meta($post_id, 'ccpuz_js_run', true) == '' && $should_be == 1) {
                    if (defined('CCPUZ_DEBUG'))
                        ccpuz_log("<ccpuz_save_postdata ERROR #2 post_id = $post_id");

                    wp_die('Oops, Add HTML file exported by Crossword Compiler.', '<strong>Error</strong>: Something went wrong.');
                }
            }
            if ($_FILES["ccpuz_js_file"]["name"]) {
                $upload_dir = wp_upload_dir();
                $filename = sanitize_file_name($_FILES["ccpuz_js_file"]["name"]);
                if (wp_mkdir_p($upload_dir['path'])) {
                    $file = $upload_dir['path'] . '/' . $filename;
                    $url = $upload_dir['url'] . '/' . $filename;
                } else {
                    $file = $upload_dir['basedir'] . '/' . $filename;
                    $url = $upload_dir['baseurl'] . '/' . $filename;
                }
                $image_data = @file_get_contents($_FILES["ccpuz_js_file"]["tmp_name"]);
                if (defined('CCPUZ_DEBUG'))
                    ccpuz_log("12.ccpuz_save_postdata post_id = $post_id file = $file size = " . strlen($image_data) . "\nurl = $url");
                @file_put_contents($file, $image_data);
                ccpuz_applet();
                update_post_meta($post_id, 'ccpuz_js_url', $url);
            } else {
                if (get_post_meta($post_id, 'ccpuz_js_url', true) == '' && $should_be == 1) {
                    if (defined('CCPUZ_DEBUG'))
                        ccpuz_log("<ccpuz_save_postdata ERROR #3 post_id = $post_id");
                    wp_die('Oops, Add puzzle .js file exported by Crossword Compiler.', '<strong>Error</strong>: Something went wrong.');
                }
            }
        }
    }
    if (defined('CCPUZ_DEBUG'))
        ccpuz_log("<ccpuz_save_postdata OK post_id = $post_id");
}
