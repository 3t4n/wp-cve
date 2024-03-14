<?php

/**
 *
 * @link    https://www.jssor.com
 * @author  jssor
 *
 */

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

class Jssor_Slider_Dal {
    public static function get_slider_data_by_id($slider_id, &$error_message) {
        $slider_data = null;
        $error_message = null;

        global $wpdb;
        $wpdb->suppress_errors();
        $table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_SLIDERS;

        $row = $wpdb->get_row( $wpdb->prepare(
            "
            SELECT *
            FROM $table_name
            WHERE id = %d
            ",
            $slider_id
        ), ARRAY_A );

        if(is_null($row)) {
            if(!empty($wpdb->last_error)) {
                $error_message = $wpdb->last_error;
            }
        }
        else if (!empty($row)) {
            $slider_data = $row;
        }

        return $slider_data;
    }

    public static function get_slider_data_by_file_name($file_name, &$error_message) {
        $slider_data = null;
        $error_message = null;

        global $wpdb;
        $wpdb->suppress_errors();
        $table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_SLIDERS;

        $row = $wpdb->get_row( $wpdb->prepare(
            "
            SELECT *
            FROM $table_name
            WHERE file_name = %s
            ",
            $file_name
        ), ARRAY_A );

        if(empty($row)) {
            if(!empty($wpdb->last_error)) {
                $error_message = $wpdb->last_error;
            }
        }
        else if (!empty($row)) {
            $slider_data = $row;
        }

        return $slider_data;
    }

    public static function get_all_slider_data(&$error_message) {
        global $wpdb;

        $wpdb->suppress_errors();
        $table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_SLIDERS;

        $result = $wpdb->get_results(
            'SELECT * FROM ' . $table_name . ' ' .
            'ORDER BY ID',
            ARRAY_A
        );

        if(empty($result)) {
            if(!empty($wpdb->last_error)) {
                $error_message = $wpdb->last_error;
            }
        }

        return $result;
    }

    public static function delete_slider_data($slider_id, &$error_message) {
        $deleted = true;
        $error_message = null;

        global $wpdb;
        $wpdb->suppress_errors();
        $table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_SLIDERS;
        $result = $wpdb->delete($table_name, array( 'id' => $slider_id ), array('%d') );

        if($result === false) {
            $deleted = false;
            $error_message = $wpdb->last_error;

            if(empty($error_message)) {
                $error_message = null;
            }
        }

        return $deleted;
    }

    public static function insert_slider_data(&$slider_data, &$error_message) {
        $inserted = false;
        $error_message = null;

        //clear unused columns
        unset($slider_data['code_path']);
        unset($slider_data['list_thumb_path']);

        global $wpdb;
        $wpdb->suppress_errors();
        $table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_SLIDERS;
        $slider_data['created_at'] = date('Y-m-d H:i:s');
        $result = $wpdb->insert($table_name, $slider_data);
        if($result === false) {
            $error_message = $wpdb->last_error;

            if(empty($error_message)) {
                $error_message = null;
            }
        }
        else if($result != 0) {
            $inserted = true;
            $slider_data['id'] = $wpdb->insert_id;
        }

        return $inserted;
    }

    public static function update_slider_data(&$slider_data, &$error_message) {
        $updated = true;
        $error_message = null;

        //clear unused columns
        unset($slider_data['code_path']);
        unset($slider_data['list_thumb_path']);

        global $wpdb;
        $wpdb->suppress_errors();
        $table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_SLIDERS;
        $slider_data['updated_at'] = date('Y-m-d H:i:s');
        $result = $wpdb->update($table_name, $slider_data, array('id' => $slider_data['id']));

        if($result === false) {
            $updated = false;
            $error_message = $wpdb->last_error;

            if(empty($error_message)) {
                $error_message = null;
            }
        }
        else if($result === 0) {
            $updated = false;
            $error = __('The slider %s is not found.', 'jssor-slider');
            $error_message = sprintf($error, strval($slider_data['id']));
        }

        return $updated;
    }

    public static function clear_all_html_code_path() {
        global $wpdb;
        $table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_SLIDERS;
        return $wpdb->query(
            "UPDATE $table_name SET `code_path` = '', `html_path` = ''"
            );
    }

    public static function find_sliders_without_files($limit = 10) {
        if (empty($limit) || !is_int($limit)) {
            $limit = 10;
        }
        global $wpdb;
        $table = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_SLIDERS;
        return $wpdb->get_results(
            "SELECT * FROM $table WHERE code_path = '' or html_path = '' ORDER BY id desc LIMIT $limit"
        );
    }
}

class Jssor_Slider_Transaction_Dal {
    public static function get_all_transaction_data() {
        global $wpdb;

        $table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_TRANSACTIONS;

        return $wpdb->get_results(
            'SELECT * FROM ' . $table_name . ' ' .
            'ORDER BY ID',
            ARRAY_A
        );
    }

