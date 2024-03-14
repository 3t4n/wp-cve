<?php
/**
 * Compose Form Log.
 *
 * @package class-socialflow-post-compose
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit( 'No direct script access allowed' );
}

/**
 * Compose input data to api standart
 *
 * @since 2.7.4
 */
class SocialFlow_Post_Compose {
	/**
	 * Compose.
	 *
	 * @var array
	 */
	protected $compose = array();

	/**
	 * Vars for config message.
	 *
	 * @var array
	 */
	protected $vars = array(
		'service_user_ids'             => array(),
		'fields'                       => array(),
		'settings'                     => array(),
		'social_type'                  => '',
		'content_image'                => array(),
		'social_native_type'           => '',
		'post_permalink'               => '',
		'post_type'                    => '',
		'is_compose_media_twitter'     => false,
		'is_compose_media'             => false,
		'is_compose_media_facebook'    => false,
		'is_compose_media_google_plus' => false,
		'media'                        => array(),
		'shorten_links'                => false,
	);

	/**
	 * Errors.
	 *
	 * @var array
	 */
	protected $errors = array();
	/**
	 * Total text length length text.
	 *
	 * @var array
	 */
	protected $total_text_length = 0;

	/**
	 * Init Post compose.
	 *
	 * @param array $data .
	 */
	public function __construct( $data ) {

		$data = shortcode_atts( $this->vars, $data );
		$this->set_vars( $data );
		if ( $this->is_compose_media() && $this->is_social_type( 'linkedin' ) ) {
			return;
		}

		$this->generate_compose_data();
	}

	/**
	 * Generate compose data
	 *
	 * @since 2.7.4
	 */
	protected function generate_compose_data() {

		$this->set_created_by();
		$this->set_shorten_links();
		$this->set_message();
		$this->set_content_attributes();
		$this->set_content_custom_image();
		$this->set_content_image();
		$this->set_compose_media();
		$this->set_publish_data();
		$this->stringify_content_atts();
	}

	/**
	 * Get data for api multimple queues
	 *
	 * @since 2.7.4
	 */
	public function get_multiple_queues() {

		$output       = array();
		$compose      = $this->compose;
		$publish_data = $compose['publish_data'];
		unset( $compose['publish_data'] );
		$account_types               = array_fill( 0, count( $this->service_user_ids ), $this->social_native_type );
		$compose['account_types']    = implode( ',', $account_types );
		$compose['service_user_ids'] = implode( ',', $this->service_user_ids );
		foreach ( $publish_data as $settings ) {
			$output[] = array_merge( $compose, $settings );
		}

		return $output;
	}

	/**
	 * Set publish settings data
	 *
	 * @since 2.7.4
	 */
	protected function set_publish_data() {

		if ( empty( $this->settings ) ) {
			$this->set_error( 'empty_publish_data' );
			return;
		}

		$output = array();
		foreach ( $this->settings as  $settings ) {
			$settings = $this->validate_publish_data( $settings );
			if ( ! $settings ) {
				continue;
			}

			$output[] = $settings;
		}

		$this->set( 'publish_data', $output );
	}

	/**
	 * Set shorten links attr
	 *
	 * @since 2.7.4
	 */
	protected function set_shorten_links() {

		global $socialflow;
		$shorten_links = absint( $socialflow->options->get( 'shorten_links' ) );
		$this->set( 'shorten_links', $shorten_links );
	}

	/**
	 * Set created_by attr
	 *
	 * @since 2.7.4
	 */
	protected function set_created_by() {

		$user_id    = get_current_user_id();
		$name       = get_user_option( 'display_name', $user_id );
		$email      = get_user_option( 'user_email', $user_id );
		$created_by = "$name <{$email}>";
		$this->set( 'created_by', $created_by );
	}

	/**
	 * Encode comtent attributes to json
	 *
	 * @see api
	 *
	 * @since 2.7.4
	 */
	protected function stringify_content_atts() {

		$attr = 'content_attributes';
		if ( ! isset( $this->compose[ $attr ] ) ) {
			return;
		}

		$this->compose[ $attr ] = wp_json_encode( $this->compose[ $attr ] );
	}

	/**
	 * Set global var on construct class
	 *
	 * @since 2.7.4
	 *
	 * @param array $data .
	 */
	protected function set_vars( $data ) {

		foreach ( $data as $key => $value ) {
			$this->$key = $value;
		}

		if ( $this->is_post_attachment() ) {
			$this->is_compose_media = true;
		}
	}

