<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

// Generate dynamically the Instant Indexing API key
function siteseo_instant_indexing_generate_api_key_fn($init = false) {
	$options = get_option('siteseo_instant_indexing_option_name') ? get_option('siteseo_instant_indexing_option_name') : [];

	$api_key = wp_generate_uuid4();
	$api_key = preg_replace('[-]', '', $api_key);
	$options['instant_indexing_bing_api_key'] = base64_encode($api_key);

	if ($init === true) {
		$options['instant_indexing_automate_submission'] = '1';
	}

	update_option('siteseo_instant_indexing_option_name', $options);

	if ($init === false) {
		wp_send_json_success();
	}
}

// Create the virtual Instant Indexing API key txt file
function siteseo_instant_indexing_api_key_txt() {
	
	$options = get_option('siteseo_instant_indexing_option_name');
	$api_key = isset($options['instant_indexing_bing_api_key']) ? base64_decode(esc_attr($options['instant_indexing_bing_api_key'])) : null;

	if ($api_key === null) {
		return;
	}

	global $wp;
	$current_url = home_url( $wp->request );

	if ( isset( $current_url ) && trailingslashit( get_home_url() ) . $api_key . '.txt' === $current_url ) {
		header( 'Content-Type: text/plain' );
		header( 'X-Robots-Tag: noindex' );
		status_header( 200 );
		echo esc_html($api_key);

		exit();
	}
}
add_action('template_redirect', 'siteseo_instant_indexing_api_key_txt', 0);

//Batch Instant Indexing
function siteseo_instant_indexing_fn($is_manual_submission = true, $permalink = null) {
	
	if ($is_manual_submission === true) {
		$options = get_option('siteseo_instant_indexing_option_name');

		//Update options
		if (isset($_POST['urls_to_submit'])) {
			$options['instant_indexing_manual_batch'] = sanitize_textarea_field($_POST['urls_to_submit']);
		}

		if (isset($_POST['indexnow_api'])) {
			$options['instant_indexing_bing_api_key'] = siteseo_opt_post('indexnow_api');
		}

		if (isset($_POST['google_api'])) {
			$options['instant_indexing_google_api_key'] = siteseo_opt_post('google_api');
		}

		if (isset($_POST['google'])) {
			if ($_POST['google'] === 'true') {
				$options['engines']['google'] = '1';
			} elseif ($_POST['google'] === 'false') {
				unset($options['engines']['google']);
			}
		}

		if (isset($_POST['bing'])) {
			if ($_POST['bing'] === 'true') {
				$options['engines']['bing'] = '1';
			} elseif ($_POST['bing'] === 'false') {
				unset($options['engines']['bing']);
			}
		}

		if (isset($_POST['automatic_submission'])) {
			if ($_POST['automatic_submission'] === 'true') {
				$options['instant_indexing_automate_submission'] = '1';
			} elseif ($_POST['automatic_submission'] === 'false') {
				unset($options['instant_indexing_automate_submission']);
			}
		}

		if (isset($_POST['update_action']) && isset($_POST['delete_action'])) {
			if ($_POST['update_action'] === 'URL_UPDATED') {
				$options['instant_indexing_google_action'] = 'URL_UPDATED';
			} elseif ($_POST['delete_action'] === 'URL_DELETED') {
				$options['instant_indexing_google_action'] = 'URL_DELETED';
			} else {
				$options['instant_indexing_google_action'] = 'URL_UPDATED';
			}
		}

		update_option('siteseo_instant_indexing_option_name', $options);
	}

	$options = get_option('siteseo_instant_indexing_option_name');

	$engines = isset($options['engines']) ? $options['engines'] : null;
	$actions = isset($options['instant_indexing_google_action']) ? esc_attr($options['instant_indexing_google_action']) : 'URL_UPDATED';
	$urls = isset($options['instant_indexing_manual_batch']) ? esc_attr($options['instant_indexing_manual_batch']) : '';
	$google_api_key = isset($options['instant_indexing_google_api_key']) ? $options['instant_indexing_google_api_key'] : '';
	$bing_api_key = isset($options['instant_indexing_bing_api_key']) ? base64_decode(esc_attr($options['instant_indexing_bing_api_key'])) : '';
	$bing_url = 'https://api.indexnow.org/indexnow/';
	$google_url = 'https://indexing.googleapis.com/v3/urlNotifications:publish';

	// Clean logs
	delete_option('siteseo_instant_indexing_log_option_name');

	// Check we have URLs to submit
	if ($urls === '' && $is_manual_submission === true) {
		$log['error'] = __('No URLs to submit','siteseo');
		update_option('siteseo_instant_indexing_log_option_name', $log, false);
		return;
	}

	// Check we have at least one search engine selected
	if (empty($engines)) {
		$log['error'] = __('No search engines selected','siteseo');
		update_option('siteseo_instant_indexing_log_option_name', $log, false);
		return;
	}

	// Check we have setup at least one API key
	if ($google_api_key === '' && $bing_api_key === '') {
		$log['error'] = __('No API key defined from the settings tab','siteseo');
		update_option('siteseo_instant_indexing_log_option_name', $log, false);
		return;
	}

	// Prepare the URLS
	if ($is_manual_submission === true) {
		$urls 	= preg_split('/\r\n|\r|\n/', $urls);
		$x_source_info = SITESEO_API.'x-source-info/true';

		$urls = array_slice($urls, 0, 100);
	} elseif ($is_manual_submission === false && !empty($permalink)) {
		$urls = null;
		$urls[] = $permalink;
		$x_source_info = SITESEO_API.'x-source-info/false';
	}

	// Bing API
	if (isset($bing_api_key) && !empty($bing_api_key) && $engines['bing'] === '1') {
		$host = wp_parse_url(get_home_url(), PHP_URL_HOST);

		$body   = [
			'host' => $host,
			'key' => $bing_api_key,
			'keyLocation'  => trailingslashit( get_home_url() ) . $bing_api_key . '.txt',
			'urlList' => $urls
		];

		// Build the POST request
		$args = [
			'body'	=> wp_json_encode($body),
			'timeout' => 30,
			'headers' => [
				'Content-Type'  => 'application/json',
				'X-Source-Info' => $x_source_info
			],
		];
		
		$args = apply_filters( 'siteseo_instant_indexing_post_request_args', $args );

		// IndexNow (Bing)
		$response = wp_remote_post( $bing_url, $args );

		// Check the response is ok first
		if (is_wp_error($response)) {
			$message = $response->get_error_message();
			$log['bing']['status'] = $message;
		}

		$log['bing']['response'] = $response;
	} elseif ($engines['bing'] === '1') {
		$log['bing']['response']['error'] = [
			'code' => 401,
			'message' => __('Bing API key is missing', 'siteseo')
		];
	}

	// Google API
	if ($is_manual_submission === true) {
		if (isset($google_api_key) && !empty($google_api_key) && $engines['google'] === '1') {
			try {
				$client = new Google_Client();

				$client->setAuthConfig( json_decode($google_api_key, true) );
				$client->setScopes( Google_Service_Indexing::INDEXING );

				$client->setUseBatch( true );

				$service = new Google_Service_Indexing( $client );
				$batch = $service->createBatch();

				$postBody = new Google_Service_Indexing_UrlNotification();

				foreach($urls as $url) {
					$postBody->setUrl( $url );
					$postBody->setType( $actions );
					$batch->add( $service->urlNotifications->publish( $postBody ) );
				}
				$results = $batch->execute();
			}
			catch (\Exception $e) {
				$results = $e->getMessage();
			}

			$log['google']['response'] = $results;
		} elseif ($engines['google'] === '1') {
			$log['google']['response']['error'] = [
				'code' => 401,
				'message' => __('Google API key is missing', 'siteseo')
			];
		}
	}

	// Log URLs submitted
	$log['log']['urls'] = $urls;
	$log['log']['date'] = current_time( 'F j, Y, g:i a' );

	update_option('siteseo_instant_indexing_log_option_name', $log, false);

	if ($is_manual_submission === true) {
		exit();
	}
}

