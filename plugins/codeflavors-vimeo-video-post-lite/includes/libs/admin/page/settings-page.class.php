<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Admin\Admin;
use Vimeotheque\Options\Options;
use Vimeotheque\Plugin;
use Vimeotheque\Post\Post_Type;
use Vimeotheque\Vimeo_Api\Vimeo_Oauth;
use WP_Error;

/**
 * Class Settings_Page
 * @package Vimeotheque\Admin
 */
class Settings_Page extends Page_Abstract implements Page_Interface{
	/**
	 * @var Vimeo_Oauth
	 */
	private $vimeo_oauth;

	/**
	 * @var WP_Error
	 */
	private $error;
	/**
	 * @var \Vimeotheque\Post\Post_Type
	 */
	private $cpt;

	/**
	 * Settings_Page constructor.
	 *
	 * @param Admin $admin
	 * @param $page_title
	 * @param $menu_title
	 * @param $slug
	 * @param $parent
	 * @param $capability
	 */
	public function __construct( Admin $admin, $page_title, $menu_title, $slug, $parent, $capability ) {
		parent::__construct( $admin, $page_title, $menu_title, $slug, $parent, $capability );
		Settings_Helper::init();

		$this->cpt = $admin->get_post_type();
	}

	/**
	 * Generates page HTML
	 */
	public function get_html(){
		$options = $this->options_obj()->get_options();
		$player_opt = $this->player_options_obj()->get_options();

		/**
		 * Filter that allows addition of extra tabs to plugin Settings page.
		 * To pass a new tab to this filter, give it an array having this format:
		 *
		 * $tabs['my-tab-id'] = array(
		 *		'title' => __('My tab title'),
		 *      'callback' => callback function that will create the tab output (ie. array( $this, 'method_name' )),
		 *      'before' => false // The tab ID that the added tab should be included after ( post_options, content_options, image_options, import_options, embed_options, auth_options ). False to be added at the end of the tabs
		 * )
		 *
		 * @param array $tabs   The new tabs to be registered.
		 */
		$extra_tabs = apply_filters( 'vimeotheque\admin\page\settings_tabs', [] );

		include VIMEOTHEQUE_PATH . 'views/plugin_settings.php';
	}

	/**
	 * Page on_load callback
	 */
	public function on_load() {
		$options = $this->options_obj()->get_options();

		$this->vimeo_oauth = new Vimeo_Oauth(
			$options['vimeo_consumer_key'],
			$options['vimeo_secret_key'],
			$options['oauth_token'],
			// you must use this instead of menu_page_url() to avoid API error
			admin_url( 'edit.php?post_type=' . $this->cpt->get_post_type() . '&page=' . $this->get_menu_slug() )
		);

		if( $this->set_unauth_token() ) {
			$options = $this->options_obj()->get_options( true );
		}

		/**
		 * Action triggered on settings page load event.
		 *
		 * @param Options $options Vimeotheque\Options\Options object reference.
		 */
		do_action( 'vimeotheque\admin\page\settings_load', $this->options_obj() );

		if( isset( $_POST['cvm_wp_nonce'] ) ){
			if( check_admin_referer('cvm-save-plugin-settings', 'cvm_wp_nonce') ){
				/**
				 * Action running before the settings are saved.
				 */
				do_action( 'vimeotheque\admin\before_settings_save' );

				$updated_settings = $this->update_settings();

				/**
				 * Action running after the settings are saved.
				 *
				 * @param array $updated_settings The saved settings array.
				 */
				do_action( 'vimeotheque\admin\after_settings_save', $updated_settings );

				/**
				 * Action running before the player settings are saved.
				 */
				do_action( 'vimeotheque\admin\before_player_settings_save' );

				$player_settings = $this->update_player_settings();

				/**
				 * Action running after the player settings are saved.
				 *
				 * @param array $player_settings The player settings saved in database.
				 */
				do_action( 'vimeotheque\admin\after_player_settings_save', $player_settings );
			}
			wp_redirect( 'edit.php?post_type=' . $this->cpt->get_post_type() . '&page=cvm_settings' );
			die();
		}

		if( isset( $_GET[ 'clear_oauth' ] ) && 'true' == $_GET[ 'clear_oauth' ] ){
			if( check_admin_referer( 'cvm-clear-oauth-token', 'cvm_nonce' ) ){
				$options['vimeo_consumer_key'] = '';
				$options['vimeo_secret_key'] = '';
				$options['oauth_token']	= '';
				$options['oauth_secret'] = '';
				$options['vimeo_access_granted'] = false;
				$this->options_obj()->update_options( $options );
			}
			wp_redirect( 'edit.php?post_type=' . $this->cpt->get_post_type() . '&page=cvm_settings' );
			die();
		}

		$this->_enqueue_assets();
	}

