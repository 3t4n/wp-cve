<?php

defined( 'ABSPATH' ) || exit();

class EPHD_Search {

	const SEARCH_INPUT_LENGTH = 200;  // let's limit the input string

	public function __construct() {
		add_action( 'wp_ajax_ephd_search', array( 'EPHD_Search', 'search' ) );
		add_action( 'wp_ajax_nopriv_ephd_search', array( 'EPHD_Search', 'search' ) );   // users not logged in should be able to search as well

		add_action( 'wp_ajax_ephd_get_post_content', array( 'EPHD_Search', 'get_post_content' ) );
		add_action( 'wp_ajax_nopriv_ephd_get_post_content', array( 'EPHD_Search', 'get_post_content' ) );   // users not logged in should be able to search as well
	}

	/**
	 * Search Help dialog KB for articles within defined categories
	 *
	 * @param bool $is_front - set current screen to ensure links have https if needed
	 */
	public static function search( $is_front=true ) {

		// remove question marks
		$search_input = EPHD_Utilities::get( 'search_terms', '', 'text', self::SEARCH_INPUT_LENGTH );
		$search_terms = stripslashes( $search_input );
		$search_terms = str_replace( '?', '', $search_terms );
		$search_terms = str_replace( array( "\r", "\n" ), '', $search_terms );

		// retrieve Widget id
		$widget_id = (int)EPHD_Utilities::post( 'widget_id', EPHD_Config_Specs::DEFAULT_ID );

		// retrieve Page id
		$page_id = (int)EPHD_Utilities::post( 'page_id', 0 );

		// retrieve search record flag
		$record_search = (int)EPHD_Utilities::post( 'record_search', 0 );

		// retrieve Global configuration
		$global_config = ephd_get_instance()->global_config_obj->get_config();

		// retrieve Widgets configuration
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config();

		// use either specified Design or default Design if the Widget does not exist
		$widget = empty( $widgets_config[$widget_id] ) ? $widgets_config[EPHD_Config_Specs::DEFAULT_ID] : $widgets_config[$widget_id];

		$no_results_sc = '<div class="ephd-hd-no-results__img"><img alt="' . esc_attr__( 'No FAQs Defined', 'help-dialog' ) . '" src="' . esc_url( Echo_Help_Dialog::$plugin_url . 'img/no-faqs-defined.jpeg' ) . '" ></div>' .
						'<div class="ephd-hd__search_results__no-results-container"> ' .
							'<div class="ephd-hd-no-results__msg">' . esc_html( $widget['no_results_found_title_text'] ) . '</div>' .
							'<div class="ephd-hd-no-results__keywords"></div>' .
							'<div class="ephd-hd-no-results__hints">' .
		                        wp_kses_post( wp_unslash( $widget['no_results_found_content_html'] ) ) .
							'</div>' .
						'</div>' .
						'<div class="ephd-hd-no-results__contact-us">' .
							'<button class="ephd-hd__contact-us__link" data-ephd-target-tab="contact">' . wp_kses_post(  wp_unslash( $widget['no_result_contact_us_text'] ) ) . '</button>' .
						'<div>';

		// require minimum size of search word(s)
		if ( empty( $search_terms ) ) {
			wp_die( wp_json_encode( array( 'status' => 'success', 'no_results' => $no_results_sc ) ) );
		}

		// require widget
		if ( empty( $widgets_config[$widget_id] ) ) {
			wp_die( wp_json_encode( array( 'status' => 'success', 'no_results' => $no_results_sc ) ) );
		}

		/**
		 * FAQs - search for given keyword(s)
		 */
		$faqs_search_results = self::search_in_faqs( $widget, $search_terms );
		$faqs_search_count = empty( $faqs_search_results ) ? 0 : count( $faqs_search_results );
		$faqs_search_html  = empty( $faqs_search_results ) ? '' : implode( '', $faqs_search_results );

		/**
		 * KBs - search for given keyword(s)
		 */
		$articles_search_results = self::search_in_kbs( $widget, $search_terms );
		$articles_search_count = empty( $articles_search_results ) ? 0 : count( $articles_search_results );
		$articles_search_html  = empty( $articles_search_results ) ? '' : implode( '', $articles_search_results );

		/**
		 * Posts - search for given keyword(s)
		 */
		$posts_search_results = self::search_in_posts( $widget, $search_terms );
		$posts_search_count = empty( $posts_search_results ) ? 0 : count( $posts_search_results );
		$posts_search_html  = empty( $posts_search_results ) ? '' : implode( '', $posts_search_results );

		/**
		 * Insert search record if user role not excluded from analytic counting
		 */
		$count_searches = true;
		$current_user = EPHD_Utilities::get_current_user();
		if ( ! empty( $current_user ) ) {
			$excluded_users = array_intersect( $global_config['analytic_excluded_roles'], $current_user->roles );
			if ( ! empty( $excluded_users ) ) {
				$count_searches = false;
			}
		}

		if ( $count_searches && ! empty( $record_search ) ) {
			$search_handler = new EPHD_Search_DB();
			$current_user_id = isset( $current_user->ID ) ? $current_user->ID : 0;
			$search_handler->insert_search_record(
				$widget['widget_id'],
				$widget['widget_name'],
				$page_id,
				$current_user_id,
				$search_input,
				$search_terms,
				$faqs_search_count,
				$widget['search_posts'] == 'on' ? $posts_search_count : -1, // insert -1 if search method is disabled (for correct analytics)
				$widget['search_kb'] != 'off' ? $articles_search_count : -1 // != 'off' because option may contain 'off' or CPT name, not 'on'
			);
		}

		// ensure that links have https if the current schema is https
		if ( $is_front ) {
			set_current_screen( 'front' );
		}

		// If no results are found
		if ( empty( $faqs_search_count + $posts_search_count + $articles_search_count ) ) {
			wp_die( wp_json_encode( array( 'status' => 'success', 'no_results' => $no_results_sc ) ) );
		}

		wp_die( wp_json_encode( array(
			'status'            => 'success',
			'faq_results'       => $faqs_search_html,
			'article_results'   => $articles_search_html,
			'post_results'      => $posts_search_html,
			'no_results'        => '' ) ) );
	}

