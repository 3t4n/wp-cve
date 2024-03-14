<?php

namespace filr;

/**
 * File System Class
 */
class FILR_Filesystem
{
    /**
     * Contains instance or null
     *
     * @var object|null
     */
    private static  $instance = null ;
    /**
     * Returns instance of FILR_Filesystem.
     *
     * @return object
     */
    public static function get_instance()
    {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor for FILR_Meta.
     */
    public function __construct()
    {
        add_action( 'wp_ajax_upload_file', array( $this, 'ajax_upload_file' ) );
        add_action( 'wp_ajax_delete_file', array( $this, 'ajax_delete_file' ) );
    }
    
    /**
     * Ajax handler to upload files via FILR_Uploader.
     *
     * @return void
     */
    public function ajax_upload_file()
    {
        $file_id = sanitize_text_field( $_POST['post_id'] );
        $nonce = $_POST['nonce'];
        $encryption = false;
        if ( !wp_verify_nonce( $nonce, 'filr-uploader-nonce' ) ) {
            die;
        }
        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $file_id ) ) {
            return $file_id;
        }
        $settings = wp_parse_args( get_option( 'filr_status' ), FILR_Admin::get_defaults( 'filr_status' ) );
        // build the path.
        $uploads_directory = wp_upload_dir();
        $filr_directory = apply_filters(
            'filr_file_directory',
            $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $settings['filr_download_directory'] . DIRECTORY_SEPARATOR . $file_id . DIRECTORY_SEPARATOR,
            $settings['filr_download_directory'],
            $file_id
        );
        // if directory not exists, create one.
        if ( !file_exists( $filr_directory ) ) {
            wp_mkdir_p( $filr_directory );
        }
        $uploader_encryption = 'name';
        // upload file.
        $file = 'file-upload';
        $uploader = new FILR_Uploader( $file, array(
            'uploadDir' => $filr_directory,
            'title'     => $uploader_encryption,
        ) );
        $data = $uploader->upload();
        // check for existing files and combine them.
        $existing_files = get_post_meta( $file_id, 'file-upload', true );
        if ( isset( $existing_files['files'] ) && !empty($existing_files['files']) ) {
            foreach ( $existing_files['files'] as $file ) {
                $data['files'][] = $file;
            }
        }
        $file_url = '';
        
        if ( isset( $data['files'][0]['name'] ) ) {
            $file_url = $uploads_directory['baseurl'] . '/' . $settings['filr_download_directory'] . '/' . $file_id . '/' . $data['files'][0]['name'];
            update_post_meta( $file_id, 'file-download', $file_url );
        }
        
