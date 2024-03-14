<?php namespace ImageComply;


class Functions
{
	public function __construct()
	{
		// error_log("---");

		// add_action('wp_ajax_nopriv_custom_script_name', [$this, 'custom_php_ajax_function']);   
		// add_action('wp_ajax_custom_script_name', [$this, 'custom_php_ajax_function']);

		add_action('wp_ajax_imagecomply_enqueue_image', [$this, 'enqueue_image_ajax_handler']);
		// add_action('wp_ajax_imagecomply_enqueue_optimization', [$this, 'enqueue_optimization_ajax_handler']);

		add_action('wp_ajax_imagecomply_generate_all_alt_text', [$this, 'generate_all_alt_text']);
		// add_action('wp_ajax_imagecomply_optimize_all_images', [$this, 'optimize_all_images']);

		// add_action('wp_ajax_imagecomply_stop_generating_captions', [$this, 'stop_generating_captions']);

		add_action('wp_ajax_imagecomply_update_license_key', [$this, 'update_license_key']);
		add_action('wp_ajax_imagecomply_update_settings', [$this, 'update_settings']);
		
		//CRON Actions
		// add_action('generate_alt_text_action', [$this, 'generate_alt_text_cron']);
		// add_action('optimize_images_action', [$this, 'optimize_images_cron']);
		
		add_action('admin_init', [$this, 'init_integrations']);
	}

	function init_integrations() {
		if(is_plugin_active('woocommerce/woocommerce.php')) {
			require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'integrations/woocommerce.php';
			new Woocommerce();
		}

		if(is_plugin_active('wordpress-seo/wp-seo.php')){
			require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'integrations/yoastseo.php';
			new YoastSEO();
		}

		if(is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php') || is_plugin_active('all-in-one-seo-pack-pro/all_in_one_seo_pack.php')){
			require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'integrations/aioseo.php';

			new AllinOneSEO();
		}

		if(is_plugin_active('seo-by-rank-math/rank-math.php')){
			require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'integrations/seo-by-rank-math.php';
			
			new RankMathSEO();
		}

		if(is_plugin_active('squirrly-seo/squirrly.php')){
			require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'integrations/squirrly-seo.php';
			
			new SquirrlySEO();
		}
		
		if( is_plugin_active("sitepress-multilingual-cms/sitepress.php")){
			require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'integrations/wpml.php';
			new WPML_helper();
		};
		
		if( is_plugin_active("polylang/polylang.php")){
			require_once IMAGECOMPLY_PLUGIN_DIR_PATH . 'integrations/polylang.php';
			new Polylang_helper();
		};
		
	}

	// function custom_php_ajax_function() {
	// 	// Check for nonce security      
	// 	if ( ! wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce_1'])), 'enqueue_vars' ) || ! wp_verify_nonce( sanitize_text_field(wp_unslash($_POST['nonce_2'])), 'enqueue_vars' ) ) {
	// 		die ( 'Busted!');
	// 	}
	// }

	// public static function accepted_optimization_mime_types(){
	// 	return array(
	// 		"image/png",
	// 		"image/jpeg",
	// 		"image/gif",
	// 		"image/jpg",
	// 	);
	// }

	// public static function can_optimize($status){
	// 	if(!isset($status)){
	// 		return true;
	// 	}

	// 	if ($status === 'optimized') {
	// 		return false;
	// 	}
	// 	if ($status === 'queued') {
	// 		return false;
	// 	}

	// 	return true;
	// }

	// public static function get_processing_limit($current){
	// 	$PROCESSING_LIMIT = 125;
		
	// 	// $optimize_images_cron = wp_next_scheduled('optimize_images_action');
	// 	$generate_alt_text_cron = wp_next_scheduled('generate_alt_text_action');

	// 	if($current === 'generate_alt_text_action'){
	// 		return $generate_alt_text_cron ? $PROCESSING_LIMIT : $PROCESSING_LIMIT * 2;
	// 	}

	// 	// if($current === 'optimize_images_action'){
	// 	// 	return $optimize_images_cron ? $PROCESSING_LIMIT : $PROCESSING_LIMIT * 2;
	// 	// }
	// }

	// public function enqueue_optimization_ajax_handler()
	// {
	// 	// Check if the nonce is set and verified
	// 	if (isset($_POST['attachment_id']) && isset($_POST['nonce_1']) 
	// 		&& wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce_1'])), 'enqueue_optimization_ajax_handler')) {

