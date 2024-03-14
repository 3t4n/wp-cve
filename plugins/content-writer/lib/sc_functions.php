<?php
if (!function_exists("conwr_get_request")) {
    function conwr_get_request($url, $referrer = false, $timeout = 0, $get_only_body = true) {
        if (!isset($timeout) || $timeout == 0 || $timeout == 10) {
            $timeout = 20;
        }

        $args = array(
            'timeout'     => $timeout,
            'httpversion' => '1.1',
            'sslverify'   => FALSE,
        );

        if (!function_exists("curl_exec")) {
            conwr_api_write_log("Error: curl not enabled on the server; Function: conwr_get_request; URL: " . $url);
            return "false";
        }

        $output = wp_remote_get($url, $args);

        if(is_wp_error($output)) {
            conwr_api_write_log("Error: " . $output->get_error_message() . "; Function: conwr_get_request; URL: " . $url);
        }

        if ($get_only_body) {
            $output = wp_remote_retrieve_body($output);
        }

        return $output;
    }
}

if (!function_exists("conwr_curl_get_request")) {
    function conwr_curl_get_request($url, $referrer = false, $timeout = 0) {
        if (!isset($timeout) || $timeout == 0 || $timeout == 10) {
            $timeout = 20;
        }

        $options = array(
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HEADER         => false,  // don't return headers
            //CURLOPT_USERAGENT      => "test", // name of client
            CURLOPT_CONNECTTIMEOUT => $timeout,    // time-out on connect
            CURLOPT_TIMEOUT        => $timeout,    // time-out on response
        ); 
    
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
    
        if (!function_exists("curl_exec")) {
            conwr_api_write_log("Error: curl not enabled on the server; Function: conwr_curl_get_request; URL: " . $url);
            return "false";
        }

        $output = curl_exec($ch);

        if($output === false) {
            conwr_api_write_log("Error: " . curl_error($ch) . "; Function: conwr_curl_get_request; URL: " . $url);
            return "false";
        }
    
        curl_close($ch);

        return $output;
    }
}

if (!function_exists("conwr_post_request")) {
    function conwr_post_request($url, $post_fields) {
        $args = array(
            'body' => $post_fields,
            'httpversion' => '1.1',
            'blocking' => true,
            'sslverify'   => FALSE,
        );

        $output = wp_remote_post($url, $args);

        return $output;
    }
}

if (!function_exists("conwr_check_api_key")) {
    function conwr_check_api_key($function_name = "") {
        $sc_email = get_option("conwr_email");
        $sc_api_key = get_option("conwr_api_key");
        $sc_plugin_verified = get_option("conwr_verified");

        if (isset($sc_email) && !empty($sc_email) && isset($sc_api_key) && !empty($sc_api_key)) {
            $url = "https://app.steadycontent.com/app/account/pluginverification.aspx?action=check_api_key&email=" . $sc_email . "&key=" . $sc_api_key;

            $response = conwr_get_request($url, false, 0);

            if (isset($response) && $response == "ok") {
                return true;
            }
            else {
                conwr_api_write_log("Could not verify email or API key, response is not valid. Email: $sc_email; API Key: $sc_api_key; Function: $function_name.");
            }
        }
        elseif (isset($sc_plugin_verified) && !empty($sc_plugin_verified)) {
            if ($sc_plugin_verified == true) {
                return true;
            } else {
                return false;
            }
        }
        else {
            conwr_api_write_log("Could not verify email or API key, some params are missing. Email: $sc_email; API Key: $sc_api_key. Function: $function_name.");
        }

        return false;
    }
}

