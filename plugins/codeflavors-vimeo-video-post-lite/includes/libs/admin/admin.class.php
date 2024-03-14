<?php

namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Exception;
use Vimeotheque\Admin\Menu\Menu_Pages;
use Vimeotheque\Admin\Notice\Admin_Notices;
use Vimeotheque\Admin\Notice\Plugin_Notice;
use Vimeotheque\Admin\Notice\Review_Notice;
use Vimeotheque\Admin\Notice\User_Notice\Message;
use Vimeotheque\Admin\Notice\User_Notice\User;
use Vimeotheque\Admin\Notice\Vimeo_Api_Notice;
use Vimeotheque\Admin\Page\Automatic_Import_Page;
use Vimeotheque\Admin\Page\Extensions_Page;
use Vimeotheque\Admin\Page\Go_Pro_Page;
use Vimeotheque\Admin\Page\List_Videos_Page;
use Vimeotheque\Admin\Page\Post_Edit_Page;
use Vimeotheque\Admin\Page\Settings_Page;
use Vimeotheque\Admin\Page\Setup_Page;
use Vimeotheque\Admin\Page\Status_Page;
use Vimeotheque\Admin\Page\Video_Import_Page;
use Vimeotheque\Extensions\Extensions;
use Vimeotheque\Helper;
use Vimeotheque\Post\Post_Type;

/**
 * Admin pages
 * 
 * @author CodeFlavors
 * @ignore
 */
class Admin{

	/**
	 * Store reference to Post_Type object
	 * 
	 * @var Post_Type
	 */
	private $post_type;
	/**
	 * Ajax Class reference
	 * 
	 * @var Ajax_Actions
	 */
	private $ajax;
	/**
	 * @var Menu_Pages
	 */
	private $admin_menu;
	/**
	 * @var Extensions
	 */
	private $extensions;

	/**
	 *
	 * @param \Vimeotheque\Post\Post_Type $post_type
	 */
	public function __construct( Post_Type $post_type ){
		// store object reference
		$this->post_type = $post_type;
		$this->extensions = new Extensions();

		add_action( 'wp_loaded', [ $this, 'init' ], -20 );

		// add admin capabilities
		add_action( 'init', [
			$this,
			'add_capabilities'
		], -999 );

		// add columns to posts table
		add_filter( 'manage_edit-' . $this->post_type->get_post_type() . '_columns', [
				$this, 
				'extra_columns'
		] );

		add_action( 'manage_' . $this->post_type->get_post_type() . '_posts_custom_column', [
				$this, 
				'output_extra_columns'
		], 10, 2 );

		add_action( 'admin_init', [
			$this,
			'register_notices'
		] );

		// alert if setting to import as post type post by default is set on all plugin pages
		add_action( 'admin_notices', [
			$this,
			'admin_notices'
		], 10 );

		add_action( 'admin_init', [
			$this,
			'privacy_policy'
		] );

		add_filter( 'plugin_action_links_' . plugin_basename( VIMEOTHEQUE_FILE ), [
			$this,
			'action_links'
		] );

		add_action(
			'admin_init',
			/**
			 * Redirect to Setup.
			 *
			 * Redirect after plugin activation to the plugin Setup Guide page.
			 */
			function(){
				if( current_user_can( 'manage_options' ) && get_transient( 'vimeotheque_setup_activated' ) ){
					delete_transient( 'vimeotheque_setup_activated' );
					wp_redirect( $this->get_admin_menu()->get_page( 'vimeotheque_setup' )->get_menu_page( false ) );
					die();
				}
			}
		);
	}

	/**
	 * Initialize
	 */
	public function init(){
		// start AJAX actions
		$this->ajax = new Ajax_Actions( $this->post_type );
		// start post edit single video page

		new Post_Edit_Page( $this );

		$this->register_pages();
	}