	/**
	 * Get compose data
	 *
	 * @since 2.7.4
	 */
	public function get() {

		if ( empty( $this->errors ) ) {
			return $this->compose;
		}

		return array();
	}

	/**
	 * Validate publish settings data
	 *
	 * @since 2.7.4
	 *
	 * @param array $values .
	 */
	protected function validate_publish_data( $values ) {
		$valid_data                   = [];
		$valid_data['publish_option'] = $values['publish_option'];
		// Validate some passed data.
		switch ( $values['publish_option'] ) {
			case 'schedule':
				if ( empty( $values['scheduled_date'] ) ) {
					$this->set_error( 'empty_scheduled_date' );
					return;
				}

				$valid_data['scheduled_date'] = $this->get_valid_date( $values['scheduled_date'] );
				if ( empty( $valid_data['scheduled_date'] ) ) {
					$this->set_error( 'incorrect_scheduled_date' );
					return;
				}

				break;
			case 'optimize':
				// Set optimize start/end date depending on optimize_period.
				if ( 'range' === $values['optimize_period'] ) {
					$fields = array( 'start', 'end' );
					foreach ( $fields as $field ) {
						$field = "optimize_{$field}_date";
						if ( empty( $values[ $field ] ) ) {
							$this->set_error( "empty_optimize_{$field}_date" );
							return;
						}

						$valid_data[ $field ] = $this->get_valid_date( $values[ $field ] );
						if ( empty( $valid_data[ $field ] ) ) {
							$this->set_error( "incorrect_optimize_{$field}_date" );
							return;
						}
					}

					// set strtotime(), because comparasion of dates, for ex.
					// 11-04-2016 04:38 am (start) AND 11-04-2016 03:38 pm (end)
					// is incorrect,
					// not counted am & pm, counted only date numerals.
					if ( strtotime( $values['optimize_end_date'] ) < strtotime( $values['optimize_start_date'] ) ) {
						$this->set_error( 'invalid_optimize_period' );
						return;
					}
				} elseif ( 'anytime' !== $values['optimize_period'] ) {
					$current_time                      = current_time( 'timestamp' );
					$valid_data['optimize_start_date'] = gmdate( 'Y-m-d H:i:s O', strtotime( '+1 minute', $current_time ) );
					$valid_data['optimize_end_date']   = gmdate( 'Y-m-d H:i:s O', strtotime( "+{$values['optimize_period']}", $current_time ) );
				}

						$valid_data['must_send'] = isset( $values['must_send'] ) ? absint( $values['must_send'] ) : 0;
				break;
		}

		return $valid_data;
	}

	/**
	 * Date validation
	 *
	 * @param  string $date .
	 * @return null|string
	 *
	 * @since 2.7.4
	 */
	protected function get_valid_date( $date ) {

		if ( empty( $date ) ) {
			return null;
		}

		$date = strtotime( $date );
		// if set date_default_timezone, so all date/time functions return value on this location, not UTC
		// @since v 2.7.3.
		$timestamp = 'UTC' === date_default_timezone_get() ? current_time( 'timestamp' ) : time();
		if ( $timestamp > $date ) {
			return date( 'Y-m-d H:i:s', $timestamp );
		}

		if ( 'UTC' === date_default_timezone_get() ) {
			$date = $date - ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
		}

		return date( 'Y-m-d H:i:s', $date );
	}

	/**
	 * Set content attributes
	 *
	 * @since 2.7.4
	 */
	protected function set_content_attributes() {

		$this->set_content_link();
		$this->set_content_name_and_description();
	}

