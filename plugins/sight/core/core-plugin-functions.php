<?php
/**
 * Plugin Functions
 *
 * Utility functions.
 *
 * @package Sight
 */

if ( ! function_exists( 'sight_doing_request' ) ) {
	/**
	 * Determines whether the current request is a WordPress REST or Ajax request.
	 */
	function sight_doing_request() {
		if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
			return true;
		}
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return true;
		}
	}
}

if ( ! function_exists( 'sight_is_context_editor' ) ) {
	/**
	 * Determines whether the current request is from WordPress Editor.
	 */
	function sight_is_context_editor() {
		if ( isset( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'] ) { // Input var ok; sanitization ok.
			return true;
		}
	}
}

if ( ! function_exists( 'sight_is_archive' ) ) {
	/**
	 * Determines whether the current request is a WordPress REST or Ajax request.
	 */
	function sight_is_archive() {
		return apply_filters( 'sight_portfolio_is_archive', false );
	}
}

if ( ! function_exists( 'sight_encode_data' ) ) {
	/**
	 * Encode data
	 *
	 * @param  mixed  $content    The content.
	 * @param  string $secret_key The key.
	 * @return string
	 */
	function sight_encode_data( $content, $secret_key = 'sight' ) {

		$content = wp_json_encode( $content );

		return base64_encode( $content );
	}
}

if ( ! function_exists( 'sight_decode_data' ) ) {
	/**
	 * Decode data
	 *
	 * @param  string $content    The content.
	 * @param  string $secret_key The key.
	 * @return string
	 */
	function sight_decode_data( $content, $secret_key = 'sight' ) {

		$content = base64_decode( $content );

		return json_decode( $content );
	}
}

if ( ! function_exists( 'sight_get_available_image_sizes' ) ) {
	/**
	 * Get the available image sizes
	 */
	function sight_get_available_image_sizes() {
		$wais = & $GLOBALS['_wp_additional_image_sizes'];

		$sizes       = array();
		$image_sizes = get_intermediate_image_sizes();

		if ( is_array( $image_sizes ) && $image_sizes ) {
			foreach ( $image_sizes as $size ) {
				if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ), true ) ) {
					$sizes[ $size ] = array(
						'width'  => get_option( "{$size}_size_w" ),
						'height' => get_option( "{$size}_size_h" ),
						'crop'   => (bool) get_option( "{$size}_crop" ),
					);
				} elseif ( isset( $wais[ $size ] ) ) {
					$sizes[ $size ] = array(
						'width'  => $wais[ $size ]['width'],
						'height' => $wais[ $size ]['height'],
						'crop'   => $wais[ $size ]['crop'],
					);
				}

				// Size registered, but has 0 width and height.
				if ( 0 === (int) $sizes[ $size ]['width'] && 0 === (int) $sizes[ $size ]['height'] ) {
					unset( $sizes[ $size ] );
				}
			}
		}

		return $sizes;
	}
}

if ( ! function_exists( 'sight_get_image_size' ) ) {
	/**
	 * Gets the data of a specific image size.
	 *
	 * @param string $size Name of the size.
	 */
	function sight_get_image_size( $size ) {
		if ( ! is_string( $size ) ) {
			return;
		}

		$sizes = sight_get_available_image_sizes();

		return isset( $sizes[ $size ] ) ? $sizes[ $size ] : false;
	}
}

if ( ! function_exists( 'sight_get_list_available_image_sizes' ) ) {
	/**
	 * Get the list available image sizes
	 */
	function sight_get_list_available_image_sizes() {

		$image_sizes = wp_cache_get( 'sight_available_image_sizes' );

		if ( empty( $image_sizes ) ) {
			$image_sizes = array();

			$intermediate_image_sizes = get_intermediate_image_sizes();

			foreach ( $intermediate_image_sizes as $size ) {
				$image_sizes[ $size ] = $size;

				$data = sight_get_image_size( $size );

				if ( isset( $data['width'] ) || isset( $data['height'] ) ) {

					$width  = '~';
					$height = '~';

					if ( isset( $data['width'] ) && $data['width'] ) {
						$width = $data['width'] . 'px';
					}
					if ( isset( $data['height'] ) && $data['height'] ) {
						$height = $data['height'] . 'px';
					}

					$image_sizes[ $size ] .= sprintf( ' [%s, %s]', $width, $height );
				}
			}

			wp_cache_set( 'sight_available_image_sizes', $image_sizes );
		}

		return array_merge( array( 'full' => esc_html__( 'Full', 'sight' ) ), $image_sizes );
	}
}

