<?php
/**
 * Compose Form Log.
 *
 * @package class-socialflow-post-accounts
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Generate all data to angular handler
 *
 * @since 2.7.4
 */
class SocialFlow_Post_Form_Data {
	/**
	 * Current post.
	 *
	 * @var object
	 */
	protected $post;

	/**
	 * Logs.
	 *
	 * @var object
	 */
	protected $logs;
	/**
	 * Fields form .
	 *
	 * @var object
	 */
	public static $fields = array(
		'twitter'     => array(
			'message'         => 'textarea',
			'message_postfix' => 'text',
		),
		'facebook'    => array(
			'message'               => 'textarea',
			'title'                 => 'text',
			'description'           => 'textarea',
			'image'                 => 'text',
			'is_custom_image'       => 'int',
			'custom_image'          => 'text',
			'custom_image_filename' => 'text',
		),
		'linkedin'    => array(
			'message'               => 'textarea',
			'title'                 => 'text',
			'description'           => 'textarea',
			'image'                 => 'text',
			'is_custom_image'       => 'int',
			'custom_image'          => 'text',
			'custom_image_filename' => 'text',
		),
		'google_plus' => array(
			'message'               => 'textarea',
			'image'                 => 'text',
			'is_custom_image'       => 'int',
			'custom_image'          => 'text',
			'custom_image_filename' => 'text',
			'media'                 => 'text',
		),
		'pinterest'   => array( 'message' => 'text' ),
	);

	/**
	 * Init post
	 *
	 * @param object|int $post .
	 */
	public function __construct( $post ) {
		$this->post = is_object( $post ) ? $post : get_post( $post );
	}

	/**
	 * May be reinit object
	 *
	 * @param object|int $post .
	 * @return object
	 */
	public function mb_reinit( $post ) {
		$post_id = is_object( $post ) ? $post->ID : $post;
		if ( $this->post->ID === $post_id ) {
			return $this;
		}

		return new self( $post );
	}

	/**
	 * Get templates
	 *
	 * @return array
	 */
	public static function get_templates() {
		return array(
			'formInPopup'          => self::get_template( 'ng-posts-list-popup/form' ),
			'composeForm'          => self::get_template( 'ng-form/compose-form' ),
			'messageComposeMedia'  => self::get_template( 'ng-form/compose-media' ),
			'globalSettings'       => self::get_template( 'ng-form/global-settings' ),
			'errors'               => self::get_template( 'ng-form/errors' ),
			'statsFull'            => self::get_template( 'ng-form/stats/full' ),
			'accounts'             => self::get_template( 'ng-form/accounts/accounts' ),
			'socialTabs'           => self::get_template( 'ng-form/social-tabs/social-tabs' ),
			'socialTabsList'       => self::get_template( 'ng-form/social-tabs/social-tabs-list' ),
			'messagesList'         => self::get_template( 'ng-form/social-tabs/messages-list/messages-list' ),
			'message'              => self::get_template( 'ng-form/social-tabs/messages-list/message/message' ),
			'inputShowCheckBox'    => self::get_template( 'ng-form/social-tabs/messages-list/message/input-show-check-box' ),
			'messageTwitter'       => self::get_template( 'ng-form/social-tabs/messages-list/message/message-twitter' ),
			'messagePinterest'     => self::get_template( 'ng-form/social-tabs/messages-list/message/message-pinterest' ),
			'messageAttachments'   => self::get_template( 'ng-form/social-tabs/messages-list/message/attachments/attachments' ),
			'advancedSettingsList' => self::get_template( 'ng-form/social-tabs/messages-list/settings/advanced-settings-list' ),
			'advancedSetting'      => self::get_template( 'ng-form/social-tabs/messages-list/settings/advanced-setting' ),
		);
	}