	// 		// Sanitize attachment ID
	// 		$attachment_id = absint($_POST['attachment_id']);

	// 		// Validate attachment ID
	// 		if (!wp_attachment_is_image($attachment_id)) {
	// 			wp_send_json_error(array(
	// 				'error' => 'Invalid attachment ID.'
	// 			));
	// 		}

	// 		// Get attachment
	// 		$attachment = get_post($attachment_id);

	// 		// Validate attachment
	// 		if (!$attachment) {
	// 			wp_send_json_error(array(
	// 				'error' => 'Invalid attachment ID.'
	// 			));
	// 		}

	// 		self::optimize_image($attachment_id);

	// 		// Escape and send JSON success response
	// 		$escaped_message = esc_html__('Image is queued for processing.', 'imagecomply');
	// 		wp_send_json_success(array(
	// 			'message' => $escaped_message
	// 		), 200);
	// 	} else {
	// 		// Escape and send JSON error response
	// 		$escaped_error_message = esc_html__('attachment_id and nonce are required.', 'imagecomply');
	// 		wp_send_json_error(array(
	// 			'error' => $escaped_error_message
	// 		), 400);
	// 	}
	// }

	public function enqueue_image_ajax_handler()
	{
		// Check if the nonce is set and verified
		if (isset($_POST['attachment_id']) && isset($_POST['nonce_2']) 
			&& wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce_2'])), 'enqueue_image_ajax_handler')) {

			// Sanitize attachment ID
			$attachment_id = absint($_POST['attachment_id']);

			// Validate attachment ID
			if (!wp_attachment_is_image($attachment_id)) {
				wp_send_json_error(array(
					'error' => 'Invalid attachment ID.'
				));
			}

			// Get attachment
			$attachment = get_post($attachment_id);

			// Validate attachment
			if (!$attachment) {
				wp_send_json_error(array(
					'error' => 'Invalid attachment ID.'
				));
			}

			self::generate_alt_text($attachment_id);

			// Escape and send JSON success response
			$escaped_message = esc_html__('Image is queued for processing.', 'imagecomply');
			wp_send_json_success(array(
				'message' => $escaped_message
			), 200);
		} else {
			// Escape and send JSON error response
			$escaped_error_message = esc_html__('attachment_id and nonce are required.', 'imagecomply');
			wp_send_json_error(array(
				'error' => $escaped_error_message
			), 400);
		}
	}


	public static function verify_license($license_key)
	{
		$response = wp_remote_post('https://www.imagecomply.com/api/license/verify', array(
			'body' => json_encode(array(
				'url' => get_site_url(),
				'licenseKey' => $license_key,
				'include' => array(
					'credits',
					'plan'
				)
			)),
			'headers' => array(
				'Content-Type' => 'application/json'
			),
		));

		$response_body = json_decode($response['body']);

		return $response_body;
	}

	public static function get_plan($license_key)
	{
		if(!$license_key) {
			return false;
		}

		$response = wp_remote_post('https://www.imagecomply.com/api/license/plan', array(
			'body' => json_encode(array(
				'url' => get_site_url(),
				'licenseKey' => $license_key
			)),
			'headers' => array(
				'Content-Type' => 'application/json'
			),
		));

		$response_body = json_decode($response['body']);

		return $response_body;
	}

	public static function get_credits($license_key)
	{
		if(!$license_key) {
			return false;
		}

		$response = wp_remote_post('https://www.imagecomply.com/api/license/credits', array(
			'body' => json_encode(array(
				'url' => get_site_url(),
				'licenseKey' => $license_key
			)),
			'headers' => array(
				'Content-Type' => 'application/json'
			),
		));

		$response_body = json_decode($response['body']);

		return $response_body;
	}