	/**
	 * Search Help dialog KB or regular post content by post id
	 *
	 */
	public static function get_post_content() {

		// check wpnonce and prevent direct access
		EPHD_Utilities::ajax_verify_nonce_and_prevent_direct_access_or_error_die();

		// retrieve post id
		$post_id = (int)EPHD_Utilities::get( 'post_id', 0 );
		if ( ! EPHD_Utilities::is_positive_int( $post_id ) ) {
			wp_die( wp_json_encode( array( 'status' => 'error', 'post_content' => '' ) ) );
		}

		// We require Widget id to retrieve user config for search source
		$widget_id = (int)EPHD_Utilities::get( 'widget_id', 0 );
		if ( ! EPHD_Utilities::is_positive_int( $widget_id ) ) {
			wp_die( wp_json_encode( array( 'status' => 'error', 'post_content' => '' ) ) );
		}
		// retrieve Widgets configuration
		$widgets_config = ephd_get_instance()->widgets_config_obj->get_config();

		// use either specified Design or default Design if the Widget does not exist
		$widget = empty( $widgets_config[$widget_id] ) ? $widgets_config[EPHD_Config_Specs::DEFAULT_ID] : $widgets_config[$widget_id];

		// remove [...] and other possible excerpt read more text
		remove_all_filters( 'excerpt_more' );
		add_filter('excerpt_more', function(){
			return '';
		});

		$post_content = get_the_excerpt( $post_id );
		$excerpt_type = has_excerpt( $post_id ) ? 'custom-excerpt' : 'trimmed-content';
		$post_type = get_post_type( $post_id );
		$post_type = empty( $post_type ) ? '' : $post_type;

		// Get Post Status ( Publish , Private , Protected )
		$post_status = get_post_status( $post_id );
		$post_status = empty( $post_status ) ? '' : $post_status;

		if ( $post_status == 'publish' ) {

			// Since WordPress does not have a post status of Protected, we need to detect if the published article has a password to give it the correct CSS class. On the JS output.
			$post_object = get_post( $post_id );
			if ( ! empty( $post_object->post_password ) ) {
				$post_status = 'protected';
			}
		}

		if ( $post_status == 'protected' ) {
			$post_content = $widget['protected_article_placeholder_text'];
		} else {
			$post_content = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $post_content ); // regexp checked by wp
			$post_content = wp_kses_post( $post_content );
		}

