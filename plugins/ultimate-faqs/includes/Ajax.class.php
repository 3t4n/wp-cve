<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewdufaqAJAX' ) ) {
	/**
	 * Class to handle AJAX interactions for Ultimate FAQs
	 *
	 * @since 2.0.0
	 */
	class ewdufaqAJAX {

		public function __construct() { 

			add_action( 'wp_ajax_ewd_ufaq_search', array( $this, 'return_search_results' ) );
			add_action( 'wp_ajax_nopriv_ewd_ufaq_search', array( $this, 'return_search_results' ) );

			add_action( 'wp_ajax_ewd_ufaq_record_search_term', array( $this, 'save_search_term' ) );
			add_action( 'wp_ajax_nopriv_ewd_ufaq_record_search_term', array( $this, 'save_search_term' ) );

			add_action( 'wp_ajax_ewd_ufaq_reset_saved_search_terms', array( $this, 'reset_saved_search_term' ) );

			add_action( 'wp_ajax_ewd_ufaq_record_view', array( $this, 'record_view' ) );
			add_action( 'wp_ajax_nopriv_ewd_ufaq_record_view', array( $this, 'record_view' ) );

			add_action( 'wp_ajax_ewd_ufaq_update_rating', array( $this, 'update_rating' ) );
			add_action( 'wp_ajax_nopriv_ewd_ufaq_update_rating', array( $this, 'update_rating' ) );

			add_action( 'wp_ajax_ewd_ufaq_update_order', array( $this, 'update_order' ) );
		}

		/**
		 * Get the results of the FAQ search
		 * @since 2.0.0
		 */
		public function return_search_results() {
			global $ewd_ufaq_controller;

			if ( !check_ajax_referer( 'ewd-ufaq-js', 'nonce' ) ) {
				ewdufaqHelper::bad_nonce_ajax();
			}

			$faq_atts = array(
				'is_search'					=> 1,
				'search_string' 			=> '',
				'post__in' 					=> '',
				'post__in_string' 			=> '',
				'include_tag'				=> '',
				'include_category' 			=> isset( $_POST['include_category'] ) ? sanitize_text_field( $_POST['include_category'] ) : '', //so that only the matching category is displayed
				'exclude_category' 			=> '',
				'include_category_ids' 		=> '',
				'exclude_category_ids' 		=> '',
				'include_category_children'	=> '',
				'no_comments' 				=> '',
				'orderby' 					=> '',
				'order' 					=> '',
				'display_all_answers' 		=> '',
				'faq_page' 					=> 1,
				'post_count' 				=> -1
			);

			$query = new ewdufaqQuery( $faq_atts );

			$query->parse_request_args();
			$query->prepare_args();

			$faq_atts['search_string'] = isset( $_REQUEST['search_string'] ) ? sanitize_text_field( stripslashes( $_REQUEST['search_string'] ) ) : ''; 

			$faqs = new ewdufaqViewFAQs( $faq_atts );

			$faqs->set_request_parameters();

			$faqs->set_faqs( $query->get_faqs() );

			$faqs->set_faqs_options();

			$faqs->create_faq_data();

			$faqs->set_faq_properties();

			ob_start();

			$ewd_ufaq_controller->shortcode_printing = true;

			$faqs->print_faqs();

			$ewd_ufaq_controller->shortcode_printing = false;

			$output = ob_get_clean();

			$pagination = '';
			if('yes' != $faqs->show_on_load && 2 > $faqs->faq_page) {
				ob_start();
				$faqs->maybe_print_pagination();
				$pagination = ob_get_clean();
			}

			$output = $output ? $output : '<div class="ewd-ufaq-no-results-found">' . sprintf( $ewd_ufaq_controller->settings->get_setting( 'label-no-results-found' ), $query->args['search_string'] ) . '</div>';

			wp_send_json_success(
				array(
					'output' 			=> $output,
					'pagination' 	=> $pagination,
					'faq_count' 	=> $faqs->faq_count,
					'max_page'		=> $faqs->max_page,
					'request_count'	=> intval( $_POST['request_count'] )
				)
			);

		    die();
		}

		/**
		 * Records the number of time an FAQ post is opened
		 * @since 2.0.0
		 */
		public function record_view() {
			global $wpdb;

			if ( !check_ajax_referer( 'ewd-ufaq-js', 'nonce' ) ) {
				ewdufaqHelper::bad_nonce_ajax();
			}

  			$post_id = intval( $_POST['post_id'] );

  			$meta_id = $wpdb->get_var( $wpdb->prepare( "SELECT meta_id FROM $wpdb->postmeta WHERE post_id=%d AND meta_key='ufaq_view_count'", $post_id ) );
  
  			if ( $meta_id != '' and $meta_id != 0 ) { $wpdb->query( $wpdb->prepare( "UPDATE $wpdb->postmeta SET meta_value=meta_value+1 WHERE post_id=%d AND meta_key='ufaq_view_count'", $post_id ) ); }
  			else { $wpdb->query( $wpdb->prepare("INSERT INTO $wpdb->postmeta (post_id,meta_key,meta_value) VALUES (%d,'ufaq_view_count','1')", $post_id ) ); }

  			die();
		}

		/**
		 * Update the up or down rating for an FAQ
		 * @since 2.0.0
		 */
		public function update_rating() {

			if ( !check_ajax_referer( 'ewd-ufaq-js', 'nonce' ) ) {
				ewdufaqHelper::bad_nonce_ajax();
			}

			$faq_id = is_numeric( $_POST['faq_id'] ) ? intval( $_POST['faq_id'] ) : 0;
			$vote_type = sanitize_text_field( $_POST['vote_type'] );

			if ( $vote_type == 'up' ) {

				$up_votes = absint( get_post_meta( $faq_id, 'FAQ_Up_Votes', true ) );
				update_post_meta( $faq_id, 'FAQ_Up_Votes', $up_votes + 1 );

				$total_score = absint( get_post_meta( $faq_id, 'FAQ_Total_Score', true ) );
				update_post_meta( $faq_id, 'FAQ_Total_Score', $total_score + 1 );
			}

			if ( $vote_type == 'down' ) {

				$down_votes = absint( get_post_meta( $faq_id, 'FAQ_Down_Votes', true ) );
				update_post_meta( $faq_id, 'FAQ_Down_Votes', $down_votes + 1 );

				$total_score = absint( get_post_meta( $faq_id, 'FAQ_Total_Score', true ) );
				update_post_meta( $faq_id, 'FAQ_Total_Score', $total_score - 1 );
			}

			die();
		}

		/**
		 * Updates the order of the FAQ, based on the ordering table
		 * @since 2.0.0
		 */
		public function update_order() {
			global $ewd_ufaq_controller;

			if (
				! check_ajax_referer( 'ewd-ufaq-admin-js', 'nonce' ) 
				|| 
				! current_user_can( $ewd_ufaq_controller->settings->get_setting( 'access-role' ) )
			) {
				ewdufaqHelper::admin_nopriv_ajax();
			}

			if ( ! is_array( $_POST['ewd-ufaq-item'] ) or ! $ewd_ufaq_controller->permissions->check_permission( 'ordering' ) ) { return; }

    		foreach ( $_POST['ewd-ufaq-item'] as $key => $id ) {

    		    update_post_meta( intval( $id ), 'ufaq_order', intval( $key ) + 1 );
    		}

   			die();
		}

		/**
		 * Records search terms, if enabled
		 * @since 2.2.6
		 */
		public function save_search_term() {
			global $ewd_ufaq_controller;

			if ( !check_ajax_referer( 'ewd-ufaq-js', 'nonce' ) ) {
				ewdufaqHelper::bad_nonce_ajax();
			}

			if ( empty( $ewd_ufaq_controller->settings->get_setting( 'save-search-terms' ) ) ) { return; }

  			$search_term = sanitize_text_field( $_POST['search_term'] );

  			if ( empty( $search_term ) or strlen( $search_term ) < 2 ) { die(); }

  			$search_terms = get_option( 'ewd-ufaq-search-data', array() );

  			$search_terms[ $search_term ] = isset( $search_terms[ $search_term ] ) ? $search_terms[ $search_term ] + 1 : 1;

  			update_option( 'ewd-ufaq-search-data', $search_terms );

  			die();
		}

		/**
		 * Resets the saved search terms
		 * @since 2.2.6
		 */
		public function reset_saved_search_term() {
			global $ewd_ufaq_controller;

			if ( !check_ajax_referer( 'ewd-ufaq-admin-js', 'nonce' ) ) {
				ewdufaqHelper::bad_nonce_ajax();
			}

			if ( empty( $ewd_ufaq_controller->settings->get_setting( 'save-search-terms' ) ) ) { return; }

  			update_option( 'ewd-ufaq-search-data', array() );

  			die();
		}
	}
}