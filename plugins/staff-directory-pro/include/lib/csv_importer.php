<?php
class StaffDirectoryPlugin_Importer
{
	var $root;
	var $last_error = '';
	var $records_imported = 0;
	static $csv_headers = array('Full Name','Body','First Name','Last Name','Title','Phone','Email','Address','Website','Categories','Photo');
	
    public function __construct($root)
    {
		$this->root = $root;
	}	

	public static function get_csv_headers()
	{
		return self::$csv_headers;
	}

	public static function output_form()
	{
		//echo '<form method="POST" action="" enctype="multipart/form-data">';
		
		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) )
				require_once $class_wp_importer;
		}
		
		// output CSV import form
		echo "<h1>Import From Clipboard</h1>";
		echo "<p>You can also import your CSV by simply copying and pasting below. If you are experiencing a time out, try breaking up the rows into batches of 100 rows.</p>";
		echo "<p><strong>Staff Member Import supports Photos!</strong> If you include the path to a photo, that is available online, in the Photo column of your CSV we will attempt to upload and attach it to the Staff Member.</p>";

		$action = add_query_arg('step', 1);
		echo '<div class="gp_upload_file_wrapper">';
			printf ( '<div data-gp-ajax-form="1" data-ajax-submitx="1" action="%s" method="POST">', $action);
			echo '<input type="hidden" name="_company_dir_do_direct_import" value="_company_dir_do_direct_import" />';					
			echo '<textarea style="resize:both" name="csv_data" rows="14" cols="150" ></textarea>';
			echo '<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Import Pasted CSV data" type="submit"></p>';
			echo '</div>';
		echo '</div>';
		
		//echo '</form>';
	}
	
	public function process_import()
	{
		$errors = array();
		
		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';

		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) )
				require_once $class_wp_importer;
		}		
		
		// upload via file
		if(!empty($_FILES))
		{
			$file = wp_import_handle_upload();

			if ( isset( $file['error'] ) ) {
				$this->last_error = sprintf('<p><strong>Sorry, there has been an error.</strong><br />%s</p>', esc_html( $file['error'] ));
				return false;
			} else if ( ! file_exists( $file['file'] ) ) {
				$err_msg = sprintf('The export file could not be found at <code>%s</code>. It is likely that this was caused by a permissions problem.', esc_html( $file['file'] ) );
				$this->last_error = sprintf('<p><strong>Sorry, there has been an error.</strong><br />%s</p>', $err_msg );
				return false;
			}
			
			$file_id = (int) $file['id'];
			$file_name = get_attached_file($file_id);
			
			if (file_exists($file_name)) {
				$result = $this->import_posts_from_csv($file_name);
			} else {
				$this->last_error = sprintf('<p><strong>Sorry, there has been an unknown error. Please try again.</strong></p>');
				return false;
			}			
		}
		//upload via textarea
		else if ( !empty($_POST['csv_data']) ) {
			$result = $this->import_csv_from_post('csv_data');
		}
		//upload via ajax-form
		else if ( !empty($_POST['data_json']) ) {
			$json = $this->get_json_data_from_post('data_json', true);		
			if ( !empty($json) ) {
				$result = $this->import_csv_from_json($json);
			}
		}
		
		// all worked!
		return $result;
	}
	
	//process data from CSV import
	private function import_posts_from_csv($posts_file)
	{
		//increase execution time before beginning import, as this could take a while
		set_time_limit(0);		
		
		$posts = $this->csv_to_array($posts_file);
		$import_result = $this->import_posts_array($posts);
		return $import_result;
	}

	private function combine_row_with_headers($row)
	{
		$row = array_pad( $row, count(self::$csv_headers), "" );
		$row = array_combine(self::$csv_headers, $row);
		return $row;
	}
	
	/* 
	 * Process data from POST field 
	 *
	 * @param string $post_key The POST field  which contains the CSV data
	 * @param bool $skip_first_row Set to true if the first row contains the 
	 * 							   CSV headers, andshould be skipped. 
	 * 							   Default: false.
	 */
	private function import_csv_from_post($post_key = 'csv', $skip_first_row = false)
	{
		//increase execution time before beginning import, as this could take a while
		set_time_limit(0);		
		$posts = $this->get_csv_data_from_post($post_key, $skip_first_row);
		$posts = array_map( array($this, 'combine_row_with_headers'), $posts);
		$import_result = $this->import_posts_array($posts);
		return $import_result;
	}
	
	/* 
	 * Process data via AJAX from POST field 
	 *
	 * @param array $json Array of rows, each one is a record to import
	 *
	 * @return array $import_result Array of information about the import
	 *							    (number of successful rows imported, 
	 * 							    duplicates, etc)
	 */
	private function import_csv_from_json($json)
	{
		//increase execution time before beginning import, as this could take a while
		set_time_limit(0);	
		$posts = array_map( array($this, 'combine_row_with_headers'), $json);
		$import_result = $this->import_posts_array($posts);
		return $import_result;
	}
	
	private function replace_new_lines_inside_quotes($text)
	{
		return preg_replace_callback('~"[^"]+"~', array($this, 'replace_new_lines_with_escaped_version'), $text);
	}
	
	private function replace_new_lines_with_escaped_version($m)
	{
		return preg_replace('~\r?\n~', '\n', $m[0]);
	}
	
	/* 
	 * Get JSON data from POST data
	 *
	 * @param string $post_key The POST field  which contains the JSON data
	 * @param bool $skip_first_row Set to true if the first row contains the 
	 * 							   JSON headers, andshould be skipped. 
	 * 							   Default: false.
	 * @returns array The posted JSON data, or empty array if no JSON data found.
	 */
	private function get_json_data_from_post($post_key = 'data_json', $skip_first_row = false)
	{		
		$json = filter_input(INPUT_POST, $post_key);
		
		if ( empty($json) ) {
			return array();
		}
		
		$json = json_decode($json, true);
		
		if ( $skip_first_row ) {
			array_shift($json);
		}

		return !empty($json)
			   ? $json
			   : array();
	}
	
	/* 
	 * Get CSV data from POST data
	 *
	 * @param string $post_key The POST field  which contains the CSV data
	 * @param bool $skip_first_row Set to true if the first row contains the 
	 * 							   CSV headers, andshould be skipped. 
	 * 							   Default: false.
	 * @returns array The posted CSV data, or empty array if no CSV data found.
	 */
	private function get_csv_data_from_post($post_key = 'csv', $skip_first_row = false)
	{		
		$csv_data = trim ( filter_input(INPUT_POST, $post_key) );
		if ( empty($csv_data) ) {
			return array();
		}
		
		$csv_data = $this->replace_new_lines_inside_quotes($csv_data);		
		$exploded = explode("\n", $csv_data );
		$csv_rows = array_map( 'str_getcsv', $exploded );
		
		if ( $skip_first_row ) {
			array_shift($csv_rows);
		}
	
		return !empty($csv_rows)
			   ? $csv_rows
			   : array();
	}
	
	private function import_posts_array($posts)
	{
		$messages = array();
		$success_count = 0;
		$fail_count = 0;
		$batch_id = strtotime('U');

		foreach($posts as $post)
		{
			// title and body are always required
			$full_name = isset($post['Full Name']) ? $post['Full Name']  : '';
			$the_body = isset($post['Body']) ? $post['Body']  : '';
			
			// look for a staff member with the same full name, to prevent duplicates
			$find_dupe = get_page_by_title( $full_name, OBJECT, 'staff-member' );
			
			// if no one with that name was found, continue with inserting the new staff member
			if( empty($find_dupe) )
			{
				$new_post = array(
					'post_title'    => $full_name,
					'post_content'  => $the_body,
					'post_status'   => 'publish',
					'post_type'     => 'staff-member'
				);
				
				$new_id = wp_insert_post($new_post);

				// assign Staff Member Categories if any were specified
				// NOTE: we are using wp_set_object_terms instead of adding a tax_input key to wp_insert_posts, because 
				// it is less likely to fail b/c of permissions and load order (i.e., taxonomy may not have been created yet)
				if (!empty($post['Categories'])) {
					$post_cats = explode(',', $post['Categories']);
					$post_cats = array_map('intval', $post_cats); // sanitize to ints
					wp_set_object_terms($new_id, $post_cats, 'staff-member-category');
				}
				
				// Save the custom fields. Default everything to empty strings
				$first_name = isset($post['First Name']) ? $post['First Name'] : '';
				$last_name = isset($post['Last Name']) ? $post['Last Name'] : '';
				$title = isset($post['Title']) ? $post['Title'] : "";
				$phone = isset($post['Phone']) ? $post['Phone'] : "";
				$email = isset($post['Email']) ? $post['Email'] : "";
				$address = isset($post['Address']) ? $post['Address'] : "";
				$website = isset($post['Website']) ? $post['Website'] : "";
								
				update_post_meta( $new_id, '_import_batch_id', $batch_id );
				update_post_meta( $new_id, '_ikcf_first_name', $first_name );
				update_post_meta( $new_id, '_ikcf_last_name', $last_name );
				update_post_meta( $new_id, '_ikcf_title', $title );
				update_post_meta( $new_id, '_ikcf_phone', $phone );
				update_post_meta( $new_id, '_ikcf_email', $email );
				update_post_meta( $new_id, '_ikcf_address', $address );
				update_post_meta( $new_id, '_ikcf_website', $website );
				
				// Look for a photo path on CSV
				// If found, try to import this photo and attach it to this staff member
				$this->import_staff_photo($new_id, $post['Photo']);				
				
				// Successfully added the post! Update success_count and continue.
				$messages[] = sprintf("Successfully imported '%s!'", $full_name);
				$success_count++;
			}
			else {
				// Rejected as duplicate. Update fail_count and continue.
				$messages[] = sprintf("Could not import '%s'; rejected as duplicate.", $full_name);
				$fail_count++;				
			}
		}
		
		return array(
			'imported' => $success_count,
			'failed' => $fail_count,
			'messages' => $messages,
			'batch_id' => $batch_id,
		);		
	}
	
	function import_staff_photo($post_id = '', $photo_source = ''){	
		//used for overriding specific attributes inside media_handle_sideload
		$post_data = array();
		
		//set attributes in override array
		$post_data = array(
			'post_title' => '', //photo title
			'post_content' => '', //photo description
			'post_excerpt' => '', //photo caption
		);
	
		require_once( ABSPATH . 'wp-admin/includes/image.php');
		require_once( ABSPATH . 'wp-admin/includes/media.php' );//need this for media_handle_sideload
		require_once( ABSPATH . 'wp-admin/includes/file.php' );//need this for the download_url function
		
		$desc = ''; // photo description
		
		$picture = urldecode($photo_source);
		
		// Download file to temp location
		$tmp = download_url( $picture);
		
		// Set variables for storage
		// fix file filename for query strings
		preg_match('/[^\?]+\.(jpg|JPG|jpe|JPE|jpeg|JPEG|gif|GIF|png|PNG)/', $picture, $matches);
		$file_array['name'] = isset($matches[0]) ? basename($matches[0]) : basename($picture);
		$file_array['tmp_name'] = $tmp;

		// If error storing temporarily, unlink
		if ( is_wp_error( $tmp ) ) {
			//$error_string = $tmp->get_error_message();
			//echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
			
			@unlink($file_array['tmp_name']);
			$file_array['tmp_name'] ='';
		}
		
		$id = media_handle_sideload( $file_array, $post_id, $desc, $post_data );
		
		// If error storing permanently, unlink
		if ( is_wp_error($id) ) {
			//$error_string = $id->get_error_message();
			//echo '<div id="message" class="error"><p>' . $error_string . '</p></div>';
			
			@unlink($file_array['tmp_name']);
		} else {		
			//add as the post thumbnail
			if( !empty($post_id) ){
				add_post_meta($post_id, '_thumbnail_id', $id, true);
			}
		}
	}
	
	//convert CSV to array
	private function csv_to_array($filename='', $delimiter=','){
		if(!file_exists($filename) || !is_readable($filename))
			return FALSE;

		$header = NULL;
		$data = array();
		
		if (($handle = fopen($filename, 'r')) !== FALSE)
		{
			while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE)
			{
				if(!$header){
					$header = $row;
				} else {
					if (count($header) == count($row)) {
						$data[] = array_combine($header, $row);
					}
				}
			}
			fclose($handle);
		}
		return $data;
	}
	
}