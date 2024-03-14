<?php

/**
 * Class for importing a template.
 *
 * @package Layouts
 */

/**
 * Class for importing a template.
 *
 */
class Layouts_Divi_Importer {

    public function __construct() {
        if (!function_exists('wp_crop_image')) {
            include ABSPATH . 'wp-admin/includes/image.php';
        }
        $this->hooks();
    }

    /**
     * Initialize
     */
    public function hooks() {
        add_action('wp_ajax_handle_import', array($this, 'handle_import'));
        add_action('wp_ajax_nopriv_handle_import', array($this, 'handle_import'));
    }

    /**
     * Import template ajax action
     */
    public function handle_import() {

        $template_id = sanitize_text_field($_POST['template_id']);
        $with_page = sanitize_text_field($_POST['with_page']);

        $template = Layouts_Divi_Remote::lfd_get_instance()->get_template_content($template_id);

        // Check Error
        if (is_wp_error($template)) {
            return false;
        }

        // Check $template as string
        if (is_string($template) && !empty($template)) {
            echo $template;
            exit;
        }

        // Finally create the page or template.
        $page_id = $this->create_page($template, $with_page);
        echo $page_id;
        exit;
    }

    /**
     * Import template using page
     */
    private function create_page($template, $with_page) {

        if (!$template) {
            return _e('Invalid Template ID.', LFD_TEXTDOMAIN);
        }

        if (!empty($template['template'])) {
            $string_content = '';
            $new_content = array();
            $temp_array = array();

            $search_str = 'src="';
            if (preg_match("/{$search_str}/i", $template['template'])) {
                $content_arr = explode('src="', $template['template']);
                $new_content_arr = $this->lfd_get_media_new_url_value($content_arr, $temp_array, $new_content);
                $string_content = implode('src="', $new_content_arr);
            } else {
                $string_content = $template['template'];
            }

            $search_str = 'image_url="';
            if (preg_match("/{$search_str}/i", $string_content)) {
                $content_arr = explode('image_url="', $string_content);
                $new_content_arr = $this->lfd_get_media_new_url_value($content_arr, $temp_array, $new_content);
                $string_content = implode('image_url="', $new_content_arr);
            }

            $search_str = 'background_image="';
            if (preg_match("/{$search_str}/i", $string_content)) {
                $content_arr = explode('background_image="', $string_content);
                $new_content_arr = $this->lfd_get_media_new_url_value($content_arr, $temp_array, $new_content);
                $string_content = implode('background_image="', $new_content_arr);
            }

            $search_str = 'gallery_ids="';
            if (preg_match("/{$search_str}/i", $string_content)) {
                $content_arr = explode('gallery_ids="', $string_content);
                $new_contents_arr = $this->lfd_get_media_new_ids_value($content_arr, $temp_array, $new_content);
                $string_content = implode('gallery_ids="', $new_contents_arr);
            }
        }

        if (!empty($with_page)) {

            // Create post object
            $args = array(
                'post_type' => 'page',
                'post_title' => $with_page,
                'post_content' => $string_content,
                'post_status' => 'draft',
                'meta_input' => array(
                    '_et_pb_use_builder' => 'on',
                ),
            );

            // Insert the post into the database
            $post_id = wp_insert_post($args);
            $result = array();
            if (!is_wp_error($post_id)) {
                return $post_id;
            } else {
                return $post_id->get_error_message();
            }
        } else {

            global $wpdb;
            $post_name = sanitize_title_with_dashes($template['title']);
            $result = $wpdb->get_row("SELECT * from " . $wpdb->posts . " where post_name = '" . $post_name . "' " . " and post_type = 'et_pb_layout' ");
            if (!empty($result) && !is_wp_error($result)) {

                $args = array(
                    'ID' => $result->ID,
                    'post_type' => 'et_pb_layout',
                    'post_title' => $template['title'],
                    'post_content' => $string_content,
                );

                // Update the post into the database
                $post_id = wp_update_post($args);
                if (!is_wp_error($post_id)) {
                    return $post_id;
                } else {
                    return $post_id->get_error_message();
                }
            } else {

                // Create Divi Library object
                $args = array(
                    'post_type' => 'et_pb_layout',
                    'post_title' => $template['title'],
                    'post_content' => $string_content,
                    'post_status' => 'publish',
                    'meta_input' => array(
                        '_et_pb_use_builder' => 'on',
                        '_et_pb_built_for_post_type' => 'page'
                    ),
                );

                // Insert the post into the database
                $post_id = wp_insert_post($args);
                if (!is_wp_error($post_id)) {
                    wp_set_object_terms($post_id, 'layout', 'layout_type', true);
                    return $post_id;
                } else {
                    return $post_id->get_error_message();
                }
            }
        }
    }