	/**
	 * Set compose media attributes
	 *
	 * @since 2.7.4
	 */
	protected function set_compose_media() {
		$bool              = true;
		$socialflow_params = filter_input_array( INPUT_POST );
		global $socialflow;
		$media = [];
		if ( $this->is_post_attachment() ) {
			foreach ( $this->media as $key => $item ) {
				if ( is_array( $item ) ) {
					if ( 'twitter' === $key && 'twitter' === $this->social_type ) {
						$bool          = false;
						$media[ $key ] = $this->media[ $key ];
					} else {
						if ( 'facebook' === $key && 'facebook' === $this->social_type ) {
							$bool          = false;
							$media[ $key ] = $this->media[ $key ];
						} elseif ( 'google_plus' === $key && 'google_plus' === $this->social_type ) {
							$bool          = false;
							$media[ $key ] = $this->media[ $key ];
						}
					}
				}
			}

			if ( $bool ) {
				return;
			}

			$bool = false;
			if ( $this->is_social_type( 'linkedin' ) ) {
				return;
			}

			if ( empty( $this->media ) ) {
				return;
			}

			$this->set( 'media_thumbnail_url', $media[ $this->social_type ]['medium_thumbnail_url'] );
			$this->set( 'media_filename', $media[ $this->social_type ]['filename'] );
		} else {
			foreach ( $this->media as $key => $items ) {
				if ( is_array( $items ) ) {
					foreach ( $items as  $item ) {
						$keys = array_keys( $item );
						if ( is_array( $keys ) ) {
							if ( 'twitter' === $keys[0] && 'twitter' === $this->social_type && $socialflow_params['socialflow']['global']['media'][ 'compose_media_url_' . $this->social_type ] === $item[ $keys[0] ][ $keys[0] ]['medium_thumbnail_url'] ) {
								if ( $socialflow_params['socialflow']['global']['media'][ 'compose_media_' . $this->social_type ] ) {
									$bool              = false;
									$media[ $keys[0] ] = $item[ $keys[0] ][ $keys[0] ];
								} elseif ( isset( $this->fields['image'] ) ) {
									$bool          = false;
									$media[ $key ] = $this->fields['image'];
								}
							} else {
								if ( 'facebook' === $keys[0] && 'facebook' === $this->social_type && $socialflow_params['socialflow']['global']['media'][ 'compose_media_url_' . $this->social_type ] === $item[ $keys[0] ][ $keys[0] ]['medium_thumbnail_url'] ) {
									if ( $socialflow_params['socialflow']['global']['media'][ 'compose_media_' . $this->social_type ] ) {
										$bool              = false;
										$media[ $keys[0] ] = $item[ $keys[0] ][ $keys[0] ];
									}
								} elseif ( 'google_plus' === $keys[0] && $socialflow_params['socialflow']['global']['media'][ 'compose_media_url_' . $this->social_type ] === $item[ $keys[0] ][ $keys[0] ]['medium_thumbnail_url'] ) {
									if ( isset( $socialflow_params['socialflow']['global']['media'] ) && isset( $socialflow_params['socialflow']['global']['media'][ 'compose_media_' . $this->social_type ] ) ) {
										$bool              = false;
										$media[ $keys[0] ] = $item[ $keys[0] ][ $keys[0] ];
									} elseif ( isset( $this->fields['image'] ) ) {
										$bool          = false;
										$media[ $key ] = $this->fields['image'];
									}
								}
							}
						}
					}
				}
			}

			if ( $bool && $socialflow_params['socialflow']['global']['media'][ 'compose_media_' . $this->social_type ] ) {
				foreach ( $this->content_image as $item ) {
					if ( $item === $socialflow_params['socialflow']['global']['media'][ 'compose_media_url_' . $this->social_type ] ) {
						$media[ $this->social_type ] = $socialflow->get_api()->add_media( $item );
						$bool                        = false;
					}
				}

				if ( $bool && $socialflow_params['socialflow']['global']['media'][ 'compose_media_url_' . $this->social_type ] && $socialflow_params['socialflow']['global']['media'][ 'compose_media_' . $this->social_type ] ) {
					$img                         = $socialflow_params['socialflow']['global']['media'][ 'compose_media_url_' . $this->social_type ];
					$media[ $this->social_type ] = $socialflow->get_api()->add_media( $img );
					$bool                        = false;
				}
			}
			if ( $bool ) {
				return;
			}

			$bool = false;
			if ( $this->is_social_type( 'linkedin' ) ) {
				return;
			}

			if ( is_wp_error( $media ) ) {
				return;
			}
			if ( empty( $this->media ) ) {
				return;
			}

			$this->set( 'media_thumbnail_url', $media[ $this->social_type ]['medium_thumbnail_url'] );
			$this->set( 'media_filename', $media[ $this->social_type ]['filename'] );
		}
	}

	/**
	 * Set content attribute - image
	 *
	 * @since 2.7.4
	 */
	protected function set_content_image() {

		global $socialflow;
		if ( $this->is_compose_media() ) {
			return;
		}

		if ( empty( $this->fields['image'] ) ) {
			return;
		}

		$image = $socialflow->get_output_image_url( $this->fields['image'] );
		$this->set_content_attr( 'picture', $image );
	}

