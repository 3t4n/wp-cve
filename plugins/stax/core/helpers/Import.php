<?php

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Import extends Base_Model {
	/**
	 * @var null
	 */
	public static $instance = null;

	/**
	 * @var
	 */
	private $local_url_base;
	/**
	 * @var
	 */
	private $remote_url_base;
	/**
	 * @var array
	 */
	private $images_imported = [];
	/**
	 * @var array
	 */
	private $image_history = [];
	/**
	 * @var string
	 */
	private $image_history_option = 'stax_image_history';
	/**
	 * @var string
	 */
	private $error = '';

	/**
	 * @return null|Import
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * @param string $content
	 * @param boolean $import_images
	 *
	 * @return array|bool
	 */
	public function process_content( $content, $import_images = false ) {

		if ( ! $content ) {
			return false;
		}

		$decoded = json_decode( $content );
		if ( ! is_object( $decoded ) ) {
			return false;
		}

		if ( $import_images ) {
			$upload_dir = wp_upload_dir();
			if ( is_ssl() ) {
				if ( strpos( $upload_dir['baseurl'], 'https://' ) === false ) {
					$upload_dir['baseurl'] = str_ireplace( 'http', 'https', $upload_dir['baseurl'] );
				}
			}
			$this->local_url_base  = trailingslashit( $upload_dir['baseurl'] );
			$this->remote_url_base = @json_decode( $content )->url_root;
			$import_images         = $this->get_images( $content );

			$imported_images = [];

			foreach ( $import_images as $img ) {
				$img = wp_unslash( $img );
				if ( '' !== $img && '#' !== $img ) {
					$new_image = $this->import_image( $img );
					if ( ! empty( $new_image ) && isset( $new_image['url'] ) ) {
						$imported_images[] = $new_image['url'];
					}
				}
			}
			if ( ! empty( $imported_images ) ) {
				$content = str_replace( $import_images, wp_slash( $imported_images ), $content );
			}

			if ( ! empty( $this->image_history ) ) {
				update_option( $this->image_history_option, $this->image_history );
			}
		}

		$content = str_replace( wp_slash( $this->remote_url_base ), wp_slash( $this->local_url_base ), $content );

		$content = @json_decode( $content );

		if ( ! is_object( $content ) ) {
			return false;
		}

		$return_data = [];

		foreach ( $content->groups as $zoneUuid => $group ) {
			$save = [];
			$zone = $content->zones->{$zoneUuid};
			if ( ! $zone ) {
				continue;
			}

			$save['zone']  = $zone;
			$save['group'] = $group;

			foreach ( $group->containers as $containerUuid => $groupContainer ) {
				$container = $content->containers->{$containerUuid};

				if ( ! $container ) {
					continue;
				}

				$save['containers'][ $container->uuid ] = $this->clearTrash( $container );

				foreach ( $groupContainer->viewport as $viewport ) {
					foreach ( $viewport->columns as $columnUuid => $groupColumn ) {
						$column = $content->columns->{$columnUuid};

						if ( ! $column ) {
							continue;
						}

						$save['columns'][ $column->uuid ] = $this->clearTrash( $column );

						foreach ( $groupColumn->elements as $elementUuid => $groupElement ) {
							$element = $content->elements->{$elementUuid};

							if ( ! $element ) {
								continue;
							}

							$save['elements'][ $element->uuid ] = $this->clearTrash( $element );
						}
					}
				}
			}

			$return_data[ $zone->name ] = $save;
		}

		return $return_data;
	}

	public function import_data( $content ) {

		/* prepare date for import */
		$processed_data = $this->process_content( $content );
		$items = [];

		/* Actually save the data as templates */
		if ( ! empty( $processed_data ) ) {
			foreach ( $processed_data as $zone_name => $save ) {
				$id = Model_Templates::instance()->createOrUpdate( $zone_name, $save );

				$items[] = [
					'id'      => $id,
					'name'    => $zone_name,
					'changed' => false,
					'pack'    => $save,
				];
			}
		}

		return $items;

	}

	/**
	 * @param \WP_REST_Request $request
	 *
	 * @return mixed|\WP_REST_Response
	 */
	public function execute( \WP_REST_Request $request ) {
		$data = $request->get_file_params();

		if ( ! isset( $data['file'] ) || ! $data['file'] ) {
			return $this->response( self::STATUS_FAILED );
		}

		$file = $data['file'];
		if ( ! $file['tmp_name'] ) {
			return $this->response( self::STATUS_FAILED );
		}

		$content = $this->fs_get_contents( $file['tmp_name'] );

		$items = $this->import_data( $content );
		if( $items && ! empty( $items ) ) {

			foreach ( $items as $i => $item ) {
				if ( isset( $item['pack']['containers'] ) ) {
					foreach ( $item['pack']['containers'] as $uuid => $container ) {
						$items[ $i ]['pack']['containers'][ $uuid ] = $this->matchAndMergeFields( $container );
					}
				}

				if ( isset( $item['pack']['columns'] ) ) {
					foreach ( $item['pack']['columns'] as $uuid => $column ) {
						$items[ $i ]['pack']['columns'][ $uuid ] = $this->matchAndMergeFields( $column );
					}
				}

				if ( isset( $item['pack']['elements'] ) ) {
					foreach ( $item['pack']['elements'] as $uuid => $element ) {
						$items[ $i ]['pack']['elements'][ $uuid ] = $this->matchAndMergeFields( $element );
					}
				}
			}

			return $this->response( self::STATUS_OK, $items );
		}

		return $this->response( self::STATUS_FAILED );
	}


	public function get_upload_dir() {
		//define dynamic styles path
		$upload_dir = wp_upload_dir();
		if ( is_ssl() ) {
			if ( strpos( $upload_dir['baseurl'], 'https://' ) === false ) {
				$upload_dir['baseurl'] = str_ireplace( 'http', 'https', $upload_dir['baseurl'] );
			}
		}

		return $upload_dir;
	}

	/**
	 * Try to write a file using WP File system API
	 *
	 * @param string $file_path
	 * @param string $contents
	 * @param int $mode
	 *
	 * @return bool
	 */
	public function fs_put_contents( $file_path, $contents, $mode = '' ) {
		/* Frontend or customizer fallback */
		if ( ! function_exists( 'get_filesystem_method' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		if ( '' === $mode ) {
			if ( defined( 'FS_CHMOD_FILE' ) ) {
				$mode = FS_CHMOD_FILE;
			} else {
				$mode = 0644;
			}
		}

		$context                      = $this->get_upload_dir();
		$allow_relaxed_file_ownership = true;

		if ( function_exists( 'get_filesystem_method' ) && get_filesystem_method( array(), $context, $allow_relaxed_file_ownership ) === 'direct' ) {
			/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
			$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, $context, null, $allow_relaxed_file_ownership );

			/* initialize the API */
			if ( ! WP_Filesystem( $creds, $context, $allow_relaxed_file_ownership ) ) {
				/* any problems and we exit */
				return false;
			}

			global $wp_filesystem;
			/* do our file manipulations below */

			$wp_filesystem->put_contents( $file_path, $contents, $mode );

			return true;

		} else {
			return false;
		}
	}


	/**
	 * Try to get a file content using WP File system API
	 *
	 * @param string $file_path Path to get contents from.
	 *
	 * @return bool
	 */
	public function fs_get_contents( $file_path ) {

		/* Frontend or customizer fallback */
		if ( ! function_exists( 'get_filesystem_method' ) ) {
			require_once ABSPATH . 'wp-admin/includes/file.php';
		}

		$upload_dir                   = $this->get_upload_dir();
		$context                      = $upload_dir['basedir'];
		$allow_relaxed_file_ownership = true;

		if ( function_exists( 'get_filesystem_method' ) && get_filesystem_method( array(), $context, $allow_relaxed_file_ownership ) === 'direct' ) {
			/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
			$creds = request_filesystem_credentials( site_url() . '/wp-admin/', '', false, $context, null, $allow_relaxed_file_ownership );

			/* initialize the API */
			if ( ! WP_Filesystem( $creds, $context, $allow_relaxed_file_ownership ) ) {
				/* any problems and we exit */
				return false;
			}

			global $wp_filesystem;

			/* do our file manipulations below */

			return $wp_filesystem->get_contents( $file_path );

		} else {
			return false;
		}
	}

	/**
	 * @param string $link
	 * @param null $post_id
	 *
	 * @return array
	 */
	private function import_image( $link = '', $post_id = null ) {

		$imported_image = array();
		if ( '' === $link ) {
			return $imported_image;
		}
		$local_url = $this->remote_to_local_url( $link, $post_id );

		if ( null === $this->image_history ) {
			$this->image_history = get_option( $this->image_history_option, array() );
		}

		/* Look in imported images history */
		if ( ! empty( $this->image_history ) ) {
			foreach ( $this->image_history as $item ) {
				if ( $link === $item['remote'] ) {
					$local_url = $item['local'];
				}
			}
		}

		// check if image exists.
		if ( $img_id = attachment_url_to_postid( $local_url ) ) {
			$imported_image['id']             = $img_id;
			$imported_image['url']            = $local_url;
			$this->images_imported[ $img_id ] = $link;

			return $imported_image;
		}

		//if image is not found locally, continue the quest
		require_once( ABSPATH . 'wp-admin/includes/media.php' );
		require_once( ABSPATH . 'wp-admin/includes/file.php' );
		require_once( ABSPATH . 'wp-admin/includes/image.php' );


		add_filter( 'http_request_host_is_external', '__return_true' );
		$new_image = media_sideload_image( $link, $post_id, null, 'src' );
		remove_filter( 'http_request_host_is_external', '__return_true' );

		if ( ! is_wp_error( $new_image ) ) {

			$img_id                = attachment_url_to_postid( $new_image );
			$imported_image['id']  = $img_id;
			$imported_image['url'] = $new_image;

			$this->images_imported[ $img_id ]    = $link;
			$this->image_history[ md5( $link ) ] = array(
				'remote' => $link,
				'local'  => $new_image,
			);

		} else {
			$this->error = $new_image->get_error_message();
		}

		return $imported_image;
	}

	/**
	 * @param null $url
	 * @param null $post_id
	 *
	 * @return bool|mixed|string
	 */
	private function remote_to_local_url( $url = null, $post_id = null ) {
		if ( ! $url ) {
			return false;
		}
		$remote_base_no_protocol = str_replace( array( 'http://', 'https://' ), '', $this->remote_url_base );
		$url_no_protocol         = str_replace( array( 'http://', 'https://' ), '', $this->local_url_base );

		if ( false !== strpos( $url_no_protocol, $remote_base_no_protocol ) ) {
			$local_url = str_replace( 'https://' . $remote_base_no_protocol, $this->local_url_base, $url );
			$local_url = str_replace( 'http://' . $remote_base_no_protocol, $this->local_url_base, $local_url );
		} else {
			$time = current_time( 'mysql' );
			if ( $post = get_post( $post_id ) ) {
				if ( substr( $post->post_date, 0, 4 ) > 0 ) {
					$time = $post->post_date;
				}
			}
			$uploads   = wp_upload_dir( $time );
			$name      = basename( $url );
			$filename  = wp_unique_filename( $uploads['path'], $name );
			$local_url = $uploads['path'] . "/$filename";
		}

		return $local_url;
	}

	/**
	 * Return images src from html content
	 *
	 * @param $string
	 *
	 * @return array
	 */
	private function get_images( $string ) {
		//$regex = '/<img(.*?)src=([\\\"]+)(.*?)([\\\"]+)(.*?)>/si';
		$regex = '/https.*?(jpg|png|gif|jpeg)/si';
		preg_match_all( $regex, $string, $matches );

		return $matches[0];
	}

}
