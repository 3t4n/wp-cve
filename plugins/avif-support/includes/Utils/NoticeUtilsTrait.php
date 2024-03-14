<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW\Utils;

/**
 * Helpers Trait.
 */
/**
 * Helpers Trait.
 */
trait NoticeUtilsTrait {

	/**
	 * Message Array.
	 *
	 * @var array
	 */
	protected static $notice_messages = array();

	/**
	 * Errors Array.
	 *
	 * @var array
	 */
	protected static $notice_errors = array();


	/**
	 * Enqueue Notice Assets.
	 *
	 * @return void
	 */
	public static function notice_assets() {
		wp_enqueue_style( 'gpls-general-module-notice-styles', static::$plugin_info['url'] . 'assets/libs/notice.min.css', array(), static::$plugin_info['version'], 'all' );
	}

	/**
	 * Output messages + errors.
	 */
	public static function show_messages() {
		if ( count( self::$notice_errors ) > 0 ) {
			foreach ( self::$notice_errors as $error ) {
				if ( ! empty( $error ) ) {
					echo wp_kses_post( '<div id="message" class="error notice inline is-dismissible"><p><strong>' . $error . '</strong></p></div>' );
				}
			}
		} elseif ( count( self::$notice_messages ) > 0 ) {
			foreach ( self::$notice_messages as $message ) {
				if ( ! empty( $message ) ) {
					echo wp_kses_post( '<div id="message" class="updated notice inline is-dismissible"><p><strong>' . $message . '</strong></p></div>' );
				}
			}
		}
	}

	/**
	 * Add Message.
	 *
	 * @param string $message
	 * @return void
	 */
	public static function add_message( $message ) {
		self::$notice_messages[] = $message;
	}

	/**
	 * Add Error Message.
	 *
	 * @param string|array $message
	 * @return void
	 */
	public static function add_error( $message ) {
		if ( is_array( $message ) ) {
			self::$notice_errors = array_merge( self::$notice_errors, $message );
		} else {
			self::$notice_errors[] = $message;
		}
	}


	/**
	 * Send AJax Response.
	 *
	 * @param string $message
	 * @param string $status
	 * @param string $context
	 * @return void
	 */
	protected static function ajax_response( $message, $status = 'success', $status_code = null, $context = '', $result = array(), $additional_data = array(), $internal_css = false ) {
		$response_arr = array(
			'status'  => $status,
			'result'  => $result,
			'context' => $context,
			'message' => ! empty( $message ) ? ( ( 'success' === $status ) ? self::success_message( $message, true, '', true, array(), $internal_css ) : self::error_message( $message, true, '', true, array(), $internal_css ) ) : '',
		);
		$response_arr = array_merge( $response_arr, $additional_data );

		if ( 'success' === $status ) {
			wp_send_json_success( $response_arr, $status_code );
		} else {
			wp_send_json_error( $response_arr, $status_code );
		}
	}

	/**
	 * Ajax Error Response.
	 *
	 * @param string $message
	 * @return void
	 */
	protected static function ajax_error_response( $message, $context = '', $inline_css = false ) {
		wp_send_json_error(
			array(
				'status'  => 'error',
				'context' => $context,
				'message' => self::error_message( $message, true, '', true, array(), $inline_css ),
			)
		);
	}

	/**
	 * Ajax Success Response.
	 *
	 * @param string $message
	 * @return void
	 */
	protected static function ajax_success_response( $message, $context = '', $inline_css = false ) {
		wp_send_json_success(
			array(
				'status'  => 'success',
				'context' => $context,
				'message' => self::success_message( $message, true, '', true, array(), $inline_css ),
			)
		);
	}

	/**
	 * Expired Ajax Response.
	 *
	 * @return void
	 */
	protected static function expired_response() {
		wp_send_json_success(
			array(
				'status'  => 'error',
				'context' => 'login',
				'message' => self::error_message( esc_html__( 'The link has expired, please refresh the page!' ) ),
			)
		);
	}

	/**
	 * Expired Message.
	 *
	 * @return void
	 */
	public static function expired_message() {
		self::error_message( esc_html__( 'The link has expired, please refresh the page!' ), false, '', false );
	}

	/**
	 * General Invalid Submitted Data Response.
	 *
	 * @return void
	 */
	protected static function invalid_submitted_data_response() {
		wp_send_json_error(
			array(
				'status'  => 'error',
				'message' => self::error_message( esc_html__( 'Invalid submitted data' ) ),
			),
			400
		);
	}

	/**
	 * Error Message.
	 *
	 * @param string|array $message
	 * @param boolean      $return
	 * @param string       $html
	 * @return string|void
	 */
	public static function error_message( $messages, $return = true, $html = '', $include_close_btn = true, $buttons = array(), $internal_css = false ) {
		return self::message_box( $messages, 'danger', $return, $html, $include_close_btn, $buttons, $internal_css );
	}