		wp_die( wp_json_encode( array(
			'status'       => 'success',
			'post_content' => $post_content,
			'excerpt_type' => $excerpt_type,
			'post_type'    => $post_type,
			'post_status'  => $post_status,
		) ) );
	}

	/**
	 * Search in FAQs
	 *
	 * @param $widget
	 * @param $search_terms
	 *
	 * @return array
	 */
	private static function search_in_faqs( $widget, $search_terms ) {

		$faq_search_result = array();

		$faq_results = array();

		// add-ons can adjust the search
		if ( has_filter( 'ephd_execute_search_faq_filter' ) ) {
			$result = apply_filters( 'ephd_execute_search_faq_filter', '', $widget, $search_terms );
			if ( is_array( $result ) ) {
				$faq_results = array_merge( $faq_results, $result );
			}
		}

		$search_query = new EPHD_Search_Query_FAQs();
		$batch_size = 10;
		$search_results = $search_query->search_faqs_articles( $search_terms, $batch_size );

		$faq_results = array_merge( $faq_results, $search_results );
		if ( empty( $faq_results ) ) {
			return $faq_search_result;
		}

		// display one line for each search result
		foreach ( $faq_results as $faq ) {
			$faq_search_result[] = EPHD_FAQs_Page::get_faq_item_html( $faq->id, $faq->question, $faq->answer );
		}

		return $faq_search_result;
	}

	/**
	 * Search in KBs if KB enabled and set in the Help Dialog settings for the current widget
	 *
	 * @param $widget
	 * @param $search_terms
	 *
	 * @return array
	 */
	private static function search_in_kbs( $widget, $search_terms ) {

		$article_search_result = array();

		// check only if KB active
		if ( ! EPHD_KB_Core_Utilities::is_kb_or_amag_enabled() || empty( $widget['search_kb'] ) || 'off' == $widget['search_kb'] ) {
			return $article_search_result;
		}

		$article_results = array();

		// add-ons can adjust the search
		if ( has_filter( 'ephd_execute_search_kb_filter' ) ) {
			$result = apply_filters( 'ephd_execute_search_kb_filter', '', $widget, $search_terms, $widget['search_kb'] );
			if ( is_array( $result ) ) {
				$article_results = array_merge( $article_results, $result );
			}
		}

		// EPHD_Search_Query search
		$kb_id = EPHD_KB_Core_Utilities::get_kb_id_by_post_type( $widget['search_kb'] );
		if ( empty( $kb_id ) ) {
			return $article_search_result;
		}

		$global_config = ephd_get_instance()->global_config_obj->get_config();
		$search_query = new EPHD_Search_Query();
		$batch_size = 10;
		$kb_search_results = $search_query->kb_search_articles( $kb_id, $search_terms, [], $batch_size );

		$article_results = empty( $kb_search_results ) ? $article_results : array_merge( $article_results, $kb_search_results );

		if ( empty( $article_results ) ) {
			return $article_search_result;
		}

		// display one line for each search result
		foreach ( $article_results as $post ) {

			$article_url = get_permalink( $post->ID );
			if ( empty( $article_url ) || is_wp_error( $article_url ) ) {
				continue;
			}

			// linked articles have their own icon
			$article_title_icon = apply_filters( 'eckb_article_icon_filter', ' ephdfa-file-text-o', $post->ID );

			$article_title_icon = str_replace( 'ep'.'kbfa', 'ephdfa', $article_title_icon);

			$article_target = EPHD_Utilities::is_link_editor( $post ) ? 'direct' : $global_config['preview_kb_mode'];

			if ( $post->post_password && $global_config['preview_kb_mode'] == 'iframe' ) {
				$article_target = 'excerpt';
			}

			$article_search_result[] =
				'<li data-ephd-type="search" class="ephd-hd_article-item" data-ephd-post-id="' . esc_attr( $post->ID ) . '" data-ephd-url="' . esc_attr( $article_url ) . '" data-ephd-target="' . esc_attr( $article_target ) . '" tabindex="0" >
					<span class="ephd-hd_article-item__name">
						<span class="ephd-hd_article-item__icon ephdfa ' . esc_attr( $article_title_icon ) . '"></span>
						<span class="ephd-hd_article-item__text">' . esc_html( $post->post_title ) . '</span>
					</span>
				</li>';
		}

		return $article_search_result;
	}

	/**
	 * Search in Posts if set in the Help Dialog settings for the current widget
	 *
	 * @param $widget
	 * @param $search_terms
	 *
	 * @return array
	 */
	private static function search_in_posts( $widget, $search_terms ) {

		$post_search_result = array();

		$post_results = array();

		// if Search Posts option is disable
		if ( $widget['search_posts'] == 'off' ) {
			return $post_search_result;
		}

		// add-ons can adjust the search
		if ( has_filter( 'ephd_execute_search_post_filter' ) ) {
			$result = apply_filters( 'ephd_execute_search_post_filter', '', $widget, $search_terms );
			if ( is_array( $result ) ) {
				$post_results = array_merge( $post_results, $result );
			}
		}

		$search_query = new EPHD_Search_Query_Posts();
		$batch_size = 10;
		$search_results = $search_query->search_posts_articles( 'post', $search_terms, $batch_size );

		$post_results = array_merge( $post_results, $search_results );
		if ( empty( $post_results ) ) {
			return $post_search_result;
		}

		$global_config = ephd_get_instance()->global_config_obj->get_config();

		// display one line for each search result
		foreach ( $post_results as $post ) {

			$post_url = get_permalink( $post->ID );
			if ( empty( $post_url ) || is_wp_error( $post_url ) ) {
				continue;
			}

			$post_target = $global_config['preview_post_mode'];

			$post_search_result[] =
				'<li data-ephd-type="search" class="ephd-hd_article-item" data-ephd-post-id="' . esc_attr( $post->ID )  . '" data-ephd-url="' . esc_attr( $post_url )  . '" data-ephd-target="' . esc_attr( $post_target ) . '" tabindex="0" >  
					<span class="ephd-hd_article-item__name">
						<span class="ephd-hd_article-item__icon ephdfa ephdfa-file-text-o"></span>
						<span class="ephd-hd_article-item__text">' . esc_html( $post->post_title ) . '</span>
					</span>
				</li>';
		}

		return $post_search_result;
	}
}