    public static function insert_transaction_data(&$transaction_data, &$error_message) {
        $inserted = true;
        $error_message = null;

        global $wpdb;
        $wpdb->suppress_errors();
        $table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_TRANSACTIONS;
        $transaction_data['created_at'] = date('Y-m-d H:i:s');
        $result = $wpdb->insert($table_name, $transaction_data);
        if($result === false) {
            $inserted = false;
            $error_message = $wpdb->last_error;

            if(empty($error_message)) {
                $error_message = null;
            }
        }

        return $inserted;
    }

    public static function delete_transaction_data($transaction_id, &$error_message) {
        $deleted = true;
        $error_message = null;

        global $wpdb;
        $wpdb->suppress_errors();
        $table_name = $wpdb->prefix . WP_Jssor_Slider_Globals::TABLE_TRANSACTIONS;
        $result = $wpdb->delete($table_name, array( 'id' => $transaction_id ), array('%s') );

        if($result === false) {
            $deleted = false;

            $error_message = $wpdb->last_error;

            if(empty($error_message)) {
                $error_message = null;
            }
        }

        return $deleted;
    }
}

class Jssor_Slider_Bll {

    const REG_HTML_CODE_HEADER = '/^[\t\n\r]*<!--(#region )?jssor-slider(-begin)? (.+?)-->/';
    const REG_HTML_CODE_FOOTER = '/<!--(#endregion )?jssor-slider(-end)?-->[\t\n\r\0\x0B]*$/';
    const REG_HTML_CODE_WITH_JSSOR_SLIDER_LIBRARY = '#<script .*?[ ]?src="[^"]*?/jssor\.slider-[\d.]*?\.min\.js".*?>#';

    private static function delete_slider_file($rel_path) {
        if(!is_null($rel_path) && !empty($rel_path)) {
            $upload_dir = wp_upload_dir();
            $full_path = $upload_dir['basedir'] . '/' . ltrim($rel_path, '/');
            @unlink($full_path);

            //delete 2 level of empty dirs
            $dir_name = dirname($full_path);
            if(WP_Jssor_Slider_Utils::is_dir_empty($dir_name)) {
                rmdir($dir_name);

                $dir_name = dirname($dir_name);
                if(WP_Jssor_Slider_Utils::is_dir_empty($dir_name)) {
                    rmdir($dir_name);
                }
            }
        }
    }

    public static function get_slider_data_by_id_or_alias($id_or_alias, &$error_message) {
        $id_or_alias = strval($id_or_alias);

        $slider_id = null;
        $slider_name = null;

        if(strpos($id_or_alias, '.') === false) {
            $slider_id = intval($id_or_alias);
        }
        else {
            $slider_name = $id_or_alias;
        }

        $slider_data = null;

        if(!empty($slider_id)) {
            $slider_data = Jssor_Slider_Dal::get_slider_data_by_id($slider_id, $error_message);
        }
        else if(!empty($slider_name)) {
            $slider_data = Jssor_Slider_Dal::get_slider_data_by_file_name($slider_name, $error_message);
        }

        return $slider_data;
    }

    private static function delete_slider_html_files($slider_data) {
        if(isset($slider_data['code_path'])) {
            Jssor_Slider_Bll::delete_slider_file($slider_data['code_path']);
        }
        if(isset($slider_data['html_path'])) {
            Jssor_Slider_Bll::delete_slider_file($slider_data['html_path']);
        }

        Jssor_Slider_Bll::delete_slider_file(Jssor_Slider_Bll::get_slider_html_code_rel_path($slider_data['id']));
        Jssor_Slider_Bll::delete_slider_file(Jssor_Slider_Bll::get_slider_html_code_rel_path($slider_data['file_name']));
    }

    public static function do_rename_slider_cleanup_transaction($rename_slider_trans_data, $supress_error = true) {
        try {
            $slider_data = json_decode($rename_slider_trans_data['meta'], true);

            if(!is_null($slider_data)) {
                $file_name = $slider_data['file_name'];

                if(!empty($file_name)) {
                    $existing_slider_data = Jssor_Slider_Dal::get_slider_data_by_file_name($file_name, $error_message);

                    if(!is_null($error_message)) {
                        throw new Exception($error_message);
                    }

                    if(is_null($existing_slider_data)) {
                        //delete html file associated with 'file_name'
                        Jssor_Slider_Bll::delete_slider_file(Jssor_Slider_Bll::get_slider_html_code_rel_path($file_name));
                    }
                }
            }
        }
        catch (Exception $e) {
            if(!$supress_error) {
                throw $e;
            }
        }

        try {
            if(!Jssor_Slider_Transaction_Dal::delete_transaction_data($rename_slider_trans_data['id'], $error_message)) {
                if(!is_null($error_message)) {
                    throw new Exception($error_message);
                }
            }
        }
        catch(Exception $e) {
            if(!$supress_error) {
                throw $e;
            }
        }
    }

