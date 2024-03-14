<?php

if(!class_exists('BIFL_ZIP_Uploader')){
class BIFL_ZIP_Uploader {

	protected $folder = '';
	protected $filename = '';
	protected $item_folder = '';

	public function __construct( $folder) {
		$this->folder = $folder;
	}

	/**
	 * Get folder name where to upload
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	public function get_folder_name( $filename ) {
		return sanitize_title( $filename );
	}

	/**
	 * Get target path for the parent folder where all files are uploaded
	 *
	 * @return string
	 */
	public function get_target_path() {
		$upload_directory = wp_get_upload_dir();
		$upload_baseurl   = $upload_directory['basedir'];
		return trailingslashit( $upload_baseurl ) . $this->folder;
	}

	/**
	 * Get path
	 *
	 * @param $post_id
	 *
	 * @return string
	 */
	public function get_folder_path( $folder ) {
		return trailingslashit( $this->get_target_path() ) . $folder;
	}

	/**
	 * Check if there is an error
	 *
	 * @param $error
	 *
	 * @return bool|WP_Error
	 */
	public function check_error($error) {
		$file_errors = array(
			0 => __( "There is no error, the file uploaded with success", 'bifl' ),
			1 => __( "The uploaded file exceeds the upload_max_files in server settings", 'bifl' ),
			2 => __( "The uploaded file exceeds the MAX_FILE_SIZE from html form", 'bifl' ),
			3 => __( "The uploaded file uploaded only partially", 'bifl' ),
			4 => __( "No file was uploaded", 'bifl' ),
			6 => __( "Missing a temporary folder", 'bifl' ),
			7 => __( "Failed to write file to disk", 'bifl' ),
			8 => __( "A PHP extension stoped file to upload", 'bifl' ),
		);

		if ( $error > 0 ) {
			return new \WP_Error( 'file-error', $file_errors[ $error ] );
		}

		return true;
	}

	function generateUniqueFolderName($prefix = 'folder') {
		// Generate a timestamp to ensure uniqueness
		$timestamp = date('YmdHis');
	
		// Generate a random string
		$randomString = bin2hex(random_bytes(4)); // Adjust the number of bytes as needed
	
		// Combine the prefix, timestamp, and random string
		$uniqueFolderName = $prefix . '_' . $timestamp . '_' . $randomString;
	
		return $uniqueFolderName;
	}

	// sanitize files 
	function scan_dir($upload_path){
		global $wp_filesystem;
		$all_files = scandir($upload_path);
		$is_clean = true;
		foreach($all_files as $file){
			if(!$is_clean){
				break;
			}
			if($file !== '.' && $file !== '..'){
				if($wp_filesystem->is_dir($upload_path.'/'.$file) ){
					$is_clean = $this->scan_dir($upload_path.'/'.$file);
				}else {
					$_extension = pathinfo($file, PATHINFO_EXTENSION);
					if(stripos($file, '.php') || in_array($_extension, ['php', 'htaccess'])){
						$is_clean = false;
					}
				}
			}
		}
		return $is_clean;
	}

    /**
	 * Upload File
	 *
	 * @param $file
	 *
	 * @return bool|string|true|WP_Error
	 */
	public function upload( $file ) {
		/** @var $wp_filesystem \WP_Filesystem_Direct */
		global $wp_filesystem;
    
		if ( ! function_exists( 'WP_Filesystem' ) ) {
			include_once ABSPATH.'wp-admin/includes/file.php';
		}
    
		WP_Filesystem();

		$file_error = $file["file"]["error"];

		// Check for Errors
		if ( is_wp_error( $this->check_error( $file_error ) ) ) {
			return $this->check_error( $file_error );
		}
    
		$file_name       = $file["file"]["name"];
		$file_name_arr   = explode( '.', $file_name );
		$extension       = array_pop( $file_name_arr );
		$filename        = implode( '.', $file_name_arr ); // File Name
		$zip_file        = sanitize_title( $filename ) . '.' . $extension; //Our File

		$this->filename = $filename;

		if ( 'zip' !== $extension ) {
			return new WP_Error( 'no-zip', __('This does not seem to be a ZIP file', 'bifl') );
		}

		$temp_name  = $file["file"]["tmp_name"];
		$file_size  = $file["file"]["size"];

    // Get our destination folder
		$current_folder = $this->get_folder_path( $this->get_folder_name( $filename ) );

		// Get default folder that contains all zips. Create if does not exists.
		$default_target = $this->get_target_path();
    // Create our default folder if it does not exist
		if( ! file_exists( $default_target ) ){
			mkdir( $default_target );
		}

		// Get folder path
		$this->item_folder = $filename;
		$upload_path = $this->get_folder_path( $this->get_folder_name( $this->item_folder ) );
		
    // We will overwrite it all, so remove it.
		if ( $wp_filesystem->exists( $upload_path ) ) {
			// $wp_filesystem->delete( $upload_path, true );
			$numbers = range(20, 60);
			shuffle($numbers);
			$this->item_folder = $filename.'-'.$numbers[0];
			$upload_path = $this->get_folder_path( $this->get_folder_name( $this->item_folder ) );
		}

		if ( $wp_filesystem->exists( $upload_path ) ) {
			// $wp_filesystem->delete( $upload_path, true );
			$numbers = range(20, 60);
			shuffle($numbers);
			$this->item_folder = $filename.'-'.$numbers[0];
			$upload_path = $this->get_folder_path( $this->get_folder_name( $this->item_folder ) );
		}
    
		// Create it
		if ( ! $wp_filesystem->exists( $upload_path ) ) {
			$wp_filesystem->mkdir( $upload_path );
		}

		// Folder name where we will upload the ZIP
		$working_dir = $upload_path .$this->generateUniqueFolderName('_');
    
    // Delete if such folder exists
		if ( $wp_filesystem->is_dir( $working_dir ) ) {
			$wp_filesystem->delete( $working_dir, true );
		}
    // Create the folder to hold our zip file
		$wp_filesystem->mkdir( $working_dir );
		
    // Uploading ZIP file
		if( move_uploaded_file( $temp_name, $working_dir . "/" . $zip_file ) ){
			// $temp_folder = $upload_path .'/'.$this->generateUniqueFolderName('temp-');
			// Unzip the file to the upload path
			$unzip_result = unzip_file( $working_dir . "/" . $zip_file, $upload_path );

			if ( is_wp_error( $unzip_result ) ) {
				return new \WP_Error( 'not-uploaded', __( 'Something went wrong!', 'bifl' ) );
			}

			if(!$wp_filesystem->is_dir( $upload_path.'/font' )){
				$wp_filesystem->delete( $upload_path, true );
				$wp_filesystem->delete( $working_dir, true );
				return new \WP_Error( 'not-uploaded', __( 'No CSS File Found' . $working_dir, 'bifl' ) );
			}

			
			// sanitize files 
			$is_clean = $this->scan_dir( $upload_path);
			if(!$is_clean){
				$wp_filesystem->delete( $upload_path, true );
				$wp_filesystem->delete( $working_dir, true );
				return new \WP_Error( 'not-uploaded', __( 'Something went wrong!'.$is_clean, 'bifl' ) );
			}

			$files = scandir($upload_path.'/font');

			$i = 0;
			foreach($files as $file){
				$_extension = pathinfo($file, PATHINFO_EXTENSION);
				
				if($_extension == 'css' || $_extension == 'scss'){
					$i++;
				}
				
			}

			if($i == 0){
				$wp_filesystem->delete( $upload_path, true );
				$wp_filesystem->delete( $working_dir, true );
				return new \WP_Error( 'not-uploaded', __( 'No CSS File Found', 'bifl' ) );
			}

			 
			// No errors with unzips, let's delete everything and unzip it again.
			if ( $wp_filesystem->is_dir( $upload_path ) ) {
				$wp_filesystem->delete( $upload_path, true );
			}
			$wp_filesystem->mkdir( $upload_path );
			$unzip_result = unzip_file( $working_dir . "/" . $zip_file, $upload_path ); 
			

			// Remove the uploaded zip
			@unlink( $working_dir . "/" . $zip_file );
			if ( $wp_filesystem->is_dir( $working_dir ) ) {
				$wp_filesystem->delete( $working_dir, true );
			}
			if ( $wp_filesystem->is_dir( $upload_path.'/license' ) ) {
				$wp_filesystem->delete( $upload_path.'/license', true );
			}

			$upload_dir = wp_upload_dir();
			// $upload_url = trailingslashit(  site_url('wp-content/uploads/'.$this->folder.'/'.$this->item_folder.'/font'));
			$upload_url = trailingslashit( $upload_dir['baseurl'].'/'.$this->folder.'/'.$this->item_folder.'/font');

			$css_file_path = '';
			$preview_path = '';
			$status = 'active';

			$files = scandir ($upload_path.'/font');
			foreach($files as $file){

				$file_name_arr   = explode( '.', $file );
				$extension       = array_pop( $file_name_arr );				

				
				if($extension == 'css'){
					$css_file_path = trailingslashit( $upload_url ) .$file;
				}
				if($extension == 'scss' && $css_file_path == ''){
					$css_file_path = trailingslashit( $upload_url ) .$file;
				}
				if($extension == 'html'){
					$preview_path = trailingslashit( $upload_url ).$file;
				}
			}

			global $wpdb;
			$table_name = $wpdb->prefix . "iconfonts"; 
			$data = array(
				'status' => 'active', 
				'name' => $this->item_folder, 
				'iconFont' => strtolower($css_file_path), 
				'preview' => strtolower($preview_path), 
				'path' => strtolower($upload_path)
			);
			$format = array('%s','%s', '%s');
			$wpdb->insert($table_name,$data, $format);
			$my_id = $wpdb->insert_id;


			if($my_id == 0){
				$wp_filesystem->delete( $upload_path, true );
			}

			return  $upload_path;
		} else {
			return new \WP_Error( 'not-uploaded', __( 'Could not upload file', 'bifl' ) );
		}
	}
}
}