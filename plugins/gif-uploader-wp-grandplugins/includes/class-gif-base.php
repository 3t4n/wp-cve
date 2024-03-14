<?php
namespace GPLSCore\GPLS_PLUGIN_WGR;

class GIF_Base {

	/**
	 * Plugin Info array.
	 *
	 * @var array
	 */
	protected static $plugin_info = array();

	/**
	 * Preview GIF Transient Key.
	 *
	 * @var string
	 */
	protected static $preview_gif_transient_key;

	/**
	 * Preview GIF Filename
	 *
	 * @var string
	 */
	protected static $preview_gif_filename;

	/**
	 * Transient Expiry Duration.
	 *
	 * @var int
	 */
	protected static $transient_expiry = 60 * 60;

	/**
	 * Init Function.
	 *
	 * @param array $plugin_info Plugin Info.
	 * @return void
	 */
	public static function init( $plugin_info ) {
		self::$plugin_info               = $plugin_info;
		self::$preview_gif_transient_key = self::$plugin_info['name'] . '-gif-watermark-transient-key';
		self::$preview_gif_filename      = 'gif-preview-tmp-' . self::$plugin_info['name'];
		self::hooks();
	}

	/**
	 * Base Hooks.
	 *
	 * @return void
	 */
	public static function hooks() {
		add_action( 'delete_expired_transients', array( get_called_class(), 'remove_preview_gif_file' ), 9 );
	}

	/**
	 * Deactivation Hook.
	 *
	 * @return void
	 */
	public static function deactivated() {
		self::remove_preview_gif_file( true );
	}

	/**
	 * Remove temp preview File when [ deleting expired transients | Deactivate ].
	 *
	 * @param boolean $force_delete  Whether to force delete the preview file or check for expiration.
	 *
	 * @return void
	 */
	public static function remove_preview_gif_file( $force_delete = false ) {
		$transient_option  = '_transient_' . self::$preview_gif_transient_key;
		$transient_timeout = '_transient_timeout_' . self::$preview_gif_transient_key;
		$timeout           = get_option( $transient_timeout );
		$preview_gif_arr   = get_option( $transient_option );
		if ( $force_delete || ( false !== $timeout && $timeout < time() ) ) {
			if ( ! empty( $preview_gif_arr ) && is_array( $preview_gif_arr ) && ! empty( $preview_gif_arr['path'] ) ) {
				@unlink( $preview_gif_arr['path'] );
				delete_option( $transient_option );
				delete_option( $transient_timeout );
			}
		}
	}

	/**
	 * Get Preview GIF.
	 *
	 * @return string
	 */
	public static function get_preview_gif( $part = null ) {
		$preview_gif_arr = get_transient( self::$preview_gif_transient_key );
		if ( false === $preview_gif_arr ) {
			return new \WP_Error(
				'get_preview_gif_error',
				esc_html__( 'Preview GIF is expired, Please click on Preview GIF button again!', 'wp-gif-editor' )
			);
		}
		if ( ! empty( $part ) ) {
			return $preview_gif_arr[ $part ];
		}
		return $preview_gif_arr;
	}

	/**
	 * Save Preview GIF.
	 *
	 * @param string $result
	 * @return array
	 */
	public static function save_preview_gif( $result, $return_part = null ) {
		if ( ! WP_Filesystem() ) {
			return new \WP_Error(
				'save_preview_gif_error',
				esc_html__( 'Unable to connect to the filesystem', 'wp-gif-editor' )
			);
		}
		global $wp_filesystem;
		$uploads = wp_upload_dir();
		$result  = $wp_filesystem->put_contents( trailingslashit( $uploads['path'] ) . self::$preview_gif_filename . '.gif', $result, 0666 );
		if ( ! $result ) {
			return new \WP_Error(
				'save_preview_gif_error',
				esc_html__( 'Unable to save the preview GIF', 'wp-gif-editor' )
			);
		}
		$preview_gif_arr = array(
			'title' => self::$preview_gif_filename,
			'path'  => trailingslashit( $uploads['path'] ) . self::$preview_gif_filename . '.gif',
			'url'   => trailingslashit( $uploads['url'] ) . self::$preview_gif_filename . '.gif',
		);
		set_transient( self::$preview_gif_transient_key, $preview_gif_arr, self::$transient_expiry );

		if ( ! empty( $return_part ) ) {
			return $preview_gif_arr[ $return_part ];
		}
		return $preview_gif_arr;
	}

