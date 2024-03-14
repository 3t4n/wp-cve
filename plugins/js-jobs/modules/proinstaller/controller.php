<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSJOBSproinstallerController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        //$module = "proinstaller";
        if ($this->canAddLayout()) {
            $layout = JSJOBSrequest::getVar('layout', null, 'stepone');
            switch ($layout) {
                case 'stepone':
                    $nonce = JSJOBSrequest::getVar('_wpnonce');
                    if (! wp_verify_nonce( $nonce, 'stepone') ) {
                         die( 'Security check Failed' ); 
                    }
                    JSJOBSincluder::getJSModel('proinstaller')->getServerValidate();
                    break;
                case 'steptwo':
                    JSJOBSincluder::getJSModel('proinstaller')->getStepTwoValidate();
                    break;
            }
            $module = JSJOBSrequest::getVar('page', null, 'jsjobs_proinstaller');
            $module = jsjobslib::jsjobs_str_replace('jsjobs_', '', $module);
            JSJOBSincluder::include_file($layout, $module);
        }
    }

    function canAddLayout() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jsjobs')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jsjobtask')
            return false;
        else
            return true;
    }

    function startinstallation() {
        $enable = true;
        $disabled = jsjobslib::jsjobs_explode(', ', ini_get('disable_functions'));
        if ($disabled)
            if (in_array('set_time_limit', $disabled))
                $enable = false;

        if (!ini_get('safe_mode')) {
            if ($enable)
                set_time_limit(0);
        }
        $post_data = array();
        $post_data['transactionkey'] = JSJOBSrequest::getVar('transactionkey');
        $post_data['serialnumber'] = JSJOBSrequest::getVar('serialnumber');
        $post_data['domain'] = JSJOBSrequest::getVar('domain');
        $post_data['producttype'] = JSJOBSrequest::getVar('producttype');
        $post_data['productcode'] = JSJOBSrequest::getVar('productcode');
        $post_data['productversion'] = JSJOBSrequest::getVar('productversion');
        $post_data['JVERSION'] = JSJOBSrequest::getVar('JVERSION');
        $post_data['level'] = JSJOBSrequest::getVar('level');
        $post_data['installnew'] = JSJOBSrequest::getVar('installnew');
        $post_data['productversioninstall'] = JSJOBSrequest::getVar('productversioninstall');
        $post_data['count'] = JSJOBSrequest::getVar('count_config');
        $url = JCONSTV;
        $response = wp_remote_post( $url, array('body' => $post_data,'timeout'=>7,'sslverify'=>false));
        if(!is_wp_error($response)){
            $result = json_decode($response['body']);
        }

        if(isset($result[0]) && $result[0] == 1){ // means everthing ok
            $plugin_url = $result[1];
            $sql_url = $result[2];
            $installed = $this->install_plugin($plugin_url, $sql_url);
            if ( !is_wp_error( $installed ) && $installed ) {
                $link = admin_url("admin.php?page=jsjobs"); 
                wp_redirect($link);
                exit;
            } else {
                $url = wp_nonce_url(admin_url("admin.php?page=jsjobs&jsjobslt=stepone"),"stepone");
                wp_redirect($url);
                exit;
            }
        }else{
            if(isset($result[0]) && $result[0] == 0){
				$error = $result[1];
                echo esc_attr($error);
            }elseif(isset($result['error']) && $result['error'] != ''){
                $error = $result['error'];
				echo esc_attr($error);
            }
        }
    }
    
    function install_plugin( $plugin_zip, $sql_zip ) {
        jsjobs::$_data['data_directory'] = JSJobsIncluder::getJSModel('configuration')->getConfigurationByConfigName('data_directory');
        $currenttheme = $this->getJSSTCurrentTheme(); //get current theme
        include(ABSPATH . "wp-admin/includes/admin.php");
        WP_Filesystem();
        $base = ABSPATH;
        $tmpfile = download_url($plugin_zip);
        if ( !is_wp_error( $tmpfile ) && $tmpfile ) {
            $plugin_path = WP_PLUGIN_DIR;
            $plugin_path = $plugin_path.'/js-jobs/';
            $path = JSJOBS_PLUGIN_PATH.'plugin.zip';
            $this->recursiveremove($plugin_path);
            $this->makeDir($plugin_path);
            copy($tmpfile, $path);
            require_once $base . 'wp-admin/includes/class-pclzip.php';
            $archive = new PclZip($path);
            $unzipfile = $archive->extract($plugin_path);
    
            @unlink( $path ); // must unlink afterwards
            @unlink( $tmpfile ); // must unlink afterwards
            if ( is_wp_error( $unzipfile ) ) {
                $url = wp_nonce_url(admin_url("admin.php?page=jsjobs&jsjobslt=stepone"),"stepone");
                wp_redirect($url);
                exit;
            } else {
                $tmpfile = download_url($sql_zip);
                $path = JSJOBS_PLUGIN_PATH.'sql.zip';
                copy($tmpfile, $path);
                $archive = new PclZip($path);
                $unzipfile = $archive->extract($plugin_path);
                                
                if (file_exists($unzipfile[0]['filename'])) {
                    $delimiter = ';';
                    $file = fopen($unzipfile[0]['filename'], 'r');
                    if (is_resource($file) === true) {
                        $query = array();
                        while (feof($file) === false) {
                            $query[] = fgets($file);
                            if (jsjobslib::jsjobs_preg_match('~' . preg_quote($delimiter, '~') . '\s*$~iS', end($query)) === 1) {
                                $query = jsjobslib::jsjobs_trim(implode('', $query));
                                $query = jsjobslib::jsjobs_str_replace("#__", jsjobs::$_db->prefix, $query);
                                if (!empty($query)) {
                                    jsjobs::$_db->query($query);
                                }
                            }
                            if (is_string($query) === true) {
                                $query = array();
                            }
                        }
                        fclose($file);
                    }
                }

                @unlink($path); // must unlink afterwards
                @unlink($tmpfile); // must unlink afterwards
                @unlink($unzipfile[0]['filename']); // must unlink sql
                $this->storeJSSTTheme($currenttheme);
                return true;
            }
        }else{
            $url = wp_nonce_url(admin_url("admin.php?page=jsjobs&jsjobslt=stepone"),"stepone");
            wp_redirect($url);
            exit;
        }
    }

    function makeDir($path){
        if (!file_exists($path)){ 
            mkdir($path, 0755);
            $ourFileName = $path.'/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("can't open file ($ourFileName)");
            fclose($ourFileHandle);
        }
    }

    function recursiveremove($dir) {
        $structure = glob(jsjobslib::jsjobs_rtrim($dir, "/").'/*');
        if (is_array($structure)) {
        foreach($structure as $file) {
            if (is_dir($file)){
                if($file != $dir."/".jsjobs::$_data['data_directory'] && $file != $dir."/languages")
                    $this->recursiveremove($file);
            }elseif (is_file($file)){
            if(file_exists($file)){
                if(is_writable($file)){
                     if(!unlink($file)){
                    }else{
                    }
                }else{
                }
            }else{
            }
            }
        }
        }
        if(count(glob("$dir/*")) === 0 )
            rmdir($dir);
    }

    function storeJSSTTheme($themedata) {
        $filepath = JSJOBS_PLUGIN_PATH . 'includes/css/style_color.php';
        $filestring = file_get_contents($filepath);
        $this->JSSTreplaceString($filestring, 1, $themedata);
        $this->JSSTreplaceString($filestring, 2, $themedata);
        $this->JSSTreplaceString($filestring, 3, $themedata);
        $this->JSSTreplaceString($filestring, 4, $themedata);
        $this->JSSTreplaceString($filestring, 5, $themedata);
        $this->JSSTreplaceString($filestring, 6, $themedata);
        $this->JSSTreplaceString($filestring, 7, $themedata);
        $this->JSSTreplaceString($filestring, 8, $themedata);
        $this->JSSTreplaceString($filestring, 9, $themedata);
        if (file_put_contents($filepath, $filestring)) {
            return true;
        } else {
            return false;
        }
        return;
    }

    function JSSTreplaceString(&$filestring, $colorNo, $data) {
        if (jsjobslib::jsjobs_strstr($filestring, '$color' . $colorNo)) {
            $path1 = jsjobslib::jsjobs_strpos($filestring, '$color' . $colorNo);
            $path2 = jsjobslib::jsjobs_strpos($filestring, ';', $path1);
            $filestring = substr_replace($filestring, '$color' . $colorNo . ' = "' . $data['color' . $colorNo] . '";', $path1, $path2 - $path1 + 1);
        }
    }

    function getJSSTColorCode($filestring, $colorNo) {
        if (jsjobslib::jsjobs_strstr($filestring, '$color' . $colorNo)) {
            $path1 = jsjobslib::jsjobs_strpos($filestring, '$color' . $colorNo);
            $path1 = jsjobslib::jsjobs_strpos($filestring, '#', $path1);
            $path2 = jsjobslib::jsjobs_strpos($filestring, ';', $path1);
            $colorcode = jsjobslib::jsjobs_substr($filestring, $path1, $path2 - $path1 - 1);
            return $colorcode;
        }
    }

    function getJSSTCurrentTheme() {
        $filepath = JSJOBS_PLUGIN_PATH . 'includes/css/style_color.php';
        $filestring = file_get_contents($filepath);
        $theme['color1'] = $this->getJSSTColorCode($filestring, 1);
        $theme['color2'] = $this->getJSSTColorCode($filestring, 2);
        $theme['color3'] = $this->getJSSTColorCode($filestring, 3);
        $theme['color4'] = $this->getJSSTColorCode($filestring, 4);
        $theme['color5'] = $this->getJSSTColorCode($filestring, 5);
        $theme['color6'] = $this->getJSSTColorCode($filestring, 6);
        $theme['color7'] = $this->getJSSTColorCode($filestring, 7);                                                    
        $theme['color8'] = $this->getJSSTColorCode($filestring, 8);                                                    
        $theme['color9'] = $this->getJSSTColorCode($filestring, 9);                                                    
        return $theme;
    }

}

$JSJOBSproinstallerController = new JSJOBSproinstallerController();
?>
