<?php
class StaffDirectoryPlugin_Exporter
{	
	function __construct()
	{
		$this->csv_headers = $this->get_export_headers();
	}

	function get_export_headers()
	{
		$default_fields = array(
			'Full Name',
			'Body',
			'First Name',
			'Last Name',
			'Title',
			'Phone',
			'Email',
			'Address',
			'Website',
			'Departments',
			'Photo'
		);

		$custom_fields = get_option('company_directory_gp_custom_fields', false);
		if ( empty($custom_fields) ) {
			return $default_fields;
		}		
		
		// generate new list from custom fields
		// always starts with Full Name and Body, 
		// always ends with Categories and Photo
		$fields_to_export = array(
			'Full Name',
			'Body'		
		);
		foreach($custom_fields as $cf) {
			if ( !empty($cf['title']) ) {
				$fields_to_export[] = $cf['title'];
			}
		}
		$fields_to_export[] = 'Categories';
		$fields_to_export[] = 'Photo';
		return $fields_to_export;
	}
	
	function get_custom_fields_to_export()
	{
		$default_fields = array(
			'First Name' => '_ikcf_first_name',
			'Last Name' => '_ikcf_last_name',
			'Title' => '_ikcf_title',
			'Phone' => '_ikcf_phone',
			'Email' => '_ikcf_email',
			'Address' => '_ikcf_address',
			'Website' => '_ikcf_website',
		);

		$custom_fields = get_option('company_directory_gp_custom_fields', false);
		if ( empty($custom_fields) ) {
			return $default_fields;
		}		
		
		// generate new list from custom fields
		// always starts with Full Name and Body, 
		// always ends with Categories and Photo
		$fields = array();
		foreach($custom_fields as $cf) {
			if ( !empty($cf['title']) ) {
				$key = '_ikcf_' . $cf['name'];
				$fields[ $cf['title'] ] = $key;
			}
		}
		return $fields;
	}
	
	public static function get_csv_headers()
	{
		return $csv_headers;
	}

	public static function output_form()
	{
		?>
		<form method="POST" action="">
			<p>Click the "Export Staff Members" button below to download a CSV file of your records.</p>			
			<input type="hidden" name="_company_dir_do_export" value="_company_dir_do_export" />
			<p><strong>Tip:</strong> You can use this export file as a template to import your own staff members.</p>			
			<p class="submit">
				<input type="submit" class="button" value="Export Staff Members" />
			</p>
		</form>
		<?php
	}
	
	/* Renders a CSV file to STDOUT representing every staff member in the database
	 * NOTE: this file is, and must remain, compatible with the Importer
	 */
	public function process_export($filename = "export.csv")
	{		
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header('Content-Description: File Transfer');
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename={$filename}");
		header("Expires: 0");
		header("Pragma: public");
		
		// set memory limit to high value (4GB) and remove time limit, to allow 
		// for large (20k+) exports. this still might not accommodate all users.
		@ini_set( 'memory_limit', apply_filters( 'admin_memory_limit', '4096M' ) );
		set_time_limit(0);
		
		// open file handle to STDOUT
		$fh = @fopen( 'php://output', 'w' );
		
		// output the headers first
		fputcsv($fh, $this->csv_headers);
			
		$page = 1;
		$posts = $this->get_posts_paged(100, $page);
		
		while ( !empty($posts) ) {
		
			// now output one row for each staff member
			foreach($posts as $post) {
				$row = array();
				$all_meta = get_metadata('post', $post->ID);
				
				// start row with full name and body
				$row['full_name'] = $post->post_title;
				$row['body'] = $post->post_content;
				
				// add custom fields in the middle of the row
				$custom_fields = $this->get_custom_fields_to_export();
				foreach( $custom_fields as $cf_title => $cf_key ) {
					$row[$cf_title] = !empty( $all_meta[$cf_key] ) 
									  ? $all_meta[$cf_key][0]
									  : $cf_title;
				}
				
				// finish row with categories and photo
				$row['categories'] = $this->list_taxonomy_ids( $post->ID, 'staff-member-category' );	
				$row['photo'] = $this->get_photo_path( $post->ID );
				
				fputcsv($fh, $row);
				ob_flush();
				flush();
			}
			$posts = null;
			ob_flush();
			flush();			
			$page++;
			$posts = $this->get_posts_paged(10, $page);
		}
		
		// Close the file handle
		fclose($fh);
	}
	
	function get_posts_paged($posts_per_page = 100, $page_number = 1)
	{
		//load records
		$args = array(
			'posts_per_page'   	=> $posts_per_page,
			'paged'   			=> $page_number,
			'orderby'          	=> 'post_date',
			'order'            	=> 'ASC',
			'post_type'        	=> 'staff-member',
			'post_status'      	=> 'publish',
			'suppress_filters' 	=> true 				
		);
		return get_posts($args);		
	}
	
	/*
	 * Get the path to the staff member's photo
	 *
	 * @returns a string representing the path to the photo
	*/
	function get_photo_path($post_id){
		$image_str = "";
		
		if (has_post_thumbnail( $post_id ) ){
			$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'single-post-thumbnail' );
			$image_str = $image[0];
		}
		
		return $image_str;
	}
	
	/* 
	 * Get a comma separated list of IDs representing each term of $taxonomy that $post_id belongs to
	 *
	 * @returns comma separated list of IDs, or empty string if no terms are assigned
	*/
	function list_taxonomy_ids($post_id, $taxonomy)
	{
		$terms = wp_get_post_terms( $post_id, $taxonomy ); // could also pass a 3rd param, $args
		if (is_wp_error($terms)) {
			return '';
		}
		else {
			$term_list = array();
			foreach ($terms as $t) {
				$term_list[] = $t->term_id;
			}
			return implode(',', $term_list);
		}
	}
}