    public static function do_delete_slider_cleanup_transaction($delete_slider_trans_data, $supress_error = true) {
        $error_message = null;

        try {
            $slider_data = json_decode($delete_slider_trans_data['meta'], true);

            if(!is_null($slider_data)) {
                $slider_id = $slider_data['id'];
                $existing_slider_data = Jssor_Slider_Dal::get_slider_data_by_id($slider_id, $error_message);

                if(!is_null($error_message)) {
                    throw new Exception($error_message);
                }

                //confirm the slider has been deleted
                if(is_null($existing_slider_data)) {
                    //delete html files
                    Jssor_Slider_Bll::delete_slider_html_files($slider_data);

                    //delete slider file
                    if(isset($slider_data['file_path'])) {
                        Jssor_Slider_Bll::delete_slider_file($slider_data['file_path']);
                    }
                }
            }
        }
        catch (Exception $e) {
            if(!$supress_error) {
                throw $e;
            }
        }

        try {
            if(!Jssor_Slider_Transaction_Dal::delete_transaction_data($delete_slider_trans_data['id'], $error_message)) {
                if(!is_null($error_message)) {
                    throw new Exception($error_message);
                }
            }
        }
        catch(Exception $e) {
            if(!$supress_error) {
                throw $e;
            }
        }
    }

    public static function do_cleanup_transactions() {
        $slider_transaction_data_array = Jssor_Slider_Transaction_Dal::get_all_transaction_data();

        if(!empty($slider_transaction_data_array)) {
            foreach($slider_transaction_data_array as $slider_transaction_data) {
                switch($slider_transaction_data['type']) {
                    case WP_Jssor_Slider_Globals::TRANSACTION_TYPE_DELETE_SLIDER_CLEANUP:
                        Jssor_Slider_Bll::do_delete_slider_cleanup_transaction($slider_transaction_data);
                        break;
                    case WP_Jssor_Slider_Globals::TRANSACTION_TYPE_RENAME_SLIDER_CLEANUP:
                        Jssor_Slider_Bll::do_rename_slider_cleanup_transaction($slider_transaction_data);
                        break;
                }
            }
        }
    }

    public static function delete_slider($slider_id, &$error_message) {
        $deleted = false;
        $error_message = null;

        $slider_data = Jssor_Slider_Dal::get_slider_data_by_id($slider_id, $error_message);

        if(is_null($slider_data)) {
            if(is_null($error_message)) {
                $error_message = __('The slider %s is not found.', 'jssor-slider');
                $error_message = wp_sprintf($error_message, strval($slider_id));
            }
        }
        else {

            $delete_slider_trans_data = array(
                'id' => uniqid(),
                'type' => WP_Jssor_Slider_Globals::TRANSACTION_TYPE_DELETE_SLIDER_CLEANUP,
                'meta' => json_encode($slider_data)
                );

            if(Jssor_Slider_Transaction_Dal::insert_transaction_data($delete_slider_trans_data, $error_message)) {
                $deleted = Jssor_Slider_Dal::delete_slider_data($slider_id, $error_message);
                Jssor_Slider_Bll::do_delete_slider_cleanup_transaction($delete_slider_trans_data);
            }
        }

        return $deleted;
    }

    /**
     * update slider and clean up cache files
     * @param array $slider_data
     * @param string $error_message
     * @return boolean
     */
    public static function rename_slider(&$slider_data, $new_file_name, &$error_message) {
        $renamed = false;

        $rename_slider_trans_data = array(
            'id' => uniqid(),
            'type' => WP_Jssor_Slider_Globals::TRANSACTION_TYPE_RENAME_SLIDER_CLEANUP,
            'meta' => json_encode(array_merge(array('new_name' => $new_file_name), $slider_data))
            );

        if(Jssor_Slider_Transaction_Dal::insert_transaction_data($rename_slider_trans_data, $error_message)) {

            $slider_data['file_name'] = $new_file_name;
            //don't need code_path and html_path any more
            $slider_data['code_path'] = '';
            $slider_data['html_path'] = '';

            $renamed = Jssor_Slider_Dal::update_slider_data($slider_data, $error_message);
            Jssor_Slider_Bll::do_rename_slider_cleanup_transaction($rename_slider_trans_data);
        }

        return $renamed;
    }

