<?php

namespace Vimeotheque\Admin\Page;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Admin\Admin;
use Vimeotheque\Admin\Editor\Classic_Editor;
use Vimeotheque\Helper;
use Vimeotheque\Plugin;
use Vimeotheque\Post\Post_Type;
use Vimeotheque\Video_Post;

/**
 * Class Post_Edit_Page
 * @package Vimeotheque\Admin
 * @ignore
 */
class Post_Edit_Page{
	/**
	 * @var Video_Post
	 */
	private $video;
	/**
	 * @var Post_Type
	 */
	private $cpt;

	/**
	 * Post_Edit_Page constructor.
	 *
	 * @param Admin $admin
	 */
	public function __construct( Admin $admin ){
		$this->cpt = $admin->get_post_type();

		add_action( 'admin_enqueue_scripts', [
			$this,
			'current_screen'
		], -999999999 );

		// action on loading post-new page for custom post type. Manages single video import
		//*
        add_action( 'load-post-new.php', [
			$this,
			'post_new_onload'
		] );
		//*/

		// Gutenberg action
		add_action( 'enqueue_block_editor_assets', [
            $this,
			'block_editor_assets'
        ] );

		// save data from meta boxes
		add_action( 'save_post', [
			$this,
			'save_post'
		], 10, 2 );
	}

	/**
	 * Save post data from meta boxes.
	 * Hooked to save_post
	 *
	 * @param int $post_id
	 * @param WP_Post $post
	 */
	public function save_post( $post_id, $post ){
		if( ! isset( $_POST[ 'cvm-video-nonce' ] ) ){
			return;
		}

		$_post = Helper::get_video_post( $post );
		// check if post is the correct type
		if( !$_post->is_video() ){
			return;
		}
		// check if user can edit
		if( ! current_user_can( 'edit_post', $post_id ) ){
			return;
		}
		// check nonce
		check_admin_referer( 'cvm-save-video-settings', 'cvm-video-nonce' );
		// hack color coming from Classic editor to remove #
        $_POST['color'] = str_replace( '#', '', $_POST['color'] );
		// update post options
        $_post->set_embed_options( $_POST );
	}

	/**
	 * Add functionality for the classic editor
	 */
	public function current_screen(){
        $screen = get_current_screen();
        if( $screen ) {
	        new Classic_Editor( get_current_screen() );
        }
	}

	/**
	 * New post load action for videos.
	 * Will first display a form to query for the video.
	 */
	public function post_new_onload(){
		if( ! isset( $_REQUEST[ 'post_type' ] ) || $this->cpt->get_post_type() !== $_REQUEST[ 'post_type' ] ){
			return;
		}

		// blocks are not needed here
        Plugin::instance()->get_blocks()->unregister_blocks();

		global $post;
		$post = get_default_post_to_edit( $this->cpt->get_post_type(), true );

		// unregister all blocks since the editor won't be loaded
		$blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
		foreach( $blocks as $block => $_block ){
			unregister_block_type( $_block );
		}

		include ABSPATH . 'wp-admin/admin-header.php';

		$options = \Vimeotheque\Plugin::instance()->get_options();
		if( empty( $options[ 'vimeo_consumer_key' ] ) || empty( $options[ 'vimeo_secret_key' ] ) ){

		    printf(
                '<p>%s</p><p>%s %s</p>',
                __( 'Before being able to import videos you must register and App on Vimeo.', 'codeflavors-vimeo-video-post-lite' ),
                sprintf(
                    '<a href="%s" target="_blank" class="button">%s</a>',
                    'https://developer.vimeo.com/apps/new',
                    __( 'Create Vimeo App', 'codeflavors-vimeo-video-post-lite' )
                ),
                sprintf(
                    '<a href="%s" target="_blank">%s</a>',
                    \Vimeotheque\Admin\Helper_Admin::docs_link( 'how-to-create-a-new-vimeo-app/' ),
                    __( 'See tutorial', 'codeflavors-vimeo-video-post-lite' )
                )
            );

		}else{
		    $handle = 'vimeotheque-import-video-react-app';
            wp_enqueue_script(
                $handle,
                VIMEOTHEQUE_URL . 'assets/back-end/js/apps/add_video/app.build.js',
                ['wp-element', 'wp-editor'],
                '1.0'
            );

			/**
			 * Allow enqueue of additional scripts
             * @ignore
             * @param string $handle React app script handle
			 */
            do_action( 'vimeotheque\admin\single-video-import-enqueue-script', $handle );

			wp_localize_script(
			    'vimeotheque-import-video-react-app',
                'wpApiSettings',
                [
				    'root' => esc_url_raw( rest_url() ),
				    'nonce' => wp_create_nonce( 'wp_rest' ),
				    // leave the post ID in to avoid errors caused by script caching in browser
                    // @todo remove the ID in version 2.0.9+
                    'postId' => $post->ID
			    ]
            );

			wp_localize_script(
			    'vimeotheque-import-video-react-app',
                'vmtqVideoSettings',
                [
	                'postId' => $post->ID
                ]
            );

			wp_enqueue_style(
				'vimeotheque-import-video-react-app',
                VIMEOTHEQUE_URL . 'assets/back-end/js/apps/add_video/style.css',
                ['wp-editor']
            );

			wp_enqueue_style( 'wp-editor' );

?>
<div class="wrap">
    <h1><?php _e( 'Import video', 'codeflavors-vimeo-video-post-lite' );?></h1>
    <div id="poststuff">
        <div class="notice error hide-if-js">
            <p><?php _e( 'JavaScript is disabled! You must enable JavaScript in order to be able to import videos.', 'codeflavors-vimeo-video-post-lite' );?></p>
        </div>
        <div id="vimeotheque-import-video"><!-- React App root --></div>
    </div>

</div>
<?php
		}

		include ABSPATH . 'wp-admin/admin-footer.php';
		die();
    }

	/**
	 * Callback on Gutenberg's script enqueue action
     * Enqueues all necessary files for Gutenberg compatibility
	 */
	public function block_editor_assets(){
	    global $post;
	    // do not enqueue script if post isn't a video post imported by the plugin
        if( is_a( $post, 'WP_Post' ) ){
            if( !Helper::get_video_post()->is_video() ){
                return;
            }
        }

		wp_enqueue_script(
		    'cvm-gutenberg',
			VIMEOTHEQUE_URL . 'assets/back-end/js/gutenberg/video-thumbnail.js',
            [ 'jquery' ],
            '1.0'
        );
    }
}