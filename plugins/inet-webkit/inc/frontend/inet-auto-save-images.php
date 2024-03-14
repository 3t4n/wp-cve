<?php

if (!class_exists('inet_auto_save_images')) {
    final class inet_auto_save_images
    {
        function __construct()
        {
            add_filter('wp_insert_post_data', array($this, 'fetch_images_when_saving'), 10, 2);
        }

        /**
         * @return void
         */
        public function remove_actions()
        {
            remove_filter('wp_insert_post_data', array($this, 'fetch_images_when_saving'));
        }

        /**
         * @param $data
         * @param $postarr
         * @return void
         */
        public function fetch_images_when_saving($data, $postarr)
        {
            set_time_limit(0);
            $inet_wk_options = get_option('inet_wk');

            if ($inet_wk_options['inet-webkit-auto-save-image']['auto-save-image-type'] == 'all-post') $allow = true;
            elseif ($inet_wk_options['inet-webkit-auto-save-image']['auto-save-image-type'] == 'new-post'
                && $data['post_status'] == 'publish') $allow = true;
            else $allow = false;

            if ($allow) {
                $this->has_remote_image = 0;
                $data['post_content'] = addslashes($this->save_post_image(stripslashes($data['post_content']), $postarr['ID']));
            }

            return $data;
        }

        /**
         * @param $post_id
         * @return void
         */
        public function fetch_images_after_save($post_id)
        {
            set_time_limit(0);
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return;
            if (defined('DOING_AJAX') && DOING_AJAX)
                return;
            $this->current_post_id = $post_id;
            $this->has_remote_image = 0;
            $this->remove_actions();

            $post = get_post($post_id);
            $html = $this->save_post_image($post->post_content, $post_id);

            remove_action('post_updated', 'wp_save_post_revision');
            wp_update_post(array('ID' => $post_id, 'post_content' => addslashes($html)));
            add_action('post_updated', 'wp_save_post_revision', 10, 1);

            $this->add_actions();
        }

        /**
         * @param $image_url
         * @return array
         */
        public function getimagesize($image_url)
        {
            $params = @getimagesize($image_url);
            $width = $params[0];
            $height = $params[1];
            $this->type = $params['mime'];
            if ($width == null) {
                $file = @file_get_contents($image_url);
                if ($file) {
                    $encoding = $this->fsockopen_image_header($image_url, 'Content-Encoding');
                    if ($encoding == 'gzip' && function_exists('gzdecode')) $file = gzdecode($file);
                    if (function_exists('getimagesizefromstring')) {
                        $params = getimagesizefromstring($file);
                        $width = $params[0];
                        $height = $params[1];
                        $this->type = $params['mime'];
                    }
                }
            } else {
                $width = $params[0];
                $height = $params[1];
                $this->type = $params['mime'];
            }
            return array($width, $height, $this->type);
        }

        /**
         * @param $html
         * @param $post_id
         * @param $action
         * @return mixed|void
         */
        private function save_post_image($html, $post_id = null, $action = 'save')
        {
            $post = get_post($post_id);

            //if ($post->post_type == 'revision') return;
            // dont save for revisions
            if (isset($post->post_type) && $post->post_type == 'revision') {
                return;
            }

            $this->change_attachment_url_to_permalink($html);
            $remote_images = array();
            $preg = preg_match_all('/<img.*?src=\"((?!\").*?)\"/i', stripslashes($html), $matches);
            if ($preg) $remote_images = $matches[1];

            $preg = preg_match_all('/<img.*?src=\'((?!\').*?)\'/i', stripslashes($html), $matches);
            if ($preg) $remote_images = array_merge($remote_images, $matches[1]);

            if (!empty($remote_images)) {
                foreach ($remote_images as $image_url) {
                    if (empty($image_url)) continue;
                    $allow = true;

                    // check pictrue size
                    list($width, $height, $type) = $this->getimagesize($image_url);

                    // check if remote image
                    if ($allow) {
                        $pos = strpos($image_url, get_bloginfo('url'));
                        if ($pos === false) {
                            $this->has_remote_image = 1;
                            if ($action == "save" && $res = $this->save_images($image_url, $post_id)) {
                                $html = $this->format($image_url, $res, $html);
                            }
                        }
                    }
                }
            }
            return apply_filters('inet-auto-save-images-content-after', $html, $post_id);
        }

        /**
         * @param $html
         * @return void
         */
        public function change_attachment_url_to_permalink(&$html)
        {
            $pattern = '/<a\s[^>]*href=\"' . $this->encode_pattern(home_url('?attachment_id=')) . '(.*?)\".*?>/i';
            if (preg_match_all($pattern, $html, $matches)) {
                foreach ($matches[1] as $attachment_id) {
                    $attachment = get_post($attachment_id);
                    $post = get_post($attachment->post_parent);
                    if ($post->post_status != 'draft' && $post->post_status != 'pending' && $post->post_status != 'future') {
                        $url = get_permalink($attachment_id);
                        $html = preg_replace('/' . $this->encode_pattern(home_url('?attachment_id=' . $attachment_id)) . '/i', $url, $html);
                    }
                }
            }
        }

        /**
         * @param $str
         * @return array|string|string[]
         */
        public function encode_pattern($str)
        {
            $str = str_replace('(', '\(', $str);
            $str = str_replace(')', '\)', $str);
            $str = str_replace('{', '\{', $str);
            $str = str_replace('}', '\}', $str);
            $str = str_replace('+', '\+', $str);
            $str = str_replace('.', '\.', $str);
            $str = str_replace('?', '\?', $str);
            $str = str_replace('*', '\*', $str);
            $str = str_replace('/', '\/', $str);
            $str = str_replace('^', '\^', $str);
            $str = str_replace('$', '\$', $str);
            $str = str_replace('|', '\|', $str);
            return $str;
        }

        /**
         * @param $image_url
         * @param $res
         * @param $html
         * @return array|string|string[]|null
         */
        public function format($image_url, $res, $html)
        {
            $no_match = false;
            $attachment_id = $res['id'];
            $url_path = str_replace(basename($res['file']), '', $res['url']);
            $size = isset($res['sizes'][$this->format['size']]) ? $this->format['size'] : 'full';
            $src = $res['url'];
            $width = $res['width'];
            $height = $res['height'];
            $pattern_image_url = $this->encode_pattern($image_url);
            $preg = false;
            $pattern = '/<a[^<]+><img\s[^>]*' . $pattern_image_url . '.*?>?<[^>]+a>/i';
            $preg = preg_match($pattern, $html, $matches);
            if (!$preg) {
                $pattern = '/<img\s[^>]*' . $pattern_image_url . '.*?>/i';
                if (preg_match($pattern, $html, $matches)) {
                    $args = $this->set_img_metadata($matches[0], $attachment_id);
                } else {
                    $pattern = '/' . $pattern_image_url . '/i';
                    $no_match = true;
                }
            }
            $alt = isset($args['alt']) ? ' alt="' . $args['alt'] . '"' : '';
            $title = isset($args['title']) ? ' title="' . $args['title'] . '"' : '';
            $img = '<img class="size-' . $size . ' wp-image-' . $attachment_id . '" src="' . $src . '" width="' . $width . '" height="' . $height . '"' . $alt . $title . ' />';
            if ($no_match) $img = $res['url'];
            $html = preg_replace($pattern, $img, $html);
            return $html;
        }

        /**
         * Meta Image
         * @param $img
         * @param $attachment_id
         * @return null[]
         */
        public function set_img_metadata($img, $attachment_id)
        {
            $alt = $this->get_post_title() ? $this->get_post_title() : null;
            $title = $this->get_post_title() ? $this->get_post_title() : null;
            if ($alt) update_post_meta($attachment_id, '_wp_attachment_image_alt', $alt);
            if ($title) {
                $attachment = array(
                    'ID' => $attachment_id,
                    'post_title' => $title
                );
                wp_update_post($attachment);
            }
            return array(
                'alt' => $alt,
                'title' => $title
            );
        }

        /**
         * @return string
         */
        public function get_post_title()
        {
            $post = get_post($this->current_post_id);
            return $post->post_title;
        }

        /**
         * @param $image_url
         * @param $post_id
         * @return array|false
         */
        public function save_images($image_url, $post_id)
        {
            set_time_limit(0);
            $image_url = urldecode(html_entity_decode($image_url));

            $file = $this->curl_get($image_url);
            if ($file) {
                $filename = basename(explode('?', $image_url)[0]);
                preg_match('/(.*?)(\.\w+)$/', $filename, $match);
                $post = get_post($post_id);
                $title = $post->post_title;
                $postname = sanitize_title($title);
                $img_name = $postname . $match[2];

                $res = wp_upload_bits($img_name, '', $file);

                if (isset($res['error']) && !empty($res['error'])) return false;
                $attachment_id = $this->append_file($res['file'], $post_id);

                $res['id'] = $attachment_id;
                $meta_data = wp_get_attachment_metadata($attachment_id);

                $res = @array_merge($res, $meta_data);
                if (!has_post_thumbnail($post_id)) {
                    $this->thumbnail_id = $res['id'];
                    set_post_thumbnail($post_id, $attachment_id);
                }

                return $res;
            }

            return false;
        }

        /**
         * @param $file
         * @param $id
         * @return int|WP_Error
         */
        public function append_file($file, $id)
        {
            $dirs = wp_upload_dir();
            $filetype = wp_check_filetype($file);
            $attachment = array(
                'guid' => $dirs['baseurl'] . '/' . _wp_relative_upload_path($file),
                'post_mime_type' => $filetype['type'],
                'post_title' => preg_replace('/\.[^.]+$/', '', basename($file)),
                'post_content' => '',
                'post_status' => 'inherit'
            );

            $attach_id = wp_insert_attachment($attachment, $file, $id);
            // if (!function_exists('wp_generate_attachment_metadata')) include_once (ABSPATH . DIRECTORY_SEPARATOR . 'wp-admin' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'image.php');
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            wp_update_attachment_metadata($attach_id, $attach_data);

            return $attach_id;
        }

        /**
         * @param $url
         * @return bool|string
         */
        public function curl_get($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            $query = curl_exec($ch);
            curl_close($ch);

            return $query;
        }
    }
}

// create new object
new inet_auto_save_images();