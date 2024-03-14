<?php

/**
 * jssor slider admin ajax call.
 *
 * @link       https://www.jssor.com
 * @since      1.0.0
 */

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

class WP_Jssor_Slider_Admin_Ajax {
    public function process_admin_ajax() {
        $method = null;

        if(isset($_POST['method'])) {
            $method = $_POST['method'];
        }

        if(empty($method) || !method_exists($this, $method)) {
            wp_send_json_error(array('message' => __('Invalid admin ajax call, method not found.', 'jssor-slider')));
        }
        else {
            $this->$method();
        }
    }

    /**
     * delete slider
     *
     * @return void
     */
    public function delete_slider()
    {
        check_ajax_referer('wjssl-add-slider');
        $this->check_permissions();

		// Slider data
        $slider_id = empty($_POST['slider_id']) ? 0 : intval($_POST['slider_id']);
        $slider_id = empty($slider_id) ? 0 : $slider_id;

        if (empty($slider_id)) {
            wp_send_json_error(array('message' => __('The request is invalid.', 'jssor-slider')));
        }

        if(Jssor_Slider_Bll::delete_slider($slider_id, $error_message) === false) {
            if (is_null($error_message)) {
                $error_message = __('Error occured, please try again later.', 'jssor-slider');
            }
            wp_send_json_error(array('message' => $error_message));
        }

        wp_send_json_success(array('slider_id' => $slider_id, 'message' => __('The slider[%s] is deleted.', 'jssor-slider')));
    }

    /**
     * rename or create new slider
     *
     * @return void
     */
    public function save_update_slider() {
        check_ajax_referer('wjssl-add-slider');
        $this->check_permissions();

        global $wpdb;

        $file_name = $_POST['slider_name'];

        //$file_name should be specified
        if (empty($file_name)) {
            wp_send_json_error(array('message' => __('Please input slider file name', 'jssor-slider')));
        }

        $file_name_error = Jssor_Slider_Bll::check_slider_file_name_error($file_name);

        //$file_name should be valid
        if (!empty($file_name_error)) {
            wp_send_json_error(array('message' => $file_name_error));
        }

        //$file_name should be safe
        $file_name = Jssor_Slider_Bll::to_safe_slider_file_name($file_name);

        if (empty($_POST['slider_id'])) {
            $slider_id = 0;
        } else {
            $slider_id = intval($_POST['slider_id']);
        }
        $slider_id = empty($slider_id) ? 0 : $slider_id;

        $slider_data = Jssor_Slider_Dal::get_slider_data_by_file_name($file_name, $error_message);

        //$file_name should not exist
        if(!is_null($slider_data)) {
            wp_send_json_error(array('message' => __('A slider with the same name exists already!', 'jssor-slider')));
        }
        else if(!is_null($error_message)) {
            wp_send_json_error(array('message' => $error_message));
        }

        //create a new slider or rename a slider
        $is_to_create_new_slider = empty($slider_id);

        if ($is_to_create_new_slider) {
            //create a new slider, obsolete, never happen any more
            wp_send_json_error(array('message' => __('Not implemented.', 'jssor-slider')));

            #region old code

            //$data = array(
            //    'file_path' => Jssor_Slider_Bll::get_template_slider_file_path(),
            //);

            //$sliderModel = new WP_Jssor_Slider_Slider(array(
            //    'id' => $slider_id,
            //    'file_name' => $file_name
            //));

            //$status = $sliderModel->save($data);

            //// Return insert database ID
            //$slider_id = $sliderModel->id;

            //if ($status === false) {
            //    wp_send_json_error(array('message' => $sliderModel->last_error()));
            //}

            //$upload = wp_upload_dir();
            //$template_path = $upload['basedir'] . Jssor_Slider_Bll::get_template_slider_file_path();
            //$slider_content = file_get_contents($template_path);

            //$slider_content = WP_Jssor_Slider_Utils::change_relative_path_for_content($slider_content);

            //$slider_path = WP_Jssor_Slider_Globals::slider_path($slider_id);
            //file_put_contents($slider_path['path'], $slider_content);
            //$sliderModel->save(array(
            //    'file_path' => $slider_path['rel_path']
            //));

            #endregion
        }
        else {
            //rename a slider
            $slider_data = Jssor_Slider_Dal::get_slider_data_by_id($slider_id, $error_message);
            if(is_null($slider_data)) {
                if(is_null($error_message)) {
                    $error_message = __('The slider %s is not found.', 'jssor-slider');
                    $error_message = wp_sprintf($error_message, strval($file_name));
                }
                wp_send_json_error(array('message' => $error_message));
            }
            else {
                if(Jssor_Slider_Bll::rename_slider($slider_data, $file_name, $error_message) === false) {
                    if(is_null($error_message)) {
                        $error_message = __('Error occured, please try again later.', 'jssor-slider');
                    }
                    wp_send_json_error(array('message' => $error_message));
                }
            }

            $slider_edit_url = '#';
            $slider_preview_url = WP_Jssor_Slider_Globals::get_jssor_preview_slider_url($slider_id, $file_name);

            wp_send_json_success(array(
                'slider_id' => $slider_id,
                'slider_name' => $file_name,
                'shortcode' => Jssor_Slider_Bll::get_shortcode_with_alias($slider_data['file_name']),
                'edit_url' => $slider_edit_url,
                'grid_thumb_url' => Jssor_Slider_Bll::get_slider_grid_thumb_url($slider_data),
                'preview_url' => $slider_preview_url
            ));
        }
    }