if ( ! function_exists( 'sight_str_truncate' ) ) {
	/**
	 * Truncates string with specified length
	 *
	 * @param  string $string      Text string.
	 * @param  int    $length      Letters length.
	 * @param  string $etc         End truncate.
	 * @param  bool   $break_words Break words or not.
	 * @return string
	 */
	function sight_str_truncate( $string, $length = 80, $etc = '&hellip;', $break_words = false ) {
		if ( 0 === $length ) {
			return '';
		}

		if ( function_exists( 'mb_strlen' ) ) {

			// MultiBite string functions.
			if ( mb_strlen( $string ) > $length ) {
				$length -= min( $length, mb_strlen( $etc ) );
				if ( ! $break_words ) {
					$string = preg_replace( '/\s+?(\S+)?$/', '', mb_substr( $string, 0, $length + 1 ) );
				}

				return mb_substr( $string, 0, $length ) . $etc;
			}
		} else {

			// Default string functions.
			if ( strlen( $string ) > $length ) {
				$length -= min( $length, strlen( $etc ) );
				if ( ! $break_words ) {
					$string = preg_replace( '/\s+?(\S+)?$/', '', substr( $string, 0, $length + 1 ) );
				}

				return substr( $string, 0, $length ) . $etc;
			}
		}

		return $string;
	}
}

if ( ! function_exists( 'sight_get_the_excerpt' ) ) {
	/**
	 * Get excerpt of post.
	 *
	 * @param int    $id          ID.
	 * @param int    $length      Letters length.
	 * @param string $etc         End truncate.
	 * @param bool   $break_words Break words or not.
	 */
	function sight_get_the_excerpt( $id = null, $length = 80, $etc = '&hellip;', $break_words = false ) {
		global $wpdb;

		if ( ! $id ) {
			$id = get_the_ID();
		}

		$excerpt = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT post_excerpt FROM {$wpdb->posts} WHERE ID = %d",
				$id
			)
		);

		if ( ! $excerpt ) {
			// If excerpt is empty, fallback to post content.
			$content = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT post_content FROM {$wpdb->posts} WHERE ID = %d",
					$id
				)
			);

			$content = strip_shortcodes( $content );
			$content = str_replace( ']]>', ']]&gt;', $content );
			$excerpt = wp_trim_words( $content, $length, $etc );
		}

		return sight_str_truncate( $excerpt, $length, $etc, $break_words );
	}
}

if ( ! function_exists( 'sight_powerkit_module_enabled' ) ) {
	/**
	 * Helper function to check the status of powerkit modules
	 *
	 * @param array $name Name of module.
	 */
	function sight_powerkit_module_enabled( $name ) {
		if ( function_exists( 'powerkit_module_enabled' ) && powerkit_module_enabled( $name ) ) {
			return true;
		}
	}
}

if ( ! function_exists( 'sight_post_views_enabled' ) ) {
	/**
	 * Check post views module.
	 *
	 * @return string Type.
	 */
	function sight_post_views_enabled() {

		// Post Views Counter.
		if ( class_exists( 'Post_Views_Counter' ) ) {
			return 'post_views';
		}

		// Powerkit Post Views.
		if ( sight_powerkit_module_enabled( 'post_views' ) ) {
			return 'pk_post_views';
		}
	}
}

if ( ! function_exists( 'sight_get_post_types_stack' ) ) {
	/**
	 * Get portfolio post types.
	 */
	function sight_get_post_types_stack() {

		$stack = wp_cache_get( 'sight_get_post_types_stack' );

		if ( ! $stack ) {

			$stack = array();

			$post_types = get_post_types( array( 'publicly_queryable' => 1 ), 'objects' );

			foreach ( $post_types as $post_type ) {
				$stack[ $post_type->name ] = $post_type->label;
			}

			wp_cache_set( 'sight_get_post_types_stack', $stack );
		}

		return $stack ? $stack : array();
	}
}

