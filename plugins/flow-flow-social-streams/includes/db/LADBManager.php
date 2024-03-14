<?php  namespace flow\db;

use Exception;
use flow\settings\FFGeneralSettings;
use flow\settings\FFSettingsUtils;
use flow\LABase;
use Unirest\Request;
use Unirest\Response;

if ( ! defined( 'WPINC' ) ) die;
/**
 * FlowFlow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */
abstract class LADBManager {
	public $table_prefix;
	public $option_table_name;
	public $posts_table_name;
	public $cache_table_name;
	public $streams_table_name;
	public $image_cache_table_name;
	public $streams_sources_table_name;
	public $snapshot_table_name;
	public $comments_table_name;
	public $post_media_table_name;

	protected $context;
	protected $plugin_slug;
	protected $plugin_slug_down;
	protected $init = false;
	protected $sources = null;
	protected $streams = null;

	function __construct($context) {
		$this->context = $context;
		$this->table_prefix = $context['table_name_prefix'];
		$this->plugin_slug = $context['slug'];
		$this->plugin_slug_down = $context['slug_down'];

		$this->option_table_name = $this->table_prefix . 'options';
		$this->posts_table_name = $this->table_prefix . 'posts';
		$this->cache_table_name = $this->table_prefix . 'cache';
		$this->streams_table_name = $this->table_prefix . 'streams';
		$this->image_cache_table_name = $this->table_prefix . 'image_cache';
		$this->streams_sources_table_name = $this->table_prefix . 'streams_sources';
		$this->snapshot_table_name= $this->table_prefix . 'snapshots';
		$this->comments_table_name= $this->table_prefix . 'comments';
		$this->post_media_table_name = $this->table_prefix . 'post_media';
	}
	
	public final function dataInit($only_enable = false, $safe = false, $remote = true){
		$this->init = true;
		
		if ($safe && !FFDB::existTable($this->streams_sources_table_name)) {
			$this->sources = [];
			$this->streams = [];
			return;
		}
		
		$boosted = [];
		$load_boosted = false;
		$sources = FFDB::sources($this->cache_table_name, $this->streams_sources_table_name, null, $only_enable);
		if ($remote) {
			foreach ( $sources as $id => &$tmp_source ) {
				if ($tmp_source['boosted'] == FFSettingsUtils::YEP){
					if (!$load_boosted){
						$boosted = $this->getBoostSources();
						$load_boosted = true;
					}
					if (isset($boosted[$id])){
						$tmp_source = $boosted[$id];
					}
				}
			}
		}
		$this->sources = $sources;
		$this->streams = FFDB::streams($this->streams_table_name);
		$connections = FFDB::conn()->getIndMultiRow('stream_id', 'select `stream_id`, `feed_id` from ?n order by `stream_id`', $this->streams_sources_table_name);
		foreach ( $this->streams as &$stream ) {
			$stream = (array)FFDB::unserializeStream($stream);
			if (!isset($stream['feeds'])) $stream['feeds'] = [];
			$stream['status'] = '1';
			if (isset($connections[$stream['id']])){
				foreach ($connections[$stream['id']] as $source){
					if (isset($this->sources[$source['feed_id']])){
						$full_source = $this->sources[$source['feed_id']];
						$stream['feeds'][] = $full_source;
						if (isset($full_source['status']) && $full_source['status'] == 0) $stream['status'] = '0';
					}
				}
			}
		}
	}

	/**
	 * Get stream settings by id endpoint
	 */
	public final function get_stream_settings(){

        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

		$id = $_GET['stream-id'];
		$this->dataInit(false, false);

		$stream = $this->streams[$id];

        // cleaning if error was saved in database stream model, can be removed in future, now it's needed for affected users
        if ( isset( $stream['error'] ) ) unset( $stream['error'] );

        die( json_encode( $stream ) );
	}

	public final function get_shortcode_pages() {
        global $wpdb;

        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

        $stream = $_POST['stream'];

        $query = "SELECT ID, post_title FROM ".$wpdb->posts." WHERE post_content LIKE '%[ff id=\"" . $stream . "\"%' AND post_status = 'publish'";

        $results = $wpdb->get_results ( $wpdb->prepare( $query ) );

        foreach ($results as $result) {
            $result->url = get_permalink( $result->ID );
        }

        die( json_encode( $results ) );
    }
	
	/**
	 * Create stream endpoint
	 */
	public final function create_stream(){
		$this->checkSecurity();

		$stream = $this->getStreamFromRequestWithoutErrors();
		try{
			FFDB::beginTransaction();
			if (false !== ($max = FFDB::maxIdOfStreams($this->streams_table_name))){
				$newId = (string) ($max + 1);
				$stream->id = $newId;
				$stream->feeds = isset($stream->feeds) ? $stream->feeds : json_encode( [] );
				$stream->name = isset($stream->name) ? $stream->name : '';
				FFDB::setStream($this->streams_table_name, $this->streams_sources_table_name, $newId, $stream);
				$response = json_encode(FFDB::getStream($this->streams_table_name, $newId));
				FFDB::commit();
				
				$this->refreshCache($newId);
				echo $response;
			}
			else echo false;
		}catch ( Exception $e){
			FFDB::rollbackAndClose();
			echo 'Caught exception: ' .  $e->getMessage() . "\n";
		}
		FFDB::close();
		die();
	}
	
