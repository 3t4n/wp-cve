<?php
class Thumbnail_Editor_Process {

    public function __construct() {
        add_action('admin_init', array($this, 'thumb_data_update'));
    }

    public function thumb_data_update() {

        if (isset($_POST['th_edit_option']) and sanitize_text_field($_POST['th_edit_option']) == "thumb_editor_save_settings") {

            if (!isset($_POST['thumb_editor_afo_save_action_field']) || !wp_verify_nonce($_POST['thumb_editor_afo_save_action_field'], 'thumb_editor_afo_save_action')) {
                wp_die(__('Sorry, your nonce did not verify.', 'thumbnail-editor'));
            }

            if (!empty($_POST['thep_disable_srcset'])) {
                update_option('thep_disable_srcset', sanitize_text_field($_POST['thep_disable_srcset']));
            } else {
                delete_option('thep_disable_srcset');
            }

            if (!empty($_POST['thep_disable_wh'])) {
                update_option('thep_disable_wh', sanitize_text_field($_POST['thep_disable_wh']));
            } else {
                delete_option('thep_disable_wh');
            }

            $GLOBALS['msg'] = __('Plugin data saved successfully.', 'thumbnail-editor');

        }

        if (isset($_POST['th_edit_option']) and sanitize_text_field($_POST['th_edit_option']) == "imageCrop") {
            $att_id = (int) sanitize_text_field($_REQUEST['att_id']);

            if ($att_id == 0 || $att_id == '') {
                wp_die(__('Image not found.', 'thumbnail-editor'));
            }

            if (!isset($_POST['th_edit_save_action_field']) || !wp_verify_nonce($_POST['th_edit_save_action_field'], 'th_edit_save_action')) {
                wp_die(__('Sorry, your nonce did not verify.', 'thumbnail-editor'));
            }

            $crop_type = sanitize_text_field($_REQUEST['crop_type']);
            $x1 = (int) sanitize_text_field($_REQUEST['x1']);
            $y1 = (int) sanitize_text_field($_REQUEST['y1']);
            $x2 = (int) sanitize_text_field($_REQUEST['x2']);
            $y2 = (int) sanitize_text_field($_REQUEST['y2']);
            $w = round((int) sanitize_text_field($_REQUEST['w']));
            $h = round((int) sanitize_text_field($_REQUEST['h']));

            $rw = round((int) sanitize_text_field($_REQUEST['rw']));
            $rh = round((int) sanitize_text_field($_REQUEST['rh']));

            $full_image = wp_get_attachment_image_src($att_id, 'full');
            $info = pathinfo($full_image[0]);
            $dirname = dirname(get_attached_file($att_id));

            // resize image //
            if ($rw != '' and $rh != '') {
                $file_name = $info['filename'] . '-up-' . time() . '.' . $info['extension'];
                $image_file = $dirname . '/' . $file_name;
                $image = wp_get_image_editor($full_image[0]);
                if (!is_wp_error($image)) {
                    $image->set_quality(100);
                    $image->resize($rw, $rh, false);
                    $image->save($image_file);
                    $up_th = wp_get_attachment_metadata($att_id);

                    $image_info = getimagesize($image_file);
                    $up_th['sizes'][$crop_type]['file'] = $file_name;
                    $up_th['sizes'][$crop_type]['width'] = $image_info[0];
                    $up_th['sizes'][$crop_type]['height'] = $image_info[1];
                    $up_th['sizes'][$crop_type]['mime-type'] = $image_info['mime'];
                    wp_update_attachment_metadata($att_id, $up_th);
                }
            }
            // resize image //

            // crop image //
            if ($rw == '' and $rh == '') {
                $parent_image = $full_image[0];
                $file_name = $info['filename'] . '-up-' . time() . '.' . $info['extension'];
                $image_file = $dirname . '/' . $file_name;
            } else {
                $parent_image = $image_file;
            }

            if ($w != '' and $h != '') {
                $image = wp_get_image_editor($parent_image);
                if (!is_wp_error($image)) {

                    $image->set_quality(100);
                    $image->crop($x1, $y1, $w, $h);
                    $image->save($image_file);
                    $up_th = wp_get_attachment_metadata($att_id);

                    $image_info = getimagesize($image_file);
                    $up_th['sizes'][$crop_type]['file'] = $file_name;
                    $up_th['sizes'][$crop_type]['width'] = $image_info[0];
                    $up_th['sizes'][$crop_type]['height'] = $image_info[1];
                    $up_th['sizes'][$crop_type]['mime-type'] = $image_info['mime'];
                    wp_update_attachment_metadata($att_id, $up_th);

                }
            }
            // crop image //	

            $GLOBALS['msg'] = "<strong>{$crop_type}</strong> " . __('image successfully updated.', 'thumbnail-editor');
        }
    }
}
