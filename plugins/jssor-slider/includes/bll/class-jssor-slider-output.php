<?php

if( !defined( 'ABSPATH') ) exit();

Jssor_Slider_Dispatcher::load_once('includes/bll/class-jssor-slider-slider.php');

/**
 * @link   https://www.jssor.com
 * @author Neil.zhou
 * @author jssor
 */
class WP_Jssor_Slider_Output {

    #region generate slider html code

    /**
     * @param array $slider_json_model
     * @param string $unique_id
     * @param int $slider_id
     * @param string $slider_name
     * @param string $error_message
     * @return string
     */
    public static function generate_html_from_slider_json_model($slider_json_model, $unique_id, $slider_id, $slider_name, &$error_message) {
        $html_code = '';
        $error_message = null;

        try {
            Jssor_Slider_Dispatcher::load_module_common();
            Jssor_Slider_Dispatcher::load_once('includes/bll/gencode/jssor-slider-build-autoload.php');
            Jssor_Slider_Dispatcher::load_once('includes/bll/gencode/class-wjssl-slider-build-helper.php');
            Jssor_Slider_Dispatcher::load_once('includes/bll/gencode/class-wjssl-slider-build-converter.php');
            Jssor_Slider_Dispatcher::load_once('includes/bll/gencode/class-wjssl-slider-code-document.php');

            $designTimeDocument = new WjsslDesignTimeDocument($slider_json_model);
            $runtimeDocument = WjsslSliderBuildConverter::to_runtime_document($designTimeDocument);
            $sliderCodeDocument = new WjsslSliderCodeDocument($designTimeDocument, $runtimeDocument, $unique_id, $slider_id, $slider_name);

            $sliderCodeDocument->build_slider();

            $htmlDocument = $sliderCodeDocument->get_html_document();

            $html_code =  $htmlDocument->saveHTML();
            if(is_null($html_code)) {
                $html_code = '';
            }
        }
        catch(Exception $e) {
            $error_message = $e->getMessage();
        }

        return $html_code;
    }

    /**
     * get slider html code from cache, regenerate if not found
     * @param string $id_or_alias
     * @param bool $dynamic
     * @param string $error_message
     * @return string
     */
    public static function ensure_slider_html_code($id_or_alias, $dynamic, &$error_message) {
        $id_or_alias = strval($id_or_alias);

        $html = '';
        $error_message = null;

        if(!$dynamic) {
            //get slider html code from cache
            $html = Jssor_Slider_Bll::read_slider_html_code($id_or_alias, $error_message);
        }

        if(empty($html)) {
            $slider_data = Jssor_Slider_Bll::get_slider_data_by_id_or_alias($id_or_alias, $error_message);

            if(is_null($slider_data)) {
                $error_message = sprintf(__('The slider %s is not found.', 'jssor-slider'), $id_or_alias);
            }
            else {
                $slider_json_model = Jssor_Slider_Bll::read_slider_json_model($slider_data, $error_message, true);
                if(!is_null($slider_json_model)) {
                    if($dynamic) {
                        $html = WP_Jssor_Slider_Output::generate_html_from_slider_json_model($slider_json_model, uniqid(), $slider_data['id'], $slider_data['file_name'], $error_message);
                    }
                    else {
                        $html = WP_Jssor_Slider_Output::generate_html_from_slider_json_model($slider_json_model, strval($slider_data['id']), $slider_data['id'], $slider_data['file_name'], $error_message);

                        if(!empty($html)) {
                            Jssor_Slider_Bll::save_slider_html_code($html, $slider_data['id'], $slider_data['file_name'], $error_message);
                        }
                    }
                }
            }
        }

        return $html;
    }

    /**
     * get slider html code, if error occured, returns shortcode with error message
     * @param string $id_or_alias
     * @param bool $dynamic
     * @return string
     */
    public static function get_slider_display_html_code($id_or_alias, $dynamic) {
        $html = '';

        $html = WP_Jssor_Slider_Output::ensure_slider_html_code($id_or_alias, $dynamic, $error_message);

        if(empty($html)) {
            if(is_null($error_message)) {
                $error_message = 'internal error';
            }

            $error_message_to_show = htmlentities($error_message);

            if(strpos($id_or_alias, '.') !== false) {
                $html = "[jssor-slider alias=\"$id_or_alias\" error=\"$error_message_to_show\"]";
            }
            else {
                $html = "[jssor-slider id=\"$id_or_alias\" error=\"$error_message_to_show\"]";
            }
        }

        return $html;
    }

    #endregion
}
