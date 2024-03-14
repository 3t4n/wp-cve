<?php

/**
 * Ftp profile section of the plugin
 *
 * @link       
 * @since 1.1.8     
 *
 * @package  Wp_Migration_Duplicator 
 */
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Wp_Migration_Profile')) {


    class Wp_Migration_Profile
    {

        private $to_export      =   '';
        private $to_export_id   =   '';
        public static $module_id       =   '';
        public static $module_id_static =   '';
        public $module_base     =   'ftp';
        protected $status;
        
        public function __construct()
        {
            self::$module_id        =   Wp_Migration_Duplicator::get_module_id($this->module_base);
            self::$module_id_static =   self::$module_id;
            $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();
            
            $this->status = $options[$this->module_base.'_'.'status'];

            add_filter('wt_mgdp_general_settings_tabhead', array(__CLASS__, 'settings_tabhead'));
            add_filter('wt_mgdb_export_options', array($this, 'add_ftp_export'), 10, 1);
            add_filter('wt_mgdb_import_options', array($this, 'add_ftp_import'), 10, 1);
            add_action('wt_mgdp_plugin_out_storage_settings_form', array($this, 'out_settings_form'));
            add_action('wp_ajax_wp_mgdp_ftp_ajax', array($this, 'ajax_main'), 11);
             add_action('wp_ajax_wp_mgdp_test_ftp_ajax', array($this, 'wt_mgdp_test_ftp_ajax'), 11);            
            add_filter('wt_mgdp_remote_adapters', array($this, 'remote_adapter'), 11, 3);
            add_filter('wt_mgdp_exporter_remote_adapter_names', array($this, 'remote_adapter_name'));
            add_filter('wt_mgdp_exporter_file_into_fields_row_id', array($this, 'exporter_file_into_fields_row_id'));
            add_action('wt_mgdp_exporter_file_into_js_fn', array($this, 'exporter_file_into_js_fn'));
            add_action('wt_migrator_after_export_page_content', array($this, 'add_ftp_profile_selection_form'), 10, 0);
            add_action('wt_migrator_after_export_page_content_schedule', array($this, 'add_ftp_profile_selection_form_schedule'), 10, 0);
            add_filter('wtmgdp_export_output', array($this, 'check_ftp_export_option_used'), 10, 1);
            add_action('mgdp_after_import_form', array($this, 'ftp_form_on_import'), 10, 0);
            add_filter('wt_migrator_get_import_attachment_url', array($this, 'get_attachment_url_from_ftp'), 10, 2);
            add_action('wp_ajax_wp_mgdp_load_ftp_backups',array($this,'get_existing_backups'));
        }

        /**
         * Tab head for module settings page
         * @since 1.1.8
         */
        public static function settings_tabhead($arr)
        {
            $arr['wt-ftp'] = __('FTP/SFTP settings', 'wp-migration-duplicator');
            return $arr;
        }

        /**
         * Add FTP profile selection for export
         * @since 1.1.8
         */
        function add_ftp_export($export_option)
        {
            if ($this->is_enabled()) {
                $export_option['ftp'] = __('FTP/SFTP', 'wp-migration-duplicator');
            }
            return $export_option;
        }

        /**
         * Add FTP profile selection for export
         * @since 1.1.8
         */
        function add_ftp_import($import_option)
        {
            if ($this->is_enabled()) {
                $import_option['ftp'] = __('FTP/SFTP', 'wp-migration-duplicator');
            }
            return $import_option;
        }
        /** 
         * Module settings form
         * @since 1.1.8
         */
        public function out_settings_form($args)
        {
            wp_enqueue_script(self::$module_id, plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), WP_MIGRATION_DUPLICATOR_VERSION);
            wp_enqueue_script('admin_basejs', WT_MGDP_PLUGIN_URL . 'admin/js/wp-migration-duplicator-admin.js', array('jquery'), WP_MIGRATION_DUPLICATOR_VERSION);
            $params = array(
                'nonces'    => array(
                    'main'  =>  wp_create_nonce(self::$module_id),
                ),
                'ajax_url'  => admin_url('admin-ajax.php'),
                'msgs'      =>  array(
                    'add_new'       =>  __('Add new', 'wp-migration-duplicator'),
                    'add_new_hd'    =>  __('Add new FTP profile', 'wp-migration-duplicator'),
                    'edit'          =>  __('Edit', 'wp-migration-duplicator'),
                    'edit_hd'       =>  __('Edit FTP profile', 'wp-migration-duplicator'),
                    'mandatory'     =>  __('All fields are mandatory', 'wp-migration-duplicator'),
                    'sure'          =>  __('Are you sure? All import/export profiles associated with this FTP profile will not work. You can\'t undu this action.', 'wp-migration-duplicator'),
                    'wait'          =>  __('Please wait...', 'wp-migration-duplicator'),
                    'delete'        =>  __('Delete', 'wp-migration-duplicator'),
                    'success'       =>  __('Success', 'wp-migration-duplicator'),
                    'some_mandatory'=>  __('Please fill mandatory fields'),
                    'error' => sprintf(__('An unknown error has occurred! Refer to our %stroubleshooting guide%s for assistance.'), '<a href="'.WT_MGDP_PLUGIN_DEBUG_BASIC_TROUBLESHOOT.'" target="_blank">', '</a>'),

                )
            );
            wp_localize_script(self::$module_id, self::$module_id, $params);
            wp_localize_script('admin_basejs', 'admin_basejs', $params);

            $view_file  = plugin_dir_path(__FILE__) . 'views/settings.php';
            $params =   array(
                'is_enabled' => $this->is_enabled()
            );
            Wp_Migration_Duplicator_Admin::envelope_settings_tabcontent('wt-ftp', $view_file, '', $params, 0);
        }

        /**
         * Process ftp list for select boxes
         * @since 1.1.8
         */
        public function get_ftp_profile_for_select($action)
        {
            $profiles   =   $this->get_ftp_data();
            $sele_arr   =   array();
            if ($profiles) {
                $sele_arr[0] = array('value' => __('Select one', 'wp-migration-duplicator'), 'path' => "");
                foreach ($profiles as $profile) {
                    $path   =   ($action == 'export' ? $profile['export_path'] : $profile['import_path']);
                    $sele_arr[$profile['id']] = array('value' => $profile['name'], 'path' => $path);
                }
            }
            return $sele_arr;
        }

        /**
         * Get FTP profile list from DB
         * @return array list of FTP profiles
         * @since 1.1.8
         */
        public static function get_ftp_data()
        {
            global $wpdb;
            $tb     =   $wpdb->prefix . Wp_Migration_Duplicator::$ftp_tb;
            $val    =   $wpdb->get_results("SELECT * FROM $tb ORDER BY id DESC", ARRAY_A);
            if ($val) {
                return $val;
            } else {
                return array();
            }
        }

        /**
         * Main ajax hook for ajax actions including steps, export, save etc. 
         * @since 1.1.8
         */
        public function ajax_main()
        {
            $action = (isset($_POST['wt_mgdp_update_action']) ? sanitize_text_field($_POST['wt_mgdp_update_action']) : '');
            $out = array(
                'status' => false,
                'msg' => __('Error', 'wp-migration-duplicator'),
                'action' => $action,
            );
           
            if (!Wp_Migration_Duplicator_Security_Helper::check_write_access(WT_MGDP_POST_TYPE, self::$module_id)) {
                echo json_encode($out);
                exit();
            }
            $allowed_actions    =   array('save_ftp', 'delete_ftp', 'ftp_list', 'enable_disabe_ftp');

            $out                =   array('status' => true, 'msg' => '');
            if (in_array($action, $allowed_actions)) {
                if (method_exists($this, $action)) {
                    $out = $this->{$action}($out); //some mothods will not retrive array
                }
            }
            echo json_encode($out);
            exit();
        }
        /**
         * List all the FTP profiles Added 
         * @since 1.1.8
         */
        private function ftp_list($out)
        {   
            $ftp_list   =   $this->get_ftp_data();
            include plugin_dir_path(__FILE__) . 'views/_ftp-list.php';
            exit(); //not return anything, prints html
        }

        
          
        public function wt_mgdp_test_ftp_ajax() {

                if(isset($_POST['is_sftp']) && $_POST['is_sftp'] == 1 && $_POST['ftpport']!=21 ) /* sftp */
		{
			include_once "classes/class-sftp.php";
			$sftp=new Wt_mgdp_Sftp();
                        $out = array();
			$out=$sftp->test_sftp($_POST, $out);
			return $out;
		}else{
            
                        $ftp_conn = @ftp_connect($_POST['host'], $_POST['ftpport']);
                        if ($ftp_conn == false) {
                            $out= __("Could not connect to Host. Server host / IP or Port may be wrong.", 'wp-migration-duplicator');
                            return wp_send_json_error($out);
                        }
                        if (@ftp_login($ftp_conn, $_POST['user_name'], $_POST['password'])) {
                            $out = __("Successfully logged in.", 'wp-migration-duplicator');

                            return wp_send_json_success($out);
                        } else {
                            $out= __("Connected to host but could not login. Server UserID or Password may be wrong or Try with / without FTPS ..", 'wp-migration-duplicator');

                            return wp_send_json_error($out);
                        }
                }
        }


        /**
         * Ajax function to save FTP details.
         * @since 1.1.8
         */
         private function save_ftp($out)
        {
            $ftp_form_fields = array('wt_mgdp_profilename', 'wt_mgdp_hostname', 'wt_mgdp_ftpuser', 'wt_mgdp_ftppassword', 'wt_mgdp_ftpport','wt_mgdp_useftps','wt_mgdp_passivemode','wt_mgdp_is_sftp', 'wt_mgdp_ftpexport_path', 'wt_mgdp_ftpimport_path');

            foreach ($ftp_form_fields as $ftp_form_field) {
                $val    = (isset($_POST[$ftp_form_field]) ? sanitize_text_field($_POST[$ftp_form_field]) : '');
                if ($val == "") {
                    $out['msg']     = __("All fields are mandatory", 'wp-migration-duplicator');
                    $out['status']  = false;
                    break;
                }
            }
            $id = (isset($_POST['wt_mgdp_ftp_id']) ? intval($_POST['wt_mgdp_ftp_id']) : 0);
            $name = (isset($_POST['wt_mgdp_profilename']) ? sanitize_text_field($_POST['wt_mgdp_profilename']) : '');

            if ($out['status']) //no validation error, ftp edit call, check for duplcate name.
            {
                $ftp_data   =   $this->get_ftp_data_by_name($name);
                if (count($ftp_data) > 1) {
                    $out['msg'] = __("FTP profile with same name already exists.", 'wp-migration-duplicator');
                    $out['status'] = false;
                    return $out;
                } else {
                    if (isset($ftp_data[0]['id']) && $ftp_data[0]['id'] != $id) /* profile with same name exists */ {
                        $out['msg'] = __("FTP profile with same name already exists.", 'wp-migration-duplicator');
                        $out['status'] = false;
                        return $out;
                    }
                }
            }

            if ($out['status']) {
                $db_data = array(
                    'name'          => stripslashes($name),
                    'server'        => sanitize_text_field($_POST['wt_mgdp_hostname']),
                    'user_name'     => sanitize_text_field($_POST['wt_mgdp_ftpuser']),
                    'password'      => sanitize_text_field($_POST['wt_mgdp_ftppassword']),
                    'port'          => sanitize_text_field($_POST['wt_mgdp_ftpport']),            
                    'export_path'   => sanitize_text_field($_POST['wt_mgdp_ftpexport_path']),
                    'import_path'   => sanitize_text_field($_POST['wt_mgdp_ftpimport_path']),
                    'ftps'          =>sanitize_text_field($_POST['wt_mgdp_useftps']),
                    'is_sftp'       =>sanitize_text_field($_POST['wt_mgdp_is_sftp']),
                    'passive_mode'  =>sanitize_text_field($_POST['wt_mgdp_passivemode']),
                );
                $db_data_type   = array('%s', '%s', '%s', '%s', '%d', '%s', '%s', '%d', '%d', '%d');
                if ($id > 0) {
                    if (!$this->update_ftp_data($id, $db_data, $db_data_type)) {
                        $out['msg'] = __("Error", 'wp-migration-duplicator');
                        $out['status'] = false;
                    }
                } else {
                    if (!$this->add_ftp_data($db_data, $db_data_type)) {
                        $out['msg']     = __("Error", 'wp-migration-duplicator');
                        $out['status']  = false;
                    }
                }
            }
            return $out;
        }
        /**
         * Helper function for getting FTP profile details by name
         * @since 1.1.8
         */
        private function get_ftp_data_by_name($name)
        {
            global $wpdb;
            $tb     =   $wpdb->prefix . Wp_Migration_Duplicator::$ftp_tb;
            $qry    =   $wpdb->prepare("SELECT * FROM $tb WHERE name=%s", array($name));
            $val    =   $wpdb->get_results($qry, ARRAY_A);
            if ($val) {
                return $val;
            } else {
                return array();
            }
        }

        /**
         * Helper function for getting FTP profile details by ID
         * @since 1.1.8
         */
        private function get_ftp_data_by_id($id)
        {
            global $wpdb;
            $tb     =   $wpdb->prefix . Wp_Migration_Duplicator::$ftp_tb;
            $qry    =   $wpdb->prepare("SELECT * FROM $tb WHERE id=%d", array($id));
            $val    =   $wpdb->get_row($qry, ARRAY_A);
            if ($val) {
                return $val;
            } else {
                return array();
            }
        }


        /**
         * Insert new FTP profile into DB
         * @since 1.1.8
         */
        private function add_ftp_data($insert_data, $insert_data_type)
        {
            global $wpdb;
            $tb     =   $wpdb->prefix . Wp_Migration_Duplicator::$ftp_tb;
            if ($wpdb->insert($tb, $insert_data, $insert_data_type)) {
                return $wpdb->insert_id;
            }
            return 0;
        }
        /**
         * Update existing FTP profile
         * @since 1.1.8
         */
        private function update_ftp_data($id, $update_data, $update_data_type)
        {
            global $wpdb;
            //updating the data
            $tb             =   $wpdb->prefix . Wp_Migration_Duplicator::$ftp_tb;
            $update_where   = array(
                'id' => $id
            );
            $update_where_type  = array(
                '%d'
            );
            if ($wpdb->update($tb, $update_data, $update_where, $update_data_type, $update_where_type) !== false) {
                return true;
            }
            return false;
        }
        /**
         * Delete FTP profile 
         * @since 1.1.8
         */
        public function delete_ftp($out)
        {
            $id =   (isset($_POST['wp_migration_duplicator_ftp_id']) ? intval($_POST['wp_migration_duplicator_ftp_id']) : 0);
            if ($id > 0) {
                global $wpdb;
                $tb     = $wpdb->prefix . Wp_Migration_Duplicator::$ftp_tb;
                $wpdb->delete($tb, array('id' => $id), array('%d'));
            } else {
                $out['msg'] = __("Error", 'wp-migration-duplicator');
                $out['status'] = false;
            }
            return $out;
        }
        /**
         * All the possible Export Methods
         * @since 1.1.8
         */

        private function get_possible_export_methos()
        {
            $export_methods = array(
                'local' => __('Local', 'wp-migration-duplicator')
            );
            return apply_filters('wt_mgdb_export_options', $export_methods);
        }
        /**
         * FTP profile selection for exporting
         * @since 1.1.8
         */
        public function add_ftp_profile_selection_form()
        {
?>
            <table class="wf-form-table wt_mgdp_export_options wt_mgdp_export_options_ftp time_class" style="max-width:650px;">
                <tbody>
                    <tr>
                        <th style="font-weight: 400"><?php _e('Export File Into', 'wp-migration-duplicator') ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Choose the location where you want to store the backup of your website.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span></th>
                        <td>
                            <?php
                            $export_options  =  $this->get_possible_export_methos(); ?>
                            <div class="wt-migrator-select-container">
                                <?php
                                if (is_array($export_options)) {
                                    echo '<select name="wt_mgdb_export_option" data-option-type="export">';
                                    foreach ($export_options as $value => $export_option) {
                                        echo '<option value="' . esc_attr($value) . '">' . esc_attr($export_option) . '</option>';
                                    }
                                    echo '</select>';
                                }
                                ?>
                                <span class="spinner"></span>
                            </div>
                        </td>
                    </tr>

                    <tr class="child-form-item child-wt_mgdb_export_option wt_mgdb_export_option_ftp">
                        <?php
                        $profiles = $this->get_ftp_data();
                        if (empty($profiles)) {
                            $profile_link = admin_url('admin.php?page=wp-migration-duplicator-settings#wt-ftp');
                        ?>  
                            <th></th>
                            <td colspan="3">
                                <?php _e('You dont seem to have any FTP profiles yet. ', 'wp-migration-duplicator'); ?>
                                <a href="<?php echo esc_url($profile_link); ?>"><?php _e('Create an FTP profile.', 'wp-migration-duplicator') ?></a>
                            </td>
                        <?php } else {
                        ?>

                            <th style="font-weight: 400"> <?php _e('Choose Ftp Profile', 'wp-migration-duplicator'); ?></th>
                            <td>
                                <?php
                                $profiles = $this->get_ftp_profile_for_select('export');
                                if (is_array($profiles) && !empty($profiles)) {
                                    echo '<select name="wt_mgdb_export_ftp_profiles">';
                                    foreach ($profiles as $key => $profile) {
                                        echo '<option value="' . esc_attr($key) . '" data-path="' . esc_attr($profile['path']) . '" >' . esc_attr($profile['value']) . '</option>';
                                    }

                                    echo '</select>';
                                }

                                ?>
                            </td>
                    </tr>
                    <tr class="child-form-item child-wt_mgdb_export_option wt_mgdb_export_option_ftp">
                        <th style="font-weight: 400"> <?php _e('Export Path', 'wp-migration-duplicator');  ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Server path where you want to store the backup files.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span> </th>
                        <td> <input type="text" name="wt_mgdb_export_path" value="" /></td>
                    </tr>
                    <tr class="child-form-item child-wt_mgdb_export_option wt_mgdb_export_option_ftp">
                        <th style="font-weight: 400"> <?php _e('Export File Name', 'wp-migration-duplicator');  ?> <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Add a custom name for the backup file. Leaving the field blank will generate a plugin defined name for your backup file.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span></th>
                        <td> <input type="text" name="wt_mgdb_export_file" value="" /></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        <?php
        }

        
          public function add_ftp_profile_selection_form_schedule()
        {
?>
            <table class="wf-form-table wt_mgdp_export_options wt_mgdp_export_options_ftp time_class" style="max-width:650px;">
                <tbody>
                    <tr>
                        <th style="font-weight: 400;padding-left: 4px"><?php _e('Export File Into', 'wp-migration-duplicator') ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Choose the location where you want to store the backup of your website.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span></th>
                        <td style="">
                            <?php
                            $export_options  =  $this->get_possible_export_methos(); 
                            foreach ($export_options as $e_key => $e_value) {
                                if($e_key == 'local'){
                                    $export_options[$e_key] = "-- Please Select -- ";
                                }else{
                                    $export_options[$e_key.'_schedule'] = $e_value;
                                    unset( $export_options[$e_key]);
                                }
                            }
                            
                            $cron_settings = get_option('wt_mgdp_cron_settings', null);
                            $ftp_settings='local';$ftp_profile='';$ftp_path='';$ftp_fname='';$cloud_type='';
                            if($cron_settings){
                            $ftp_settings = unserialize($cron_settings['data']);
                            $cloud_type = isset($cron_settings['display_data']['cloud_details']) ? $cron_settings['display_data']['cloud_details'].'_schedule' : 'local';
                            $ftp_profile = isset($ftp_settings['ftp_profile']) ? $ftp_settings['ftp_profile'] : 0;
                            $ftp_path = isset($ftp_settings['ftp_path']) ? $ftp_settings['ftp_path'] : '';
                            $ftp_fname = isset($ftp_settings['ftp_file']) ? $ftp_settings['ftp_file'] : '';
                            }

                            ?>
                            
                            <div class="wt-migrator-select-container">
                                <?php
                                if (is_array($export_options)) {
                                    echo '<select name="wt_mgdb_export_option_schedule" data-option-type="export">';
                                    foreach ($export_options as $value => $export_option) {
                                        if($cloud_type == $value){
                                            echo '<option value="' . esc_attr($value) . '" selected>' . esc_attr($export_option) . '</option>';
                                        }else{
                                            echo '<option value="' . esc_attr($value) . '">' . esc_attr($export_option) . '</option>';
                                        }
                                    }
                                    echo '</select>';
                                }
                                ?>
                                <span class="spinner"></span>
                            </div>
                        </td>
                    </tr>

                    <tr class="child-form-item child-wt_mgdb_export_option wt_mgdb_export_option_ftp_schedule">
                        <?php
                        $profiles = $this->get_ftp_data();
                        if (empty($profiles)) {
                            $profile_link = admin_url('admin.php?page=wp-migration-duplicator-settings#wt-ftp');
                        ?>  
                            <th></th>
                            <td colspan="3">
                                <?php _e('You dont seem to have any FTP profiles yet. ', 'wp-migration-duplicator'); ?>
                                <a href="<?php echo esc_url($profile_link); ?>"><?php _e('Create an FTP ptofile.', 'wp-migration-duplicator') ?></a>
                            </td>
                        <?php } else {
                        ?>

                            <th style="font-weight: 400"> <?php _e('Choose Ftp Profile', 'wp-migration-duplicator'); ?></th>
                            <td>
                                <?php
                                $profiles = $this->get_ftp_profile_for_select('export');
                                if (is_array($profiles) && !empty($profiles)) {
                                    echo '<select name="wt_mgdb_export_ftp_profiles_schedule">';
                                    foreach ($profiles as $key => $profile) {
                                        if($key == $ftp_profile){
                                            echo '<option value="' . esc_attr($key) . '" data-path="' . esc_attr($profile['path']) . '" selected >' . esc_attr($profile['value']) . '</option>';

                                        }else{
                                              echo '<option value="' . esc_attr($key) . '" data-path="' . esc_attr($profile['path']) . '" >' . esc_attr($profile['value']) . '</option>';
                                        }
                                    }

                                    echo '</select>';
                                }

                                ?>
                            </td>
                    </tr>
                    <tr class="child-form-item child-wt_mgdb_export_option wt_mgdb_export_option_ftp_schedule">
                        <th style="font-weight: 400"> <?php _e('Export Path', 'wp-migration-duplicator');  ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Server path where you want to store the backup files.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span> </th>
                        <td> <input type="text" name="wt_mgdb_export_path_schedule" value=<?php echo esc_attr($ftp_path); ?> ></td>
                    </tr>
                    <tr class="child-form-item child-wt_mgdb_export_option wt_mgdb_export_option_ftp_schedule">
                        <th style="font-weight: 400"> <?php _e('Export File Name', 'wp-migration-duplicator');  ?> <span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Add a custom name for the backup file. Leaving the field blank will generate a plugin defined name for your backup file.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span></th>
                        <td> <input type="text" name="wt_mgdb_export_file_schedule" value=<?php echo esc_attr($ftp_fname); ?> ></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        <?php
        }

        /**
         * FTP exporting 
         * @since 1.1.8
         */
        public function check_ftp_export_option_used($out)
        {
            $export_option_post = (isset($_POST['export_option'])) ? $_POST['export_option'] : $out['export_option'];
            $export_option_post = Wp_Migration_Duplicator_Security_Helper::sanitize_item( $export_option_post );
            Webtoffe_logger::write_log( 'Export',$export_option_post .' file upload started .. ' );
            if ('ftp' == $export_option_post) {
                $out['export_option'] = 'ftp';
                $ftp_profile = (isset($_POST['ftp_profile'])) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['ftp_profile'],'int') : 0;

                if (0 == $ftp_profile) {
                    $out['status'] = false;
                    $out['msg'] = __('Please choose profile', 'wp-migration-duplicator');
                    return $out;
                }
                
                $ftp_path   = (isset($_POST['ftp_path'])) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['ftp_path']) : '';     
                $ftp_details = $this->get_ftp_data_by_id($ftp_profile);
               
                if (empty($ftp_details)) {
                    $out['status']  = false;
                    $out['msg']     =  __('Please choose profile', 'wp-migration-duplicator');
                    return $out;
                }
                $ftp_server = $ftp_details['server'];
                $ftp_port   = $ftp_details['port'];
                $username   = $ftp_details['user_name'];
                $password   = $ftp_details['password'];
                $wt_is_sftp   = $ftp_details['is_sftp'];

                 
                 $file       = (isset($_POST['ftp_file']) && '' !== $_POST['ftp_file']) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item(rtrim($_POST['ftp_file'],'.zip')).'.zip' : $out['backup_file'];
                 $local_file = WP_CONTENT_DIR . Wp_Migration_Duplicator::$backup_dir_name . "/" . $out['backup_file_name'];//$out['backup_file'];
                $remote_file = (substr($ftp_path, -1) != '/') ? ($ftp_path . "/" . basename($file)) : ($ftp_path . basename($file));

                if($wt_is_sftp == 1)/* sftp */
		{
			//handle sftp upload
			include_once "classes/class-sftp.php";
			$sftp=new Wt_mgdp_Sftp();

			/* preparing remote file path */
			$out=$sftp->upload($ftp_details, $local_file, $remote_file, $out);

			return $out;
		}else{
                $ftp_conn = $this->establish_connection_ftp($ftp_server, $ftp_port, $username, $password);
                   //$ftp_path = ltrim($ftp_path, '/');
                    if( $this->ftp_isdir( $ftp_conn, $ftp_path) === false ) {
                        $out['status']  = false;
                        $out['msg']     =  __('The specified directory could not be found!', 'wp-migration-duplicator');
                        Webtoffe_logger::write_log( 'Export','The specified directory could not be found!' );
                        return $out;
                    }
                    $ftp_connection = ftp_put($ftp_conn, $remote_file, $local_file, FTP_BINARY);
                    if(!$ftp_connection){
                        if(!@ftp_pasv($ftp_conn, true)) //failed to enable passive mode
                        {
                                $out['msg']=__('Failed to enable passive mode.', 'wp-migration-duplicator');
                                ftp_close($ftp_conn);
                                $out['status']  = false;
                                Webtoffe_logger::write_log( 'Export','Failed to enable passive mode.' );
                                return $out;
                        }
                        $ftp_connection = ftp_put($ftp_conn, $remote_file, $local_file, FTP_BINARY);
                    }
                    if ($ftp_connection) {
                        $out['msg']     =  __('Exported into FTP specified', 'wp-migration-duplicator');
                        unlink($local_file);
                    } else {
                        $out['status']  = false;
                        $out['msg']     =  __('Something went wrong!', 'wp-migration-duplicator');
                        Webtoffe_logger::write_log( 'Export','Something went wrong!.Please check login details' );
                    }
                }
            }
            return $out;
        }
        function ftp_isdir($conn_id, $dir)
        {
            if (@ftp_chdir($conn_id, $dir)) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Establish FTP connection
         * @since 1.1.8
         */
        public function establish_connection_ftp($ftp_server, $ftp_port, $ftp_user, $ftp_password)
        {
            $ftp_conn = ftp_connect($ftp_server, $ftp_port);
            if ($ftp_conn == false) {
                $error_message = "Could not connect " . $ftp_server;
                Webtoffe_logger::error($error_message);
                return false;
            }
            if (@ftp_login($ftp_conn, $ftp_user, $ftp_password) == false) {
                $error_message = "Couldn't establish a connection.";
                Webtoffe_logger::error($error_message);
                return  false;
            }
            return $ftp_conn;
        }
        /**
         * Add import from FTP Form
         * @since 1.1.8
         */
        function ftp_form_on_import()
        { ?>
            <div class="child-form-item child-wt_mgdb_import_option wt_mgdb_import_option_ftp" style="display:none">

                <table class="wf-form-table wt_mgdp_import_options" style="max-width:650px;">
                    <tbody>

                        <tr class="wt_mgdb_export_option">
                            <?php
                            $profiles = $this->get_ftp_data();
                            if (empty($profiles)) {
                                $profile_link = admin_url('admin.php?page=wp-migration-duplicator-settings#wt-ftp');
                            ?>
                                <td colspan="3" style="padding:10px 0px;">
                                    <?php _e('You dont seem to have any FTP profiles yet.', 'wp-migration-duplicator'); ?>
                                    <a href="<?php echo esc_url($profile_link); ?>"><?php _e('Create an FTP ptofile.', 'wp-migration-duplicator') ?></a>
                                </td>
                            <?php } else {
                            ?>
                                <th style="font-weight: 400"> <?php _e('Choose Ftp Profile', 'wp-migration-duplicator'); ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Choose the location where you want to store the backup of your website.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span></th>
                                <td>
                                    <?php
                                    $profiles = $this->get_ftp_profile_for_select('import');
                                    if (is_array($profiles) && !empty($profiles)) {
                                        echo '<select name="wt_mgdb_import_ftp_profiles">';
                                        foreach ($profiles as $key => $profile) {
                                            echo '<option value="' . esc_attr($key) . '" data-path="' . esc_attr($profile['path']) . '" >' . esc_attr($profile['value']) . '</option>';
                                        }

                                        echo '</select>';
                                    }

                                    ?>
                                </td>
                        </tr>
                        <tr class="wt_mgdb_import_option">
                        <th style="font-weight: 400"> <?php _e('Import Path', 'wp-migration-duplicator');  ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('Choose a directory on the server from where you want to import the backup.The directory specified should be present on the server.', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span> </th>
                            <td> <input type="text" name="wt_mgdb_import_path" value="" /></td>
                        </tr>
                        <tr class="wt_mgdb_import_option">
                            <th style="font-weight: 400"> <?php _e('Import File Name', 'wp-migration-duplicator');  ?><span class="wt-mgdp-tootip" data-wt-mgdp-tooltip="<?php _e('The exact filename of the backup file to import', 'wp-migration-duplicator'); ?>"><span class="wt-mgdp-tootip-icon"></span></span></th>
                            <td> <input type="text" name="wt_mgdb_import_ftp_file" value="" /></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
<?php

        }
        /**
         * get the attached file from FTP
         * @since 1.1.8
         */
        function get_attachment_url_from_ftp($import_data, $import_method)
        {

            if ('ftp' != $import_method) {
                return $import_data;
            }
            $error_message = __('The specified file could not be found on the FTP server','wp-migration-duplicator');
            $import_data['message'] = $error_message;
            $local_file_url = content_url() . Wp_Migration_Duplicator::$backup_dir_name . '/temp-import-file.zip';
            $local_file = Wp_Migration_Duplicator::$backup_dir . '/temp-import-file.zip';

            $ftp_profile    = (isset($_POST['ftp_profile'])) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['ftp_profile'],'int') : 0;
            $ftp_path       = (isset($_POST['ftp_path'])) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['ftp_path']) : '';
            $ftp_file       = (isset($_POST['ftp_file'])) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['ftp_file']) : '';
            if (0 == $ftp_profile || '' == $ftp_path) {
                $import_data['message'] = __('Please choose a FTP profile', 'wp-migration-duplicator');
                return $import_data;
            }
            else if('' === $ftp_file ) {
                $import_data['message'] = __('Please provide a file name', 'wp-migration-duplicator');
                return $import_data;
            }
            $ftp_details = $this->get_ftp_data_by_id($ftp_profile);
            if (empty($ftp_details)) {
                $import_data['message'] = __('Please choose a FTP profile', 'wp-migration-duplicator');
                return $import_data;
            }
            
            $ftp_server = $ftp_details['server'];
            $ftp_port   = $ftp_details['port'];
            $username   = $ftp_details['user_name'];
            $password   = $ftp_details['password'];
            $remote_file_path = (substr($ftp_path, -1) != '/') ? ($ftp_path . "/") : ( $ftp_path );
    //            $ftp_path = ltrim($ftp_path, '/');
            if (preg_match('/\.zip/', $ftp_file) === 1 ) {
                $remote_file_path = $remote_file_path.$ftp_file;
            } else {
                $remote_file_path = $remote_file_path.$ftp_file.'.zip';
            }
            $ftp_file = $remote_file_path.$ftp_file.'.zip';
                       if($ftp_details['is_sftp'] == 1) /* sftp */
		{
			//handle sftp download
			include_once "classes/class-sftp.php";
			$sftp=new Wt_mgdp_Sftp();

			/* preparing remote file path */
			$import_data = $sftp->download($ftp_details, $local_file, $remote_file_path, $out);
                        
                        if($import_data['status'] === true) {
                            $import_data['message'] = __('File has fetched from the FTP server, now importing the file....','wp-migration-duplicator');
                            $import_data['file'] = $local_file_url;
                        }

			return $import_data;
		}else{  
                    
                     $ftp_conn   = $this->establish_connection_ftp($ftp_server, $ftp_port, $username, $password);
                    if( $this->ftp_isdir( $ftp_conn, $ftp_path) === false ) {
                        $import_data['message']   =  __('The specified directory could not be found!', 'wp-migration-duplicator');
                        return $import_data;
                    }
                    if( $ftp_conn === false ) {
                        $import_data['message'] = __('Could not establish a connection with FTP server please check your FTP credentials!','wp-migration-duplicator');
                    } else {
                        if (ftp_get($ftp_conn, $local_file, $remote_file_path, FTP_BINARY)) {
                            $import_data['status'] = true;
                            $import_data['message'] = __('File has fetched from the FTP server, now importing the file....','wp-migration-duplicator');
                            $import_data['file'] = $local_file_url;
                        } else {
                            $import_data['message'] = __('Specified file could not be found on the FTP Server','wp-migration-duplicator');
                        }
                    }
                     return $import_data;
                }
        }


        /**
         * Enable/Disable FTP 
         * @since 1.1.8
         */

        function enable_disabe_ftp()
        {
            $is_enabled = wp_validate_boolean( (isset($_POST['is_enabled'])) ? $_POST['is_enabled'] : true );
            $options = Wp_Migration_Duplicator::get_webtoffee_migrator_option();
            $options[$this->module_base.'_'.'status'] = $is_enabled;
            Wp_Migration_Duplicator::update_webtoffee_migrator_option($options);
            die();
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
        * List existing backups from FTP
        *
        * @since  1.1.8
        * @throws Exception Error message.
        * @return bool,array
        */
        public function get_existing_backups() {
            if ( ! Wp_Migration_Duplicator_Security_Helper::check_write_access( WT_MGDP_PLUGIN_FILENAME, WT_MGDP_PLUGIN_FILENAME ) ) {
                wp_die(__('You do not have sufficient permission to perform this operation', 'wp-migration-duplicator-pro')); 
            }
            $error_message = __('Please choose an FTP profile', 'wp-migration-duplicator'); 
            $ftp_profile = (isset($_POST['ftp_profile']) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['ftp_profile']) : '');
            $ftp_path = (isset($_POST['ftp_path']) ? Wp_Migration_Duplicator_Security_Helper::sanitize_item($_POST['ftp_path']) : '/');

            if ('' == $ftp_profile)  {
                wp_send_json_error( $error_message );
            }
            $ftp_details = $this->get_ftp_data_by_id( $ftp_profile );
            if ( empty($ftp_details) ) {
                wp_send_json_error( $error_message );
            }
            $ftp_server = ( isset( $ftp_details['server'] ) ? $ftp_details['server'] : '' );
            $ftp_port   = ( isset( $ftp_details['port'] ) ? $ftp_details['port'] : '' );
            $username   = ( isset( $ftp_details['user_name'] ) ? $ftp_details['user_name'] : '' );
            $password   = ( isset( $ftp_details['password'] ) ? $ftp_details['password'] : '' );
            $ftp_conn = $this->establish_connection_ftp($ftp_server, $ftp_port, $username, $password);
            $file_names = array();
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "ftp://$ftp_server".$ftp_path);
            curl_setopt($ch, CURLOPT_PORT, $ftp_port);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, $username.":".$password);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_DIRLISTONLY, TRUE);
            $files_list = curl_exec($ch);
            curl_close($ch);
        
            // The list of all files names on folder
            $file_names_array= explode("\n", $files_list);
          
            // Filter and exclude array elements not valid
            foreach ($file_names_array as $file_name)
            {  
                if (preg_match('/\.zip/', $file_name) === 1 ) {
                    $file_names[] = $file_name;
                }
            }
            return $file_names;
            wp_send_json_success(__('Authentication success!', 'wp-migration-duplicator'));
        }
    }

    new Wp_Migration_Profile();
}