	/**
	 * Set custom image to content attribute
	 */
	protected function set_content_custom_image() {

		global $socialflow;
		if ( $this->is_compose_media() ) {
			return;
		}

		if ( $this->is_social_type( 'linkedin' ) ) {
			return;
		}

		if ( empty( $this->fields['is_custom_image'] ) ) {
			return;
		}

		if ( empty( $this->fields['custom_image'] ) ) {
			return;
		}

		$image = $socialflow->get_output_image_url( $this->fields['custom_image'] );
		$this->set_content_attr( 'picture', $image );
	}

	/**
	 * Set name and description to cpntent attributes
	 *
	 * @since 2.7.4
	 */
	protected function set_content_name_and_description() {

		$socials = array( 'facebook', 'linkedin' );
		// Dont send title and description for media compose.
		if ( $this->is_compose_media() ) {
			return;
		}

		if ( ! in_array( $this->social_type, $socials, true ) ) {
			return;
		}

		if ( $this->fields['title'] ) {
			$name = esc_html( $this->fields['title'] );
			$name = $this->validate_text( 'name', $name );
			$this->set_content_attr( 'name', $name );
		}

		if ( $this->fields['description'] ) {
			$desc = wp_trim_words( esc_html( $this->fields['description'] ), 150, ' ...' );
			$desc = $this->validate_text( 'description', $desc );
			$this->set_content_attr( 'description', $desc );
		}
	}

	/**
	 * Retrieve some specific account data depending on account type
	 *
	 * @since 2.7.4
	 */
	protected function set_content_link() {

		if ( $this->is_compose_socials_networks() ) {
			return;
		}
		$socials = array( 'facebook', 'google_plus', 'linkedin' );
		if ( ! in_array( $this->social_type, $socials, true ) ) {
			return;
		}

		$this->set_content_attr( 'link', $this->post_permalink );
	}

	/**
	 * Set message attribute
	 *
	 * @since 2.7.4
	 */
	protected function set_message() {

		$message = trim( $this->fields['message'] );

			$message .= " {$this->post_permalink}";
			if ( $this->fields['message_postfix'] ) {
				$message .= " {$this->fields['message_postfix']}";
			}

		$message = $this->validate_text( 'message', $message );
		// see api WP_SocialFlow::add_multiple().
		$message = stripslashes( urldecode( $message ) );
		$this->set( 'message', $message );
	}

	/**
	 * Check attachment is current post type
	 *
	 * @since 2.7.4
	 *
	 * @return boolean
	 */
	protected function is_post_attachment() {

		return ( 'attachment' === $this->post_type );
	}
	/**
	 * Is compose socials networks
	 *
	 * @since 2.7.4
	 *
	 * @return boolean
	 */
	protected function is_compose_socials_networks() {

		if ( 'facebook' === $this->social_type ) {
			return $this->is_compose_media_facebook;
		}

		if ( 'google_plus' === $this->social_type ) {
			return $this->is_compose_media_facebook;
		}

		if ( 'twitter' === $this->social_type ) {
			return $this->is_compose_media_twitter;
		}
		return false;
	}
	/**
	 * Is compose  media
	 *
	 * @since 2.7.4
	 *
	 * @return boolean
	 */
	protected function is_compose_media() {

		return $this->is_compose_media;
	}

	/**
	 * Is  socials type
	 *
	 * @since 2.7.4
	 *
	 * @param string $type .
	 * @return boolean
	 */
	protected function is_social_type( $type ) {

		return ( $type === $this->social_type );
	}
	/**
	 * Set content attr
	 *
	 * @since 2.7.4
	 *
	 * @param int    $key .
	 * @param string $value .
	 */
	protected function set_content_attr( $key, $value ) {

		$attr_key = 'content_attributes';
		if ( ! isset( $this->compose[ $attr_key ] ) ) {
			$this->compose[ $attr_key ] = array();
		}

		$this->compose[ $attr_key ][ $key ] = $value;
	}
	/**
	 * Set compose.
	 *
	 * @since 2.7.4
	 *
	 * @param int   $key .
	 * @param array $value .
	 */
	protected function set( $key, $value ) {

		$this->compose[ $key ] = $value;
	}

