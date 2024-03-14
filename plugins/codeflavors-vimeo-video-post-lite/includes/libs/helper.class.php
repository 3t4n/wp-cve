<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Helper
 * @package Vimeotheque
 */
class Helper{
	/**
	 * Return the access token for the Vimeo API.
	 *
	 * @return bool|string  The access token or bool false if no token is set
	 */
	public static function get_access_token(){
		$options = Plugin::instance()->get_options();
		$token = false;
		if( !empty( $options['oauth_secret'] ) ){
			$token = $options['oauth_secret'];
		}elseif( !empty( $options['oauth_token'] ) ){
			$token = $options['oauth_token'];
		}

		/**
		 * Filter on Vimeo access token.
		 * Allows setup of a different access token by code.
		 *
		 * @param string $token The Vimeo API token
		 */
		return apply_filters( 'vimeotheque\vimeo_api\access_token', $token );
	}

	/**
	 * Returns the user agent for remote requests.
	 * Will return a WordPress user agent for the remote requests made by the plugin.
	 *
	 * @return string
	 */
	public static function request_user_agent(){
		return 'WordPress/' . get_bloginfo( 'version' ) . '; ' . get_bloginfo( 'url' );
	}

	/**
	 * Get the \Vimeotheque\Video_Post object of a post.
	 * Returns the video post object \Vimeotheque\Video_Post for the current post in the loop or the post passed to the function.
	 *
	 * @param bool|int|\WP_Post $post   The WordPress post that will be processed into a Video_Post object.
	 *
	 * @return Video_Post
	 */
	public static function get_video_post( $post = false ){
		if( $post instanceof Video_Post ){
			return $post;
		}

		return new Video_Post( $post );
	}

	/**
	 * Get the video embed size for the current post.
	 * Returns an array with keys "height" and "width" for the current video post.
	 *
	 * @param bool|int|\WP_Post $post   The WordPress post that should be processed.
	 *
	 * @return array|bool   An array with structure ['width' => (int), 'height' => (int)] or false if the post was not imported by Vimeotheque.
	 */
	public static function get_post_player_size( $post ){
		$_post = self::get_video_post( $post );
		if( $_post->is_video() ){
			$options = $_post->get_embed_options();
			$height = self::calculate_player_height( $options['aspect_ratio'], $options['width'] );
			return [
				'width' => $options['width'],
				'height' => $height
			];
		}

		return false;
	}

	/**
	 * Output video parameters as data-* attributes.
	 * Returns a string of data attributes that can be used on HTML elements to output the embedding options that will
	 * be used by the embedding script to display the video.
	 *
	 * @param array $attributes     The attributes array.
	 * @param bool $echo            Output the result (true).
	 *
	 * @return string   The resulting attributes string.
	 */
	public static function data_attributes( $attributes, $echo = false ){
		$result = [];
		// these variables are not needed by js and will be skipped
		$exclude = [ 'video_position', 'aspect_override' ];
		// loop attributes
		foreach( $attributes as $key=>$value ){
			// skip values from $exclude
			if( in_array( $key, $exclude ) ){
				continue;
			}
			$result[] = sprintf( 'data-%s="%s"', $key, $value );
		}
		if( $echo ){
			echo implode(' ', $result);
		}else{
			return implode(' ', $result);
		}
	}

	/**
	 * Add video player script to page.
	 * Enqueue the Vimeotheque player script and styling into the page.
	 *
	 * @param bool $include_js              Enqueue the player JavaScript (true) or skip it (false).
	 * @param bool|string $js_dependency    Add additional JavaScript handle that depends on the player script.
	 * @param bool|string $css_dependency   Add additional styles handle that depend on the player style.
	 *
	 * @return array
	 */
	public static function enqueue_player( $include_js = true,  $js_dependency = false, $css_dependency = false  ){
		$handles = [
			'js' => false,
			'css' => 'cvm-video-player'
		];

		if( $include_js ) {
			wp_register_script(
				'vimeo-video-player-sdk',
				'https://player.vimeo.com/api/player.js',
				false,
				'2.11'
			);

			$js_dependency = $js_dependency ? ['jquery', 'vimeo-video-player-sdk', $js_dependency] : ['jquery', 'vimeo-video-player-sdk'];

			wp_enqueue_script(
				'cvm-video-player',
				VIMEOTHEQUE_URL . 'assets/back-end/js/apps/player/app.build.js',
				$js_dependency,
				'1.0'
			);
			$handles['js'] = 'cvm-video-player';
		}

		$css_dependency = $css_dependency ? [ $css_dependency ] : false;
		wp_enqueue_style(
			'cvm-video-player',
			VIMEOTHEQUE_URL . 'assets/front-end/css/video-player.css',
			$css_dependency
		);

		return $handles;
	}

