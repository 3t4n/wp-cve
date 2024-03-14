<?php

/*
 * This class should be used to include ajax actions.
 */

class daexthefu_Ajax {

	protected static $instance = null;
	private $shared = null;

	private function __construct() {

		//Assign an instance of the plugin info
		$this->shared = daexthefu_Shared::get_instance();

		//Ajax requests for logged-in users ----------------------------------------------------------------------------
		add_action( 'wp_ajax_daexthefu_save_feedback', array( $this, 'save_feedback' ) );
		add_action( 'wp_ajax_nopriv_daexthefu_save_feedback', array( $this, 'save_feedback' ) );

		//for logged-in users --------------------------------------------------
		add_action( 'wp_ajax_daexthefu_update_feedback_archive', array( $this, 'update_feedback_archive' ) );
		add_action( 'wp_ajax_daexthefu_generate_post_data_modal_window_data',
			array( $this, 'generate_post_data_modal_window_data' ) );

	}

	/*
	 * Return an instance of this class
	 */
	public static function get_instance() {

		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	/**
	 * Ajax handler used to receive the feedback from the form displayed in the front-end.
	 */
	public function save_feedback() {

		//check the referer
		check_ajax_referer( 'daexthefu', 'security' );

		//Init
		$response     = [];
		$query_result = false;

		//Sanitization
		$value = (isset( $_POST['value'] ) and intval( $_POST['value'], 10 ) === 1) ? 1 : 0;
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'], 10 ) : 0;
		$comment = isset( $_POST['comment'] ) ? sanitize_textarea_field( $_POST['comment'] ) : '';

		/**
		 * Feedback with a non-existing post_id should not be saved. Note: get_post_status() is used to check the
		 * existence of the post.
		 */
		if ( get_post_status( $post_id ) !== false ) {

			//get the current date
			$current_time = current_time( 'mysql' );

			//get the ip address
			if ( intval( get_option( $this->shared->get( 'slug' ) . "_unique_submission" ), 10 ) === 2 or
			     intval( get_option( $this->shared->get( 'slug' ) . "_unique_submission" ), 10 ) === 3 ) {
				$ip_address = $this->shared->get_ip_address();
			} else {
				$ip_address = '';
			}

			//save the feedback
			global $wpdb;
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_feedback";
			$safe_sql   = $wpdb->prepare( "INSERT INTO $table_name SET 
                    date = %s,
                    post_id = %d,
                    value = %d,
                    description = %s,
                    ip_address = %s",
				$current_time,
				$post_id,
				$value,
				$comment,
				$ip_address
			);

			$query_result = $wpdb->query( $safe_sql );

		}

		if ( $query_result !== false ) {
			$response['valid']       = true;
			$response['feedback_id'] = $wpdb->insert_id;
		} else {
			$response['valid']       = false;
			$response['feedback_id'] = 0;
		}

		echo json_encode( $response );
		die();

	}

	/*
	 * Ajax handler used to generate feedback archive displayed in the "Statistics" menu.
	 */
	public function update_feedback_archive() {

		//check the referer
		if ( ! check_ajax_referer( 'daexthefu', 'security', false ) ) {
			echo "Invalid AJAX Request";
			die();
		}

		//check the capability
		if ( ! current_user_can( get_option( $this->shared->get( 'slug' ) . "_statistics_menu_capability" ) ) ) {
			echo "Invalid Capability";
			die();
		}

		//Set the PHP "Max Execution Time" and "Memory Limit" based on the values defined in the options
		$this->shared->set_met_and_ml();

		//delete the feedback archive database table content
		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_archive";
		$result     = $wpdb->query( "TRUNCATE TABLE $table_name" );

		//Get the list of post types where the analysis should be applied
		$post_types_a = maybe_unserialize( get_option( $this->shared->get( 'slug' ) . '_analysis_post_types' ) );

		//Add query parts with the post types option
		$counter    = 0;
		$post_types_query = '';

		foreach ( $post_types_a as $post_type ) {

			if ( $counter > 0 ) {
				$post_types_query .= ' OR ';
			}

			$post_types_query .= "post_type = '" . $post_type . "'";
			$counter ++;

		}

		//Get the posts analysis limit
		$limit_posts_analysis = intval( get_option( $this->shared->get( 'slug' ) . '_limit_posts_analysis' ), 10 );

		//Iterate over all the posts
		$table_name = $wpdb->prefix . "posts";
		$safe_sql   = "SELECT ID, post_title, post_type, post_date, post_content FROM $table_name WHERE (" . $post_types_query . ") AND post_status = 'publish' ORDER BY post_date DESC LIMIT " . $limit_posts_analysis;
		$posts_a    = $wpdb->get_results( $safe_sql, ARRAY_A );

		foreach ( $posts_a as $single_post ) {

			//calculate the number of positive feedbacks
			$table_name        = $wpdb->prefix . $this->shared->get( 'slug' ) . "_feedback";
			$safe_sql          = $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE post_id = %d AND value = 1",
				$single_post['ID'] );
			$positive_feedback = intval( $wpdb->get_var( $safe_sql ), 10 );

			//calculate the number of negative feedbacks
			$table_name        = $wpdb->prefix . $this->shared->get( 'slug' ) . "_feedback";
			$safe_sql          = $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE post_id = %d AND value = 0",
				$single_post['ID'] );
			$negative_feedback = intval( $wpdb->get_var( $safe_sql ), 10 );

			//Calculate the pfr indicator
			$pfr = - 1;
			if ( ( $positive_feedback + $negative_feedback ) > 0 ) {
				$pfr = intval( ( $positive_feedback / ( $positive_feedback + $negative_feedback ) ) * 100, 10 );
			}

			//save the calculated data and the post data in the archive database table
			$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_archive";
			$safe_sql   = $wpdb->prepare( "INSERT INTO $table_name SET 
                    post_id = %d,
                    post_title = %s,
                    post_type = %s,
                    post_date = %s,
                    positive_feedback = %d,
                    negative_feedback = %d,
                    pfr = %d",
				$single_post['ID'],
				$single_post['post_title'],
				$single_post['post_type'],
				$single_post['post_date'],
				$positive_feedback,
				$negative_feedback,
				$pfr
			);

			$query_result = $wpdb->query( $safe_sql );

		}