	/**
	 * Add subpage for custom post type admin menu
	 */
	public function register_pages(){

		$this->admin_menu = new Menu_Pages(
			new Video_Import_Page(
				$this,
				__( 'Import videos', 'codeflavors-vimeo-video-post-lite' ),
				__( 'Import videos', 'codeflavors-vimeo-video-post-lite' ),
				'cvm_import',
				'edit.php?post_type=' . $this->post_type->get_post_type(),
				$this->get_capability('manual_import')
			)
		);

		$this->admin_menu->register_page(
			new Extensions_Page(
				$this,
				__( 'Add-ons', 'codeflavors-vimeo-video-post-lite' ),
				__( 'Add-ons', 'codeflavors-vimeo-video-post-lite' ),
				'vimeotheque_extensions',
				'edit.php?post_type=' . $this->post_type->get_post_type(),
				'activate_plugins'
			)
		);

		$this->admin_menu->register_page(
			new Status_Page(
				$this,
				__('Status', 'codeflavors-vimeo-video-post-lite'),
				__('Status', 'codeflavors-vimeo-video-post-lite'),
				'vimeotheque_status',
				'edit.php?post_type=' . $this->post_type->get_post_type(),
				'manage_options'
			)
		);

		$this->admin_menu->register_page(
			new Settings_Page(
				$this,
				__( 'Settings', 'codeflavors-vimeo-video-post-lite' ),
				__( 'Settings', 'codeflavors-vimeo-video-post-lite' ),
				'cvm_settings',
				'edit.php?post_type=' . $this->post_type->get_post_type(),
				'manage_options'
			)
		);

		$this->admin_menu->register_page(
			new Setup_Page(
				$this,
				__( 'Setup', 'codeflavors-vimeo-video-post-lite' ),
				__( 'Setup', 'codeflavors-vimeo-video-post-lite' ),
				'vimeotheque_setup',
				false,
				'manage_options'
			)
		);

		$this->admin_menu->register_page(
			new List_Videos_Page(
				$this,
				__( 'Videos', 'codeflavors-vimeo-video-post-lite' ),
				__( 'Videos', 'codeflavors-vimeo-video-post-lite' ),
				'cvm_videos',
				false,
				'edit_posts'
			)
		);

		$this->admin_menu->register_page(
			new Go_Pro_Page(
				$this,
				__( 'Go PRO!', 'codeflavors-vimeo-video-post-lite' ),
				__( 'Go PRO!', 'codeflavors-vimeo-video-post-lite' ),
				'vimeotheque_go_pro',
				'edit.php?post_type=' . $this->post_type->get_post_type(),
				'edit_posts'
			)
		);
	}

	/**
	 * Add admin capabilities
	 *
	 * @throws Exception
	 */
	public function add_capabilities(){
		if( !is_admin() ){
			return;
		}

		$capabilities = $this->get_capability();
		// admin always has access
		$admin = get_role('administrator');
		foreach ( $capabilities as $cap ) {
			$admin->add_cap( $cap['capability'] );
		}

		$roles = $this->get_roles();
		foreach( $roles as $role => $name ){
			$r = get_role( $role );
			if( is_a( $r, 'WP_Role' ) ) {
				foreach ( $capabilities as $cap ) {
					if( !$r->has_cap( $cap['capability'] ) ) {
						$r->add_cap( $cap['capability'] );
					}
				}
			}
		}
	}

	/**
	 * @param bool $cap
	 *
	 * @return array|mixed
	 * @throws Exception
	 */
	public function get_capability( $cap = false ){
		$capabilities = [
			'manual_import' => [
				'capability' => 'cvm_manual_import',
				'description' => __( 'Manual import', 'codeflavors-vimeo-video-post-lite' )
			],
			'automatic_import' => [
				'capability' => 'cvm_automatic_import',
				'description' => __( 'Automatic import', 'codeflavors-vimeo-video-post-lite' )
			]
		];

		if( !$cap ){
			return $capabilities;
		}

		if( isset( $capabilities[ $cap ] ) ){
			return $capabilities[ $cap ]['capability'];
		}else{
			throw new Exception( sprintf( 'Capability "%s" could not be found.', $cap ) );
		}
	}

	/**
	 * @return array
	 */
	public function get_roles(){
		$roles = [
			'editor' => __( 'Editor', 'codeflavors-vimeo-video-post-lite' ),
			'author' => __( 'Author', 'codeflavors-vimeo-video-post-lite' ),
			'contributor' => __( 'Contributor', 'codeflavors-vimeo-video-post-lite' ),
		];

		return $roles;
	}