if ( ! function_exists( 'sight_get_categories_stack' ) ) {
	/**
	 * Get portfolio categories.
	 */
	function sight_get_categories_stack() {

		$stack = wp_cache_get( 'sight_get_categories_stack' );

		if ( ! $stack ) {

			$stack = array();

			$categories = get_terms(
				array(
					'taxonomy'   => 'sight-categories',
					'hide_empty' => false,
				)
			);

			foreach ( $categories as $category ) {
				$stack[ $category->term_id ] = $category->name;
			}

			wp_cache_set( 'sight_get_categories_stack', $stack );
		}

		return $stack ? $stack : array();
	}
}

if ( ! function_exists( 'sight_portfolio_area_classes' ) ) {
	/**
	 * Get portfolio area classes.
	 *
	 * @param array $attributes The attributes.
	 * @param array $options    The options.
	 */
	function sight_portfolio_area_classes( $attributes, $options ) {
		$classes = array( 'sight-portfolio-area' );

		// Enable Lightbox.
		if ( isset( $attributes['attachment_lightbox'] ) && $attributes['attachment_lightbox'] ) {
			$classes[] = 'sight-portfolio-area-lightbox';
		}

		// Apply filters.
		$classes = apply_filters( 'sight_portfolio_area_classes', $classes, $attributes, $options );

		// Build class.
		$class = implode( ' ', $classes );

		// Return.
		return $class;
	}
}

if ( ! function_exists( 'sight_portfolio_area_main_attrs' ) ) {
	/**
	 * Output portfolio area main attrs.
	 *
	 * @param array $attributes The attributes.
	 * @param array $options    The options.
	 */
	function sight_portfolio_area_main_attrs( $attributes, $options ) {
		// Apply filters.
		$attrs = apply_filters( 'sight_portfolio_area_main_attrs', '', $attributes, $options );

		// Return.
		return call_user_func( 'printf', '%s', $attrs );
	}
}