	/**
	 * Generate alt text for a single image
	 */
	public static function generate_alt_text($attachment_id, $ignore_hooks = false)
	{
		// if($ignore_hooks == true){
		// 	error_log("Ignoring hooks -- ". $attachment_id);
		// }

		$license_key = get_option('imagecomply_license_key');

		//no svg
		if(get_post_mime_type($attachment_id) === 'image/svg+xml'){
			return;
		}

		if ($license_key === false) {
			return array(
				'error' => 'License key not set.'
			);
		}

		$plan = self::get_plan($license_key);
		$credits = 0;

		if(!$plan) {
			$credits = self::get_credits($license_key);

			if (!$credits->credits) {
				return array(
					'error' => 'No credits remaining.'
				);
			}
		}

		

		$attachment_url = wp_get_attachment_image_url($attachment_id, 'medium');
		
		if (!$attachment_url) {
			$attachment_url = wp_get_attachment_image_url($attachment_id, 'full');
		}
		
		if(!$ignore_hooks){
			do_action("imagecomply_pre_generate_alt_text", $attachment_id);
		}

		// Overide Languae if WPML
		$language = apply_filters("imagecomply_language_override", get_option('imagecomply_alt_text_language', ''), $attachment_id);
		// error_log("Request Language: ". $language);

		$image_data = [
			'id' => $attachment_id,
			'url' => $attachment_url,
			'language' =>  $language,
			'keywords' => get_option('imagecomply_alt_text_keywords', ''),
			'negKeywords' => get_option('imagecomply_alt_text_neg_keywords', ''),
		];

		$image_data = apply_filters('imagecomply_image_data', $image_data);
		
		if(self::validateResponce($image_data)){
			return array(
				'error' => 'Invalid image data.'
			);
		}

		$response = wp_remote_post('https://api.imagecomply.com/v2/generate-captions', array(
			'body' => json_encode(array(
				'url' => get_site_url(),
				'licenseKey' => $license_key,
				'images' => [
					$image_data
				]
			)),
			'headers' => array(
				'Content-Type' => 'application/json'
			),
		));
		


		$response_body = json_decode($response['body']);

		if (!$response_body->success) {
			
			if(isset($response_body->errors)) {
				return array(
					'errors' => $response_body->errors
				);
			} else if(isset($response_body->error)) {
				return array(
					'errors' => $response_body->error
				);
			}

			return array(
				'errors' => 'Something went wrong.'
			);
		}
		
		$status = 'queued';
		
		update_post_meta($attachment_id, 'imagecomply_alt_text_status', $status);

		return array(
			'message' => 'All alt text queued for processing.',
			'attachments_queued' => 1,
		);
	}

	/**
	 * CRON function that staggers requests to the API for image optimization
	 */
	// public function optimize_images_cron(){
	// 	$PROCESSING_LIMIT = self::get_processing_limit('optimize_images_action');

	// 	$license_key = get_option('imagecomply_license_key');

	// 	if ($license_key === false) {
	// 		wp_send_json_error(array(
	// 			'error' => 'License key not set.'
	// 		), 400);

	// 		return;
	// 	}

	// 	$query_args = array(
	// 		'post_type' => 'attachment',
	// 		'post_status' => 'inherit',
	// 		'post_mime_type' => 'image',
	// 		'posts_per_page' => $PROCESSING_LIMIT,
	// 		'meta_query' => array(
	// 				array(
	// 						'key' => 'imagecomply_optimization_status',
	// 						'value' => 'requested',
	// 						'compare' => '=' 
	// 				)
	// 		),
	// 	);

	// 	$attachments = get_posts($query_args);	
	// 	$attachments_queued = array();

	// 	//Remove the cron job if all the images have been looked after
	// 	if(count($attachments) < $PROCESSING_LIMIT){
	// 		wp_clear_scheduled_hook('optimize_images_action');

	// 		update_option('imagecomply_optimization_in_progress', 0);
	// 	}	

	// 	foreach ($attachments as $attachment) {
	// 		$attachment_id = $attachment->ID;

	// 		update_post_meta($attachment_id, 'imagecomply_optimization_status', 'queued');

	// 		$attachments_queued[] = $attachment;
	// 	}

	// 	$response = wp_remote_post('https://api.imagecomply.com/v1/optimize-images', array(
	// 		'body' => json_encode(array(
	// 			'apiVersion' => 1,
	// 			'url' => get_site_url(),
	// 			'licenseKey' => $license_key,
	// 			'images' => array_map(function ($attachment) {
	// 				$attachment_url = wp_get_attachment_image_url($attachment->ID, 'full');

	// 				if (!$attachment_url) {
	// 					$attachment_url = wp_get_attachment_image_url($attachment->ID, 'medium');
	// 				}

	// 				return array(
	// 					'id' => $attachment->ID,
	// 					'url' => $attachment_url,
	// 				);
	// 			}, $attachments_queued)
	// 		)),
	// 		'headers' => array(
	// 			'Content-Type' => 'application/json'
	// 		),
	// 	));

	// 	$response_body = json_decode($response['body']);

	// 	if (isset($response_body->errors)) {
	// 		wp_send_json_error(array(
	// 			'errors' => $response_body->errors
	// 		), 400);

