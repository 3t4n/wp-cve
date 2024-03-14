<?php

/**
 * @since 3.1.5
 * @author jssor
 */
class class_wjssl_resource_downloader
{
    /**
     * @var integer
     */
    private $max_simultaneous = 5;

    /**
     * @var integer
     */
    private $timeout = 50;

    /**
     * @var array
     */
    private $urls_to_download;


    /**
     * @var iJssor_Slider_Progress_Reporter
     */
    private $progress_reporter;

    /**
     * @var integer
     */
    private $progress_current;

    private $url_reported = array();

    /**
     * @var array
     */
    private $urls_downloading;

    /**
     * @param array $urls_to_download
     */
    public function __construct($urls_to_download, iJssor_Slider_Progress_Reporter $progress_reporter = null) {
        $this->urls_to_download = $urls_to_download;
        $this->progress_reporter = $progress_reporter;
    }

    private function report_progress(Wjssl_Local_Res_Info $local_res_info, $has_error = false) {
        if(!isset($this->url_reported[$local_res_info->upload_key])) {
            $this->url_reported[$local_res_info->upload_key] = true;

            if(!$has_error) {
                WP_Jssor_Slider_Utils::ensure_metadata($local_res_info, $attach_id);
            }

            $this->progress_current++;

            if(!is_null($this->progress_reporter)) {
                $this->progress_reporter->report_progress(basename($local_res_info->local_url), $this->progress_current, count($this->urls_to_download));
            }
        }
    }

    private function success(Wjssl_Local_Res_Info $local_res_info) {
        $this->report_progress($local_res_info);
    }

    private function fail(Wjssl_Local_Res_Info $local_res_info) {
        $this->report_progress($local_res_info, true);
    }

    private function resolve_downloads() {
        $urls_downloading = array_merge(array(), $this->urls_downloading);

        foreach($urls_downloading as $url_downloading) {
            $jssor_res_info = $url_downloading['jssor_res'];
            $local_res_info = $url_downloading['local_res'];
            $remote_url = $jssor_res_info->remote_url;
            $result = $url_downloading['result'];

            if(!is_wp_error($result) || wp_remote_retrieve_response_code($result) === 200) {
                //save download
                $local_path = $local_res_info->local_path;
                $dir = dirname($local_path);

                if (@wp_mkdir_p($dir)) {
                    $rawdata = $result['body'];
                    $fp = fopen($local_path,'w');
                    fwrite($fp, $rawdata);
                    fclose($fp);

                    $this->success($local_res_info);
                }
                else {
                    $this->fail($local_res_info);
                }
            }

            //resolved, clear download task
            unset($this->urls_downloading[$remote_url]);
        }
    }

    public function download() {
        if(WP_Jssor_Slider_Utils::is_curl_enabled()) {
            //multi cur download
            $this->download_multi_cur();
        }
        else {
            //wp_remote_get download
            $download_index = 0;

            $this->max_simultaneous = 1;
            $this->urls_downloading = array();

            while($download_index < count($this->urls_to_download) || !empty($this->urls_downloading)) {
                //add download task
                for(;$download_index < count($this->urls_to_download) && count($this->urls_downloading) < $this->max_simultaneous; $download_index++) {

                    $url_to_download = $this->urls_to_download[$download_index];
                    $jssor_res_info = $url_to_download['jssor_res'];
                    $local_res_info = $url_to_download['local_res'];

                    if($local_res_info->exists() || $jssor_res_info->ensure()) {
                        $this->success($local_res_info);
                    }
                    else {
                        $remote_url = $jssor_res_info->remote_url;

                        $args = array(
                            'timeout'     => 50,
                            'sslverify'   => false
                        );
                        $accessible_url = WP_Jssor_Slider_Utils::to_accessible_jssor_url($remote_url);
                        $result = wp_remote_get($accessible_url, $args);
                        $url_to_download['result'] = $result;

                        $this->urls_downloading[$remote_url] = $url_to_download;
                    }
                }

                if(!empty($this->urls_downloading)) {
                    sleep(1);

                    //resolve downloads
                    $this->resolve_downloads();
                }
            }
        }
    }

