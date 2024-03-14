<?php

defined('ABSPATH') or die("No direct script access!");

add_action('wp_head', 'qcopd_ajax_ajaxurl');
add_action('admin_head', 'qcopd_ajax_ajaxurl');

if ( ! function_exists( 'qcopd_ajax_ajaxurl' ) ) {
    function qcopd_ajax_ajaxurl(){

        echo '<script type="text/javascript">
                var ajaxurl = "' . admin_url('admin-ajax.php') . '";
                var qc_sld_get_ajax_nonce = "'.wp_create_nonce( 'qc-opd').'";
             </script>';
    }
}

//Doing ajax action stuff
if ( ! function_exists( 'upvote_ajax_action_stuff' ) ) {
    function upvote_ajax_action_stuff(){

        check_ajax_referer( 'qc-opd', 'security');

        //Get posted items
        $action = isset($_POST['action']) ? sanitize_text_field($_POST['action']): '';
        $post_id = isset($_POST['post_id']) ? absint(sanitize_text_field($_POST['post_id'])): '';
        $meta_title = isset($_POST['meta_title']) ? sanitize_text_field($_POST['meta_title']): '';
        $meta_link = isset($_POST['meta_link']) ? esc_url_raw($_POST['meta_link']): '';
        $li_id = isset($_POST['li_id']) ? sanitize_text_field($_POST['li_id']) : '';

        //Check wpdb directly, for all matching meta items
        global $wpdb;

        $results = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key = 'qcopd_list_item01'");

        //Defaults
        $votes = 0;

        $data['votes'] = 0;
        $data['vote_status'] = 'failed';

        $voted_id = isset($_COOKIE['voted_li']) ? $_COOKIE['voted_li'] : array();

        $exists = in_array($li_id, $voted_id);

        //If li-id not exists in the cookie, then prceed to vote
        if (!$exists) {

            if(!empty($results)){
            
                //Iterate through items
                foreach ($results as $key => $value) {

                    $item = $value;

                    $meta_id = $value->meta_id;

                    $unserialized = unserialize($value->meta_value);

                    //If meta title and link matches with unserialized data
                    if (trim($unserialized['qcopd_item_title']) == trim($meta_title) && trim($unserialized['qcopd_item_link']) == trim($meta_link)) {

                        $metaId = $meta_id;

                        //Defaults for current iteration
                        $upvote_count = 0;
                        $new_array = array();
                        $flag = 0;

                        //Check if there already a set value (previous)
                        if (array_key_exists('qcopd_upvote_count', $unserialized)) {
                            $upvote_count = (int)$unserialized['qcopd_upvote_count'];
                            $flag = 1;
                        }

                        foreach ($unserialized as $key => $value) {
                            if ($flag) {
                                if ($key == 'qcopd_upvote_count') {
                                    $new_array[$key] = $upvote_count + 1;
                                } else {
                                    $new_array[$key] = $value;
                                }
                            } else {
                                $new_array[$key] = $value;
                            }
                        }

                        if (!$flag) {
                            $new_array['qcopd_upvote_count'] = $upvote_count + 1;
                        }

                        $votes = (int)$new_array['qcopd_upvote_count'];

                        $updated_value = serialize($new_array);

                        $wpdb->update(
                            $wpdb->postmeta,
                            array(
                                'meta_value' => $updated_value,
                            ),
                            array('meta_id' => $metaId)
                        );

                        $voted_li = array("$li_id");

                        $total = 0;
                        $total = count($voted_id);
                        $total = $total + 1;

                        setcookie("voted_li[$total]", $li_id, time() + (86400 * 30), "/");

                        $data['vote_status'] = 'success';
                        $data['votes'] = $votes;
                    }

                }

            }



        }

        $data['cookies'] = $voted_id;

        echo json_encode($data);


        die(); // stop executing script
    }
}

//Implementing the ajax action for frontend users
add_action('wp_ajax_qcopd_upvote_action', 'upvote_ajax_action_stuff'); // ajax for logged in users
add_action('wp_ajax_nopriv_qcopd_upvote_action', 'upvote_ajax_action_stuff'); // ajax for not logged in users