	/**
	 * Save sources endpoint
	 */	
	public final function save_sources_settings(){
		if (FF_USE_WP) {
			if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
				$dontChange = true;
			}
		}

		if (isset($_POST['model'])){
			$model = $_POST['model'];
			$model['id'] = 1; // DON'T DELETE, ID is always 1, this is needed to detect if model was saved

            if (isset( $dontChange ) && isset( $_POST['model']['feeds_changed'] )) {
                unset( $_POST['model']['feeds_changed'] );
            }

			$boosted = false;
            $original_status = 0;
			if (isset($_POST['model']['feeds_changed'])){
				foreach ( $_POST['model']['feeds_changed'] as $feed ) {
					switch ($feed['state']) {
						case 'changed':
							$source = $_POST['model']['feeds'][ $feed['id'] ];
                            $original_status = $source['status'];
							$sources = FFDB::sources($this->cache_table_name, $this->streams_sources_table_name);
							$old = $sources[$source['id']];
							$changed_content = $this->changedContent($source, $old);
							if ($changed_content) {
								$this->cleanFeed($feed['id']);
								if (!$boosted){
									$boosted = FFSettingsUtils::YepNope2ClassicStyle($source['boosted'], false);
									if (!$boosted && ($source['boosted'] != $old['boosted'])){
										$boosted = true;
									}
								}
							}
							else if (!$boosted){
								$boosted = FFSettingsUtils::YepNope2ClassicStyle($source['boosted'], false);
							}
							$this->modifySource( $source, $changed_content );
                            if ($source['enabled'] == 'yep') {
                                $this->refreshCache4Source($feed['id'], false, $boosted);
                            }
							break;
						case 'created':
							$source = $_POST['model']['feeds'][$feed['id']];
							$this->modifySource($source);
							if (!$boosted){
								$boosted = FFSettingsUtils::YepNope2ClassicStyle($source['boosted'], false);
							}
							$this->refreshCache4Source($feed['id'], true, $boosted);
							break;
						case 'reset_cache':
							$source = $_POST['model']['feeds'][ $feed['id'] ];
							$this->cleanFeed($feed['id']);
							if (!$boosted){
								$boosted = FFSettingsUtils::YepNope2ClassicStyle($source['boosted'], false);
							}
							$this->refreshCache4Source($feed['id'], true, $boosted);
							break;
						case 'deleted':
							$sources = FFDB::sources($this->cache_table_name, $this->streams_sources_table_name);
							if (isset($sources[$feed['id']])){
                                $source = $sources[$feed['id']];
                                $this->deleteFeed($feed['id']);
                                if (!$boosted){
                                    $boosted = FFSettingsUtils::YepNope2ClassicStyle($source['boosted'], false);
                                }
                            }
							break;
					}
				}
			}

			if ($boosted){
				$response = $this->proxyRequest($_POST);
				if ($response->code == 200){
					$json = $response->body;
					foreach ( $json['feeds'] as &$feed ) {
						$enabled = FFSettingsUtils::YepNope2ClassicStyle($feed['enabled'], false) ? 1 : 0;
						$status = ['last_update' => $feed['last_update'], 'status' => $original_status, 'enabled' => $enabled];
						if (isset($feed['errors']) && is_array($feed['errors'])) $status['errors'] = serialize($feed['errors']);
						$this->saveSource($feed['id'], $status);
						$feed['last_update'] = $feed['last_update'] == 0 ? 'N/A' : FFSettingsUtils::classicStyleDate($feed['last_update']);
					}
					echo json_encode($json);
				}
				else if ($response->code == 504){
					header('HTTP/1.1 504 Gateway Time-out');
				}
				else if ($response->code == 403){
					header('HTTP/1.1 403 Forbidden');
					echo $response->raw_body;
				}
				else {
					//TODO: обработка исключений
				}
			}
			else {
				if (isset($model['feeds'])){
                    $this->dataInit();
                    $sources = $this->sources();
					foreach ( $model['feeds'] as &$source ) {
						if (array_key_exists($source['id'], $sources)){
							$source = $sources[$source['id']];
						}
					}
				}
				if (isset( $dontChange )) {
					$model['error'] = 'Not allowed';
				}
				echo json_encode($model);
			}
			die();
		}
		die(1);
	}
	
	/**
	 * Save stream endpoint
	 */
	public final function save_stream_settings(){
		$this->checkSecurity();
		$stream = $this->getStreamFromRequestWithoutErrors();
		try{
			FFDB::beginTransaction();
			$stream->last_changes = time();
			FFDB::setStream($this->streams_table_name, $this->streams_sources_table_name, $stream->id, $stream);
			
			$this->generateCss($stream);
			
			echo json_encode($stream);
			FFDB::commit();

			$this->proxyRequest($_POST);
		}catch (Exception $e){
			FFDB::rollbackAndClose();
			error_log('save_stream_settings error:');
			error_log($e->getMessage());
			error_log($e->getTraceAsString());
		}
		FFDB::close();
		die();
	}
	
	/**
	 * Save general settings endpoint
	 */
	public final function ff_save_settings_fn() {

        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

		$serialized_settings = $_POST['settings']; // param1=foo&param2=bar
		$settings = [];
		parse_str( $serialized_settings, $settings );
		
		try{
			$force_load_cache = $this->clean_cache($settings);
			
			FFDB::beginTransaction();
			
			$settings = $this->saveGeneralSettings($settings);
			
			FFDB::commit();

            global $wp_locale;
            $_POST['wp_locale'] = json_encode($wp_locale);
            $_POST['wp_timezone_string'] = get_option( 'timezone_string' );
            $_POST['wp_date_format'] = get_option( 'date_format' );
            $_POST['wp_time_format'] = get_option( 'time_format' );
            if (false !== ($option = get_option( 'la_facebook_access_token', false )))
                $_POST['la_facebook_access_token'] = $option;
            if (false !== ($option = get_option( 'la_facebook_access_token_expires', false )))
                $_POST['la_facebook_access_token_expires'] = $option;

			$this->proxyRequest($_POST);
			
			if ($force_load_cache) {
				$this->refreshCache(null, $force_load_cache);
			}
			
			$response = [
				'settings' => $settings, 
				'activated' => false
			];
			$this->customizeResponse($response);
			
			echo json_encode( $response );
		}catch ( Exception $e){
			error_log('ff_save_settings_fn error:');
			$msg = $e->getMessage();

			if ( strpos( $msg, 'Connection timed out after') !== false ) {
			    $msg .= '. Failed to connect to http://flow.looks-awesome.com which validates purchase code. Please ask help from your hosting support and tell them curl_exec exits with connection timeout error on line 889 of wp-content/plugins/flow-flow/includes/db/LADBManager.php';
            }

			error_log( $msg );
			error_log($e->getTraceAsString());
			FFDB::rollbackAndClose();
			die($e->getMessage());
		}
		FFDB::close();
		die();
	}

    /**
     * @param mixed $old_value
     * @param mixed $value
     * @param string $option
     */
    public function update_wp_date_format_hook($old_value, $value, $option){
        if ($option === 'WPLANG'){
            switch_to_locale(empty($value) ? 'en_US' : $value);
        }
        global $wp_locale;
        $post['action'] = 'flow_flow_save_settings_date_format';
        $post['wp_locale'] = json_encode($wp_locale);
        $post['wp_timezone_string'] = get_option( 'timezone_string' );
        $post['wp_date_format'] = get_option( 'date_format' );
        $post['wp_time_format'] = get_option( 'time_format' );
        $this->proxyRequest($post);
    }

    public function update_options(){
        $data = [
            'action' => 'flow_flow_save_settings',
            'options' => $this->getOption('options', false, false, true),
            'fb_auth_options' => $this->getOption('fb_auth_options', false, false, true)
        ];
        if (false != ($la_facebook_access_token = get_option('la_facebook_access_token', false))){
            $data['la_facebook_access_token'] = $la_facebook_access_token;
        }
        if (false != ($la_facebook_access_token_expires = get_option('la_facebook_access_token_expires', false))) {
            $data['la_facebook_access_token_expires'] = $la_facebook_access_token_expires;
        }
        $this->proxyRequest($data);
    }

	public function get_sources(  ) {
		if (FF_USE_WP) {
			if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
				die( json_encode( [ 'error' => 'not_allowed' ] ) );
			}
		}

		$this->dataInit(false, false);
        if (isset($_REQUEST['id']) && !empty($_REQUEST['id'])){
            if (isset($this->sources[$_REQUEST['id']])){
                die( json_encode( $this->sources[$_REQUEST['id']] ) );
            }
            else {
                header("HTTP/1.0 404 Not Found");
                die();
            }
        }
        die( json_encode( $this->sources ) );
	}

	public final function get_boosts(){
		if ( FF_USE_WP ) {
			if ( !current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
				die( json_encode( [ 'error' => 'not_allowed' ] ) );
			}
		}

		if ( isset($_POST['not_active']) ) {
			// return dummy
			$response = [
				'status' => 'never_used', // 'active', 'cancelled', 'paused'
				'plan' => 0,
				'available' => 0,
				'expire' => 0
            ];
			echo json_encode( $response );
			die();
		}

		if (null != ($token = $this->getToken())){
			if (false != ($subscription = $this->getOption('boosts_subscription'))){
				echo $subscription;
			}
			else {
				$response = Request::post(FF_BOOST_SERVER . 'flow-flow/ff', [
					'Content-Type: application/x-www-form-urlencoded'
				], http_build_query(['action' => 'get_subscription', 'token' => $token]));

				if ($response->code == 200 && !empty($response->raw_body) && is_object($response->body)) {
					$subscription = json_encode($response->body);
					if (JSON_ERROR_NONE == json_last_error()){
						$this->setOption('boosts_subscription', $subscription);
						echo $subscription;
					}
					else {
						error_log($response->raw_body);
					}
				}
				else if ($this->isExpiredToken($response)){
					$this->get_boosts();
				}
				else {
					error_log($response->raw_body);
				}
			}
		}
		die;
	}

	public function paymentSuccess(){
		$email = $_REQUEST['email'];
		$checkout_id = $_REQUEST['checkout_id'];
		$this->setOption('boosts_email', $email);
		$this->setOption('boosts_checkout_id', $checkout_id);

		$domain = $_SERVER['HTTP_HOST'];
		$url = FF_BOOST_SERVER . 'registration?shop=' . $domain;

		$data = [
			'action' => 'domain_registration',
			'email' => $email,
			'checkout_id' => $checkout_id,
			'options' => $this->getOption('options', false, false, true),
			'fb_auth_options' => $this->getOption('fb_auth_options', false, false, true),
			'url' => get_site_url()
		];
		if (false != ($la_facebook_access_token = get_option('la_facebook_access_token', false))){
			$data['la_facebook_access_token'] = $la_facebook_access_token;
		}
		if (false != ($la_facebook_access_token_expires = get_option('la_facebook_access_token_expires', false))) {
			$data['la_facebook_access_token_expires'] = $la_facebook_access_token_expires;
		}
		Request::jsonOpts(true);
		Request::timeout(120);
		$response = Request::post($url, [
			'Content-Type: application/x-www-form-urlencoded'
		], http_build_query($data));

		if ($response->code != 200) {
			error_log($response->raw_body);
		}

		header('Location: ' . admin_url('admin.php?page=flow-flow-admin&subscription=1'), true, 301);
		die();
	}

	public function cancelSubscription() {
		if (null != ($token = $this->getToken())){
			$response = Request::post(FF_BOOST_SERVER . 'flow-flow/ff', [
				'Content-Type: application/x-www-form-urlencoded'
			], http_build_query(['action' => 'cancel_subscription', 'token' => $token]));

			if ($response->code == 200) {
				$response->body = (array)$response->body;
				if ($response->body['success']){
					$this->deleteOption('boosts_email');
					$this->deleteOption('boosts_token');
					$this->deleteOption('boosts_checkout_id');
					$this->deleteOption('boosts_subscription');
					$this->deleteBoostedFeeds();
					header('Location: ' . admin_url('admin.php?page=flow-flow-admin'), true, 301);
					die;
				}
			}
			else if ($this->isExpiredToken($response)){
				$this->cancelSubscription();
			}
			else {
				error_log($response->raw_body);
			}
		}
		error_log('FLOW-FLOW DEBUG: no subscription token' );
		http_response_code(500);
		die;
	}

	public function modifySource($source, $changed_content = true, $with_errors = false){
		$errors = '';
		$id = $source['id'];
		$enabled = $source['enabled'];
		$cache_lifetime = $source['cache_lifetime'];
		$status = isset($source['status']) ? intval($source['status']) : 0;
		$boosted = $source['boosted'];
		unset($source['id']);
		unset($source['enabled']);
		unset($source['last_update']);
		unset($source['cache_lifetime']);
		unset($source['boosted']);
		if ($with_errors && isset($source['errors'])){
			$errors = serialize($source['errors']);
		}
		if (isset($source['errors'])) unset($source['errors']);
		if (isset($source['status'])) unset($source['status']);
		if (isset($source['system_enabled'])) unset($source['system_enabled']);
		
		$in = [
				'settings' => serialize((object)$source),
				'enabled' => (int)FFSettingsUtils::YepNope2ClassicStyle($enabled, true),
				'system_enabled' => (int)FFSettingsUtils::YepNope2ClassicStyle($enabled, true),
				'last_update' => 0,
				'changed_time' => time(),
				'cache_lifetime' => $cache_lifetime,
				'status' => $status,
                'boosted' => $boosted
        ];
		$up = [
				'settings' => serialize((object)$source),
				'enabled' => (int)FFSettingsUtils::YepNope2ClassicStyle($enabled, true),
				'system_enabled' => (int)FFSettingsUtils::YepNope2ClassicStyle($enabled, true),
				'cache_lifetime' => $cache_lifetime,
				'boosted' => $boosted
        ];
		if ($changed_content) $up['last_update'] =  '0';
		if ($with_errors && !empty($errors)) $up['errors'] = $errors;
		try {
			if ( false === FFDB::conn()->query( 'INSERT INTO ?n SET `feed_id`=?s, ?u ON DUPLICATE KEY UPDATE ?u',
					$this->cache_table_name, $id, $in, $up ) ) {
						throw new Exception();
					}
					FFDB::commit();
		}
		catch ( Exception $e){
			FFDB::rollback();
		}
	}
	
	private function changedContent( $source, $old ) {
		foreach ( $source as $key => $value ) {
			$old_value = $old[$key];
			if ($key == 'status' || $key == 'enabled' || $key == 'posts' || $key == 'errors' || $key == 'last_update' || 
					$key == 'cache_lifetime' || $key == 'mod' || $key == 'posts') continue;
			if ($old_value !== $value) {
				return true;
			}
		}
		return false;
	}
	
	public function getGeneralSettings(){
		return new FFGeneralSettings($this->getOption('options', true), $this->getOption('fb_auth_options', true));
	}

	
	public function getOption( $optionName, $serialized = false, $lock_row = false, $without_cache = false ) {
		$options = FFDB::getOption($this->option_table_name, $this->plugin_slug_down . '_' . $optionName, $serialized, $lock_row, $without_cache);
		if ($optionName == 'options' && is_array($options)) {
			$options['general-uninstall'] = get_option($this->plugin_slug_down . '_general_uninstall', FFSettingsUtils::NOPE);
		}
		return $options;
	}

	public function setOption($optionName, $optionValue, $serialized = false, $cached = true){
		FFDB::setOption($this->option_table_name, $this->plugin_slug_down . '_' . $optionName, $optionValue, $serialized, $cached);
	}

	public function deleteOption($optionName){
		FFDB::deleteOption($this->option_table_name, $this->plugin_slug_down . '_' . $optionName);
	}
	
	public function streams(){
		if ($this->init) return $this->streams;
		throw new Exception('Don`t init data manager');
	}
	
	public function countFeeds(){
		return FFDB::countFeeds($this->cache_table_name);
	}
	
	public function getStream($streamId){
		$stream = $this->streams[$streamId];
		return $stream;
	}
	
	public function delete_stream(){

	    if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

		try {
			FFDB::beginTransaction();
			$id = $_POST['stream-id'];
			FFDB::deleteStream($this->streams_table_name, $this->streams_sources_table_name, $id);
			do_action('ff_after_delete_stream', $id);
			FFDB::commit();

			$this->proxyRequest($_POST);
		} catch (Exception $e){
			error_log($e->getMessage());
			error_log($e->getTraceAsString());
			FFDB::rollbackAndClose();
			die(false);
		}
		die();
	}
	
	public function canCreateCssFolder(){
		$dir = WP_CONTENT_DIR . '/resources/' . $this->context['slug'] . '/css';
		if(!file_exists($dir)){
			return mkdir($dir, 0777, true);
		}
		return true;
	}
	
	public function generateCss($stream){
		$dir = WP_CONTENT_DIR . '/resources/' . $this->context['slug'] . '/css';
		if(!file_exists($dir)){
			mkdir($dir, 0777, true);
		}
		
		$filename = $dir . "/stream-id" . $stream->id . ".css";
		if (!is_main_site()){
			$filename = $dir . '/stream-id' . $stream->id . '-'. get_current_blog_id() . '.css';
		}
		ob_start();
		/** @noinspection PhpIncludeInspection */
		include($this->context['root']  . 'views/stream-template-css.php');
		$output = ob_get_clean();
		$a = fopen($filename, 'w');
		fwrite($a, $output);
		fclose($a);
		chmod($filename, 0644);
	}
	
	public function clone_stream(){

        if (FF_USE_WP) {
            if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
                die( json_encode( [ 'error' => 'not_allowed' ] ) );
            }
        }

		$stream = $_REQUEST['stream'];

        // cleaning if error was saved in database stream model, can be removed in future, now it's needed for affected users
        if ( isset( $stream['error'] ) ) unset( $stream['error'] );

		$stream = (object)$stream;
		try{
			FFDB::beginTransaction();
			if (false !== ($count = FFDB::maxIdOfStreams($this->streams_table_name))) {
				$newId = (string) ($count + 1);
				$stream->id = $newId;
				$stream->name = "{$stream->name} copy";
				$stream->last_changes = time();
				FFDB::setStream($this->streams_table_name, $this->streams_sources_table_name, $newId, $stream);
				$this->generateCss($stream);

				$this->proxyRequest($_POST);

				FFDB::commit();
				echo json_encode($stream);
			}
			else {
				throw new Exception('Can`t get a new id for the clone stream');
			}
		}catch (Exception $e){
			FFDB::rollbackAndClose();
			error_log('clone_stream error:');
			error_log($e->getMessage());
			error_log($e->getTraceAsString());
		}
		FFDB::close();
		die();
	}
	
	protected function saveGeneralSettings($settings){
		if (isset($settings['flow_flow_options']['general-uninstall'])){
			$general_uninstall_option_name = $this->plugin_slug_down . '_general_uninstall';
			$value = ($settings['flow_flow_options']['general-uninstall'] === FFSettingsUtils::YEP) ? FFSettingsUtils::YEP : FFSettingsUtils::NOPE;
			if ( get_option( $general_uninstall_option_name) !== false ) {
				update_option( $general_uninstall_option_name, $value );
			}
			else {
				add_option( $general_uninstall_option_name, $value, '', 'no' );
			}
			unset($settings['flow_flow_options']['general-uninstall']);
		}
		
		$this->setOption('options', $settings['flow_flow_options'], true);
		return $settings;
	}
	
	protected abstract function customizeResponse(&$response);
	
	protected abstract function clean_cache($options);
	
	protected function refreshCache($streamId, $force_load_cache = false){
		//TODO: anf: refactor
		LABase::get_instance($this->context)->refreshCache($streamId, $force_load_cache);
	}

    protected function refreshCache4Source($id, $force_load_cache = false, $boosted = false){
        if (!$boosted){
            $this->saveSource($id, ['status' => '2']);

            $useIpv4 = $this->getGeneralSettings()->useIPv4();
            $use = $this->getGeneralSettings()->useCurlFollowLocation();
            $url = $this->getLoadCacheUrl( $id, $force_load_cache );
            FFSettingsUtils::get( $url, 1, false, false, $use, $useIpv4);
        }
    }
	
	public function streamsWithStatus(){
		if (false !== ($result = self::streams())){
			return $result;
		}
		return [];
	}
	
	public function sources($only_enable = false){
		if ($this->init)  return $this->sources;
		throw new Exception('Don`t init data manager');
	}
	
	//TODO: refactor posts table does not have field with name stream_id
	public function clean(array $streams = null){
		$partOfSql = $streams == null ? '' : FFDB::conn()->parse('WHERE `stream_id` IN (?a)', $streams);
		try{
			if (FFDB::beginTransaction()){
				FFDB::conn()->query('DELETE FROM ?n ?p', $this->posts_table_name, $partOfSql);
				FFDB::conn()->query('DELETE FROM ?n', $this->image_cache_table_name);
				FFDB::commit();
			}
			FFDB::rollback();
		}catch ( Exception $e){
			FFDB::rollbackAndClose();
		}
	}
	
	
	public function deleteFeed($feedId){
		try{
			if (FFDB::beginTransaction()){
				$partOfSql = FFDB::conn()->parse('WHERE `feed_id` = ?s', $feedId);
				FFDB::conn()->query('DELETE FROM ?n ?p', $this->posts_table_name, $partOfSql);
				FFDB::conn()->query('DELETE FROM ?n ?p', $this->post_media_table_name, $partOfSql);
				FFDB::conn()->query('DELETE FROM ?n ?p', $this->cache_table_name, $partOfSql);
				FFDB::conn()->query('DELETE FROM ?n ?p', $this->streams_sources_table_name, $partOfSql);
				FFDB::commit();
			}
			FFDB::rollback();
		}catch ( Exception $e){
			FFDB::rollbackAndClose();
		}
	}
	
	public function cleanFeed($feedId){
		try{
			if (FFDB::beginTransaction()){
				$partOfSql = FFDB::conn()->parse('WHERE `feed_id` = ?s', $feedId);
				FFDB::conn()->query('DELETE FROM ?n ?p', $this->posts_table_name, $partOfSql);
				FFDB::conn()->query('DELETE FROM ?n ?p', $this->post_media_table_name, $partOfSql);
				$this->setCacheInfo($feedId, [ 'last_update' => 0, 'status' => 0 ] );
				FFDB::commit();
			}
			FFDB::rollback();
		}catch (Exception $e){
			FFDB::rollbackAndClose();
		}
	}
	
	public function cleanByFeedType($feedType){
		try{
			if (FFDB::beginTransaction()){
				$feeds = FFDB::conn()->getCol('SELECT DISTINCT `feed_id` FROM ?n WHERE `post_type` = ?s', $this->posts_table_name, $feedType);
				if (!empty($feeds)){
					FFDB::conn()->query("DELETE FROM ?n WHERE `feed_id` IN (?a)", $this->posts_table_name, $feeds);
					FFDB::conn()->query("DELETE FROM ?n WHERE `feed_id` IN (?a)", $this->post_media_table_name, $feeds);
					FFDB::commit();
				}
			}
			FFDB::rollback();
		}catch (Exception $e){
			FFDB::rollbackAndClose();
		}
	}
	
	public function addOrUpdatePost($only4insertPartOfSql, $imagePartOfSql, $mediaPartOfSql, $common){
		$sql = "INSERT INTO ?n SET ?p, ?p ?p ?u ON DUPLICATE KEY UPDATE ?p ?p ?u";
		if (false == FFDB::conn()->query($sql, $this->posts_table_name, $only4insertPartOfSql, $imagePartOfSql, $mediaPartOfSql, $common, $imagePartOfSql, $mediaPartOfSql, $common)){
			throw new Exception(FFDB::conn()->conn->error);
		}
	}
	
	public function updateAdditionalInfo($posts){
		foreach ($posts as $post) {
			$sql = FFDB::conn()->parse('UPDATE ?n SET `post_additional` = ?s WHERE `post_id` = ?s AND `feed_id` = ?s AND `post_type` = ?s',
				$this->posts_table_name, json_encode($post->additional), $post->id, $post->feed_id, $post->type);
			if (false == FFDB::conn()->query($sql)){
				throw new Exception(FFDB::conn()->conn->error);
			}
		}
	}
	
	/**
	 * @param string $feedId
	 *
	 * @return array|false
	 */
	public function getIdPosts($feedId){
		return FFDB::conn(true)->getCol('SELECT `post_id` FROM ?n WHERE `feed_id`=?s', $this->posts_table_name, $feedId);
	}
	
	public function getPostsIf($fields, $condition, $order, $offset = null, $limit = null){
		$limitPart = ($offset !== null && $offset !== null) ? FFDB::conn()->parse("LIMIT ?i, ?i", $offset, $limit) : '';
		$sql = FFDB::conn()->parse("SELECT ?p FROM ?n post INNER JOIN ?n stream ON stream.feed_id = post.feed_id INNER JOIN ?n cach ON post.feed_id = cach.feed_id WHERE ?p ORDER BY ?p ?p",
				$fields, $this->posts_table_name, $this->streams_sources_table_name, $this->cache_table_name, $condition, $order, $limitPart);
		return FFDB::conn()->getAll($sql);
	}
	
	public function getPostsIf2($fields, $condition){
		return FFDB::conn()->getAll("SELECT ?p FROM ?n post INNER JOIN ?n stream ON stream.feed_id = post.feed_id INNER JOIN ?n cach ON post.feed_id = cach.feed_id WHERE ?p ORDER BY post.post_timestamp DESC, post.post_id",
				$fields, $this->posts_table_name, $this->streams_sources_table_name, $this->cache_table_name, $condition);
	}
	
	public function countPostsIf($condition){
		return FFDB::conn()->getOne('SELECT COUNT(*) FROM ?n post INNER JOIN ?n stream ON stream.feed_id = post.feed_id INNER JOIN ?n cach ON post.feed_id = cach.feed_id WHERE ?p',
				$this->posts_table_name, $this->streams_sources_table_name, $this->cache_table_name, $condition);
	}
	
	public function getLastUpdateHash($streamId){
		return $this->getHashIf(FFDB::conn()->parse('stream.`stream_id` = ?s', $streamId));
	}
	
	public function getHashIf($condition){
		return FFDB::conn()->getOne("SELECT MAX(post.creation_index) FROM ?n post INNER JOIN ?n stream ON stream.feed_id = post.feed_id INNER JOIN ?n cach ON post.feed_id = cach.feed_id WHERE cach.boosted = 'nope' AND ?p",
				$this->posts_table_name, $this->streams_sources_table_name, $this->cache_table_name, $condition);
	}
	
	public function getLastUpdateTime($streamId){
		return FFDB::conn()->getOne('SELECT MAX(`last_update`) FROM ?n `cach` inner join ?n `st2src` on `st2src`.`feed_id` = `cach`.`feed_id` WHERE `stream_id` = ?s',  $this->cache_table_name, $this->streams_sources_table_name, $streamId);
	}
	
	public function getLastUpdateTimeAllStreams(){
		return FFDB::conn()->getIndCol('stream_id', 'SELECT MAX(`last_update`), `stream_id` FROM ?n `cach` inner join ?n `st2src` on `st2src`.`feed_id` = `cach`.`feed_id` GROUP BY `stream_id`',  $this->cache_table_name, $this->streams_sources_table_name);
	}
	
	public function systemDisableSource($feedId, $enabled){
		$values = [ 'system_enabled' => $enabled ];
		if($enabled == 0){
			$values['send_email'] = 0;
		}
		return $this->saveSource($feedId, $values);
	}

	public function saveSource( $feedId, $values ) {
		return FFDB::saveFeed($this->cache_table_name, $feedId, $values);
	}

	/**
	 * @deprecated
	 * Use \flow\db\LADBManager::saveSource
	 */
	public function setCacheInfo($feedId, $values){
		$sql = 'INSERT INTO ?n SET `feed_id`=?s, ?u ON DUPLICATE KEY UPDATE ?u';
		return FFDB::conn()->query( $sql, $this->cache_table_name, $feedId, $values, $values );
	}

	public function removeOldRecords($c_count){
		$result = FFDB::conn()->getAll('select count(*) as `count`, `feed_id` from ?n group by `feed_id` order by 1 desc', $this->posts_table_name);
		foreach ( $result as $row ) {
			$count = (int)$row['count'];
			if ($count > $c_count) {
				$feed = $row['feed_id'];
				$count = $count - $c_count;
				$sub_query = FFDB::conn()->parse('select max(tmp.`post_timestamp`) from (select `post_timestamp` from ?n where `feed_id` = ?s order by `post_timestamp` limit 0, ?i) as tmp',$this->posts_table_name, $feed, $count);
				$sub_query2 = FFDB::conn()->parse('select tmp2.post_id from ?n as tmp2 where tmp2.post_timestamp <= (?p)', $this->posts_table_name, $sub_query);
				FFDB::conn()->query('delete from ?n where feed_id = ?s and post_id in (?p)', $this->post_media_table_name, $feed, $sub_query2);
				FFDB::conn()->query('delete from ?n where feed_id = ?s and post_timestamp <= (?p)', $this->posts_table_name, $feed, $sub_query);
				continue;
			}
		}
	}
	
	public function setPostStatus($status, $condition, $creation_index = null){
		$sql = "UPDATE ?n SET `post_status` = ?s";
		$sql .= ($creation_index != null) ? ", `creation_index` = " . $creation_index . " ?p" : " ?p";
		if (false == FFDB::conn()->query($sql, $this->posts_table_name, $status, $condition)){
			throw new Exception(FFDB::conn()->conn->error);
		}
	}

    public abstract function getLoadCacheUrl($streamId = null, $force = false);

	private function getStreamFromRequestWithoutErrors(){
		$stream = $_POST['stream'];

		// cleaning if error was saved in database stream model, can be removed in future, now it's needed for affected users
		if ( isset( $stream['error'] ) ) unset( $stream['error'] );

		// casting object
		return (object)$stream;
	}

	private function checkSecurity() {
		if (FF_USE_WP) {
			if (!current_user_can('manage_options') || !check_ajax_referer( 'flow_flow_nonce', 'security', false ) ) {
				die( json_encode( [ 'error' => 'not_allowed' ] ) );
			}
		}
	}

	public function getBoostSources(){
		$token = $this->getToken();
		if (!empty($token)){
			$this->getOption('options');
			Request::jsonOpts(true);
			Request::timeout(120);
			$response = Request::post(FF_BOOST_SERVER . 'flow-flow/ff', [
				'Content-Type: application/x-www-form-urlencoded'
			], http_build_query(['action' => 'get_sources', 'token' => $token]));

			if ($response->code == 200) {
				foreach ($response->body as &$source){
					FFDB::prepareSource($source);
				}
				return $response->body;
			}
			else if ($this->isExpiredToken($response)){
				return $this->getBoostSources();
			}
		}
		return [];
	}

    /**
     * @param array $data
     *
     * @return array|mixed|Response
     */
    private function proxyRequest($data){
		$response = [];
		if (null != ($token = $this->getToken())){
			Request::jsonOpts(true);
			Request::timeout(120);
            $data['token'] = $token;
			$response = Request::post(FF_BOOST_SERVER . 'flow-flow/ff', [
				'Content-Type: application/x-www-form-urlencoded'
			], http_build_query($data));
			if ($this->isExpiredToken($response)){
				$response = $this->proxyRequest($data);
			}
		}

		return $response;
	}

	public function getToken( $force = false ) {
		$email = $this->getOption('boosts_email');
		if (!empty($email)){
			$domain = $_SERVER['HTTP_HOST'];

			$token = $this->getOption('boosts_token');
			if ($force || (false == $token)){
				Request::jsonOpts(true);
				Request::timeout(120);
				$response = Request::post(FF_BOOST_SERVER . 'token', [
					'Content-Type: application/form-data'
				], ['domain' => $domain, 'email' => $email]);
				if ($response->code == 200 && isset($response->body['token']) && is_string($response->body['token'])) {
					$token = $response->body['token'];
					$this->setOption('boosts_token', $token);
					FFDB::commit();
				}
				else {
					return null;
				}
			}
			return $token;
		}
		return null;
	}

	private function isExpiredToken( $response ) {
		if ($response->code == 400 &&
		    ((isset($response->body->error) && $response->body->error == 'Provided token is expired.') ||
			(isset($response->body['error']) && $response->body['error'] == 'Provided token is expired.'))
		){
			$this->deleteOption('boosts_token');
			$this->deleteOption('boosts_subscription');
			FFDB::commit();
			return true;
		}
		return false;
	}

    private function deleteBoostedFeeds() {
        $feeds = FFDB::conn()->getCol('SELECT `feed_id` FROM ?n WHERE `boosted` = ?s', $this->cache_table_name, FFSettingsUtils::YEP);
        FFDB::conn()->query('DELETE FROM ?n WHERE `feed_id` IN (?a)', $this->streams_sources_table_name, $feeds);
        $values = ['system_enabled' => 0, 'boosted' => FFSettingsUtils::NOPE];
        FFDB::conn()->query('UPDATE ?n SET ?u WHERE `boosted` = ?s', $this->cache_table_name, $values, FFSettingsUtils::YEP);
    }
}
