<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Image_Import
 * @package Vimeotheque
 */
class Image_Import {
	/**
	 * @var Video_Post
	 */
	private $video_post;

	/**
	 * Image_Import constructor.
	 *
	 * @param Video_Post $video_post
	 */
	public function __construct( Video_Post $video_post ) {
		$this->video_post = $video_post;
	}

	/**
	 * @param bool $refresh
	 *
	 * @return array|bool|void
	 */
	public function set_featured_image( $refresh = false ){
		if( !$this->video_post->video_id ){
			return;
		}

		Helper::debug_message(
			sprintf(
				'Preparing to import featured image for post ID #%d.',
				$this->video_post->get_post()->ID
			)
		);

		if( $refresh ){
			$result = $this->import_from_api();
		}else{

			$args = [
				'post_type' => 'attachment',
				'meta_key'  	=> 'video_thumbnail',
				'meta_value'	=> $this->video_post->video_id
			];

			if( !empty( $this->video_post->image_uri ) ){
				$args['meta_key'] = '__vimeo_image_uri';
				$args['meta_value']  = $this->video_post->image_uri;
			}

			// check if thumbnail was already imported
			$attachment = get_posts( $args );
			// if thumbnail exists, return it
			if( $attachment ){
				// set image as featured for current post
				set_post_thumbnail( $this->video_post->get_post()->ID, $attachment[0]->ID );

				Helper::debug_message(
					sprintf(
						'An existing attachment having ID %d was detected and was set as featured image for post ID %d.',
						$attachment[0]->ID,
						$this->video_post->get_post()->ID
					)
				);

				$result = [
					'post_id' 		=> $this->video_post->get_post()->ID,
					'attachment_id' => $attachment[0]->ID
				];
			}else{
				$image_url = end( $this->video_post->thumbnails );
				$result = $this->import_to_media( $image_url );

				if( isset( $result['attachment_id'] ) && !empty( $this->video_post->image_uri ) ){
					update_post_meta(
						$result['attachment_id'],
						'__vimeo_image_uri',
						$this->video_post->image_uri
					);
				}

			}
		}

		if( !$result ){
			Helper::debug_message(
				'Error, the featured image was not imported.'
			);
		}

		return $result;
	}

	/**
	 * @return array|bool
	 */
	private function import_from_api(){
		$q = new Video_Import( 'thumbnails', $this->video_post->video_id );
		$thumbnails = $q->get_feed();
		if( $thumbnails ){

			$exists = $this->check_duplicate( $thumbnails['uri'] );

			if( $exists ){

				Helper::debug_message(
					sprintf(
						'While importing thumbnail from Vimeo API, a duplicate image with ID #%s was found. Setting duplicate as featured image for post #%s.',
						$exists->ID,
						$this->video_post->get_post()->ID
					)
				);

				set_post_thumbnail( $this->video_post->get_post()->ID, $exists->ID);

				return [
					'post_id' => $this->video_post->get_post()->ID,
					'attachment_id' => $exists->ID
				];

			}else{
				$img    = end( $thumbnails['images'] );
				$result = $this->import_to_media( $img );

				if ( isset( $result['attachment_id'] ) ) {

					Helper::debug_message(
						sprintf(
							'Imported image ID #%s from Vimeo API and set it as featured image for post ID #%s.',
							$result['attachment_id'],
							$result['post_id']
						)
					);

					update_post_meta(
						$result['attachment_id'],
						'__vimeo_image_uri',
						$thumbnails['uri']
					);
				}

				return $result;
			}
		}
	}

	/**
	 * Check if a duplicate image exists
	 *
	 * @param $image_uri
	 *
	 * @return false|int|\WP_Post
	 */
	private function check_duplicate( $image_uri ){

		$args = [
			'post_type' => 'attachment',
			'numberposts' => 1,
			'suppress_filters' => true,
			'meta_query' => [[
				'key' => '__vimeo_image_uri',
				'value' => $image_uri,
				'compare' => 'LIKE'
			]]
		];

		$posts = get_posts( $args );

		return isset( $posts[0] ) ? $posts[0] : false;
	}

