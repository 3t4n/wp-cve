<?php

/*
 * this class should be used to include ajax actions
 */

class Daextinma_Ajax {

	protected static $instance = null;
	private $shared = null;

	private function __construct() {

		//assign an instance of the plugin info
		$this->shared = Daextinma_Shared::get_instance();

		//ajax requests --------------------------------------------------------

		//for logged-in users --------------------------------------------------
		add_action( 'wp_ajax_update_interlinks_archive', array( $this, 'update_interlinks_archive' ) );
		add_action( 'wp_ajax_update_juice_archive', array( $this, 'update_juice_archive' ) );
		add_action( 'wp_ajax_generate_interlinks_optimization', array( $this, 'generate_interlinks_optimization' ) );
		add_action( 'wp_ajax_daextinma_generate_juice_url_modal_window_data',
			array( $this, 'daextinma_generate_juice_url_modal_window_data' ) );

	}

	/*
	 * return an istance of this class
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/*
	 * Ajax handler used to generate the interlinks archive in the "Dashboard"
	 * menu
	 */
	public function update_interlinks_archive() {

		//check the referer
		if ( ! check_ajax_referer( 'daextinma', 'security', false ) ) {
			esc_html_e( "Invalid AJAX Request", 'daext-interlinks-manager');
			die();
		}

		//check the capability
		if ( ! current_user_can( 'edit_posts' ) ) {
			esc_html_e( "Invalid Capability", 'daext-interlinks-manager');
			die();
		}

		//Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options
		$this->shared->set_met_and_ml();

		/*
		 * Create a query used to consider in the analysis only the post types
		 * selected with the 'dashboard_post_types' option
		 */
		$dashboard_post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_dashboard_post_types' ) );
		$post_types_query       = '';
		if ( is_array( $dashboard_post_types_a ) ) {
			foreach ( $dashboard_post_types_a as $key => $value ) {

				if ( ! preg_match( "/[a-z0-9_-]+/", $value ) ) {
					continue;
				}

				$post_types_query .= "post_type = '" . $value . "'";
				if ( $key != ( count( $dashboard_post_types_a ) - 1 ) ) {
					$post_types_query .= ' OR ';
				}

			}
		}

		/*
		 * get all the manual internal links and save them in the archive db
		 * table
		 */
		global $wpdb;
		$table_name           = $wpdb->prefix . "posts";
		$limit_posts_analysis = intval( get_option( $this->shared->get( 'slug' ) . '_limit_posts_analysis' ), 10 );
		$safe_sql             = "SELECT ID, post_title, post_type, post_date, post_content FROM $table_name WHERE ($post_types_query) AND post_status = 'publish' ORDER BY post_date DESC LIMIT " . $limit_posts_analysis;
		$posts_a              = $wpdb->get_results( $safe_sql, ARRAY_A );

		//delete the internal links archive database table content
		$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_archive";
		$wpdb->query( "TRUNCATE TABLE $table_name" );

		//init $archive_a
		$archive_a = array();

		foreach ( $posts_a as $key => $single_post ) {

			//set the post id
			$post_archive_post_id = $single_post['ID'];

			//get the post title
			$post_archive_post_title = $single_post['post_title'];

			//set the post type
			$post_archive_post_type = $single_post['post_type'];

			//set the post date
			$post_archive_post_date = $single_post['post_date'];

			//set the post content
			$post_content = $single_post['post_content'];

			//set the number of manual internal links
			$post_archive_manual_interlinks = $this->shared->get_manual_interlinks( $post_content );

			//set the post content length
			$post_archive_content_length = mb_strlen( trim( $post_content ) );

			//set the recommended interlinks
			$post_archive_recommended_interlinks = $this->shared->calculate_recommended_interlinks(
				$post_archive_content_length );

			//set the optimization flag
			$optimization = $this->shared->calculate_optimization( $post_archive_manual_interlinks,
				$post_archive_content_length );

			/*
			 * save data in the $archive_a array ( data will be later saved into
			 * the archive db table )
			 */
			$archive_a[] = array(
				'post_id'                => $post_archive_post_id,
				'post_title'             => $post_archive_post_title,
				'post_type'              => $post_archive_post_type,
				'post_date'              => $post_archive_post_date,
				'manual_interlinks'      => $post_archive_manual_interlinks,
				'content_length'         => $post_archive_content_length,
				'recommended_interlinks' => $post_archive_recommended_interlinks,
				'optimization'           => $optimization
			);

		}

		/*
		 * Save data into the archive db table with multiple queries of 100
		 * items each one.
		 * It's a compromise for the following two reasons:
		 * 1 - For performance, too many queries slow down the process
		 * 2 - To avoid problem with queries too long the number of inserted
		 * rows per query are limited to 100
		 */
		$table_name       = $wpdb->prefix . $this->shared->get( 'slug' ) . "_archive";
		$query_groups     = array();
		foreach ( $archive_a as $key => $single_archive ) {

			$query_index = intval( $key / 100, 10 );

			$query_groups[ $query_index ][] = $wpdb->prepare( "( %d, %s, %s, %s, %d, %d, %d, %d )",
				$single_archive['post_id'],
				$single_archive['post_title'],
				$single_archive['post_type'],
				$single_archive['post_date'],
				$single_archive['manual_interlinks'],
				$single_archive['content_length'],
				$single_archive['recommended_interlinks'],
				$single_archive['optimization']
			);

		}

		/*
		 * Each item in the $query_groups array includes a maximum of 100
		 * assigned records. Here each group creates a query and the query is
		 * executed
		 */
		$query_start = "INSERT INTO $table_name (post_id, post_title, post_type, post_date, manual_interlinks, content_length, recommended_interlinks, optimization) VALUES ";
		$query_end   = '';

		foreach ( $query_groups as $key => $query_values ) {

			$query_body = '';

			foreach ( $query_values as $single_query_value ) {

				$query_body .= $single_query_value . ',';

			}

			$safe_sql = $query_start . substr( $query_body, 0, strlen( $query_body ) - 1 ) . $query_end;

			//save data into the archive db table
			$wpdb->query( $safe_sql );

		}

		//send output
		echo 'success';
		die();

	}