if (!function_exists("conwr_fire_off_webhook")) {
    function conwr_fire_off_webhook($action, $post_id, $description, $content) {
        //get Steady Content Post ID
        $sc_id = get_post_meta($post_id, "steady_content_id", true);

        $post = get_post($post_id);
        $post_author = isset($post->post_author) ? $post->post_author : "";

        $category_ids = "";
        $cat_args = array('hide_empty' => 0);
        $wp_categories = get_the_category($post_id);

        if (isset($wp_categories)) {
            foreach ($wp_categories as $category) {
                $category_ids .= $category->term_id . ",";
            }
        }

        if ($category_ids != "") {
            $category_ids = substr($category_ids, 0, strlen($category_ids) - 1);
        }

        $webhook_URL = 'https://api.steadycontent.com/notify.aspx';
        $post_fields = array(
            'Action'      => $action,
            'PostID'      => $post_id,
            'SCID'        => $sc_id,
            'PostURL'     => get_permalink($post_id),
            'AuthorID'    => $post_author,
            'CategoryID'  => $category_ids,
            'Description' => $description,
            'Content'     => htmlentities($content),
            );
        $request = conwr_post_request($webhook_URL, $post_fields);
    }
}

if (!function_exists("conwr_post_transition_status")) {
    function conwr_post_transition_status($new_status, $old_status, $post) {
        if ($old_status != $new_status) {
            if ($new_status == 'future') {
                conwr_fire_off_webhook("Post Scheduled", $post->ID, "Post {$post->ID} has been scheduled for {$post->post_date_gmt} GMT", $post->post_content);
            }
            else if ($old_status == 'future' && $new_status == 'publish') {
                conwr_fire_off_webhook("Scheduled Post Published", $post->ID, "Scheduled post {$post->ID} has been published.", $post->post_content);
            }
            else if ($new_status == 'publish') {
                conwr_fire_off_webhook("Post Published", $post->ID, "Post {$post->ID} has been published.", $post->post_content);
            }
            else if ($new_status == 'trash') {
                conwr_fire_off_webhook("Post Deleted", $post->ID, "Post {$post->ID} has been deleted.", $post->post_content);
            }
        }
    }
}

if (!function_exists("conwr_post_updated_action")) {
    function conwr_post_updated_action($post_id, $post_after, $post_before) {
        if($post_after->post_status == $post_before->post_status) {
            if($post_after->post_content !== $post_before->post_content) {
                conwr_fire_off_webhook("Post Content Updated", $post_id, "Content of the post {$post_id} has been updated.", $post_after->post_content);
            }
        }
    }
}

if (!function_exists("conwr_post_deleted_action")) {
    function conwr_post_deleted_action($post_id) {
        if (!wp_is_post_revision($post_id)) {
            $post = get_post($post_id);
            conwr_fire_off_webhook("Post Permanently Deleted", $post_id, "Post {$post_id} has been permanently deleted.", $post->post_content);
        }
    }
}

if (!function_exists("conwr_get_plugin_latest_version")) {
    function conwr_get_plugin_latest_version() {
        $url = "https://api.wordpress.org/plugins/info/1.0/content-writer.json";

        $response = json_decode(conwr_get_request($url));

        if (isset($response)) {
            return $response->version;
        }

        return 0;
    }
}

if (!function_exists('conwr_get_plugin_current_version')) {
    // Returns the current version of the active plugins.
    function conwr_get_plugin_current_version($plugin_url) {
        if (file_exists($plugin_url)) {
            if (!function_exists('get_plugin_data')){
                require_once(ABSPATH . 'wp-admin/includes/plugin.php');
            }

            $plugin_data = get_plugin_data($plugin_url);

            if (is_array($plugin_data))
                return $plugin_data['Version'];
        }

        return 0;
    }
}

if (!function_exists('conwr_copy_remote_file')) {
    // Copies files from source to destination folder.
    function conwr_copy_remote_file($url, $dest_file) {
        try{
                $args = array(
                'timeout'     => 60,
                'httpversion' => '1.1',
                'stream'      => true,
                'filename'    => $dest_file,
                'sslverify'   => FALSE,
            );
            
            $output = wp_remote_get($url, $args);

            if(is_wp_error($output)) {
                conwr_api_write_log("Error: " . $output->get_error_message() . "; Function: conwr_copy_remote_file; URL: " . $url);
            }

            if ($output === false) {
                return false;
            }
            else {
                return true;
            }
        }
        catch (Exception $ex) 
        { 
            return false;
        }
        
    }
}