    /**
     * create a new slider with jssor slider json model object
     * returns slider data array
     *
     * @return array|null
     */
    public static function create_new_slider($slider_json_model, $file_name, &$error_message) {
        $slider_data = null;
        $error_message = null;

        $upload = wp_upload_dir();
        $time_str = date('Y/m', time());
        $slider_rel_dir =  WP_Jssor_Slider_Globals::UPLOAD_SLIDER . '/' . $time_str;

        if (!wp_mkdir_p($upload['basedir'] . $slider_rel_dir)) {
            $error_message = 'Failed to create directory for a slider.';
        }
        else {
            //insert_slider_data
            $temp_slider_data = array(
                'file_name' => $file_name
                );

            if(Jssor_Slider_Dal::insert_slider_data($temp_slider_data, $error_message)) {
                try {
                    $slider_rel_file_path = $slider_rel_dir . '/' . $temp_slider_data['id'] . '.slider';
                    $json_text = wp_json_encode($slider_json_model);
                    if(@file_put_contents($upload['basedir'] . $slider_rel_file_path, $json_text)) {
                        $thumb_path = Jssor_Slider_Bll::extract_slider_thumbnail_image_url($slider_json_model);
                        $thumb_path = Jssor_Slider_Bll::resolve_persistent_image_url($thumb_path);

                        $grid_thumb_path = Jssor_Slider_Bll::resolve_persistent_grid_thumb_url($thumb_path);

                        $temp_slider_data['file_path'] = $slider_rel_file_path;
                        $temp_slider_data['thumb_path'] = $thumb_path;
                        $temp_slider_data['grid_thumb_path'] = $grid_thumb_path;

                        if(Jssor_Slider_Dal::update_slider_data($temp_slider_data, $error_message)) {
                            $slider_data = $temp_slider_data;
                        }
                        else {
                            //should clean up slider files
                        }
                    }
                    else {
                        $error_message = 'Failed to write slider file.';
                        //should clean up slider files
                    }
                }
                catch(Exception $e) {
                    $error_message = $e->getMessage();
                }

                if(is_null($slider_data)) {
                    //clean up slider file
                    Jssor_Slider_Bll::delete_slider_file($temp_slider_data['file_path']);
                    Jssor_Slider_Dal::delete_slider_data($temp_slider_data['id'], $error_message);
                }
            }
        }

        return $slider_data;
    }

    /**
     * update a slider with jssor slider json model object
     * returns new slider data array
     *
     * @return array|null
     */
    public static function save_existing_slider($slider_json_model, $old_slider_data, &$error_message) {
        $slider_data = null;
        $error_message = null;

        try {
            $temp_slider_data = array_merge(array(), $old_slider_data);

            $slider_rel_file_path = $temp_slider_data['file_path'];

            #region backward compatibility, correct file path

            if (empty($file_path) || Jssor_Slider_Bll::is_template_slider_file_path($file_path)) {
                $time_str = date('Y/m', time());
                $temp_slider_data['file_path'] = $slider_rel_file_path = WP_Jssor_Slider_Globals::UPLOAD_SLIDER . '/' . $time_str . '/' . $temp_slider_data['id'] . '.slider';
            }

            #endregion

            $json_text = wp_json_encode($slider_json_model);

            //always clean up slider html files
            Jssor_Slider_Bll::delete_slider_html_files($old_slider_data);

            $upload = wp_upload_dir();
            if(@file_put_contents($upload['basedir'] . $slider_rel_file_path, $json_text)) {
                $thumb_path = Jssor_Slider_Bll::extract_slider_thumbnail_image_url($slider_json_model);
                $thumb_path = Jssor_Slider_Bll::resolve_persistent_image_url($thumb_path);
                $grid_thumb_path = Jssor_Slider_Bll::resolve_persistent_grid_thumb_url($thumb_path);

                $temp_slider_data['thumb_path'] = $thumb_path;
                $temp_slider_data['grid_thumb_path'] = $grid_thumb_path;

                if(Jssor_Slider_Dal::update_slider_data($temp_slider_data, $error_message)) {
                    $slider_data = $temp_slider_data;
                }
            }
            else {
                $error_message = 'Failed to write slider file.';
            }
        }
        catch(Exception $e) {
            $error_message = $e->getMessage();
        }

        return $slider_data;
    }

    public static function get_shortcode_with_alias($alias) {
        return sprintf('[jssor-slider alias="%s"]', $alias);
    }

    public static function get_shortcode_do_format($alias) {
        return sprintf('<?php echo do_shortcode("[jssor-slider alias=\'%s\']")?>', $alias);
    }

    public static function get_shortcode_put_format($alias) {
        return sprintf('<?php pubJssorSlider("[jssor-slider alias=\'%s\']")?>', $alias);
    }

    public static function check_slider_file_name_error($file_name) {
        $file_name_error = null;

        $file_name = strtolower($file_name);
        if ('new.slider' === $file_name) {
            $file_name_error = 'The slider name \'new.slider\' is reserved, please specify another name.';
        } else if(preg_match('/[\?:\*"\'<>\|%\/\\\]/', $file_name)) {
            $file_name_error = 'Invalid characters (\ / : * ? " \' < > | %) found in file name.';
        }

        return $file_name_error;
    }