	/**
	 * Utility function, updates plugin settings
	 */
	private function update_settings(){
		$defaults = $this->options_obj()->get_defaults();

		foreach( $defaults as $key => $val ){
			if( is_numeric( $val ) ){
				if( isset( $_POST[ $key ] ) ){
					$defaults[ $key ] = (int)$_POST[ $key ];
				}
				continue;
			}
			if( is_bool( $val ) ){
				$defaults[ $key ] = isset( $_POST[ $key ] );
				continue;
			}

			// trim strings
			if( isset( $_POST[ $key ] ) ){
				$defaults[ $key ] = trim( $_POST[ $key ] );
			}
		}

		// rewrite
		$plugin_settings = $this->options_obj()->get_options();
		$flush_rules = false;
		if( isset( $_POST['post_slug'] ) ){
			$post_slug = sanitize_title( $_POST['post_slug'] );
			if( !empty( $_POST['post_slug'] ) && $plugin_settings['post_slug'] !== $post_slug ){
				$defaults['post_slug'] = $post_slug;
				$flush_rules = true;
			}else{
				$defaults['post_slug'] = $plugin_settings['post_slug'];
			}
		}

		if( isset( $_POST['taxonomy_slug'] ) ){
			$tax_slug = sanitize_title( $_POST['taxonomy_slug'] );
			/**
			 * Check that taxonomy slug is not empty, is updated and is not the same as the post slug
			 */
			if( !empty( $_POST['taxonomy_slug'] ) && $plugin_settings['taxonomy_slug'] !== $tax_slug && $_POST['taxonomy_slug'] != $defaults['post_slug'] ){
				$defaults['taxonomy_slug'] = $tax_slug;
				$flush_rules = true;
			}else{
				$defaults['taxonomy_slug'] = $plugin_settings['taxonomy_slug'];
			}
		}

		if( isset( $_POST['tag_slug'] ) ){
			$tag_slug = sanitize_title( $_POST['tag_slug'] );
			if( !empty( $_POST['tag_slug'] ) && $plugin_settings['tag_slug'] !== $tag_slug && $_POST['tag_slug'] != $defaults['post_slug'] && $_POST['tag_slug'] != $defaults['taxonomy_slug'] ){
				$defaults['tag_slug'] = $tag_slug;
				$flush_rules = true;
			}else{
				$defaults['tag_slug'] = $plugin_settings['tag_slug'];
			}
		}

		// reset OAuth if user changes the keys
		if( isset( $_POST['vimeo_consumer_key'] ) && isset( $_POST['vimeo_secret_key'] ) ){
			if(
				($_POST['vimeo_consumer_key'] != $plugin_settings['vimeo_consumer_key']) ||
				($_POST['vimeo_secret_key'] != $plugin_settings['vimeo_secret_key'] )
			){
				$defaults['oauth_token'] = '';
				$defaults['oauth_secret'] = '';
			}
		}else{
			// if the consumer keys are not sent by POST, set the old values
			$defaults['vimeo_consumer_key'] = $plugin_settings['vimeo_consumer_key'];
			$defaults['vimeo_secret_key'] = $plugin_settings['vimeo_secret_key'];
			$defaults['oauth_token'] = $plugin_settings['oauth_token'];
		}

		$this->options_obj()->update_options( $defaults );

		if( $flush_rules ){
			// create rewrite ( soft )
			// register custom post
			Plugin::instance()->get_cpt()->register_post();
			flush_rewrite_rules();
		}

		return $defaults;
	}

