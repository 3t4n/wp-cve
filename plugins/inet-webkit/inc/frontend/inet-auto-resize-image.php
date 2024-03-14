<?php
$inet_wk_options = get_option('inet_wk');
if (isset($inet_wk_options['limit-image-size']) && $inet_wk_options['limit-image-size']) {
    add_filter('wp_handle_upload_prefilter', 'inet_validate_image_size');

    /**
     * @param $file
     * @return mixed|void
     */
    function inet_validate_image_size($file)
    {
        $inet_wk_options = get_option('inet_wk');
        if (isset($inet_wk_options['limit-image-size']) && $inet_wk_options['limit-image-size']) {
            $image_size = $file['size'] / 1024;
            $limit = esc_textarea($inet_wk_options['limit-image-size']);
            $is_image = strpos($file['type'], 'image');
            if (($image_size > $limit) && ($is_image !== false)) {
                $file['error'] = __("Image size exceeds $limit kb, please try again!", 'inet');
                return $file;
            }
            return $file;
        }
    }
}

add_action('wp_handle_upload', 'inet_upload_resize_image');
/**
 * @param $image_data
 * @return mixed
 */
function inet_upload_resize_image($image_data)
{
    inet_resize_error_log("**-start--resize-image-upload");
    $inet_wk_options = get_option('inet_wk');

    $max_width = $inet_wk_options['limit-image-width'];
    $max_height = $inet_wk_options['limit-image-height'];
    if (isset($inet_wk_options['inet-webkit-resize-image']['auto-resize-image']) && $inet_wk_options['inet-webkit-resize-image']['auto-resize-image']) {
        $fatal_error_reported = false;
        $valid_types = array('image/gif', 'image/png', 'image/jpeg', 'image/jpg');
        if (empty($image_data['file']) || empty($image_data['type'])) {
            inet_resize_error_log("--non-data-in-file-( " . print_r($image_data, true) . " )");
            $fatal_error_reported = true;
        } else if (!in_array($image_data['type'], $valid_types)) {
            inet_resize_error_log("--non-image-type-uploaded-( " . $image_data['type'] . " )");
            $fatal_error_reported = true;
        }
        inet_resize_error_log("--filename-( " . $image_data['file'] . " )");

        $image_editor = wp_get_image_editor($image_data['file']);
        if ($fatal_error_reported || is_wp_error($image_editor)) {
            inet_resize_error_log("--wp-error-reported");
        } else {
            $to_save = false;
            $resized = false;
            if (isset($inet_wk_options['inet_image_resize']) && $inet_wk_options['inet_image_resize']) {
                inet_resize_error_log("--resizing-enabled");
                $sizes = $image_editor->get_size();
                if ((isset($sizes['width']) && $sizes['width'] > $max_width)
                    || (isset($sizes['height']) && $sizes['height'] > $max_height)
                ) {
                    $image_editor->resize($max_width, $max_height, false);
                    $resized = true;
                    $to_save = true;
                    $sizes = $image_editor->get_size();
                    inet_resize_error_log("--new-size--" . $sizes['width'] . "x" . $sizes['height']);
                } else {
                    inet_resize_error_log("--no-resizing-needed");
                }
            } else {
                inet_resize_error_log("--no-resizing-requested");
            }

            // Compress image here
            inet_upload_compress_image($image_data, $resized, $to_save);
        }
    } else {
        inet_resize_error_log("--no-action-required");
    }
    inet_resize_error_log("**-end--resize-image-upload\n");

    return $image_data;
}

/**
 * @param $image_data
 * @param $resized
 * @return void
 */
function inet_upload_compress_image($image_data, $resized, &$to_save)
{
    $inet_wk_options = get_option('inet_wk');
    $compression_level = $inet_wk_options['compress-image-quality'];
    if (isset($inet_wk_options['auto-compress-image']) && $inet_wk_options['auto-compress-image'] && (
            $image_data['type'] == 'image/jpg' || $image_data['type'] == 'image/jpeg')
    ) {
        $to_save = true;
        inet_resize_error_log("--compression-level--q-" . $compression_level);
    } elseif (!$resized) {
        inet_resize_error_log("--no-forced-recompression");
    }

    $image_editor = wp_get_image_editor($image_data['file']);
    if ($to_save) {
        $image_editor->set_quality($compression_level);
        $saved_image = $image_editor->save($image_data['file']);
        inet_resize_error_log("--image-saved");
    } else {
        inet_resize_error_log("--no-changes-to-save");
    }

    return $saved_image;
}

/**
 * @param $message
 * @return void
 */
function inet_resize_error_log($message)
{
    global $DEBUG_LOGGER;
    if ($DEBUG_LOGGER) {
        error_log(print_r($message, true));
    }
}