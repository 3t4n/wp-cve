<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

$slider_name = isset($_GET['filename']) ? strtolower(sanitize_file_name($_GET['filename'])) : '';
$slider_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$debug = isset($_GET['debug']) ? true : false;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preview <?php echo esc_html($slider_name) ?></title>
</head>
<body style="margin:0;padding:0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif;">
    <?php
    if (empty($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'wjssl-preview')) {
        echo __("The request is invalid.", 'jssor-slider');
    }
    else if(empty($slider_name) && empty($slider_id)) {
        echo __("Neither slider name nor slider id is specified.", 'jssor-slider');
    }
    elseif (current_user_can('manage_options')) {

        $slider_json_text = false;

        if (!empty($_POST['data'])) {
            // normal post data
            $slider_json_text = empty($_POST['data']) ? false : $_POST['data'];
            $slider_json_text = stripslashes($slider_json_text);

        } elseif (!empty($_POST['docdata'])) {
            // decompress LZW string posted
            $slider_json_text = empty($_POST['docdata']) ? false : $_POST['docdata'];
            $slider_json_text = stripslashes($slider_json_text);
            Jssor_Slider_Dispatcher::load_once('includes/utils/class-wjssl-lzw.php');
            $compressor = new WjsslLZW();
            $slider_json_text = $compressor->decompress($slider_json_text, false);
        }

        $html = '';
        $error_message = null;

        if(empty($slider_json_text)) {
            $id_or_alias = $slider_id;
            if(empty($id_or_alias)) {
                $id_or_alias = $slider_name;
            }

            //get slider html code from cache
            $html = WP_Jssor_Slider_Output::ensure_slider_html_code($id_or_alias, false, $error_message);
        }
        else {
            $slider_json_model = json_decode($slider_json_text, true);
            $html = WP_Jssor_Slider_Output::generate_html_from_slider_json_model($slider_json_model, strval($slider_id), $slider_id, $slider_name, $error_message);
        }

        if(empty($html)) {
            if(empty($error_message)) {
                $error_message = sprintf(__("Failed to generate html code for slider %s.", 'jssor-slider'), empty($slider_name) ? $slider_id : $slider_name);
            }

            echo $error_message;
        }
        else {
            //if(preg_match('/^[\t\n\r]*<!--(#region )?jssor-slider(-begin)? (.+?)-->/', $html, $matches)) {
            //    $array_specs = explode(',', $matches[1]);

            //    //enqueue script
            //    $upload = wp_upload_dir();
            //    $script_url = $upload['baseurl'] . '/jssor-slider/jssor.com/script/jssor.slider-' . $array_specs[0] . '.min.js';
            //    echo "<script src='$script_url'></script>";
            //}

            if ($debug) {
                echo htmlspecialchars($html);
            } else {
                echo $html;
            }
        }
    } else {
        echo __("Permission Denied!", 'jssor-slider');
    }
    ?>
</body>
</html>
