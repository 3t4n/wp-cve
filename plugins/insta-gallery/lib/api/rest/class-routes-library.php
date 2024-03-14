<?php
namespace QuadLayers\IGG\Api\Rest;

use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Accounts\Get as Api_Rest_Accounts_Get;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Accounts\Create as Api_Rest_Accounts_Create;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Accounts\Delete as Api_Rest_Accounts_Delete;

use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds\Get as Api_Rest_Feeds_Get;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds\Edit as Api_Rest_Feeds_Edit;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds\Create as Api_Rest_Feeds_Create;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds\Delete as Api_Rest_Feeds_Delete;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Feeds\Clear_Cache as Api_Rest_Feeds_Clear_Cache;

use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Settings\Get as Api_Rest_Settings_Get;
use QuadLayers\IGG\Api\Rest\Endpoints\Backend\Settings\Save as Api_Rest_Settings_Save;

use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\User_Profile as Api_Rest_User_Profile;
use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\User_Media as Api_Rest_User_Media;
use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\Hashtag_Media as Api_Rest_Hashtag_Media;
use QuadLayers\IGG\Api\Rest\Endpoints\Route as Route_Interface;

class Routes_Library {

	protected static $instance;
	protected $routes = array();

	private static $rest_namespace = 'quadlayers/instagram';

	private function __construct() {
		add_action( 'rest_api_init', array( $this, '_rest_init' ) );
	}

	public static function get_namespace() {
		return self::$rest_namespace;
	}

	public function get_routes( $route_path = null ) {
		if ( ! $route_path ) {
			return $this->routes;
		}
		if ( isset( $this->routes[ $route_path ] ) ) {
			return $this->routes[ $route_path ];
		}
	}

	public function register( Route_Interface $instance ) {
		$this->routes[ $instance::get_name() ] = $instance;
	}

	public function _rest_init() {
		// Backend
		// Accounts
		new Api_Rest_Accounts_Get();
		new Api_Rest_Accounts_Create();
		new Api_Rest_Accounts_Delete();
		// Feeds
		new Api_Rest_Feeds_Get();
		new Api_Rest_Feeds_Create();
		new Api_Rest_Feeds_Edit();
		new Api_Rest_Feeds_Delete();
		new Api_Rest_Feeds_Clear_Cache();
		// Settings
		new Api_Rest_Settings_Get();
		new Api_Rest_Settings_Save();
		// Frontend
		new Api_Rest_User_Profile();
		new Api_Rest_User_Media();
		new Api_Rest_Hashtag_Media();
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
