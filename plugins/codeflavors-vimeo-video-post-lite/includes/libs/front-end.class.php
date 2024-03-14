<?php
namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Front_End
 * @package Vimeotheque
 */
class Front_End{
	/**
	 * @var Plugin
	 */
	private $plugin;

	/**
	 * The content embed filter priority
	 *
	 * @var int
	 */
	private $embed_filter_priority = 999;

	/**
	 * Store a list of video post ID's to skip automatic embedding for.
	 * Used for video posts that have the block editor video position block
	 * or for posts that use the video position shortcode.
	 *
	 * @var array
	 */
	private $skip_autoembed = [];

	/**
	 * Front_End constructor.
	 * @ignore
	 *
	 * @param Plugin $plugin
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;

		add_action( 'init', [ $this, 'init' ], 9999 );
	}

	/**
	 * Init action callback.
	 * Will set all filters and actions needed by the plugin to do embeds and perform front-end
	 * tasks. Used for internal purposes, should not be called manually.
	 */
	public function init(){

		$this->embed_filter_priority = intval(
			/**
			 * Automatic video embedding in post content filter priority.
			 *
			 * @param int $priority The "the_content" filter priority used to automatically embed the video into the post content.
			 */
			apply_filters(
				'vimeotheque\embed_filter_priority',
				$this->embed_filter_priority
			)
		);

		// filter content to embed video
		add_filter( 'the_content', [
			$this,
			'embed_video'
		], $this->embed_filter_priority, 1 );

		// add player script
		add_action( 'wp_print_scripts', [
			$this,
			'add_player_script'
		] );

		add_action( 'post_thumbnail_html', [
			$this,
			'filter_thumbnail_html'
		], 10, 2 );

		/**
		 * Template function the_term() works by default only for post_tag taxonomy.
		 * This filter will add the plugin taxonomy for plugin custom post type
		 */
		// add this filter only in front-end
		if( ! is_admin() ){
			add_filter( 'get_the_terms', [
				$this,
				'filter_video_terms'
			], 10, 3 );
		}
	}

	/**
	 * Post content filter callback to embed the video into the post content.
	 * The method is called on the "post_content" filter and embeds the attached video above
	 * or below the post content, depending on the setting from the plugin Settings or the individual post options.
	 *
	 * @param string $content   The post content
	 * @return string   The post content
	 */
	public function embed_video( $content ){
		if( ! Helper::video_is_visible() ){
			return $content;
		}

		global $post;

		$_post = get_post( $post );

		if( !$_post ){
			return $content;
		}

		// check if post is password protected
		if( post_password_required( $_post ) ){
			return $content;
		}

		// check if filters prevent auto embedding
		if( !Helper::is_autoembed_allowed() ){
			return $content;
		}

		// if video is in skipped auto embed list (has block or the video position shortcode in content), don't embed
		if( $this->skipped_autoembed( $_post ) ){
			return $content;
		}

		$video_post = Helper::get_video_post( $_post );
		$settings = $video_post->get_embed_options();

		if( !in_array( $settings['video_position'], [ 'above-content', 'below-content' ] ) ){
			return $content;
		}

		$video_container = Helper::embed_video( $_post, [], false );

		// put the filter back for other posts; remove in method 'prevent_autoembeds'
		add_filter( 'the_content', [
			$GLOBALS[ 'wp_embed' ],
			'autoembed'
		], 8 );

		/**
		 * Fires before the video embed is placed into the post content.
		 * Action that runs when the video is set to be automatically inserted into the post content.
		 *
		 * @param Video_Post $video_post The \Vimeotheque\Video_Post object generated for the current post in loop.
		 */
		do_action(
			'vimeotheque\automatic_embed_in_content',
			$video_post
		);

		if( 'below-content' === $settings[ 'video_position' ] ){
			return $content . $video_container;
		}else{
			return $video_container . $content;
		}
	}

	/**
	 * Post featured image filter callback.
	 * Filter for the WP featured image to replace it with the video embed if option is enabled from the plugin Settings.
	 *
	 * @param $html
	 * @param $post_id
	 *
	 * @return mixed|void
	 */
	public function filter_thumbnail_html( $html, $post_id ){

		if( ! Helper::video_is_visible() ){
			return $html;
		}

		$video = Helper::get_video_post( $post_id );
		if( !$video->is_video() ){
			return $html;
		}

		$options = $video->get_embed_options();
		if( 'replace-featured-image' !== $options['video_position'] ){
			return $html;
		}

		$video_container = Helper::embed_video( $video, [], false );

		/**
		 * Filter the embed code when option to replace featured image is on.
		 * Filter is triggered when the option to embed videos in place of the featured image is activated.
		 *
		 * @param string $video_container   The HTML element that will contrin the video embed.
		 * @param Video_Post $video         The \Vimeotheque\Video_Post post object.
		 * @param string $thumbnail_html    The featured image HTML code.
		 */
		return apply_filters(
			'vimeotheque_enhanced_embed\embed_code',
			$video_container,
			$video,
			$html
		);
	}

	/**
	 * Check if post should be skipped from autoembedding.
	 *
	 * @ignore
	 *
	 * @param \WP_Post $post
	 *
	 * @return bool
	 */
	private function skipped_autoembed( \WP_Post $post ){
		return in_array( $post->ID, $this->skip_autoembed );
	}

	/**
	 * Embed player script on video pages.
	 *
	 * @ignore  Internal functionality
	 *
	 * @return void
	 */
	public function add_player_script(){
		if( ! Helper::video_is_visible() ){
			return;
		}
		Helper::enqueue_player();
	}

	/**
	 * Filter the tags for the custom post type implemented by this plugin.
	 * Useful in template pages using function the_tags() - this function works
	 * only for the default post_tag taxonomy; the filter adds functionality
	 * for plugin post type tag taxonomy
	 *
	 * @ignore  Internal functionality
	 *
	 * @param array $terms - the terms found
	 * @param int $post_id - the id of the post
	 * @param string $taxonomy - the taxonomy searched for
	 * @return
	 *
	 */
	public function filter_video_terms( $terms, $post_id, $taxonomy ){
		// get the current post
		$post = get_post( $post_id );
		if( ! $post ){
			return $terms;
		}
		// check only for the custom post type of the plugin
		if( $this->plugin->get_cpt()->get_post_type() == $post->post_type ){
			// the_tags() will check only for taxonomy post_tag. Check if this is what it's looking for and replace if true
			if( $taxonomy != $this->plugin->get_cpt()->get_tag_tax() && 'post_tag' == $taxonomy ){
				return get_the_terms( $post_id, $this->plugin->get_cpt()->get_tag_tax() );
			}
		}
		return $terms;
	}

	/**
	 * Return the filter priority for the automaticembed in post content
	 *
	 * @return int
	 */
	public function get_embed_filter_priority(){
		return $this->embed_filter_priority;
	}

	/**
	 * Remove filter set on post content to embed the video;
	 * prevents automatic video embed above or below content when called.
	 *
	 * @ignore  Internal functionality
	 *
	 * @param int|false $post_id    The post ID registered to skip the auto embedding for
	 */
	public function prevent_post_autoembed( $post_id = false ){
		if( !$post_id ){
			/**
			 * @var \WP_Post
			 */
			global $post;
			$post_id = $post->ID;
		}

		$this->skip_autoembed[ $post_id ] = $post_id;
	}
}