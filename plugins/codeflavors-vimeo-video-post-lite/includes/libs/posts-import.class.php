<?php

namespace Vimeotheque;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Vimeotheque\Post\Post_Type;
use WP_Error;

/**
 * Class Posts_Import
 * @package Vimeotheque
 */
class Posts_Import{
	/**
	 * @var Post_Type
	 */
	protected $post_type;

	/**
	 * Posts_Import constructor.
	 *
	 * @param Post_Type $post_type
	 */
	public function __construct( Post_Type $post_type ) {
		$this->post_type = $post_type;
	}

	/**
	 * Imports videos from a given feed source.
	 * Used by automatic updates.
	 *
	 * @param $raw_feed
	 * @param Feed|array $import_options
	 *
	 * @return array|void
	 */
	public function run_import( $raw_feed, $import_options ){
		/**
		 * @var array $native_tax
		 * @var array $native_tag
		 * @var bool $import_description
		 * @var string $import_status
		 * @var bool $import_title
		 * @var bool $import_date
		 * @var int $import_user
		 */
		extract( $this->get_import_options( $import_options ), EXTR_SKIP );

		// get import options
		$options = Plugin::instance()->get_options();

		if( !$options['enable_templates'] ) {
			// overwrite plugin import settings with import settings
			$options['import_description'] = $import_description;
			$options['import_title']       = $import_title;
		}

		$options['import_status'] = $import_status;

		// store results
		$result = [
			'private' 	=> 0,
			'imported' 	=> 0,
			'skipped' 	=> 0,
			'total'		=> count( $raw_feed ),
			'ids'		=> [],
			'error'		=> []
		];

		$duplicates = $this->get_duplicate_posts( $raw_feed, $this->post_type->get_post_type() );

		// parse feed
		foreach( $raw_feed as $video ){

			// video already exists, don't do anything
			if( array_key_exists( $video['video_id'], $duplicates ) ){

				/**
				 * Generate an error and pass it for debugging
				 * @var WP_Error
				 */
				$error = new WP_Error(
					'cvm_import_skip_existing_video',
					sprintf(
						'%s %s',
						sprintf(
							__( 'Skipped video having ID %s because it already exists.', 'codeflavors-vimeo-video-post-lite' ),
							$video['video_id']
						),
						sprintf(
							__( 'Existing post has ID %s.', 'codeflavors-vimeo-video-post-lite' ),
							$duplicates[ $video['video_id'] ][0]
						)
					),
					[
						'video_data' => $video,
						'existing_posts' => $duplicates[ $video['video_id'] ]
					]
				);
				$result['error'][] = $error;

				/**
				 * Pass error to debug function
				 */
				Helper::debug_message(
					'Import error: ' . $error->get_error_message(),
					"\n",
					$error
				);

				foreach( $duplicates[ $video['video_id'] ] as $_post_id ){
					// retrieve the post object for backwards compatibility
					$post = get_post( $_post_id );

					/**
					 * Action triggered when duplicate posts were detected.
					 * Can be used to set extra taxonomies for already existing posts.
					 *
					 * @param \WP_Post $post             The WordPress post object that was found as duplicate.
					 * @param string $taxonomy           The taxonomy that must be imported for the post.
					 * @param string $taxonomy_value     The plugin taxonomy that must be set up.
					 * @param string $tag_taxonomy       The tag taxonomy that must be set up.
					 * @param string $tag_taxonomy_value The tag taxonomy value that must be set for the post.
					 */
					do_action( 'vimeotheque\import_duplicate_taxonomies',
						$post,
						$this->post_type->get_post_tax(),
						$native_tax,
						$this->post_type->get_tag_tax(),
						$native_tag
					);
				}

				$result['skipped'] += 1;
				continue;
			}

			if( 'private' == $video['privacy'] ){
				$result['private'] += 1;
				if( 'skip' == $options['import_privacy'] ){
					$result['skipped'] += 1;

					/**
					 * Generate an error and pass it for debugging
					 * @var WP_Error
					 */
					$error = new WP_Error(
						'cvm_import_skip_private_video',
						sprintf(
							__( 'Skipped private video having ID %s because of plugin settings.', 'cvm-video' ),
							$video['video_id']
						),
						[
							'video_data' => $video
						]
					);
					$result['error'][] = $error;

					/**
					 * Send error to debug function
					 */
					Helper::debug_message(
						'Import error: ' . $error->get_error_message(),
						"\n",
						$error
					);

					continue;
				}
			}

			$post_id = $this->import_video( [
				'video' 		=> $video, // video details retrieved from Vimeo
				'category' 		=> $native_tax, // category name (if any) - will be created if category_id is false
				'tags'			=> $native_tag,
				'user'			=> ( isset( $import_user ) ? absint( $import_user ) : false ), // save as a given user if any
				'post_format'	=> 'video', // post format will default to video
				'status'		=> $this->post_type->get_post_settings()->post_status( $import_status ), // post status
				'options'		=> $options
			] );

			if( $post_id ){
				$result['imported'] += 1;
				$result['ids'][] = $post_id;

				$video = Helper::get_video_post( $post_id );
				$video->set_embed_options(
					$this->get_import_options( $import_options ),
					true
				);
			}
		}

		Helper::debug_message(
			sprintf(
				'Processed %d entries: created %d posts, skipped %d entries, %d entries were marked private.',
				$result['total'],
				$result['imported'],
				$result['skipped'],
				$result['private']
			)
		);

		return $result;
	}

