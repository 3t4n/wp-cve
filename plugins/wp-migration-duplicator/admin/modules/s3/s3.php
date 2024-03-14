<?php

/**
 * Google drive import/export section of the plugin
 *
 * @link       
 * @since 1.0.0     
 *
 * @package  Wp_Migration_Duplicator 
 */


require_once __DIR__ . '/src/autoload.php';

use Akeeba\Engine\Postproc\Connector\S3v4\Configuration;
use Akeeba\Engine\Postproc\Connector\S3v4\Input;
use Akeeba\Engine\Postproc\Connector\S3v4\Connector;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Wp_Migration_S3')) {




    class Wp_Migration_S3
    {

        // The next endpoint only exists with APIv1
        public $module_base             =   's3';
        public static $module_id_static =   '';
        protected  $client_secret;
        public $module_id;


        public static $s3_status;
        public static $s3_accesskey;
        public static $s3_secretkey;
        public static $s3_location;

        public $client;

        protected $status;
        protected $accesskey;
        protected $secretkey;
        protected $ocation;
        protected $authenticated;
        protected $connector;
        
        public function __construct()
        {

            $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();
            $this->module_id = Wp_Migration_Duplicator::get_module_id($this->module_base);
            self::$module_id_static = $this->module_id;
            self::$s3_status = $this->module_base . '_' . 'status';
            self::$s3_accesskey = $this->module_base . '_' . 'accesskey';
            self::$s3_secretkey = $this->module_base . '_' . 'secretkey';
            self::$s3_location = $this->module_base . '_' . 'location';

            $this->status = $options[self::$s3_status];
            $this->accesskey = $options[self::$s3_accesskey];
            $this->secretkey = $options[self::$s3_secretkey];
            $this->location = $options[self::$s3_location];

            $this->connector = $this->is_authenticated();
            $this->authenticated = ( is_null( $this->connector ) || $this->connector === false ) ? false : true;
           
            add_filter('wt_mgdb_export_options', array($this, 'add_s3bucket_export'), 11, 1);
            add_filter('wtmgdp_export_output', array($this, 'check_s3bucket_option_used'), 10, 1);

            add_filter('wt_mgdp_general_settings_tabhead', array(__CLASS__, 'settings_tabhead'));
            add_action('wt_mgdp_plugin_out_storage_settings_form', array($this, 'out_settings_form'));


            add_filter('wt_mgdb_import_options', array($this, 'import_options'), 10, 1);
            add_action('mgdp_after_import_form', array($this, 's3bucket_form_on_import'), 10, 0);
            add_filter('wt_migrator_get_import_attachment_url', array($this, 'get_attachment_url_from_s3bucket'), 10, 2);

            add_action('wt_migrator_after_export_page_content', array($this, 'add_s3bucket_export_form_items'), 10, 0);
            add_action('wt_migrator_after_export_page_content_schedule', array($this, 'add_s3bucket_export_form_items_schedule'), 10, 0);

            add_action('wp_ajax_wp_mgdp_authenticate_s3bucket', array($this, 'authenticate_s3bucket'), 10, 0);
            add_action('wp_ajax_wp_mgdp_disconnect_s3bucket', array($this, 'disconnect_s3bucket'), 10, 0);
            add_action('wp_ajax_wp_mgdp_check_s3bucket_authentication', array($this, 'wt_s3bucket_ajax_authentication'));
            add_filter('wt_migrator_s3bucket_is_authenticated', array($this, 'is_authenticated'));
            add_filter("wt_migrator_s3bucket_load_backups", array($this, "get_existing_backups"));
        }
        /**
         * Add Google drive tab head
         * @since 1.0.0
         */
        public static function settings_tabhead($tab_items)
        {
            $tab_items['wt-s3bucket'] = __('Amazon S3', 'wp-migration-duplicator');
            return $tab_items;
        }
        /** 
         * Module settings form
         * @since 1.0.0
         */

        public function out_settings_form($args)
        {
            wp_enqueue_script($this->module_id, plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), WP_MIGRATION_DUPLICATOR_VERSION);
            $params = array(
                'nonce' => wp_create_nonce($this->module_id),
                'ajax_url'  => admin_url('admin-ajax.php'),
                'error_messages' => array(
                    'empty_access_token'       => __('Please enter the access token', 'wp-migration-duplicator'),
                    'auth_error' => __('Authentication failed', 'wp-migration-duplicator')
                )

            );
            wp_localize_script($this->module_id, 'wp_migration_duplicator_s3bucket', $params);


            $status = $this->status;
            $accesskey = $this->accesskey;
            $secretkey = $this->secretkey;
            $location = $this->location;
            $view_file  = plugin_dir_path(__FILE__) . 'views/settings.php';
            $params = array(
                'status'    => $status,
                'accesskey' => $accesskey,
                'secretkey' => $secretkey,
                'location'  => $location,
                'authenticated' => $this->authenticated
            );
            Wp_Migration_Duplicator_Admin::envelope_settings_tabcontent('wt-s3bucket', $view_file, '', $params, 0);
        }

        /**
         * Add new export option using Google Drive
         * @since 1.0.0
         */
        function add_s3bucket_export($export_option)
        {
            if ($this->is_enabled()) {
                $export_option['s3bucket'] = __('Amazon S3', 'wp-migration-duplicator');
            }
            return $export_option;
        }



        /**
         * Google Drive export action
         * @since 1.0.0
         */
        function check_s3bucket_option_used($out)
        {
            $export_option_post = (isset($_POST['export_option'])) ? $_POST['export_option'] : $out['export_option'];
            $export_option_post = Wp_Migration_Duplicator_Security_Helper::sanitize_item($export_option_post);
            $s3bucketname = $this->location;
            $cloud_storage_name = apply_filters('wt_migrator_cloud_storage_location', WT_MGDP_CLOUD_STORAGE_LOCATION);
            Webtoffe_logger::write_log( 'Export',$export_option_post .' file upload started .. ' );
            if ('s3bucket' == $export_option_post) {
		$out['export_option'] = 's3bucket';
                if( $this->connector ) {
                    $connector = $this->connector;
                } else 
                {
                    $connector = $this->is_authenticated();
                }
                if ( $connector ) {
                    try {
                        $storage_location_array = explode('/', trim($s3bucketname,'/'));
                        $storage_location = '';
                        if(isset($storage_location_array[0]) && !empty($storage_location_array[0])){
                           $s3bucketname = $storage_location_array[0];
                           unset($storage_location_array[0]);
                           $storage_location = implode('/', $storage_location_array);
                        }
                        $file_name = (isset($_POST['s3bucket_file_name']) && '' != $_POST['s3bucket_file_name']) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['s3bucket_file_name']) : date('Y-m-d-h-i-sa', time());
                      
                        if($storage_location){
                              $file_name =  $storage_location.'/'.$cloud_storage_name . '/' . $file_name . '.zip';
                        }else{
                             $file_name = $cloud_storage_name . '/' . $file_name. '.zip';
                        }
                       
                        $sourceFile = WP_CONTENT_DIR . Wp_Migration_Duplicator::$backup_dir_name . "/" . $out['backup_file_name'];
                        $input = Input::createFromFile($sourceFile);
                        try{
                           $uploadId = $connector->startMultipart($input, $s3bucketname, $file_name);
                        } catch (Exception $exceptions) {
                            
                             Webtoffe_logger::write_log( 'Export',$exceptions->getMessage() );
                            $out['status']  = false;
                            $out['msg']     =  __('Failed to upload file.<br/><br/><b>Possible Reasons</b><br/><b>1.</b> File path may be invalid.<br/><b>2.</b> Maybe File / Folder Permission missing for specified file or folder in path.<br/><b>3.</b> Write permission may be missing.', 'wp-migration-duplicator');
                            Webtoffe_logger::write_log( 'Export','---[ Export Ended at '.date('Y-m-d H:i:s').' ] --- ' );
                            delete_option('wp_mgdp_log_id');
                             return $out;
                        }

                        $eTags = array();
                        $eTag = null;
                        $partNumber = 0;

                        do {
                            // IMPORTANT: You MUST create the input afresh before each uploadMultipart call
                            $input = Input::createFromFile($sourceFile);
                            $input->setUploadID($uploadId);
                            $input->setPartNumber(++$partNumber);

                            $eTag = $connector->uploadMultipart($input, $s3bucketname, $file_name);

                            if (!is_null($eTag)) {
                                $eTags[] = $eTag;
                            }
                        } while (!is_null($eTag));

                        // IMPORTANT: You MUST create the input afresh before finalising the multipart upload
                        $input = Input::createFromFile($sourceFile);
                        $input->setUploadID($uploadId);
                        $input->setEtags($eTags);

                        $connector->finalizeMultipart($input, $s3bucketname, $file_name);
                        $out['status']  = true;
                        unlink($out['backup_file']);
						//unset($out['backup_file']);
                        $out['msg']     =  __('Successfully uploaded !!', 'wp-migration-duplicator');
                        Webtoffe_logger::write_log( 'Export','Zip File Successfully uploaded !! ' );
                    } catch (Exception $exception) {
                        echo sprintf("%s error: %s", 's3bucket', $exception->getMessage()) . ' (' . $exception->getCode() . ')';
                        Webtoffe_logger::error($exception->getMessage());
                        Webtoffe_logger::write_log( 'Export',$exception->getMessage() );
                        $out['status']  = false;
                        $out['msg']     =  __('Please check the authentication', 'wp-migration-duplicator');
                    }
                } else {
                    $out['status']  = false;
                    $out['msg']     =  __('Please Authenticate with your access token', 'wp-migration-duplicator');
                    Webtoffe_logger::write_log( 'Export','Please authenticate your google drive account.' );
                }
                Webtoffe_logger::write_log( 'Export','---[ Export Ended at '.date('Y-m-d H:i:s').' ] --- ' );
                delete_option('wp_mgdp_log_id');
            }
            return $out;
        }

        /**
         * Ajax action for authenticating s3bucket
         * @since 1.0.0
         */
        function authenticate_s3bucket()
        {

            if (!Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_PLUGIN_FILENAME, $this->module_id)) {
                wp_die(__('You do not have sufficient permission to perform this operation', 'wp-migration-duplicator'));
            }

            $aws_accesskey = Wp_Migration_Duplicator_Security_Helper::sanitize_item((isset($_POST['access_key']) ? $_POST['access_key'] : ''));
            $aws_secretkey = Wp_Migration_Duplicator_Security_Helper::sanitize_item((isset($_POST['secret_key']) ? $_POST['secret_key'] : ''));
            $aws_s3_location = Wp_Migration_Duplicator_Security_Helper::sanitize_item((isset($_POST['s3_location']) ? $_POST['s3_location'] : ''));
            
            if( empty($aws_accesskey) || empty($aws_secretkey) || empty($aws_s3_location)) {
                wp_send_json_error(__('Check if all the fields have correct information and retry.', 'wp-migration-duplicator'));
            }
            try {

                $configuration = new Akeeba\Engine\Postproc\Connector\S3v4\Configuration(
                    $aws_accesskey,
                    $aws_secretkey
                );
                $connector = new Akeeba\Engine\Postproc\Connector\S3v4\Connector($configuration);
                $listing = $connector->listBuckets(true);
            } catch (Exception $exception) {

                Webtoffe_logger::error($exception->getMessage());
                $msg = $exception->getMessage();
                if(@strstr($msg,'SSL certificate problem')){
                  $msg = 'SSL certificate problem: unable to get local issuer certificate';  
                }
                wp_send_json_error(__($msg, 'wp-migration-duplicator'));
            }
            $this->status = true;
            $this->accesskey = $aws_accesskey;
            $this->secretkey = $aws_secretkey;
            $this->location = $aws_s3_location;

            $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();

            $options[self::$s3_status] = true;
            $options[self::$s3_accesskey] = $this->accesskey;
            $options[self::$s3_secretkey] = $this->secretkey;
            $options[self::$s3_location] = $this->location;

            Wp_Migration_Duplicator::update_webtoffee_migrator_option( $options );
            wp_send_json_success(__('Authentication success!', 'wp-migration-duplicator'));
        }

        /**
         * disconnect s3bucket
         * @since 1.0.0
         */
        function disconnect_s3bucket()
        {
            if (!Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_PLUGIN_FILENAME, $this->module_id)) {
                wp_die(__('You do not have sufficient permission to perform this operation', 'wp-migration-duplicator'));
            }
            $this->accesskey = '';
            $this->secretkey = '';
            $this->location = '';
            $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();
            $options[self::$s3_accesskey] = $this->accesskey;
            $options[self::$s3_secretkey] = $this->secretkey;
            $options[self::$s3_location] = $this->location;
            Wp_Migration_Duplicator::update_webtoffee_migrator_option( $options );
            wp_send_json_success(__('Disconnected!', 'wp-migration-duplicator'));
        }

        /**
         * Helper function for checking is authenticated by any profile
         * @since 1.0.0
         */
        function is_authenticated()
        {   
            $accesskey = $this->accesskey;
            $secretkey = $this->secretkey;
            if (empty($accesskey) || empty($secretkey)) {
                return false;
            }
            try {
                $configuration = new Configuration(
                    $accesskey,
                    $secretkey
                );
                $connector = new Connector($configuration);
                $listing = $connector->listBuckets(true);
            } catch (Exception $e) {
                Webtoffe_logger::error($e->getMessage());
                return false;
            }
            return $connector;
        }
        public function wt_s3bucket_ajax_authentication()
        {
            if (!Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_PLUGIN_FILENAME, $this->module_id)) {
                wp_die(__('You do not have sufficient permission to perform this operation', 'wp-migration-duplicator'));
            }
            if( $this->connector ) {
                $authenticated = $this->connector;
            } else 
            {
                $authenticated = $this->is_authenticated();
            }
            if ($authenticated) {
                wp_send_json_success(__('Authentication success!', 'wp-migration-duplicator'));
            }
            wp_send_json_error(__('Authentication failed', 'wp-migration-duplicator'));
        }
        /**
         * import options
         * @since 1.0.0
         */
        function import_options($import_options)
        {
            if ($this->is_enabled()) {
                $import_options['s3bucket'] = __('Amazon S3', 'wp-migration-duplicator');
            }
            return $import_options;
        }

        /**
         * Form for importing from google drive
         * @since 1.0.0
         */
        function s3bucket_form_on_import()
        {
?>
            <div class="wt-migrator-cloud-storage-info child-form-item child-wt_mgdb_import_option wt_mgdb_import_option_s3bucket" style="display:none">
                <?php
                $url = admin_url('admin.php?page=wp-migration-duplicator-settings#wt-s3bucket');
                ?>
                <span class="wt-migrator-authentication-error"><?php echo sprintf(wp_kses(__('Your account with Amazon S3 is not authenticated please click <a href="%s">here</a> to authenticate', 'wp-migration-duplicator'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url)); ?></span>
                <table class="wf-form-table wt_mgdp_import_options " style="max-width:650px;">
                    <tbody>
                        <tr>
                            <th style="font-weight: 400"><?php _e('Enable Amazon S3', 'wp-migration-duplicator') ?></th>
                            <td>
                            <span class="wt-cli-cloud-status wt-cli-cloud-status-enabled">
                                <span class="dashicons dashicons-saved"></span><?php _e('(Enabled)','wp-migration-duplicator');?>
                            </span>
                                <a  href="<?php echo esc_url(admin_url('admin.php?page=wp-migration-duplicator-settings#wt-s3bucket'))?>"><?php echo esc_html__('Settings','wp-migration-duplicator-pro')?></a>
                            </td>
                        </tr>
                        <tr>
                            <th style="font-weight: 400">
                                <?php _e('Amazon S3 File Name', 'wp-migration-duplicator') ?>
                                <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The exact file name of the backup file to import', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                            </th>
                            <td>
                                <select class="wt-migrator-select2-single wt-migrator-cloud-import-file wt-migrator-input wt-migrator-file"  data-hidden-name="wt_mgdb_s3bucket_file" id='wt_mgdb_s3bucket_file' style="width:100%">
                                    <option value="-1"><?php echo esc_html__('Please select your backup file','wp-migration-duplicator');?></option>
                                </select>
                                <input type="hidden" name="wt_mgdb_s3bucket_file">
                            </td>
                        </tr>   
                    </tbody>
                </table>
            </div>
        <?php
        }

        /**
         * Export product from s3bucket
         * @since 1.0.0
         */

        function get_attachment_url_from_s3bucket($import_data, $import_method)
        {
            $s3bucketname = $this->location;
            $cloud_storage_name = apply_filters('wt_migrator_cloud_storage_location', WT_MGDP_CLOUD_STORAGE_LOCATION);
            if ('s3bucket' != $import_method) {
                return $import_data;
            }
            $error_message = __('The specified file could not be found on Amazon S3 Bucket','wp-migration-duplicator');
            $import_data['message'] = $error_message;
            
            if( $this->connector ) {
                $connector = $this->connector;
            } else 
            {
                $connector = $this->is_authenticated();
            }
            if ( $connector ) {
                $file_name = (isset($_POST['wt_mgdb_s3bucket_file'])) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['wt_mgdb_s3bucket_file']) : '';
                if ('' == $file_name) {
                    $import_data['message'] = __('Please specify a file to import','wp-migration-duplicator');
                    return $import_data;
                }

                $local_file_url = content_url() . Wp_Migration_Duplicator::$backup_dir_name . '/temp-import-file.zip';
                $local_file = Wp_Migration_Duplicator::$backup_dir . '/temp-import-file.zip';

                try {
                    $s3bucketname = $this->location;
                    $storage_location_array = explode('/', trim($s3bucketname,'/'));
                    $storage_location = '';
                    if(isset($storage_location_array[0]) && !empty($storage_location_array[0])){
                       $s3bucketname = $storage_location_array[0];
                       unset($storage_location_array[0]);
                       $storage_location = implode('/', $storage_location_array);
                    }
                    $cloud_storage_name = $storage_location .'/'.$cloud_storage_name;
                    // Double checking to ensure file is not deleted during import start process
                    $listing = $connector->getBucket($s3bucketname, $cloud_storage_name . '/');
                    $is_file_exist = array_key_exists($file_name, $listing);
                    if ($is_file_exist !== true) {
                        return $import_data;
                    }
                    ob_end_clean();
                    $connector->getObject($s3bucketname, $file_name, $local_file);
                } catch (Exception $e) {
                    Webtoffe_logger::error($e->getMessage());
                    echo sprintf("%s error: %s", 's3bucket', $e->getMessage()) . ' (' . $e->getCode() . ')';
                    return $import_data;
                }
                $import_data['status'] = true;
                $import_data['message'] = __('File has fetched from the Amazon S3 Bucket, now importing the file....','wp-migration-duplicator');
                $import_data['file'] = $local_file_url;
            }
            return $import_data;
        }

        /**
         * Add form feields for Import option using Google drive
         * @since 1.0.0
         */
        function add_s3bucket_export_form_items()
        {
        ?>
            <div class="wt-migrator-cloud-storage-info child-form-item child-wt_mgdb_export_option wt_mgdb_export_option_s3bucket time_class" style="display:none">
                <?php
                $url = admin_url('admin.php?page=wp-migration-duplicator-settings#wt-s3bucket');
                ?>
                <span class="wt-migrator-authentication-error"><?php echo sprintf(wp_kses(__('Your account with Amazon S3 is not authenticated please click <a href="%s">here</a> to authenticate', 'wp-migration-duplicator'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url)); ?></span>
                <table class="wf-form-table" style="max-width:650px;">
                    <tbody>
                        <tr>
                            <th style="font-weight: 400"><?php _e('Enable Amazon S3', 'wp-migration-duplicator') ?></th>
                            <td>
                            <span class="wt-cli-cloud-status wt-cli-cloud-status-enabled" style="margin-left: 20px;">
                                <span class="dashicons dashicons-saved"></span><?php _e('(Enabled)','wp-migration-duplicator');?>
                            </span>
                                <a  href="<?php echo esc_url(admin_url('admin.php?page=wp-migration-duplicator-settings#wt-s3bucket'))?>"><?php echo esc_html__('Settings','wp-migration-duplicator-pro')?></a>
                            </td>
                        </tr>
                        <tr>
                            <th style="font-weight: 400">
                                <?php _e('Export Filename', 'wp-migration-duplicator') ?>
                                <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Allows to add a custom name to the backup file, leave it blank if you want the plugin to define the name for your backup file.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                            </th>
                            <td>
                                <input type="text" class="wt-migrator-input wt-migrator-file" name="wt_mgdb_s3bucket_file_name" disabled placeholder="e.g. critical-backup" style="width: 320px;margin-left: 25px;"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

<?php
        }
        
        
           function add_s3bucket_export_form_items_schedule()
        {
        ?>
            <div class="wt-migrator-cloud-storage-info child-form-item child-wt_mgdb_export_option wt_mgdb_export_option_s3bucket_schedule time_class" style="display:none">
                <?php
                $cron_settings = get_option('wt_mgdp_cron_settings', null);
                $s3_settings = isset($cron_settings['data']) && !empty($cron_settings['data']) ? unserialize($cron_settings['data']):'';
                $file_name = isset($s3_settings['s3bucket_file_name']) && !empty($s3_settings['s3bucket_file_name']) ? $s3_settings['s3bucket_file_name']:'';
                $url = admin_url('admin.php?page=wp-migration-duplicator-settings#wt-s3bucket');
                ?>
                <span class="wt-migrator-authentication-error"><?php echo sprintf(wp_kses(__('Your account with Amazon S3 is not authenticated please click <a href="%s">here</a> to authenticate', 'wp-migration-duplicator'), array('a' => array('href' => array(), 'target' => array()))), esc_url($url)); ?></span>
                <table class="wf-form-table" style="max-width:650px;">
                    <tbody>
                        <tr>
                            <th><?php _e('Enable Amazon S3', 'wp-migration-duplicator') ?></th>
                            <td>
                            <span class="wt-cli-cloud-status wt-cli-cloud-status-enabled" style="margin-left: 20px;">
                                <span class="dashicons dashicons-saved"></span><?php _e('(Enabled)','wp-migration-duplicator');?>
                            </span>
                                <a  href="<?php echo esc_url(admin_url('admin.php?page=wp-migration-duplicator-settings#wt-s3bucket'))?>"><?php echo esc_html__('Settings','wp-migration-duplicator-pro')?></a>
                            </td>
                        </tr>
                        <tr>
                            <th style="font-weight: 400">
                                <?php _e('Export Filename', 'wp-migration-duplicator') ?>
                                <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Allows to add a custom name to the backup file, leave it blank if you want the plugin to define the name for your backup file.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span>
                            </th>
                            <td>
                                <input type="text" class="wt-migrator-input wt-migrator-file" name="wt_mgdb_s3bucket_file_name_schedule" value= <?php echo esc_attr($file_name); ?> disabled placeholder="e.g. critical-backup" style="width: 320px;margin-left: 25px;"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

<?php
        }
        
        
        public function get_existing_backups() {
            if (!Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_PLUGIN_FILENAME)) {
                wp_die(__('You do not have sufficient permission to perform this operation', 'wp-migration-duplicator'));
            }
            $cloud_storage_name = apply_filters('wt_migrator_cloud_storage_location', WT_MGDP_CLOUD_STORAGE_LOCATION);
            $s3bucketname = $this->location;
            $storage_location_array = explode('/', trim($s3bucketname,'/'));
            $storage_location = '';
            if(isset($storage_location_array[0]) && !empty($storage_location_array[0])){
               $s3bucketname = $storage_location_array[0];
               unset($storage_location_array[0]);
               $storage_location = implode('/', $storage_location_array);
            }
            $cloud_storage_name = $storage_location .'/'.$cloud_storage_name;
            if( $this->connector ) {
                $connector = $this->connector;
            } else 
            {
                $connector = $this->is_authenticated();
            }
            if ( $connector) {
                try {
                    
                    $listing = $connector->getBucket($s3bucketname, $cloud_storage_name . '/');
                    $listing = ( isset( $listing ) && is_array( $listing ) ) ? $listing : array();
                    $backup_files = array();
                    foreach( $listing as $key => $file ) {
                        $backup_files[] = array(
                            'name' => str_replace(array($cloud_storage_name.'/','.zip'),array('',''),$key),
                            'file' => $key
                        );
                    }
                    return $backup_files;

                } catch (Exception $e) {
                    Webtoffe_logger::error($e->getMessage());
                    echo sprintf("%s error: %s", 's3bucket', $e->getMessage()) . ' (' . $e->getCode() . ')';
                    return false;
                }
            }
        }
        /**
         * helper function for checking is FTP is enabed.
         * @since 1.0.0
         */
        function is_enabled()
        {
            $is_enabled =  $this->status;
            return filter_var($is_enabled, FILTER_VALIDATE_BOOLEAN);
        }
    }
    $s3bucket = new Wp_Migration_S3();
}
