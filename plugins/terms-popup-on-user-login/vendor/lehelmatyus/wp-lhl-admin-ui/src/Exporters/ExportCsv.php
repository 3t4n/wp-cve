<?php

namespace WpLHLAdminUi\Exporters;
use WpLHLAdminUi\Helpers\Utils;
use WpLHLAdminUi\Helpers\FolderPathUtil;

class ExportCsv{


    private $file_directory = '';
    private $file_url = '';

    public function __construct($folder_name) {

        $folderPath = new FolderPathUtil($folder_name);
        $this->file_directory = $folderPath->getDirectory();
        $this->file_url = $folderPath->getUrl();

    }


    public function expost_arr_as_CSV($csv_array, $file_name = 'log_file', $add_date_suffix = true, $add_unique_suffix = false) {


        $date_suffix = '';
        if($add_date_suffix){
            $date_suffix .=   "--" . Utils::__get_date_for_file_name();
        }
        if($add_date_suffix && $add_unique_suffix){
            $date_suffix .=  "-";
        }
        if($add_unique_suffix){
            $date_suffix .=  Utils::__get_4_char_hash();
            $date_suffix .=  Utils::__generate_random_string();
        }

        $file_name = str_replace("/","--",$file_name);
        $file_name = sanitize_file_name($file_name);

        // error_log('count($csv_array)');
        // error_log(count($csv_array));
        // cleanup after 3 minutes
        //  wp_schedule_single_event( time() + 300, 'tpul_schedule_log_file_cleanup_event' );

        return $this->write_csv_file($csv_array, $file_name . $date_suffix . ".csv");
        
    }


    /**
     * Cleanup log file folder
     * add_action( 'tpul_schedule_log_file_cleanup_event','cleanup_reports_folder' );
     */
    function cleanup_reports_folder() {

        // require_once ( ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php' );
        // require_once ( ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php' );
        // $fileSystemDirect = new WP_Filesystem_Direct(false);
        // $fileSystemDirect->rmdir($this->file_directory, true);
        
    }
    
    public function write_csv_file($csv_array, $title, $delimiter=",", $htaccess_hardening = false){

        /**
         * Prepare
         */
        
         $file_dir = $this->file_directory;

		if ( ! is_dir( $file_dir ) ) {
			mkdir( $file_dir, 0755, true );
		}

        if ( ! file_exists($file_dir)) {
            return false;
        }

        /**
         * Create an index.html to avoid directory listing.
         */
        if (!file_exists($file_dir . '/index.html')) {
            @file_put_contents(
                $file_dir . '/index.html',
                '<!-- Prevent the directory listing. -->'
            );
        }

        /**
         * Harden with htaccess
         * make folder only accessible by the logged in user
         */

        if ($htaccess_hardening){

            if (count($_COOKIE)) {
                foreach ($_COOKIE as $key => $val) {
                    if (preg_match("/wordpress_logged_in/i", $key)) {
                        $cookie_key = $key;
                    }       
                }
            }else{
                return false;
            }

            @file_put_contents(
                $file_dir . '/.htaccess',
                ''
            );
            $htaccess_array = [];
            $htaccess_array[] = "<IfModule mod_rewrite.c>";
            $htaccess_array[] =  "RewriteCond %{REQUEST_FILENAME} (.*)";
            $htaccess_array[] = "RewriteCond %{HTTP_COOKIE} !" . $cookie_key . "([a-zA-Z0-9_]*) [NC]";
            $htaccess_array[] = "RewriteRule .* - [F,L]";
            $htaccess_array[] = "</IfModule>";

            $htaccess_fhandle = @fopen($file_dir . '/.htaccess', 'a');
            $htaccess_ftext = implode("\n", $htaccess_array);
            $written = @fwrite($htaccess_fhandle, "\n" . $htaccess_ftext . "\n");
            @fclose($htaccess_fhandle);

        }

        /**
         * Write the file
         */

		$file_handle = fopen( $file_dir . '/' . $title, 'w' );

        // create keys as columns
        // standardize data so all items contain all keys
        $standardized_csv_array = $this->standardize_values($csv_array);

        fputcsv( $file_handle, $standardized_csv_array['columns'] , $delimiter );
        
        foreach ( $standardized_csv_array['data'] as $item ) {
            fputcsv( $file_handle, array_values($item) , $delimiter );
        }

        fclose( $file_handle );

		return $this->file_url . "/" . $title;

    }

    /**
     * Collects unique key values for columns from all children elements
     * standardize data so all items contain all keys
     */
    public function standardize_values($csv_array){

        $unique_keys = [];
        $standardized_csv_array = [];

        // Get all existing unique Keys
        foreach ($csv_array as $item) 
        { 
            foreach ($item as $key => $value) 
            {
                if (!in_array($key, $unique_keys)) 
                { 
                    array_push($unique_keys, $key); 
                }
            }
        }
        asort($unique_keys);

        // Fill array items with existing unique keys 
        // if they dont exist on the item
        foreach ($csv_array as $item_key => $item) {
            foreach ($unique_keys as $unique_key){

                if( !array_key_exists($unique_key, $item) ) {
                    // must not be empty string
                    $item[$unique_key] = '_';
                }
            }
            ksort($item);
            $standardized_csv_array[] = $item;
        }

        

        return [
            'columns' => $unique_keys,
            'data' => $standardized_csv_array
        ];
    }


    /**
     * NOT USED
     * Creates a file stream for download
     * ditching this method in favor of creating a file instead in the uploads folder
     */
    public function array_csv_download( $array, $filename = "export.csv", $delimiter="," ) {
        header( 'Content-Type: application/csv' );
        header( 'Content-Disposition: attachment; filename="' . $filename . '";' );

        // clean output buffer
        ob_end_clean();

        $handle = fopen( 'php://output', 'w' );

        // use keys as column titles
        fputcsv( $handle, array_keys( $array['0'] ) , $delimiter );

        foreach ( $array as $value ) {
            fputcsv( $handle, $value , $delimiter );
        }

        fclose( $handle );

        // flush buffer
        ob_flush();

        // use exit to get rid of unexpected output afterward
        exit();
    }

}