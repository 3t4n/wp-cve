<?php
namespace Vimeotheque\Blocks;
use Vimeotheque\Helper;
use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Video
 * @package Vimeotheque\Blocks
 * @ignore
 */
class Video_Position extends Block_Abstract implements Block_Interface {
	/**
	 * Video constructor.
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		parent::__construct( $plugin );

		$handle = parent::register_script( 'vimeotheque-video-position-block', 'video_position' );

		wp_localize_script(
			$handle,
			'vmtq_default_embed_options',
			Plugin::instance()->get_embed_options()
		);

		parent::register_block_type( 'vimeotheque/video-position', [
			'editor_script' => parent::get_script_handle(),
			'render_callback' => function( $attr ){
				// check if filters or settings prevent auto embedding
				if( !Helper::is_autoembed_allowed() ){
					return;
				}
				/**
				 * Add current post to skipped videos from auto embedding
				 * @see Front_End::embed_video()
				 */
				parent::get_plugin()->get_front_end()->prevent_post_autoembed();

				// check if embedding in archives is allowed
				$options = Plugin::instance()->get_options();
				if( !is_singular() && !$options['archives'] ){
					return;
				}

				global $post;
				$video_post = Helper::get_video_post( $post );

				$options = $video_post->get_embed_options();
				if( 'replace-featured-image' == $options['video_position'] && !is_admin() ){
					return;
				}

				$block_options = isset( $attr['extra'] ) ? $attr['extra'] : [];

				if( $video_post->is_video() ) {
					return Helper::embed_video( $post, $block_options, false );
				}
			}
		] );

		register_post_meta(
			'',
			parent::get_plugin()->get_cpt()->get_post_settings()->get_meta_embed_settings(),//'__cvm_playback_settings'
			[
				'single' => true,
				'type' => 'object',
				'show_in_rest' => [
					'schema' => [
						'additionalProperties' => true
					],
				],
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				}
			]
		);

		register_post_meta(
			'',
			parent::get_plugin()->get_cpt()->get_post_settings()->get_meta_video_id(),//'__cvm_video_id',
			[
				'single' => true,
				'show_in_rest' => true,
				'type' => 'string',
				'default' => '',
				'auth_callback' => function() {
					return current_user_can( 'edit_posts' );
				}
			]
		);

		add_action( 'admin_enqueue_scripts', [ $this, 'init' ] );
		add_action( 'the_post', [ $this, 'force_video_block' ], -99999, 2 );
	}

	/**
	 *
	 */
	public function init(){
		global $post;
		$_post = Helper::get_video_post( $post );
		if( !$_post->is_video() || !Helper::is_autoembed_allowed() ){
			$this->deactivate();
			wp_deregister_script( parent::get_script_handle() );
			//wp_deregister_style( parent::get_editor_style_handle() );
			//wp_deregister_style( parent::get_style_handle() );
		}
	}

	/**
	 * @param \WP_Post $post
	 * @param \WP_Query $query
	 */
	public function force_video_block( \WP_Post $post, \WP_Query $query ){
		if( !is_admin() || !Helper::is_autoembed_allowed() ){
			return;
		}

		$_post = Helper::get_video_post( $post );
		if( $_post->is_video() && !has_block( parent::get_wp_block_type()->name, $post ) ) {
			$settings = $_post->get_embed_options();

			$block = sprintf(
				'<!-- wp:%s {"extra":%s} /-->',
				parent::get_wp_block_type()->name,
				json_encode( $this->get_block_extra_params( $post ) )
			);

			if( 'below-content' == $settings[ 'video_position' ] ){
				$post->post_content .= "\n" . $block;
			}else{
				$post->post_content = $block . "\n" . $post->post_content ;
			}
		}
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return array
	 */
	private function get_block_extra_params( \WP_Post $post ){
		/**
		 * Filter for player options.
		 * Used to get block extra parameters and put them on the block in case video position block is not present in post content.
		 *
		 * @param array $defaults Default options array.
		 */
		$defaults = apply_filters(
			'vimeotheque\player_options_default',
			[]
		);

		$result = [];

		if( !$defaults ){
			return $result;
		}

		$_post = Helper::get_video_post( $post );
		$options = $_post->get_embed_options();

		$defaults['duration'] = $_post->duration;

		foreach ( $defaults as $key => $value ){
			$result[ $key ] = isset( $options[ $key ] ) ? $options[ $key ] : $value;
		}

		return $result;
	}
}