	/**
	 * Save GIF.
	 *
	 * @param string $filename Save File name.
	 *
	 * @return array|\WP_Error
	 */
	public static function save_gif( $filename = '' ) {
		$gif_path = self::get_preview_gif( 'path' );
		if ( is_wp_error( $gif_path ) ) {
			return $gif_path;
		}

		if ( empty( $filename ) ) {
			$filename = 'gif-' . wp_generate_uuid4();
		}

		$title     = $filename;
		$filename  = sanitize_title( $filename );
		$file_data = self::save( $gif_path, $filename, 'path' );
		if ( is_wp_error( $file_data ) ) {
			return $file_data;
		}
		$image_meta = wp_read_image_metadata( $file_data['file'] );
		if ( $image_meta && empty( $title ) && trim( $image_meta['title'] ) && ! is_numeric( sanitize_title( $image_meta['title'] ) ) ) {
			$title = $image_meta['title'];
		}

		$post_data = array(
			'post_mime_type' => 'image/gif',
			'guid'           => $file_data['url'],
			'post_parent'    => 0,
			'post_title'     => $title,
			'post_content'   => '',
			'post_excerpt'   => '',
		);

		$attachment_id = wp_insert_attachment( $post_data, $file_data['file'], 0, true );
		if ( ! is_wp_error( $attachment_id ) ) {
			if ( ! headers_sent() ) {
				header( 'X-WP-Upload-Attachment-ID: ' . $attachment_id );
			}
			$_POST[ self::$plugin_info['name'] . '-gif-creator' ] = true;
			wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $file_data['file'] ) );
		}
		self::remove_preview_gif_file( true );

		return $attachment_id;
	}

	/**
	 * Save GIF into FILe.
	 *
	 * @param string $gif GIF String | GIF PATH.
	 * @param string $filename Filename full PATH.
	 * @param string $type GIF type.
	 * @return array
	 */
	public static function save( $gif, $filename, $type = 'string' ) {
		$time      = current_time( 'mysql' );
		$uploads   = wp_upload_dir( $time );
		$filename .= '.gif';
		$filename  = wp_unique_filename( $uploads['path'], $filename );
		$new_file  = $uploads['path'] . '/' . $filename;

		if ( 'string' === $type ) {
			file_put_contents( $new_file, $gif );
		} elseif ( 'path' === $type ) {
			$copied_preview_gif = @copy( $gif, $new_file );
			if ( false === $copied_preview_gif ) {
				return new \WP_Error(
					'save_gif_error',
					esc_html__( 'Failed to move preview GIF!', 'wp-gif-editor' )
				);
			}
		}

		// Set correct file permissions.
		$stat  = stat( dirname( $new_file ) );
		$perms = $stat['mode'] & 0000666; // Same permissions as parent folder, strip off the executable bits.
		chmod( $new_file, $perms );

		$url = $uploads['url'] . '/' . $filename;

		return array(
			'name' => $filename,
			'file' => $new_file,
			'url'  => $url,
			'type' => 'image/gif',
		);
	}

	/**
	 * Display GIF Box after save.
	 *
	 * @return void
	 */
	public static function display_gif_icon_box( $media_id ) {
		$media_post = get_post( $media_id );
		$title      = get_the_title( $media_post );
		$thumb      = wp_get_attachment_image( $media_id, array( 150, 150 ), true, array( 'alt' => '' ) );
		$edit_link  = get_edit_post_link( $media_id );
		$file       = get_attached_file( $media_id );
		?>
		<div class="gif-media-icon-box card mb-3">
			<div class="row no-gutters">
				<div class="col-md-3">
					<a target="_blank" href="<?php echo esc_url( $edit_link ); ?>"><?php echo $thumb; ?></a>
				</div>
				<div class="col-md-9">
					<div class="card-body">
						<h5 class="card-title"><a target="_blank" href="<?php echo esc_url( $edit_link ); ?>"><strong><?php echo esc_html( $title ); ?></strong></a></h5>
						<p class="card-text mt-4"><?php echo esc_html( wp_basename( $file ) ); ?></p>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

}