	/**
	 * Calculate player height from given aspect ratio and width.
	 *
	 * @param string $aspect_ratio  The aspect ratio used for the calculations.
	 * @param int $width            The video embed width.
	 * @param bool|float $ratio     A given ratio that will override aspect ratio if set.
	 *
	 * @return float    The player height.
	 */
	public static function calculate_player_height( $aspect_ratio, $width, $ratio =  false ){
		$width = absint($width);

		$override = Plugin::instance()->get_embed_options_obj()
		                              ->get_option('aspect_override');

		if( !is_wp_error( $override ) && $override && is_numeric( $ratio ) && $ratio > 0 ){
			$height = floor( $width / $ratio );
		}else{
			switch( $aspect_ratio ){
				case '4x3':
					$height = floor( ($width * 3) / 4 );
					break;
				case '16x9':
				default:
					$height = floor( ($width * 9) / 16 );
					break;
				case '2.35x1':
					$height = floor( $width / 2.35 );
					break;
			}
		}

		return $height;
	}

	/**
	 * Calculates player width based on the player height.
	 *
	 * @param string     $aspect_ratio  The aspect ratio used for the calculations.
	 * @param int        $height        The player height.
	 * @param bool|float $ratio         A given ratio that will override aspect ratio if set.
	 *
	 * @return float    The player width.
	 */
	public static function calculate_player_width( $aspect_ratio, $height, $ratio = false ){
		$height = absint( $height );

		$override = Plugin::instance()->get_embed_options_obj()
		                  ->get_option('aspect_override');

		if( !is_wp_error( $override ) && $override && is_numeric( $ratio ) && $ratio > 0 ){
			$width = floor( $height * $ratio );
		}else{
			switch( $aspect_ratio ){
				case '4x3':
					$width = floor( ($height * 4) / 3 );
					break;
				case '16x9':
				default:
				$width = floor( ($height * 16) / 9 );
					break;
				case '2.35x1':
					$width = floor( $height * 2.35 );
					break;
			}
		}

		return $width;
	}

	/**
	 * Get the global embedding options.
	 * Will return the options set in Vimeotheque Settings page.
	 *
	 * @param array $_options   Array of options that will override the options set by the user.
	 *
	 * @return array    The array of options.
	 */
	public static function get_embed_options( array $_options = [] ){
		$embed_options	= Plugin::instance()->get_embed_options();

		if( $_options ){
			foreach( $_options as $k => $v ){
				if( isset( $_options[ $k ] ) ){
					$embed_options[ $k ] = $_options[ $k ];
				}
			}
		}

		return $embed_options;
	}

	/**
	 * Create a HH:MM:SS from a timestamp.
	 * Given a number of seconds, the function returns a readable duration formatted as HH:MM:SS
	 *
	 * @param int $seconds  Number of seconds.
	 * @return string       The formatted time.
	 */
	public static function human_time( $seconds ){

		$seconds = absint( $seconds );

		if( $seconds < 0 ){
			return;
		}

		$h = floor( $seconds / 3600 );
		$m = floor( $seconds % 3600 / 60 );
		$s = floor( $seconds %3600 % 60 );

		return ( ($h > 0 ? $h . ":" : "") . ( ($m < 10 ? "0" : "") . $m . ":" ) . ($s < 10 ? "0" : "") . $s);
	}

