<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class ContentViews_Block_TemplatePattern {

	protected static $instance	 = null;
	private $api_url			 = CVB_API_URL . 'block-template-pattern';
	private $allinfo = []; // store templates and patterns

	static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function __construct() {
		// add_action( 'init', [ $this, 'init' ] );
		// add_action( 'contentviews_block_cron', [ $this, 'update_data' ] );
		add_action( 'rest_api_init', [ $this, 'api_endpoint' ] );
	}

	public function init() {
		if ( current_user_can( 'edit_posts' ) ) {
			if ( !wp_next_scheduled( 'contentviews_block_cron' ) ) {
				wp_schedule_event( strtotime( date( 'Y-m-d' ) . ' midnight' ), 'daily', 'contentviews_block_cron' );
			}
		}
	}

	public function update_data() {
		$data = $this->get_from_api();
		if ( !empty( $data ) ) {
			$this->save_data( $data );
		}
	}

	public function api_endpoint() {
		register_rest_route(
			'contentviews/v1', '/block_template_pattern', array(
			array(
				'methods'				 => 'POST',
				'callback'				 => array( $this, 'get_block_template_pattern' ),
				'permission_callback'	 => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		)
		);

		register_rest_route(
		'contentviews/v1', '/update_block_template_pattern', array(
			array(
				'methods'				 => 'POST',
				'callback'				 => array( $this, 'synch_block_template_pattern' ),
				'permission_callback'	 => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		)
		);
	}

	function get_block_template_pattern() {
		if ( !empty( $this->allinfo ) ) {
			return $this->allinfo;
		}
		$cached = $this->get_from_cache();
		if ( !empty( $cached ) ) {
			return $cached;
		}
		$update = $this->synch_block_template_pattern();
		if ( !empty( $update ) ) {
			$this->allinfo = $update;
			return $this->allinfo;
		}
		return false;
	}

	function synch_block_template_pattern() {
		$data = $this->get_from_api();
		if ( !empty( $data ) ) {
			$this->save_data( $data );
			return $data;
		}
		return false;
	}

	private function get_from_cache() {
		$cache_file = wp_upload_dir()[ 'basedir' ] . '/content-views/templates_patterns.json';
		if ( file_exists( $cache_file ) ) {
			$file			 = file_get_contents( $cache_file );
			$this->allinfo	 = $file;
			return $this->allinfo;
		}
		return false;
	}

	private function get_from_api() {
		$response = wp_remote_post(
			$this->api_url
		);

		$res = wp_remote_retrieve_body( $response );
		
		// only return if valid json
		json_decode( $res );
		return (json_last_error() === JSON_ERROR_NONE) ? $res : null;
	}

	private function save_data( $data ) {
		try {
			$cache_file_dir = wp_upload_dir()[ 'basedir' ] . '/content-views';
			if ( !file_exists( $cache_file_dir ) ) {
				wp_mkdir_p( $cache_file_dir );
			}
			file_put_contents( $cache_file_dir . '/templates_patterns.json', $data );
			return true;
		} catch ( Exception $e ) {
			error_log( $e->getMessage() );
			return false;
		}
	}


}

ContentViews_Block_TemplatePattern::get_instance();
