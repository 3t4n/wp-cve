<?php

/**
 * Google drive import/export section of the plugin
 *
 * @link       
 * @since 1.1.8     
 *
 * @package  Wp_Migration_Duplicator 
 */
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Wp_Migration_Google_Drive')) {

    require_once plugin_dir_path(dirname(__FILE__)) . '../vendor/autoload.php';

    class Wp_Migration_Google_Drive
    {

        public $module_base             =   'googledrive';
        public static $module_id_static =   '';
        public $module_id;

        public static $accesstoken_key;
        public static $googledrive_location_key;
        public static $client_secret_key;
        public static $client_id_key;

        protected $client_id;
        protected  $client_secret;

        protected $client;

        public $accesstoken;
        protected $googledrive_location;
        protected $status;
        protected $callback_url;
        protected $api_url;

        public $authenticated;

        public function __construct()
        {

            $this->google_client    = new Google_Client();
            $this->module_id        =   Wp_Migration_Duplicator::get_module_id($this->module_base);
            self::$module_id_static =   $this->module_id;
            self::$accesstoken_key = $this->module_base . '_' . 'accesstoken';
            self::$googledrive_location_key = $this->module_base . '_' . 'location';
            self::$client_id_key = $this->module_base . '_' . 'client_id';
            self::$client_secret_key = $this->module_base . '_' . 'client_secret';
            
            $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();
            
            $this->client_id = $options[self::$client_id_key];
            $this->client_secret = $options[self::$client_secret_key];
            $this->accesstoken = $options[self::$accesstoken_key];
            $this->googledrive_location = $options[self::$googledrive_location_key];
            $this->status = $options[$this->module_base . '_' . 'status'];
            
            $this->client = $this->is_authenticated();
         
            add_filter('wt_mgdb_export_options', array($this, 'add_google_drive_export'), 11, 1);
            add_filter('wtmgdp_export_output', array($this, 'check_google_drive_option_used'), 10, 1);

            add_filter('wt_mgdp_general_settings_tabhead', array(__CLASS__, 'settings_tabhead'));
            add_action('wt_mgdp_plugin_out_storage_settings_form', array($this, 'out_settings_form'));
            add_action('wp_loaded', array($this, 'authenticate_google_drive'), 10, 0);

            add_action('wp_loaded', array($this, 'authenticate_cloud'), 10, 0);
            add_action('wp_ajax_wp_mgdp_disconnect_googledrive', array($this, 'disconnect_googledrive'), 10, 0);
            add_filter('wt_mgdb_import_options', array($this, 'import_options'), 10, 1);
            add_action('mgdp_after_import_form', array($this, 'google_drive_form_on_import'), 10, 0);
            add_filter('wt_migrator_get_import_attachment_url', array($this, 'get_attachment_url_from_google_drive'), 10, 2);
             add_action('wt_migrator_after_export_page_content_schedule', array($this, 'add_google_drive_export_form_items_schedule'), 10, 0);

            add_action('wt_migrator_after_export_page_content', array($this, 'add_google_drive_export_form_items'), 10, 0);

            add_action('wp_ajax_wp_mgdp_check_googledrive_authentication', array($this, 'wt_googledrive_ajax_authentication'));
            add_filter('wt_migrator_googledrive_is_authenticated', array($this, 'is_authenticated'));
            add_filter("wt_migrator_googledrive_load_backups", array($this, "get_existing_backups"));
        }
        /**
         * Add Google drive tab head
         * @since 1.1.8
         */
        public static function settings_tabhead($tab_items)
        {
            $tab_items['wt-googledrive'] = __('Google Drive', 'wp-migration-duplicator');
            return $tab_items;
        }
        /** 
         * Module settings form
         * @since 1.1.8
         */
        public function out_settings_form($args)
        {
            wp_enqueue_script($this->module_id, plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), WP_MIGRATION_DUPLICATOR_VERSION);
            $params = array(
                'module_base' => $this->module_base,
                'nonce' =>  wp_create_nonce($this->module_id),
                'ajax_url'  => admin_url('admin-ajax.php'),
                'error_messages' => array(
                    'fields_missing' => __('Check if all the fields have correct information and retry.', 'wp-migration-duplicator'),
                    'auth_error' => __('Authentication failed', 'wp-migration-duplicator')
                )

            );
            wp_localize_script($this->module_id, 'wp_migration_duplicator_googledrive', $params);
            $view_file  = plugin_dir_path(__FILE__) . 'views/settings.php';
            
            $this->authenticated = ( $this->client !== false ? true : false );

            $params =   array(
                'is_enabled'    => $this->is_enabled(),
                'authenticated' => $this->authenticated,
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret
            );
            Wp_Migration_Duplicator_Admin::envelope_settings_tabcontent('wt-googledrive', $view_file, '', $params, 0);
        }

        /**
         * Add new export option using Google Drive
         * @since 1.1.8
         */
        function add_google_drive_export($export_option)
        {
            if ($this->is_enabled()) {
                $export_option['googledrive'] = __('Google Drive', 'wp-migration-duplicator');
            }
            return $export_option;
        }
        /**
         * Google Drive export action
         * @since 1.1.8
         */
        function check_google_drive_option_used($out)
        {       
            $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();
            $export_option_post = (isset($_POST['export_option'])) ? $_POST['export_option'] : $out['export_option'];
            $export_option_post = Wp_Migration_Duplicator_Security_Helper::sanitize_item($export_option_post);
            $cloud_storage_name = apply_filters('wt_migrator_cloud_storage_location', WT_MGDP_CLOUD_STORAGE_LOCATION);
            Webtoffe_logger::write_log( 'Export',$export_option_post .' file upload started .. ' );
            if ('googledrive' == $export_option_post) {
               $out['export_option'] = 'googledrive';
                if ( $this->client ) {
                    $client = $this->client;
                    $service = new Google_Service_Drive($client);
                    $parent_folder = $this->listFilesFolders($cloud_storage_name, 'root', 'folders');

                    if (empty($parent_folder)) {
                        $parent_id = $this->createFolder('root', $cloud_storage_name);
                    } else {
                        $parent_id = count($parent_folder) ? array_keys($parent_folder)[0] : null;
                    }
                    $options[self::$googledrive_location_key] = $parent_id;
                    Wp_Migration_Duplicator::update_webtoffee_migrator_option( $options );
                    $this->googledrive_location = $parent_id;
                    if ($client->getAccessToken()) {
                        $backup_file = WP_CONTENT_DIR . Wp_Migration_Duplicator::$backup_dir_name . "/" . $out['backup_file_name'];
                        $file_name = (isset($_POST['google_drive_file_name']) && '' != $_POST['google_drive_file_name']) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['google_drive_file_name']) : date('Y-m-d-h-i-sa', time());
                        $file = new Google_Service_Drive_DriveFile(array(
                            'name' => $file_name,
                            'parents' => array($parent_id)
                        ));

                        $file->setDescription(__('backup using WP Migrator Duplicator', 'wp-migration-duplicator'));
                        try {

                            $chunkSizeBytes = 1 * 1024 * 1024;

                            // Call the API with the media upload, defer so it doesn't immediately return.
                            $client->setDefer(true);
                            $request = $service->files->create($file);

                            // Create a media file upload to represent our upload process.
                            $media = new Google_Http_MediaFileUpload(
                                $client,
                                $request,
                                'application/zip',
                                null,
                                true,
                                $chunkSizeBytes
                            );
                            $media->setFileSize(filesize($backup_file));
                            // Upload the various chunks. $status will be false until the process is
                            // complete.
                            $status = false;
                            $handle = fopen($backup_file, "rb");
                            while (!$status && !feof($handle)) {
                                // read until you get $chunkSizeBytes from TESTFILE
                                // fread will never return more than 8192 bytes if the stream is read buffered and it does not represent a plain file
                                // An example of a read buffered file is when reading from a URL
                                $chunk = $this->readVideoChunk($handle, $chunkSizeBytes);
                                $status = $media->nextChunk($chunk);
                            }

                            // The final value of $status will be the data from the API for the object
                            // that has been uploaded.
                            $result = false;
                            if ($status != false) {
                                $result = $status;
                            }
                            fclose($handle);
                            $out['status']  = true;
                            unlink($out['backup_file']);
                            $out['msg']     =  __('Successfully uploaded !!', 'wp-migration-duplicator');
                            Webtoffe_logger::write_log( 'Export','Zip File Successfully uploaded !! ' );
                                     
                        } catch (Exception $e) {
                            $error_message = "ERROR: upload error (" . get_class($e) . "): " . $e->getMessage() . ' (line: ' . $e->getLine() . ', file: ' . $e->getFile() . ')';
                            Webtoffe_logger::error($error_message);
                            $out['status']  = false;
                            $out['msg']     =  $error_message;
                            Webtoffe_logger::write_log( 'Export',$error_message );
                        }
                    }
                } else {
                    $out['status']  = false;
                    $out['msg']     =  __('Please authenticate your google drive account', 'wp-migration-duplicator');
                    Webtoffe_logger::write_log( 'Export','Please authenticate your google drive account.' );
                }
                Webtoffe_logger::write_log( 'Export','---[ Export Ended at '.date('Y-m-d H:i:s').' ] --- ' );
                    delete_option('wp_mgdp_log_id'); 
            }
            return $out;
        }
        // read
        function readVideoChunk($handle, $chunkSize)
        {
            $byteCount = 0;
            $giantChunk = "";
            while (!feof($handle)) {
                // fread will never return more than 8192 bytes if the stream is read buffered and it does not represent a plain file
                $chunk = fread($handle, 8192);
                $byteCount += strlen($chunk);
                $giantChunk .= $chunk;
                if ($byteCount >= $chunkSize) {
                    return $giantChunk;
                }
            }
            return $giantChunk;
        }
        /**
         * Authenticate google drive
         * @since 1.1.8
         */
        function authenticate_google_drive()
        {      
            if (isset($_POST['wt_authenticate_google_form'])) {
                check_admin_referer($this->module_id,'_google_drive_auth');
                $client_id      = Wp_Migration_Duplicator_Security_Helper::sanitize_item(((isset($_POST['wt_google_client_id'])) ? $_POST['wt_google_client_id'] : ''));
                $client_secret  = Wp_Migration_Duplicator_Security_Helper::sanitize_item(((isset($_POST['wt_google_client_secret'])) ? $_POST['wt_google_client_secret'] : ''));
                $instance_id = $this->create_instance();
                $this->callback_url = admin_url('admin.php?page=wp-migration-duplicator-settings');
                $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();
                $options[self::$client_id_key] = $client_id;
                $options[self::$client_secret_key] = $client_secret;
                Wp_Migration_Duplicator::update_webtoffee_migrator_option($options );
                $params = array(
                    'response_type' => 'code',
                    'client_id' => $client_id,
                    'redirect_uri' => $this->callback_url,
                    'scope' => apply_filters('wt_migrator_googledrive_scope', 'https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/drive.readonly https://www.googleapis.com/auth/userinfo.profile'),
                    'state' => $instance_id,
                    'access_type' => 'offline',
                    'approval_prompt' => 'force'
                );
                $url = 'https://accounts.google.com/o/oauth2/auth?'.http_build_query($params, null, '&');
                
                header('Location: '.$url);   
            }
        }
        public function get_client() {
            $this->callback_url = admin_url('admin.php?page=wp-migration-duplicator-settings');
            try {
                if($sock = @fsockopen('www.google.com', 80))
                {
                    $client = new Google_Client();
                    $client->setApplicationName('Webtoffee Wordpress Migration & Backup');
                    $client->setClientId( $this->client_id );
                    $client->setClientSecret( $this->client_secret );
                    $client->setScopes(array('https://www.googleapis.com/auth/drive.file', 'https://www.googleapis.com/auth/drive.readonly', 'https://www.googleapis.com/auth/userinfo.profile'));
                    $client->setRedirectUri( $this->callback_url );
                    $client->setAccessType('offline');
                    $client->setPrompt('force');                   
                }else{
                    Webtoffe_logger::error("Internet connecton required");
                    return false;
                }
            } catch (Exception $e) {
                Webtoffe_logger::error($e->getMessage());
                return false;
            }
            return $client;
        }
        function authenticate_cloud()
        {       
            if (isset( $_GET['code'] ) && isset( $_GET['state'])) {
                $client = $this->get_client();
                if( !$client) {
                    return false;
                }
                $code = $_GET['code'];
                $state =  $_GET['state'];
                $token = $client->fetchAccessTokenWithAuthCode( $code );
                $access_token = ( isset( $token['access_token'] ) ? $token : '' );
                if( '' !== $access_token ) {
                    $state = ( isset( $state ) ? $state : '' );
                    if( '' !== $state ) {
                        if( !empty( $state )) {
                            $tmp_token = $state;
                            if( $tmp_token === $this->get_last_instance_id() ) {

                                $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();
                                $options[self::$accesstoken_key] = $access_token;
                                $this->accesstoken = $access_token;
                                $client->setAccessToken( $access_token);
                                $this->client = $client;
                                Wp_Migration_Duplicator::update_webtoffee_migrator_option($options );
                            }
                        }
                    }
                }
            }
        }
        public function create_instance(){
            $instance_id = 'mgdp-'.wp_create_nonce( $this->module_id );
            $transient_key = '_wt_migrator_'.$this->module_id.'instance';
			set_transient($transient_key, $instance_id, 3600);
            return $instance_id;
        }
        public function get_last_instance_id(){
            $transient_key = '_wt_migrator_'.$this->module_id.'instance';
            $transient_value = get_transient($transient_key);
            return $transient_value;
        }
        /**
         * Helper function for checking is authenticated by any profile
         * @since 1.1.8
         */
        function is_authenticated()
        {    
            $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();
            $googledrive_client_id = $options['googledrive_client_id'];
            $googledrive_client_secret = $options['googledrive_client_secret'];
            $access_token = $options[self::$accesstoken_key];
            if( empty( $googledrive_client_id ) || empty( $googledrive_client_secret ) || ( empty( $access_token ))) {
                return false;
            } 
            $client = $this->get_client();
            if( !$client) {
                return false;
            }
            $client->setAccessToken($access_token);
            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                   $token = $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                   if( isset( $token )) {
                    $options[self::$accesstoken_key] = $token;
                    $this->accesstoken = $token;
                    Wp_Migration_Duplicator::update_webtoffee_migrator_option($options);
                   }
                } else {
                    return false;
                }
            }
            return $client;
        }
        /**
        * Desceiption
        *
        * @since  1.0.0
        * @throws Exception Error message.
        * @return array
        */
        public function wt_googledrive_ajax_authentication()
        {
            if (!Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_PLUGIN_FILENAME, $this->module_id)) {
                wp_die(__('You do not have sufficient permission to perform this operation', 'wp-migration-duplicator'));
            }
            
            if ($this->client) {
                wp_send_json_success(__('Authentication success!', 'wp-migration-duplicator'));
            }
            wp_send_json_error(__('Authentication failed', 'wp-migration-duplicator'));
        }
        /**
         * disconnect google drive
         * @since 1.1.8
         */
        function disconnect_googledrive()
        {       
            if (!Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_PLUGIN_FILENAME, $this->module_id)) {
                wp_die(__('You do not have sufficient permission to perform this operation', 'wp-migration-duplicator'));
            }
            $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();
            $this->accesstoken = '';
            $this->client_id = '';
            $this->client_secret = '';
            $options[self::$accesstoken_key] = '';
            $options[self::$googledrive_location_key] = '';
            $options[self::$client_id_key]='';
            $options[self::$client_secret_key]='';
            Wp_Migration_Duplicator::update_webtoffee_migrator_option($options);
            wp_send_json_success(__('Disconnected!', 'wp-migration-duplicator'));
        }
        /**
         * import options
         * @since 1.1.8
         */
        function import_options($import_options)
        {
            if ($this->is_enabled()) {
                $import_options['googledrive'] = __('Google Drive', 'wp-migration-duplicator');
            }
            return $import_options;
        }

        /**
         * Form for importing from google drive
         * @since 1.1.8
         */
        function google_drive_form_on_import()
        {
?>

            <div class="wt-migrator-cloud-storage-info child-form-item child-wt_mgdb_import_option wt_mgdb_import_option_googledrive" style="display:none">
                <?php
                $url = admin_url('admin.php?page=wp-migration-duplicator-settings#wt-googledrive');
                ?>
                <span class="wt-migrator-authentication-error"><?php echo sprintf(wp_kses(__('Your account with Google drive is not authenticated please click <a href="%s">here</a> to authenticate', 'wp-migration-duplicator'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url)); ?></span>
                <table class="wf-form-table wt_mgdp_import_options " style="max-width:650px;">
                    <tbody>
                        <tr>
                            <th style="font-weight: 400"><?php _e('Enable Google Drive', 'wp-migration-duplicator') ?></th>
                            <td>
                            <span class="wt-cli-cloud-status wt-cli-cloud-status-enabled">
                                <span class="dashicons dashicons-saved"></span><?php _e('(Enabled)','wp-migration-duplicator');?>
                            </span>
                                <a  href="<?php echo esc_url(admin_url('admin.php?page=wp-migration-duplicator-settings#wt-googledrive'))?>"><?php echo esc_html__('Settings','wp-migration-duplicator-pro')?></a>
                            </td>
                        </tr>
                        <tr>
                            <th style="font-weight: 400">
                                <?php _e('Google Drive File Name', 'wp-migration-duplicator') ?>
                                <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The exact file name of the backup file to import', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                            </th>
                            <td>
                                <select class="wt-migrator-select2-single wt-migrator-cloud-import-file wt-migrator-input wt-migrator-file"  data-hidden-name="wt_mgdb_google_drive_file" id ='wt_mgdb_google_drive_file' style="width:100%">
                                    <option value="-1"><?php echo esc_html__('Please select a backup file','wp-migration-duplicator');?></option>
                                </select>
                                <input type="hidden" name="wt_mgdb_google_drive_file">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php
        }

        /**
         * Export product from google drive
         * @since 1.1.8
         */

        function get_attachment_url_from_google_drive($import_data, $import_method)
        {   
            if ('googledrive' != $import_method) {
                return $import_data;
            }
            $error_message = __('The specified file could not be found on Google drive','wp-migration-duplicator');
            $import_data['message'] = $error_message;
            
            $file_name = (isset($_POST['google_drive_file'])) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['google_drive_file']) : '';
            if ('' == $file_name) {
                $import_data['message'] = __('Please specify a file to import','wp-migration-duplicator');
                return $import_data;
            }

            $local_file_url = content_url() . Wp_Migration_Duplicator::$backup_dir_name . '/temp-import-file.zip';
            $local_file = Wp_Migration_Duplicator::$backup_dir . '/temp-import-file.zip';
           
            if ($this->client) {
                $client = $this->client;
                if ($client->getAccessToken()) {

                    $service        = new Google_Service_Drive($client);
                    $parent_id = $this->googledrive_location;
                    try {

                        // $query = "'" . $parent_id . "' in parents and mimeType != 'application/vnd.google-apps.folder' and name = 'Mgdp' and trashed = false";
                        // $files_list = $service->files->listFiles([
                        //     'q' => $query,
                        //     'fields' => 'files(id,size)'
                        // ]);
                        // if (count($files_list) == 0) {
                        //     $error_message = "The required file " . $file_name . " could  not be found on the cloud storage";
                        //     Webtoffe_logger::debug($error_message);
                        //     return $return;
                        // }
                        $fileId = $file_name;
                        $optpParams = array(
                            'fields' => "size"
                            );
                        $file_info = $service->files->get( $fileId ,$optpParams);
                        $fileSize = ( isset( $file_info->size ) ? intval($file_info->size) : -1 ); ;
                        if( $fileSize < 0) {
                            return $import_data;
                        }
                        $http = $client->authorize();
						ob_end_clean();
                        $fp = fopen($local_file, 'w');
                        $chunkSizeBytes = 1 * 1024 * 1024;
                        $chunkStart = 0;

                        while ($chunkStart < $fileSize) {
                            $chunkEnd = $chunkStart + $chunkSizeBytes;
                            $response = $http->request(
                                'GET',
                                sprintf('/drive/v3/files/%s', $fileId),
                                [
                                    'query' => ['alt' => 'media'],
                                    'headers' => [
                                        'Range' => sprintf('bytes=%s-%s', $chunkStart, $chunkEnd)
                                    ]
                                ]
                            );
                            $chunkStart = $chunkEnd + 1;
                            fwrite($fp, $response->getBody()->getContents());
                        }
                        fclose($fp);
                        $import_data['status'] = true;
                        $import_data['message'] = __('File has fetched from the google drive, now importing the file....','wp-migration-duplicator');
                        $import_data['file'] = $local_file_url;
                        return $import_data;
                    } catch (Exception $e) {
                        Webtoffe_logger::error($e->getMessage());
                        return $import_data;
                    }
                }
            }
            return false;
        }

        /**
         * Add form feields for Import option using Google drive
         * @since 1.1.8
         */
        function add_google_drive_export_form_items()
        {
        ?>
            <div class="wt-migrator-cloud-storage-info child-form-item child-wt_mgdb_export_option wt_mgdb_export_option_googledrive time_class" style="display:none">
                <?php
                $url = admin_url('admin.php?page=wp-migration-duplicator-settings#wt-googledrive');
                ?>
                <span class="wt-migrator-authentication-error"><?php echo sprintf(wp_kses(__('Your account with Google drive is not authenticated please click <a href="%s">here</a> to authenticate', 'wp-migration-duplicator'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url)); ?></span>
                <table class="wf-form-table" style="max-width:650px;">
                    <tbody>
                        <tr>
                            <th style="font-weight: 400">
                                <?php _e('Export Filename', 'wp-migration-duplicator') ?>
                                <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Allows to add a custom name to the backup file, leave it blank if you want the plugin to define the name for your backup file.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                            </th>
                            <td>
                                <input type="text" class="wt-migrator-input wt-migrator-file" name="wt_mgdb_google_drive_file_name" disabled placeholder="e.g. critical-backup" style="width: 320px;margin-left: 25px;"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

<?php
        }
            function add_google_drive_export_form_items_schedule()
        {
        ?>
            <div class="wt-migrator-cloud-storage-info child-form-item child-wt_mgdb_export_option wt_mgdb_export_option_googledrive_schedule time_class" style="display:none">
                <?php
                $cron_settings = get_option('wt_mgdp_cron_settings', null);
                $gdrive_settings = isset($cron_settings['data']) && !empty($cron_settings['data']) ? unserialize($cron_settings['data']):'';
                $file_name = isset($gdrive_settings['google_drive_file_name']) && !empty($gdrive_settings['google_drive_file_name']) ? $gdrive_settings['google_drive_file_name']:'';
                $url = admin_url('admin.php?page=wp-migration-duplicator-settings#wt-googledrive');
                ?>
                <span class="wt-migrator-authentication-error"><?php echo sprintf(wp_kses(__('Your account with Google drive is not authenticated please click <a href="%s">here</a> to authenticate', 'wp-migration-duplicator'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url)); ?></span>
                <table class="wf-form-table" style="max-width:650px;">
                    <tbody>
                        <tr>
                            <th style="font-weight: 400">
                                <?php _e('Export Filename', 'wp-migration-duplicator') ?>
                                <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Allows to add a custom name to the backup file, leave it blank if you want the plugin to define the name for your backup file.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                            </th>
                            <td>
                                <input type="text" class="wt-migrator-input wt-migrator-file"  name="wt_mgdb_google_drive_file_name_schedule" disabled placeholder="e.g. critical-backup" style="width: 320px;margin-left: 25px;" value= <?php echo esc_attr($file_name); ?> >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

<?php
        }
        /**
         * helper function for checking is FTP is enabed.
         * @since 1.1.8
         */
        function is_enabled()
        {
            $is_enabled =  $this->status;
            return filter_var($is_enabled, FILTER_VALIDATE_BOOLEAN);
        }
        /**
         *  Get the list of files or folders or both from given folder or root
         *  @param string $search complete or partial name of file or folder to search
         *  @param string $parentId parent folder id or root from which the list of files or folders or both will be generated
         *  @param string $type='all' file or folder
         *  @return array list of files or folders or both from given parent directory
         */
        public function listFilesFolders($search, $parentId, $type = 'all')
        {
            $query = '';
            // Checking if search is empty the use 'contains' condition if search is empty (to get all files or folders).
            // Otherwise use '='  condition
            $condition = $search != '' ? '=' : 'contains';

            // Search all files and folders otherwise search in root or  any folder
            $query .= $parentId != 'all' ? "'" . $parentId . "' in parents" : "";

            // Check if want to search files or folders or both
            switch ($type) {
                case "files":
                    $query .= $query != '' ? ' and ' : '';
                    $query .= "mimeType != 'application/vnd.google-apps.folder' 
                                and name " . $condition . " '" . $search . "'";
                    break;

                case "folders":
                    $query .= $query != '' ? ' and ' : '';
                    $query .= "mimeType = 'application/vnd.google-apps.folder' and name contains '" . $search . "'";
                    break;
                default:
                    $query .= "";
                    break;
            }

            // Make sure that not list trashed files
            $query .= $query != '' ? ' and trashed = false' : 'trashed = false';
            $optParams = array('q' => $query, 'pageSize' => 1000);
            // Returns the list of files and folders as object
            $result = array();
            $client = $this->client;
           
            if ($client) {
                $service = new Google_Service_Drive($client);
                $results = $service->files->listFiles($optParams);

                // Return false if nothing is found
                if (count($results->getFiles()) == 0) {
                    return array();
                }

                // Converting array to object

                foreach ($results->getFiles() as $file) {
                    $result[$file->getId()] = $file->getName();
                }
            }
            return $result;
        }
        /**
         *  Create folder at google drive
         *  @param string $parentId parent folder id or root where folder will be created
         *  @param string $folderName folder name to create
         *  @return string id of created folder
         */
        public function createFolder($parentId, $folderName)
        {
            // Setting File Matadata
            $fileMetadata = new Google_Service_Drive_DriveFile(array(
                'name' => $folderName,
                'parents' => array($parentId),
                'mimeType' => 'application/vnd.google-apps.folder'
            ));
            $client = $this->client;
            if ($client) {
                $service = new Google_Service_Drive($client);
                // Creating Folder with given Matadata and asking for ID field as result
                $file = $service->files->create($fileMetadata, array('fields' => 'id'));
                return $file->id;
            }
            return false;
        }
        /**
        * Get existing backups from Google drive
        *
        * @since  1.1.8
        * @throws Exception Error message.
        * @return array
        */
        public function get_existing_backups() {
            $cloud_storage_name = apply_filters('wt_migrator_cloud_storage_location', WT_MGDP_CLOUD_STORAGE_LOCATION);
           
            $parent_folder = $this->listFilesFolders($cloud_storage_name, 'root', 'folders');
            $parent_id = count($parent_folder) ? array_keys($parent_folder)[0] : null;
            if( is_null( $parent_id )) {
                return false;
            }
            $this->googledrive_location = $parent_id;
            $listing = $this->listFilesFolders('', $this->googledrive_location, 'files');
            $listing = ( isset( $listing ) && is_array( $listing ) ) ? $listing : array();
            $backup_files = array();
            foreach( $listing as $key => $file ) {
                $backup_files[] = array(
                    'name' => $file,
                    'file' => $key
                );
            }
            return $backup_files;
        }


    }

    $googledrive = new Wp_Migration_Google_Drive();
}