	/**
	 * Get ng data
	 *
	 * @return array
	 */
	public function get_ng_data() {
		$post              = $this->post;
		$socialflow_params = filter_input_array( INPUT_SERVER );
		return array(
			'post'                                        => array(
				'ID'        => $post->ID,
				'type'      => $post->post_type,
				'status'    => $post->post_status,
				'title'     => $post->post_title,
				'content'   => trim( str_replace( array( "\r", "\n" ), '', addslashes( wp_strip_all_tags( $post->post_content ) ) ) ),
				'thumbnail' => has_post_thumbnail( $post->ID ) ? get_the_post_thumbnail_url( $post, 'full' ) : '',
			),
			'twitter'                                     => get_post_meta( $this->post->ID, 'sf_media_twitter', true ),
			'facebook'                                    => get_post_meta( $this->post->ID, 'sf_media_facebook', true ),
			'google_plus'                                 => get_post_meta( $this->post->ID, 'sf_media_google_plus', true ),
			'globalSettings'                              => $this->get_ng_global_settings_data(),
			'accounts'                                    => $this->accounts()->get_ng_data(),
			'socialData'                                  => $this->get_ng_social_data(),
			'messageAttachments'                          => $this->get_ng_message_attachments(),
			'atachmentfeatureAndEditor'                   => $this->get_ng_message_attachments(),
			'message_attachments_for_socials_facebook'    => $this->get_ng_message_attachments_for_socials( 'facebook' ),
			'message_attachments_for_socials_google_plus' => $this->get_ng_message_attachments_for_socials( 'google_plus' ),
			'message_attachments_for_socials_twitter'     => $this->get_ng_message_attachments_for_socials( 'twitter' ),
			'current_url_facebook'                        => $this->get_ng_current_url_for_social( 'facebook' ),
			'current_url_twitter'                         => $this->get_ng_current_url_for_social( 'twitter' ),
			'current_url_google_plus'                     => $this->get_ng_current_url_for_social( 'google_plus' ),
			'current_url_linkedin'                        => $this->get_ng_current_url_for_social( 'linkedin' ),
			'compose_media_pos_facebook'                  => $this->get_ng_pos_for_social( 'facebook' ),
			'compose_media_pos_linkedin'                  => $this->get_ng_pos_for_social( 'linkedin' ),
			'content_url_twitter'                         => $this->get_custom_image( 'twitter' ),
			'content_google_plus'                         => $this->get_custom_image( 'google_plus' ),
			'content_facebook'                            => $this->get_custom_image( 'facebook' ),
			'content_linkedin'                            => $this->get_custom_image( 'linkedin' ),
			'compose_media_pos_google_plus'               => $this->get_ng_pos_for_social( 'google_plus' ),
			'compose_media_pos_twitter'                   => $this->get_ng_pos_for_social( 'twitter' ),
			'messageComposeMedia'                         => $this->get_ng_message_compose_media(),
			'advancedSetting'                             => $this->get_ng_advanced_setting(),
			'stats'                                       => $this->get_ng_stats_data(),
			'errors'                                      => $this->get_ng_errors_data(),
			'facebook_domain_verified'                    => $this->get_domain_verified_facebook(),
			'facebook_change_meta'                        => 1,
			'domain_current'                              => $socialflow_params['HTTP_HOST'],
		);
	}
	/**
	 * Get template for render
	 *
	 * @param string $path .
	 * @return string
	 */
	protected static function get_template( $path ) {

		global $socialflow;
		return $socialflow->get_view( $path )->get_render();
	}

	/**
	 * Get error form form
	 *
	 * @return array
	 */
	public function get_ng_errors_data() {

		global $socialflow;
		$errors = $socialflow->get_errors( $this->post->ID );
		if ( empty( $errors ) ) {
			return array();
		}

		return array_map( 'wp_kses_post', $socialflow->filter_errors( $errors )->get_error_messages() );
	}

	/**
	 * Fet stats data,
	 * used in ajax_compose()
	 */
	public function get_ng_stats_data() {

		$logs      = $this->get_formated_post_success_logs();
		$last_sent = '';
		if ( ! empty( $logs ) ) {
			// Reorder success messages by date.
			krsort( $logs );
			// Get last success publish data.
			$last_sent = array_keys( $logs );
			$last_sent = array_shift( $last_sent );
		}

		return array(
			'logs'      => $logs,
			'last_sent' => mysql2date( 'd F, Y h:i a', $last_sent ),
		);
	}

