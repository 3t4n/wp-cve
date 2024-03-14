<?php namespace MSMoMDP\Wp;

use MSMoMDP\Std\Core\Str;

class WpStd {

	// SECTION Public

	/**
	 * Updates post meta for a post. It also automatically deletes or adds the value to field_name if specified
	 *
	 * @access     protected
	 * @param      integer     The post ID for the post we're updating
	 * @param      string      The field we're updating/adding/deleting
	 * @param      string      [Optional] The value to update/add for field_name. If left blank, data will be deleted.
	 * @return int|false       Meta ID (or true if deleting) success, false on failure.
	 */
	public static function update_post_meta( int $post_id, string $field_name, $value = '' ) {
		$res = false;
		if ( empty( $value ) or ! $value ) {
			$res = delete_post_meta( $post_id, $field_name );
		} elseif ( ! get_post_meta( $post_id, $field_name ) ) {
			$res = add_post_meta( $post_id, $field_name, $value );
		} else {
			$res = update_post_meta( $post_id, $field_name, $value );
		}
		return $res;
	}

	public static function is_post_type( $type ) {
		global $wp_query;
		if ( $type == get_post_type( $wp_query->post->ID ) ) {
			return true;
		}
		return false;
	}

	// $exceptionTerms taxonomy-name=>taxonomy-value  Set force to False if you want to send them to Trash.
	public static function delete_meta_in_all_posts( string $postType, string $metaId, array $exceptionByTaxonomy = null ) {
		$allCountryPosts = get_posts(
			array(
				'post_type'   => $postType,
				'numberposts' => -1,
			)
		);
		foreach ( $allCountryPosts as $post ) {
			$doNotDelete = false;
			if ( $exceptionByTaxonomy ) {
				foreach ( $exceptionByTaxonomy as $taxonomyName => $taxonomyValue ) {

					if ( has_term( $taxonomyValue, $taxonomyName, $post ) ) {
						$doNotDelete = true;
						break;
					}
				}
			}
			if ( ! $doNotDelete ) {
				self::update_post_meta( $post->ID, $metaId );
			}
		}
	}

	// $exceptionTerms taxonomy-name=>taxonomy-value  Set force to False if you want to send them to Trash.
	public static function delete_all_posts( string $postType, bool $force = true, array $exceptionByTaxonomy = null ) {
		$allCountryPosts = get_posts(
			array(
				'post_type'   => $postType,
				'numberposts' => -1,
			)
		);
		foreach ( $allCountryPosts as $post ) {
			$doNotDelete = false;
			if ( $exceptionByTaxonomy ) {
				foreach ( $exceptionByTaxonomy as $taxonomyName => $taxonomyValue ) {

					if ( has_term( $taxonomyValue, $taxonomyName, $post ) ) {
						$doNotDelete = true;
						break;
					}
				}
			}
			if ( ! $doNotDelete ) {
				wp_delete_post( $post->ID, $force );
			}
		}
	}

	/*public static function set_post_featured_img(int $postId, string $uploadedImgFilePath)
	{
		if ( $postId && $uploadedImgFilePath ) {
			$wp_filetype = wp_check_filetype($uploadedImgFilePath, null );
			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title' => sanitize_file_name($uploadedImgFilePath),
				'post_content' => '',
				'post_status' => 'inherit'
			);
			$attach_id = wp_insert_attachment( $attachment, $uploadedImgFilePath, $postId );
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $uploadedImgFilePath );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			set_post_thumbnail( $postId, $attach_id );
		}
	}*/

	/**
	 * Get the current archive post type name (e.g: post, page, product).
	 *
	 * @return String|Boolean  The archive post type name or false if not in an archive page.
	 */
	public static function get_archive_name() {
		return is_archive() ? get_queried_object()->name : false;
	}

	public static function get_image_id( $image_url ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->posts WHERE guid=%s;", $image_url ) );
			return $attachment[0];
	}

