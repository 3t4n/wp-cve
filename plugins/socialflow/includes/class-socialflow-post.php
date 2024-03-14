<?php
/**
 * Holds the SocialFlow Post class
 * is responsible for adding meta boxes and fires message compose
 * adds some changes in admin posts interface
 *
 * @package SocialFlow
 * @since 2.0
 */

/**
 * SocialFlow_Post
 */
class SocialFlow_Post {

	/**
	 * Hold post options
	 *
	 * @var array
	 * @since 2.1
	 */
	public $js_settings = array();

	/**
	 * Used post ids in transition post status
	 * used to avoid duplication
	 *
	 * @var array
	 * @since 2.7
	 */
	protected $used_post_ids = array();

	/**
	 * Post data object
	 *
	 * @var object
	 * @since 2.7.4
	 */
	protected $data;

	/**
	 * PHP5 constructor
	 * Add actions and filters
	 *
	 * @since 2.0
	 * @access public
	 */
	public function __construct() {

		if ( is_admin() ) {

			// Add posts columns.
			add_action( 'admin_init', array( $this, 'manage_posts_columns' ) );

			// Add meta box.
			add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

			// Ajax response with thumbnails.
			add_action( 'wp_ajax_sf_attachments', array( $this, 'ajax_post_attachments' ) );

			// Output compose form on ajax call.
			add_action( 'wp_ajax_sf-composeform-data', array( $this, 'ajax_compose_form_data' ) );

			add_filter( 'sf_message', array( $this, 'default_message' ), 10, 3 );

			// Compose message to socialflow via ajax.
			add_action( 'wp_ajax_sf-compose', array( $this, 'ajax_compose' ) );

			// Get single message via ajax request.
			add_action( 'wp_ajax_sf-get-message', array( $this, 'get_compose_log' ) );

			// Get custom attachment media.
			add_action( 'wp_ajax_sf_get_custom_message_image', array( $this, 'ajax_attachment_media' ) );
			add_action( 'wp_ajax_sf_get_custom_message_image_atacments_slide', array( $this, 'ajax_attachment_media_slide' ) );

			// Add media.
			add_filter( 'tiny_mce_before_init', array( $this, 'bind_editor_update' ) );

			// Add new updated message.
			add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );

			// Ouput js settings object if necessary.
			add_action( 'admin_footer', array( $this, 'post_settings' ) );
		}