    /**
     * get slider 220x160 thumbnail to display temporarily
     *
     * @return string|null
     */
    public static function get_slider_grid_thumb_url($slider_data) {
        $grid_thumb_path = $slider_data['grid_thumb_path'];
        $thumb_path = $slider_data['thumb_path'];

        if (!empty($grid_thumb_path)) {
            if((stripos($grid_thumb_path, 'http://') !== 0) && (stripos($grid_thumb_path, 'https://') !== 0) && (stripos($grid_thumb_path, '/') !== 0)) {
                $upload = wp_upload_dir();
                $grid_thumb_path = $upload['baseurl']  . '/' . ltrim($grid_thumb_path, '/');
            }
        }
        else if (!empty($thumb_path)) {
            //as 'thumb_path' has been cleared from database for version less than 3.1.0
            //for backward compatibility, auto gen thumbnail using url format '?jssorextver=%s&method=crop_img&size=%sx%s&url=%s'

            $thumb_sizes = WP_Jssor_Slider_Globals::get_jssor_slider_thumb_sizes();
            $thumb_width = $thumb_sizes['jssor-grid-thumb']['width'];
            $thumb_height = $thumb_sizes['jssor-grid-thumb']['height'];
            if (WP_Jssor_Slider_Utils::is_import_tag_url($thumb_path)) {
                $grid_thumb_path = WP_Jssor_Slider_Utils::format_crop_img_url($thumb_path, $thumb_width, $thumb_height);
            }
            else {
                $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($thumb_path);
                if($local_res_info->is_valid && $local_res_info->under_upload_dir) {
                    $temp_grid_thumb_Path = WP_Jssor_Slider_Utils::get_thumb_path($local_res_info->local_url, $thumb_width, $thumb_height);
                    $temp_grid_thumb_local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($temp_grid_thumb_Path);
                    if($temp_grid_thumb_local_res_info->exists()) {
                        //the actual thumbnail url found
                        $grid_thumb_path = $temp_grid_thumb_Path;
                    }
                    else {
                        $grid_thumb_path = WP_Jssor_Slider_Utils::format_crop_img_url($thumb_path, $thumb_width, $thumb_height);
                    }
                }
            }
            if(empty($grid_thumb_path)) {
                $grid_thumb_path = $thumb_path;
            }
        }

        return $grid_thumb_path;
    }

    /**
     * resolve slider 220x160 thumbnail for persistence
     * returns actual 220x160 thumbnila url if found
     * @return string|null
     */
    private static function resolve_persistent_grid_thumb_url($prototype_image_url) {
        $grid_thumb_url = '';

        if(!empty($prototype_image_url)) {
            $resolved_url = $prototype_image_url;

            //convert import tag url as possible
            if(WP_Jssor_Slider_Utils::is_import_tag_url($prototype_image_url)) {
                $resolved_url = substr($prototype_image_url, 8);
            }

            $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($resolved_url);
            $jssor_res_info = null;

            if(!$local_res_info->is_valid) {
                $temp_jssor_res_info = WP_Jssor_Slider_Utils::to_jssor_res_info($resolved_url);
                if($temp_jssor_res_info->is_valid) {
                    $jssor_res_info = $temp_jssor_res_info;
                    $resolved_url = $temp_jssor_res_info->local_url;
                    $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($resolved_url);
                }
            }

            //find the exact thumbnail file
            if($local_res_info->is_valid && $local_res_info->under_upload_dir) {
                if($local_res_info->exists()) {
                    $thumb_sizes = WP_Jssor_Slider_Globals::get_jssor_slider_thumb_sizes();
                    $thumb_width = $thumb_sizes['jssor-grid-thumb']['width'];
                    $thumb_height = $thumb_sizes['jssor-grid-thumb']['height'];

                    $thumb_file_abs_path = WP_Jssor_Slider_Utils::get_thumb_path($local_res_info->local_path, $thumb_width, $thumb_height);

                    if(file_exists($thumb_file_abs_path)) {
                        $grid_thumb_url = WP_Jssor_Slider_Utils::get_thumb_path($local_res_info->upload_rel_path, $thumb_width, $thumb_height);;
                    }
                    else {
                        $metadata = WP_Jssor_Slider_Utils::ensure_metadata($local_res_info, $attach_id);

                        if(is_null($metadata)) {
                            //for some file that cannot generate metadata (e.g. *.svg), grid thumb will be the file itself.
                            $grid_thumb_url = $local_res_info->upload_rel_path;
                        }
                        else {
                            $image_width = $metadata['width'];
                            $image_height = $metadata['height'];

                            if(empty($image_width) || empty($image_height)) {
                                //for some file without width and height, grid thumb will be the file itself.
                                $grid_thumb_url = $local_res_info->upload_rel_path;
                            }
                            else if ($image_width <= $thumb_width && $image_height <= $thumb_height) {
                                //for some image is smaller than grid thumb size, grid thumb will be the file itself.
                                $grid_thumb_url = $local_res_info->upload_rel_path;
                            }
                            else {
                                //the exact thumbnail file doesnot exist, it will be ready
                                //do nothing, keep resolving next time
                            }
                        }
                    }
                }
                else if(is_null($jssor_res_info)) {
                    //local file doesnot exist, jssor res doesnot exist, grid thumb will be the bad image itself
                    $grid_thumb_url = $local_res_info->upload_rel_path;
                }
                else {
                    //local is valid under upload dir but doesn't exist, and jssor res is valid, it may come available sometime.
                    //do nothing, keep resolving next time
                }
            }
            else {
                //for file that failed to map to local res under upload dir, there is no way to generate thumb, grid thumb will be the file itself.
                $grid_thumb_url = $resolved_url;
            }
        }

        return $grid_thumb_url;
    }