	/**
	 * Extra columns in list table
	 *
	 * @param array $columns
	 *
	 * @return array
	 */
	public function extra_columns( $columns ){
		$cols = [];
		foreach( $columns as $c => $t ){
			$cols[ $c ] = $t;
			if( 'title' == $c ){
				$cols[ 'video_id' ] = __( 'Video ID', 'codeflavors-vimeo-video-post-lite' );
				$cols[ 'duration' ] = __( 'Duration', 'codeflavors-vimeo-video-post-lite' );
			}
		}
		return $cols;
	}

	/**
	 * Extra columns in list table output
	 * 
	 * @param string $column_name
	 * @param int $post_id
	 */
	public function output_extra_columns( $column_name, $post_id ){
		$_post = Helper::get_video_post( $post_id );
		$meta = $_post->get_video_data();
		switch( $column_name ){
			case 'video_id':
				echo $meta['video_id'];
			break;
			case 'duration':
				echo \Vimeotheque\Helper::human_time( $meta[ 'duration' ] );
			break;
		}
	}

	/**
	 * Register plugin notices
	 */
	public function register_notices(){
		Admin_Notices::instance()->register( new Vimeo_Api_Notice() );

		$message = new Message(
			sprintf(
				'%s <br/>%s',
				sprintf(
					__( "It's great to see that you've been using %s plugin for a while now. Hopefully you're happy with it!", 'codeflavors-vimeo-video-post-lite' ),
					sprintf( '<strong>%s</strong>', 'Vimeotheque' )
				),
				__( 'Would you consider leaving a positive review? It really helps to support the plugin and helps others to discover it too!', 'codeflavors-vimeo-video-post-lite' )
			),
			'https://wordpress.org/plugins/codeflavors-vimeo-video-post-lite/'
		);

		Admin_Notices::instance()->register(
			new Review_Notice(
				'vmtq_plugin_review_callout',
				$message,
				new User('vmtq_ignore_review_nag')
			)
		);
	}

	/**
	 * Set admin notices
	 */
	public function admin_notices(){
		if( !isset( $_GET['post_type'] ) || $this->post_type->get_post_type() != $_GET['post_type'] ){
			return;
		}

		Admin_Notices::instance()->show_notices();
	}

	/**
	 * Add to Privacy policy
	 */
	public function privacy_policy(){
		if( !function_exists( 'wp_add_privacy_policy_content' ) ){
			return;
		}

		$policy_content = sprintf(
			__( 'By using the embed feature of this plugin you will be agreeing to Vimeo\'s privacy policy. More details can be found here: %s', 'cvm-video' ),
			'https://vimeo.com/privacy'
		);

		wp_add_privacy_policy_content( 'Vimeotheque PRO', $policy_content );
	}

	/**
	 * Plugin action links
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function action_links( $links ){
		$anchor = '<a href="%s" target="%s">%s</a>';

		$links[] = sprintf(
			$anchor,
			Helper_Admin::docs_link( 'how-to-create-a-new-vimeo-app/' ),
			'_blank',
			__( 'First time installation', 'codeflavors-vimeo-video-post-lite' )
		);

		if( $this->get_admin_menu() ) {
			$links[] = sprintf(
				$anchor,
				$this->get_admin_menu()->get_page( 'cvm_settings' )
				     ->get_menu_page( false ),
				'_self',
				$this->get_admin_menu()->get_page( 'cvm_settings' )
				     ->get_menu_title()
			);

			$page = $this->get_admin_menu()->get_page( 'vimeotheque_go_pro' );
			if ( $page ) {
				$links[] = sprintf(
					$anchor,
					$page->get_menu_page( false ),
					'_self',
					$page->get_menu_title()
				);
			}
		}

		return $links;
	}

	/**
	 * @return \Vimeotheque\Post\Post_Type
	 */
	public function get_post_type(){
		return $this->post_type;
	}

	/**
	 * @return Extensions
	 */
	public function get_extensions() {
		return $this->extensions;
	}

	/**
	 * @return Ajax_Actions
	 */
	public function get_ajax(){
		return $this->ajax;
	}

	/**
	 * @return Menu_Pages
	 */
	public function get_admin_menu() {
		return $this->admin_menu;
	}
}