	/**
	 * Validate text before sending to SocialFlow
	 *
	 * @param  string $name input text.
	 * @param  string $text input text.
	 * @return string       validated text
	 */
	protected function validate_text( $name, $text ) {

		global $socialflow;
		// Decode html entities.
		$text = wp_specialchars_decode( $text, ENT_QUOTES );
		switch ( $name ) {
			case 'message':
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																		$text = $socialflow->trim_chars( $text, 4200 );
				break;
			case 'name':
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																		$text = $socialflow->trim_chars( $text, 500 );
				break;
			case 'description':
																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																																		$text = $socialflow->trim_chars( $text, 5000 - $this->total_text_length );
				break;
			// no default case.
		}

		$this->total_text_length += strlen( $text );
		return $text;
	}
	/**
	 * Get errors
	 *
	 * @return array.
	 */
	public function get_errors() {

		return $this->errors;
	}
	/**
	 * Has errors
	 *
	 * @return boolean.
	 */
	public function has_errors() {

		return ! ! $this->get_errors();
	}

	/**
	 * Set errors
	 *
	 * @param string $error_key type error.
	 */
	protected function set_error( $error_key ) {

		$error = null;
		switch ( $error_key ) {
			case 'empty_message':
				if ( ! $this->is_social_type( 'google_plus' ) ) {
					/* translators: %s: search term */
					$mess  = __( '<b>Error:</b> Message field is required for: <i>%s</i>.' );
					$error = array(
						'code'    => "{$error_key}:",
						'message' => $mess,
						// translators:  jjj.
						'data'    => $this->social_type,
					);
				}
				break;
			case 'empty_publish_data':
				$error = array(
					'code'    => 'empty_message:',
					/* translators: %s: search term */
					'message' => printf( esc_html( __( '<b>Error:</b> Publish options are required for: <i>%s</i>.' ) ) ),
					'data'    => $this->social_type,
				);
				break;
			case 'empty_scheduled_date':
				/* translators: %s: search term */
				$mess  = __( '<b>Error:</b> Scheduled date is required for schedule publish option for: <i>%s.</i>' );
				$error = array(
					'code'    => "{$error_key}:",
					'message' => $mess,
					'data'    => $this->social_type,
				);
				break;
			case 'incorrect_scheduled_date':
				/* translators: %s: search term */
				$mess  = __( '<b>Error:</b> Post could not be sent to SocialFlow: %s scheduled time is in the past.' );
				$error = array(
					'code'    => "{$error_key}:",
					'message' => $mess,
					'data'    => ucfirst( $this->social_type ),
				);
				break;
			case 'empty_optimize_start_date':
				/* translators: %s: search term */
				$mess  = __( '<b>Error:</b> Optimize start date is required for optimize publish option for: <i>%s.</i>' );
				$error = array(
					'code'    => "{$error_key}:",
					'message' => $mess,
					'data'    => $this->social_type,
				);
				break;
			case 'empty_optimize_end_date':
				/* translators: %s: search term */
				$mess  = __( '<b>Error:</b> Optimize end date is required for optimize publish option for: <i>%s.</i>' );
				$error = array(
					'code'    => "{$error_key}:",
					'message' => $mess,
					'data'    => $this->social_type,
				);
				break;
			case 'incorrect_optimize_start_date':
				/* translators: %s: search term */
				$mess  = __( '<b>Error:</b> Post could not be sent to SocialFlow: set relevant optimize start time for: <i>%s.</i>' );
				$error = array(
					'code'    => "{$error_key}:",
					'message' => $mess,
					'data'    => $this->social_type,
				);
				break;
			case 'incorrect_optimize_end_date':
				/* translators: %s: search term */
				$mess  = __( '<b>Error:</b> Post could not be sent to SocialFlow: set relevant optimize end time for: <i>%s.</i>' );
				$error = array(
					'code'    => "{$error_key}:",
					'message' => $mess,
					'data'    => $this->social_type,
				);
				break;
			case 'invalid_optimize_period':
				/* translators: %s: search term */
				$mess = __( '<b>Error:</b> Invalid optimize period for <i>%s.</i>' );

				$error = array(
					'code'    => "{$error_key}:",
					'message' => $mess,
					'data'    => $this->social_type,
				);
				break;
		}

		if ( empty( $error ) ) {
			return;
		}

		$default        = array(
			'code'    => '',
			'message' => '',
			'data'    => '',
		);
		$error          = shortcode_atts( $default, $error );
		$this->errors[] = new WP_Error( $error['code'], $error['message'], $error['data'] );
	}

}