	/**
	 * @param array $raw_feed
	 * @param $post_type
	 *
	 * @return array
	 */
	public function get_duplicate_posts( $raw_feed, $post_type ){

		if( !$raw_feed ){
			return [];
		}

		$video_ids = [];
		foreach( $raw_feed as $video ){
			$video_ids[] = $video['video_id'];
		}
		/**
		 * @var \WP_Query
		 */
		global $wpdb;
		$query = $wpdb->prepare(
			"
			SELECT {$wpdb->postmeta}.post_id, {$wpdb->postmeta}.meta_value 
			FROM {$wpdb->postmeta}
			LEFT JOIN {$wpdb->posts}
			ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID
			WHERE
			{$wpdb->posts}.post_type LIKE '%s' 
			AND meta_value IN(" . implode( ',', $video_ids ) . ")
			",
			$post_type
		);

		$existing = $wpdb->get_results( $query );
		$_result = [];

		if( $existing ){
			foreach( $existing as $r ){
				$_result[ $r->meta_value ][] = $r->post_id;
			}
		}

		/**
		 * Filter the duplicate posts found by the plugin.
		 * When perfoming imports, the filter runs when duplicate imports are detected.
		 *
		 * @param array $_result    The post IDs found as duplicates.
		 */
		$result = apply_filters( 'vimeotheque\duplicate_posts_found', $_result );

		if( $_result !== $result ){
			Helper::debug_message(
				sprintf(
					'Detected duplicate posts override by filter "%s".',
					'vimeotheque\duplicate_posts_found'
				)
			);
		}

		return $result;
	}