	// 		return;
	// 	}


	// 	wp_send_json_success(array(
	// 		'attachments_queued' => count($attachments_queued),
	// 	), 200);
	// }

	/**
	 * CRON function that staggers requests to the API for alt text generation
	 */
	// public function generate_alt_text_cron(){
	// 	$PROCESSING_LIMIT = self::get_processing_limit('generate_alt_text_action');

	// 	$license_key = get_option('imagecomply_license_key');

	// 	if ($license_key === false) {
	// 		wp_send_json_error(array(
	// 			'error' => 'License key not set.'
	// 		), 400);

	// 		return;
	// 	}
		
	// 	//Get all images where meta_query.imagecomply.status === requested
	// 	$query_args = array(
	// 		'post_type' => 'attachment',
	// 		'post_status' => 'inherit',
	// 		'post_mime_type' => 'image',
	// 		'posts_per_page' => $PROCESSING_LIMIT,
	// 		'meta_query' => array(
	// 				array(
	// 						'key' => 'imagecomply_alt_text_status',
	// 						'value' => 'requested',
	// 						'compare' => '=' 
	// 				)
	// 		),
	// 	);

	// 	$attachments = get_posts($query_args);	
	// 	$attachments_queued = array();

	// 	//Remove the cron job if all the images have been looked after
	// 	if(count($attachments) < $PROCESSING_LIMIT){
	// 		wp_clear_scheduled_hook('generate_alt_text_action');

	// 		update_option('imagecomply_alt_text_in_progress', 0);
	// 	}	

	// 	foreach ($attachments as $attachment) {
	// 		$attachment_id = $attachment->ID;

	// 		update_post_meta($attachment_id, 'imagecomply_alt_text_status', 'queued');

	// 		$attachments_queued[] = $attachment;
	// 	}

	// 	$response = wp_remote_post('https://api.imagecomply.com/v1/generate-captions', array(
	// 		'body' => json_encode(array(
	// 			'apiVersion' => 1,
	// 			'url' => get_site_url(),
	// 			'licenseKey' => $license_key,
	// 			'images' => array_map(function ($attachment) {
	// 				$attachment_url = wp_get_attachment_image_url($attachment->ID, 'medium');

	// 				if (!$attachment_url) {
	// 					$attachment_url = wp_get_attachment_image_url($attachment->ID, 'full');
	// 				}

	// 				return array(
	// 					'id' => $attachment->ID,
	// 					'url' => $attachment_url,
	// 				);
	// 			}, $attachments_queued)
	// 		)),
	// 		'headers' => array(
	// 			'Content-Type' => 'application/json'
	// 		),
	// 	));

	// 	$response_body = json_decode($response['body']);

	// 	if (isset($response_body->errors)) {
	// 		wp_send_json_error(array(
	// 			'errors' => $response_body->errors
	// 		), 400);

	// 		return;
	// 	}
	// }

