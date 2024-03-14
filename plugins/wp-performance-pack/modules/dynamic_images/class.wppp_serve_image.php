<?php
/**
 * Base class to serve intermediate images on demand.
 *
 * @author Björn Ahrens
 * @package WP Performance Pack
 * @since 2.0
 */

class WPPP_Serve_Image {
	protected $filename = null;
	protected $filename_scaled = null;
	protected $localfilename = null;
	protected $localfilename_scaled = '';
	protected $localfiletime = 0;
	protected $width = 0;
	protected $height = 0;
	protected $wppp = null;

	function __construct() {
		define( 'WPPP_SERVING_IMAGE', true ); // this is to prevent unnecessary actions and filters being registered by wppp
	}

	/*
	 * Check request header for modified request
	 */
	function check_cache_headers() {
		// Getting headers sent by the client.
		$headers = apache_request_headers(); 
		// Checking if the client is validating his cache and if it is current.
		if ( isset( $headers[ 'If-Modified-Since' ] ) && ( strtotime( $headers[ 'If-Modified-Since' ] ) == $this->localfiletime ) ) {
			// Client's cache IS current, so we just respond '304 Not Modified'.
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', filemtime( $this->localfilename ) ) . ' GMT', true, 304 );
			exit();
		}
	}

	function check_wp_cache() {
		if ( $this->wppp->options['dynamic_images_cache'] && ( false !== ( $data = wp_cache_get( $this->localfilename . $this->width . 'x' . $this->height, 'wppp' ) ) ) ) {
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $this->localfiletime ) . ' GMT', true, 200 );
			header( 'Content-Length: ' . strlen( $data[ 'data' ] ) );
			header( 'Content-Type: ' . $data[ 'mimetype' ], true, 200 );
			echo $data[ 'data' ];
			exit;
		}
	}

	/*
	 * Exit with 404 and prevent browser caching (which shouldn't happen anyway) 
	 * so image loading is retried in case of an error
	 */
	function exit404( $message ) {
		global $wp_query;
		if ( isset( $wp_query ) ) {
			$wp_query->set_404();
			status_header( 404 );
		} else { 
			header( $_SERVER[ 'SERVER_PROTOCOL' ] . ' 404 Not Found' );
			header( 'Cache-Control: no-cache, must-revalidate' );	// HTTP/1.1
			header( 'Expires: Sat, 26 Jul 1997 05:00:00 GMT' );		// past date
			echo $message;
			exit();
		}
	}

	function filter_wp_image_editor ( $editors ) {
		if ( $this->wppp->options['dynamic_images_exif_thumbs']  ) {
			include( sprintf( "%s/class.wp-image-editor-gd-exif.php", dirname( __FILE__ ) ) );
			include( sprintf( "%s/class.wp-image-editor-imagick-exif.php", dirname( __FILE__ ) ) );
			array_unshift( $editors, 'WP_Image_Editor_Imagick_EXIF', 'WP_Image_Editor_GD_EXIF' );
		}
		return $editors;
	}

	function get_local_filename() {
		if ( $this->localfilename === null ) {
			$uploads_dir = wp_upload_dir();
			$upload_path = parse_url( $uploads_dir[ 'baseurl' ] )[ 'path' ];

			$pos = strpos( $this->filename, $upload_path );
			if ( $pos !== false ) {
				$this->localfilename = $uploads_dir['basedir'] . substr( $this->filename, $pos + strlen( $upload_path ) );
				$this->localfilename_scaled	= $uploads_dir['basedir'] . substr( $this->filename_scaled, $pos + strlen( $upload_path ) );
			} else {
				$this->exit404( 'Error getting local file name for "' . $this->filename . '"' );
				return false;
			}
			if ( !file_exists( $this->localfilename ) ) {
				$this->exit404( 'File "' . $this->localfilename . '" not found' );
				return false;
			}
			$this->localfiletime	= filemtime( $this->localfilename );
		}
		return true;
	}

	/** 
	 * Get image mime type - WP_Image_Editor has functions for this, but they are all protected :(
	 * so use the code from get_mime_type directly
	 */
	function get_mimetype() {
		$extension = strtolower( pathinfo( $this->filename, PATHINFO_EXTENSION ) );
		if ( ! $extension ) {
			return false;
		}

		$mime_types = wp_get_mime_types();
		$extensions = array_keys( $mime_types );

		foreach ( $extensions as $_extension ) {
			if ( preg_match( "/{$extension}/i", $_extension ) ) {
				return $mime_types[ $_extension ];
			}
		}

		return false;
	}

	function init( $request ) {
		if ( !preg_match( '/(.*)-([0-9]+)x([0-9]+)?\.(jpeg|jpg|png|gif)/i', $request, $matches ) ) {
			$this->exit404( 'No file match' );
			return false;
		}
		$this->filename = urldecode( $matches[ 1 ] . '.' . $matches[ 4 ] );
		$this->filename_scaled = urldecode( $matches[ 1 ] . '-scaled.' . $matches[ 4 ] ); //TODO: find a better way to get scaled name as this wont adapt well to changes in name for scaled images
		$this->width = $matches[ 2 ];
		$this->height = $matches[ 3 ];
		return true;
	}

	function load_wppp() {
		global $wp_performance_pack;
		$this->wppp = $wp_performance_pack;
	}

	/*
	 * Get attachment ID for image url
	 * Source: http://philipnewcomer.net/2012/11/get-the-attachment-id-from-an-image-url-in-wordpress/
	 */
	function get_attachment_id_from_file( $attachment_filename = '', $scaled_filename = '' ) {
		global $wpdb;
		$attachment_id = false;
		if ( '' !== $attachment_filename ) {
			$upload_dir_paths = wp_upload_dir();
			$basepath = $upload_dir_paths[ 'basedir' ];
			if ( false !== strpos( $attachment_filename, $basepath ) ) {
				$attachment_filename = str_replace( $basepath . '/', '', $attachment_filename );
				$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_filename ) );
				if ( ( $attachment_id === null ) && ( $scaled_filename !== '' ) ) {
					// If the image was scaled during upload by WordPress it is saved as "[image_name]-scaled.[ext]" in postmeta
					// so if the first search didn't result in a valid attachment id try to search for the scaled images name
					$scaled_filename = str_replace( $basepath . '/', '', $scaled_filename );
					$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $scaled_filename ) );
				}
			}
		}
		return $attachment_id;
	}


	/*
	 * Called before actual resizing - used to load EWWW when using SHORTINIT
	 */
	function prepare_resize() {
	}

	function serve_image( $request ) {
		if ( !$this->init( $request ) )
			return false;

		add_filter( 'wp_image_editors', array ( $this, 'filter_wp_image_editor' ), 1000, 1 ); // set to very low priority, so it is hopefully called last as this overrides previously registered editors

		$this->load_wppp();

		if ( !$this->get_local_filename() )
			return false;

		if ( $this->wppp->options[ 'dynamic_images_nosave' ] && function_exists( 'apache_request_headers' ) ) {
			// Only check request headers if nosave option is enabled. Else server_image shouldn't be called twice for the same image.
			// Also test for apache_request_headers which might not be available (see https://www.php.net/manual/en/function.apache-request-headers.php)
			$this->check_cache_headers();
		}
		$this->check_wp_cache();

		if ( !function_exists( '__' ) ) {
			function __( $text, $domain = 'default' ) {
				return	$text;
			}
		}

		try {
			// get defined image sizes - no way to get them all here, because
			// this would require to initialize the template and all plugins
			// that's why they are stored as an option
			$image = wp_get_image_editor( $this->localfilename );
			if ( is_wp_error( $image ) ) {
				$this->exit404( 'Error loading image' );
				return fasle;
			}
			$imgsize = $image->get_size();

			// test, if the requested image size matches any of the saved sizes. WPPP only serves "known" image sizes to prevent filling up server space
			$sizes = get_option( 'wppp_dynimg_sizes' );
			$the_size = '';
			foreach ( $sizes as $size => $size_data ) {
				// always check, even if size is in meta data, as the size could have changed since it was saved to meta data
				$new_size = image_resize_dimensions( $imgsize[ 'width' ], $imgsize[ 'height' ], $size_data[ 'width' ], $size_data[ 'height' ], $size_data[ 'crop' ] );
				if ( ( abs( $new_size[ 4 ] - $this->width ) <= 1 ) && ( abs( $new_size[ 5 ] - $this->height ) <= 1 ) ) {
					// allow size to vary by one pixel to catch rounding differences in size calculation
					$the_size = $size;
					$crop = $size_data[ 'crop' ];
					break;
				}
			}

			if ( $the_size === '' ) {
				$this->exit404( 'Unknown image size' );
				return false;
			}
			unset( $sizes );

			// create intermediate file name before resizing in order to serve intermediate images from file if they are mirrored into wppp folder
			$newfile = $image->generate_filename( $this->width . 'x' . $this->height );
			if ( $this->wppp->options[ 'dynamic_images_thumbfolder' ] ) {
				$updir = wp_upload_dir()[ 'basedir' ];
				$uplen = strlen( $updir );
				if ( ( $uplen > 0 ) && ( substr( $newfile, 0, $uplen ) === $updir ) ) {
					// change save folder
					$newfile = WP_CONTENT_DIR . '/wppp/images' . substr( $newfile, $uplen, strlen( $newfile ) - $uplen );
					if ( !$this->wppp->options[ 'dynamic_images_nosave' ] )
						wp_mkdir_p( dirname( $newfile ) );
				}
				if ( !$this->wppp->options[ 'dynamic_images_nosave' ] && $this->wppp->options[ 'dynimg_serve_method' ] === 'wordpress' && file_exists( $newfile ) ) {
					header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $this->localfiletime ) . ' GMT', true, 200 );
					header( 'Content-Length: ' . filesize( $newfile ) );
					header( 'Content-Type: ' . $this->get_mimetype(), true, 200 );
					readfile( $newfile );
					return true;
				}
			}

			$data = null;
			$image->set_quality( $this->wppp->options[ 'dynimg_quality' ] );
			$image->resize( $this->width, $this->height, $crop );
			if ( !$this->wppp->options[ 'dynamic_images_nosave' ] ) {
				// first add the generated size to the images meta data so the intermediate 
				// image gets deleted, when the image is deleted

				$attachment_id = $this->get_attachment_id_from_file( $this->localfilename, $this->localfilename_scaled );
				if ( $attachment_id !== false ) {
					$attachment_meta = wp_get_attachment_metadata( $attachment_id );
					if ( $attachment_meta ) {
						// save the image to disc and update metadata
						$size = $image->save( $newfile );
						if ( !is_wp_error( $size ) ) {
							$attachment_meta[ 'sizes' ][ $the_size ] = array(
								'file'	=> wp_basename( apply_filters( 'image_make_intermediate_size', $newfile ) ),
								'width'     => $size[ 'width' ],
								'height'    => $size[ 'height' ],
								'mime-type' => $size[ 'mime-type' ],
							);
							wp_update_attachment_metadata( $attachment_id, $attachment_meta );
						}
					}
				}
			} else {
				if ( $this->wppp->options[ 'dynamic_images_cache' ] ) {
					$data = array();
					$data[ 'mimetype' ] = $this->get_mimetype();
					ob_start();
					$image->stream( $data[ 'mimetype' ] );
					unset( $image );
					$data['data'] = ob_get_contents(); // read from buffer
					ob_end_clean();
					if ( strlen( $data[ 'data' ] ) <= 256 * 1024 )
						wp_cache_set( $this->localfilename . $this->width . 'x' . $this->height, $data, 'wppp', 24 * HOUR_IN_SECONDS );
					header( 'Content-Length: ' . strlen( $data[ 'data' ] ) );
				}

				// if intermediate images are not saved, explicitly set cache headers for browser caching
				header( 'Cache-Control: max-age=' . 24 * HOUR_IN_SECONDS );
				header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 7 * 24 * HOUR_IN_SECONDS ) . ' GMT' );
			}
			header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $this->localfiletime ) . ' GMT', true, 200 );
			if ( $data === null ) {
				$image->stream( $this->get_mimetype() );
			} else
				echo $data[ 'data' ];
			unset( $image );
			return true;
		} catch ( Exception $e ) {
			unset( $image );
			throw $e;
		}
	}
}