if (!function_exists('conwr_get_guid')) {
    function conwr_get_guid() {
        $uuid = "";
        if (function_exists('com_create_guid')){
            $uuid = com_create_guid();
        }
        else {
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// ""
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
                .chr(125);// ""
        }

        $uuid = str_replace("{", "", $uuid);
        $uuid = str_replace("}", "", $uuid);
        return $uuid;
    }
}

if (!function_exists('conwr_api_write_log')) {
    function conwr_api_write_log($log_text, $add_line = false) {
        $file_name = dirname(dirname( __FILE__ )) . '/log_cw_plugin.txt';

        if (!file_exists($file_name)) {
            @file_put_contents($file_name, $new_text, FILE_APPEND);
        }

        $plugin = 'Content Writer V' . CONWR_VERSION;
        $date = date("m/d/Y H:i:s");

        $new_text = sprintf('[%1$s] [%2$s] [%3$s] [%4$s]%5$s', $date, $plugin, site_url(), $log_text, "\r\n");

        if($add_line)
            $new_text = "------------------\r\n";

        @file_put_contents($file_name, $new_text, FILE_APPEND);
    }
}

if (!function_exists("conwr_get_post_keywords_meta")) {
    function conwr_get_post_keywords_meta($post_id) {
        $post_kw = "";   
        $post_kw = get_post_meta($post_id, "_yoast_wpseo_focuskw", true);

        if (!isset($post_kw) || empty($post_kw)) {
            $post_kw = get_post_meta($post_id, "_conwr_sc_post_keywords", true);
        }

        return $post_kw;
    }
}

if (!function_exists("conwr_get_first_paragraph_text")) {
    function conwr_get_first_paragraph_text($content) {   
        if (substr($content, 0, 3) === "<p>") {
            //if first paragraph starts with <p> tag        
            $start = strpos($content, '<p>');
            $end = strpos($content, '</p>', $start);
            return substr($content, $start, $end - $start + 4);
        }
        else {
            //if first paragraph doesn't contain <p> tag
            return strtok($content, "\n");
        }
    }
}

if (!function_exists("conwr_is_first_paragraph_contains_kw")) {
    function conwr_is_first_paragraph_contains_kw($content, $keywords_arr) {
        $first_p = conwr_get_first_paragraph_text($content);

        foreach ($keywords_arr as $kw) {
            if (strpos(strtolower($first_p), strtolower($kw)) !== false) {
                return true;
            }
        }

        return false;
    }
}

if (!function_exists("conwr_get_number_of_occurrences")) {
    function conwr_get_number_of_occurrences($content, $word) {        
        $content = strip_tags(strtolower($content));
        $word = strtolower($word);

        return preg_match_all("/\b{$word}\b/", strtolower(strip_tags($content)));
    }
}

if (!function_exists("conwr_get_words_count")) {
    function conwr_get_words_count($content) {
        $words_to_count = strip_tags($content); 
        $pattern = "/[^(\w|\d|\'|\"|\.|\!|\?|;|,|\\|\/|\-\-|:|\&|@)]+/"; 
        $words_to_count = preg_replace ($pattern, " ", $words_to_count); 
        $words_to_count = trim($words_to_count); 
        $total_words = count(explode(" ",$words_to_count));

        return $total_words;

        // $word_count = str_word_count(strip_tags($content));
        // return $word_count;
    }
}