	/**
	 * Get a variable from POST or GET.
	 *
	 * @param string $name              The variable name.
	 * @param bool|string $type         The variable type (POST, GET).
	 * @param bool|string $sanitize     A function name that will be sued for sanitization.
	 *
	 * @return bool|mixed   The variable value.
	 */
	public static function get_var( $name, $type = false, $sanitize = false ) {
		$result = false;

		switch( $type ){
			case 'POST':
				$result = isset( $_POST[ $name ] ) ? $_POST[ $name ] : false;
				break;
			case 'GET':
				$result = isset( $_GET[ $name ] ) ? $_GET[ $name ] : false;
				break;
			default:
				if( isset( $_GET[ $name ] ) ){
					$result = $_GET[ $name ];
				}elseif ( isset( $_POST[ $name ] ) ){
					$result = $_POST[ $name ];
				}
				break;
		}

		if( $sanitize ){
			$result = call_user_func( $sanitize, $result );
		}

		return $result;
	}

	/**
	 * Embed the video attached to a video post.
	 *
	 * @param \WP_Post $post    The WordPress post that has the video attached to it.
	 * @param array $options    Any options passed manually or from block editor parameters.
	 * @param bool $echo        Output the result (true) or not (false).
	 *
	 * @return string|void  The HTML for the embed.
	 */
	public static function embed_video( $post, $options = [], $echo = true ){
		$_post = self::get_video_post( $post );
		if( !$_post->is_video() ){
			return;
		}

		$player = new Player\Player( $_post, $options );
		return $player->get_output( $echo );
	}

	/**
	 * Check if embedding is allowed.
	 * Used to check if autoembedding in post content is prevented by using the available filters.
	 *
	 * @return bool Embed is allowed (true) or is prevented (false).
	 */
	public static function is_autoembed_allowed(){

		if( !is_admin() ){
			/**
			 * Filter that can prevent video embedding into the post content.
			 * Preventing the video embedding into the post content is useful when using custom theme templates for the video posts.
			 *
			 * @param bool $allow Allow automatic embedding into the post content (true) or prevent it (false).
			 */
			$allowed =  apply_filters( 'vimeotheque\post_content_embed', true );
		}else{
			/**
			 * Filter that can prevent video embedding into the post content for the admin area.
			 * Preventing the video embedding into the post content is useful when using custom theme templates for the video posts.
			 *
			 * @param bool $allow Allow automatic embedding into the post content (true) or prevent it (false).
			 */
			$allowed =  apply_filters( 'vimeotheque\admin_post_content_embed', true );
		}

		return $allowed;
	}

	/**
	 * Checks if video embed is visible.
	 * Determines if a video attached to current global post can be displayed into the page.
	 * Will always return false for pages and attachments unless "display in archives" option is enabled.
	 *
	 * @return bool Video is visible (true) or is hidden (false);
	 */
	public static function video_is_visible(){
		$options = Plugin::instance()->get_options();
		$is_visible = $options[ 'archives' ] ? true : is_single();
		if( is_admin() || ! $is_visible || !self::get_video_post()->is_video() ){
			return false;
		}
		return true;
	}

	/**
	 * Query Vimeo for a single video.
	 * Given a video ID from Vimeo, will return the video details from the Vimeo API.
	 *
	 * @param string $video_id  The Vimeo video ID.
	 *
	 * @return array|\WP_Error  The video details.
	 */
	public static function query_video( $video_id ){
		$vimeo = new Video_Import( 'video', $video_id );
		$result = $vimeo->get_feed();
		if( !$result ){
			$error = $vimeo->get_errors();
			if( is_wp_error( $error ) ){
				return $error;
			}
		}
		return $result;
	}

	/**
	 * Debug method that sets an action to allow third party scripts to hook to it.
	 *
	 * @param string $message       The debug message.
	 * @param string $separator     The separator that should be used after the message.
	 * @param mixed $data           Additional data that will be passed on.
	 *
	 * @return void
	 */
	public static function debug_message( $message, $separator = "\n", $data = false ){
		/**
		 * Fires a debug message action.
		 *
		 * @param string $message   The message to send to debugger.
		 * @param string $separator A separator to be used.
		 * @param mixed $data       Any type of data that can be passed.
		 */
		do_action( 'vimeotheque\debug', $message, $separator, $data );
	}

