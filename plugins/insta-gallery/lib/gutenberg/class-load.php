<?php

namespace QuadLayers\IGG\Gutenberg;

use QuadLayers\IGG\Helpers as Helpers;
use QuadLayers\IGG\Models\Feed as Models_Feed;
use QuadLayers\IGG\Models\Account as Models_Account;
use QuadLayers\IGG\Models\Setting as Models_Settings;
use QuadLayers\IGG\Frontend\Load as Frontend;
use QuadLayers\IGG\Backend\Load as Backend;

use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\User_Profile as Api_Rest_User_Profile;
use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\User_Media as Api_Rest_User_Media;
use QuadLayers\IGG\Api\Rest\Endpoints\Frontend\Hashtag_Media as Api_Rest_Hashtag_Media;

class Load {

	protected static $instance;

	private function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'register_scripts' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_scripts' ) );
		add_action( 'init', array( $this, 'register_block' ) );
	}

	public function register_scripts() {
		Frontend::instance()->register_scripts();
		Backend::instance()->register_scripts();
		$gutenberg = include QLIGG_PLUGIN_DIR . 'build/gutenberg/js/index.asset.php';
		wp_register_style( 'qligg-gutenberg-editor', plugins_url( '/build/gutenberg/css/editor.css', QLIGG_PLUGIN_FILE ), array(), QLIGG_PLUGIN_VERSION );
		wp_register_script( 'qligg-gutenberg', plugins_url( '/build/gutenberg/js/index.js', QLIGG_PLUGIN_FILE ), $gutenberg['dependencies'], $gutenberg['version'], true );
		wp_localize_script(
			'qligg-gutenberg',
			'qligg_gutenberg',
			array(
				'image_url'                  => plugins_url( '/assets/backend/img', QLIGG_PLUGIN_FILE ),
				'access_token_link_business' => Helpers::get_business_access_token_link(),
				'access_token_link_personal' => Helpers::get_personal_access_token_link(),
			)
		);
		/**
		 * Fix missing qligg_frontend object in gutenberg script
		 * Frontend is loaded in the gutenberg editor script directly
		 */
		$models_settings = new Models_Settings();
		$settings        = $models_settings->get();
		wp_localize_script(
			'qligg-gutenberg',
			'qligg_frontend',
			array(
				'settings'       => $settings,
				'restRoutePaths' => array(
					'username'    => Api_Rest_User_Media::get_rest_url(),
					'tag'         => Api_Rest_Hashtag_Media::get_rest_url(),
					'userprofile' => Api_Rest_User_Profile::get_rest_url(),
				),
			)
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'qligg-gutenberg-editor' );
		wp_enqueue_script( 'qligg-gutenberg' );
	}

	public function register_block() {
		Frontend::instance()->register_scripts();
		Backend::instance()->register_scripts();
		register_block_type(
			'qligg/box',
			array(
				'attributes'      => $this->get_attributes(),
				'render_callback' => array( $this, 'render_callback' ),
				'style'           => [ 'qligg-swiper', 'qligg-frontend', 'qligg-backend' ],
				'script'          => [ 'qligg-swiper', 'masonry' ],
				'editor_style'    => [ 'qligg-swiper', 'qligg-frontend', 'qligg-backend' ],
				'editor_script'   => [ 'qligg-swiper', 'masonry' ],
			)
		);
	}

	public function render_callback( $attributes, $content, $block = array() ) {
		return Frontend::instance()->create_shortcode( $attributes );
	}

	private function get_attributes() {

		$account  = new Models_Account();
		$accounts = $account->get();

		$feed_model = new Models_Feed();
		$feed_arg   = $feed_model->get_args();

		$attributes = array();

		foreach ( $feed_arg as $id => $value ) {
			$attributes[ $id ] = array(
				'type'    => array( 'string', 'object', 'array', 'boolean', 'number', 'null' ),
				'default' => $value,
			);
			if ( $id === 'account_id' ) {
				$attributes[ $id ] = array(
					'type'    => array( 'string', 'object', 'array', 'boolean', 'number', 'null' ),
					'default' => (string) array_key_first( $accounts ),
				);
			}
		}

		return $attributes;
	}

	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
