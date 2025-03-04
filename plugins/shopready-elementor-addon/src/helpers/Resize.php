<?php

namespace Shop_Ready\helpers;

class Resize {
		/**
		 * The singleton instance
		 */
		static private $instance = null;

		/**
		 * No initialization allowed
		 */
		private function __construct() {
		}

		/**
		 * No cloning allowed
		 */
		private function __clone() {
		}

		static public function getInstance() {
			if ( self::$instance == null ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		private function get_attachment_info( $attachment ) {

            try{
				$row  = $this->get_attachment( $attachment );
				if(!isset($row['ID'])){
                    throw new \Exception('Invalid Image');
				}
				$path = get_attached_file( $row['ID'] );
	
				return ( ! isset( $row ) || ! $path ) ? false : array(
					'id'   => intval( $row['ID'] ),
					'path' => $path,
					'url'  => is_ssl() ? preg_replace( "/^http:/i", "https:", $row['guid'] ) : $row['guid']
				);
			}catch (\Exception $e) {
               return false;
			}
			
		}

		private function get_attachment( $attachment ) {
			/**
			 * @var WPDB $wpdb
			 */
			global $wpdb;

			if ( is_numeric( $attachment ) ) {
				return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE ID=%d LIMIT 1", $attachment ), ARRAY_A );
			} else {

				$attachment = str_replace( array( 'http:', 'https:' ), '', $attachment );

				return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE guid LIKE %s LIMIT 1", '%' . $wpdb->esc_like($attachment) ), ARRAY_A );
			}
		}

		public function process( $attachment, $width = false, $height = false, $crop = false ) {

			$attachment_info = $this->get_attachment_info( $attachment );

			if ( ! $attachment_info ) {
				return new \WP_Error( 'invalid_attachment', 'Invalid Attachment', $attachment );
			}

			$file_path = $attachment_info['path'];

			$info = pathinfo( $file_path );
			$dir  = $info['dirname'];
			$ext  = ( isset( $info['extension'] ) ) ? $info['extension'] : 'jpg';
			$name = wp_basename( $file_path, ".$ext" );
			$name = preg_replace( '/(.+)(\-\d+x\d+)$/', '$1', $name );

			{
				if ( ! $width || ! $height ) {
					$editor = wp_get_image_editor( $file_path );

					if (is_wp_error($editor)) {
						return $editor;
					}

					$size         = $editor->get_size();
					$orig_width   = $size['width'];
					$orig_height  = $size['height'];
					if ( ! $height && $width ) {
						$height = round( ( $orig_height * $width ) / $orig_width );
					} elseif ( ! $width && $height ) {
						$width = round( ( $orig_width * $height ) / $orig_height );
					} else {
						return $attachment;
					}
				}
			}

			// Suffix applied to filename
			$suffix = "{$width}x{$height}";

			// Get the destination file name
			$destination_file_name = "{$dir}/{$name}-{$suffix}.{$ext}";
			// No need to resize & create a new image if it already exists
			if ( ! file_exists( $destination_file_name ) ) {
				//Image Resize
				$editor = (isset($editor)) ? $editor : wp_get_image_editor( $file_path );

				if ( is_wp_error( $editor ) ) {
					return new \WP_Error( 'wp_image_editor', 'WP Image editor can\'t resize this attachment', $attachment );
				}

				// Get the original image size
				$size        = $editor->get_size();
				$orig_width  = $size['width'];
				$orig_height = $size['height'];

				$src_x = $src_y = 0;
				$src_w = $orig_width;
				$src_h = $orig_height;

				if ( $crop ) {

					$cmp_x = $orig_width / $width;
					$cmp_y = $orig_height / $height;

					// Calculate x or y coordinate, and width or height of source
					if ( $cmp_x > $cmp_y ) {
						$src_w = round( $orig_width / $cmp_x * $cmp_y );
						$src_x = round( ( $orig_width - ( $orig_width / $cmp_x * $cmp_y ) ) / 2 );
					} else if ( $cmp_y > $cmp_x ) {
						$src_h = round( $orig_height / $cmp_y * $cmp_x );
						$src_y = round( ( $orig_height - ( $orig_height / $cmp_y * $cmp_x ) ) / 2 );
					}

				}

				$editor->crop( $src_x, $src_y, $src_w, $src_h, $width, $height );

				$saved = $editor->save( $destination_file_name );

				$images = wp_get_attachment_metadata( $attachment_info['id'] );
				if ( ! empty( $images['resizes'] ) && is_array( $images['resizes'] ) ) {
					foreach ( $images['resizes'] as $image_size => $image_path ) {
						$images['resizes'][ $image_size ] = addslashes( $image_path );
					}
				}
				$uploads_dir = wp_upload_dir();
				$images['resizes'][ $suffix ] = $uploads_dir['subdir'] . '/' .  $saved['file'];
				wp_update_attachment_metadata( $attachment_info['id'], $images );

			}

			return array(
				'id'  => $attachment_info['id'],
				'src' => str_replace( basename( $attachment_info['url'] ), basename( $destination_file_name ), $attachment_info['url'] )
			);
		}
	}

