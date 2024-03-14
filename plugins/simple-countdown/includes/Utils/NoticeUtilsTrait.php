<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Utils;

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
	protected static function ajax_response( $message, $status = 'success', $status_code = null, $context = '', $result = array(), $additional_data = array() ) {
		$response_arr = array(
			'status'  => $status,
			'result'  => $result,
			'context' => $context,
			'message' => ! empty( $message ) ? ( ( 'success' === $status ) ? self::success_message( $message ) : self::error_message( $message ) ) : '',
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
	protected static function ajax_error_response( $message ) {
		wp_send_json_error(
			array(
				'status'  => 'error',
				'context' => 'login',
				'message' => self::error_message( $message ),
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
				'message' => self::error_message( 'The link has expired, please refresh the page!' ),
			)
		);
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
				'message' => self::error_message( 'Invalid submitted data' ),
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
	public static function error_message( $messages, $return = true, $html = '', $include_close_btn = true, $buttons = array() ) {
		if ( ! is_array( $messages ) ) {
			$messages = (array) $messages;
		}
		if ( $return ) {
			ob_start();
		}
		?>
		<div class="<?php echo esc_attr( static::$plugin_info['classes_general'] . '-error-notice' ); ?> <?php echo esc_attr( static::$plugin_info['classes_general'] . '-notice' ); ?> position-relative">
			<?php if ( $include_close_btn ) : ?>
				<div class="btn-close-wrapper d-block">
					<button type="button" class="btn-close btn-close-white me-2 m-auto start-0 end-0 top-1 btn-close-white border border-success p-1" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			<?php endif; ?>
			<ul class="errors-list notices-list">
				<?php foreach ( $messages as $message ) : ?>
				<li><?php echo esc_html( $message ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php
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
	 * Success Message.
	 *
	 * @param string|array $message
	 * @param boolean      $return
	 * @param string       $html
	 * @return string|void
	 */
	public static function success_message( $messages, $return = true, $html = '', $include_close_btn = true, $buttons = array() ) {
		if ( ! is_array( $messages ) ) {
			$messages = (array) $messages;
		}
		if ( $return ) {
			ob_start();
		}
		?>
		<div class="<?php echo esc_attr( static::$plugin_info['classes_general'] . '-success-notice' ); ?> <?php echo esc_attr( static::$plugin_info['classes_general'] . '-notice' ); ?>">
			<?php if ( $include_close_btn ) : ?>
				<div class="btn-close-wrapper d-block">
					<button type="button" class="btn-close btn-close-white me-2 m-auto start-0 end-0 top-1 btn-close-white border border-success p-1" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			<?php endif; ?>
			<ul class="msgs-list notices-list">
				<?php foreach ( $messages as $message ) : ?>
				<li><?php echo esc_html( $message ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php
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
	 * Warning Message.
	 *
	 * @param string|array $message
	 * @param boolean      $return
	 * @param string       $html
	 * @return string|void
	 */
	public static function warning_message( $messages, $return = true, $html = '', $include_close_btn = true, $buttons = array() ) {
		if ( ! is_array( $messages ) ) {
			$messages = (array) $messages;
		}
		if ( $return ) {
			ob_start();
		}
		?>
		<div class="<?php echo esc_attr( static::$plugin_info['classes_general'] . '-warning-notice' ); ?> <?php echo esc_attr( static::$plugin_info['classes_general'] . '-notice' ); ?>">
			<?php if ( $include_close_btn ) : ?>
				<div class="btn-close-wrapper d-block">
					<button type="button" class="btn-close btn-close-white me-2 m-auto start-0 end-0 top-1 btn-close-white border border-success p-1" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			<?php endif; ?>
			<ul class="warnings-list notices-list">
				<?php foreach ( $messages as $message ) : ?>
				<li><?php echo esc_html( $message ); ?></li>
				<?php endforeach; ?>
			</ul>
			<?php
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
	 * Info Message.
	 *
	 * @param string|array $message
	 * @param boolean      $return
	 * @param string       $html
	 * @return string|void
	 */
	public static function info_message( $messages, $return = true, $html = '', $include_close_btn = true, $buttons = array() ) {
		if ( ! is_array( $messages ) ) {
			$messages = (array) $messages;
		}
		if ( $return ) {
			ob_start();
		}
		?>
		<div class="<?php echo esc_attr( static::$plugin_info['classes_general'] . '-info-notice' ); ?> <?php echo esc_attr( static::$plugin_info['classes_general'] . '-notice' ); ?>">
			<?php if ( $include_close_btn ) : ?>
				<div class="btn-close-wrapper d-block">
					<button type="button" class="btn-close btn-close-white me-2 m-auto start-0 end-0 top-1 btn-close-white border border-success p-1 button" data-bs-dismiss="toast" aria-label="Close"></button>
				</div>
			<?php endif; ?>
			<!-- Notices List -->
			<ul class="info-list notices-list">
				<?php foreach ( $messages as $message ) : ?>
				<li><?php echo esc_html( $message ); ?></li>
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
			wp_send_json_error( esc_html( ! empty( $error_messages['nonce'] ) ? $error_messages['nonce'] : esc_html__( 'Sorry, you are not allowed to perform this action.' ) ), ! empty( $codes['nonce'] ) ? $codes['nonce'] : 403 );
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), $nonce_key ) ) {
			self::expired_response();
		}

		if ( ! current_user_can( $cap ) ) {
			self::ajax_response( ! empty( $error_messages['cap'] ) ? $error_messages['cap'] : esc_html__( 'Sorry, you are not allowed to perform this action.' ), 'error', ! empty( $codes['cap'] ) ? $codes['cap'] : null, $context );
		}
	}
}