if (!function_exists("conwr_get_writer_details")) {
    function conwr_get_writer_details($sc_post_id) {
        try {
            require_once(dirname( __FILE__ ) . '/nusoap/nusoap.php');

            $client = new nusoap_client('https://api.steadycontent.com/Service.asmx?WSDL', 'WSDL_CACHE_NONE');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;

            $params = array('PostID' => $sc_post_id);
            
            $json_response = $client->call('GetWriterDetails', $params)->GetWriterDetailsResult;

            return $json_response;
        }
        catch (Exception $ex) 
        { 
            return $ex->getMessage();
        }

        return "";
    }
}

if (!function_exists("conwr_get_writer_code")) {
    function conwr_get_writer_code($sc_post_id) {
        try {
            require_once(dirname( __FILE__ ) . '/nusoap/nusoap.php');

            $client = new nusoap_client('https://api.steadycontent.com/Service.asmx?WSDL', 'WSDL_CACHE_NONE');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;

            $params = array('PostID' => $sc_post_id);

            $writer_code = $client->call('GetWriterCodeByPostID', $params)->GetWriterCodeByPostIDResult;
            
            return $writer_code;
        }
        catch (Exception $ex) 
        { 
            return $ex->getMessage();
        }

        return "";
    }
}

if (!function_exists("conwr_get_content_id")) {
    function conwr_get_content_id($sc_post_id) {
        try {

            require_once(dirname( __FILE__ ) . '/nusoap/nusoap.php');

            $client = new nusoap_client('https://api.steadycontent.com/Service.asmx?WSDL', 'WSDL_CACHE_NONE');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;

            $params = array('PostID' => $sc_post_id);

            $content_id = $client->call('GetContentIDByPostID', $params)->GetContentIDByPostIDResult;

            return $content_id;
        }
        catch (Exception $ex) 
        { 
            return $ex->getMessage();
        }

        return "";
    }
}

if (!function_exists("conwr_show_writer_info")) {
    function conwr_show_writer_info($sc_post_id) {
        try {

            require_once(dirname( __FILE__ ) . '/nusoap/nusoap.php');

            $client = new nusoap_client('https://api.steadycontent.com/Service.asmx?WSDL', 'WSDL_CACHE_NONE');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;

            $params = array('PostID' => $sc_post_id);

            $json_response = $client->call('ShowWriterInfo', $params)->ShowWriterInfoResult;

            $response = json_decode($json_response, true);
                
            if ($response != null && $response["Description"] != null && (string)$response["Description"] == "true") {
                return true;
            }
        }
        catch (Exception $ex) 
        { 
            return $ex->getMessage();
        }

        return false;
    }
}

if (!function_exists("conwr_is_writer_is_favorited")) {
    function conwr_is_writer_is_favorited($sc_post_id, $writer_id) {
        try {

            require_once(dirname( __FILE__ ) . '/nusoap/nusoap.php');

            $client = new nusoap_client('https://api.steadycontent.com/Service.asmx?WSDL', 'WSDL_CACHE_NONE');
            $client->soap_defencoding = 'UTF-8';
            $client->decode_utf8 = FALSE;

            $params = array('PostID' => $sc_post_id, 'WriterID' => $writer_id);

            $json_response = $client->call('IsWriterFavoritedOnCampaign', $params)->IsWriterFavoritedOnCampaignResult;

            $response = json_decode($json_response, true);
                
            if ($response != null && $response["Description"] != null && (string)$response["Description"] == "true") {
                return true;
            }
        }
        catch (Exception $ex) 
        { 
            return $ex->getMessage();
        }

        return false;
    }
}

if (!function_exists("decode_value_for_plugin")) {
    function decode_value_for_plugin($value, $function_name = '') {
        try {
            if(!isset($value))
                return '';

            if(is_null($value))
                return '';

            //first we replace some specific characters we did because of difference in coding/decoding in c# and php
            $raw = str_replace("MAZDAKMAZDAKMAZOO","+",$value);

            $decoded_value = base64_decode($raw);

            return $decoded_value;
        }
        catch (Exception $ex) 
        { 
            conwr_api_write_log($ex->getMessage());
            return $ex->getMessage();
        }

        return false;
    }
}

?>