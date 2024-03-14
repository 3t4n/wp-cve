<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

interface iJssor_Slider_Progress_Reporter {
    public function report_progress($progress_title, $progress_current, $progress_total);
}

/**
 * Class WP_Jssor_Slider_Importer
 * @link   https://www.jssor.com
 * @author Neil.zhou
 */
class WP_Jssor_Slider_Importer implements iJssor_Slider_Progress_Reporter
{
    public function __construct($context)
    {
        $this->remote_url = esc_url_raw($context['remote_slider']);
        $this->processor = $context['processor'];
        $this->slider_name = $context['slider_name'];
    }

    public function report_progress($progress_title, $progress_current, $progress_total) {
        $progress_percent = $progress_current / $progress_total;
        $progress = 15 + round($progress_percent * 80);
        $this->processor()->arrive_at($progress, __('Fetching resources ...', 'jssor-slider'), $progress_title);
    }

    public function import($lately_download = false) {
        $slider_json_model = $this->copy_from_template();
        if (empty($slider_json_model)) {
            $slider_json_model = $this->fetch_remote_slider();
        }

        if (is_wp_error($slider_json_model)) {
            return $slider_json_model;
        }

        $this->processor()->arrive_at(15, __('Fetching resources ...', 'jssor-slider'), $this->slider_name);

        if(!empty($slider_json_model)) {
            //foriegn url to @Import tag url
            $jssor_slider_converter = new Jssor_Slider_Converter_Foriegn_To_Standard($slider_json_model);
            $jssor_slider_converter->convert_resource_urls();
            $converted_urls = $jssor_slider_converter->get_converted_resource_urls();

            //fetch urls to download
            $url_handled = array();
            $urls_to_download = array();
            foreach($converted_urls as $import_url) {
                if(WP_Jssor_Slider_Utils::is_import_tag_url($import_url)) {
                    $remote_url = substr($import_url, 8);

                    $jssor_res_info = WP_Jssor_Slider_Utils::to_jssor_res_info($remote_url);
                    $local_res_info = WP_Jssor_Slider_Utils::to_local_res_info($jssor_res_info->local_url);
                    if(!isset($url_handled[$local_res_info->upload_key]) && $jssor_res_info->is_valid) {
                        $urls_to_download[] = array(
                                'jssor_res' => $jssor_res_info,
                                'local_res' =>$local_res_info
                            );
                    }
                }
            }

            //download resources
            require_once WP_JSSOR_SLIDER_PATH . 'includes/utils/class-wjssl-resource-downloader.php';
            $resource_downloader = new class_wjssl_resource_downloader($urls_to_download, $this);
            $resource_downloader->download();

            //@Import tag url to local url
            $jssor_slider_converter = new Jssor_Slider_Converter_Standard_To_Local($slider_json_model);
            $jssor_slider_converter->convert_resource_urls();
        }

        return $slider_json_model;
    }

    private function fetch_remote_slider() {
        $this->processor()->arrive_at(10, __('Fetching slider ...', 'jssor-slider'), $this->remote_url());

        global $wp_version;

        $import_api_url = WP_Jssor_Slider_Globals::URL_JSSOR_SECURE() . WP_Jssor_Slider_Globals::URL_JSSOR_IMPORT;
        $data = array(
            'jssorext' => WP_JSSOR_SLIDER_EXTENSION_NAME,
            'hosturl' => esc_url_raw(WP_Jssor_Slider_Globals::get_jssor_wordpress_site_url()),
            'instid' => get_option('wp_jssor_slider_instance_id', ''),
            'acckey' => get_option('wjssl_acckey', ''),
            'instver' => $wp_version,
            'extver' => WP_JSSOR_SLIDER_VERSION,
            'fileurl' => esc_url_raw($this->remote_url)
        );

        $params = array('data' => json_encode($data));
        $headers = array();
        if (function_exists('gzencode')) {
            $params = gzencode(http_build_query($params));
            $headers = array('Content-Encoding' => 'gzip');
        } else {
            Jssor_Slider_Dispatcher::load_once('includes/utils/class-wjssl-lzw.php');
            $compressor = new WjsslLZW();
            $params = $compressor->compress($params['data']);
            $headers = array('Content-Encoding' => 'jslzwutf16binary');
        }

        $remote_url = esc_url_raw($import_api_url);
        $accessible_url = WP_Jssor_Slider_Utils::to_accessible_jssor_url($remote_url);
        $resp = wp_remote_post($accessible_url, array(
            'body' => $params,
            'headers' => $headers,
            'timeout' => 60,
        ));

        if (is_wp_error($resp)) {
            return $resp;
        }

        $response_json_model = json_decode($resp['body']);

        if (empty($response_json_model)) {
            return new WP_Error('JSON-DECODE-ERROR', $resp['body']);
        }

        if(!empty($response_json_model->error))
        {
            return new WP_Error('IMPORT-SLIDER-ERROR', $response_json_model->message);
        }

        $slider_json_model = $response_json_model->document;

        //can receive $slider_json_model directly without json_decode since 3.1.5
        if(is_string($slider_json_model)) {
            $slider_json_model = json_decode($slider_json_model);
        }

        return $slider_json_model;
    }

    private function copy_from_template() {
        $jssor_res_info = WP_Jssor_Slider_Utils::to_jssor_res_info($this->remote_url);

        if($jssor_res_info->installed()) {
            $slider_json_text = @file_get_contents($jssor_res_info->install_path);

            if($slider_json_text !== false) {
                $slider_json_model = json_decode($slider_json_text);

                if(!empty($slider_json_model)) {
                    return $slider_json_model;
                }
            }
        }

        return false;
    }

    private function remote_url()
    {
        return $this->remote_url;
    }

    private function processor()
    {
        return $this->processor;
    }

    private function slider_name()
    {
        return $this->slider_name;
    }

}