if ( ! function_exists( 'sight_get_youtube_video_id' ) ) {
	/**
	 * Get Youtube video ID from URL
	 *
	 * @param string $url YouTube URL.
	 */
	function sight_get_youtube_video_id( $url ) {
		preg_match( '/(http(s|):|)\/\/(www\.|)yout(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $url, $results );

		if ( isset( $results[6] ) && $results[6] ) {
			return $results[6];
		}
	}
}

if ( ! function_exists( 'sight_get_local_video_url' ) ) {
	/**
	 * Get local video URL
	 *
	 * @param string $url Local URL.
	 */
	function sight_get_local_video_url( $url ) {
		if ( attachment_url_to_postid( $url ) ) {
			return $url;
		}
	}
}

if ( ! function_exists( 'sight_get_video_background' ) ) {
	/**
	 * Get element video background
	 *
	 * @param string $type     The type.
	 * @param string $location The current location.
	 * @param int    $post_id  The id of post.
	 * @param string $template Template.
	 * @param bool   $controls Display tools.
	 */
	function sight_get_video_background( $type = 'always', $location = null, $post_id = null, $template = 'default', $controls = true ) {

		if ( sight_is_context_editor() ) {
			return;
		}

		if ( is_customize_preview() ) {
			return;
		}

		if ( ! $post_id ) {
			$post_id = get_the_ID();
		}

		// Params.
		$url   = get_post_meta( $post_id, 'sight_post_video_url', true );
		$start = get_post_meta( $post_id, 'sight_post_video_bg_start_time', true );
		$end   = get_post_meta( $post_id, 'sight_post_video_bg_end_time', true );

		// Location.
		if ( $location ) {
			$support = (array) get_post_meta( $post_id, 'sight_post_video_location', true );

			if ( ! in_array( $location, $support, true ) ) {
				return;
			}
		}

		// Video info.
		$local_url  = sight_get_local_video_url( $url );
		$youtube_id = sight_get_youtube_video_id( $url );

		// Output.
		if ( $youtube_id || $local_url ) {
			// Get video mode.
			$mode = $youtube_id ? 'youtube' : 'local';

			// Controls.
			if ( true === $controls ) {
				$controls = array( 'youtube', 'volume', 'state' );

				if ( 'hover' === $type ) {
					$controls = array_diff( $controls, array( 'state' ) );
				}
				if ( 'local' === $mode ) {
					$controls = array_diff( $controls, array( 'youtube' ) );
				}
			}
			?>
			<div class="sight-portfolio-video-container" data-video-type="<?php echo esc_attr( $type ); ?>" data-video-mode="<?php echo esc_attr( $mode ); ?>" data-youtube-id="<?php echo esc_attr( $youtube_id ); ?>" data-video-start="<?php echo esc_attr( (int) $start ); ?>" data-video-end="<?php echo esc_attr( (int) $end ); ?>">
				<?php if ( $youtube_id ) { ?>
					<div class="sight-portfolio-video-inner"></div>
				<?php } else { ?>
					<video class="sight-portfolio-video-inner" loop muted>
						<source src="<?php echo esc_attr( $local_url ); ?>" type="video/webm" />
					</video>
				<?php } ?>

				<div class="sight-portfolio-video-loader"></div>
			</div>

			<?php if ( is_array( $controls ) && $controls ) { ?>
				<div class="sight-portfolio-video-controls sight-portfolio-video-controls-<?php echo esc_attr( $template ); ?>">
					<?php if ( in_array( 'youtube', $controls, true ) ) { ?>
						<a class="sight-portfolio-player-control sight-portfolio-player-link sight-portfolio-player-stop" target="_blank" href="<?php echo esc_url( $url ); ?>">
							<span class="sight-portfolio-tooltip"><span><?php esc_html_e( 'View on YouTube', 'sight' ); ?></span></span>
						</a>
					<?php } ?>

					<?php if ( in_array( 'volume', $controls, true ) ) { ?>
						<span class="sight-portfolio-player-control sight-portfolio-player-volume sight-portfolio-player-mute"></span>
					<?php } ?>

					<?php if ( in_array( 'state', $controls, true ) ) { ?>
						<span class="sight-portfolio-player-control sight-portfolio-player-state sight-portfolio-player-pause"></span>
					<?php } ?>
				</div>
			<?php } ?>
			<?php
		}
	}
}

if ( ! function_exists( 'sight_portfolio_render_style' ) ) {
	/**
	 * Callback used to render style for portfolio.
	 *
	 * @param array  $attributes The attributes.
	 * @param array  $options    The options.
	 * @param string $id         The id.
	 */
	function sight_portfolio_render_style( $attributes, $options, $id ) {
		ob_start();

		// Heading Font Size.
		if ( isset( $attributes['typography_heading'] ) && $attributes['typography_heading'] ) {
			?>
			.sight-block-portfolio-id-{id} .sight-portfolio-entry__heading {
				font-size: <?php echo esc_attr( $attributes['typography_heading'] ); ?> !important;
			}
			<?php
		}

		// Caption Font Size.
		if ( isset( $attributes['typography_caption'] ) && $attributes['typography_caption'] ) {
			?>
			.sight-block-portfolio-id-{id} .sight-portfolio-entry__caption {
				font-size: <?php echo esc_attr( $attributes['typography_caption'] ); ?> !important;
			}
			<?php
		}

		// Heading Color.
		if ( isset( $attributes['color_heading'] ) && $attributes['color_heading'] ) {
			?>
			.sight-block-portfolio-id-{id} .sight-portfolio-entry__heading,
			.sight-block-portfolio-id-{id} .sight-portfolio-entry__heading a {
				color: <?php echo esc_attr( $attributes['color_heading'] ); ?> !important;
			}
			<?php
		}

		// Heading Hover Color.
		if ( isset( $attributes['color_heading_hover'] ) && $attributes['color_heading_hover'] ) {
			?>
			.sight-block-portfolio-id-{id} .sight-portfolio-entry__heading a:hover {
				color: <?php echo esc_attr( $attributes['color_heading_hover'] ); ?> !important;
			}
			<?php
		}

		// Caption Color.
		if ( isset( $attributes['color_caption'] ) && $attributes['color_caption'] ) {
			?>
			.sight-block-portfolio-id-{id} .sight-portfolio-entry__caption {
				color: <?php echo esc_attr( $attributes['color_caption'] ); ?> !important;
			}
			<?php
		}

		$style = ob_get_clean();

		/*
			* -------------------------------------
			*/

		// Apply filters.
		$style = apply_filters( 'sight_portfolio_render_css', $style, $attributes, $options, $id );

		// Replace ids.
		$style = str_replace( '{id}', $id, $style );

		// Wrap tags.
		if ( $style ) {
			$style = sprintf( '<style>%s</style>', $style );
		}

		// Print.
		call_user_func( 'printf', '%s', $style );
	}
}