	/*
	 * Ajax handler used to generate the juice archive in "Juice" menu
	 */
	public function update_juice_archive() {

		//check the referer
		if ( ! check_ajax_referer( 'daextinma', 'security', false ) ) {
			echo esc_html_e( "Invalid AJAX Request", 'daext-interlinks-manager');
			die();
		}

		//check the capability
		if ( ! current_user_can( 'edit_posts' ) ) {
			echo esc_html_e( "Invalid Capability", 'daext-interlinks-manager');
			die();
		}

		//Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options
		$this->shared->set_met_and_ml();

		//delete the juice db table content
		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_juice";
		$wpdb->query( "TRUNCATE TABLE $table_name" );

		//delete the anchors db table content
		$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_anchors";
		$wpdb->query( "TRUNCATE TABLE $table_name" );

		//update the juice archive ---------------------------------------------
		$juice_a  = array();
		$juice_id = 0;

		/*
		 * Create a query used to consider in the analysis only the post types
		 * selected with the 'juice_post_types' option
		 */
		$juice_post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_juice_post_types' ) );
		$post_types_query   = '';
		if ( is_array( $juice_post_types_a ) ) {
			foreach ( $juice_post_types_a as $key => $value ) {

				if ( ! preg_match( "/[a-z0-9_-]+/", $value ) ) {
					continue;
				}

				$post_types_query .= "post_type = '" . $value . "'";
				if ( $key != ( count( $juice_post_types_a ) - 1 ) ) {
					$post_types_query .= ' OR ';
				}

			}
		}

		/*
		 * get all the manual and auto internal links and save them in an array
		 */
		global $wpdb;
		$table_name           = $wpdb->prefix . "posts";
		$limit_posts_analysis = intval( get_option( $this->shared->get( 'slug' ) . '_limit_posts_analysis' ), 10 );
		$safe_sql             = "SELECT ID, post_title, post_type, post_date, post_content FROM $table_name WHERE ($post_types_query) AND post_status = 'publish' ORDER BY post_date DESC LIMIT " . $limit_posts_analysis;
		$posts_a              = $wpdb->get_results( $safe_sql, ARRAY_A );

		foreach ( $posts_a as $key => $single_post ) {

			//set the post content
			$post_content = $single_post['post_content'];

			//remove the HTML comments
			$post_content = $this->shared->remove_html_comments( $post_content );

			//remove script tags
			$post_content = $this->shared->remove_script_tags( $post_content );

			/*
			 * Get the website url and quote and escape the regex character. # and
			 * whitespace ( used with the 'x' modifier ) are not escaped, thus
			 * should not be included in the $site_url string
			 */
			$site_url = preg_quote( get_home_url() );

			/*
			 * find all the manual and auto interlinks matches with a regular
			 * expression and add them in the $juice_a array
			 */
			preg_match_all(
				'{<a                                #1 Begin the element a start-tag
                [^>]+                               #2 Any character except > at least one time
                href\s*=\s*                         #3 Equal may have whitespaces on both sides
                ([\'"]?)                            #4 Match double quotes, single quote or no quote ( captured for the backreference \1 )
                (' . $site_url . '[^\'">\s]* )      #5 The site URL ( Scheme and Domain ) and the rest of the URL ( Path and/or File ) ( captured )
                \1                                  #6 Backreference that matches the href value delimiter matched at line 4     
                [^>]*                               #7 Any character except > zero or more times
                >                                   #8 End of the start-tag
                (.*?)                               #9 Link text or nested tags. After the dot ( enclose in parenthesis ) negative lookbehinds can be applied to avoid specific stuff inside the link text or nested tags. Example with single negative lookbehind (.(?<!word1))*? Example with multiple negative lookbehind (.(?<!word1)(?<!word2)(?<!word3))*?
                <\/a\s*>                            #10 Element a end-tag with optional white-spaces characters before the >
                }ix',
				$post_content, $matches, PREG_OFFSET_CAPTURE );

			//save the URLs, the juice value and other info in the array
			$captures = $matches[2];
			foreach ( $captures as $key => $single_capture ) {

				//get the link position
				$link_position = $matches[0][ $key ][1];

				//save the captured URL
				$url = $single_capture[0];

				/*
				 * remove link to anchor from the URL ( if enabled through the
				 * options )
				 */
				if ( intval( get_option( $this->shared->get( 'slug' ) . '_remove_link_to_anchor' ), 10 ) == 1 ) {
					$url = $this->shared->remove_link_to_anchor( $url );
				}

				/*
				 * remove the URL parameters ( if enabled through the options )
				 */
				if ( intval( get_option( $this->shared->get( 'slug' ) . '_remove_url_parameters' ), 10 ) == 1 ) {
					$url = $this->shared->remove_url_parameters( $url );
				}

				$juice_a[ $juice_id ]['url']        = $url;
				$juice_a[ $juice_id ]['juice']      = $this->shared->calculate_link_juice( $post_content,
					$single_post['ID'], $link_position );
				$juice_a[ $juice_id ]['anchor']     = $matches[3][ $key ][0];
				$juice_a[ $juice_id ]['post_id']    = $single_post['ID'];
				$juice_a[ $juice_id ]['post_title'] = $single_post['post_title'];

				$juice_id ++;

			}

		}

		/*
		 * Save data into the anchors db table with multiple queries of 100
		 * items each one.
		 * It's a compromise for the following two reasons:
		 * 1 - For performance, too many queries slow down the process
		 * 2 - To avoid problem with queries too long the number of inserted
		 * rows per query are limited to 100
		 */
		$table_name     = $wpdb->prefix . $this->shared->get( 'slug' ) . "_anchors";
		$query_groups   = array();
		foreach ( $juice_a as $key => $single_juice ) {

			$query_index = intval( $key / 100, 10 );

			$query_groups[ $query_index ][] = $wpdb->prepare( "( %s, %s, %d, %d, %s )",
				$single_juice['url'],
				$single_juice['anchor'],
				$single_juice['post_id'],
				$single_juice['juice'],
				$single_juice['post_title']
			);

		}

		/*
		 * Each item in the $query_groups array includes a maximum of 100
		 * assigned records. Here each group creates a query and the query is
		 * executed
		 */
		$query_start = "INSERT INTO $table_name (url, anchor, post_id, juice, post_title) VALUES ";
		$query_end   = '';

		foreach ( $query_groups as $key => $query_values ) {

			$query_body = '';

			foreach ( $query_values as $single_query_value ) {

				$query_body .= $single_query_value . ',';

			}

			$safe_sql = $query_start . substr( $query_body, 0, strlen( $query_body ) - 1 ) . $query_end;

			//save data into the archive db table
			$wpdb->query( $safe_sql );

		}

		//prepare data that should be saved in the juice db table --------------
		$juice_a_no_duplicates    = array();
		$juice_a_no_duplicates_id = 0;

		/*
		 * Reduce multiple array items with the same URL to a single array item
		 * with a sum of iil and juice
		 */
		foreach ( $juice_a as $key => $single_juice ) {

			$duplicate_found = false;

			//verify if an item with this url already exist in the $juice_a_no_duplicates array
			foreach ( $juice_a_no_duplicates as $key => $single_juice_a_no_duplicates ) {

				if ( $single_juice_a_no_duplicates['url'] == $single_juice['url'] ) {
					$juice_a_no_duplicates[ $key ]['iil'] ++;
					$juice_a_no_duplicates[ $key ]['juice'] = $juice_a_no_duplicates[ $key ]['juice'] + $single_juice['juice'];
					$duplicate_found                        = true;
				}

			}

			/*
			 * if this url doesn't already exist in the array save it in
			 * $juice_a_no_duplicates
			 */
			if ( ! $duplicate_found ) {

				$juice_a_no_duplicates[ $juice_a_no_duplicates_id ]['url']   = $single_juice['url'];
				$juice_a_no_duplicates[ $juice_a_no_duplicates_id ]['iil']   = 1;
				$juice_a_no_duplicates[ $juice_a_no_duplicates_id ]['juice'] = $single_juice['juice'];
				$juice_a_no_duplicates_id ++;

			}

		}

		/*
		 * calculate the relative link juice on a scale between 0 and 100,
		 * the maximum value found corresponds to the 100 value of the
		 * relative link juice
		 */
		$max_value = 0;
		foreach ( $juice_a_no_duplicates as $key => $juice_a_no_duplicates_single ) {
			if ( $juice_a_no_duplicates_single['juice'] > $max_value ) {
				$max_value = $juice_a_no_duplicates_single['juice'];
			}
		}

		//set the juice_relative index in the array
		foreach ( $juice_a_no_duplicates as $key => $juice_a_no_duplicates_single ) {
			$juice_a_no_duplicates[ $key ]['juice_relative'] = ( 140 * $juice_a_no_duplicates_single['juice'] ) / $max_value;
		}

		/*
		 * Save data into the juice db table with multiple queries of 100
		 * items each one.
		 * It's a compromise for the following two reasons:
		 * 1 - For performance, too many queries slow down the process
		 * 2 - To avoid problem with queries too long the number of inserted
		 * rows per query are limited to 100
		 */
		$table_name                   = $wpdb->prefix . $this->shared->get( 'slug' ) . "_juice";
		$query_groups                 = array();
		foreach ( $juice_a_no_duplicates as $key => $value ) {

			$query_index = intval( $key / 100, 10 );

			$query_groups[ $query_index ][] = $wpdb->prepare( "( %s, %d, %d, %d )",
				$value['url'],
				$value['iil'],
				$value['juice'],
				$value['juice_relative']
			);

		}

		/*
		 * Each item in the $query_groups array includes a maximum of 100
		 * assigned records. Here each group creates a query and the query is
		 * executed
		 */
		$query_start = "INSERT INTO $table_name (url, iil, juice, juice_relative) VALUES ";
		$query_end   = '';

		foreach ( $query_groups as $key => $query_values ) {

			$query_body = '';

			foreach ( $query_values as $single_query_value ) {

				$query_body .= $single_query_value . ',';

			}

			$safe_sql = $query_start . substr( $query_body, 0, strlen( $query_body ) - 1 ) . $query_end;

			//save data into the archive db table
			$wpdb->query( $safe_sql );

		}

		//send output
		return 'success';
		die();

	}

	/*
	 * Ajax handler used to generate the content of the "Interlinks Optimization" meta box.
	 */
	public function generate_interlinks_optimization() {

		//check the referer
		if ( ! check_ajax_referer( 'daextinma', 'security', false ) ) {
			echo esc_html_e( "Invalid AJAX Request", 'daext-interlinks-manager');
			die();
		}

		//check the capability
		if ( ! current_user_can( 'edit_posts' ) ) {
			echo esc_html_e( "Invalid Capability", 'daext-interlinks-manager');
			die();
		}

		//get data
		$post_id = intval( $_POST['post_id'], 10 );

		//generate the HTML of the meta-box
		$output = $this->shared->generate_interlinks_optimization_metabox_html( get_post( $post_id ) );

		//send the output
		echo $output;
		die();

	}

	/*
	 * Ajax handler used to generate the modal window used to display and browse the anchors associated with a specific
	 * url.
	 *
	 * This method is called when in the "Juice" menu one of these elements is clicked:
	 * - The modal window icon associate with a specific URL
	 * - One of the pagination links included in the modal window
	 */
	public function daextinma_generate_juice_url_modal_window_data() {

		//check the referer
		if ( ! check_ajax_referer( 'daextinma', 'security', false ) ) {
			echo esc_html( "Invalid AJAX Request", 'interlinks-manager' );
			die();
		}

		//check the capability
		if ( ! current_user_can( 'edit_posts' ) ) {
			echo esc_html( 'Invalid Capability', 'interlinks-manager' );
			die();
		}

		//Init Variables
		$data      = [];
		$juice_max = 0;

		//Sanitize Data
		$juice_id     = intval( $_POST['juice_id'], 10 );
		$current_page = intval( $_POST['current_page'], 10 );

		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_juice";
		$safe_sql   = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $juice_id );
		$juice_obj  = $wpdb->get_row( $safe_sql, OBJECT );

		//URL ----------------------------------------------------------------------------------------------------------
		$data['url'] = $juice_obj->url;

		//Pagination ---------------------------------------------------------------------------------------------------

		//Initialize pagination class
		require_once( $this->shared->get( 'dir' ) . '/admin/inc/class-daextinma-pagination-ajax.php' );
		$pag = new Daextinma_Pagination_Ajax();
		$pag->set_total_items( $this->shared->get_anchors_with_url( $juice_obj->url ) );//Set the total number of items
		$pag->set_record_per_page( 10 ); //Set records per page
		$pag->set_current_page( $current_page );//set the current page number from $_GET
		$query_limit = $pag->query_limit();

		//Generate the pagination html
		$data['pagination'] = $pag->getData();

		//Save the total number of items
		$data['total_items'] = $pag->total_items;

		//Body ---------------------------------------------------------------------------------------------------------

		//Get the maximum value of the juice
		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_anchors";
		$safe_sql   = $wpdb->prepare( "SELECT * FROM $table_name WHERE url = %s ORDER BY id ASC", $juice_obj->url );
		$results    = $wpdb->get_results( $safe_sql, ARRAY_A );

		if ( count( $results ) > 0 ) {

			//Calculate the maximum value
			foreach ( $results as $result ) {
				if ( $result['juice'] > $juice_max ) {
					$juice_max = $result['juice'];
				}
			}

		} else {

			echo 'no data';
			die();

		}

		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_anchors";
		$safe_sql   = $wpdb->prepare( "SELECT * FROM $table_name WHERE url = %s ORDER BY juice DESC $query_limit",
			$juice_obj->url );
		$results    = $wpdb->get_results( $safe_sql, ARRAY_A );

		if ( count( $results ) > 0 ) {

			foreach ( $results as $result ) {

				$data['body'][] = [
					'postTitle'     => $result['post_title'],
					'juice'         => intval( $result['juice'], 10 ),
					'juiceVisual'   => intval( 140 * $result['juice'] / $juice_max, 10 ),
					'anchor'        => $result['anchor'],
					'postId'        => intval( $result['post_id'], 10 ),
					'postPermalink' => get_permalink( $result['post_id'] )
				];

			}

		} else {

			echo esc_html_e( 'no data', 'daext-interlinks-manager');
			die();

		}

		//Return respose
		echo json_encode( $data );
		die();

	}

}