	/**
	 * Import a single video based on the passed data
	 *
	 * @param array $args
	 *
	 * @return bool|int
	 */
	public function import_video( $args = [] ){

		$defaults = [
			'video' 			=> [], // video details retrieved from Vimeo
			'post_id'           => false,
			'category' 			=> false, // category name (if any) - will be created if category_id is false
			'tags'				=> false,
			'user'				=> false, // save as a given user if any
			'post_format'		=> 'video', // post format will default to video
			'status'			=> 'draft', // post status
			'options'			=> false,
		];
		/**
		 * @var array $video
		 * @var int $post_id
		 * @var string $category
		 * @var array $tags
		 * @var int $user
		 * @var string $post_format
		 * @var string $status
		 * @var array $options
		 */
		extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

		if( !$options ){
			$options = Plugin::instance()->get_options();
		}

		// if no video details, bail out
		if( !$video ){
			return false;
		}

		/**
		 * Filter that allows changing of post format when importing videos.
		 *
		 * @param string $post_format   The post format.
		 */
		$post_format = apply_filters(
			'vimeotheque\import_post_format',
			$post_format
		);

		/**
		 * Filter that allows video imports.
		 * Can be used to prevent importing of videos.
		 *
		 * @param bool $allow       Allow video improts to be made (true) or prevent them (false).
		 * @param array $video      Video details array.
		 * @param string $post_type The post type that should be created from the video details.
		 * @param false $false      An unset parameter.
		 */
		$allow_import = apply_filters(
			'vimeotheque\allow_import',
			true,
			$video,
			$this->post_type->get_post_type(),
			false
		);

		if( !$allow_import ){
			/**
			 * Generate an error and pass it for debugging
			 * @var WP_Error
			 */
			$error = new WP_Error(
				'vimeotheque_video_import_prevented_by_filter',
				sprintf(
					__( 'Video having ID %s could not be imported because of a filter blocking all imports.', 'codeflavors-vimeo-video-post-lite' ),
					$video['video_id']
				),
				[ 'video_data' => $video ]
			);

			/**
			 * Send error to debug function
			 */
			Helper::debug_message(
				'Import error: ' . $error->get_error_message(),
				"\n",
				$error
			);

			return false;
		}

		// plugin settings; caller can pass their own import options
		if( !$options ){
			$options = Plugin::instance()->get_options();
		}

		if( 'private' == $video['privacy'] && 'pending' == $options['import_privacy'] ){
			$status = 'pending';
		}

		// post content
		$post_content = '';
		if( 'content' == $options['import_description'] || 'content_excerpt' == $options['import_description'] ){
			$post_content = $video['description'];
		}
		// post excerpt
		$post_excerpt = '';
		if( 'excerpt' == $options['import_description'] || 'content_excerpt' == $options['import_description'] ){
			$post_excerpt = $video['description'];
		}

		// post title
		$post_title 	= $options['import_title'] ? $video['title'] : '';

		/**
		 * Action that runs before the post is inserted into the database.
		 *
		 * @param array $video  The video details array retrieved from Vimeo.
		 * @param false $false  Always false in Vimeotheque Lite.
		 */
		do_action(
			'vimeotheque\import_before',
			$video,
			false
		);

		// set post data
		$post_data = [

			/**
			 * Post title filter before the post is inserted into the database.
			 *
			 * @param string $title     The post title.
			 * @param array $video      The video details.
			 * @param bool $false       Unused parameter.
			 */
			'post_title' 	=> apply_filters(
				'vimeotheque\import_post_title',
				$post_title,
				$video,
				false
			),

			/**
			 * Post content filter before the post is inserted into the database.
			 *
			 * @param string $content   The post content.
			 * @param array $video      The video details.
			 * @param false $false      Unused parameter.
			 */
			'post_content' 	=> apply_filters(
				'vimeotheque\import_post_content',
				$post_content,
				$video,
				false
			),

			/**
			 * Post excerpt filter before the post is inserted into the database.
			 *
			 * @param string $excerpt   The post excerpt.
			 * @param array $video      The video details.
			 * @param false $false      Unused parameter.
			 */
			'post_excerpt'	=> apply_filters(
				'vimeotheque\import_post_excerpt',
				$post_excerpt,
				$video,
				false
			),
			'post_type'		=> $this->post_type->get_post_type(),

			/**
			 * Post status filter before the post is inserted into the database.
			 *
			 * @param string $status    The post status.
			 * @param array $video      The video details.
			 * @param false $false      Unused parameter.
			 */
			'post_status'	=> apply_filters(
				'vimeotheque\import_post_status',
				$status,
				$video,
				false
			)
		];

		$pd = $options['import_date'] ? date('Y-m-d H:i:s', strtotime( $video['published'] )) : current_time( 'mysql' );

		/**
		 * Post date filter before the post is inserted into the database.
		 *
		 * @param string $date      The post date.
		 * @param array $video      The video details.
		 * @param false $false      Unused parameter.
		 */
		$post_date = apply_filters(
			'vimeotheque\import_post_date',
			$pd,
			$video,
			false
		);

		if( isset( $options['import_date'] ) && $options['import_date'] ){
			$post_data['post_date_gmt'] = $post_date;
			$post_data['edit_date']		= $post_date;
			$post_data['post_date']		= $post_date;
		}

		// set user
		if( $user ){
			$post_data['post_author'] = $user;
		}
		/**
		 * @var int|\WP_Error $post_id
		 */
		// single video import will pass post ID
		if( isset( $post_id ) && $post_id ){
			$post_data['ID'] = $post_id;
			$post_id = wp_update_post( $post_data, true );
		}else {
			// allow empty insert into post content
			add_filter(
				'wp_insert_post_empty_content',
				'__return_false'
			);

			$post_id = wp_insert_post( $post_data, true );
		}

		if( is_wp_error( $post_id ) ){
			Helper::debug_message(
				sprintf(
					'Video with ID %s generated the following database error on insert: "%s"; video post could not be created.',
					$video['video_id'],
					$post_id->get_error_message()
				)
			);
		}

		// check if post was created
		if( !is_wp_error( $post_id ) ){

			// set post format
			if( $post_format  ){
				set_post_format( $post_id, $post_format );
			}

			// set post category
			if( $category ){
				$category = is_array( $category ) ? $category : [ $category ];
				wp_set_post_terms( $post_id, $category, $this->post_type->get_post_tax() );
			}

			if( $tags ){
				wp_set_post_terms( $post_id, $tags, $this->post_type->get_tag_tax() );
			}

			// insert tags
			if( ( isset( $options['import_tags'] ) && $options['import_tags'] ) && $this->post_type->get_tag_tax() ){
				if( isset( $video['tags'] ) && is_array( $video['tags'] ) ){
					$count = absint( $options['max_tags'] );
					$tags = array_slice( $video['tags'], 0, $count );
					if( $tags ){
						wp_set_post_terms( $post_id, $tags, $this->post_type->get_tag_tax(), true );
					}
				}
			}

			// set post meta
			$_post = Helper::get_video_post( $post_id );
			$_post->set_video_data( $video );
			$_post->set_video_id_meta();
			$_post->set_video_url_meta();

			/**
			 * Action after a video post was successfully imported into the database.
			 *
			 * @param int $post_id          ID of the post newly created from the Vimeo video.
			 * @param array $video          Array of video details retrieved from Vimeo.
			 * @param false $unknown        Unused parameter.
			 * @param string $post_type     The post type that was created.
			 */
			do_action(
				'vimeotheque\import_success',
				$post_id,
				$video,
				false,
				$this->post_type->get_post_type()
			);

			/**
			 * Send a debug message
			 */
			Helper::debug_message(
				sprintf(
					'Imported video ID %s into post #%d having post type "%s".',
					$video['video_id'],
					$post_id,
					$this->post_type->get_post_type()
				)
			);

			// import image
			if( $options['featured_image'] ){
				$_post->set_featured_image();
			}

			return $post_id;

		}// end checking if not wp error on post insert

		return false;
	}

	/**
	 * Process the import options
	 *
	 * @param array $source
	 *
	 * @return array
	 */
	private function get_import_options( $source = [] ){
		$taxonomy = $this->post_type->get_post_tax();
		$tag_tax = $this->post_type->get_tag_tax();
		$native_tax = isset( $source['tax_input'][ $taxonomy ] ) ? (array) $source['tax_input'][ $taxonomy ] : [];
		$native_tag = isset( $source['tax_input'][ $tag_tax ] ) ? (array) $source['tax_input'][ $tag_tax ] : [];

		$import_options = [
			'native_tax'		=> $native_tax,
			'native_tag'		=> $native_tag,
			'import_description' => $source['import_description'],
			'import_status' => $source['import_status'],
			'import_title' => isset( $source['import_title'] )
		];

		return $import_options;
	}
}