        // save file array for later usage.
        update_post_meta( $file_id, 'file-upload', $data );
        // clean file records.
        if ( apply_filters( 'filr_auto_clean', false ) ) {
            self::file_cleaner( $file_id );
        }
        $response = array(
            'file-upload' => $data,
        );
        if ( !empty($file_url) ) {
            $response = array(
                'file-upload'   => $data,
                'download_link' => $file_url,
            );
        }
        print wp_json_encode( $response );
        exit;
    }
    
    /**
     * Ajax handler to delete files via FILR_Uploader.
     *
     * @return void
     */
    public function ajax_delete_file()
    {
        $file_id = sanitize_text_field( $_POST['post_id'] );
        $file_name = sanitize_text_field( $_POST['filename'] );
        $nonce = $_POST['nonce'];
        if ( !wp_verify_nonce( $nonce, 'filr-uploader-nonce' ) ) {
            die;
        }
        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $file_id ) ) {
            return $file_id;
        }
        $settings = wp_parse_args( get_option( 'filr_status' ), FILR_Admin::get_defaults( 'filr_status' ) );
        // build the path.
        $uploads_directory = wp_upload_dir();
        $filr_directory = apply_filters(
            'filr_file_directory',
            $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $settings['filr_download_directory'] . DIRECTORY_SEPARATOR . $file_id . DIRECTORY_SEPARATOR,
            $settings['filr_download_directory'],
            $file_id
        );
        $file = $filr_directory . $file_name;
        // delete file from file upload array in post meta.
        $existing_files = get_post_meta( $file_id, 'file-upload', true );
        $new_files = array();
        if ( $existing_files['files'] ) {
            foreach ( $existing_files['files'] as $file ) {
                if ( $file['name'] !== $file_name ) {
                    $new_files[] = $file;
                }
            }
        }
        // Update new array of files in metadata.
        $existing_files['files'] = $new_files;
        update_post_meta( $file_id, 'file-upload', $existing_files );
        // if more than one file we need to regenerate zip too, otherwise update download link meta only.
        
        if ( $existing_files['files'] ) {
            $file_url = $uploads_directory['baseurl'] . '/' . $settings['filr_download_directory'] . '/' . $file_id . '/' . $existing_files['files'][0]['name'];
            update_post_meta( $file_id, 'file-download', $file_url );
        } else {
            delete_post_meta( $file_id, 'file-download' );
        }
        
        // delete the file from the filesystem.
        
        if ( isset( $file_name ) ) {
            $deletable_file = $filr_directory . str_replace( array( DIRECTORY_SEPARATOR, '\\' ), '', $file_name );
            if ( file_exists( $deletable_file ) ) {
                unlink( $deletable_file );
            }
        }
        
        // clean file records.
        if ( apply_filters( 'filr_auto_clean', false ) ) {
            self::file_cleaner( $file_id );
        }
        $response = array(
            'delete' => true,
        );
        if ( !empty($file_url) ) {
            $response = array(
                'delete'        => true,
                'download_link' => $file_url,
            );
        }
        print wp_json_encode( $response );
        exit;
    }
    
    /**
     * Handles the creation of a subfolder inside of /uploads/ directory.
     *
     * @return void
     */
    public static function create_filr_directory( $directory_name )
    {
        $uploads_directory = wp_upload_dir();
        $filr_directory = apply_filters( 'filr_directory', $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $directory_name, $directory_name );
        if ( !file_exists( $filr_directory ) ) {
            wp_mkdir_p( $filr_directory );
        }
    }
    
    /**
     * Delete a subfolder inside of /uploads/ directory.
     *
     * @return void
     */
    public static function delete_filr_directory()
    {
        global  $wp_filesystem ;
        if ( !function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if ( is_null( $wp_filesystem ) ) {
            WP_Filesystem();
        }
        $uploads_directory = wp_upload_dir();
        $settings = wp_parse_args( get_option( 'filr_status' ), FILR_Admin::get_defaults( 'filr_status' ) );
        $filr_directory = apply_filters( 'filr_directory', $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $settings['filr_download_directory'], $settings['filr_download_directory'] );
        if ( file_exists( $filr_directory ) ) {
            $wp_filesystem->delete( $filr_directory, true );
        }
    }
    
    /**
     * Creates an index.php file to prevent direct access via HTTP.
     *
     * @return void
     */
    public static function create_index_file()
    {
        global  $wp_filesystem ;
        if ( !function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if ( is_null( $wp_filesystem ) ) {
            WP_Filesystem();
        }
        $uploads_directory = wp_upload_dir();
        $settings = wp_parse_args( get_option( 'filr_status' ), FILR_Admin::get_defaults( 'filr_status' ) );
        $file = $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $settings['filr_download_directory'] . DIRECTORY_SEPARATOR . 'index.php';
        $content = '<?php // silence is golden.';
        if ( !file_exists( $file ) ) {
            $wp_filesystem->put_contents( $file, $content, FS_CHMOD_FILE );
        }
    }
    
    /**
     * Delete a the index file
     *
     * @return void
     */
    public static function delete_index_file()
    {
        global  $wp_filesystem ;
        if ( !function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if ( is_null( $wp_filesystem ) ) {
            WP_Filesystem();
        }
        $uploads_directory = wp_upload_dir();
        $settings = wp_parse_args( get_option( 'filr_status' ), FILR_Admin::get_defaults( 'filr_status' ) );
        $file = $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $settings['filr_download_directory'] . DIRECTORY_SEPARATOR . 'index.php';
        if ( file_exists( $file ) ) {
            $wp_filesystem->delete( $file, true );
        }
    }
    
    /**
     * Creates an .htaccess file to prevent direct access via HTTP.
     *
     * @return void
     */
    public static function create_htaccess_file()
    {
        global  $wp_filesystem ;
        if ( !function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if ( is_null( $wp_filesystem ) ) {
            WP_Filesystem();
        }
        $uploads_directory = wp_upload_dir();
        $settings = wp_parse_args( get_option( 'filr_status' ), FILR_Admin::get_defaults( 'filr_status' ) );
        $file = $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $settings['filr_download_directory'] . '/.htaccess';
        $content = 'Options All -Indexes';
        if ( !file_exists( $file ) ) {
            $wp_filesystem->put_contents( $file, $content, FS_CHMOD_FILE );
        }
    }
    
    /**
     * Delete a the .htaccess file
     *
     * @return void
     */
    public static function delete_htaccess_file()
    {
        global  $wp_filesystem ;
        if ( !function_exists( 'WP_Filesystem' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }
        if ( is_null( $wp_filesystem ) ) {
            WP_Filesystem();
        }
        $uploads_directory = wp_upload_dir();
        $settings = wp_parse_args( get_option( 'filr_status' ), FILR_Admin::get_defaults( 'filr_status' ) );
        $file = $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $settings['filr_download_directory'] . '/.htaccess';
        if ( file_exists( $file ) ) {
            $wp_filesystem->delete( $file, true );
        }
    }
    
    /**
     * Returns unit based on byte size.
     *
     * @param int $bytes number of bytes.
     *
     * @return string
     */
    public static function format_byte_sizes( int $bytes ) : string
    {
        
        if ( $bytes >= 1073741824 ) {
            $bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
        } elseif ( $bytes >= 1048576 ) {
            $bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
        } elseif ( $bytes >= 1024 ) {
            $bytes = number_format( $bytes / 1024, 2 ) . ' KB';
        } elseif ( $bytes > 1 ) {
            $bytes = $bytes . ' bytes';
        } elseif ( $bytes == 1 ) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        
        return $bytes;
    }
    
    /**
     * Block file access via htaccess
     *
     * @param int $file_id current download object id.
     * @param string $expire_date a date string for expiration.
     * @param string $remaining_downloads number of remaining downloads available.
     *
     * @return bool
     */
    public static function block_file_access( int $file_id, string $expire_date, string $remaining_downloads ) : bool
    {
        return false;
    }
    
    /**
     * Check if files has to be blocked per interval (hourly).
     *
     * @return void
     */
    public function check_file_access_on_interval()
    {
    }
    
    /**
     * Clean file records with filesystem comparing.
     *
     * @param int $file_id current file id.
     *
     * @return void
     */
    public static function file_cleaner( int $file_id )
    {
        $file_upload = get_post_meta( $file_id, 'file-upload', true );
        $settings = wp_parse_args( get_option( 'filr_status' ), FILR_Admin::get_defaults( 'filr_status' ) );
        $uploads_directory = wp_upload_dir();
        $filr_directory = apply_filters(
            'filr_file_directory',
            $uploads_directory['basedir'] . DIRECTORY_SEPARATOR . $settings['filr_download_directory'] . DIRECTORY_SEPARATOR . $file_id . DIRECTORY_SEPARATOR,
            $settings['filr_download_directory'],
            $file_id
        );
        $remaining_files = list_files( $filr_directory );
        $current_files = array();
        foreach ( $file_upload['files'] as $file ) {
            $current_files[] = $file['file'];
        }
        foreach ( $remaining_files as $file ) {
            if ( !in_array( $file, $current_files ) ) {
                unlink( $file );
            }
        }
    }
    
    /**
     * Clean file records with filesystem comparing.
     *
     * @param int $file_id current file id.
     *
     * @return void
     */
    public static function check_encryption_mode( int $file_id, $encrypt )
    {
    }
    
    /**
     * Open file and force download with PHP.
     *
     * @return void
     */
    public function open_file()
    {
    }
    
    /**
     * Get secure download URL from id.
     *
     * @param int $post_id given post id.
     *
     * @return string|bool
     */
    public static function get_secure_url( int $post_id )
    {
        return false;
    }

}