	/**
	 * @param $image_url
	 *
	 * @return array|bool
	 */
	private function import_to_media( $image_url ){
		if( !$image_url ){
			Helper::debug_message(
				sprintf(
					'Post #%d featured image not set because no image URL was detected.',
					$this->video_post->get_post()->ID
				)
			);

			return false;
		}

		// get the thumbnail
		$request = wp_remote_get(
			$image_url,
			[
				'user-agent' => Helper::request_user_agent(),
				'sslverify' => false,
				/**
				 * Request timeout filter.
				 * Video image import request timeout in seconds.
				 *
				 * @param int $timeout Remote request timeout in seconds.
				 */
				'timeout' => apply_filters( 'vimeotheque\image_request_timeout', 30 )
			]
		);

		if( is_wp_error( $request ) || 200 != wp_remote_retrieve_response_code( $request ) ) {

			$error_message = is_wp_error( $request ) ?
				sprintf( 'generated error "%s"', $request->get_error_message() ) :
				sprintf( 'returned response code "%s"', wp_remote_retrieve_response_code( $request ) );

			Helper::debug_message(
				sprintf(
					'Remote request to URL %s for featured image setup on post ID #%d %s.',
					$image_url,
					$this->video_post->get_post()->ID,
					$error_message
				)
			);

			return false;
		}

		$image_contents = $request['body'];
		$image_type = wp_remote_retrieve_header( $request, 'content-type' );
		// Translate MIME type into an extension
		if ( $image_type == 'image/jpeg' ){
			$image_extension = '.jpg';
		}elseif ( $image_type == 'image/png' ){
			$image_extension = '.png';
		}

		if( !isset( $image_extension ) ){
			Helper::debug_message(
				sprintf(
					'Could not determine extension for image "%s".',
					$image_url
				)
			);

			return false;
		}

		// Construct a file name using post slug and extension
		$fname = urldecode( basename( get_permalink( $this->video_post->get_post()->ID ) ) ) ;
		$new_filename = preg_replace( '/[^A-Za-z0-9\-]/', '', $fname ) .
		                '-vimeo-thumbnail' .
		                $image_extension;

		// Save the image bits using the new filename
		$upload = wp_upload_bits( $new_filename, null, $image_contents );
		if ( $upload['error'] ) {

			Helper::debug_message(
				sprintf(
					'The following error was encountered during the file upload in WP: "%s".',
					$upload['error']
				)
			);

			return false;
		}

		$_image_url = $upload['url'];
		$filename = $upload['file'];

		/**
		 * Action that allows modification of image that will be attached to video post.
		 *
		 * @param string $filename  Complete path to original video image within WP gallery.
		 * @param int $post_id      The post ID that the image will be attached to as featured image.
		 * @param string $video_id  The video ID from Vimeo.
		 */
		do_action(
			'vimeotheque\image_file_raw',
			$filename,
			$this->video_post->get_post()->ID,
			$this->video_post->video_id
		);

		$wp_filetype = wp_check_filetype( basename( $filename ), null );
		$attachment = [
			'post_mime_type'	=> $wp_filetype['type'],
			'post_title'		=> get_the_title( $this->video_post->get_post()->ID ).' - Vimeo thumbnail',
			'post_content'		=> '',
			'post_status'		=> 'inherit',
			'guid'				=> $_image_url
		];
		$attach_id = wp_insert_attachment( $attachment, $filename, $this->video_post->get_post()->ID );

		if( is_wp_error( $attach_id ) ){
			Helper::debug_message(
				sprintf(
					'The following error was encountered when trying to insert the new attachment into the database: "%s".',
					$attach_id->get_error_message()
				)
			);
			return;
		}

		// you must first include the image.php file
		// for the function wp_generate_attachment_metadata() to work
		require_once( ABSPATH . 'wp-admin/includes/image.php' );
		$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
		wp_update_attachment_metadata( $attach_id, $attach_data );

		// Add field to mark image as a video thumbnail
		update_post_meta(
			$attach_id,
			'video_thumbnail',
			$this->video_post->video_id
		);

		// set image as featured for current post
		update_post_meta(
			$this->video_post->get_post()->ID,
			'_thumbnail_id',
			$attach_id
		);

		/**
		 * Trigger action on plugin import.
		 *
		 * @param int $attachment_id    ID of attachment create.
		 * @param string $video_id      The video ID from Vimeo being processed.
		 * @param int $post_id          The post ID that has the attachment.
		 */
		do_action(
			'vimeotheque\image_imported',
			$attach_id,
			$this->video_post->video_id,
			$this->video_post->get_post()->ID
		);

		Helper::debug_message(
			sprintf(
				'Image imported successfully from %s into attachment #%d and set as featured image for post #%d.',
				$image_url,
				$attach_id,
				$this->video_post->get_post()->ID
			)
		);

		return [
			'post_id' 		=> $this->video_post->get_post()->ID,
			'attachment_id' => $attach_id
		];
	}

}