		// Add save action
		// Meta data is saved and message composition may be processed.
		add_action( 'transition_post_status', array( $this, 'transition_post_status' ), 1, 3 );
	}

	/**
	 * Add socialflow features to admin interface
	 *
	 * @since 2.0
	 * @access public
	 */
	public function manage_posts_columns() {
		global $socialflow;

		// Loop through all active post_types and add custom columns.
		if ( $socialflow->options->get( 'post_type' ) ) {
			foreach ( $socialflow->options->get( 'post_type' ) as $post_type ) {
				add_filter( "manage_{$post_type}_posts_columns", array( $this, 'add_column' ) );
				add_action( "manage_{$post_type}_posts_custom_column", array( $this, 'custom_column' ), 10, 2 );
			}
		}

		// Add send action to posts list table.
		add_action( 'post_row_actions', array( $this, 'row_actions' ), 10, 2 );
		add_action( 'page_row_actions', array( $this, 'row_actions' ), 10, 2 );
		add_action( 'media_row_actions', array( $this, 'row_actions' ), 10, 2 );
	}

	/**
	 * Add socialflow meta box to posts
	 *
	 * @since 2.0
	 * @access public
	 */
	public function add_meta_box() {
		global $socialflow;

		// Don't add meta box if user is not authorized or no post types selected.
		if ( ! ( $socialflow->is_authorized() && $socialflow->options->get( 'post_type' ) ) ) {
			return;
		}

		foreach ( $socialflow->options->get( 'post_type' ) as $type ) {

			// Meta boxes for attachments are too narrow
			// and you can send attachment only from attachments media list.
			if ( 'attachment' === $type ) {
				continue;
			}

			add_meta_box( 'socialflow', __( 'SocialFlow', 'socialflow' ), array( $this, 'meta_box' ), $type, 'advanced', 'high', array( 'post_page' => true ) );
		}
	}

	/**
	 * Display Meta box
	 *
	 * @since 2.0
	 * @since 2.7.4 is updated
	 * @access public
	 *
	 * @param object $post - post current.
	 */
	public function meta_box( $post ) {
		global $socialflow;

		$data = $this->data( $post )->get_ng_data();

		wp_localize_script( 'angular', 'sfPostForm', $data );

		$socialflow->render_view( 'ng-meta-box' );
	}

	/**
	 * Get data for statistics view
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param int $post_id current post id.
	 * @return array of public arguments for stats view
	 */
	public function get_view_stat_data( $post_id ) {
		// Get statuses.
		$logs = $this->data( $post_id )->logs()->get();

		if ( is_array( $logs ) && ! empty( $logs ) ) {

			// Reorder success messages by date.
			krsort( $logs );

			// Get last success publish data.
			$date = array_keys( $logs );
			$date = array_shift( $date );
		} else {
			$date = '';
			$logs = $date;
		}

		return array(
			'form_messages' => $logs,
			'last_sent'     => $date,
			'post_id'       => $post_id,
		);
	}

	/**
	 * Maybe send message to socialflow,
	 * or simply update socialflow meta
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param string $post_status - current post status.
	 * @param string $previous_status - previous post status.
	 * @param object $post - current post object.
	 */
	public function transition_post_status( $post_status, $previous_status, $post ) {
		global $socialflow, $pagenow;

		if ( 'post-new.php' === $pagenow ) {
			return;
		}

		sf_log_post( 'TRANSITION POST STATUS -> START', $post );

		// Doing autosave.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check if we are dealing with revision.
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( $this->is_duplicate( $post->ID ) ) {
			return;
		}

		sf_log_post( 'TRANSITION POST STATUS', $post );

		// Prevent action duplication.
		$this->add_used_post_id( $post->ID );

		$errors            = new WP_Error();
		$socialflow_params = filter_input_array( INPUT_POST );
		// Form is not submited inside cron job for scheduled posts.
		// thats why we are skipping some validations.
		if ( ! ( defined( 'DOING_CRON' ) && DOING_CRON && 'future' === $previous_status && 'publish' === $post_status ) ) {
			$message = __( 'Please, update page and then save your data.', 'socialflow' );
			// Verify nonce.
			if ( ! isset( $socialflow_params['socialflow_nonce'] ) || ! wp_verify_nonce( $socialflow_params['socialflow_nonce'], SF_ABSPATH ) ) {
				$errors->add( 'error_verify_nonce', $message );

				return $socialflow->save_errors( $post->ID, $errors );
			}

			// Prevent multiple form submission.
			if ( get_post_meta( $post->ID, 'socialflow_nonce', true ) !== $socialflow_params['socialflow_nonce'] ) {
				$errors->add( 'error_nonce', $message );

				return $socialflow->save_errors( $post->ID, $errors );
			}

			delete_post_meta( $post->ID, 'socialflow_nonce' );

			sf_log_post( 'TRANSITION POST STATUS - NONCE VERIFIED', $post );

			// Check if user has enough capabilities.
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return;
			}
		}

		// Prevent action duplication
		// Deprecated since v2.7, see duplicate post_ids
		// no need to save post meta inside schedule scenario.
		if ( ! ( 'future' === $previous_status && 'publish' === $post_status ) ) {
			$this->save_meta( $post->ID );
		}

		// Compose to socialflow.
		// Check if send now is checked.
		if ( get_post_meta( $post->ID, 'sf_compose_now', true ) && 'publish' === $post_status ) {

			$result = $this->compose( $post->ID );

			// If message compose fails and post was inteded to be published.
			// set post status as draft.
			if ( is_wp_error( $result ) && 'publish' === $post_status && 'publish' !== $previous_status ) {

				// Set post status to draft.
				$post->post_status = 'draft';
				wp_update_post( $post );

				// Redirect user to approptiate message.
				$location = add_query_arg( 'message', 20, get_edit_post_link( $post->ID, 'url' ) );
				wp_safe_redirect( $location );
				exit;
			}
		}
	}

	/**
	 * Collect socialflow _POST meta and save it
	 *
	 * @since 2.7.4
	 * @access public
	 *
	 * @param int $post_id - current post id.
	 */
	public function save_meta( $post_id ) {
		$socialflow_params = filter_input_array( INPUT_POST );
		$data              = $socialflow_params['socialflow'];
		$media             = $socialflow_params['socialflow']['global']['media'];
		$this->data( $post_id )->save_global_settings( $data );
		$this->data( $post_id )->save_social_messages( $data );
		if ( $media ) {
			$this->data( $post_id )->save_social_compose_media( $media );
			if ( strlen( $media['compose_media_pos_twitter'] ) ) {
				$this->data( $post_id )->save_media_slider_position( (int) $media['compose_media_pos_twitter'], 'twitter' );
				$this->save_curent_url_for_social( $post_id, 'twitter', $media['compose_media_url_twitter'] );
			}
			if ( strlen( $media['compose_media_pos_facebook'] ) || $media['compose_media_pos_facebook'] ) {
				$this->save_curent_url_for_social( $post_id, 'facebook', $media['compose_media_url_facebook'] );
				$this->data( $post_id )->save_media_slider_position( (int) $media['compose_media_pos_facebook'], 'facebook' );
			}
			if ( strlen( $media['compose_media_pos_google_plus'] ) ) {
				$this->save_curent_url_for_social( $post_id, 'google_plus', $media['compose_media_url_google_plus'] );
				$this->data( $post_id )->save_media_slider_position( (int) $media['compose_media_pos_google_plus'], 'google_plus' );
			}
			if ( strlen( $media['compose_media_pos_linkedin'] ) ) {
				$this->save_curent_url_for_social( $post_id, 'linkedin', $media['compose_media_url_linkedin'] );
				$this->data( $post_id )->save_media_slider_position( (int) $media['compose_media_pos_linkedin'], 'linkedin' );
			}
		}
		$this->data( $post_id )->accounts()->save_enabled( $data );
	}

	/**
	 * Save Curent Url For Social
	 *
	 * @since 2.7.4
	 * @access protected
	 *
	 * @param int    $post_id - current post id.
	 * @param string $social_type - current post type.
	 * @param string $current_url - current post url.
	 */
	protected function save_curent_url_for_social( $post_id, $social_type, $current_url ) {
		update_post_meta( $post_id, 'compose_media_url_current_' . $social_type, $current_url );
	}

	/**
	 * Get Compose_data
	 *
	 * @since 2.7.4
	 * @access protected
	 *
	 * @param int $post_id - current post id.
	 * @return array
	 */
	protected function get_compose_data( $post_id ) {
		global $socialflow;

		$post_data         = $this->data( $post_id );
		$socialflow_params = filter_input_array( INPUT_POST );
		$output            = array();

		$post_type = get_post_type( $post_id );
		if ( 'attachment' !== $post_type ) {
			$post_permalink = get_permalink( $post_id );
		} else {
			$post_permalink = null;
		}

		$accounts_data                = $post_data->accounts()->get_data_to_compose();
		$social_messages              = $post_data->get_saved_social_messages();
		$is_compose_media_twitter     = $post_data->get_compose_media_social( 'twitter' );
		$is_compose_media_facebook    = $post_data->get_compose_media_social( 'facebook' );
		$is_compose_media             = $post_data->get_compose_media();
		$is_compose_media_google_plus = $post_data->get_compose_media_social( 'google_plus' );
		$media                        = ( 'attachment' !== $post_type ) ?
			[ $post_data->get_media( 'twitter' ), $post_data->get_media( 'facebook' ), $post_data->get_media( 'google_plus' ) ]
			: ( $post_data->get_attachment_media() );

		$errors = array();

		foreach ( $social_messages as $social_type => $messages ) {
			if ( ! isset( $accounts_data[ $social_type ] ) ) {
				continue;
			}
			$account_data = $accounts_data[ $social_type ];
			$post         = get_post( $post_id );
			$media_info   = $socialflow_params['socialflow']['global']['media'];
			if ( $media_info ) {
				if ( $media_info[ 'compose_media_' . $social_type ] ) {
					if ( $media_info[ 'compose_media_url_current_' . $social_type ] ) {
						update_post_meta( $post_id, 'compose_media_url_current_' . $social_type, $media_info[ 'compose_media_pos_url_current_' . $social_type ] );
					}
				}
			}
			$content_image         = $this->data( $post_id )->get_post_attachments( $post->post_content );
			$social_type_has_error = false;

			foreach ( $messages as $message ) {
				if ( $social_type_has_error ) {
					continue;
				}
				$compose = new SocialFlow_Post_Compose(
					array(
						'service_user_ids'             => $account_data['service_user_ids'], // see api why.
						'content_image'                => $content_image,
						'fields'                       => $message['fields'],
						'settings'                     => $message['settings'],
						'social_type'                  => $social_type,
						'social_native_type'           => $account_data['social_native_type'],
						'post_permalink'               => $post_permalink,
						'post_type'                    => $post_type,
						'is_compose_media'             => $is_compose_media,
						'is_compose_media_twitter'     => $is_compose_media_twitter,
						'is_compose_media_facebook'    => $is_compose_media_facebook,
						'is_compose_media_google_plus' => $is_compose_media_google_plus,
						'media'                        => $media,
					)
				);

				if ( $compose->has_errors() ) {
					$social_type_has_error = true;

					$errors = array_merge( $errors, $compose->get_errors() );

					continue;
				}

				$output[] = $compose;
			}
		}

		if ( ! empty( $errors ) ) {
			return $socialflow->join_errors( $errors, null, ', ' );
		}

		return $output;
	}


	/**
	 * Success compose logs
	 *
	 * @since 2.7.4
	 * @access protected
	 *
	 * @param int $post_id - current post id.
	 * @param int $result - current result.
	 */
	protected function save_success_compose_logs( $post_id, $result ) {
		global $socialflow;

		$logs = $this->data( $post_id )->logs();

		sf_log( 'REQUEST RESULT', $result );

		// $result is array of messages, so we need to create success array to hold account success messages
		foreach ( $result as $messages ) {
			foreach ( $messages as $message ) {
				$logs->add( $message['client_service_id'], $message );
			}
		}

		// Set zero compose now meta.
		update_post_meta( $post_id, 'sf_compose_now', 0 );

		// store all succefully send account ids.
		$logs->save();

		// Clear errors for this post.
		$socialflow->clear_errors( $post_id );
	}

	/**
	 * This function either calls
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param int $post_id current post ID.
	 * @return mixed ( bool | WP_Error ) return true on success or WP_Error object on failure
	 */
	protected function compose( $post_id ) {
		global $socialflow;

		$errors = new WP_Error();

		$compose_data = $this->get_compose_data( $post_id );

		if ( empty( $compose_data ) ) {
			$errors->add( 'empty_accounts', __( '<b>Error:</b> No accounts were selected', 'socialflow' ) );

			return $socialflow->save_errors( $post_id, $errors );
		}

		if ( is_wp_error( $compose_data ) ) {
			return $socialflow->save_errors( $post_id, $compose_data );
		}

		// Send prepared data to accounts object.
		$result = $this->request( $compose_data );

		if ( is_wp_error( $result ) ) {
			return $socialflow->save_errors( $post_id, $result );
		}

		$this->save_success_compose_logs( $post_id, $result );

		return true;
	}

	/**
	 * Send message to accounts
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param array $data array of additional data for each account, array keys are client_account_id's.
	 * @return mixed ( WP_Error | array ) true on success and WP_Error on failure
	 */
	protected function request( $data = array() ) {
		global $socialflow;
		// In fact passed data can't be empty but we will still check in this too.
		if ( empty( $data ) ) {
			return new WP_Error( 'empty_data', __( '<b>Error:</b> Empty send data was passed', 'socialflow' ) );
		}

		$api = $socialflow->get_api();

		// We have valid api object and valid data,
		// but we still need to collect statuses from socialflow.
		$success  = array();
		$errors   = $success;
		$statuses = $errors;
		$queues   = $statuses;

		foreach ( $data as $compose ) {

				$queues = array_merge( $queues, $compose->get_multiple_queues() );
		}

		sf_log( 'COMPOSE QUEUES:', $queues );

		// Loop through data and send message to appropriate account.
		foreach ( $queues as $queue ) {
			$message          = $queue['message'];
			$service_user_ids = explode( ',', $queue['service_user_ids'] );
			$account_types    = $queue['account_types'];
			$publish_option   = $queue['publish_option'];
			$shorten_links    = $queue['shorten_links'];

			$statuses[] = $api->add_multiple(
				$message,
				$service_user_ids,
				$account_types,
				$publish_option,
				$shorten_links,
				$queue
			);
		}

		sf_log( 'COMPOSE RESPONCE STATUSES:', $statuses );

		// Find all errors in statuses.
		foreach ( $statuses as $status ) {
			// Collect error statuses.
			if ( is_wp_error( $status ) ) {
				$errors[] = $status;
			} // Collect success statuses.
			else {
				$success[] = $status;
			}
		}

		if ( ! empty( $errors ) ) {
			return $socialflow->join_errors( $errors );
		}

		return $success;
	}

	/**
	 * Output list of images attached to requested post id
	 *
	 * @since 2.0
	 * @since 2.4.7 - update
	 * @access public
	 */
	public function ajax_post_attachments() {
		$socialflow_params = filter_input_array( INPUT_POST );
		$post_id           = absint( $socialflow_params['ID'] );
		$content           = $socialflow_params['content'];

		wp_send_json( $this->data( $post_id )->get_post_attachments( $content ) );
	}

	/**
	 * Response to ajax request for attachment media
	 * Can be requested as media attach request
	 *
	 * @return void
	 */
	public function ajax_attachment_media_slide() {
		$media             = [];
		$media_correct     = [];
		$socialflow_params = filter_input_array( INPUT_POST );
		if ( ! isset( $socialflow_params['attachment_id'] ) ) {
			die( 0 );
		}

			$social_id                                = $socialflow_params['social_id'];
			$media[ $socialflow_params['social_id'] ] = $this->data( absint( $socialflow_params['attachment_id'] ) )->get_attachment_media_slide();
			$media_curent                             = get_post_meta( absint( $socialflow_params['attach_to_post'] ), 'sf_media_' . $socialflow_params['social_id'], true );
		if ( ! $media_curent ) {
			if ( isset( $media[ $social_id ] ) && isset( $media[ $socialflow_params['social_id'] ][ $socialflow_params['social_id'] ] ) && isset( $media[ $socialflow_params['social_id'] ][ $socialflow_params['social_id'] ][ $socialflow_params['social_id'] ] ) ) {
				$media_correct[ $socialflow_params['social_id'] ] = $media[ $social_id ][ $social_id ];
				$media = $media_correct;
			} elseif ( isset( $media[ $social_id ] ) && ! isset( $media[ $social_id ][ $social_id ] ) ) {
				$media_correct[ $socialflow_params['social_id'] ] = $media;
				$media = $media_correct;
			} elseif ( ! isset( $media[ $social_id ] ) ) {
				$media_correct[ $socialflow_params['social_id'] ]                                    = [];
				$media_correct[ $socialflow_params['social_id'] ][ $socialflow_params['social_id'] ] = $media;
				$media = $media_correct;
			}
			$media_curent = [];
		}

		if ( isset( $media[ $social_id ] ) && isset( $media[ $socialflow_params['social_id'] ][ $socialflow_params['social_id'] ] ) && isset( $media[ $socialflow_params['social_id'] ][ $socialflow_params['social_id'] ][ $socialflow_params['social_id'] ] ) ) {
			$media_correct[ $socialflow_params['social_id'] ] = $media[ $social_id ][ $social_id ];
			$media = $media_correct;
		} elseif ( isset( $media[ $social_id ] ) && ! isset( $media[ $social_id ][ $social_id ] ) ) {
			$media_correct[ $socialflow_params['social_id'] ] = $media;
			$media = $media_correct;
		} elseif ( ! isset( $media[ $social_id ] ) ) {
			$media_correct[ $socialflow_params['social_id'] ]                                    = [];
			$media_correct[ $socialflow_params['social_id'] ][ $socialflow_params['social_id'] ] = $media;
			$media = $media_correct;
		}
		array_push( $media_curent, $media );
		if ( ! $media ) {
			die( 0 );
		}

		if ( isset( $socialflow_params['attach_to_post'] ) ) {
			update_post_meta(
				absint( $socialflow_params['attach_to_post'] ),
				'sf_media_' . $socialflow_params['social_id'],
				$media_curent
			);

			update_post_meta(
				absint( $socialflow_params['attachment_id'] ) . absint( $socialflow_params['attach_to_post'] ),
				'sf_media_atacment_' . $socialflow_params['social_id'],
				absint( $socialflow_params['attachment_id'] )
			);
		}

			wp_send_json( $media_curent );
	}
	/**
	 * Response to ajax request for attachment media
	 * Can be requested as media attach request
	 *
	 * @return void
	 */
	public function ajax_attachment_media() {
		$socialflow_params = filter_input_array( INPUT_POST );
		if ( ! isset( $socialflow_params['feature'] ) ) {
			if ( ! isset( $socialflow_params['attachment_id'] ) ) {
				die( 0 );
			}

			$media[ $socialflow_params['social_id'] ] = $this->data( absint( $socialflow_params['attachment_id'] ) )->get_attachment_media();

			if ( ! $media ) {
				die( 0 );
			}

			if ( isset( $socialflow_params['attach_to_post'] ) ) {
				update_post_meta(
					absint( $socialflow_params['attach_to_post'] ),
					'sf_media_' . $socialflow_params['social_id'],
					$media[ $socialflow_params['social_id'] ]
				);
			}

			wp_send_json( $media );
		} else {

			if ( ! isset( $socialflow_params['attachment_id'] ) ) {
				die( 0 );
			}

			$media = $this->data( absint( $socialflow_params['attachment_id'] ) )->get_attachment_media();

			if ( ! $media ) {
				die( 0 );
			}

			if ( isset( $socialflow_params['attach_to_post'] ) ) {
				update_post_meta( absint( $socialflow_params['attach_to_post'] ), 'sf_media', $media );

			}

			wp_send_json( $media );

		}
	}

	/**
	 * Output compose form as a response to ajax call
	 * This is a callback for ajax action
	 *
	 * @access public
	 * @since 2.2
	 */
	public function ajax_compose_form_data() {
		$socialflow_params = filter_input_array( INPUT_POST );
		$post              = get_post( absint( $socialflow_params['post'] ) );

		$data = $this->data( $post )->get_ng_data();

		if ( ! $data ) {
			wp_die();
		}

		wp_send_json( $data );
	}

	/**
	 * Callback function for ajax compose call
	 *
	 * @since 2.1
	 * @access public
	 */
	public function ajax_compose() {
		$socialflow_params = filter_input_array( INPUT_POST );
		$post_id           = absint( $socialflow_params['post_id'] );

		$error_message = __( '<b>Errors</b> occurred. There was a server error, reload the page.', 'socialflow' );

		// Verify nonce.
		if ( ! isset( $socialflow_params['socialflow_nonce'] ) || ! wp_verify_nonce( $socialflow_params['socialflow_nonce'], SF_ABSPATH ) ) {
			wp_send_json(
				array(
					'status'       => 0,
					'form_message' => '<p class="sf-error">' . $error_message . '</p>',
				)
			);
		}

		// Check if user has enough capabilities.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json(
				array(
					'status'       => 0,
					'form_message' => '<p class="sf-error">' . $error_message . '</p>',
				)
			);
		}

		// Add compose now variable.
		$_POST['socialflow']['global']['compose_now'] = 1;

		// Save socialflow meta data.
		$this->save_meta( $post_id );

		$this->compose( $post_id );

		// Check if there are any success messages and return updated messages block.
		$logs = $this->data( $post_id )->get_ng_stats_data();

		$errors = $this->data( $post_id )->get_ng_errors_data();

		if ( $errors ) {
			$status = 0;

			$form_message = implode( '<br>', $errors );

			if ( ! $form_message ) {
				$form_message = __( '<b>Errors</b> occurred. View messages block for more information.', 'socialflow' );
			}

			$form_message = '<p class="sf-error">' . $form_message . '</p>';
		} else {
			$status       = 1;
			$form_message = '<p class="success">' . __( 'Message was successfully sent. View statistics block for more information.', 'socialflow' ) . '</p>';
		}

		wp_send_json(
			array(
				'stats'        => $logs,
				'status'       => $status,
				'form_message' => $form_message,
			)
		);
	}

	/**
	 * Get single message from SocialFlow api
	 * This is a callback for ajax call
	 *
	 * @since 2.2   - sf_get_message()
	 * @since 2.7.4 - update
	 * @access public
	 * @return void result is outputted as html
	 */
	public function get_compose_log() {
		global $socialflow;
		$socialflow_params = filter_input_array( INPUT_GET );
		// Get arguments.
		$content_item_id = absint( $socialflow_params['id'] );
		$post_id         = absint( $socialflow_params['post_id'] );
		$account_id      = absint( $socialflow_params['account_id'] );
		$time            = esc_attr( $socialflow_params['time'] );

		$api         = $socialflow->get_api();
		$compose_log = $api->view_message( $content_item_id );

		$status = '';

		if ( is_wp_error( $compose_log ) ) {
			// we need only message.
			if ( 'http_request_failed' === $compose_log->get_error_code() ) {
				$status = __( '<b>Error:</b> Server connection timed out. Please, try again.', 'socialflow' );
			} else {
				$status = $compose_log->get_error_message();
			}
		} elseif ( empty( $compose_log ) ) { // if message_id not defined in socialflow server.
			$status = __( '<b>Error:</b> Message data is incorrect.', 'socialflow' );
		} else {
			// update post messages.
			$logs = $this->data( $post_id )->logs();

			$status = $compose_log['status'];

			if ( $compose_log['is_deleted'] ) {
				$status .= ' <i class="deleted">' . __( 'deleted', 'socialflow' ) . '</i>';
			}

			$is_updated = $logs->update_by_content_item_id(
				$time, $account_id, $content_item_id, array(
					'status'       => $status,
					'is_published' => $compose_log['is_published'],
				)
			);

			if ( $is_updated ) {
				$status .= ' &rarr; <span style="display:inline-block">';
				$status .= ( 0 === $compose_log['is_published'] ) ? __( 'In Queue', 'socialflow' ) : __( 'Published', 'socialflow' );
				$status .= '</span>';
			}
		}

		wp_die( wp_kses_post( $status ) );
	}

	/**
	 * After editor loaded we need to notify parent document body about this
	 *
	 * @to-do add support for tinyMCE editor < 4
	 * @param  array $config Mce editor config.
	 * @return array         Mce editor config
	 */
	public function bind_editor_update( $config ) {

		global $socialflow;
		$screen = get_current_screen();

		if ( ! ( is_admin() && 'edit' === $screen->parent_base && in_array( $screen->post_type, $socialflow->options->get( 'post_type' ), true ) ) ) {
			return $config;
		}



		$config['setup'] = "function (editor) {
			editor.on('init', function(event) {
				parent.window.jQuery('body').trigger('wp-tinymce-loaded');
				
			});

			editor.on( 'change keyup', function(event) {
				parent.window.jQuery('body').trigger('wp-tinymce-change',[editor.getContent()]);
			});
		}";

		return $config;
	}

	/**
	 * Default socialflow message
	 *
	 * @param  string $message SocialFlow message.
	 * @param  string $type    Default type.
	 * @param  object $post    Post object.
	 * @return string          Default message
	 */
	public function default_message( $message, $type, $post ) {
		if ( ! empty( $message ) ) {
			return $message;
		}

		if ( 'attachment' === $post->post_type ) {
			if ( ! empty( $post->post_content ) ) {
				$message = $post->post_content;
			} elseif ( ! empty( $post->post_excerpt ) ) {
				$message = $post->post_excerpt;
			} else {
				$message = $post->post_title;
			}
		}

		return $message;
	}

	/**
	 * Add SocialFlow custom column heading
	 *
	 * Callback for "manage_{$post_type}_posts_columns" hook
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param array $columns of list columns.
	 * @return array of filtered list columns.
	 */
	public function add_column( $columns ) {
		$columns['socialflow'] = __( 'SocialFlow', 'socialflow' );
		return $columns;
	}

	/**
	 * Add SocialFlow custom column content
	 *
	 * Callback for "manage_{$post_type}_posts_custom_column" hook
	 *
	 * @since 2.0
	 * @access public
	 *
	 * @param string $column column key.
	 * @param int    $post_id current post id.
	 */
	public function custom_column( $column, $post_id ) {
		global $socialflow;

		if ( 'socialflow' === $column ) {
			// if sf_compose == 0 than message was already composed.
			if ( $this->data( $post_id )->logs()->get() ) {
				echo wp_kses_post( '<img class="js-sf-extended-stat-toggler" src="' . plugins_url( 'assets/images/success.gif', SF_FILE ) . '" width="12" height="12" title="' . __( 'Successfully sent', 'socialflow' ) . '" alt="' . __( 'Successfully sent', 'socialflow' ) . '" />' );

				// Render compact stats table.
				$socialflow->render_view( 'stats/compact', $this->get_view_stat_data( $post_id ) );
			} elseif ( 'publish' !== get_post_status( $post_id ) && ( get_post_meta( $post_id, 'sf_message_facebook', true ) || get_post_meta( $post_id, 'sf_message_twitter', true ) ) ) {
				echo wp_kses_post( '<img src="' . plugins_url( 'assets/images/notice.gif', SF_FILE ) . '" width="12" height="12" title="' . __( 'SocialFlow data filled', 'socialflow' ) . '" alt="' . __( 'SocialFlow data filled', 'socialflow' ) . '" />' );
			} else {
				echo wp_kses_post( '<img src="' . plugins_url( 'assets/images/default.gif', SF_FILE ) . '" width="12" height="12" />' );
			}
		}
	}

	/**
	 * Add action link to for composing message right from posts list table
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param array  $actions of post actions.
	 * @param object $post current post.
	 * @return array filtered actions
	 */
	public function row_actions( $actions, $post ) {
		// Post must be published and post type enabled in plugin options.
		if ( ! $this->is_post_enabled( $post ) ) {
			return $actions;
		}

		$title = esc_attr__( 'Send to SocialFlow', 'socialflow' );

		$actions['sf-compose-action'] = '<a class="thickbox sf-open-popup" href="#TB_inline?width=770&inlineId=sf-form-popup" data-post-id="' . $post->ID . '" title="' . $title . '">' . $title . '</a>';

		return $actions;
	}

	/**
	 * Check if socialflow is enabled for current post
	 *
	 * @param  Object $post WP_Post object.
	 * @return boolean       Enabled status
	 */
	public function is_post_enabled( $post ) {
		global $socialflow;

		// Post type must be enabled in plugin options.
		if ( ! in_array( $post->post_type, $socialflow->options->get( 'post_type', array() ), true ) ) {
			return false;
		}

		// Include only image attachments.
		if ( 'attachment' === $post->post_type ) {
			return strpos( $post->post_mime_type, 'image' ) !== false;
		}

		// All other post types must be published.
		return 'publish' === $post->post_status;
	}

	/**
	 * Add new updated messages
	 *
	 * @param  array $messages .
	 * @return array $messages
	 */
	public function post_updated_messages( $messages ) {
		global $socialflow;

		// Add message only for enabled post types.
		if ( $socialflow->options->get( 'post_type' ) ) {
			foreach ( $socialflow->options->get( 'post_type' ) as $type ) {
				$mess                  = '<b>Notice:</b> ' . $type . ' was not published, because some errors occurred when sending messages to SocialFlow. <a href="#socialflow">View More.</a>';
				$messages[ $type ][20] = $mess;
			}
		}

		return $messages;
	}

	/**
	 * Pass post settings js object if necessary
	 *
	 * @since  2.1
	 */
	public function post_settings() {
		global $socialflow;

		$this->js_settings['postType']            = $socialflow->options->get( 'post_type' );
		$this->js_settings['disableAutoComplete'] = $socialflow->options->get( 'disable_autocomplete', 0 );
		wp_localize_script( 'socialflow-admin', 'optionsSF', $this->js_settings );

		$socialflow->render_view( 'ng-posts-list-popup/popup' );
	}

	/**
	 * Check duplicates in transition post status
	 *
	 * @param  int $post_id .
	 * @return boolean
	 * @since 2.7
	 */
	protected function is_duplicate( $post_id ) {
		return in_array( $post_id, $this->used_post_ids, true );
	}

	/**
	 * Save post_id with transited post status
	 *
	 * @param  int $post_id .
	 * @return boolean
	 * @since 2.7
	 */
	protected function add_used_post_id( $post_id ) {
		if ( $this->is_duplicate( $post_id ) ) {
			return;
		}

		$this->used_post_ids[] = $post_id;
	}

	/**
	 * Post data
	 *
	 * @param  obj $post .
	 * @return obj
	 * @since 2.7.4
	 */
	protected function data( $post ) {
		if ( ! $this->data ) {
			$this->data = new SocialFlow_Post_Form_Data( $post );
		} else {
			$this->data = $this->data->mb_reinit( $post );
		}

		return $this->data;
	}
}