	/** 
	 * Updates all available images to have the Requested status
	 */
	public function generate_all_alt_text()
	{
		$license_key = get_option('imagecomply_license_key');

		if ($license_key === false) {
			wp_send_json_error(array(
				'error' => 'License key not set.'
			), 400);

			return;
		}

		$plan = self::get_plan($license_key);
		$credits = 0;

		if(!$plan) {
			$credits = self::get_credits($license_key);

			if (!$credits->credits) {
				wp_send_json_error(array(
					'error' => 'No credits remaining.'
				), 400);

				return;
			}
		}

		$posts_per_page_min = 10;
		$posts_per_page_max = 100;

		$posts_per_page = isset($_POST['per_page']) ? 
			intval(sanitize_text_field(wp_unslash($_POST['per_page'])))
			: 50;

		if ($posts_per_page < $posts_per_page_min) {
			$posts_per_page = $posts_per_page_min;
		} else if ($posts_per_page > $posts_per_page_max) {
			$posts_per_page = $posts_per_page_max;
		}

		global $woocommerce_imagecomply_int;
		
		
		/**
		 * Occurs before querying for all images to generate alt text for.
		 */
		do_action('imagecomply_pre_generate_all_alt_text');
		
		$query_args = array(
			'post_type' => 'attachment',
			'post_status' => 'inherit',
			'post_mime_type' => IMAGECOMPLY_PLUGIN_MIME_TYPES,
			'posts_per_page' => $posts_per_page,
			'meta_query' => array(
				'relation' => 'AND',

				// Include images that don't have alt text status / have default alt text status
				array(
					'relation' => 'OR',

					array(
						'key' => 'imagecomply_alt_text_status',
						'value' => 'incomplete',
						'compare' => '='
					),
					// Disclude images that don't have alt text status
					array(
						'key' => 'imagecomply_alt_text_status',
						'compare' => 'NOT EXISTS'
					),


					array(
						'relation' => 'AND',

						array(
							'key' => 'imagecomply_alt_text_status',
							'compare' => '=',
							'value' => 'complete-manual'
						),
						array(
							'relation' => 'OR',

							array(
								'key' => '_wp_attachment_image_alt',
								'compare' => 'NOT EXISTS'
							),
							array(
								'key' => '_wp_attachment_image_alt',
								'value' => array(''),
								'compare' => 'IN',
							),
						)
					),
				),
				// Disclude images that already have alt text
				array(
					'relation' => 'OR',
					array(
						'key' => '_wp_attachment_image_alt',
						'compare' => 'NOT EXISTS'
					),
					array(
						'key' => '_wp_attachment_image_alt',
						'value' => array(''),
						'compare' => 'IN',
					),
				)
			),
		);

		/**
		 * Filter the query args used to get attachements before image comply
		 * @param $query_args array of query args
		 */
		$query_args = apply_filters('imagecomply_generate_all_alt_text_query_args', $query_args);

		$ic_query = new \WP_Query($query_args);

		if(!$ic_query->have_posts()) {
			wp_send_json_success(array(
				'message' => 'No images to generate alt text for.',
				'max_num_pages' => 0,
			), 200);

			return;
		}
		

		$attachments = $ic_query->posts;
		$max_num_pages = $ic_query->max_num_pages;
		
		if (!$plan && (!$credits->credits || $credits->credits < count($attachments))) {
			wp_send_json_error(array(
				'error' => 'Not enough credits.'
			), 400);

			return;
		}

		$global_alt_text_language = get_option('imagecomply_alt_text_language', '');
		$global_alt_text_keywords = get_option('imagecomply_alt_text_keywords', '');
		$global_alt_text_neg_keywords = get_option('imagecomply_alt_text_neg_keywords', '');

		$response = wp_remote_post('https://api.imagecomply.com/v2/generate-captions', array(
			'body' => json_encode(array(
				'url' => get_site_url(),
				'licenseKey' => $license_key,
				'images' => array_map(function ($attachment) use($global_alt_text_language, $global_alt_text_keywords, $global_alt_text_neg_keywords, $woocommerce_imagecomply_int) {
					$attachment_url = wp_get_attachment_image_url($attachment->ID, 'medium');

					if (!$attachment_url) {
						$attachment_url = wp_get_attachment_image_url($attachment->ID, 'full');
					}
					
					$language = apply_filters("imagecomply_language_override", $global_alt_text_language, $attachment->ID);
					// error_log("Gen All: Request Language: ". $language. " - Attachment ID: ". $attachment->ID);

					$image_data = array(
						'id' => $attachment->ID,
						'url' => $attachment_url,
						'language' => $language,
						'keywords' => $global_alt_text_keywords,
						'negKeywords' => $global_alt_text_neg_keywords,
					);

					$image_data = apply_filters('imagecomply_image_data', $image_data);

					return $image_data;
				}, $attachments)
			)),
			'headers' => array(
				'Content-Type' => 'application/json'
			),
		));

		$response_body = json_decode($response['body']);

		if (isset($response_body->errors)) {
			wp_send_json_error(array(
				'errors' => $response_body->errors
			), 400);

			return;
		}

		if(!isset($response_body->data) || !isset($response_body->data->api_response) || !isset($response_body->data->api_response->success) || !$response_body->data->api_response->success) {
			wp_send_json_error(array(
				'errors' => isset($response_body->data->api_response->message) ? $response_body->data->api_response->message : 'Something went wrong.'
			), 400);

			return;
		}

		foreach($attachments as $attachment) {
			$status = 'queued';
			
			update_post_meta($attachment->ID, 'imagecomply_alt_text_status', $status);
		}

		wp_send_json_success(array(
			'message' => 'All images queued for alt text generation.',
			'max_num_pages' => $max_num_pages,
			'api_response' => $response_body,
		), 200);

		// $attachments_queued = [];

		// foreach ($attachments as $attachment) {
		// 	$attachment_id = $attachment->ID;

		// 	if(get_post_mime_type($attachment_id) === 'image/svg+xml'){
		// 		continue;
		// 	}

		// 	$status = get_post_meta($attachment_id, 'imagecomply_alt_text_status', true);

		// 	if (
		// 		$status === 'queued' ||
		// 		$status === 'requested' ||
		// 		$status === 'complete' ||
		// 		$status === 'complete-pro' ||
		// 		$status === 'complete-manual'
		// 		) {
		// 		continue;
		// 	}

		// 	$status = 'requested';

		// 	update_post_meta($attachment_id, 'imagecomply_alt_text_status', $status);

		// 	$attachments_queued[] = $attachment;
		// }

		// if(!wp_next_scheduled('generate_alt_text_action') && $attachments_queued !== []) {
		// 	update_option('imagecomply_alt_text_in_progress', 1);

		// 	wp_schedule_event(time(), 'imagecomply_cron', 'generate_alt_text_action');
		// }
		
		// if($attachments_queued !== []){
		// 	wp_send_json_success(array(
		// 		'attachments_queued' => count($attachments_queued),
		// 	), 200);
		// }
		// else {
		// 	wp_send_json_error(array(
		// 		'error' => 'No images to generate alt text for.',
		// 	), 400);
		// }
	}

