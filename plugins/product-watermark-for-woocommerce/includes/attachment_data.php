<?php
class BeRocket_attachment_data{
	public $attachment, $fullsizepath;
	function __construct($attachment_id) {
		$this->attachment = get_post( $attachment_id );

		if ( ! $this->attachment || 'attachment' !== get_post_type( $this->attachment ) ) {
			$this->attachment = FALSE;
		}
	}
	public function get_fullsizepath() {
		if ( $this->fullsizepath ) {
			return $this->fullsizepath;
		}

		if ( function_exists( 'wp_get_original_image_path' ) ) {
			$this->fullsizepath = wp_get_original_image_path( $this->attachment->ID );
		} else {
			$this->fullsizepath = get_attached_file( $this->attachment->ID );
		}

		if ( false === $this->fullsizepath || ! file_exists( $this->fullsizepath ) ) {
			$error = new WP_Error(
				'regenerate_thumbnails_regenerator_file_not_found',
				sprintf(
					/* translators: The relative upload path to the attachment. */
					__( "The fullsize image file cannot be found in your uploads directory at <code>%s</code>. Without it, new thumbnail images can't be generated.", 'regenerate-thumbnails' ),
					_wp_relative_upload_path( $this->fullsizepath )
				),
				array(
					'status'       => 404,
					'fullsizepath' => _wp_relative_upload_path( $this->fullsizepath ),
					'attachment'   => $this->attachment,
				)
			);

			$this->fullsizepath = $error;
		}

		return $this->fullsizepath;
	}
	public function get_thumbnail( $editor, $fullsize_width, $fullsize_height, $thumbnail_width, $thumbnail_height, $crop ) {
		$dims = image_resize_dimensions( $fullsize_width, $fullsize_height, $thumbnail_width, $thumbnail_height, $crop );

		if ( ! $dims ) {
			return false;
		}

		list( , , , , $dst_w, $dst_h ) = $dims;

		$suffix   = "{$dst_w}x{$dst_h}";
		$file_ext = strtolower( pathinfo( $this->get_fullsizepath(), PATHINFO_EXTENSION ) );

		return array(
			'filename' => $editor->generate_filename( $suffix, null, $file_ext ),
			'width'    => $dst_w,
			'height'   => $dst_h,
		);
	}
	public function get_attachment_info() {
		if( $this->attachment == FALSE ) {
			return FALSE;
		}
		$fullsizepath = $this->get_fullsizepath();
		if ( is_wp_error( $fullsizepath ) ) {
			return FALSE;
		}
		$editor = wp_get_image_editor( $fullsizepath );
		if ( is_wp_error( $editor ) ) {
			return FALSE;
		}
		$metadata = wp_get_attachment_metadata( $this->attachment->ID );
		if ( false === $metadata || ! is_array( $metadata ) ) {
			return FALSE;
		}

		if ( ! isset( $metadata['sizes'] ) ) {
			$metadata['sizes'] = array();
		}
		$width  = ( isset( $metadata['width'] ) ) ? $metadata['width'] : null;
		$height = ( isset( $metadata['height'] ) ) ? $metadata['height'] : null;

		require_once( ABSPATH . '/wp-admin/includes/image.php' );

		$preview = false;
		if ( file_is_displayable_image( $fullsizepath ) ) {
			$preview = wp_get_attachment_url( $this->attachment->ID );
		} elseif (
			is_array( $metadata['sizes'] ) &&
			is_array( $metadata['sizes']['full'] ) &&
			! empty( $metadata['sizes']['full']['file'] )
		) {
			$preview = str_replace(
				wp_basename( $fullsizepath ),
				$metadata['sizes']['full']['file'],
				wp_get_attachment_url( $this->attachment->ID )
			);

			if ( ! file_exists( $preview ) ) {
				$preview = false;
			}
		}

        $folder = _wp_get_attachment_relative_path( $fullsizepath );
        if( is_multisite() ) {
            $pattern = "/sites(\\\|\/)".get_current_blog_id()."(\\\|\/)/i";
            $folder = preg_replace($pattern, '', $folder);
        }
		$response = array(
			'name'               => ( $this->attachment->post_title ) ? $this->attachment->post_title : sprintf( __( 'Attachment %d', 'regenerate-thumbnails' ), $this->attachment->ID ),
			'preview'            => $preview,
			'folder'             => $folder,
			'file_name'          => wp_basename( $fullsizepath ),
			'relative_path'      => $folder . DIRECTORY_SEPARATOR . wp_basename( $fullsizepath ),
			'edit_url'           => get_edit_post_link( $this->attachment->ID, 'raw' ),
			'width'              => $width,
			'height'             => $height,
			'registered_sizes'   => array(),
			'unregistered_sizes' => array(),
		);
        if( ! empty($metadata['original_image']) ) {
            $response['scaled'] = _wp_get_attachment_relative_path( $fullsizepath ) . DIRECTORY_SEPARATOR . wp_basename( $metadata['file'] );
        }

		$wp_upload_dir = dirname( $fullsizepath ) . DIRECTORY_SEPARATOR;

		$registered_sizes = $this->get_thumbnail_sizes();

		if ( 'application/pdf' === get_post_mime_type( $this->attachment ) ) {
			return FALSE;
		}
		foreach ( $registered_sizes as $size ) {
			if ( $width && $height ) {
				$thumbnail = $this->get_thumbnail( $editor, $width, $height, $size['width'], $size['height'], $size['crop'] );

				if ( $thumbnail ) {
					$size['filename']   = wp_basename( $thumbnail['filename'] );
					$size['fileexists'] = file_exists( $thumbnail['filename'] );
				} else {
					$size['filename']   = false;
					$size['fileexists'] = false;
				}
			} elseif ( ! empty( $metadata['sizes'][ $size['label'] ]['file'] ) ) {
				$size['filename']   = wp_basename( $metadata['sizes'][ $size['label'] ]['file'] );
				$size['fileexists'] = file_exists( $wp_upload_dir . $metadata['sizes'][ $size['label'] ]['file'] );
			} else {
				$size['filename']   = false;
				$size['fileexists'] = false;
			}

			$response['registered_sizes'][] = $size;
		}

		if ( ! $width && ! $height && is_array( $metadata['sizes']['full'] ) ) {
			$response['registered_sizes'][] = array(
				'label'      => 'full',
				'width'      => $metadata['sizes']['full']['width'],
				'height'     => $metadata['sizes']['full']['height'],
				'filename'   => $metadata['sizes']['full']['file'],
				'fileexists' => file_exists( $wp_upload_dir . $metadata['sizes']['full']['file'] ),
			);
		}

		// Look at the attachment metadata and see if we have any extra files from sizes that are no longer registered.
		foreach ( $metadata['sizes'] as $label => $size ) {
			if ( ! file_exists( $wp_upload_dir . $size['file'] ) ) {
				continue;
			}

			// An unregistered size could match a registered size's dimensions. Ignore these.
			foreach ( $response['registered_sizes'] as $registered_size ) {
				if ( $size['file'] === $registered_size['filename'] ) {
					continue 2;
				}
			}

			if ( ! empty( $registered_sizes[ $label ] ) ) {
				/* translators: Used for listing old sizes of currently registered thumbnails */
				$label = sprintf( __( '%s (old)', 'regenerate-thumbnails' ), $label );
			}

			$response['unregistered_sizes'][] = array(
				'label'      => $label,
				'width'      => $size['width'],
				'height'     => $size['height'],
				'filename'   => $size['file'],
				'fileexists' => true,
			);
		}

		return $response;
	}
	public function get_thumbnail_sizes() {
		global $_wp_additional_image_sizes;

		$thumbnail_sizes = array();

		foreach ( get_intermediate_image_sizes() as $size ) {
			$thumbnail_sizes[ $size ]['label'] = $size;
			if ( in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
				$thumbnail_sizes[ $size ]['width']  = (int) get_option( $size . '_size_w' );
				$thumbnail_sizes[ $size ]['height'] = (int) get_option( $size . '_size_h' );
				$thumbnail_sizes[ $size ]['crop']   = ( 'thumbnail' == $size ) ? (bool) get_option( 'thumbnail_crop' ) : false;
			} elseif ( ! empty( $_wp_additional_image_sizes ) && ! empty( $_wp_additional_image_sizes[ $size ] ) ) {
				$thumbnail_sizes[ $size ]['width']  = (int) $_wp_additional_image_sizes[ $size ]['width'];
				$thumbnail_sizes[ $size ]['height'] = (int) $_wp_additional_image_sizes[ $size ]['height'];
				$thumbnail_sizes[ $size ]['crop']   = (bool) $_wp_additional_image_sizes[ $size ]['crop'];
			}
		}

		return $thumbnail_sizes;
	}
}