    /**
     * Get Image url to url
     */
    private function lfd_get_media_new_url_value($content_arr, $temp_array, $new_content) {
        foreach ($content_arr as $key => $value) {
            if (!empty($value)) {
                $value_arr = explode('"', $value, 2);
                if (!empty($value_arr) && !empty($value_arr[0]) && strpos($value_arr[0], 'http') === 0) {
                    if (count($temp_array) > 0 && array_search($value_arr[0], array_column($temp_array, 'old'))) {
                        $exists_val = array_search($value_arr[0], array_column($temp_array, 'old'));
                        if (!empty($exists_val)) {
                            $new_val = $temp_array[$exists_val]['new'];
                            if (!empty($new_val)) {
                                $new_image_arr = array_replace($value_arr, array(0 => $new_val));
                                $value = implode('"', $new_image_arr);
                            }
                        }
                    } else {
                        //Get image url using id
                        $img_exist = $this->lfd_get_new_image($value_arr[0]);
                        if (empty($img_exist['img_exist']) && !empty($img_exist['image_url'])) {
                            //insert media in my wordpress
                            $attach_id = $this->lfd_insert_media_from_url($img_exist['image_url']);
                            $image_url = $attach_id ? wp_get_attachment_url($attach_id) : '';
                            $value = $this->lfd_get_new_image_string($image_url, $value_arr, $temp_array);
                        } else {
                            //Get attachment id using url
                            $value = $this->lfd_get_new_image_string($img_exist['img_exist'], $value_arr, $temp_array);
                        }
                    }
                }
            }
            $new_content[$key] = $value;
        }
        return $new_content;
    }

    /**
     * Get Image id to id
     */
    private function lfd_get_media_new_ids_value($content_arr, $temp_array, $new_content) {
        foreach ($content_arr as $key => $value) {
            if (!empty($value)) {
                $value_arr = explode('"', $value, 2);
                if (!empty($value_arr) && !empty($value_arr[0]) && preg_match('/[0-9]/i', $value_arr[0])) {
                    $search_str = ',';
                    if (preg_match("/{$search_str}/i", $value_arr[0])) {
                        $ids_arr = explode(',', $value_arr[0]);
                    } else {
                        $ids_arr = array(0 => $value_arr[0]);
                    }
                    $new_image_arr = array();
                    foreach ($ids_arr as $key => $img_id) {
                        if (!empty($img_id) && is_numeric($img_id)) {
                            if (count($temp_array) > 0 && is_numeric(array_search($img_id, array_column($temp_array, 'old')))) {
                                $exists_val = array_search($img_id, array_column($temp_array, 'old'));
                                if (!empty($exists_val)) {
                                    $new_val = $temp_array[$exists_val]['new'];
                                    if (!empty($new_val)) {
                                        array_push($new_image_arr, $new_val);
                                    }
                                }
                            } else {
                                //Get image url using id
                                $img_exist = $this->lfd_get_new_image($img_id);
                                if (empty($img_exist['img_exist']) && !empty($img_exist['image_url'])) {
                                    //insert media in my wordpress
                                    $attach_id = $this->lfd_insert_media_from_url($img_exist['image_url']);
                                    array_push($new_image_arr, $attach_id);
                                } else {
                                    //Get attachment id using url
                                    $attach_id = attachment_url_to_postid($img_exist['img_exist']);
                                    array_push($new_image_arr, $attach_id);
                                }
                                //Set default value
                                if (empty($attach_id)) {
                                    $attach_id = $img_id;
                                }
                                //Add value in temp array
                                if (!in_array(array('old' => $img_id, 'new' => $attach_id), $temp_array)) {
                                    array_push($temp_array, array('old' => $img_id, 'new' => $attach_id));
                                }
                            }
                        }
                    }
                    if (count($new_image_arr) > 0) {
                        $new_ids = implode(',', $new_image_arr);
                        $new_val = array(0 => $new_ids);
                        $new_ids_arr = array_replace($value_arr, $new_val);
                        $value = implode('"', $new_ids_arr);
                    } else {
                        $value = implode('"', $value_arr);
                    }
                }
            }
            array_push($new_content, $value);
        }
        return $new_content;
    }