	/**
	 * Get logs for success formatted,
	 */
	protected function get_formated_post_success_logs() {

		global $socialflow;
		$logs = $this->logs()->get();
		if ( empty( $logs ) ) {
			return array();
		}
		$accounts_names = $socialflow->accounts->get_all_accounts_names();
		foreach ( $logs as $time => $accounts ) {
			foreach ( $accounts as $account_id => $messages ) {
				foreach ( $messages as $key => $message ) {
					$queue_status = '';
					if ( isset( $message['is_published'] ) ) {
						if ( 0 === (int) $message['is_published'] ) {
							$queue_status = esc_attr__( 'In Queue', 'socialflow' );
						} else {
							$queue_status = esc_attr__( 'Published', 'socialflow' );

						}
					}

					if ( $queue_status ) {
						$messages[ $key ]['status'] .= ' &rarr; ' . wp_kses_post( $queue_status );
					}
				}

				$accounts[ $account_id ] = array(
					'name'     => $accounts_names[ $account_id ],
					'messages' => $messages,
				);
			}

			$logs[ $time ] = array(
				'date'     => mysql2date( 'd F, Y h:i:s', $time ),
				'accounts' => $accounts,
			);
		}

		return $logs;
	}

	/**
	 * Get for form media,
	 *
	 * @return array
	 */
	protected function get_ng_message_compose_media() {

		return array( 'media' => $this->get_attachment_media() );
	}
	/**
	 * Get  media for slide,
	 *
	 * @return array
	 */
	protected function get_ng_message_attachments() {

		return $this->get_post_attachments( $this->post->post_content );
	}
	/**
	 * Get  url for social for current selected image,
	 *
	 * @param  string $social_type .
	 * @return array
	 */
	protected function get_ng_current_url_for_social( $social_type ) {

		return get_post_meta( $this->post->ID, 'compose_media_url_current_' . $social_type, true );
	}
	/**
	 * Get  pos for social for current selected image,
	 *
	 * @param  string $social_type .
	 * @return array
	 */
	protected function get_ng_pos_for_social( $social_type ) {
		return get_post_meta( $this->post->ID, 'compose_media_pos_' . $social_type, true );
	}
	/**
	 * Get  attachments for all socials,
	 *
	 * @param  string $social_type .
	 * @return array
	 */
	protected function get_ng_message_attachments_for_socials( $social_type ) {

		$urlarr = [];
		$data   = get_post_meta( $this->post->ID, 'sf_media_' . $social_type, true );
		if ( ! empty( $data ) ) {
			foreach ( $data as  $item ) {
				foreach ( $item as $val ) {
					if ( isset( $val[ $social_type ] ) ) {
						$urlarr[] = $val[ $social_type ]['medium_thumbnail_url'];
					}
				}
			}
		}
		$attach_to_content = $this->get_post_attachments( $this->post->post_content );
		if ( $attach_to_content ) {
			foreach ( $attach_to_content as $img ) {
				$urlarr[] = $img;
			}
		}
		return $urlarr;
	}

	/**
	 * Retrieve images from post content
	 *
	 * @since 2.7.4
	 * @access public
	 *
	 * @param string $post_content - current post content.
	 * @return array
	 */
	public function get_post_attachments( $post_content = '' ) {
		$images       = array();
		$thumbnail_id = get_post_thumbnail_id( $this->post->ID );
		if ( $thumbnail_id ) {
			$thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, 'full' );
			$images[]      = $thumbnail_src[0];
		}

		if ( empty( $post_content ) ) {
			return $images;
		}

		$post_content = stripslashes( $post_content );
		$regex        = '/<\s*img [^\>]*src\s*=\s*(["\'])(.*?)\1/im';
		if ( ! preg_match_all( $regex, $post_content, $matches ) ) {
			return $images;
		}

		foreach ( $matches[2] as $image ) {
			if ( in_array( $image, $images, true ) ) {
				continue;
			}
			$images[] = esc_url( $image );
		}