    /**
     * duplicate slider
     */
    public function duplicate_slider() {

        #region validation

        check_ajax_referer('wjssl-add-slider');
        $this->check_permissions();

        $slider_id = empty($_POST['slider_id']) ? 0 : intval($_POST['slider_id']);
        $new_slider_name = empty($_POST['new_slider_name']) ? '' : sanitize_file_name($_POST['new_slider_name']);

        if (empty($slider_id)) {
            wp_send_json_error(array('message' => __("The request is invalid.", 'jssor-slider')));
        }

        //$file_name should be specified
        if (empty($new_slider_name)) {
            wp_send_json_error(array('message' => __('Please input slider file name', 'jssor-slider')));
        }

        $file_name_error = Jssor_Slider_Bll::check_slider_file_name_error($new_slider_name);

        //$file_name should be valid
        if (!empty($file_name_error)) {
            wp_send_json_error(array('message' => $file_name_error));
        }

        #endregion

        //$file_name should be safe
        $new_slider_name = Jssor_Slider_Bll::to_safe_slider_file_name($new_slider_name);

        #region check if slider with new name exists or not

        $exists_slider_data = Jssor_Slider_Dal::get_slider_data_by_file_name($new_slider_name, $error_message);

        if (!is_null($exists_slider_data)) {
            $error = __('The slider with the same name \'%s\' exists already.', 'jssor-slider');
            wp_send_json_error(array('message' => wp_sprintf($error, $new_slider_name)));
        }
        else if(!is_null($error_message)) {
            wp_send_json_error(array('message' => $error_message));
        }

        #endregion

        #region check if the slider to copy from exists or not

        $old_slider_data = Jssor_Slider_Dal::get_slider_data_by_id($slider_id, $error_message);

        if(is_null($old_slider_data)) {
            if(is_null($error_message)) {
                $error_message = __('The slider %s is not found.', 'jssor-slider');
                $error_message = wp_sprintf($error_message, strval($slider_id));
            }
            wp_send_json_error(array('message' => $error_message));
        }

        #endregion

        #region create new slider
        $new_slider_data = null;

        $old_file_rel_path = $old_slider_data['file_path'];
        if(empty($old_file_rel_path)) {
            $old_file_rel_path = Jssor_Slider_Bll::get_template_slider_file_path();
        }

        $upload = wp_upload_dir();
        $slider_json_text = @file_get_contents($upload['basedir'] . $old_file_rel_path);

        if($slider_json_text !== false) {
            $slider_json_model = json_decode($slider_json_text);
            $new_slider_data = Jssor_Slider_Bll::create_new_slider($slider_json_model, $new_slider_name, $error_message);
        }

        if(is_null($new_slider_data)) {
            if(empty($error_message)) {
                $error_message = 'Unknown error.';
            }
            wp_send_json_error(array('message' => $error_message));
        }

        #endregion

        #region send new slider data

        $new_id = $new_slider_data['id'];

        $slider_edit_url = '#';
        $slider_preview_url = WP_Jssor_Slider_Globals::get_jssor_preview_slider_url($new_id, $new_slider_name);
        $grid_thumb_url = Jssor_Slider_Bll::get_slider_grid_thumb_url($new_slider_data);
        $shortcode_with_alias = Jssor_Slider_Bll::get_shortcode_with_alias($new_slider_name);

        wp_send_json_success(
            array(
            'slider_id' => $new_id,
            'slider_name' => $new_slider_name,
            'shortcode' => $shortcode_with_alias,
            'edit_url' => $slider_edit_url,
            'grid_thumb_url' => $grid_thumb_url,
            'preview_url' => $slider_preview_url
            )
        );

        #endregion
    }