	//manual
	// public static function optimize_image($attachment_id){ 
	// 	$serialized_data = get_post_meta($attachment_id, 'imagecomply', true);
	// 	$data_array = unserialize($serialized_data);

	// 	$mime_type = get_post_mime_type($attachment_id);

	// 	if (!in_array($mime_type, self::accepted_optimization_mime_types())) {
	// 		return;
	// 	}

	// 	if(!self::can_optimize($data_array)){
	// 		return;
	// 	}

	// 	$license_key = get_option('imagecomply_license_key');

	// 	if ($license_key === false) {
	// 		wp_send_json_error(array(
	// 			'error' => 'License key not set.'
	// 		), 400);

	// 		return;
	// 	}

	// 	$credits = self::get_credits($license_key);

	// 	if ($credits->credits === 0) {
	// 		wp_send_json_error(array(
	// 			'error' => 'No credits remaining.'
	// 		), 400);

	// 		return;
	// 	}

	// 	//generate the image
	// 	$attachment_url = wp_get_attachment_image_url($attachment_id, 'full');

	// 	$response = wp_remote_post('https://api.imagecomply.com/v1/optimize-images', array(
	// 		'body' => json_encode(array(
	// 			'apiVersion' => 1,
	// 			'url' => get_site_url(),
	// 			'licenseKey' => $license_key,
	// 			'images' => array(
	// 				array(
	// 					'id' => $attachment_id,
	// 					'url' => $attachment_url,
	// 				),
	// 			),
	// 		)),
	// 		'headers' => array(
	// 			'Content-Type' => 'application/json'
	// 		),
	// 	));


	// 	$response_body = json_decode($response['body']);

	// 	if (isset($response_body->errors)) {
	// 		wp_send_json_error(array(
	// 			'errors' => $response_body->errors
	// 		), 400);

	// 		return;
	// 	}

	// 	wp_send_json_success(array(
	// 		'message' => 'Image has been processed and has been optimized.',
	// 		'imageId' => $attachment_id,
	// 	), 200);
	// }	

	// public function stop_generating_captions(){
	// 	$license_key = get_option('imagecomply_license_key');

	// 	if ($license_key === false) {
	// 		wp_send_json_error(array(
	// 			'error' => 'License key not set.'
	// 		), 400);

	// 		return;
	// 	}	

	// 	wp_remote_post('https://api.imagecomply.com/v1/stop-generating-captions', array(
	// 		'body' => json_encode(array(
	// 			'apiVersion' => 1,
	// 			'url' => get_site_url(),
	// 			'licenseKey' => $license_key,
	// 		)),
	// 		'headers' => array(
	// 			'Content-Type' => 'application/json'
	// 		),
	// 	));
	// }

	//ajax
	// public function optimize_all_images()
	// {
	// 	$license_key = get_option('imagecomply_license_key');

	// 	if ($license_key === false) {
	// 		wp_send_json_error(array(
	// 			'error' => 'License key not set.'
	// 		), 400);

	// 		return;
	// 	}

	// 	$credits = self::get_credits($license_key);

	// 	if ($credits->credits === 0) {
	// 		wp_send_json_error(array(
	// 			'error' => 'No credits remaining.'
	// 		), 400);

	// 		return;
	// 	}

