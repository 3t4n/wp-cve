<?php
/**
 * input parameters
 * import: {
 *     src: "source url",
 *     name: "filename.slider",
 *     overwrite: 0,
 *     type: 0          //0: create slider, 1: import slider
 * }
 *
 * output methods:
 * progress(job, progress, item) {
 *    //job: current job that working on
 *    //progress: overall progress. e.g. 0.25 means 25%
 *    //item: current item that working on
 * }
 *
 * fail(errorCode, message) {
 *     //errorCode:
 *       1: not authenticated, login required
 *       2: authenticated, permission deinied
 *       3: slider exists already
 *     //message: detailed error message
 * }
 *
 * success(id, filename, thumbnailurl) {
 *     //id: id of the slider
 *     //filename: file name
 *     //thumbnailurl: thumbnail url of slider
 * }
 *
 * @link   https://www.jssor.com
 * @version 1.0
 * @author jssor
*/

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

//@apache_setenv('no-gzip', 1);
ini_set('zlib.output_compression', 0);
ini_set('output_buffering', 0);
ini_set('implicit_flush', 1);
set_time_limit(30 * 60);

@status_header(200);
header('X-Accel-Buffering: no');
//header('Content-Encoding: utf-8');
//header('Content-Encoding: none;');
//header('Transfer-Encoding: chunked');
header('Content-type: text/html; charset=utf-8' );
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');

@ob_end_flush();
@ob_implicit_flush(1);

require_once WP_JSSOR_SLIDER_PATH . 'includes/bll/import/class-jssor-slider-push.php';
require_once WP_JSSOR_SLIDER_PATH . 'includes/bll/import/class-wp-jssor-push-processor.php';

$jssor_push = new WP_Jssor_Push();
$jssor_push->write('<!DOCTYPE html>');
$jssor_push->write('<html xmlns="http://www.w3.org/1999/xhtml">');
$jssor_push->write('<head>');
$jssor_push->write('<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />');

//write script library

$push_server_scripts = WP_Jssor_Slider_Condition::get_push_server_scripts();
foreach($push_server_scripts as $script) {
    $jssor_push->write('<script type="text/javascript" src="'. $script . '"></script>');
}

//$push_server_script_paths = WP_Jssor_Slider_Condition::get_push_server_script_paths();
//foreach($push_server_script_paths as $script_path) {
//    $jssor_push->write('<script type="text/javascript">');
//    $file = file_get_contents(WP_JSSOR_SLIDER_PATH . $script_path);
//    $jssor_push->write($file);
//    $jssor_push->write('</script>');
//}

$jssor_push->write('</head>');
$jssor_push->write('<body>');

$jssor_push->write('<script>wp_jssor_push_server_init();</script>');

$jssor_push->begin();

//start to push progress
$processor = new WP_Jssor_Push_Processor(array('jssor_push' => $jssor_push));

$error = '';
$array = json_decode(stripslashes(sanitize_text_field($_GET['import'])), true);

if (
    empty($array)
    ||
    empty($array['filename'])
    ||
    empty($array['src'])
) {
    $error = __("The request is invalid.", 'jssor-slider');

}

// validate
if (empty($error)) {
    $array['filename'] = sanitize_file_name($array['filename']);

    if (!current_user_can('manage_options')) {
        $error = __("Permission Denied!", 'jssor-slider');

    } elseif (empty($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'wjssl-import')) {
        $error = __("The request is invalid.", 'jssor-slider');

    } elseif (empty($array['filename'])) {
        $error = __("Please input slider name", 'jssor-slider');
    }
}

if ($error) {
    $jssor_push->push('fail', array(2, $error));
} else {
    $type = 1;  //0: create, 1: import
    $lazy_download_resources = false;
    // not sleep when create slider.
    if (empty($array['type'])) {
        $processor->set_sleep(false);
        $type = 0;
        $lazy_download_resources = true;
    }

    $file_name = $array['filename'];

    //$file_name should be specified
    $file_name_error = null;

    if(empty($file_name)) {
        $file_name_error = 'Plase input a slider file name.';
    }
    else {
        //$file_name should be valid
        $file_name_error = Jssor_Slider_Bll::check_slider_file_name_error($file_name);
    }

    if(!is_null($file_name_error)) {
        $jssor_push->push('fail', array(2, $file_name_error));
    }
    else {
        //$file_name should be safe
        $file_name = Jssor_Slider_Bll::to_safe_slider_file_name($file_name);

        $slider_data = Jssor_Slider_Dal::get_slider_data_by_file_name($file_name, $error_message);
        $is_to_overwrite = !is_null($slider_data);

        if(!is_null($error_message)) {
            $jssor_push->push('fail', array(2, $error_message));
        }
        else if(empty($array['overwrite']) && $is_to_overwrite) {
            $jssor_push->push('fail', array(3, $file_name . ' exists already.'));
        }
        else {
            $creationType = ($type == 1) ? "importing" : "creating";
            $processor->arrive_at(5, 'Start ' . $creationType . ' ...', $file_name);

            require_once WP_JSSOR_SLIDER_PATH . 'includes/bll/import/class-jssor-slider-importer.php';
            $context = array(
                'remote_slider' => $array['src'],
                'processor' => $processor,
                'slider_name' => $file_name
            );
            $importer = new WP_Jssor_Slider_Importer($context);
            $slider_json_model = $importer->import($lazy_download_resources);

            if (is_wp_error($slider_json_model)) {
                $jssor_push->push('fail', array(2, $slider_json_model->get_error_message()));
            }
            else {
                $processor->arrive_at(95, _x('Saving slider ...', "noun", 'jssor-slider'), $file_name);

                $error_message = null;

                if($is_to_overwrite) {
                    $slider_data = Jssor_Slider_Bll::save_existing_slider($slider_json_model, $slider_data, $error_message);
                }
                else {
                    $slider_data = Jssor_Slider_Bll::create_new_slider($slider_json_model, $file_name, $error_message);
                }

                if(is_null($slider_data)) {
                    if(empty($error_message)) {
                        $error_message = 'Import slider failure.';
                    }
                    $jssor_push->push('fail', array(2, $error_message));
                }
                else {
                    $file_name = $slider_data['file_name'];
                    $slider_id = $slider_data['id'];
                    $slider_edit_url = '#';
                    $slider_preview_url = WP_Jssor_Slider_Globals::get_jssor_preview_slider_url($slider_id, $file_name);

                    $sliderInfo = array(
                            'slider_id' => $slider_id,
                            'slider_name' => $file_name,
                            'edit_url' => $slider_edit_url,
                            'preview_url' => $slider_preview_url,
                            'grid_thumb_url' => Jssor_Slider_Bll::get_slider_grid_thumb_url($slider_data),
                            'shortcode' => Jssor_Slider_Bll::get_shortcode_with_alias($file_name),
                            'message' => sprintf(__('The slider[%s] is imported successfully!', 'jssor-slider'), $file_name),
                        );

                    $jssor_push->push('success', array($sliderInfo));
                }
            }
        }
    }
}

//stop pushing progress
$jssor_push->end();

$jssor_push->write('</body>');
$jssor_push->write('</html>');

$jssor_push->close();
?>
