<?php

class Meow_SeoEngineRest
{
	private $core = null;
	private $namespace = 'seo-engine/v1';
	
	public function __construct( $core, $admin ) {
		if ( !current_user_can( 'administrator' ) ) {
			return;
		} 
		$this->core = $core;
		add_action( 'rest_api_init', array( $this, 'rest_api_init' ) );
	}

	function rest_api_init() {
		try {
			// SETTINGS
			register_rest_route( $this->namespace, '/settings/update', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_settings_update' )
			) );
			register_rest_route( $this->namespace, '/settings/list', array(
				'methods' => 'GET',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_settings_list' ),
			) );
			register_rest_route( $this->namespace, '/settings/reset', array(
				'methods' => 'GET',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_settings_reset' ),
			) );
			register_rest_route( $this->namespace, '/settings/generate-sitemap', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_generate_sitemap' ),
			) );

			register_rest_route( $this->namespace, '/post_types', array(
				'methods' => 'GET',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_post_types' ),
			) );
			register_rest_route( $this->namespace, '/posts', array(
				'methods' => 'GET',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_posts' ),
			) );
			register_rest_route( $this->namespace, '/update_post', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_update_post' )
			) );
			register_rest_route( $this->namespace, '/update_skip_option', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_update_skip_option' )
			) );
			register_rest_route( $this->namespace, '/get_ai_keywords', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_get_ai_keywords' )
			) );

			// Google Ranking
			register_rest_route( $this->namespace, '/fetch_searches', array(
				'methods' => 'GET',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_fetch_searches' )
			) );
			register_rest_route( $this->namespace, '/save_search', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_save_search' )
			) );
			register_rest_route( $this->namespace, '/delete_search', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_delete_search' )
			) );

			


			// AI Engine
			register_rest_route( $this->namespace, '/ai_suggestion', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_ai_suggest' )
			) );
			register_rest_route( $this->namespace, '/ai_web_scraping', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_ai_web_scraping' )
			) );
			register_rest_route( $this->namespace, '/ai_magic_fix', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_ai_magic_fix' )
			) );
			register_rest_route( $this->namespace, '/ai_magic_fix_update_post', array(
				'methods' => 'POST',
				'permission_callback' => array( $this->core, 'can_access_settings' ),
				'callback' => array( $this, 'rest_ai_magic_fix_update_post' )
			) );
		}
		catch (Exception $e) {
			var_dump($e);
		}
	}

	//#region Update Options

	function rest_settings_list() {

		// Actually refresh dynamic options (related to Wordpress' settings).
		$this->core->refresh_options();


		return new WP_REST_Response( [
			'success' => true,
			'options' => $this->core->get_all_options()
		], 200 );
	}

	function rest_settings_update( $request ) {
		try {
			$params = $request->get_json_params();
			$value = $params['options'];
			$options = $this->core->update_options( $value );
			$success = !!$options;
			$message = __( $success ? 'OK' : "Could not update options.", 'seo-engine' );
			return new WP_REST_Response([ 'success' => $success, 'message' => $message, 'options' => $options ], 200 );
		}
		catch ( Exception $e ) {
			$message = apply_filters( 'mwai_ai_exception', $e->getMessage() );
			return new WP_REST_Response([ 'success' => false, 'message' => $message ], 500 );
		}
	}

	function rest_settings_reset() {
		try {
			$options = $this->core->reset_options();
			$success = !!$options;
			$message = __( $success ? 'OK' : "Could not reset options.", 'seo-engine' );
			return new WP_REST_Response([ 'success' => $success, 'message' => $message, 'options' => $options ], 200 );
		}
		catch ( Exception $e ) {
			$message = apply_filters( 'mwai_ai_exception', $e->getMessage() );
			return new WP_REST_Response([ 'success' => false, 'message' => $message ], 500 );
		}
	}

	function rest_generate_sitemap() {
		try {
			$sitemap = new Meow_SeoEngineSitemap( null );
			$path = $sitemap->create_sitemap();

			return new WP_REST_Response([ 'success' => true, 'message' => 'OK', 'path' => $path ], 200 );
		}
		catch ( Exception $e ) {
			$message = apply_filters( 'mwai_ai_exception', $e->getMessage() );
			return new WP_REST_Response([ 'success' => false, 'message' => $message ], 500 );
		}
	}

	function validate_updated_option( $option_name ) {
		// $option_checkbox = get_option( 'SEOKISSoption_checkbox', false );
		// $option_text = get_option( 'SEOKISSoption_text', 'Default' );
		// if ( $option_checkbox === '' )
		// 	update_option( 'SEOKISSoption_checkbox', false );
		// if ( $option_text === '' )
		// 	update_option( 'SEOKISSoption_text', 'Default' );
		return $this->createValidationResult();
	}

	function createValidationResult( $result = true, $message = null) {
		$message = $message ? $message : __( 'OK', 'seo-engine' );
		return ['result' => $result, 'message' => $message];
	}

	//#endregion


	function rest_post_types() {
		$data = $this->core->make_post_type_list( $this->core->get_post_types() );
		return new WP_REST_Response( [
			'success' => true,
			'data' => $data,
		], 200 );
	}

	function rest_posts( $request ) {
		$post_type = get_option( 'seo_kiss_options', null)['seo_engine_default_post_type'] ?? null;

		$args = [
			'post_type' => $post_type,
			'numberposts' => -1,
		];
		$posts = get_posts( $args );

		$data = [];
		foreach ( $posts as $post ) {
			$data[] = [
				'id' => $post->ID,
				'title' => $post->post_title,
				'excerpt' => $post->post_excerpt,
				'slug' => $post->post_name,
				'permalink' => get_permalink( $post->ID ),
				'status' => $this->core->calculate_seo_score( $post ),
				'publish_date' => $post->post_date,
				'featured_image' => get_the_post_thumbnail_url( $post->ID, 'full' ),
				'seo_title' => get_post_meta( $post->ID, $this->core->meta_key_seo_title, true ),
				'seo_excerpt' => get_post_meta( $post->ID, $this->core->meta_key_seo_excerpt, true ),
				'rendered_title' => $this->core->build_title( $post ),
				'rendered_excerpt' => $this->core->build_excerpt( $post ),
			];
		}

		return new WP_REST_Response( [
			'success' => true,
			'data' => $data,
			'total' => count($data),
		], 200 );
	}

	function rest_update_post( $request ) {
		$params = $request->get_json_params();
		// Validation
		if ( !isset( $params['id'] ) || !isset( $params['title'] ) || !isset( $params['excerpt'] ) || !isset( $params['slug'] )) {
			return new WP_REST_Response( [
				'success' => false,
				'message' => 'Missing some parameters. Required: id, title, excerpt and slug.',
			], 200 );
		}

		// Update the post.
		$post_id = $params['id'];
		$post = [
			'ID' => $post_id,
			'post_title' => $params['title'],
			'post_excerpt' => $params['excerpt'],
			'post_name' => $params['slug'],
		];
		$result = wp_update_post( $post );
		if ( $result === 0 ) {
			return new WP_REST_Response( [
				'success' => false,
				'message' => 'Failed to update the post.',
			], 200 );
		}

		// Update the AI keywords.
		$ai_keywords = $params['ai_keywords'] == '' ? null : explode(' ', $params['ai_keywords'] );
		$this->update_or_delete_post_meta( $post_id, '_seo_engine_ai_keywords', $ai_keywords );


		// Update the post metadata.
		$seo_title = $params['seo_title'] ?? null;
		$seo_excerpt = $params['seo_excerpt'] ?? null;
		if ( $seo_title !== null ) {
			$this->update_or_delete_post_meta( $post_id, $this->core->meta_key_seo_title, $seo_title );
		}
		if ( $seo_excerpt !== null ) {
			$this->update_or_delete_post_meta( $post_id, $this->core->meta_key_seo_excerpt, $seo_excerpt );
		}

		return new WP_REST_Response( [
			'success' => true,
		], 200 );
	}

	function rest_update_skip_option( $request ) {
		$params = $request->get_json_params();
		// Validation
		if ( !isset( $params['id'] ) || !isset( $params['skip'] )) {
			return new WP_REST_Response( [
				'success' => false,
				'message' => 'Missing some parameters. Required: id and skip.',
			], 200 );
		}

		$post_id = $params['id'];
		$skip = boolVal( $params['skip'] );
		$this->update_or_delete_post_meta( $post_id, $this->core->meta_key_skip, $skip );

		return new WP_REST_Response( [
			'success' => true,
		], 200 );
	}

	function update_or_delete_post_meta( $post_id, $meta_key, $meta_value ) {
		//add post meta if non-existent
		if ( !get_post_meta( $post_id, $meta_key ) ) {
			add_post_meta( $post_id, $meta_key, $meta_value );
			return;
		}

		if ( $meta_value ) {
			update_post_meta( $post_id, $meta_key, $meta_value );
		}
		else {
			delete_post_meta( $post_id, $meta_key, $meta_value );
		}
	}



	function rest_fetch_searches(  ) {
		$searches = get_option( 'seo_engine_searches', [] );
		$interval = get_option( 'seo_kiss_options', null )[ 'seo_engine_google_interval_hours' ] ?? 24;
		$track_points = (int)get_option( 'seo_kiss_options', null )[ 'seo_engine_google_track_points' ] ?? 60;


		foreach ( $searches as $key => $search ) {
			if ( strtotime( $search['updated_at'] ) > strtotime( '-' . $interval . ' hours' )
			&& $search['rank'] !== null // when search is created, rank is null
			&& $search['force_update'] !== true // if force update is true, we will update the rank
			) {
				continue;
			}

			$result = $this->core->google_custom_search( $search );
			if ( $result === false ) {
				continue;
			}
			
			$date = date('Y-m-d H:i:s');

			$searches[ $key ]['force_update'] = false;

			$searches[ $key ]['last_ranks'][] = [
				'rank' => $search['rank'] ?? $result['rank'], // if rank is null, it means the search is created
				'timestamp' => $date,
			];
			$searches[ $key ]['last_ranks'] = array_slice($searches[ $key ]['last_ranks'], -$track_points);
			$searches[ $key ]['rank'] = $result['rank'];
			
			$searches[ $key ]['updated_at'] = $date;
			$searches[ $key ]['message'] = $result['message'];

			$searches[ $key ]['top_competitors'] = $result['top_competitors'];
		}

		update_option( 'seo_engine_searches', $searches );
		
		return new WP_REST_Response([
			'success' => true,
			'message' => 'OK',
			'data' => $searches,
		], 200 );
	}

	function rest_delete_search( $request ) {
		try {

		$params = $request->get_json_params();

		$searches = get_option( 'seo_engine_searches', [] );

		unset( $searches[ $params['id'] ] );

		update_option( 'seo_engine_searches', $searches );

		return new WP_REST_Response([
			'success' => true,
			'message' => 'OK',
			'data' => $searches,
		], 200 );

		}
		catch( Exception $e)
		{
			return new WP_REST_Response([
				'success' => false,
				'message' => $e->getMessage(),
			], 500 );
		}
	}

	function rest_save_search( $request ) {
		try {

		$params = $request->get_json_params();
		$searches = get_option( 'seo_engine_searches', [] );

		if ( $params['force_update'] ){

			$searches[ $params['id'] ]['force_update'] = true;

			update_option( 'seo_engine_searches', $searches );

			return new WP_REST_Response([
				'success' => true,
				'message' => 'OK - Force Update',
				'data' => $searches[ $params['id'] ],
			], 200 );
		}

		if ( $params['id'] == '' ){
			$params['id'] = uniqid();
		}
		

		if ( $params['use_wordpress'] ){
			$locale = get_locale();

			$params['cr__country'] = substr($locale, 3, 2);
			$params['hl__interface_language'] = substr($locale, 0, 2);
			$params['gl__geolocation'] = 'country' . substr($locale, 3, 2);
		}

		$searches[ $params['id'] ] = [
			...$params,
			'created_at' => date('Y-m-d H:i:s'),
			'updated_at' => date('Y-m-d H:i:s'),
			'rank' => null,
			'last_ranks' => [],
		];

		update_option( 'seo_engine_searches', $searches );

		return new WP_REST_Response([
			'success' => true,
			'message' => 'OK - Save New Search',
			'data' => $searches[ $params['id'] ],
		], 200 );

		}
		catch( Exception $e)
		{
			return new WP_REST_Response([
				'success' => false,
				'message' => $e->getMessage(),
			], 500 );
		}
	}


	function rest_ai_suggest( $request ) {
		try {

			$params = $request->get_json_params();
			$post = get_post( $params[ 'id' ] );
	
			if ( !$post ) {
				return new WP_REST_Response([
					'success' => false,
					'message' => 'Post not found.',
				], 404 );
			}
			
			global $mwai;
			if (is_null( $mwai ) || !isset( $mwai ) ) {
				return new WP_REST_Response([
					'success' => false,
					'message' => 'Missing AI Engine.',
				], 500 );
			}else{
				$ai_suggestion = Meow_Modules_SeoEngine_Ai_Suggestion::prompt( $post, $params[ 'field' ] );
			}

			if (empty($ai_suggestion) || is_null($ai_suggestion)) {
				return new WP_REST_Response([
					'success' => false,
					'message' => 'AI suggestion is invalid.',
				], 400 );
			}
	
			return new WP_REST_Response([
				'success' => true,
				'message' => 'OK',
				'data' => str_replace('"', '', $ai_suggestion),
			], 200 );
	
		}
		catch( Exception $e)
		{
			return new WP_REST_Response([
				'success' => false,
				'message' => $e->getMessage(),
			], 500 );
		}
	}

	function rest_ai_magic_fix_update_post( $request ){
		try{
			$params = $request->get_json_params();
			$post = get_post( $params[ 'id' ] );
	
			if ( !$post ) {
				return new WP_REST_Response([
					'success' => false,
					'message' => 'Post not found.',
				], 404 );
			}
	
			$fix_result = $this->core->magic_fix( $post, $params[ 'fixes' ], true );
			if ( $fix_result === false ) {
				return new WP_REST_Response([
					'success' => false,
					'message' => 'Missing AI Engine.',
				], 200 );
			}
	
			return new WP_REST_Response([
				'success' => true,
				'message' => 'OK',
				'data' => [
					'id_received' => $params[ 'id' ],
					'fixes_result' => $fix_result,
				]
			], 200 );
	
		}
		catch( Exception $e)
		{
			error_log($e->getMessage());
			return new WP_REST_Response([
				'success' => false,
				'message' => $e->getMessage(),
			], 500 );
		}
	}

	function rest_ai_magic_fix( $request ){
		try{
			$params = $request->get_json_params();
			$post = get_post( $params[ 'id' ] );
	
			if ( !$post ) {
				return new WP_REST_Response([
					'success' => false,
					'message' => 'Post not found.',
				], 404 );
			}

			$fix_result = $this->core->magic_fix( $post, $params[ 'codes' ] );
			if ( $fix_result === false ) {
				return new WP_REST_Response([
					'success' => false,
					'message' => 'Missing AI Engine.',
				], 200 );
			}

			return new WP_REST_Response([
				'success' => true,
				'message' => 'OK',
				'data' => [
					'id_received' => $params[ 'id' ],
					'codes_received' => $params[ 'codes' ],
					'fix_result' => $fix_result,
				]
			], 200 );

		}
		catch( Exception $e)
		{
			error_log($e->getMessage());
			return new WP_REST_Response([
				'success' => false,
				'message' => $e->getMessage(),
			], 500 );
		}
	}

	function rest_ai_web_scraping( $request ) {
		try {
			$params = $request->get_json_params();
			$search = $params[ 'search' ];
			$search = str_replace( ' ', '+', $search );
			
			$ai_suggestion = Meow_Modules_SeoEngine_Scraping::scrap_bing_request( $search );

			preg_match('/title: (.+?), excerpt: (.+?), slug: (.+)/', $ai_suggestion, $matches);

	
			if (empty($matches) || is_null($matches)) {
				return new WP_REST_Response([
					'success' => false,
					'message' => 'AI suggestion is invalid.',
				], 400 );
			}
	
			return new WP_REST_Response([
				'success' => true,
				'message' => 'OK',
				'data' => [
					'title' => str_replace('"', '', $matches[1]),
					'excerpt' => str_replace('"', '', $matches[2]),
					'slug' => str_replace('"', '', $matches[3]),
				]
			], 200 );
	
		}
		catch( Exception $e)
		{
			return new WP_REST_Response([
				'success' => false,
				'message' => $e->getMessage(),
			], 500 );
		}
	}

	function rest_get_ai_keywords( $request ){
		try{
			$params = $request->get_json_params();
			$post = get_post( $params[ 'id' ] );
	
			if ( !$post ) {
				return new WP_REST_Response([
					'success' => false,
					'message' => 'Post not found.',
				], 404 );
			}
	
			$keywords = get_post_meta( $post->ID, '_seo_engine_ai_keywords', true );
	
			return new WP_REST_Response([
				'success' => true,
				'message' => 'OK',
				'data' => [
					'id_received' => $params[ 'id' ],
					'keywords' => $keywords == '' ? [] : $keywords,
				]
			], 200 );
	
		}
		catch( Exception $e)
		{
			return new WP_REST_Response([
				'success' => false,
				'message' => $e->getMessage(),
			], 500 );
		}
	}
}