	// 	$attachments = get_posts(array(
	// 		'post_type' => 'attachment',
	// 		'post_status' => 'inherit',
	// 		'post_mime_type' => 'image',
	// 		'posts_per_page' => -1,
	// 	));

	// 	$count = 0;

	// 	foreach ($attachments as $attachment) {
	// 		$attachment_id = $attachment->ID;
	// 		$mime_type = get_post_mime_type($attachment_id);

	// 		if (!in_array($mime_type, self::accepted_optimization_mime_types())) {
	// 			continue;
	// 		}

	// 		$status = get_post_meta($attachment_id, 'imagecomply_optimization_status', true);

	// 		if(!self::can_optimize($status)){
	// 			continue;
	// 		}

	// 		$count++;

	// 		update_post_meta($attachment_id, 'imagecomply_optimization_status', 'requested');
	// 	}

	// 	if(!wp_next_scheduled('optimize_images_action') && $count !== 0) {
	// 		update_option('imagecomply_optimization_in_progress', 1);

	// 		wp_schedule_event(time(), 'imagecomply_cron', 'optimize_images_action');
	// 	}

	// 	if($count > 0){
	// 		wp_send_json_success(array(
	// 			'attachments_queued' => $count,
	// 		), 200);
	// 	}
	// 	else{
	// 		wp_send_json_error(array(
	// 			'error' => 'No images to optimize.',
	// 		), 400);
	// 	}
	// }

