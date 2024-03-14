<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class MLMediaController {
	/**
	 * About WP image sizes:
	 * the_post_thumbnail( 'thumbnail' );     // Thumbnail (150 x 150 hard cropped).
	 * the_post_thumbnail( 'medium' );        // Medium resolution (300 x 300 max height 300px).
	 * the_post_thumbnail( 'medium_large' );  // Medium Large (added in WP 4.4) resolution (768 x 0 infinite height).
	 * the_post_thumbnail( 'large' );         // Large resolution (1024 x 1024 max height 1024px).
	 * the_post_thumbnail( 'full' );          // Full resolution (original size uploaded).
	 *
	 * About ML image sizes:
	 * The following are all the images we need for current & legacy apps to function:
	 * big_thumb (legacy android, 768 or 1024 px width) (currently if we don't find this, we're falling back on thumb.).
	 * medium (current iOS+android compact mode, 300x300 px).
	 * medium_large (current iOS+android, 768 or 1024 px width ).
	 * full (legacy iOS + current gallery, full resolution).
	 *
	 * @var array
	 */
	public $img_sizes;

	/**
	 * @var int
	 */
	private $image_format;

	public function __construct() {

		$this->image_format = 1;

		$this->img_sizes = array(
			'full'      => array( 'full' ),
			'thumb'     => array( 'thumbnail' ),
			'big-thumb' => array( 'medium_large', 'large', 'full' ),
		);

		if ( ! function_exists( 'file_get_html' ) ) {
			require_once MOBILOUD_PLUGIN_DIR . 'libs/simple_html_dom.php';
		}

	}

	public function set_image_format( $format ) {

		if ( $format === 2 ) {
			$this->image_format = 2;
			$this->img_sizes    = array(
				'medium'       => array( 'medium' ),
				'medium_large' => array( 'medium_large', 'medium', 'large', 'full' ),
				'large'        => array( 'large', 'full' ),
				'full'         => array( 'full' ),
			);
		}

	}

	/**
	 * @param $post
	 * @param $final_post
	 * @param $images
	 *
	 * @return mixed
	 */
	public function add_media( $post, $final_post ) {
		try {
			$video_id = $this->get_the_first_youtube_id( $post );
		} catch ( Exception $e ) {
		}

		if ( ! is_null( $video_id ) ) {
			$final_post['videos'][] = $video_id;
		}

		$final_post = $this->add_featured_image( $post, $final_post );
		$final_post = $this->add_images( $post, $final_post );

		return $final_post;
	}


	/**
	 * @param $post
	 * @param $final_post
	 *
	 * @return mixed
	 */
	private function add_images( $post, $final_post ) {

		$images = get_children(
			array(
				'post_parent'    => $post->ID,
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'posts_per_page' => 100,
			)
		);

		foreach ( (array) $images as $image ) {
			$imageToAdd = array();

			foreach ( $this->img_sizes as $size => $wp_sizes ) {

				foreach ( $wp_sizes as $try_size ) {

					$image_data = $this->ml_get_attachment_image_src( $image->ID, $try_size );

					if ( ! empty( $image_data ) && ( $image_data !== null ) ) {

						if ( isset( $image_data['url'] ) && ( $image_data['url'] === null ) ) {
							break;
						}

						if ( $this->image_format === 2 ) {

							if ( ! empty( $image_data ) && ! empty( $image_data[0] ) ) {
								$image_data[0] = $this->url_path_encode( $image_data[0] );
							}
							$imageToAdd[ $size ] = $image_data;
							break;
						};

						if ( $size !== 'full' ) {

							// Add url.
							if ( $image_data[0] === null ) {
								$image_data[0] = '';
							}
							$imageToAdd[ $size ] = array( 'url' => $this->url_path_encode( $image_data[0] ) );

							break;
						}

						$imageToAdd[ $size ] = $this->url_path_encode( $image_data[0] );

						break;
					};
				}
			}

			if ( $this->image_format === 2 ) {
				$imageToAdd['imageId'] = $image->ID;
			}

			$final_post['images'][] = $imageToAdd;
		}

		return $final_post;

	}


	/**
	 * @param $post
	 * @param $final_post
	 *
	 * @return mixed
	 */
	private function add_featured_image( $post, $final_post ) {
		list( $main_image_url, $image_id ) = $this->get_featured_image( $post );

		if ( ! empty( $main_image_url ) ) {

			$featured_image = array();

			foreach ( $this->img_sizes as $size => $wp_sizes ) {
				foreach ( $wp_sizes as $try_size ) {

					list( $image_data, $image_id_1 ) = $this->get_featured_image( $post, $try_size );

					if ( isset( $image_data['url'] ) && empty( $image_data['url'] ) ) {
						break;
					}

					if ( ! empty( $image_data['url'] ) ) {
						$image_data['url'] = $this->url_path_encode( $image_data['url'] );
					} elseif ( is_string( $image_data ) ) {
						$image_data = $this->url_path_encode( $image_data );
					}

					if ( $this->image_format === 2 ) {
						if ( ! empty( $image_data ) && ! empty( $image_data[0] ) ) {
							$image_data[0] = $this->url_path_encode( $image_data[0] );
						}
						$featured_image[ $size ] = $image_data;
						break;
					};

					if ( ! empty( $image_data ) && $image_data !== null ) {

						$featured_image[ $size ] = $image_data;

						break;
					};

				}
			}

			$final_post['featured_image'] = $featured_image;

			if ( ! empty( $featured_image ) ) {
				$final_post['images'][0] = $featured_image;
			}
		}

		if ( strlen( get_option( 'ml_custom_featured_image' ) ) > 0 && class_exists( 'MultiPostThumbnails' ) ) {

			$custom_image_url = MultiPostThumbnails::get_post_thumbnail_url(
				get_post_type( $post->ID ),
				Mobiloud::get_option( 'ml_custom_featured_image' ),
				$post->ID,
				'medium'
			);

			if ( $custom_image_url !== false ) {
				$image_id = MultiPostThumbnails::get_post_thumbnail_id(
					get_post_type( $post->ID ),
					Mobiloud::get_option( 'ml_custom_featured_image' ),
					$post->ID
				);

				$final_post['images'][0] = array();

				foreach ( $this->img_sizes as $size => $wp_sizes ) {
					foreach ( $wp_sizes as $try_size ) {
						$image_data = MultiPostThumbnails::get_post_thumbnail_url(
							get_post_type( $post->ID ),
							Mobiloud::get_option( 'ml_custom_featured_image' ),
							$post->ID,
							$try_size
						);

						if ( ! empty( $image_data ) ) {

							if ( isset( $image_data['url'] ) && empty( $image_data['url'] ) ) {
								break;
							}

							if ( $image_data === null ) {
								break;
							}

							if ( ! empty( $image_data['url'] ) ) {
								$image_data['url'] = $this->url_path_encode( $image_data['url'] );
							} elseif ( is_string( $image_data ) ) {
								$image_data = $this->url_path_encode( $image_data );
							}
							$final_post['images'][0][ $size ] = $image_data;
							break;
						};
					}
				}
			}
		}

		// Filter to allow overriding of featured image for themes that don't use post thumbnails.
		$featured_image_override = apply_filters( 'mobiloud_main_image_custom', $post );
		if ( is_string( $featured_image_override ) ) {
			if ( $final_post['featured_image']['big-thumb']['url'] !== $featured_image_override ) {
				$final_post['featured_image']['big-thumb']['url'] = $featured_image_override;
				unset( $image_id );
			}
		}
		if ( ! empty( $image_id ) ) {
			$final_post['featured_image_resp'] = [
				'html'   => wp_get_attachment_image( $image_id, 'full' ),
				'low'    => apply_filters( 'mobiloud_image_url', wp_get_attachment_image_url( $image_id, 'thumbnail', true ) ),
				'srcset' => wp_get_attachment_image_srcset( $image_id, 'full' ),
				'sizes'  => wp_get_attachment_image_sizes( $image_id ),
			];
		}

		return $final_post;
	}


	public function ml_get_attachment_image_src( $id, $size ) {
		// for custom size.
		return wp_get_attachment_image_src( $id, $size );
	}


	/**
	 * @param $post
	 *
	 * @return null
	 */
	public function get_featured_image( $post, $size = 'medium' ) {
		// try to get the featured image.
		if ( has_post_thumbnail( $post->ID ) ) {
			$image_id = get_post_thumbnail_id( $post->ID );
			$image    = $this->ml_get_attachment_image_src( $image_id, $size );
			if ( $image !== null && count( $image ) > 0 ) {

				// to get thumbnail sizes in json output:
				// return $image;
				if ( $this->image_format === 2 ) {
					return [ $image, $image_id ];
				}

				if ( $size !== 'full' ) {
					// Add url.
					if ( $image[0] === null ) {
						$image[0] = '';
					}

					return [ array( 'url' => $image[0] ), $image_id ];
				}

				return [ $image[0], $image_id ];

			}
		}

		// if there is no featured image, check what's the first image
		// inside the html.
		$html = str_get_html( $post->post_content );
		if ( $html === null || ! $html ) {
			return [ null, null ];
		}

		$img_tags = $html->find( 'img' );
		foreach ( $img_tags as $img ) {
			if ( $img && isset( $img->src ) ) {
				$id        = $this->get_attachment_id( $img->src );
				$image_src = $this->ml_get_attachment_image_src( $id, $size );

				if ( $this->image_format === 2 ) {
					return [ $image_src, $id ];
				}

				if ( $size !== 'full' ) {
					// Add url.
					if ( $image_src === null ) {
						$image_src = '';
					}

					if ( isset( $image_src[0] ) ) {
						return [ array( 'url' => $image_src[0] ), $id ];
					}
				}

				if ( isset( $image_src[0] ) ) {
					return [ $image_src[0], $id ];
				}
			}
		}

		return [ null, null ];
	}

	/**
	 * @param $post
	 *
	 * @return null
	 */
	public function get_the_first_youtube_id( $post ) {
		$html = str_get_html( $post->post_content );

		if ( $html === null || ! $html ) {
			return null;
		}

		$video_tags = $html->find( 'iframe' );

		foreach ( $video_tags as $v ) {
			$yid = $this->youtubeID_from_link( $v->src );
			if ( $yid !== null ) {
				return $yid;
			}
		}

		return null;
	}


	/**
	 * Get an attachment ID given a URL.
	 *
	 * @param string $url
	 *
	 * @return int Attachment ID on success, 0 on failure
	 */
	public function get_attachment_id( $url ) {

		$attachment_id = 0;

		if ( function_exists( 'attachment_url_to_postid' ) ) {
			$attachment_id = attachment_url_to_postid( $url );
			if ( empty( $attachment_id ) ) {
				// attachment_url_to_postid() does not support resized images, try to remove that part of url.
				if ( preg_match( '!(-\d+x\d+)(\.[^/\.]+)$!s', wp_basename( $url ), $m ) ) {
					$url           = str_replace( $m[0], $m[2], $url );
					$attachment_id = attachment_url_to_postid( $url );
				}
			}
		}
		// if nothing found, try to use slow way.
		if ( empty( $attachment_id ) ) {
			$dir = wp_upload_dir();
			if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?

				$file = wp_basename( $url );

				$query_args = array(
					'post_type'     => 'attachment',
					'post_status'   => 'inherit',
					'fields'        => 'ids',
					'meta_query'    => array( // WPCS: slow query ok.
						array(
							'value'   => $file,
							'compare' => 'LIKE',
							'key'     => '_wp_attachment_metadata',
						),
					),
					'no_found_rows' => true,
				);

				$query = new WP_Query( $query_args );

				if ( $query->have_posts() ) {

					foreach ( $query->posts as $post_id ) {

						$meta = wp_get_attachment_metadata( $post_id );

						$original_file = basename( $meta['file'] );

						if ( isset( $meta['sizes'] ) ) {
							$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
						}

						if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
							$attachment_id = $post_id;
							break;
						}
					}
				}
			}
		}

		return $attachment_id;
	}


	/**
	 * @param $link
	 *
	 * @return null
	 */
	public function youtubeID_from_link( $link ) {
		$matches = array();
		if ( preg_match(
			'~
		# Match non-linked youtube URL in the wild. (Rev:20111012)
		https?://         # Required scheme. Either http or https.
		(?:[0-9A-Z-]+\.)? # Optional subdomain.
		(?:               # Group host alternatives.
		youtu\.be/      # Either youtu.be,
		| youtube\.com    # or youtube.com followed by
		\S*             # Allow anything up to VIDEO_ID,
		[^\w\-\s]       # but char before ID is non-ID char.
		)                 # End host alternatives.
		([\w\-]{11})      # $1: VIDEO_ID is exactly 11 chars.
		(?=[^\w\-]|$)     # Assert next char is non-ID or EOS.
		(?!               # Assert URL is not pre-linked.
		[?=&+%\w]*      # Allow URL (query) remainder.
		(?:             # Group pre-linked alternatives.
		[\'"][^<>]*>  # Either inside a start tag,
		| </a>          # or inside <a> element text contents.
		)               # End recognized pre-linked alts.
		)                 # End negative lookahead assertion.
		[?=&+%\w-]*        # Consume any URL (query) remainder.
		~ix',
			$link,
			$matches
		) ) {

			if ( count( $matches ) >= 2 ) {
				return $matches[1];
			}
		} else {
			return null;
		}
	}

	private function url_path_encode( $url ) {
		$url  = apply_filters( 'mobiloud_image_url', $url );
		$path = wp_parse_url( $url, PHP_URL_PATH );
		if ( strpos( $path, '%' ) !== false ) {
			return $url; // avoid double encoding.
		} else {
			$encoded_path = array_map( 'rawurlencode', explode( '/', $path ) );
			return str_replace( $path, implode( '/', $encoded_path ), $url );
		}
	}
}