    private function download_multi_cur() {
        $succeeded = true;
        $urls_to_download = $this->urls_to_download;

        $mh = curl_multi_init();
        $conn = array();
        $active = null;

        $medias_count = count($urls_to_download);
        $max_simultaneous = min($this->max_simultaneous, $medias_count);

        $common_options = array(
            CURLOPT_HEADER         => 0,
            CURLOPT_CONNECTTIMEOUT => 60,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_FOLLOWLOCATION => 1
        );

        for ($i = 0; $i < $medias_count; $i++) {
            $url_to_download = $urls_to_download[$i];
            $jssor_res_info = $url_to_download['jssor_res'];
            $local_res_info = $url_to_download['local_res'];

            if($local_res_info->exists() || $jssor_res_info->ensure()) {
                $this->success($local_res_info);
            }
            else {
                if(@wp_mkdir_p(dirname($local_res_info->local_path))) {
                    $remote_url = esc_url_raw($jssor_res_info->remote_url);
                    $accessible_url = WP_Jssor_Slider_Utils::to_accessible_jssor_url($remote_url);

                    $ch = curl_init($accessible_url);
                    curl_setopt_array($ch, $common_options);
                    curl_multi_add_handle($mh, $ch);
                    $key = (string) $ch;
                    $conn[$key] = $i;

                    if(count($conn) >= $max_simultaneous) {
                        break;
                    }
                }
                else {
                    $this->fail($local_res_info);
                }
            }
        }

        if (empty($conn)) {
            curl_multi_close($mh);
            return true;
        }
        // do {
        //     $mrc = curl_multi_exec($mh, $active);
        //     curl_multi_select($mh);
        // } while ($active > 0);

        do {
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);

            if ($mrc != CURLM_OK) {
                break;
            }

            // a request was just completed -- find out which one
            while($done = curl_multi_info_read($mh)) {
                // get the info and content returned on the request
                $info = curl_getinfo($done['handle']);
                $data = curl_multi_getcontent($done['handle']);
                // send the return values to the callback function.

                $key = (string) $done['handle'];

                if (isset($conn[$key]) && ($done['result'] == CURLE_OK)) {
                    $key_value = $conn[$key];
                    $url_to_download = $urls_to_download[$key_value];
                    $local_res_info = $url_to_download['local_res'];
                    $local_path = $local_res_info->local_path;

                    $fp = null;
                    $has_error = false;

                    try {
                        $fp = @fopen($local_path, "w");
                        if($fp === false || @fwrite($fp, $data) === false) {
                            $has_error = true;
                            $succeeded = false;
                        }
                    }
                    catch(Exception $e)
                    {
                        $has_error = true;
                        $succeeded = false;
                    }

                    if(!is_null($fp))
                    {
                        @fclose($fp);
                    }

                    if(!$has_error && $local_res_info->exists()) {
                        $this->success($local_res_info);
                    }
                    else {
                        @unlink($local_res_info->local_path);
                        $this->fail($local_res_info);
                    }
                }

                for ($i++; $i < $medias_count;) {
                    $url_to_download = $urls_to_download[$i];
                    $jssor_res_info = $url_to_download['jssor_res'];
                    $local_res_info = $url_to_download['local_res'];
                    if($local_res_info->exists() || $jssor_res_info->ensure()) {
                        $this->success($local_res_info);
                    }
                    else {
                        if(wp_mkdir_p(dirname($local_res_info->local_path))) {
                            $url = esc_url_raw($jssor_res_info->remote_url);

                            $ch = curl_init($url);
                            curl_setopt_array($ch, $common_options);
                            curl_multi_add_handle($mh, $ch);

                            $key = (string) $ch;
                            $conn[$key] = $i;
                            break;
                        }
                    }
                }

                // remove the curl handle that just completed
                curl_multi_remove_handle($mh, $done['handle']);
                curl_close($done['handle']);
            }

            // Block for data in / output; error handling is done by curl_multi_exec
            if ($active) {
                curl_multi_select($mh, $this->timeout);
            }

        } while ($active);

        curl_multi_close($mh);

        return $succeeded;
    }
}