//Ajax Batch Instant Indexing
function siteseo_instant_indexing_post(){
	check_ajax_referer('siteseo_instant_indexing_post_nonce');
	require_once SITESEO_DIR_PATH . '/vendor/autoload.php';
	if (current_user_can(siteseo_capability('manage_options', 'instant-indexing')) && is_admin()) {
		siteseo_instant_indexing_fn();
	}

	wp_send_json_success();
}
add_action('wp_ajax_siteseo_instant_indexing_post', 'siteseo_instant_indexing_post');

//Ajax Generate Instant Indexing API Key
function siteseo_instant_indexing_generate_api_key(){
	check_ajax_referer('siteseo_instant_indexing_generate_api_key_nonce');
	if (current_user_can(siteseo_capability('manage_options', 'instant-indexing')) && is_admin()) {
		siteseo_instant_indexing_generate_api_key_fn();
	}

	wp_safe_redirect(admin_url('admin.php?page=siteseo-instant-indexing'));
	exit();
}
add_action('wp_ajax_siteseo_instant_indexing_generate_api_key', 'siteseo_instant_indexing_generate_api_key');

//Automatic submission
function siteseo_instant_indexing_on_post_publish( $new_status, $old_status, $post ){
	
	$options = get_option('siteseo_instant_indexing_option_name');

	// Is automatic submission enabled?
	if (!isset($options['instant_indexing_automate_submission'])) {
		return;
	}

	$do_submit = false;
	$type = "add";
	if ($old_status === 'publish' && $new_status === 'publish') {
		$do_submit = true;
		$type = "update";
	}
	else if ($old_status != 'publish' && $new_status === 'publish') {
		$do_submit = true;
		$type = "add";
	}
	else if ($old_status === 'publish' && $new_status === 'trash') {
		$do_submit = true;
		$type = "delete";
	}

	// Do submission
	if ($do_submit) {
		$permalink = get_permalink($post);

		// Clean permalink if trashed post
		if (strpos($permalink, '__trashed') > 0) {
			$permalink = substr($permalink, 0, strlen($permalink) - 10) . "/";
		}
		if (empty($permalink)) {
			return;
		}

		// Is it a public post type?
		if(function_exists('is_post_publicly_viewable')){
			$is_public_post = is_post_publicly_viewable($post);

			if(!$is_public_post &&  $type != 'delete'){
				return;
			}
			return siteseo_instant_indexing_fn(false, $permalink);
		}
	}
}
add_action( 'transition_post_status', 'siteseo_instant_indexing_on_post_publish', 10, 3 );