    /**
     * Get existing Image in local Media
     */
    private function lfd_get_new_image($image_url) {
        $img_exist = '';
        if (is_numeric($image_url)) {
            $image_url = $this->lfd_get_image_url($image_url);
        }

        // Check $image_url not empty
        if (!empty($image_url)) {
            $slug = basename($image_url);
            $file_name = sanitize_file_name(pathinfo($slug, PATHINFO_FILENAME));
            $img_exist = $this->lfd_get_attachment_url_by_slug($file_name);
            if (empty($img_exist)) {
                $img_exist = $this->lfd_get_attachment_url_by_slug($file_name . '-1');
                if (empty($img_exist)) {
                    $img_exist = $this->lfd_get_attachment_url_by_slug($file_name . '-2');
                }
            }
        }
        return $result = array(
            'img_exist' => $img_exist,
            'image_url' => $image_url
        );
    }

    /**
     * Get Image url by id
     */
    private function lfd_get_image_url($img_id) {
        $image_url = Layouts_Divi_Remote::lfd_get_instance()->get_media_image($img_id);
        if (!empty($image_url) && is_string($image_url) && strpos($image_url, 'Missing Attachment') !== true) {
            return $image_url;
        } else {
            return '';
        }
    }

    /**
     * Check Image exist in local Media
     */
    private function lfd_get_attachment_url_by_slug($slug) {
        $args = array(
            'post_type' => 'attachment',
            'name' => $slug,
            'posts_per_page' => 1,
            'post_status' => 'inherit',
        );
        $_header = get_posts($args);
        $header = $_header ? array_pop($_header) : null;
        return $header ? wp_get_attachment_url($header->ID) : '';
    }

    /**
     * Media replace live media to local
     */
    private function lfd_get_new_image_string($image_url, $value_arr, $temp_array) {
        $new_val = array(0 => $image_url);
        $new_image_arr = array_replace($value_arr, $new_val);
        $new_image = implode('"', $new_image_arr);
        if (!in_array(array('old' => $value_arr[0], 'new' => $image_url), $temp_array)) {
            array_push($temp_array, array('old' => $value_arr[0], 'new' => $image_url));
        }
        return $new_image;
    }

    /**
     * Insert media using live url
     */
    private function lfd_insert_media_from_url($image_url) {
        $attachment_id = '';
        if (!empty($image_url) && strpos($image_url, 'Missing Attachment') !== true) {
            $filename = basename($image_url);
            $upload_file = wp_upload_bits($filename, null, file_get_contents($image_url));
            if (!$upload_file['error']) {
                $wp_filetype = wp_check_filetype($filename, null);
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                    'post_content' => '',
                    'post_status' => 'inherit'
                );
                $attachment_id = wp_insert_attachment($attachment, $upload_file['file']);
                if (!is_wp_error($attachment_id)) {
                    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
                    wp_update_attachment_metadata($attachment_id, $attachment_data);
                }
            }
        }
        return $attachment_id;
    }

}

new Layouts_Divi_Importer();