    /**
     * resolve image url for persistence
     * returns the actual local image url if found, otherwise returns original image url
     * @return string|null
     */
    private static function resolve_persistent_image_url($image_url) {
        $resolved_url = '';

        if(!empty($image_url)) {
            $resolved_url = $image_url;

            //convert import tag url as possible
            if(WP_Jssor_Slider_Utils::is_import_tag_url($image_url)) {
                $jssor_url = substr($image_url, 8);

                $jssor_res_info = WP_Jssor_Slider_Utils::to_jssor_res_info($jssor_url);
                if($jssor_res_info->is_valid) {
                    $local_url = $jssor_res_info->local_url;
                    $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($local_url);

                    //find the exact image file
                    if($local_res_info->is_valid && $local_res_info->under_upload_dir && $local_res_info->exists()) {
                        $resolved_url = $local_url;
                    }
                }
            }
            else {
                $resolved_url = $image_url;

                //$local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($image_url);
                //if($local_res_info->is_valid) {
                //    $resolved_url = $local_res_info->local_url;
                //}
            }
        }

        return $resolved_url;
    }

    public static function to_safe_slider_file_name($file_name) {
        if(!empty($file_name)) {
            $file_name = sanitize_file_name($file_name);
            $file_name = strtolower($file_name);

            if (substr($file_name, -7) !== '.slider') {
                $file_name .= '.slider';
            }
        }
        return $file_name;
    }

    public static function is_template_slider_file_path($slider_file_path) {
        return stripos($slider_file_path, WP_Jssor_Slider_Globals::UPLOAD_TEMPLATE) === 0;
    }

    public static function get_template_slider_file_path() {
        return WP_Jssor_Slider_Globals::UPLOAD_TEMPLATE . '/001.slider';
    }

    private static function extract_slider_thumbnail_image_url($slider_json_model) {
        if (isset($slider_json_model->slides) && !empty($slider_json_model->slides)) {
            if (isset($slider_json_model->slides[0]->image)) {
                return $slider_json_model->slides[0]->image;
            }
        }

        if(isset($slider_json_model->layouts) && isset($slider_json_model->layouts->layout) && isset($slider_json_model->layouts->layout->ocBgImage)) {
            return $slider_json_model->layouts->layout->ocBgImage;
        }

        return '';
    }

    #region slider contents, slider document, html

    /**
     * @param array $slider_data
     * @return stdClass|null;
     */
    public static function read_slider_json_model($slider_data, $error_message, $assoc = false) {
        $slider_json_model = null;

        $upload = wp_upload_dir();
        $slider_text_path = $upload['basedir'] . $slider_data['file_path'];
        $slider_json_text = @file_get_contents($slider_text_path);
        if($slider_json_text === false) {
            $error_message = 'Failed to read slider file.';
        }
        else {
            $slider_json_model = json_decode($slider_json_text, $assoc);
            if(is_null($slider_json_model)) {
                $error_message = 'Failed to parse slider file.';
            }
        }

        return $slider_json_model;
    }

    private static function get_slider_html_code_rel_path($id_or_alias) {
        $id_or_alias = strtolower(strval($id_or_alias));
        $hash = (crc32($id_or_alias) & 0x3FFF) % 10000;
        $array = array(floor($hash / 100), $hash % 100);

        return 'jssor-slider/html/' . implode('/', $array) . '/' . $id_or_alias . '.htm_';
    }

    private static function get_slider_html_code_full_path($id_or_alias) {
        $upload = wp_upload_dir();
        return $upload['basedir'] . '/' . Jssor_Slider_Bll::get_slider_html_code_rel_path($id_or_alias);
    }

    public static function format_slider_html_code_comment_header($slider_version, $slider_id, $slider_name) {
        //return "#region jssor-slider $slider_version,$slider_id,$slider_name";
        return "jssor-slider-begin $slider_version,$slider_id,$slider_name";
    }

    public static function format_slider_html_code_comment_footer() {
        //return '#endregion jssor-slider';
        return 'jssor-slider-end';
    }