		//send output
		echo 'success';
		die();

	}

	/*
	 * Ajax handler used to generate the modal window used to display and browse the single feedback.
	 *
	 * This method is called when in the "Statistics" menu one of these elements is clicked:
	 *
	 * - The modal window icon associated with a specific URL
	 * - One of the pagination links included in the modal window
	 */
	public function generate_post_data_modal_window_data() {

		//check the referer
		if ( ! check_ajax_referer( 'daexthefu', 'security', false ) ) {
			echo "Invalid AJAX Request";
			die();
		}

		//check the capability
		if ( ! current_user_can( get_option( $this->shared->get( 'slug' ) . "_statistics_menu_capability" ) ) ) {
			echo "Invalid Capability";
			die();
		}

		//Init Variables
		$data      = [];

		//Sanitize Data
		$post_id      = intval( $_POST['post_id'], 10 );
		$current_page = intval( $_POST['current_page'], 10 );

		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_feedback";
		$safe_sql   = $wpdb->prepare( "SELECT * FROM $table_name WHERE post_id = %d", $post_id );
		$feedback_a = $wpdb->get_results( $safe_sql, ARRAY_A );

		//Pagination ---------------------------------------------------------------------------------------------------

		//Initialize pagination class
		require_once( $this->shared->get( 'dir' ) . '/admin/inc/class-daexthefu-pagination-ajax.php' );
		$pag = new Daexthefu_Pagination_Ajax();
		$pag->set_total_items( count( $feedback_a ) );//Set the total number of items
		$pag->set_record_per_page( 10 ); //Set records per page
		$pag->set_current_page( $current_page );//set the current page number from $_GET
		$query_limit = $pag->query_limit();

		//Generate the pagination html
		$data['pagination'] = $pag->getData();

		//Save the total number of items
		$data['total_items'] = $pag->total_items;

		$data['title'] = get_the_title( $post_id );

		//Body ---------------------------------------------------------------------------------------------------------

		global $wpdb;
		$table_name = $wpdb->prefix . $this->shared->get( 'slug' ) . "_feedback";
		$safe_sql   = $wpdb->prepare( "SELECT * FROM $table_name WHERE post_id = %d ORDER BY date DESC $query_limit",
			$post_id );
		$result_a   = $wpdb->get_results( $safe_sql, ARRAY_A );

		if ( count( $result_a ) > 0 ) {

			foreach ( $result_a as $result ) {

				$data['body'][] = [
					'date'        => wp_date( get_option('date_format') . ' ' . get_option('time_format'), strtotime( $result['date'] ) ),
					'title'       => get_the_title( $result['post_id'] ),
					'value'       => intval( $result['value'], 10 ),
					'description' => stripslashes( $result['description'] ),
				];

			}

		} else {

			echo 'no data';
			die();

		}

		//Return respose
		echo json_encode( $data );
		die();

	}

}