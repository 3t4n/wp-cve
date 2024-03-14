<?php

namespace WP_Rplg_Google_Reviews\Includes\Core;

class Connect_Google {

    public function __construct() {
        add_action('wp_ajax_grw_hide_review', array($this, 'hide_review'));
        add_action('wp_ajax_grw_connect_google', array($this, 'connect_google'));
    }

    public function hide_review() {
        global $wpdb;

        if (current_user_can('editor') || current_user_can('administrator')) {
            if (isset($_POST['grw_wpnonce']) === false) {
                $error = __('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.', 'widget-google-reviews');
                $response = compact('error');
            } else {
                check_admin_referer('grw_wpnonce', 'grw_wpnonce');

                $review = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM " . $wpdb->prefix . Database::REVIEW_TABLE .
                        " WHERE id = %d", $_POST['id']
                    )
                );

                $hide = $review->hide == '' ? 'y' : '';
                $wpdb->update($wpdb->prefix . Database::REVIEW_TABLE, array('hide' => $hide), array('id' => $_POST['id']));

                // Cache clear
                if ($_POST['feed_id']) {
                    delete_transient('grw_feed_' . GRW_VERSION . '_' . $_POST['feed_id'] . '_reviews', false);
                } else {
                    $feed_ids = get_option('grw_feed_ids');
                    if (!empty($feed_ids)) {
                        $ids = explode(",", $feed_ids);
                        foreach ($ids as $id) {
                            delete_transient('grw_feed_' . GRW_VERSION . '_' . $id . '_reviews', false);
                        }
                    }
                }

                $response = array('hide' => $hide);
            }
            header('Content-type: text/javascript');
            echo json_encode($response);
            die();
        }
    }

    public function connect_google() {
        if (current_user_can('manage_options')) {
            if (isset($_POST['grw_wpnonce']) === false) {
                $error = __('Unable to call request. Make sure you are accessing this page from the Wordpress dashboard.', 'widget-google-reviews');
                $response = compact('error');
            } else {
                check_admin_referer('grw_wpnonce', 'grw_wpnonce');

                if (isset($_POST['key'])) {
                    $key = sanitize_text_field(wp_unslash($_POST['key']));
                    if (strlen($key) > 0) {
                        update_option('grw_google_api_key', $key);
                    }
                }
                $google_api_key = get_option('grw_google_api_key');

                $id = sanitize_text_field(wp_unslash($_POST['id']));
                $lang = sanitize_text_field(wp_unslash($_POST['lang']));
                $local_img = sanitize_text_field(wp_unslash($_POST['local_img']));

                if ($google_api_key && strlen($google_api_key) > 0) {
                    $url = $this->api_url($id, $google_api_key, $lang);
                } else {
                    $url = 'https://app.richplugins.com/gpaw/get/json' .
                           '?siteurl=' . get_option('siteurl') .
                           '&authcode=' . get_option('grw_auth_code') .
                           '&pid=' . $id;
                    if ($lang && strlen($lang) > 0) {
                        $url = $url . '&lang=' . $lang;
                    }
                }

                $res = wp_remote_get($url);
                $body = wp_remote_retrieve_body($res);
                $body_json = json_decode($body);

                if (!$body_json || !isset($body_json->result)) {
                    $result = $body_json;
                    $status = 'failed';
                } elseif (!isset($body_json->result->rating)) {
                    $error_msg = 'Google place <a href="' . $body_json->result->url . '" target="_blank">which you try to connect</a> does not have a rating and reviews, it seems it\'s a street address, not a business locations. Please read manual how to find <a href="' . admin_url('admin.php?page=grw-support&grw_tab=fig#place_id') . '" target="_blank">right Place ID</a>.';
                    $result = array('error_message' => $error_msg);
                    $status = 'failed';
                } else {
                    if ($google_api_key && strlen($google_api_key) > 0) {
                        $photo = $this->business_avatar($body_json->result, $google_api_key);
                        $body_json->result->business_photo = $photo;
                    }

                    $this->save_reviews($body_json->result, $local_img);

                    $result = array(
                        'id'      => $body_json->result->place_id,
                        'name'    => $body_json->result->name,
                        'photo'   => strlen($body_json->result->business_photo) ? $body_json->result->business_photo : GRW_GOOGLE_BIZ,
                        'reviews' => $body_json->result->reviews
                    );
                    $status = 'success';

                    if ($_POST['feed_id']) {
                        delete_transient('grw_feed_' . GRW_VERSION . '_' . $_POST['feed_id'] . '_reviews', false);
                    }
                }
                $response = compact('status', 'result');
            }
            header('Content-type: text/javascript');
            echo json_encode($response);
            die();
        }
    }

    function grw_refresh_reviews($args) {

        $place_id = $args[0];
        $reviews_lang = $args[1];
        $local_img = isset($args[2]) ? $args[2] : 'false';

        $url = '';
        $google_api_key = get_option('grw_google_api_key');
        $api_key_filled = $google_api_key && strlen($google_api_key) > 0;

        if ($api_key_filled) {

            $url = $this->api_url($place_id, $google_api_key, $reviews_lang, 'newest');

        } else {

            $url = 'https://app.richplugins.com/gpaw/update/json' .
                   '?siteurl=' . get_option('siteurl') .
                   '&authcode=' . get_option('grw_auth_code') .
                   '&pid=' . $place_id .
                   '&time=' . time();
            if ($reviews_lang && strlen($reviews_lang) > 0) {
                $url = $url . '&lang=' . $reviews_lang;
            }
        }

        if (strlen($url) > 0) {
            $res = wp_remote_get($url);
            $body = wp_remote_retrieve_body($res);
            $body_json = json_decode($body);

            if ($body_json && isset($body_json->result) && isset($body_json->result->rating)) {

                if ($api_key_filled) {
                    $photo = $this->business_avatar($body_json->result, $google_api_key);
                    $body_json->result->business_photo = $photo;
                }

                $this->save_reviews($body_json->result, $local_img);
            }
        }
    }

    function save_reviews($place, $local_img) {
        global $wpdb;

        $google_place_id = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT id FROM " . $wpdb->prefix . Database::BUSINESS_TABLE .
                " WHERE place_id = %s", $place->place_id
            )
        );

        // Insert or update Google place
        if ($google_place_id) {

            // Update Google place
            $update_params = array(
                'name'    => $place->name,
                'rating'  => $place->rating,
                'updated' => round(microtime(true) * 1000),
            );

            $review_count = isset($place->user_ratings_total) ? $place->user_ratings_total : 0;

            if ($review_count > 0) {
                $update_params['review_count'] = $review_count;
            }
            if (isset($place->business_photo) && strlen($place->business_photo) > 0) {
                $update_params['photo'] = $place->business_photo;
            }
            $wpdb->update($wpdb->prefix . Database::BUSINESS_TABLE, $update_params, array('ID' => $google_place_id));

            // Insert Google place rating stats
            $stats = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT rating, review_count FROM " . $wpdb->prefix . Database::STATS_TABLE .
                    " WHERE google_place_id = %d ORDER BY id DESC LIMIT 1", $google_place_id
                )
            );
            if (count($stats) > 0) {
                if ($stats[0]->rating != $place->rating || ($review_count > 0 && $stats[0]->review_count != $review_count)) {
                    $wpdb->insert($wpdb->prefix . Database::STATS_TABLE, array(
                        'google_place_id' => $google_place_id,
                        'time'            => time(),
                        'rating'          => $place->rating,
                        'review_count'    => $review_count
                    ));
                }
            } else {
                $wpdb->insert($wpdb->prefix . Database::STATS_TABLE, array(
                    'google_place_id' => $google_place_id,
                    'time'            => time(),
                    'rating'          => $place->rating,
                    'review_count'    => $review_count
                ));
            }

        } else {

            // Insert Google place
            $place_rating = isset($place->rating) ? $place->rating : null;
            $review_count = isset($place->user_ratings_total) ?
                                $place->user_ratings_total : (isset($place->reviews) ? count($place->reviews) : null);

            $wpdb->insert($wpdb->prefix . Database::BUSINESS_TABLE, array(
                'place_id'     => $place->place_id,
                'name'         => $place->name,
                'photo'        => $place->business_photo,
                'icon'         => $place->icon,
                'address'      => $place->formatted_address,
                'rating'       => $place_rating,
                'url'          => isset($place->url)     ? $place->url     : null,
                'website'      => isset($place->website) ? $place->website : null,
                'review_count' => $review_count,
                'updated'      => round(microtime(true) * 1000)
            ));
            $google_place_id = $wpdb->insert_id;

            if ($place_rating > 0) {
                $wpdb->insert($wpdb->prefix . Database::STATS_TABLE, array(
                    'google_place_id' => $google_place_id,
                    'time'            => time(),
                    'rating'          => $place_rating,
                    'review_count'    => $review_count
                ));
            }
        }

        // Insert or update Google reviews
        if ($place->reviews) {

            $reviews = $place->reviews;

            foreach ($reviews as $review) {
                $google_review_id = 0;
                if (isset($review->author_url) && strlen($review->author_url) > 0) {
                    $where = " WHERE author_url = %s";
                    $where_params = array($review->author_url);
                } elseif (isset($review->author_name) && strlen($review->author_name) > 0) {
                    $where = " WHERE author_name = %s";
                    $where_params = array($review->author_name);
                } else {
                    $where = " WHERE time = %s";
                    $where_params = array($review->time);
                }

                $review_lang = null;
                if (isset($review->language)) {
                    $review_lang = ($review->language == 'en-US' ? 'en' : $review->language);
                    if (strlen($review_lang) > 0) {
                        $where = $where . " AND language = %s";
                        array_push($where_params, $review_lang);
                    }
                }

                if ($google_place_id) {
                    $where = $where . " AND google_place_id = %d";
                    array_push($where_params, $google_place_id);
                }

                $google_review_id = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT id FROM " . $wpdb->prefix . Database::REVIEW_TABLE . $where, $where_params
                    )
                );

                $author_img = null;
                if (isset($review->profile_photo_url)) {
                    if ($local_img === true || $local_img == 'true') {
                        $img_name = $place->place_id . '_' . md5($review->profile_photo_url);
                        $author_img = $this->upload_image($review->profile_photo_url, $img_name);
                    } else {
                        $author_img = $review->profile_photo_url;
                    }
                }

                if ($google_review_id) {
                    $update_params = array(
                        'rating' => $review->rating,
                        'text'   => $review->text
                    );
                    if ($author_img) {
                        $update_params['profile_photo_url'] = $author_img;
                    }
                    $wpdb->update($wpdb->prefix . Database::REVIEW_TABLE, $update_params, array('id' => $google_review_id));
                } else {
                    $wpdb->insert($wpdb->prefix . Database::REVIEW_TABLE, array(
                        'google_place_id'   => $google_place_id,
                        'rating'            => $review->rating,
                        'text'              => $review->text,
                        'time'              => $review->time,
                        'language'          => $review_lang,
                        'author_name'       => $review->author_name,
                        'author_url'        => isset($review->author_url) ? $review->author_url : null,
                        'profile_photo_url' => $author_img
                    ));
                }
            }
        }
    }

    function api_url($placeid, $google_api_key, $reviews_lang = '', $reviews_sort = '') {
        $url = GRW_GOOGLE_PLACE_API . 'details/json?placeid=' . $placeid . '&key=' . $google_api_key;
        if (strlen($reviews_lang) > 0) {
            $url = $url . '&language=' . $reviews_lang;
        }
        if (strlen($reviews_sort) > 0) {
            $url = $url . '&reviews_sort=' . $reviews_sort;
        }
        return $url;
    }

    function business_avatar($response_result_json, $google_api_key) {
        if (isset($response_result_json->photos)) {
            $url = add_query_arg(
                array(
                    'photoreference' => $response_result_json->photos[0]->photo_reference,
                    'key'            => $google_api_key,
                    'maxwidth'       => '300',
                    'maxheight'      => '300',
                ),
                'https://maps.googleapis.com/maps/api/place/photo'
            );
            return $this->upload_image($url, $response_result_json->place_id);
        }
        return null;
    }

    function upload_image($url, $name) {
        $res = wp_remote_get($url, array('timeout' => 8));

        if(is_wp_error($res)) {
            // LOG
            return null;
        }

        $bits = wp_remote_retrieve_body($res);
        $filename = $name . '.jpg';

        $upload_dir = wp_upload_dir();
        $full_filepath = $upload_dir['path'] . '/' . $filename;
        if (file_exists($full_filepath)) {
            wp_delete_file($full_filepath);
        }

        $upload = wp_upload_bits($filename, null, $bits);
        return $upload['url'];
    }

}