    /**
     * activate plugin
     *
     * @return void
     */
    public function activate_plugin()
    {
        check_ajax_referer('wjssl-purchase');
        $this->check_permissions();

        $purchase_code = sanitize_text_field($_POST['purchase_code']);
        if (empty($purchase_code)) {
            wp_send_json_error(array(
                'message' => __('The purchase code is empty.', 'jssor-slider')
            ));
        }
        $last_time = get_option('wjssl_activate_request_time', 0);
        update_option('wjssl_activate_request_time', time());
        // last request should be 6sec ago
        $time_elapsed = time() - $last_time;
        if($time_elapsed < 6) {
            sleep(6 - $time_elapsed);
        }
        //if ((time() - $last_time) < 6) {
        //    wp_send_json_error(array(
        //        'message' => sprintf(__('The time interval of the activation request should be more than %d seconds.', 'jssor-slider'), 6)
        //    ));
        //}
        $url = WP_Jssor_Slider_Globals::URL_JSSOR_SECURE() . WP_Jssor_Slider_Globals::URL_JSSOR_ACTIVATE;

        $instance_id = get_option('wp_jssor_slider_instance_id', '');
        $data = array(
            'jssorext' => WP_JSSOR_SLIDER_EXTENSION_NAME,
            'hosturl' => esc_url_raw(WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url()),
            'instid' => $instance_id,
            'purchcode' => $purchase_code,
        );

        $remote_url = esc_url_raw($url);
        $accessible_url = WP_Jssor_Slider_Utils::to_accessible_jssor_url($remote_url);
        $response = wp_remote_post($accessible_url, array(
            'body' => array(
                'data' => json_encode($data)
            ),
            'timeout' => 30
        ));

        update_option('wjssl_activate_request_time', time());
        if (is_wp_error($response)) {
            wp_send_json_error(array(
                'message' => $response->get_error_message()
            ));
        }

        $return = json_decode($response['body'], true);
        if (empty($return['error'])) {
            // code...
            if (!empty($return['newinst'])) {
                update_option('wp_jssor_slider_instance_id', WP_Jssor_Slider_Utils::create_guid());
                wp_send_json_error(array(
                    'message' => 'This is a copy of another instance, a new instance id has been generated, please re-active this plugin.'
                ));
            }
            update_option('wjssl_actcode', $return['actcode']);
            update_option('wjssl_acckey', $return['acckey']);
            update_option('wjssl_purchcode', $purchase_code);

            Jssor_Slider_Dal::clear_all_html_code_path();

            //update_option('wjssl-valid', 'true');

            wp_send_json_success(array(
                'actcode' => $return['actcode'],
                'message' => __('Purchase Code activated.', 'jssor-slider')
            ));
        }

        wp_send_json_error(array(
            'message' => $return['message']
        ));
    }

