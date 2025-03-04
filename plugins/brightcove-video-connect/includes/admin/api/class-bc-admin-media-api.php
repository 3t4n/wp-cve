<?php
/**
 * BC_Admin_Media_API class file.
 *
 * @package Brightcove_Video_Connect
 */

/**
 * BC_Admin_Media_API class.
 */
class BC_Admin_Media_API {

	/**
	 * BC_CMS_API object
	 *
	 * @var BC_CMS_API
	 */
	protected $cms_api;

	/**
	 * BC_Player_Management_API object
	 *
	 * @var BC_Player_Management_API
	 */
	protected $player_api;

	/**
	 * BC_Experiences_API object
	 *
	 * @var BC_Experiences_API
	 */
	protected $experiences_api;

	/**
	 * BC_Playlists object
	 *
	 * @var BC_Playlists
	 */
	protected $playlists;

	/**
	 * BC_Video_Upload object
	 *
	 * @var BC_Video_Upload
	 */
	protected $video_upload;

	/**
	 * BC_Videos Object
	 *
	 * @var BC_Videos
	 */
	protected $videos;

	/**
	 * Constructor method
	 */
	public function __construct() {

		global $bc_accounts;

		if ( ! $bc_accounts || ! $bc_accounts->get_account_details_for_user() ) {
			return new WP_Error( 'bc-account-no-perms-invalid', esc_html__( 'You do not have permission to use this Brightcove Account', 'brightcove' ) );
		}

		$this->cms_api         = new BC_CMS_API();
		$this->player_api      = new BC_Player_Management_API();
		$this->experiences_api = new BC_Experiences_API();
		$this->playlists       = new BC_Playlists( $this->cms_api );
		$this->videos          = new BC_Videos( $this->cms_api );
		$this->video_upload    = new BC_Video_Upload( $this->cms_api );

		/* All of these actions are for authenticated users only for a reason */
		add_action( 'wp_ajax_bc_media_query', array( $this, 'brightcove_media_query' ) );
		add_action( 'wp_ajax_bc_media_update', array( $this, 'bc_ajax_update_video_or_playlist' ) );
		add_action( 'wp_ajax_bc_media_delete', array( $this, 'bc_ajax_delete_video_or_playlist' ) );
		add_action( 'wp_ajax_bc_media_upload', array( $this, 'brightcove_media_upload' ) ); // For uploading a file.
		add_action( 'wp_ajax_bc_poster_upload', array( $this, 'ajax_poster_upload' ), 10, 5 );
		add_action( 'wp_ajax_bc_thumb_upload', array( $this, 'ajax_thumb_upload' ), 10, 5 );
		add_action( 'wp_ajax_bc_caption_upload', array( $this, 'ajax_caption_upload' ), 10, 3 );
		add_action( 'wp_ajax_bc_media_players', array( $this, 'ajax_players' ) );
		add_filter( 'heartbeat_received', array( $this, 'heartbeat_received' ), 10, 2 );
		add_filter( 'brightcove_media_query_results', array( $this, 'add_in_process_videos' ), 10, 2 );

		add_action( 'wp_ajax_bc_resolve_shortcode', array( $this, 'resolve_shortcode' ) );
	}

	/**
	 * Sends shortcode over.
	 */
	public function resolve_shortcode() {

		$video_id   = ( ! empty( $_POST['video_id'] ) ) ? sanitize_text_field( wp_unslash( $_POST['video_id'] ) ) : 0;
		$account_id = ( ! empty( $_POST['account_id'] ) ) ? sanitize_text_field( wp_unslash( $_POST['account_id'] ) ) : 0;

		$default_shortcode = '[bc_video video_id="' . absint( $video_id ) . '" account_id="' . absint( $account_id ) . '" player_id="default" embed="iframe" padding_top="56.25%" autoplay="" min_width="0px" max_width="640px" mute="" width="100%" height="100%"]';

		// If no shortcode was supplied, let's show a default one.
		$shortcode = ( ! empty( $_POST['shortcode'] ) ) ? sanitize_text_field( wp_unslash( $_POST['shortcode'] ) ) : $default_shortcode;

		wp_send_json_success( do_shortcode( $shortcode ) );
	}

	/**
	 * Helper function for validating Ajax requests
	 */
	protected function bc_helper_check_ajax() {

		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset( $_POST['nonce'] ) ) {
			wp_send_json_error( esc_html__( 'Invalid Request', 'brightcove' ) );
		}

