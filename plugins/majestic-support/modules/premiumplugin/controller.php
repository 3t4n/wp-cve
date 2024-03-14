<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class MJTC_premiumpluginController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $module = "premiumplugin";
        if ($this->canAddLayout()) {
            $layout = MJTC_request::MJTC_getLayout('mjslay', null, 'step1');
            switch ($layout) {
                case 'admin_step1':
                    majesticsupport::$_data['versioncode'] = MJTC_includer::MJTC_getModel('configuration')->getConfigurationByConfigName('versioncode');
                    majesticsupport::$_data['productcode'] = MJTC_includer::MJTC_getModel('configuration')->getConfigurationByConfigName('productcode');
                    majesticsupport::$_data['producttype'] = MJTC_includer::MJTC_getModel('configuration')->getConfigurationByConfigName('producttype');
                break;
            }
            $module =  'premiumplugin';
            MJTC_includer::MJTC_include_file($layout, $module);
        }
    }

    function canAddLayout() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'majesticsupport')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'mstask')
            return false;
        else
            return true;
    }

    function verifytransactionkey(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'verify-transaction-key') ) {
            die( 'Security check Failed' );
        }
        $post_data['transactionkey'] = MJTC_request::MJTC_getVar('transactionkey','','');
        if($post_data['transactionkey'] != ''){


            $post_data['domain'] = site_url();
            $post_data['step'] = 'one';
            $post_data['myown'] = 1;

            $url = 'https://majesticsupport.com/setup/index.php';

            $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
            if( !is_wp_error($response) && $response['response']['code'] == 200 && isset($response['body']) ){
                $result = $response['body'];
                $result = json_decode($result,true);
            }else{
                $result = false;
                if(!is_wp_error($response)){
                   $error = $response['response']['message'];
               }else{
                    $error = $response->get_error_message();
               }
            }
            if(is_array($result) && isset($result['status']) && $result['status'] == 1 ){ // means everthing ok
                $resultaddon = json_encode($result);
                $resultaddon = MJTC_majesticsupportphplib::MJTC_safe_encoding( $resultaddon );
                $result['actual_transaction_key'] = $post_data['transactionkey'];
                // in case of session not working
                add_option('ms_addon_install_data',json_encode($result));
                $url = admin_url("admin.php?page=majesticsupport_premiumplugin&mjslay=step2");
                wp_redirect($url);
                return;
            }else{
                if(isset($result[0]) && $result[0] == 0){
                    $error = $result[1];
                }elseif(isset($result['error']) && $result['error'] != ''){
                    $error = $result['error'];
                }
            }
        }else{
            $error = esc_html(__('Please insert activation key to proceed','majestic-support')).'!';
        }
        $array['data'] = array();
        $array['status'] = 0;
        $array['message'] = $error;
        $array['transactionkey'] = $post_data['transactionkey'];
        $array = json_encode( $array );
        $array = MJTC_majesticsupportphplib::MJTC_safe_encoding($array);
        MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , $array , 0, COOKIEPATH);
        if ( SITECOOKIEPATH != COOKIEPATH ){
            MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , $array , 0, SITECOOKIEPATH);
        }
        $url = admin_url("admin.php?page=majesticsupport_premiumplugin&mjslay=step1");
        wp_redirect($url);
        return;
    }

    function downloadandinstalladdons(){
        $nonce = MJTC_request::MJTC_getVar('_wpnonce');
        if (! wp_verify_nonce( $nonce, 'download-and-install-addons') ) {
            die( 'Security check Failed' );
        }
        $post_data = MJTC_request::get('post');

        $addons_array = $post_data;
        if(isset($addons_array['token'])){
            unset($addons_array['token']);
        }
        $addon_json_array = array();

        foreach ($addons_array as $key => $value) {
            if($key != ''){
                $addon_json_array[] = MJTC_majesticsupportphplib::MJTC_str_replace('majestic-support-', '', $key);
            }
        }
        $token = $post_data['token'];
        if($token == ''){
            $array['data'] = array();
            $array['status'] = 0;
            $array['message'] = esc_html(__('Addon Installation Failed','majestic-support')).'!';
            $array['transactionkey'] = $post_data['transactionkey'];
            $array = json_encode( $array );
            $array = MJTC_majesticsupportphplib::MJTC_safe_encoding($array);
            MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , $array , 0, COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , $array , 0, SITECOOKIEPATH);
            }
            $url = admin_url("admin.php?page=majesticsupport_premiumplugin&mjslay=step1");
            wp_redirect($url);
            exit;
        }
        $site_url = site_url();
        if($site_url != ''){
		    $site_url = MJTC_majesticsupportphplib::MJTC_str_replace("https://","",$site_url);
            $site_url = MJTC_majesticsupportphplib::MJTC_str_replace("http://","",$site_url);
        }
        $url = 'https://majesticsupport.com/setup/index.php?token='.esc_attr($token).'&productcode='. json_encode($addon_json_array).'&domain='. esc_attr($site_url);

        $install_count = 0;

        $installed = $this->install_plugin($url);
        if ( !is_wp_error( $installed ) && $installed ) {
            // had to run two seprate loops to save token for all the addons even if some error is triggered by activation.
            foreach ($post_data as $key => $value) {
                if(MJTC_majesticsupportphplib::MJTC_strstr($key, 'majestic-support-')){
                    update_option('transaction_key_for_'.$key,$token);
                }
            }

            foreach ($post_data as $key => $value) {
                if(MJTC_majesticsupportphplib::MJTC_strstr($key, 'majestic-support-')){
                    $activate = activate_plugin( $key.'/'.$key.'.php' );
                    $install_count++;
                }
            }

        }else{
            $array['data'] = array();
            $array['status'] = 0;
            $array['message'] = esc_html(__('Addon Installation Failed','majestic-support')).'!';
            $array['transactionkey'] = $post_data['transactionkey'];
            $array = json_encode( $array );
            $array = MJTC_majesticsupportphplib::MJTC_safe_encoding($array);
            MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , $array , 0, COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , $array , 0, SITECOOKIEPATH);
            }

            $url = admin_url("admin.php?page=majesticsupport_premiumplugin&mjslay=step1");
            wp_redirect($url);
            exit;
        }
        $url = admin_url("admin.php?page=majesticsupport_premiumplugin&mjslay=step3");
        wp_redirect($url);
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
                $array['data'] = array();
                $array['status'] = 0;
                $array['message'] = esc_html(__('Addon installation failed','majestic-support')).'.';
                $array['message'] .= " ".wp_kses(majesticsupport::MJTC_getVarValue($unzipfile->get_error_message(), MJTC_ALLOWED_TAGS));
                $array['transactionkey'] = $post_data['transactionkey'];
                $array = json_encode( $array );
                $array = MJTC_majesticsupportphplib::MJTC_safe_encoding($array);
                MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , $array , 0, COOKIEPATH);
                if ( SITECOOKIEPATH != COOKIEPATH ){
                    MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , $array , 0, SITECOOKIEPATH);
                }

                $url = admin_url("admin.php?page=majesticsupport_premiumplugin&mjslay=step1");
                wp_redirect($url);
                exit;
            } else {
                return true;
            }
        }else{
            $array['data'] = array();
            $array['status'] = 0;
            $error_string = $tmpfile->get_error_message();
            $array['message'] = esc_html(__('Addon Installation Failed, File download error','majestic-support')).'! '.$error_string;
            $array['transactionkey'] = $post_data['transactionkey'];
            $array = json_encode( $array );
            $array = MJTC_majesticsupportphplib::MJTC_safe_encoding($array);
            MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , $array , 0, COOKIEPATH);
            if ( SITECOOKIEPATH != COOKIEPATH ){
                MJTC_majesticsupportphplib::MJTC_setcookie('ms_addon_return_data' , $array , 0, SITECOOKIEPATH);
            }
            $url = admin_url("admin.php?page=majesticsupport_premiumplugin&mjslay=step1");
            wp_redirect($url);
            exit;
        }
    }
}
$MJTC_premiumpluginController = new MJTC_premiumpluginController();
?>