	/**
	 * Retrieves default metadata value for the specified meta key and object.
	 *
	 * By default, an empty string is returned if `$single` is true, or an empty array
	 * if it's false.
	 *
	 *
	 * @param string $meta_type Type of object metadata is for. Accepts 'post', 'comment', 'term', 'user',
	 *                          or any other object type with an associated meta table.
	 * @param int    $object_id ID of the object metadata is for.
	 * @param string $meta_key  Metadata key.
	 * @param bool   $single    Optional. If true, return only the first value of the specified meta_key.
	 *                          This parameter has no effect if meta_key is not specified. Default false.
	 * @return mixed Single metadata value, or array of values.
	 */
	public static function get_metadata_default( $meta_type, $object_id, $meta_key, $single = true ){
		if( function_exists( 'get_metadata_default' ) ){
			return \get_metadata_default( $meta_type, $object_id, $meta_key, $single );
		}elseif( $single ) {
			return '';
		}else{
			return [];
		}
	}

	/**
	 * Check if a WP_Error object is a Vimeo API error.
	 * Determines if an object of WP_Error type is an error returned by a Vimeo API query.
	 *
	 * @param \WP_Error $wp_error  The error object.
	 *
	 * @return bool     True is the error was returned by the Vimeo API or false if it's a generic WP error.
	 */
	public static function is_vimeo_api_error( $wp_error ){
		if( !is_wp_error( $wp_error ) ){
			return false;
		}

		$error_data = $wp_error->get_error_data();
		/**
		 * Key 'vimeo_api_error' is set in \Vimeotheque\Vimeo_Api\Vimeo::api_error()
		 * @see \Vimeotheque\Vimeo_Api\Vimeo::api_error()
		 * @since 2.0
		 */
		return ( isset( $error_data['vimeo_api_error'] ) && $error_data['vimeo_api_error'] );
	}

	/**
	 * Create the embed code based on given parameters
	 *
	 * @param string    $video_id   The Vimeo video ID.
	 * @param array     $args       An array of arguments used when embedding.
	 * @param bool      $echo       Output the embed code (true) or not (false).
	 *
	 * @return string
	 */
	public static function embed_by_video_id( $video_id, $args = [], $echo = true ){
		$default = [
			'title' => 1,
			'byline' => 1,
			'portrait' => 1,
			'dnt' => 1
		];

		$args = wp_parse_args(
			$args,
			$default
		);

		$embed = sprintf(
			'<iframe src="%s" width="100%%" height="100%%" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>',
			sprintf(
				'https://player.vimeo.com/video/%s?%s',
				urlencode( $video_id ),
				http_build_query( $args )
			)
		);

		if( $echo ){
			echo $embed;
		}

		return $embed;
	}

	/**
	 * Plugin version.
	 *
	 * Returns the plugin version.
	 *
	 * @return string
	 */
	public static function get_plugin_version(){
		return VIMEOTHEQUE_VERSION;
	}

	/**
	 * Plugin path.
	 *
	 * Get the plugin absolute path.
	 *
	 * @return string
	 */
	public static function get_path(){
		return VIMEOTHEQUE_PATH;
	}

	/**
	 * Plugin URL.
	 *
	 * Get the plugin URL path.
	 *
	 * @return string
	 */
	public static function get_url(){
		return VIMEOTHEQUE_URL;
	}

	/**
	 * Check single video post.
	 *
	 * Check if the current page is a single video post (post type "vimeo-video").
	 *
	 * @return bool
	 */
	public static function is_video(){
		return is_singular( Plugin::instance()->get_cpt()->get_post_type() );
	}

	/**
	 * Check if AJAX
	 *
	 * Check if an AJAX or REST request has been made.
	 *
	 * @return bool
	 */
	public static function is_ajax(){
		$ajax = defined('DOING_AJAX') && DOING_AJAX;
		$rest = defined('REST_REQUEST') && REST_REQUEST;
		return $ajax || $rest;
	}
}