		$nonce = $_POST['nonce'];

		if ( ! wp_verify_nonce( $nonce, '_bc_ajax_search_nonce' ) ) {
			wp_send_json_error( esc_html__( 'Invalid Request', 'brightcove' ) );
		}
	}

	/**
	 * Update a video or a playlist via ajax
	 *
	 * @global BC_Accounts $bc_accounts
	 */
	public function bc_ajax_update_video_or_playlist() {

		global $bc_accounts;

		// Call this to check if we are allowed to continue..
		$this->bc_helper_check_ajax();

		$type_msg     = '';
		$updated_data = array();

		// Check if playlist or video data was sent.
		$fields = array(
			'long_description',
			'description',
			'name',
			'playlist_id',
			'video_id',
			'tags',
			'width',
			'height',
			'state',
			'scheduled_start_date',
			'scheduled_end_date',
			'sub_type',
			'language',
		);

		foreach ( $fields as $field ) {
			$updated_data[ $field ] = isset( $_POST[ $field ] ) ? sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) : '';
		}

		// Only Playlists have playlist_videos. We only do this if we're updating playlists.
		if ( isset( $_POST['playlist_videos'] ) ) {

			$playlist_videos = array();

			foreach ( $_POST['playlist_videos'] as $video_id ) {
				$playlist_videos[] = BC_Utility::sanitize_id( $video_id );
			}

			$updated_data['playlist_videos'] = $playlist_videos;

		}

		// Only Videos have a video_id so we only do this when updating a video.
		if ( isset( $_POST['video_id'] ) ) {
			$updated_data['video_id'] = BC_Utility::sanitize_id( $_POST['video_id'] );
		}

		$labels = array();
		if ( isset( $_POST['labels'] ) ) {
			foreach ( $_POST['labels'] as $label ) {
				$labels[] = sanitize_text_field( $label );
			}
		}

		$updated_data['labels'] = $labels;

		// If custom fields are sent, be sure to sanitize them separately
		if ( isset( $_POST['custom_fields'] ) ) {
			$custom = array();
			foreach ( $_POST['custom_fields'] as $id => $value ) {
				$id    = sanitize_text_field( $id );
				$value = sanitize_text_field( $value );

				if ( ! empty( $id ) && ! empty( $value ) ) {
					$custom[ $id ] = $value;
				}
			}

			// Get a list of available custom fields
			$fields      = $this->cms_api->video_fields();
			$use_history = false;
			foreach ( $fields['custom_fields'] as $item ) {
				if ( '_change_history' === $item['id'] ) {
					$use_history = true;
					break;
				}
			}

			// Build out history if it's supported
			if ( $use_history ) {
				$history = null;
				if ( isset( $_POST['history'] ) ) {
					$raw     = wp_unslash( $_POST['history'] );
					$history = json_decode( $raw, true );
				}
				if ( null === $history ) {
					$history = array();
				}

				$user      = wp_get_current_user();
				$history[] = array(
					'user' => $user->user_login,
					'time' => gmdate( 'Y-m-d H:i:s', time() ),
				);

				$custom['_change_history'] = wp_json_encode( $history );
			}

			$updated_data['custom_fields'] = $custom;
		}

		$updated_data['update-playlist-metadata'] = sanitize_text_field( $_POST['nonce'] );

		$status = false;

		if ( ! in_array( $_POST['type'], array( 'playlists', 'videos' ), true ) ) {
			wp_send_json_error( __( 'Type is not specified', 'brightcove' ) );
		}

		$hash = $_POST['account'];

		if ( false === $bc_accounts->get_account_by_hash( $hash ) ) {
			wp_send_json_error( __( 'No such account exists', 'brightcove' ) );
		}

		$updated_data['account'] = $hash;

		if ( 'playlists' === $_POST['type'] ) {

			$updated_data['playlist-name'] = $updated_data['name'];
			$status                        = $this->playlists->update_bc_playlist( $updated_data );
			$type_msg                      = 'playlist';

		} elseif ( 'videos' === $_POST['type'] ) {
			$type_msg = 'video';
			if ( 'variant' === $_POST['sub_type'] ) {
				$status = $this->videos->update_bc_video( $updated_data, sanitize_text_field( $_POST['sub_type'] ) );
			} else {
				$status = $this->videos->update_bc_video( $updated_data );

				if ( isset( $_POST['folder_id'] ) && isset( $_POST['old_folder_id'] ) ) {
					$folder_id     = sanitize_text_field( $_POST['folder_id'] );
					$old_folder_id = sanitize_text_field( $_POST['old_folder_id'] );

					$this->cms_api->add_folder_to_video( $old_folder_id, $folder_id, $updated_data['video_id'] );
				}

				// Maybe update poster
				if ( isset( $_POST['poster'] ) ) {
					$poster_data = json_decode( wp_unslash( $_POST['poster'] ), true );

					if ( $poster_data ) {
						// Maybe update poster
						$this->ajax_poster_upload( $hash, $updated_data['video_id'], $poster_data['url'], $poster_data['width'], $poster_data['height'] );
					}
				}

				// Maybe update thumbnail
				if ( isset( $_POST['thumbnail'] ) ) {
					$thumb_data = json_decode( wp_unslash( $_POST['thumbnail'] ), true );

					if ( $thumb_data ) {
						// Maybe update poster
						$this->ajax_thumb_upload( $hash, $updated_data['video_id'], $thumb_data['url'], $thumb_data['width'], $thumb_data['height'] );
					}
				}

				if ( isset( $_POST['captions'] ) ) {
					// Maybe update captions
					$this->ajax_caption_upload( $hash, $updated_data['video_id'], $_POST['captions'] );
				} else {
					$this->ajax_caption_delete( $hash, $updated_data['video_id'] );
				}
			}
		}

		BC_Utility::delete_cache_item( '*' );
		$bc_accounts->restore_default_account();

		BC_Utility::delete_cache_item( '*' ); // Clear the cache of video lists retrieved.

		if ( true === $status ) {

			wp_send_json_success( esc_html__( 'Successfully updated ', 'brightcove' ) . esc_html( $type_msg ) );

		} elseif ( is_wp_error( $status ) ) {

			wp_send_json_error( esc_html__( 'Failed to sync with WordPress!', 'brightcove' ) );

		} else {

			wp_send_json_error( esc_html__( 'Failed to update ', 'brightcove' ) . esc_html( $type_msg ) . '!' );

		}
	}

	/**
	 * Delete video or playlist
	 *
	 * Process call to delete video or playlist which is sent to the Brightcove API
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function bc_ajax_delete_video_or_playlist() {

		global $bc_accounts;

		// Call this to check if we are allowed to continue.
		$this->bc_helper_check_ajax();

		$type     = sanitize_key( $_POST['type'] );
		$type_msg = '';
		$id       = BC_Utility::sanitize_id( $_POST['id'] );

		// Get type brightcove-playlist or brightcove-video.
		if ( ! in_array( $type, array( 'playlists', 'videos' ), true ) ) {
			wp_send_json_error( esc_html__( 'Type is not specified!', 'brightcove' ) );
		}

		// Try to get the existing post based on id.
		if ( 'playlists' === $type ) {

			// Get playlist.
			$type_msg = 'playlist';

		} elseif ( 'videos' === $type ) {

			// Find existing video.
			$type_msg = 'video';

		} else {

			wp_send_json_error( esc_html__( 'Wrong type is specified!', 'brightcove' ) );

		}

		$hash = sanitize_text_field( $_POST['account'] );

		if ( false === $bc_accounts->get_account_by_hash( $hash ) ) {
			wp_send_json_error( __( 'No such account exists', 'brightcove' ) );
		}

		$bc_accounts->set_current_account( $hash );

		// Remove from Brightcove.
		$delete_status  = false;
		$delete_message = '';

		if ( 'videos' === $type ) {

			$delete_status = $this->cms_api->video_delete( $id );

			if ( ! $delete_status ) {

				// We were not able to delete video from Brightcove.
				$delete_message = esc_html__( 'Unable to remove video from Brightcove!', 'brightcove' );

			} else {

				$delete_message = esc_html__( 'Successfully deleted your video.', 'brightcove' );

			}
		} elseif ( 'playlists' === $type ) {

			$delete_status = $this->cms_api->playlist_delete( $id );

			if ( ! $delete_status ) {

				// We were not able to delete playlist from Brightcove.
				$delete_message = esc_html__( 'Unable to remove playlist from Brightcove!', 'brightcove' );

			} else {

				$delete_message = esc_html__( 'Successfully deleted your playlist.', 'brightcove' );

			}
		}

		BC_Utility::delete_cache_item( '*' );
		$bc_accounts->restore_default_account();

		if ( $delete_status ) {

			wp_send_json_success( $delete_message );

		} else {

			wp_send_json_error( $delete_message );

		}
	}

	/**
	 * Handles video uploads
	 *
	 * Handles videos uploaded via WordPress.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function brightcove_media_upload() {

		global $bc_accounts;

		foreach ( array( 'nonce', 'tags', 'name', 'account' ) as $parameter ) {
			if ( ! isset( $_POST[ $parameter ] ) ) {
				wp_send_json_error();
			}
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], '_bc_ajax_search_nonce' ) ) {
			wp_send_json_error();
		}

		$account_hash = sanitize_text_field( $_POST['account'] );
		$account      = $bc_accounts->set_current_account( $account_hash );

		$tags = sanitize_text_field( $_POST['tags'] );
		$name = sanitize_text_field( $_POST['name'] );

		if ( ! is_array( $account ) || ! is_string( $name ) || '' === $name ) {
			$bc_accounts->restore_default_account();
			wp_send_json_error();
		}

		$ingestion_request_status = $this->video_upload->process_uploaded_video( $_FILES, $account_hash, $tags, $name );
		$bc_accounts->restore_default_account();

		if ( is_wp_error( $ingestion_request_status ) ) {
			wp_send_json_error( $ingestion_request_status->get_error_message() );
		}

		$this->videos->add_or_update_wp_video( $ingestion_request_status['videoDetails'], true );
		wp_send_json_success( $ingestion_request_status );
	}

	/**
	 * Retrieves videos and playlists
	 *
	 * Handles the query and distributes to the proper part of the CMS API.
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function brightcove_media_query() {

		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], '_bc_ajax_search_nonce' ) ) {
			wp_send_json_error();
		}

		$video_ids = false; // Used for the playlist edit.

		if ( isset( $_POST['videoIds'] ) ) {

			if ( 'none' === $_POST['videoIds'] ) {
				wp_send_json_success( array() ); // Existing playlist with no video IDs.
			}

			$video_ids = array();

			// To handle the playlist of one video ID which is sent as a string instead of Array of 1 element.
			if ( is_string( $_POST['videoIds'] ) ) {

				$video_ids[] = BC_Utility::sanitize_id( $_POST['videoIds'] );

			} else {

				foreach ( $_POST['videoIds'] as $video_id ) {
					$video_ids[] = BC_Utility::sanitize_id( $video_id );
				}
			}
		}

		$account_id = ( isset( $_POST['account'] ) ) ? sanitize_text_field( $_POST['account'] ) : 'all';

		if ( 'all' !== $account_id ) {
			$account_id = BC_Utility::sanitize_id( $_POST['account'] );
		}

		$query     = ( isset( $_POST['search'] ) && '' !== $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : false;
		$tag_name  = ( isset( $_POST['tagName'] ) && '' !== $_POST['tagName'] ) ? sanitize_text_field( $_POST['tagName'] ) : false;
		$dates     = ( isset( $_POST['dates'] ) && 'all' !== $_POST['dates'] ) ? BC_Utility::sanitize_date( $_POST['dates'] ) : false;
		$folder_id = ( isset( $_POST['folder_id'] ) && '' !== $_POST['folder_id'] ) ? sanitize_text_field( $_POST['folder_id'] ) : false;
		$label     = ( isset( $_POST['labelPath'] ) && '' !== $_POST['labelPath'] ) ? sanitize_text_field( $_POST['labelPath'] ) : false;
		$state     = ( isset( $_POST['state'] ) && '' !== $_POST['state'] ) ? sanitize_text_field( $_POST['state'] ) : apply_filters( 'brightcove_state_filter', false );

		/**
		 * Filter the maximum number of items the brightcove media call will query for.
		 *
		 * Enables adjusting the `posts_per_page` parameter used when querying for media. Absint is applied,
		 * so a positive number should be supplied.
		 *
		 * @param int $posts_per_page Posts per page for media query. Default 100.
		 */
		$posts_per_page = isset( $_POST['posts_per_page'] ) ? absint( $_POST['posts_per_page'] ) : apply_filters( 'brightcove_max_posts_per_page', 100 );
		$page           = isset( $_POST['page_number'] ) ? absint( $_POST['page_number'] ) : 1;

		$type = isset( $_POST['type'] ) ? sanitize_key( $_POST['type'] ) : false;

		if ( ! $type || ! in_array( $type, array( 'videos', 'playlists', 'videoexperience', 'playlistexperience', 'inpageexperiences' ), true ) ) {

			wp_send_json_error( esc_html__( 'Invalid Search Type', 'brightcove' ) );
			exit; // Type can only be videos or playlists.

		}

		global $bc_accounts;

		$tries = apply_filters( 'wpbc_api_tries', 3 );

		if ( 'videos' === $type || 'videoexperience' === $type ) {

			$query_terms = array();

			if ( $tag_name ) {
				// Tag Dropdown Search should use quotes to signify an exact match.
				// Handles single and multi-word tags
				$query_terms[] = 'tags:"' . $tag_name . '"';
			}

			if ( $dates ) {

				$query_terms[] = "updated_at:{$dates}-01..{$dates}-31";
			}

			if ( $query ) {

				array_unshift( $query_terms, $query );
			}

			if ( $state && 'all' !== $state ) {
				$query_terms[] = 'state:' . $state;
			}

			if ( $label ) {
				$query_terms[] = 'labels:' . $label;
			}

			if ( $video_ids ) {

				// We send the video_ids sorted since we have them returned sorted by ID.
				// This way we get cache hits when playlist order, but not content have changed.
				$video_ids_sorted = $video_ids;
				sort( $video_ids_sorted );
				$query_terms[] = 'id:' . implode( '+id:', $video_ids_sorted );

			}

			$query_string = implode( '+', apply_filters( 'bc_video_query_terms', $query_terms ) );

			/**
			 * For playlists, we specify the order in the query string as follows:
			 * https://cms.api.brightcove.com/v1/accounts/<account_id>/videos?q=id:<video_id1>...+id:<video_idn>
			 *
			 * However it comes back to us sorted by video ID (smallest to largest, so afterwards we resort the dataset
			 * by playlist sort order.
			 */

			$bc_accounts->set_current_account_by_id( $account_id );

			/**
			 * Filter to modify the default sort order.
			 * Ref: https://support.brightcove.com/overview-cms-api#parameters
			 *
			 * @param string Valid sort field name.
			 */
			$bc_video_sort_field = apply_filters( 'bc_video_sort_field', 'updated_at' );

			// Get a list of videos.

			for ( $i = 0; $i < $tries; $i ++ ) {
				$results = $this->cms_api->video_list( $posts_per_page, $posts_per_page * ( $page - 1 ), $query_string, $bc_video_sort_field, true, $folder_id );

				if ( ! is_wp_error( $results ) ) {
					break;
				} else {
					sleep( 1 ); // Sleep for 1 second on a failure
				}
			}

			if ( is_wp_error( $results ) ) {
				wp_send_json_error();
			}

			/**
			 * Since we use the video_list to fetch the videos for a playlist, it returns them to us
			 * ordered by video_id, so we use the order of video_ids (the playlist order) to sort them
			 * as per the playlist.
			 */

			if ( $video_ids ) {

				$ordered_results = array();

				foreach ( $video_ids as $video_id ) {

					foreach ( $results as $video_result ) {

						if ( $video_id === $video_result['id'] ) {

							$ordered_results[] = $video_result;
							break;

						}
					}
				}

				// $ordered_results is now in the same order as $video_ids
				$results = $ordered_results;

			}
		} elseif ( 'playlists' === $type || 'playlistexperience' === $type ) {

			$bc_accounts->set_current_account_by_id( $account_id );

			for ( $i = 0; $i < $tries; $i ++ ) {
				$results = $this->cms_api->playlist_list( $query );

				if ( ! is_wp_error( $results ) ) {
					break;
				} else {
					sleep( 1 ); // Sleep for 1 second on a failure
				}
			}

			if ( is_wp_error( $results ) ) {
				wp_send_json_error();
			}
		} elseif ( 'inpageexperiences' === $type ) {
			if ( ! is_numeric( $account_id ) ) {
				$account_id = $bc_accounts->get_account_id();
			}

			$bc_accounts->set_current_account_by_id( $account_id );

			for ( $i = 0; $i < $tries; $i ++ ) {
				$results = $this->experiences_api->get_experiences_by_account_id( $account_id );

				if ( ! is_wp_error( $results ) ) {
					break;
				} else {
					sleep( 1 ); // Sleep for 1 second on a failure
				}
			}

			if ( is_wp_error( $results ) || ! isset( $results['items'] ) ) {
				wp_send_json_error();
			}

			$results = $results['items'];
		}

		// Get a list of available custom fields
		for ( $i = 0; $i < $tries; $i ++ ) {
			$fields = $this->cms_api->video_fields();

			if ( ! is_wp_error( $fields ) ) {
				break;
			} else {
				sleep( 1 ); // Sleep for 1 second on a failure
			}
		}

		if ( is_wp_error( $fields ) ) {
			wp_send_json_error();
		}

		$processed_results = array();
		// Loop through results to remap items
		foreach ( $results as $result ) {
			// Map the custom_fields array to a collection of objects with description, display name, id, etc
			$result['custom'] = $fields['custom_fields'];

			if ( isset( $result['custom_fields'] ) ) {
				foreach ( $result['custom_fields'] as $id => $value ) {
					// Extract the change tracking item explicitly
					if ( '_change_history' === $id ) {
						$result['history'] = $value;
						continue;
					}

					foreach ( $result['custom'] as &$field ) {
						if ( $field['id'] === $id ) {
							$field['value'] = $value;
							break;
						}
					}
				}
			}

			// Massage the text tracks
			$result['captions'] = array();

			if ( isset( $result['test_tracks'] ) ) {
				foreach ( $result['text_tracks'] as $caption ) {
					$result['captions'][] = array(
						'source'   => $caption['src'],
						'language' => $caption['srclang'],
						'label'    => $caption['label'],
					);
				}
			}

			$processed_results[] = $result;
		}

		$bc_accounts->restore_default_account();

		/**
		 * Filter media query results.
		 *
		 * @since 1.3
		 */
		$results = apply_filters( 'brightcove_media_query_results', $processed_results, $type );

		wp_send_json_success( $results );
	}

	/**
	 * Retrieve a list of players available for usage on the front-end
	 *
	 * Requires the following fields:
	 *  - nonce   WordPress nonce to prevent replay attacks
	 *  - account ID of the account we're referencing
	 *
	 * Will return an array of objects (in JSON) representing available players. Each player will roughly contain:
	 * - accountId   (string)
	 * - id          (string)
	 * - name        (string)
	 * - description (string)
	 * - branches    (object)
	 * - created_at  (datetime)
	 * - url         (string)
	 * - embed_count (integer)
	 *
	 * @see http://docs.brightcove.com/en/video-cloud/player-management/reference/versions/v1/index.html#api-Players-Get_All_Players
	 *
	 * @global BC_Accounts $bc_accounts
	 */
	public function ajax_players() {
		global $bc_accounts;

		// Ensure all required fields were sent
		foreach ( array( 'nonce', 'account' ) as $parameter ) {
			if ( ! isset( $_POST[ $parameter ] ) ) {
				wp_send_json_error();
			}
		}

		// Validate our nonce
		if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], '_bc_ajax_players' ) ) {
			wp_send_json_error(); // Nonce was invalid, fail
		}

		// Set up the account from which we're fetching data
		$account_id = sanitize_text_field( $_POST['account'] );
		$account    = $bc_accounts->set_current_account_by_id( $account_id );

		if ( false === $account ) {
			wp_send_json_error(); // Account was invalid, fail
		}

		// Get players from Brightcove
		$players = $this->player_api->player_list();

		// Restore our global, default account
		$bc_accounts->restore_default_account();

		if ( false === $players ) {
			wp_send_json_error(); // Retrieval failed, fail
		}

		wp_send_json_success( $players );
	}

	/**
	 * Handle an uploaded preroll image and associate it with a specific video
	 *
	 * @global BC_Accounts $bc_accounts
	 *
	 * @param string $account_hash The account hash for the account.
	 * @param int    $video_id     The ID of the video to associate the image with.
	 * @param string $url          The URL of the image to upload.
	 * @param int    $width        The width of the image.
	 * @param int    $height       The height of the image.
	 */
	public function ajax_poster_upload( $account_hash, $video_id, $url, $width, $height ) {
		global $bc_accounts;

		// Set up the account to which we're pushing data
		$account = $bc_accounts->set_current_account( $account_hash );
		if ( false === $account ) { // Account was invalid, fail
			// Restore our global, default account
			$bc_accounts->restore_default_account();

			return;
		}

		// Sanitize our passed data
		$video_id = BC_Utility::sanitize_id( $video_id );
		$url      = esc_url( $url );
		if ( empty( $url ) ) { // Attachment has no URL, fail
			$bc_accounts->restore_default_account();

			return;
		}
		$height = absint( $height );
		$width  = absint( $width );

		// Push the poster to Brightcove
		$s = $this->cms_api->poster_upload( $video_id, $url, $height, $width );

		// Restore our global, default account
		$bc_accounts->restore_default_account();
	}

	/**
	 * Handle an uploaded thumbnail image and associate it with a specific video
	 *
	 * @global BC_Accounts $bc_accounts
	 *
	 * @param string $account_hash The account hash for the account.
	 * @param int    $video_id     The video ID.
	 * @param string $url          The URL of the thumbnail attachment.
	 * @param int    $width        The width of the thumbnail.
	 * @param int    $height       The height of the thumbnail.
	 */
	public function ajax_thumb_upload( $account_hash, $video_id, $url, $width, $height ) {
		global $bc_accounts;

		// Set up the account to which we're pushing data
		$account = $bc_accounts->set_current_account( $account_hash );
		if ( false === $account ) { // Account was invalid, fail
			// Restore our global, default account
			$bc_accounts->restore_default_account();

			return;
		}

		// Sanitize our passed data
		$video_id = BC_Utility::sanitize_id( $video_id );
		$url      = esc_url( $url );
		if ( empty( $url ) ) { // Attachment has no URL, fail
			$bc_accounts->restore_default_account();

			return;
		}
		$height = absint( $height );
		$width  = absint( $width );

		// Push the thumbnail to Brightcove
		$this->cms_api->thumbnail_upload( $video_id, $url, $height, $width );

		// Restore our global, default account
		$bc_accounts->restore_default_account();
	}

	/**
	 * Handle deleting all captions from the specified video.
	 *
	 * @global BC_Accounts $bc_accounts
	 *
	 * @param string $account_hash The account hash to which the video belongs.
	 * @param int    $video_id     The ID of the video from which to delete captions.
	 */
	public function ajax_caption_delete( $account_hash, $video_id ) {
		global $bc_accounts;

		// Set up the account to which we're pushing data
		$account = $bc_accounts->set_current_account( $account_hash );
		if ( ! $account ) {
			$bc_accounts->restore_default_account();

			return;
		}

		$this->cms_api->text_track_delete( $video_id );

		// Restore our global, default account
		$bc_accounts->restore_default_account();
	}

	/**
	 * Handle an uploaded caption file and associate it with the specified video
	 *
	 * @global BC_Accounts $bc_accounts
	 *
	 * @param string $account_hash The account hash for the account.
	 * @param int    $video_id     The video ID
	 * @param array  $raw_captions The raw captions data.
	 */
	public function ajax_caption_upload( $account_hash, $video_id, $raw_captions ) {
		global $bc_accounts;

		// Set up the account to which we're pushing data
		$account = $bc_accounts->set_current_account( $account_hash );
		if ( false === $account ) {
			$bc_accounts->restore_default_account();

			return;
		}

		// Sanitize our passed data
		$video_id     = BC_Utility::sanitize_id( $video_id );
		$new_captions = array();
		$old_captions = array();
		foreach ( $raw_captions as $caption ) {
			// Validate required fields
			if ( ! isset( $caption['source'] ) || ! isset( $caption['language'] ) ) {
				continue;
			}

			$url  = esc_url( $caption['source'] );
			$lang = sanitize_text_field( $caption['language'] );
			if ( empty( $url ) || empty( $lang ) ) {
				continue; // Attachment has no URL, fail
			}
			$label   = isset( $caption['label'] ) ? sanitize_text_field( $caption['label'] ) : '';
			$default = ( isset( $caption['default'] ) && 'checked' === $caption['default'] );

			$source = wp_parse_url( $caption['source'] );
			if ( 0 === strpos( $source['host'], 'brightcove' ) && false === strpos( $source['host'], '.test' ) ) {
				// If the hostname starts with "brightcove," assume this media has already been ingested and add to old captions.
				$old_captions[] = new BC_Text_Track( $url, $lang, 'captions', $label, $default );
				continue;
			}

			$new_captions[] = new BC_Text_Track( $url, $lang, 'captions', $label, $default );
		}

		// We need to handle the text track updating first because the PATCH request would override the newly ingested text tracks.
		$this->cms_api->text_track_update( $video_id, $old_captions );

		if ( empty( $new_captions ) ) {
			$bc_accounts->restore_default_account();
			return; // After sanitization, we have no valid new captions
		}

		// Push the new captions to Brightcove
		$has_caption_uploaded = $this->cms_api->text_track_upload( $video_id, $new_captions );

		// Restore our global, default account
		$bc_accounts->restore_default_account();

		if ( false === $has_caption_uploaded ) {
			wp_send_json_error( esc_html__( 'Failed to upload caption.', 'brightcove' ) );
		}
	}

	/**
	 * Return a set of the most recent videos for the specified account.
	 *
	 * @param string $account_id The account ID to retrieve videos for.
	 * @param int    $count      The number of videos to return.
	 *
	 * @global BC_Accounts $bc_accounts
	 *
	 * @return array
	 */
	protected function fetch_videos( $account_id, $count = 10 ) {
		global $bc_accounts;

		$transient_key = BC_Utility::generate_transient_key( '_brightcove_req_heartbeat_', $account_id );
		$results       = BC_Utility::get_cache_item( $transient_key );
		$results       = is_array( $results ) ? $results : array();

		if ( empty( $results ) ) {
			// Set up the account from which we're fetching data
			$account = $bc_accounts->set_current_account_by_id( $account_id );
			if ( false === $account ) { // Account was invalid, fail
				// Restore our global, default account
				$bc_accounts->restore_default_account();

				return array();
			}

			// Get a list of videos
			$results = $this->cms_api->video_list( $count, 0, '', 'updated_at' );

			if ( is_wp_error( $results ) ) {

				BC_Logging::log( sprintf( 'BC API ERROR: %s', $results->get_error_message() ) );

				return array();
			}

			// Get a list of available custom fields
			$fields = $this->cms_api->video_fields();

			// Loop through results to remap items
			foreach ( $results as &$result ) {
				// Map the custom_fields array to a collection of objects with description, display name, id, etc
				$result['custom'] = $fields['custom_fields'];

				foreach ( $result['custom_fields'] as $id => $value ) {
					// Extract the change tracking item explicitly
					if ( '_change_history' === $id ) {
						$result['history'] = $value;
						continue;
					}

					foreach ( $result['custom'] as &$field ) {
						if ( $field['id'] === $id ) {
							$field['value'] = $value;
							break;
						}
					}
				}

				// Massage the text tracks
				$result['captions'] = array();

				if ( ! empty( $result['text_tracks'] ) ) {
					foreach ( $result['text_tracks'] as $caption ) {
						$result['captions'][] = array(
							'source'   => $caption['src'],
							'language' => $caption['srclang'],
							'label'    => $caption['label'],
						);
					}
				}
			}

			$bc_accounts->restore_default_account();

			if ( ! empty( $results ) ) {
				BC_Utility::set_cache_item( $transient_key, 'video_list', $results, 600 ); // High cache time due to high expense of fetching the data.
			}
		}

		return $results;
	}

	/**
	 * When a WP heartbeat is received with an account hash, respond with the most recent 10 videos available.
	 *
	 * @param array $response WP heartbeat response.
	 * @param array $data Data received from heartbeat
	 *
	 * @return array
	 */
	public function heartbeat_received( $response, $data ) {
		if ( isset( $data['brightcove_heartbeat'] ) ) {
			$response['brightcove_heartbeat']               = array();
			$response['brightcove_heartbeat']['videos']     = $this->fetch_videos( $data['brightcove_heartbeat']['accountId'] );
			$response['brightcove_heartbeat']['account_id'] = $data['brightcove_heartbeat']['accountId'];
		}

		return $response;
	}

	/**
	 * Add in process videos to media query results.
	 * Also clear in process videos if they are already returned by brightcove.
	 *
	 * @param array $videos List of videos.
	 *
	 * @return array Processed list of videos.
	 */
	public function add_in_process_videos( $videos ) {
		$video_ids      = wp_list_pluck( $videos, 'id' );
		$video_post_ids = $this->videos->get_in_progress_videos();

		foreach ( $video_post_ids as $video_post_id ) {
			$in_process_video_id = BC_Utility::get_sanitized_video_id( $video_post_id );
			if ( in_array( $in_process_video_id, $video_ids, true ) ) {
				wp_delete_post( $video_post_id, true );
			} else {
				$videos[] = get_post_meta( $video_post_id, '_brightcove_video_object', true );
			}
		}

		return $videos;
	}
}