	public function update_settings()
	{
		// Sanitize and validate the input data
		$imagecomply_generate_on_upload = isset($_POST['imagecomply_generate_on_upload']) ? sanitize_text_field($_POST['imagecomply_generate_on_upload']) : '';
		
		$imagecomply_medialibrary_show_status = isset($_POST['imagecomply_medialibrary_show_status']) ? sanitize_text_field($_POST['imagecomply_medialibrary_show_status']) : '';

		$imagecomply_medialibrary_show_alt_text = isset($_POST['imagecomply_medialibrary_show_alt_text']) ? sanitize_text_field($_POST['imagecomply_medialibrary_show_alt_text']) : '';
		
		$imagecomply_alt_text_language = isset($_POST['imagecomply_alt_text_language']) ? sanitize_text_field($_POST['imagecomply_alt_text_language']) : '';

		$imagecomply_alt_text_keywords = isset($_POST['imagecomply_alt_text_keywords']) ? sanitize_text_field($_POST['imagecomply_alt_text_keywords']) : '';

		$imagecomply_alt_text_neg_keywords = isset($_POST['imagecomply_alt_text_neg_keywords']) ? sanitize_text_field($_POST['imagecomply_alt_text_neg_keywords']) : '';


		// $imagecomply_optimize_on_upload = isset($_POST['imagecomply_optimize_on_upload']) ? sanitize_text_field($_POST['imagecomply_optimize_on_upload']) : '';

		// error_log(print_r([$imagecomply_generate_on_upload, $imagecomply_alt_text_keywords, $imagecomply_alt_text_neg_keywords, $_POST['_wpnonce'], wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'update_settings')], true));

		if (empty($imagecomply_generate_on_upload) || empty($imagecomply_medialibrary_show_alt_text) || empty($imagecomply_medialibrary_show_status) /*|| empty($imagecomply_optimize_on_upload) */) {
			wp_send_json_error(array(
				'error' => 'Settings not provided.'
			), 400);
			return;
		}

		// Verify the nonce
		if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'update_settings')) {

			// Escape the data before updating the options
			$imagecomply_generate_on_upload = esc_attr($imagecomply_generate_on_upload);
			$imagecomply_medialibrary_show_status = esc_attr($imagecomply_medialibrary_show_status);
			$imagecomply_medialibrary_show_alt_text = esc_attr($imagecomply_medialibrary_show_alt_text);
			$imagecomply_alt_text_language = esc_attr($imagecomply_alt_text_language);
			$imagecomply_alt_text_keywords = esc_attr($imagecomply_alt_text_keywords);
			$imagecomply_alt_text_neg_keywords = esc_attr($imagecomply_alt_text_neg_keywords);

			// $imagecomply_optimize_on_upload = esc_attr($imagecomply_optimize_on_upload);

			// Update the options with the sanitized and escaped data
			update_option('imagecomply_generate_on_upload', $imagecomply_generate_on_upload);
			update_option('imagecomply_medialibrary_show_status', $imagecomply_medialibrary_show_status);
			update_option('imagecomply_medialibrary_show_alt_text', $imagecomply_medialibrary_show_alt_text);
			update_option('imagecomply_alt_text_language', $imagecomply_alt_text_language);
			update_option('imagecomply_alt_text_keywords', $imagecomply_alt_text_keywords);
			update_option('imagecomply_alt_text_neg_keywords', $imagecomply_alt_text_neg_keywords);
			// update_option('imagecomply_optimize_on_upload', $imagecomply_optimize_on_upload);

			// Send success response
			wp_send_json_success(array(
				'message' => 'Settings updated successfully.',
			), 200);

			return;
		}

		// Send error response if nonce verification failed
		wp_send_json_error(array(
			'error' => 'Nonce verification failed.'
		));
	}

	/**
	 * Connects the site to the ImageComply API
	 */
	public static function connect_site($license_key)
	{
		$response = wp_remote_post('https://www.imagecomply.com/api/site/connect', array(
			'body' => json_encode(array(
				'url' => get_site_url(),
				'licenseKey' => $license_key,
			)),
			'headers' => array(
				'Content-Type' => 'application/json'
			),
		));

		if (is_wp_error($response)) {
			return null;
		}

		$response_body = json_decode($response['body']);

		return $response_body;
	}

	/**
	 * Disconnects the site from the ImageComply API
	 */
	public static function disconnect_site($license_key)
	{
		$response = wp_remote_post('https://www.imagecomply.com/api/site/disconnect', array(
			'body' => json_encode(array(
				'url' => get_site_url(),
				'licenseKey' => $license_key,
			)),
			'headers' => array(
				'Content-Type' => 'application/json'
			),
		));

		if (is_wp_error($response)) {
			return null;
		}

		$response_body = json_decode($response['body']);

		return $response_body;
	}

	public function update_license_key()
	{
		// Sanitize the license key
		$license_key = isset($_POST['imagecomply_license_key']) ? sanitize_text_field($_POST['imagecomply_license_key']) : '';

		// Verify the nonce
		if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'update_settings')) {

			$existing_license_key = get_option('imagecomply_license_key', '');

			if ($license_key !== '') {
				$verify = self::verify_license($license_key);
				
				// If the license key is invalid, return an error
				if($verify === null) {
					wp_send_json_error(array(
						'error' => 'Something went wrong. Please try again.'
					), 400);
					
					return;
				}
				else if (isset($verify->error)) {
					wp_send_json_error(array(
						'error' => $verify->error
					), 400);
					
					return;
				}
				
				$connect = self::connect_site($license_key);
				if($connect === null) {
					wp_send_json_error(array(
						'error' => 'Something went wrong. Please try again.'
					), 400);
					
					return;
				}
				else if (isset($connect->error)) {
					wp_send_json_error(array(
						'error' => $connect->error
					), 400);
					
					return;
				}
			} else {
				
				if($existing_license_key === "") {
					wp_send_json_error(array(
						'error' => 'License key not set.'
					), 400);
					
					return;
				}

				$disconnect = self::disconnect_site($existing_license_key);
				
				if($disconnect === null) {
					wp_send_json_error(array(
						'error' => 'Something went wrong. Please try again.'
					), 400);
					
					return;
				}
				else if (isset($disconnect->error)) {
					wp_send_json_error(array(
						'error' => $disconnect->error
					), 400);
					
					return;
				}
			}

			// Run the update function
			update_option('imagecomply_license_key', $license_key);

			// Escape and send JSON success response
			$escaped_message = esc_html__('License key updated successfully.', 'imagecomply');
			wp_send_json_success(array(
				'message' => $escaped_message,
				'verification' => $verify,
			), 200);

			return;
		}

		// Escape and send JSON error response
		$escaped_error_message = esc_html__('Nonce verification failed.', 'imagecomply');
		wp_send_json_error(array(
			'error' => $escaped_error_message
		));
	}


	#region private functions
	private static function validateResponce($image_data){
		// error_log("Validating Responce");

		if(!$image_data['language']){
			// error_log("No Language");
			return false;
		}
		if(!$image_data['keywords']){
			// error_log("No Keywords");
			return false;
		}
		if(!$image_data['negKeywords']){
			// error_log("No Neg Keywords");
			return false;
		}
		if(!$image_data['url']){
			// error_log("No URL");
			return false;
		}
		if(!$image_data['id']){
			// error_log("No ID");
			return false;
		}

		return true;
	}

	#endregion
}

// Instantiate the Functions class
new Functions();
