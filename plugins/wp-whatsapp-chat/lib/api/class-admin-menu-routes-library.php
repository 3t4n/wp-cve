<?php

namespace QuadLayers\QLWAPP\Api;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Box\Get as Box_Get;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Box\Post as Box_Post;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Button\Get as Button_Get;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Button\Post as Button_Post;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Contacts\Get as Contacts_Get;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Contacts\Edit as Contacts_Edit;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Contacts\Create as Contacts_Create;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Contacts\Delete as Contacts_Delete;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Display\Get as Display_Get;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Display\Post as Display_Post;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Scheme\Get as Scheme_Get;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Scheme\Post as Scheme_Post;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Settings\Get as Settings_Get;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\Settings\Post as Settings_Post;

use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\WooCommerce\Get as WooCommerce_Get;
use QuadLayers\QLWAPP\Api\Rest\Admin_Menu\WooCommerce\Post as WooCommerce_Post;

use QuadLayers\QLWAPP\Api\Rest\Route as Route_Interface;

class Admin_Menu_Routes_Library {
	protected $routes                = array();
	protected static $rest_namespace = 'quadlayers/wp-whatsapp-chat';
	protected static $instance;

	private function __construct() {
		add_action( 'init', array( $this, '_rest_init' ) );
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
		new Box_Get();
		new Box_Post();

		new Button_Get();
		new Button_Post();

		new Contacts_Get();
		new Contacts_Create();
		new Contacts_Edit();
		new Contacts_Delete();

		new Display_Get();
		new Display_Post();

		new Scheme_Get();
		new Scheme_Post();

		new Settings_Get();
		new Settings_Post();

		new WooCommerce_Get();
		new WooCommerce_Post();
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