    public static function read_slider_html_code($id_or_alias, &$error_message) {
        $id_or_alias = strval($id_or_alias);

        $html = '';

        try {
            $file_path = Jssor_Slider_Bll::get_slider_html_code_full_path($id_or_alias);
            if(file_exists($file_path)) {
                $temp_html = @file_get_contents($file_path);

                if($temp_html !== false) {
                    $match_header = preg_match(Jssor_Slider_Bll::REG_HTML_CODE_HEADER, $temp_html);
                    $match_footer = preg_match(Jssor_Slider_Bll::REG_HTML_CODE_FOOTER, $temp_html);

                    if($match_header && $match_footer) {
                        $html = $temp_html;
                    }
                    else {
                        //clear corrupted html file
                        @unlink($file_path);
                    }
                }
            }

            #region backward compatibility, read from old html path

            if(empty($html)) {
                $slider_data = Jssor_Slider_Bll::get_slider_data_by_id_or_alias($id_or_alias, $error_message);

                //Since 3.1.0, no 'html_path' any more
                if(!is_null($slider_data) && !empty($slider_data['html_path'])) {
                    $upload = wp_upload_dir();
                    $file_path = $upload['basedir'] . '/' . ltrim($slider_data['html_path'], '/');
                    if(file_exists($file_path)) {
                        $temp_html = @file_get_contents($file_path);

                        if($temp_html !== false) {
                            if(preg_match(Jssor_Slider_Bll::REG_HTML_CODE_WITH_JSSOR_SLIDER_LIBRARY, $temp_html)) {
                                $html =  $temp_html;
                            }
                            else {
                                Jssor_Slider_Dispatcher::load_once('includes/bll/class-jssor-slider-activator.php');
                                WP_Jssor_Slider_Activator::check_slider_script();

                                $script_url = $upload['baseurl'] . '/jssor-slider/jssor.com/script/jssor.slider-' . WP_JSSOR_MIN_JS_VERSION . '.min.js';
                                $script_library_html = "<script src='$script_url'></script>";

                                $html = '<!--' . Jssor_Slider_Bll::format_slider_html_code_comment_header(WP_JSSOR_MIN_JS_VERSION, $slider_data['id'], $slider_data['file_name']) . '-->' . $script_library_html . $temp_html;
                            }
                        }
                    }
                }
            }
        }
        catch(Exception $e) {
            $error_message = $e->getMessage();
        }

        #endregion

        return $html;
    }

    public static function save_slider_html_code($html_code, $slider_id, $slider_name, $error_message) {
        try {
            Jssor_Slider_Dispatcher::load_once('includes/bll/class-jssor-slider-activator.php');
            WP_Jssor_Slider_Activator::check_slider_script();

            $file_path = Jssor_Slider_Bll::get_slider_html_code_full_path($slider_id);
            if(@wp_mkdir_p(dirname($file_path))) {
                @file_put_contents($file_path, $html_code);
            }

            $file_path = Jssor_Slider_Bll::get_slider_html_code_full_path($slider_name);
            if(@wp_mkdir_p(dirname($file_path))) {
                @file_put_contents($file_path, $html_code);
            }
        }
        catch(Exception $e) {
            $error_message = $e->getMessage();
        }
    }

    #endregion
}

/**
 * convert resource urls in jssor slider json model
 */
abstract class Jssor_Slider_Converter {

    const RESIDENCE_OUT_CONTAINER_BACKGROUND_IMAGE = 1;
    const RESIDENCE_SLIDE_MAIN_IMAGE = 2;
    const RESIDENCE_SLIDE_THUMBNAIL_IMAGE = 3;
    const RESIDENCE_LAYER_IMAGE = 4;
    const RESIDENCE_LAYER_BACKGROUND_IMAGE = 5;

    /**
     * @var stdClass
     */
    private $slider_json_model;

    /**
     * @var array
     */
    private $converted_resource_urls = array();

    public function __construct(stdClass $slider_json_model) {
        $this->slider_json_model = $slider_json_model;
    }

    private function resolve_image_url($image_url, $residence) {
        if(!empty($image_url)) {
            if($this->convert_resource_url($image_url, $residence, $new_url)) {
                $this->converted_resource_urls[$image_url] = $new_url;
                $image_url = $new_url;
            }
        }

        return $image_url;
    }