    /**
     * deactivate plugin
     *
     * @return void
     */
    public function deactivate_plugin()
    {
        check_ajax_referer('wjssl-purchase');
        $this->check_permissions();

        $url = WP_Jssor_Slider_Globals::URL_JSSOR_SECURE() . WP_Jssor_Slider_Globals::URL_JSSOR_DEACTIVATE;
        $instance_id = get_option('wp_jssor_slider_instance_id', '');
        $purchcode = get_option('wjssl_purchcode', '');
        $acckey = get_option('wjssl_acckey', '');

        $data = array(
            'jssorext' => WP_JSSOR_SLIDER_EXTENSION_NAME,
            'hosturl' => esc_url_raw(WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url()),
            'instid' => $instance_id,
            'purchcode' => $purchcode,
            'acckey' => $acckey
        );

        $remote_url = esc_url_raw($url);
        $accessible_url = WP_Jssor_Slider_Utils::to_accessible_jssor_url($remote_url);
        $response = wp_remote_post($accessible_url, array(
            'body' => array(
                'data' => json_encode($data)
            ),
            'timeout' => 30
        ));
        if (is_wp_error($response)) {
            wp_send_json_error(array(
                'message' => $response->get_error_message()
            ));
        }

        $return = json_decode($response['body'], true);
        if (empty($return['error'])) {
            if (!empty($return['newinst'])) {
                update_option('wp_jssor_slider_instance_id', WP_Jssor_Slider_Utils::create_guid());
            }
            delete_option('wjssl_actcode');
            delete_option('wjssl_acckey');
            delete_option('wjssl_purchcode');
            //delete_option('wjssl-valid');
            //
            Jssor_Slider_Dal::clear_all_html_code_path();

            wp_send_json_success(array('message' => __('Purchase Code deregistered.', 'jssor-slider')));
        }

        wp_send_json_error(array('message' => $return['message']));
    }

    /**
     * check for updates
     */
    public function check_for_updates()
    {
        check_ajax_referer('wjssl-update');
        $this->check_permissions();

        if (WP_JSSOR_SLIDER_UPDATE_FROM_WP) {
            wp_send_json_success(WP_Jssor_Slider_Globals::get_jssor_wordpress_updates_info());
        }

        $jssor_slider_update = new Jssor_Slider_Update();
        $force_check = true;
        if (isset($_POST['noforce'])
            &&
            (!empty($_POST['noforce']))
        ) {
            $force_check = false;
        }

        try {
		    $jssor_slider_update->check_version_info($force_check);

            if($jssor_slider_update->has_error()) {
                return wp_send_json_error(array(
                    'message' => $jssor_slider_update->get_error_message()
                ));
            }
        }
        catch(Exception $e) {
            return wp_send_json_error(array(
                'message' => $e->getMessage()
            ));
        }

        wp_send_json_success(WP_Jssor_Slider_Globals::get_jssor_wordpress_updates_info());
    }

    /**
     * check status of capability of updates/connection
     *
     * @return bool
     */
    public function check_status()
    {
        check_ajax_referer('wjssl-status');

        $this->check_permissions();

        if (WP_JSSOR_SLIDER_UPDATE_FROM_WP) {
            $status = WP_Jssor_Slider_Utils::get_jssor_wordpress_status_info();

            wp_send_json_success($status);
        }

        $jssor_slider_update = new Jssor_Slider_Update();
        $force_check = true;
        if (isset($_POST['noforce'])
            &&
            (!empty($_POST['noforce']))
        ) {
            $force_check = false;
        }

        try {
		    $jssor_slider_update->check_version_info($force_check);

            //if($jssor_slider_update->has_error()) {
            //    return wp_send_json_error(array(
            //        'message' => $jssor_slider_update->get_error_message()
            //    ));
            //}
        }
        catch(Exception $e) {
            return wp_send_json_error(array(
                'message' => $e->getMessage()
            ));
        }

        $status = WP_Jssor_Slider_Utils::get_jssor_wordpress_status_info();

        if($jssor_slider_update->has_error()) {
            $status['error'] = 1;
            $status['message'] = $jssor_slider_update->get_error_message();
        }

        wp_send_json_success($status);
    }

    /**
     * check ajax call permission
     *
     * @return bool
     */
    private function check_permissions()
    {
        $permission_allowed = current_user_can('manage_options');

        if(!$permission_allowed) {
            wp_send_json_error(array(
                'message' => __("Permission Denied!", 'jssor-slider')
            ));
        }

        return $permission_allowed;
    }
}
