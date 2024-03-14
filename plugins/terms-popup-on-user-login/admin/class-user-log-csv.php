<?php

use WpLHLAdminUi\LicenseKeys\LicenseKeyHandler;

class User_Log_CSV {


    private $log_directory = '';
    private $logs_url = '';
    private $license_is_active = false;

    public function __construct() {

        $uploads  = wp_upload_dir(null, false);
        $logs_dir = $uploads['basedir'] . '/terms-popup-on-user-login';
        $this->log_directory = $logs_dir;

        $upload_dir = wp_upload_dir();
        $logs_url = set_url_scheme($upload_dir["baseurl"], 'https') . "/terms-popup-on-user-login";
        $this->logs_url = $logs_url;

        $license_key_handler = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());
        $this->license_is_active = $license_key_handler->is_active();
    }


    public function expost_log_CSV($generate_file_type = "report") {

        $license_key_active = $this->license_is_active;

        if (!$license_key_active) {
            return false;
        }

        if (($generate_file_type == "report" || $generate_file_type == "userlog")) {
            /**
             * Generate uniqu string
             * for filemaes Later
             */
            $utils = new Terms_Popup_On_User_Utils();
            // Make unique file name for site
            $date_suffix =  $utils->__get_date_for_file_name();
            $date_suffix .=  "-";
            $date_suffix .=   $utils->__get_4_char_hash();
            $date_suffix .=  $utils->__generate_random_string();
        }

        if ($generate_file_type == "report") {

            $fields = array('ID', 'Dsiplay_Name', 'Accepted', 'Latest_Terms_Accepted', 'Date');
            $values = array();    // initialize the array

            // $users = get_users( ['fields' => ['ID','display_name'], 'role__not_in' => ['administrator'] ] );
            $users = get_users(['fields' => ['ID', 'display_name']]);

            $csv_array = [];
            foreach ($users as $user) {
                $the_user_id   = (int) $user->ID;

                $values['ID'] =  $user->ID;
                $values['Dsiplay_Name'] =  $user->display_name;

                $values['Accepted'] =  "";
                $values['Latest_Terms_Accepted'] =  "";
                $values['Last_Accepted_Date'] =  "";
                $values['Useragent'] =  "";
                $values['User_IP'] =  "";
                $values['User_Location'] =  "";

                $user_state_manager = new TPUL_User_State($the_user_id);
                $user_accepted_terms  = $user_state_manager->get_user_state();
                $values['Accepted'] =  "not seen";

                if (!empty($user_accepted_terms)) {

                    $values['Accepted'] =  "not seen";
                    if ($user_accepted_terms > 0) {
                        $values['Accepted'] =  "accepted";
                    } elseif ($user_accepted_terms < 0) {
                        $values['Accepted'] =  "declined";
                    }

                    // date
                    $user_accepted_terms_date = $user_state_manager->get_user_accepted_date_raw();

                    if (!empty($user_accepted_terms_date)) {
                        $utils = new Terms_Popup_On_User_Utils();
                        $values['Last_Accepted_Date'] =  $utils->__get_user_accepted_date_for_file($the_user_id);
                    } else {
                        $values['Last_Accepted_Date'] =  "";
                    }

                    // latest accepted
                    if ($user_accepted_terms == 2) {
                        $values['Latest_Terms_Accepted'] =  "latest terms accepted";
                    } else {
                        $values['Latest_Terms_Accepted'] =  "";
                    }

                    // if (!empty($user_accepted_terms_date)) {
                    $user_state_manager = new TPUL_User_State($the_user_id);

                    $user_agent = $user_state_manager->get_recorded_useragent();
                    $values['Useragent'] = $user_agent;
                    // }

                    $user_clientIP = $user_state_manager->get_clientIP();
                    $values['User_IP'] =  $user_clientIP;

                    $user_location = $user_state_manager->get_location_coordinates();
                    $values['User_Location'] =  $user_location;
                }

                $csv_array[] = $values;
            }
            //cleanup after 3 minutes
            wp_schedule_single_event(time() + 300, 'tpul_schedule_log_file_cleanup_event');
            return $this->write_csv_file($csv_array, "terms-user-report--" . $date_suffix . ".csv");
        } elseif ($generate_file_type == "userlog") {

            $log_table = new termspul\Tpul_DB;
            $db_result = $log_table->fetch_all();


            $values = array();
            $csv_array = [];

            foreach ($db_result as $key => $record) {
                $values['Log_Record'] = $record->tpul_log_id;
                $values['Date_time'] = $record->created_at;
                $values['User_Id'] = $record->the_user_id;
                $values['Username'] = $record->user_username;
                $values['User_display_name'] = $record->user_displayname;
                $values['User_action'] = $record->user_action;

                $csv_array[] = $values;
            }

            //cleanup after 3 minutes
            wp_schedule_single_event(time() + 300, 'tpul_schedule_log_file_cleanup_event');
            return $this->write_csv_file($csv_array, "terms-user-advanced-log--" . $date_suffix . ".csv");
        }

        return false;
    }


    /**
     * Cleanup log file folder
     */
    function cleanup_reports_folder() {

        require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php');
        require_once(ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php');
        $fileSystemDirect = new WP_Filesystem_Direct(false);
        $fileSystemDirect->rmdir($this->log_directory, true);

        // error_log("Cleanup happened" . date("d h i:s"));

    }
    // add_action( 'tpul_schedule_log_file_cleanup_event','cleanup_reports_folder' );

    public function write_csv_file($csv_array, $title, $delimiter = ";") {

        /**
         * Prepare
         */

        $logs_dir = $this->log_directory;

        if (!is_dir($logs_dir)) {
            mkdir($logs_dir, 0755, true);
        }

        if (!file_exists($logs_dir)) {
            return false;
        }

        /**
         * Create an index.html to avoid directory listing.
         */
        if (!file_exists($logs_dir . '/index.html')) {
            @file_put_contents(
                $logs_dir . '/index.html',
                '<!-- Prevent the directory listing. -->'
            );
        }

        /**
         * Harden with htaccess
         */

        if (count($_COOKIE)) {
            foreach ($_COOKIE as $key => $val) {
                if (preg_match("/wordpress_logged_in/i", $key)) {
                    $cookie_key = $key;
                }
            }
        } else {
            return false;
        }

        @file_put_contents(
            $logs_dir . '/.htaccess',
            ''
        );
        $htaccess_array = [];
        $htaccess_array[] = "<IfModule mod_rewrite.c>";
        $htaccess_array[] =  "RewriteCond %{REQUEST_FILENAME} (.*)";
        $htaccess_array[] = "RewriteCond %{HTTP_COOKIE} !" . $cookie_key . "([a-zA-Z0-9_]*) [NC]";
        $htaccess_array[] = "RewriteRule .* - [F,L]";
        $htaccess_array[] = "</IfModule>";

        $htaccess_fhandle = @fopen($logs_dir . '/.htaccess', 'a');
        $htaccess_ftext = implode("\n", $htaccess_array);
        $written = @fwrite($htaccess_fhandle, "\n" . $htaccess_ftext . "\n");
        @fclose($htaccess_fhandle);

        /**
         * Write the file
         */

        $file_handle = fopen($logs_dir . '/' . $title, 'w');

        // use keys as column titles
        fputcsv($file_handle, array_keys($csv_array['0']), $delimiter);

        foreach ($csv_array as $value) {
            fputcsv($file_handle, $value, $delimiter);
        }

        fclose($file_handle);

        return $this->logs_url . "/" . $title;
    }


    /**
     * NOT USED
     * Creates a file stream for download
     * ditching this method in favor of creating a file instead in the uploads folder
     */
    public function array_csv_download($array, $filename = "export.csv", $delimiter = ";") {
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '";');

        // clean output buffer
        ob_end_clean();

        $handle = fopen('php://output', 'w');

        // use keys as column titles
        fputcsv($handle, array_keys($array['0']), $delimiter);

        foreach ($array as $value) {
            fputcsv($handle, $value, $delimiter);
        }

        fclose($handle);

        // flush buffer
        ob_flush();

        // use exit to get rid of unexpected output afterward
        exit();
    }
}