    private function convert_layer_images($layer_json_model) {
        if(isset($layer_json_model->image)) {
            $layer_json_model->image = $this->resolve_image_url($layer_json_model->image, Jssor_Slider_Converter::RESIDENCE_LAYER_IMAGE);
        }
        if(isset($layer_json_model->bgImage)) {
            $layer_json_model->bgImage = $this->resolve_image_url($layer_json_model->bgImage, Jssor_Slider_Converter::RESIDENCE_LAYER_BACKGROUND_IMAGE);
        }
        if(isset($layer_json_model->bgImageEx) && isset($layer_json_model->bgImageEx->image)) {
            $layer_json_model->bgImageEx->image = $this->resolve_image_url($layer_json_model->bgImageEx->image, Jssor_Slider_Converter::RESIDENCE_LAYER_BACKGROUND_IMAGE);
        }
        if (isset($layer_json_model->children) && !empty($layer_json_model->children)) {
            foreach($layer_json_model->children as $child_layer_json_model) {
                $this->convert_layer_images($child_layer_json_model);
            }
        }
    }

    private function convert_slide_images($slide_json_model) {
        if(isset($slide_json_model->image)) {
            $slide_json_model->image = $this->resolve_image_url($slide_json_model->image, Jssor_Slider_Converter::RESIDENCE_SLIDE_MAIN_IMAGE);
        }
        if (isset($slide_json_model->layers) && !empty($slide_json_model->layers)) {
            foreach($slide_json_model->layers as $layer_json_model) {
                $this->convert_layer_images($layer_json_model);
            }
        }
        if (isset($slide_json_model->thumb) && !empty($slide_json_model->thumb) && isset($slide_json_model->thumb->images) && !empty($slide_json_model->thumb->images)) {
            foreach ($slide_json_model->thumb->images as $t_key => $t_value) {
                if (!empty($t_value)) {
                    $slide_json_model->thumb->images[$t_key] = $this->resolve_image_url($t_value, Jssor_Slider_Converter::RESIDENCE_SLIDE_THUMBNAIL_IMAGE);
                }
            }
        }
    }

    private function convert_slider_images($slider_json_model) {
        if(isset($slider_json_model->layouts) && isset($slider_json_model->layouts->layout) && isset($slider_json_model->layouts->layout->ocBgImage)) {
            $slider_json_model->layouts->layout->ocBgImage = $this->resolve_image_url($slider_json_model->layouts->layout->ocBgImage, Jssor_Slider_Converter::RESIDENCE_OUT_CONTAINER_BACKGROUND_IMAGE);
        }
        if (isset($slider_json_model->slides) && !empty($slider_json_model->slides)) {
            foreach($slider_json_model->slides as $slide_json_model) {
                $this->convert_slide_images($slide_json_model);
            }
        }
    }

    /**
     * Get converted resource urls
     * @return array
     */
    public function get_converted_resource_urls() {
        return $this->converted_resource_urls;
    }

    /**
     * Try to convert resource url
     * @param string $url
     * @param integer $residence
     * @param string $new_url
     */
    abstract protected function convert_resource_url($url, $residence, &$new_url);

    /**
     * Do the convert
     */
    public function convert_resource_urls() {
        $this->convert_slider_images($this->slider_json_model);
    }
}

/**
 * convert @Import tag resource urls to local image urls as possible
 */
class Jssor_Slider_Converter_Standard_To_Local extends Jssor_Slider_Converter {
    /**
     * @param string $image_url
     * @param string $new_image_url
     * @return boolean
     */
    private static function resolve_import_tag_image_url($image_url, &$new_image_url) {
        $converted = false;
        $new_image_url = null;

        if(!empty($image_url)) {
            //convert import tag url as possible
            if(WP_Jssor_Slider_Utils::is_import_tag_url($image_url)) {
                $jssor_url = substr($image_url, 8);

                $jssor_res_info = WP_Jssor_Slider_Utils::to_jssor_res_info($jssor_url);
                if($jssor_res_info->is_valid) {
                    $local_url = $jssor_res_info->local_url;
                    $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($local_url);

                    //find the exact image file
                    if($local_res_info->is_valid && $local_res_info->under_upload_dir && $jssor_res_info->ensure()) {
                        $new_image_url = $local_res_info->path;
                        $converted = true;
                    }
                }
            }
        }

        return $converted;
    }

    protected function convert_resource_url($url, $residence, &$new_url) {
        return Jssor_Slider_Converter_Standard_To_Local::resolve_import_tag_image_url($url, $new_url);
    }
}

/**
 * convert foriegn resource urls to local or @Import tag image urls
 */
class Jssor_Slider_Converter_Foriegn_To_Standard extends Jssor_Slider_Converter {

    protected function convert_resource_url($image_url, $residence, &$new_image_url) {
        $converted = false;

        //url starts with '/' and not starts with '//';
        if(preg_match('/^\/[^\/]/', $image_url)) {
            $upload = wp_upload_dir();

            $local_url = $upload['baseurl'] . WP_Jssor_Slider_Globals::UPLOAD_JSSOR_COM . $image_url;
            $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($local_url);
            if($local_res_info->exists()) {
                $new_image_url = $local_res_info->path;
            }
            else {
                $new_image_url = '@Import/https://www.jssor.com' . $image_url;
            }

            $converted = true;
        }

        return $converted;
    }
}