		return $images;
	}
	/**
	 * Get global data for message form
	 *
	 * @since 2.7.4
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_ng_global_settings_data() {

		global $socialflow, $pagenow;
		$post    = $this->post;
		$wpnonce = wp_create_nonce( SF_ABSPATH );
		update_post_meta( $post->ID, 'socialflow_nonce', $wpnonce );
		if ( 'auto-draft' === $post->post_status ) {
			$compose_now = $socialflow->options->get( 'compose_now' );
		} else {
			$compose_now = get_post_meta( $post->ID, 'sf_compose_now', true );
		}
		if ( 'attachment' === $post->post_type ) {
			$compose_media_twitter     = 1;
			$compose_media_facebook    = 1;
			$compose_media_google_plus = 1;
		} else {
			$compose_media_twitter     = $this->get_compose_media_social( 'twitter' );
			$compose_media_facebook    = $this->get_compose_media_social( 'facebook' );
			$compose_media_google_plus = $this->get_compose_media_social( 'google_plus' );
		}

		if ( 'post-new.php' === $pagenow ) {
			$disable_autcomplete = $socialflow->options->get( 'global_disable_autocomplete', 0 );
		} else {
			$disable_autcomplete = get_post_meta( $post->ID, 'sf_disable_autcomplete', true );
		}

		$compose_now               = absint( $compose_now );
		$compose_media_twitter     = absint( $compose_media_twitter );
		$compose_media_facebook    = absint( $compose_media_facebook );
		$compose_media_facebook    = absint( $compose_media_facebook );
		$compose_media_google_plus = absint( $compose_media_google_plus );
		return compact( 'compose_now', 'compose_media_twitter', 'compose_media_facebook', 'compose_media_google_plus', 'disable_autcomplete', 'wpnonce' );
	}
	/**
	 * Get compose media
	 *
	 * @since 2.7.4
	 * @access protected
	 *
	 * @return string
	 */
	public function get_compose_media() {

		return absint( get_post_meta( $this->post->ID, 'sf_compose_media', true ) );
	}

	/**
	 * Get image custom.
	 *
	 * @param  string $type .
	 * @return mixed
	 */
	public function get_custom_image( $type ) {
		return get_post_meta( $this->post->ID, 'sf_compose_media_url_' . $type, true );
	}
	/**
	 * Get compose media social
	 *
	 * @since 2.7.4
	 * @access protected
	 *
	 * @param string $type .
	 * @return string
	 */
	public function get_compose_media_social( $type ) {

		return absint( get_post_meta( $this->post->ID, 'sf_compose_media_' . $type, true ) );
	}
	/**
	 * Get advanced setting
	 *
	 * @since 2.7.4
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_ng_advanced_setting() {
		$must_send = empty(get_option( 'socialflow' )['must_send']) ? false : get_option( 'socialflow' )['must_send'];
		return array(
			'const'    => array(
				'publish_option'  => SocialFlow_Admin_Settings_General::get_publish_options(),
				'optimize_period' => SocialFlow_Admin_Settings_General::get_optimize_periods(),
				'data_tz_offset'  => get_option( 'gmt_offset' ) * HOUR_IN_SECONDS,
				'duplicated'      => array( 'optimize', 'schedule' ),
			),
			'defaults' => array(
				'publish_option'  => get_option( 'socialflow' )['publish_option'] ?: 'optimize',
				'optimize_period' => get_option( 'socialflow' )['optimize_period'] ?: '1 hour',
				'must_send'       => $must_send,
			),
		);
	}
	/**
	 * Validate message social
	 *
	 * @since 2.7.4
	 * @access protected
	 *
	 * @param array  $messages .
	 * @param string $social_type .
	 * @return array
	 */
	protected function validate_social_messages( $messages, $social_type ) {

		$default = array(
			'fields'   => array(),
			'settings' => array( array() ),
		);
		if ( empty( $messages ) ) {
			$messages[] = $default;
			return $messages;
		}

		foreach ( $messages as $key => $message ) {
			$settings         = isset( $message['settings'] ) ? $message['settings'] : $default['settings'];
			$fields           = isset( $message['fields'] ) ? $message['fields'] : $default['fields'];
			$fields           = $this->validate_social_message_fields( $social_type, $fields );
			$messages[ $key ] = array(
				'type'     => $social_type,
				'fields'   => $fields,
				'settings' => $settings,
			);
		}

		return $messages;
	}

	/**
	 * Retrieve associative array of advanced user options
	 *
	 * @since 2.0
	 * @since 2.7.4 - update
	 * @access public
	 *
	 * @param array $account client service account.
	 * @param array $data passed advanced account data.
	 * @return array of filtered data
	 */
	protected function get_account_advanced_settings( $account, $data = array() ) {

		global $socialflow;
		$keys    = array( 'publish_option', 'must_send', 'optimize_period', 'optimize_start_date', 'optimize_end_date', 'scheduled_date' );
		$account = is_int( $account ) ? $socialflow->filter_accounts( array( 'client_service_id' => $account ) ) : $account;
		$data    = isset( $data[ $account['client_service_id'] ] ) ? $data[ $account['client_service_id'] ] : array();
		// formatting data array
		// since v 2.7.
		if ( ! isset( $data[0] ) ) {
			$data = array( $data );
		}

		foreach ( $data as $i => $item ) {
			$output = array();
			foreach ( $keys as $key ) {
				$value = isset( $item[ $key ] ) ? $item[ $key ] : $socialflow->options->get( $key );
				if ( 'must_send' === $key ) {
					$value = absint( $value );
				} elseif ( 'scheduled_date' === $key && empty( $value ) ) {
					$value = '';
				}

				$output[ $key ] = $value;
			}

			$data[ $i ] = array_map( 'sanitize_text_field', $output );
		}

		return $data;
	}

	/**
	 * Validate social single message fields.
	 * Adds missing message fields
	 *
	 * @since 3.0
	 *
	 * @param  (string) $social_type .
	 * @param  (array)  $fields .
	 * @return (array)  validated fields
	 */
	protected function validate_social_message_fields( $social_type, $fields ) {

		$defaults = self::$fields[ $social_type ];
		// check missing fields.
		if ( count( $fields ) === count( $defaults ) ) {
			return $fields;
		}

		// add missing fields.
		foreach ( $defaults as $name => $type ) {
			if ( isset( $fields[ $name ] ) ) {
				continue;
			}

			$fields[ $name ] = '';
		}

		return $fields;
	}

	/**
	 * Get structured social messages data
	 *
	 * @since 3.0
	 *
	 * @return [array]
	 */
	protected function get_ng_social_data() {
		$social_messages = $this->get_saved_social_messages();
		$output          = array();
		foreach ( SocialFlow_Accounts::$type_order as $social_type ) {
			if ( 'linkedin' === $social_type && 'attachemnt' === $this->post->post_type ) {
				continue;
			}

			$messages = isset( $social_messages[ $social_type ] ) ? $social_messages[ $social_type ] : array();
			$output[] = array(
				'type'       => $social_type,
				'messages'   => $this->validate_social_messages( $messages, $social_type ),
				'field_meta' => array(
					'name_prefix' => "socialflow[socials][{$social_type}]",
					'id_prefix'   => "sf_socials_{$social_type}",
				),
			);
		}
		return $output;
	}



	/**
	 * Data structure
	 * array =>
	 *      [type] => array
	 *          [fields] => array
	 *              [key] => [value]
	 *                  ...
	 *          [settings] => array
	 *               ...
	 *
	 * @return [array]
	 */
	public function get_saved_social_messages() {

		$meta_key = 'sf_social_messages';
		$data     = get_post_meta( $this->post->ID, $meta_key, true );
		if ( $data ) {
			return $data;
		}
		return $data;
	}

	/**
	 * Save media compose
	 *
	 * @param array $data .
	 */
	public function save_social_compose_media( $data ) {

		foreach ( $data as $key => $item ) {
			update_post_meta( $this->post->ID, 'sf_' . $key, $item );
		}

	}
	/**
	 * Md converter
	 *
	 * @return array
	 */
	protected function md_conver_old_accounts_data() {

		global $socialflow;
		$output   = array();
		$meta_key = 'sf_old_data_converted';
		// is old data converted or is new version for new post.
		if ( get_post_meta( $this->post->ID, $meta_key, true ) ) {
			return $output;
		}

		// array of enabled account ids.
		$send_enabled = get_post_meta( $this->post->ID, 'sf_send_accounts', true );
		if ( ! $send_enabled ) {
			$send_enabled = $socialflow->options->get( 'send', array() );
		}

		foreach ( $this->get_accounts() as $account_id => $account ) {
			$account_type = $account->get_type();
			$fields       = array();
			foreach ( self::$fields[ $account_type ] as $field => $type ) {
				$value = $this->get_value( $field, $account_type );
				if ( 'textarea' === $type ) {
					$value = esc_html( $value );
				} else {
					$value = esc_attr( $value );

				}

				$fields[ $field ] = $value ? $value : '';
			}
			$send     = absint( in_array( $account_id, $send_enabled, true ) );
			$advanced = get_post_meta( $this->post->ID, 'sf_advanced', true );
			delete_post_meta( $this->post->ID, 'sf_advanced' );
			$settings                                  = $this->get_account_advanced_settings( $account, $advanced );
			$output[ "{$account_id}:{$account_type}" ] = array(
				'meta'     => compact( 'send' ),
				'messages' => array( compact( 'fields', 'settings' ) ),
			);
		}

		update_post_meta( $this->post->ID, $meta_key, 1 );
		return $output;
	}
	/**
	 * Get media for slide
	 *
	 * @return array|null
	 */
	public function get_attachment_media_slide() {

		global $socialflow;
		$socialflow_params = filter_input_array( INPUT_POST );
		$social_id         = isset( $socialflow_params['social_id'] ) ? $socialflow_params['social_id'] : 0;
		$media             = [];
		if ( $social_id ) {
			$media[ $social_id ] = $this->get_media( $social_id );
		} else {
			$media ['twitter']    = $this->get_media( 'twitter' );
			$media['facebook']    = $this->get_media( 'facebook' );
			$media['google_plus'] = $this->get_media( 'google_plus' );
		}

		foreach ( $media as $items ) {
			foreach ( $items as $item ) {
				if ( $item && ! is_wp_error( $item ) ) {
					return $media;
				}
			}
		}
		$image = wp_get_attachment_image_src( $this->post->ID, 'full' );
		if ( ! $image ) {
			return;
		}

		$media = $socialflow->get_api()->add_media( $socialflow->get_output_image_url( $image[0] ) );
		// test localhost data.
		if ( $socialflow->is_localhost() ) {
			return array(
				'medium_thumbnail_url' => 'https://s3.amazonaws.com/socialflow-image-upload-thumbs/med_385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
				'filename'             => '385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
				'fullsize_url'         => 'https://s3.amazonaws.com/prod-cust-photo-posts-jfaikqealaka/385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
				'thumbnail_url'        => 'https://s3.amazonaws.com/socialflow-image-upload-thumbs/385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
				'size'                 => '370299',
			);
		}
		if ( is_wp_error( $media ) ) {
			return;
		}

		update_post_meta( $this->post->ID, 'sf_media_' . $socialflow_params['social_id'], $media );
		// media already presents so we can call recursievly.
		return $media;
	}
	/**
	 * Get attachment media
	 *
	 * @return array|null
	 */
	public function get_attachment_media() {

		global $socialflow;
		$socialflow_params = filter_input_array( INPUT_POST );
		$media             = [];
		$social_id         = isset( $socialflow_params['social_id'] ) ? $socialflow_params['social_id'] : 0;
		if ( ! isset( $socialflow_params['feature'] ) ) {
			if ( $social_id ) {
				$media[ $social_id ] = $this->get_media( $social_id );
			} else {
				$media ['twitter']    = $this->get_media( 'twitter' );
				$media['facebook']    = $this->get_media( 'facebook' );
				$media['google_plus'] = $this->get_media( 'google_plus' );
			}

			foreach ( $media as $item ) {
				if ( $item && ! is_wp_error( $item ) ) {
					return $media;
				}
			}
				$image = wp_get_attachment_image_src( $this->post->ID, 'full' );
			if ( ! $image ) {
				return;
			}
			$media = $socialflow->get_api()->add_media( $socialflow->get_output_image_url( $image[0] ) );
			if ( $media ) {
				update_post_meta( $this->post->ID, 'sf_media_twitter', $media );
				update_post_meta( $this->post->ID, 'sf_media_facebook', $media );
				update_post_meta( $this->post->ID, 'sf_media_google_plus', $media );
			}
			// test localhost data.
			if ( $socialflow->is_localhost() ) {
				return array(
					'medium_thumbnail_url' => 'https://s3.amazonaws.com/socialflow-image-upload-thumbs/med_385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
					'filename'             => '385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
					'fullsize_url'         => 'https://s3.amazonaws.com/prod-cust-photo-posts-jfaikqealaka/385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
					'thumbnail_url'        => 'https://s3.amazonaws.com/socialflow-image-upload-thumbs/385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
					'size'                 => '370299',
				);
			}
			if ( is_wp_error( $media ) ) {
				return;
			}
			update_post_meta( $this->post->ID, 'sf_media_' . $socialflow_params['social_id'], $media );
		} else {
			$media = $this->get_media_feature();
			if ( $media && ! is_wp_error( $media ) ) {
				return $media;
			}

			$image = wp_get_attachment_image_src( $this->post->ID, 'full' );
			if ( ! $image ) {
				return;
			}
			$media = $socialflow->get_api()->add_media( $socialflow->get_output_image_url( $image[0] ) );
			// test localhost data.
			if ( $socialflow->is_localhost() ) {
				return array(
					'medium_thumbnail_url' => 'https://s3.amazonaws.com/socialflow-image-upload-thumbs/med_385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
					'filename'             => '385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
					'fullsize_url'         => 'https://s3.amazonaws.com/prod-cust-photo-posts-jfaikqealaka/385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
					'thumbnail_url'        => 'https://s3.amazonaws.com/socialflow-image-upload-thumbs/385-b4f71e5a62ff898dfcede2d026f6da1e.jpg',
					'size'                 => '370299',
				);
			}

			if ( is_wp_error( $media ) ) {
				return;
			}

			update_post_meta( $this->post->ID, 'sf_media', $media );
			// media already presents so we can call recursievly.
			return $media;
		}
		// media already presents so we can call recursievly.
		return $media;
	}
	/**
	 * Get  media
	 *
	 * @param string $type .
	 * @return array
	 */
	public function get_media( $type ) {

		return get_post_meta( $this->post->ID, 'sf_media_' . $type, true );
	}
	/**
	 * Get media feature
	 *
	 * @return array
	 */
	public function get_media_feature() {

		return get_post_meta( $this->post->ID, 'sf_media', true );
	}
	/**
	 * Save message
	 *
	 * @param array $data .
	 */
	public function save_social_messages( $data ) {
		update_post_meta( $this->post->ID, 'sf_social_messages', $data['socials'] );
	}

	/**
	 * Save slider position
	 *
	 * @param int   $pos .
	 * @param array $social_type .
	 */
	public function save_media_slider_position( $pos, $social_type ) {

		update_post_meta( $this->post->ID, 'compose_media_pos_' . $social_type, $pos );
	}


	/**
	 * Save global settings
	 *
	 * @param array $data .
	 */
	public function save_global_settings( $data ) {

		$settings = array( 'compose_now', 'compose_media', 'disable_autcomplete' );
		$global   = isset( $data['global'] ) ? $data['global'] : array();
		foreach ( $settings as $setting ) {
			$value = absint( isset( $global[ $setting ] ) );
			update_post_meta( $this->post->ID, "sf_{$setting}", $value );
		}
	}

	/**
	 * Get logs
	 *
	 * @return  SocialFlow_Compose_Form_Logs
	 */
	public function logs() {

		if ( empty( $this->logs ) ) {
			$this->logs = new SocialFlow_Compose_Form_Logs( $this->post->ID );
		}

		return $this->logs;
	}
	/**
	 * Get domains verified for facebook
	 *
	 * @return  array
	 */
	public function get_domain_verified_facebook() {

		global $socialflow;
		$api = $socialflow->get_api();
		return $api->get_facebook_domain();
	}
	/**
	 * Get accounts
	 *
	 * @return  array
	 */
	public function accounts() {

		if ( empty( $this->accounts ) ) {
			$this->accounts = new SocialFlow_Post_Accounts( $this->post->ID, $this->post->post_type );
		}

		return $this->accounts;
	}
}
