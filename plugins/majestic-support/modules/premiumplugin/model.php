<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_premiumpluginModel {

    private static $server_url = 'https://majesticsupport.com/setup/index.php';

    function verfifyAddonActivation($addon_name){
        $option_name = 'transaction_key_for_majestic-support-'.esc_attr($addon_name);
        $transaction_key = MJTC_includer::MJTC_getModel('majesticsupport')->getAddonTransationKey($option_name);
        try {
            if (! $transaction_key ) {
                throw new Exception( 'License key not found' );
            }
            if ( empty( $transaction_key ) ) {
                throw new Exception( 'License key not found' );
            }
            $activate_results = $this->activate( array(
                'token'    => $transaction_key,
                'plugin_slug'    => $addon_name
            ) );
            if ( false === $activate_results ) {
                throw new Exception( 'Connection failed to the server' );
            } elseif ( isset( $activate_results['error_code'] ) ) {
                throw new Exception( $activate_results['error'] );
            } elseif(isset($activate_results['verfication_status']) && $activate_results['verfication_status'] == 1 ){
                return true;
            }
            throw new Exception( 'License could not activate. Please contact support.' );
        } catch ( Exception $e ) {
            $data = '<div class="notice notice-error is-dismissible">
                    <p>'.wp_kses_post($e->MJTC_getMessage()).'.</p>
                </div>';
            echo wp_kses($data, MJTC_ALLOWED_TAGS);
            return false;
        }
    }

    function logAddonDeactivation($addon_name){
        $option_name = 'transaction_key_for_majestic-support-'.esc_attr($addon_name);
        $transaction_key = MJTC_includer::MJTC_getModel('majesticsupport')->getAddonTransationKey($option_name);

        $activate_results = $this->deactivate( array(
            'token'    => $transaction_key,
            'plugin_slug'    => $addon_name
        ) );
    }

    function logAddonDeletion($addon_name){
        $option_name = 'transaction_key_for_majestic-support-'.esc_attr($addon_name);
        $transaction_key = MJTC_includer::MJTC_getModel('majesticsupport')->getAddonTransationKey($option_name);
        $activate_results = $this->delete( array(
            'token'    => $transaction_key,
            'plugin_slug'    => $addon_name
        ) );
    }

    public static function activate( $args ) {
        $site_url = MJTC_includer::MJTC_getModel('majesticsupport')->getSiteUrl();
        $defaults = array(
            'request'  => 'activate',
            'domain' => $site_url,
            'activation_call' => 1
        );

        $args    = wp_parse_args( $defaults, $args );
        $url = self::$server_url . '?' . http_build_query( $args, '', '&' );
        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $args, '', '&' ) );
        if ( is_wp_error( $request ) ) {
            return json_encode( array( 'error_code' => $request->get_error_code(), 'error' => $request->get_error_message() ) );
        }

        if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
            return json_encode( array( 'error_code' => wp_remote_retrieve_response_code( $request ), 'error' => 'Error code: ' . wp_remote_retrieve_response_code( $request ) ) );
        }
        $response =  wp_remote_retrieve_body( $request );
        $response = json_decode($response,true);
        return $response;
    }

    /**
     * Attempt t deactivate a license
     */
    public static function deactivate( $dargs ) {
        $site_url = MJTC_includer::MJTC_getModel('majesticsupport')->getSiteUrl();
        $defaults = array(
            'request'  => 'deactivate',
            'domain' => $site_url
        );

        $args    = wp_parse_args( $defaults, $dargs );
        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $args, '', '&' ) );
        if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
            return false;
        } else {
            return wp_remote_retrieve_body( $request );
        }
    }
    /**
     * Attempt t deactivate a license
     */
    public static function delete( $args ) {
        $site_url = MJTC_includer::MJTC_getModel('majesticsupport')->getSiteUrl();
        $defaults = array(
            'request'  => 'delete',
            'domain' => $site_url,
        );

        $args    = wp_parse_args( $defaults, $args );
        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $args, '', '&' ) );
        if ( is_wp_error( $request ) || wp_remote_retrieve_response_code( $request ) != 200 ) {
            return false;
        } else {
            return;
        }
    }

    function verifyAddonSqlFile($addon_name,$addon_version){
        $option_name = 'transaction_key_for_majestic-support-'.esc_attr($addon_name);
        $transaction_key = MJTC_includer::MJTC_getModel('majesticsupport')->getAddonTransationKey($option_name);
        $network_site_url = MJTC_includer::MJTC_getModel('majesticsupport')->getNetworkSiteUrl();
        $site_url = MJTC_includer::MJTC_getModel('majesticsupport')->getSiteUrl();
        $defaults = array(
            'request'  => 'getactivatesql',
            'domain' => $network_site_url,
            'subsite' => $site_url,
            'activation_call' => 1,
            'plugin_slug' => $addon_name,
            'addonversion' => $addon_version,
            'token' => $transaction_key
        );
        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $defaults, '', '&' ) );
        if ( is_wp_error( $request ) ) {
            return json_encode( array( 'error_code' => $request->get_error_code(), 'error' => $request->get_error_message() ) );
        }

        if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
            return json_encode( array( 'error_code' => wp_remote_retrieve_response_code( $request ), 'error' => 'Error code: ' . wp_remote_retrieve_response_code( $request ) ) );
        }

        $response =  wp_remote_retrieve_body( $request );
        return $response;
    }

    function getAddonSqlForUpdation($plugin_slug,$installed_version,$new_version){
        $option_name = 'transaction_key_for_majestic-support-'.esc_attr($plugin_slug);
        $transaction_key = MJTC_includer::MJTC_getModel('majesticsupport')->getAddonTransationKey($option_name);
        $network_site_url = MJTC_includer::MJTC_getModel('majesticsupport')->getNetworkSiteUrl();
        $site_url = MJTC_includer::MJTC_getModel('majesticsupport')->getSiteUrl();
        $defaults = array(
            'request'  => 'getupdatesql',
            'domain' => $network_site_url,
            'subsite' => $site_url,
            'activation_call' => 1,
            'plugin_slug' => $plugin_slug,
            'installedversion' => $installed_version,
            'newversion' => $new_version,
            'token' => $transaction_key
        );

        $request = wp_remote_get( self::$server_url . '?' . http_build_query( $defaults, '', '&' ) );
        if ( is_wp_error( $request ) ) {
            return json_encode( array( 'error_code' => $request->get_error_code(), 'error' => $request->get_error_message() ) );
        }

        if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
            return json_encode( array( 'error_code' => wp_remote_retrieve_response_code( $request ), 'error' => 'Error code: ' . wp_remote_retrieve_response_code( $request ) ) );
        }

        $response =  wp_remote_retrieve_body( $request );
        return $response;
    }

    function getAddonUpdateSqlFromUpdateDir($installedversion,$newversion,$directory){

        if($installedversion != "" && $newversion != ""){
            for ($i = ($installedversion + 1); $i <= $newversion; $i++) {
                $installfile = $directory . '/' . $i . '.sql';
                if (file_exists($installfile)) {
                    $delimiter = ';';
                    $file = fopen($installfile, 'r');
                    if (is_resource($file) === true) {
                        $query = array();

                        while (feof($file) === false) {
                            $query[] = fgets($file);
                            if (MJTC_majesticsupportphplib::MJTC_preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
                                $query = trim(implode('', $query));
                                if($query != ''){
                                    $query = MJTC_majesticsupportphplib::MJTC_str_replace("#__", majesticsupport::$_db->prefix, $query);
                                }
                                if (!empty($query)) {
                                    majesticsupport::$_db->query($query);
                                }
                            }
                            if (is_string($query) === true) {
                                $query = array();
                            }
                        }
                        fclose($file);
                    }
                }
            }
        }
    }

    function getAddonUpdateSqlFromLive($installedversion,$newversion,$plugin_slug){
        if($installedversion != "" && $newversion != "" && $plugin_slug != ""){
            $addonsql = $this->getAddonSqlForUpdation($plugin_slug,$installedversion,$newversion);
            $decodedata = json_decode($addonsql,true);
            $delimiter = ';';
            if(isset($decodedata['verfication_status']) && $decodedata['update_sql'] != ""){
                $lines = MJTC_majesticsupportphplib::MJTC_explode(PHP_EOL, $addonsql);
                if(!empty($lines)){
                    foreach($lines as $line){
                        $query[] = $line;
                        if (MJTC_majesticsupportphplib::MJTC_preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
                            $query = trim(implode('', $query));
                            if($query != ''){
                                $query = MJTC_majesticsupportphplib::MJTC_str_replace("#__", majesticsupport::$_db->prefix, $query);
                            }
                            if (!empty($query)) {
                                majesticsupport::$_db->query($query);
                            }
                        }
                        if (is_string($query) === true) {
                            $query = array();
                        }
                    }
                }
            }
        }
    }

    function MJTC_checkAddoneInfo($name){
        $slug = $name.'/'.$name.'.php';
        if(file_exists(WP_PLUGIN_DIR . '/'.$slug) && is_plugin_active($slug)){
            $status = esc_html(__("Activated",'majestic-support'));
            $action = esc_html(__("Deactivate",'majestic-support'));
            $actionClass = 'ms-admin-adons-status-Deactive';
            $url = "plugins.php?s=".$name."&plugin_status=active";
            $disabled = "disabled";
            $class = "mjtc-btn-activated";
            $availability = "-1";
            $version = "";
        }else if(file_exists(WP_PLUGIN_DIR . '/'.$slug) && !is_plugin_active($slug)){
            $status = esc_html(__("Deactivated",'majestic-support'));
            $action = esc_html(__("Activate",'majestic-support'));
            $actionClass = 'ms-admin-adons-status-Active';
            $url = "plugins.php?s=".$name."&plugin_status=inactive";
            $disabled = "";
            $class = "mjtc-btn-green mjtc-btn-active-now";
            $availability = "1";
            $version = "";
        }else if(!file_exists(WP_PLUGIN_DIR . '/'.$slug)){
            $status = esc_html(__("Not Installed",'majestic-support'));
            $action = esc_html(__("Install Now",'majestic-support'));
            $actionClass = 'ms-admin-adons-status-Install';
            $url = admin_url("admin.php?page=majesticsupport_premiumplugin&mjslay=step1");
            $disabled = "";
            $class = "mjtc-btn-install-now";
            $availability = "0";
            $version = "---";
        }
        return array("status" => $status, "action" => $action, "url" => $url, "disabled" => $disabled, "class" => $class, "availability" => $availability, "actionClass" => $actionClass, "version" => $version);
    }

    function downloadandinstalladdonfromAjax(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'download-and-install-addon') ) {
            die( 'Security check Failed' );
        }

        $key = MJTC_request::MJTC_getVar('dataFor');
        $installedversion = MJTC_request::MJTC_getVar('currentVersion');
        $newversion = MJTC_request::MJTC_getVar('cdnVersion');
        $addon_json_array = array();

        if($key != ''){
            $addon_json_array[] = MJTC_majesticsupportphplib::MJTC_str_replace('majestic-support-', '', $key);
            $plugin_slug = MJTC_majesticsupportphplib::MJTC_str_replace('majestic-support-', '', $key);
        }
        $token = get_option('transaction_key_for_'.esc_attr($key));
        $result = array();
        $result['error'] = false;
        if($token == ''){
            $result['error'] = esc_html(__('Addon Installation Failed','majestic-support'));
            $result = json_encode($result);
            return $result;
        }
        $site_url = site_url();
        if($site_url != ''){
            $site_url = MJTC_majesticsupportphplib::MJTC_str_replace("https://","",$site_url);
            $site_url = MJTC_majesticsupportphplib::MJTC_str_replace("http://","",$site_url);
        }
        $url = 'https://majesticsupport.com/setup/index.php?token='.esc_attr($token).'&productcode='. json_encode($addon_json_array).'&domain=123';
        // verify token
        $verifytransactionkey = $this->verifytransactionkey($token, $url);
        if($verifytransactionkey['status'] == 0){
            $result['error'] = $verifytransactionkey['message'];
            $result = json_encode($result);
            return $result;
        }
        $install_count = 0;

        $installed = $this->install_plugin($url);
        if ( !is_wp_error( $installed ) && $installed ) {
            // had to run two seprate loops to save token for all the addons even if some error is triggered by activation.
            if(MJTC_majesticsupportphplib::MJTC_strstr($key, 'majestic-support-')){
                update_option('transaction_key_for_'.$key,$token);
            }

            if(MJTC_majesticsupportphplib::MJTC_strstr($key, 'majestic-support-')){
                $activate = activate_plugin( $key.'/'.$key.'.php' );
                $install_count++;
            }

            // run update sql
            if ($installedversion != $newversion) {
                $optionname = 'ms-addon-'. $plugin_slug .'s-version';
                update_option($optionname, $newversion);
                $plugin_path = WP_CONTENT_DIR;
                $plugin_path = $plugin_path.'/plugins/'.$key.'/includes';
                if(is_dir($plugin_path . '/sql/') && is_readable($plugin_path . '/sql/')){
                    if($installedversion != ''){
                        $installedversion = MJTC_majesticsupportphplib::MJTC_str_replace('.','', $installedversion);
                    }
                    if($newversion != ''){
                        $newversion = MJTC_majesticsupportphplib::MJTC_str_replace('.','', $newversion);
                    }
                    $this->getAddonUpdateSqlFromUpdateDir($installedversion,$newversion,$plugin_path . '/sql/');
                    $updatesdir = $plugin_path.'/sql/';
                    if(MJTC_majesticsupportphplib::MJTC_preg_match('/majestic-support-[a-zA-Z]+/', $updatesdir)){
                        msRemoveAddonUpdatesFolder($updatesdir);
                    }
                }else{
                    $this->getAddonUpdateSqlFromLive($installedversion,$newversion,$plugin_slug);
                }
            }

        }else{
            $result['error'] = esc_html(__('Addon Installation Failed','majestic-support'));
            $result = json_encode($result);
            return $result;
        }

        $result['success'] = esc_html(__('Addon Installed Successfully','majestic-support'));
        $result = json_encode($result);
        return $result;
    }

    function install_plugin( $plugin_zip ) {

        do_action('majesticsupport_load_wp_admin_file');
        WP_Filesystem();
        $tmpfile = download_url( $plugin_zip);

        if ( !is_wp_error( $tmpfile ) && $tmpfile ) {
            $plugin_path = WP_CONTENT_DIR;
            $plugin_path = $plugin_path.'/plugins/';
            $path = MJTC_PLUGIN_PATH.'addon.zip';
            copy( $tmpfile, $path );

            $unzipfile = unzip_file( $path, $plugin_path);

            @unlink( $path ); // must unlink afterwards
            @unlink( $tmpfile ); // must unlink afterwards

            if ( is_wp_error( $unzipfile ) ) {
                $result['error'] = esc_html(__('Addon installation failed','majestic-support')).'.';
                $result['error'] .= " ".wp_kses(majesticsupport::MJTC_getVarValue($unzipfile->get_error_message()), MJTC_ALLOWED_TAGS);
                $result = json_encode($result);
                return $result;
            } else {
                return true;
            }
        }else{
            $error_string = $tmpfile->get_error_message();
            $result['error'] = esc_html(__('Addon Installation Failed, File download error','majestic-support')).'! '.esc_attr($error_string);
            $result = json_encode($result);
            return $result;
        }
    }

    function verifytransactionkey($transactionkey, $url){
        $message = 1;
        if($transactionkey != ''){
            $response = wp_remote_post( $url );
            if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                $result = $response['body'];
                $result = json_decode($result,true);
                if(is_array($result) && isset($result[0]) && $result[0] == 0){
                    $result['status'] = 0;
                } else{
                    $result['status'] = 1;
                }
            }else{
                $result = false;
                if(!is_wp_error($response)){
                   $error = $response['response']['message'];
                }else{
                    $error = $response->get_error_message();
                }
            }
            if(is_array($result) && isset($result['status']) && $result['status'] == 1 ){ // means everthing ok
                $message = 1;
            }else{
                if(isset($result[0]) && $result[0] == 0){
                    $error = $result[1];
                }elseif(isset($result['error']) && $result['error'] != ''){
                    $error = $result['error'];
                }
                $message = 0;
            }
        }else{
            $message = 0;
            $error = esc_html(__('Please insert activation key to proceed','majestic-support')).'!';
        }
        $array['data'] = array();
        if ($message == 0) {
            $array['status'] = 0;
            $array['message'] = $error;
        } else {
            $array['status'] = 1;
            $array['message'] = 'success';
        }
        return $array;
        
    }

    function MJTC_getAddonsArray(){
        return array(
            'majestic-support-actions' => array('title' => esc_html(__('Ticket Actions','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-agent' => array('title' => esc_html(__('Agents','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-autoclose' => array('title' => esc_html(__('Ticket Auto Close','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-faq' => array('title' => esc_html(__('FAQs','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-helptopic' => array('title' => esc_html(__('Help Topic','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-maxticket' => array('title' => esc_html(__('Max Tickets','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-overdue' => array('title' => esc_html(__('Ticket Overdue','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-smtp' => array('title' => esc_html(__('SMTP','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-tickethistory' => array('title' => esc_html(__('Ticket History','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-useroptions' => array('title' => esc_html(__('User Options','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-mailchimp' => array('title' => esc_html(__('Mailchimp','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-export' => array('title' => esc_html(__('Export','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-announcement' => array('title' => esc_html(__('Announcements','majestic-support')), 'price' => 0, 'status' => 1),   
            'majestic-support-mail' => array('title' => esc_html(__('Internal Mail','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-note' => array('title' => esc_html(__('Private Note','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-cannedresponses' => array('title' => esc_html(__('Canned Response','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-woocommerce' => array('title' => esc_html(__('WooCommerce','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-privatecredentials'=> array('title' => esc_html(__('Private Credentials','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-envatovalidation' => array('title' => esc_html(__('Envato Validation','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-emailcc' => array('title' => esc_html(__('Email CC','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-feedback' => array('title' => esc_html(__('Feedback','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-knowledgebase' => array('title' => esc_html(__('Knowledge Base','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-mergeticket' => array('title' => esc_html(__('Merge Tickets','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-emailpiping' => array('title' => esc_html(__('Email Piping','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-timetracking' => array('title' => esc_html(__('Time Tracking','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-banemail' => array('title' => esc_html(__('Ban Email','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-notification' => array('title' => esc_html(__('Desktop Notification','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-download' => array('title' => esc_html(__('Downloads','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-agentautoassign' => array('title' => esc_html(__('Agent Auto Assign','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-multiform' => array('title' => esc_html(__('Multi Forms','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-dashboardwidgets' => array('title' => esc_html(__('Admin Widgets','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-widgets' => array('title' => esc_html(__('Front-end Widgets','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-paidsupport'  => array('title' => esc_html(__('Paid Support','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-easydigitaldownloads' => array('title' => esc_html(__('Easy Digital Downloads','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-multilanguageemailtemplates'  => array('title' => esc_html(__('Multi-Language Emails','majestic-support')), 'price' => 0, 'status' => 1),
            'majestic-support-ticketclosereason' => array('title' => esc_html(__('Ticket Closed Reason','majestic-support')), 'price' => 0, 'status' => 1),
        );
    }

}

?>