	/**
	 * Update general player settings
	 */
	private function update_player_settings(){
		$defaults = $this->player_options_obj()->get_defaults();
		foreach( $defaults as $key => $val ){
			if( is_numeric( $val ) ){
				if( isset( $_POST[ $key ] ) ){
					$defaults[ $key ] = (int)$_POST[ $key ];
				}else{
					$defaults[ $key ] = 0;
				}
				continue;
			}
			if( is_bool( $val ) ){
				$defaults[ $key ] = isset( $_POST[ $key ] );
				continue;
			}

			if( isset( $_POST[ $key ] ) ){
				$defaults[ $key ] = $_POST[ $key ];
			}
		}

		$this->player_options_obj()->update_options( $defaults );

		return $defaults;
	}

	/**
	 * Set token for unauthenticated API requests
	 */
	private function set_unauth_token(){
		$options = $this->options_obj()->get_options();
		if( !empty( $options['vimeo_consumer_key'] ) && !empty( $options['vimeo_secret_key'] ) ){
			if( empty( $options['oauth_token'] ) || ( isset( $options['oauth_secret'] ) && empty( $options['oauth_secret'] ) ) ){
				// account token
				$token = $this->vimeo_oauth->get_unauth_token();
				if( !is_wp_error( $token ) ){
					// set the token
					$options['oauth_token'] 	= $token;
				}else{
					// reset everything in case of error
					$options['vimeo_consumer_key'] = '';
					$options['vimeo_secret_key'] = '';
					$options['oauth_token'] = '';
					// set a notice for the error
					parent::set_error( $token->get_error_code(), $token->get_error_message() );
				}

				$this->options_obj()->update_options( $options );
				return true;
			}
		}
	}

	/**
	 * Outputs a link that allows users to clear OAuth credentials
	 *
	 * @param string $text
	 * @param string $echo
	 * @return void|string
	 */
	private function clear_oauth_credentials_link( $text = '', $class='', $echo = true ){
		if( empty( $text ) ){
			$text = __( 'Clear OAuth credentials', 'codeflavors-vimeo-video-post-lite' );
		}

		$options = $this->options_obj()->get_options();
		if( empty( $options[ 'vimeo_consumer_key' ] ) || empty( $options[ 'vimeo_secret_key' ] ) ){
			return;
		}

		$nonce = wp_create_nonce( 'cvm-clear-oauth-token' );
		$url = menu_page_url( 'cvm_settings', false ) . '&clear_oauth=true&cvm_nonce=' . $nonce . '#auth-options';
		$output = sprintf( '<a href="%s" class="%s">%s</a>', $url, esc_attr( $class ), $text );

		if( $echo ){
			echo $output;
		}

		return $output;
	}

	/**
	 * Enqueue scripts and CSS for this page
	 */
	private function _enqueue_assets(){
		wp_enqueue_style(
			'cvm-plugin-settings',
			VIMEOTHEQUE_URL . 'assets/back-end/css/plugin-settings.css',
			false
		);

		wp_enqueue_script(
			'cvm-tabs',
			VIMEOTHEQUE_URL . 'assets/back-end/js/tabs.js',
			[ 'jquery', 'jquery-ui-tabs' ]
		);

		wp_enqueue_script(
			'cvm-settings',
			VIMEOTHEQUE_URL . 'assets/back-end/js/plugin-settings.js',
			[ 'jquery' ]
		);

		wp_enqueue_script(
			'cvm-video-edit',
			VIMEOTHEQUE_URL . 'assets/back-end/js/video-edit.js',
			[ 'jquery', 'wp-color-picker' ],
			'1.0'
		);
		wp_enqueue_style('wp-color-picker');
	}

	/**
	 * Get plugin options object
	 * @return Options
	 */
	private function options_obj(){
		return Plugin::instance()->get_options_obj();
	}

	/**
	 * Get player options object
	 * @return Options
	 */
	private function player_options_obj(){
		return Plugin::instance()->get_embed_options_obj();
	}
}