	/**
	 * Success Message.
	 *
	 * @param string|array $message
	 * @param boolean      $return
	 * @param string       $html
	 * @return string|void
	 */
	public static function success_message( $messages, $return = true, $html = '', $include_close_btn = true, $buttons = array(), $internal_css = false ) {
		return self::message_box( $messages, 'success', $return, $html, $include_close_btn, $buttons, $internal_css );
	}

	/**
	 * Warning Message.
	 *
	 * @param string|array $message
	 * @param boolean      $return
	 * @param string       $html
	 * @return string|void
	 */
	public static function warning_message( $messages, $return = true, $html = '', $include_close_btn = true, $buttons = array(), $internal_css = false ) {
		return self::message_box( $messages, 'warning', $return, $html, $include_close_btn, $buttons, $internal_css );
	}

	/**
	 * Info Message.
	 *
	 * @param string|array $message
	 * @param boolean      $return
	 * @param string       $html
	 * @return string|void
	 */
	public static function info_message( $messages, $return = true, $html = '', $include_close_btn = true, $buttons = array(), $internal_css = false ) {
		return self::message_box( $messages, 'info', $return, $html, $include_close_btn, $buttons );
	}

	/**
	 * Message Box.
	 *
	 * @param array $messages
	 * @param string $type
	 * @param boolean $return
	 * @param string $html
	 * @param boolean $include_close_btn
	 * @param array $buttons
	 * @return mixed
	 */
	public static function message_box( $messages, $type, $return = true, $html = '', $include_close_btn = true, $buttons = array(), $internal_css = false ) {
		if ( ! is_array( $messages ) ) {
			$messages = (array) $messages;
		}
		if ( $return ) {
			ob_start();
		}
		?>
		<div class="<?php echo esc_attr( static::$plugin_info['classes_general'] . '-' . $type . '-notice' ); ?> <?php echo esc_attr( static::$plugin_info['classes_general'] . '-notice' ); ?>">
			<?php if ( $include_close_btn ) : ?>
				<div class="btn-close-wrapper d-block" style="margin-bottom:10px;">
					<button style="width:20px;height:20px;display:flex;justify-content:center;" type="button" class="btn-close btn-close-white me-2 m-auto start-0 end-0 top-1 btn-close-white border border-success button" data-bs-dismiss="toast" aria-label="Close">&#10005;</button>
				</div>
			<?php endif; ?>
			<!-- Notices List -->
			<ul class="ms-0 ps-0 msgs-list <?php echo esc_attr( $type ); ?>-list notices-list" style="padding:5px !important;">
				<?php foreach ( $messages as $message ) : ?>
				<li><?php echo wp_kses_post( $message ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php
			// HTML.
			if ( ! empty( $html ) ) {
				wp_kses_post( $html );
			}

			// Buttons.
			if ( ! empty( $buttons ) ) :
				?>
			<div class="notice-buttons-wrapper">
				<?php foreach ( $buttons as $button ) : ?>
				<a href="<?php echo esc_url_raw( $button['link'] ); ?>" <?php echo esc_attr( ! empty( $button['new_tab'] ) ? 'target="_blank"' : '' ); ?> role="button" class="notice-button"><?php echo esc_html( $button['title'] ); ?></a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
		<?php
		if ( $return ) {
			return ob_get_clean();
		}
	}

	/**
	 * Toast Notice.
	 *
	 * @return void
	 */
	public static function toast() {
		?>
		<div class="toast <?php echo esc_attr( static::$plugin_info['classes_general'] . '-toast' ); ?> bg-primary border-0 text-white fixed-top collapse justify-content-center mt-5 align-items-center top-0 start-50 translate-middle-x" role="alert" aria-live="assertive" aria-atomic="true" >
			<div class="d-flex">
				<div class="toast-body">
					<div class="toast-msg m-0"></div>
				</div>
				<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
			</div>
		</div>
		<?php
	}

	/**
	 * Handle AJAX Request.
	 *
	 * @param string $nonce_key
	 * @param string $cap
	 * @param array  $error_messages
	 * @return void
	 */
	public static function ajax_request_handle( $nonce_key, $cap = 'administrator', $error_messages = array(), $codes = array(), $context = '' ) {
		if ( empty( $_POST['nonce'] ) ) {
			wp_send_json_error( esc_html( ! empty( $error_messages['nonce'] ) ? $error_messages['nonce'] : esc_html__( 'The link has expired, please refresh the page!' ) ), ! empty( $codes['nonce'] ) ? $codes['nonce'] : 403 );
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), $nonce_key ) ) {
			self::expired_response();
		}

		if ( ! empty( $cap ) && ! current_user_can( $cap ) ) {
			self::ajax_response( ! empty( $error_messages['cap'] ) ? $error_messages['cap'] : esc_html__( 'Sorry, you are not allowed to perform this action.' ), 'error', ! empty( $codes['cap'] ) ? $codes['cap'] : null, $context );
		}
	}
}