	/**
	 * post_exists_by_slug.
	 *
	 * @return mixed boolean false if no post exists; post ID otherwise.
	 */
	public static function post_exists_by_slug( $post_slug, $post_type = null ) {
		if ( ! $post_type ) {
			$post_type = get_post_types();
		}
		$args  = array(
			'name'        => $post_slug,
			'post_type'   => $post_type,
			'post_status' => 'any',
			'numberposts' => 1,
		);
		$posts = get_posts( $args );

		if ( ! $posts || count( $posts ) == 0 ) {
			return false;
		} else {

			return $posts[0]->ID;
		}
	}

	/**
	 * post_exists_by_slug.
	 *
	 * @return mixed boolean false if no post exists; post ID otherwise.
	 */
	public static function get_post_by_url_path( $post_url_path, $post_type = null ) {
		$res = null;

		if ( $post_url_path ) {
			$post_url_path         = Str::separed_first_part( $post_url_path, '?' );
			$post_url_path         = Str::separed_first_part( $post_url_path, '#' );
			$post_url_path_trimmed = trim( $post_url_path, '/' );
			if ( is_front_page() || empty( $post_url_path_trimmed ) ) {
				$post_url_path = get_site_url();
			}
			if ( ! $post_type ) {
				$post_type = array_values( get_post_types( array( 'public' => true ) ) );
			}
			$res = get_page_by_path( $post_url_path, OBJECT, $post_type );
			if ( ! $res ) {
				$id = url_to_postid( $post_url_path );
				if ( $id ) {
					$res = get_post( $id );
				}
			}
		}
		return $res;
	}


	public static function get_post_by_url_( $url ) {
		// Try the core function
		$post_id = url_to_postid( $url );
		if ( $post_id == 0 ) {
			// Try custom post types
			$cpts = get_post_types(
				array(
					'public'   => true,
					'_builtin' => false,
				),
				'objects',
				'and'
			);
			// Get path from URL
			//$url_parts = explode( '/', trim( $url, '/' ) );
			//$url_parts = array_splice( $url_parts, 3 );
			$path = $url;//implode( '/', $url_parts );
			// Test against each CPT's rewrite slug
			foreach ( $cpts as $cpt_name => $cpt ) {
				$cpt_slug = trim( $cpt->rewrite['slug'], '/' );
				if ( $cpt_slug ) {
					if ( strlen( $path ) > strlen( $cpt_slug ) && substr( $path, 0, strlen( $cpt_slug ) ) == $cpt_slug ) {
						$slug  = substr( $path, strlen( $cpt_slug ) );
						$query = new \WP_Query(
							array(
								'post_type'      => $cpt_name,
								'name'           => $slug,
								'posts_per_page' => 1,
							)
						);
						if ( is_object( $query->post ) ) {
							$post_id = $query->post->ID;
						}
					}
				}
			}
		}
		return $post_id;
	}

	public static function get_post_ID_from_SERVER_REQ_URL( $post_type = null, $fallback_id = null ) {
		$currentPost = self::get_post_by_url_path( $_SERVER['REQUEST_URI'], $post_type );
		if ( $currentPost ) {
			return $currentPost->ID;
		}
		return $fallback_id;
	}



	public static function get_current_url( $trimQueryParams = false ) {
		$request_uri = $_SERVER['REQUEST_URI'];
		if ( $trimQueryParams && \array_key_exists( 'REQUEST_QUERY', $_SERVER ) && $_SERVER['REQUEST_QUERY'] ) {
			$request_uri = str_replace( $_SERVER['REQUEST_QUERY'], '', $_SERVER['REQUEST_URI'] );
		}
		return ( is_ssl() ? 'https' : 'http' ) . '://' . $_SERVER['HTTP_HOST'] . $request_uri;
	}



	// !SECTION End - Public


	// SECTION Private


	// !SECTION End - Private
}
