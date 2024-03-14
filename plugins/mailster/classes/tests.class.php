<?php

class MailsterTests {

	private $message;
	private $tests;
	private $current;
	private $next;
	private $total;

	private $last_is_error;
	private $last_error_test;
	private $last_error_message;
	private $last_error_type;
	private $last_error_link;

	private $current_id;

	private $errors;

	public function __construct( $test = null ) {

		if ( ! is_null( $test ) ) {
			$this->tests = is_array( $test ) ? array_keys( $test ) : array( $test => 0 );
		} else {
			$this->tests = $this->get_tests();
		}
		$this->total  = count( $this->tests );
		$this->errors = array(
			'count'         => 0,
			'error_count'   => 0,
			'warning_count' => 0,
			'notice_count'  => 0,
			'success_count' => 0,
			'all'           => array(),
			'error'         => array(),
			'warning'       => array(),
			'notice'        => array(),
			'success'       => array(),
		);
	}

	public function __call( $method, $args ) {

		switch ( $method ) {
			case 'error':
			case 'warning':
			case 'notice':
			case 'success':
				call_user_func_array( array( &$this, $method ), $args );
				break;
		}

		if ( method_exists( $this, 'test_' . $method ) ) {
			$this->run( $method );
			return ! $this->last_is_error;
		}
	}

	public function run( $test_id = null, $args = array() ) {

		if ( $test_id == null ) {
			$test_id = key( $this->tests );
		}

		if ( isset( $this->tests[ $test_id ] ) ) {

			$this->last_is_error      = false;
			$this->last_error_test    = null;
			$this->last_error_message = null;
			$this->last_error_type    = 'success';
			$this->last_error_link    = null;

			$this->current_id = $test_id;
			$this->current    = $this->tests[ $test_id ];

			try {
				if ( is_callable( $this->current ) ) {
					call_user_func_array( $this->current, array( $this ) );
				} elseif ( method_exists( $this, 'test_' . $test_id ) ) {
					call_user_func_array( array( &$this, 'test_' . $test_id ), $args );
				} else {
					$this->warning( 'Test \'' . $test_id . '\' does not exist!' );
				}
			} catch ( Exception $e ) {
				$this->error( $e );
			}
			return ! ( $this->last_error_test == $test_id );

		}

		return null;
	}

	public function get_tests() {

		$tests = get_class_methods( $this );
		$tests = preg_grep( '/^test_/', $tests );
		$tests = array_values( $tests );
		$tests = preg_replace( '/^test_/', '', $tests );
		$tests = array_flip( $tests );

		return apply_filters( 'mailster_tests', $tests );
	}

	public function get_message() {

		$time   = date( 'Y-m-d H:i:s' );
		$html   = '';
		$text   = '';
		$maxlen = max( array_map( 'strlen', array_keys( $this->get_tests() ) ) );

		foreach ( array( 'error', 'warning', 'notice', 'success' ) as $type ) {
			if ( ! $this->errors[ $type . '_count' ] ) {
				continue;
			}
			foreach ( $this->errors[ $type ] as $test_id => $test_errors ) {

				foreach ( $test_errors as $i => $error ) {
					$name = $this->nicename( $test_id );
					$more = '';
					if ( $error['data']['link'] ) {
						$more = ( filter_var( $error['data']['link'], FILTER_VALIDATE_URL ) !== false ) ? ' (<a class="mailster-test-result-link external" href="' . esc_url( $error['data']['link'] ) . '">' . esc_html__( 'More Info', 'mailster' ) . '</a>)' : $this->beacon( $error['data']['link'] );
					}
					$html .= '<div class="mailster-test-result mailster-test-is-' . $type . '"><h4>' . $name . $more . '</h4><div class="mailster-test-result-more">' . nl2br( $error['msg'] ) . '</div></div>';
					if ( $type != 'success' ) {
						$text .= '[' . $type . '] ' . $test_id . ': ' . strip_tags( $error['msg'] ) . "\n";
					}
				}
			}
		}

		return array(
			'test' => $this->current_id,
			'time' => $time,
			'html' => $html,
			'text' => $text,
		);
	}

	public function nicename( $test ) {
		if ( empty( $test ) ) {
			return $test;
		}
		$test = ucwords( str_replace( array( 'test_', '_' ), array( '', ' ' ), $test ) );
		$test = str_replace( array( 'Php', 'Wordpress', 'Wp ', 'Db', 'Mymail', 'Wpmail' ), array( 'PHP', 'WordPress', 'WP ', 'DB ', 'MyMail', 'wpmail()' ), $test );
		return $test;
	}

	public function get_next() {

		foreach ( $this->tests as $key => $value ) {
			unset( $this->tests[ $key ] );

			if ( $key == $this->current_id ) {
				break;
			}
		}
		$next = key( $this->tests );
		return $next;
	}

	public function get_current() {
		return $this->nicename( $this->current_id );
	}
	public function get_current_type() {
		return $this->last_error_type;
	}

	public function get_total() {

		return $this->total;
	}
	public function get_error_counts() {

		return array(
			'error'   => $this->errors['error_count'],
			'warning' => $this->errors['warning_count'],
			'notice'  => $this->errors['notice_count'],
			'success' => $this->errors['success_count'],
		);
	}



	private function error( $msg, $link = null ) {

		$this->failure( 'error', $msg, $link );
	}


	private function warning( $msg, $link = null ) {

		$this->failure( 'warning', $msg, $link );
	}


	private function notice( $msg, $link = null ) {

		$this->failure( 'notice', $msg, $link );
	}

	private function success( $msg, $link = null ) {

		$this->failure( 'success', $msg, $link );
	}


	private function failure( $type, $msg, $link = null ) {

		$test_id = $this->current_id;

		if ( is_null( $test_id ) ) {
			$test_id = uniqid();
		}

		$data = array( 'link' => $link );
		if ( ! isset( $this->errors['all'][ $test_id ] ) ) {
			$this->errors['all'][ $test_id ] = array();
		}
		$this->errors['all'][ $test_id ][] = array(
			'msg'  => $msg,
			'data' => $data,
		);
		if ( ! isset( $this->errors[ $type ][ $test_id ] ) ) {
			$this->errors[ $type ][ $test_id ] = array();
		}
		$this->errors[ $type ][ $test_id ][] = array(
			'msg'  => $msg,
			'data' => $data,
		);
		++$this->errors['count'];
		++$this->errors[ $type . '_count' ];

		$this->last_is_error      = 'success' != $type;
		$this->last_error_type    = $type;
		$this->last_error_test    = $test_id;
		$this->last_error_message = $msg;
		$this->last_error_link    = $link;
	}

	public function get() {
		return $this->errors['count'] ? $this->errors['all'] : true;
	}

	public function has( $type = null ) {
		if ( is_null( $type ) ) {
			return $this->errors['count'];
		} elseif ( isset( $this->errors[ $type ] ) ) {
			return $this->errors[ $type . '_count' ];
		}

		return false;
	}





	private function _test_error() {
		$this->error( 'This is an error error' );
	}
	private function _test_notice() {
		$this->notice( 'This is a notice error' );
	}
	private function _test_warning() {
		$this->warning( 'This is a warning error' );
	}
	private function _test_success() {
		$this->success( 'This is a success error' );
	}
	private function _test_multiple() {
		$this->error( 'This is a error error' );
		$this->notice( 'This is a notice error' );
		$this->warning( 'This is a warning error' );
		$this->success( 'This is a success error' );
	}



	private function test_php_version() {
		if ( version_compare( PHP_VERSION, '7.2.5' ) < 0 ) {
			$this->error( sprintf( 'Mailster requires PHP version 7.2.5 or higher. Your current version is %s. Please update or ask your hosting provider to help you updating.', PHP_VERSION ) );
		} elseif ( version_compare( PHP_VERSION, '7.4' ) < 0 ) {
			$this->notice( sprintf( 'Mailster recommends PHP version 7.4 or higher. Your current version is %s. Please update or ask your hosting provider to help you updating.', PHP_VERSION ) );
		} else {
			$this->success( 'You have version ' . PHP_VERSION );
		}
	}
	private function test_wordpress_version() {
		$update  = get_preferred_from_update_core();
		$current = get_bloginfo( 'version' );
		if ( version_compare( $current, '4.6' ) < 0 ) {
			$this->error( sprintf( 'Mailster requires WordPress version 4.6 or higher. Your current version is %s.', $current ) );
		} elseif ( $update && $update->response == 'upgrade' && version_compare( $update->current, $current ) ) {
			$this->warning( sprintf( 'Your WordPress site is not up-to-date! Version %1$s is available. Your current version is %2$s.', $update->current, $current ) );
		} else {
			$this->success( 'You have version ' . $current );

		}
	}
	private function test_dom_document_extension() {
		if ( ! class_exists( 'DOMDocument' ) ) {
			$this->error( 'Mailster requires the <a href="https://php.net/manual/en/class.domdocument.php" target="_blank" rel="noopener">DOMDocument</a> library.' );
		} else {
			$this->success( 'Your server supports <a href="https://php.net/manual/en/class.domdocument.php" target="_blank" rel="noopener">DOMDocument</a>.' );
		}
	}
	private function test_fsockopen_extension() {
		if ( ! function_exists( 'fsockopen' ) ) {
			$this->warning( 'Your server does not support <a href="https://php.net/manual/en/function.fsockopen.php" target="_blank" rel="noopener">fsockopen</a>.' );
		} else {
			$this->success( 'Your server supports <a href="https://php.net/manual/en/function.fsockopen.php" target="_blank" rel="noopener">fsockopen</a>.' );
		}
	}

	private function test_memory_limit() {
		$max = max( (int) ini_get( 'memory_limit' ), (int) WP_MAX_MEMORY_LIMIT, (int) WP_MEMORY_LIMIT );
		if ( $max < 128 ) {
			$this->warning( 'Your Memory Limit is ' . size_format( $max * 1048576 ) . ', Mailster recommends at least 128 MB' );
		} else {
			$this->success( 'Your Memory Limit is ' . size_format( $max * 1048576 ) );
		}
	}

	private function test_wpmail() {

		$args = $this->get_test_mail_data();

		$to      = $args['to'];
		$subject = $args['subject'];
		$message = $args['message'];

		add_action( 'wp_mail_failed', array( $this, 'wp_mail_failed' ) );
		if ( $response = wp_mail( $to, '[wp_mail] ' . $subject, $message ) ) {
			$this->success( '[wp_mail] Email was successfully delivery to ' . $to );
		}
		remove_action( 'wp_mail_failed', array( $this, 'wp_mail_failed' ) );
	}


	private function test_port_993() {

		$this->port_test( 993, 'smtp.gmail.com' );
	}
	private function test_port_25() {

		$this->port_test( 25, 'smtp.gmail.com' );
	}
	private function test_port_2525() {

		$this->port_test( 2525, 'smtp.sparkpostmail.com' );
	}
	private function test_port_465() {

		$this->port_test( 465, 'smtp.gmail.com' );
	}
	private function test_port_587() {

		$this->port_test( 587, 'smtp.gmail.com' );
	}


	private function test_finished() {

		$this->success( 'All tests have finished!' );
	}

	public function wp_mail_failed( $error ) {
		$error_message = strip_tags( $error->get_error_message() );
		$msg           = 'You are not able to use <code>wp_mail()</code> with Mailster';

		if ( false !== stripos( $error_message, 'smtp connect()' ) ) {
			$this->error( $msg . '<br>' . $error_message, '611bba516ffe270af2a99963' );
		} elseif ( false !== stripos( $error_message, 'data not accepted' ) ) {
			$this->error( $msg . '<br>' . $error_message, '611bb6ba6ffe270af2a99935' );
		} elseif ( false !== stripos( $error_message, 'could not execute' ) ) {
			$this->error( $msg . '<br>' . $error_message, '611bad7cb55c2b04bf6df0a0' );
		} else {
			$this->error( $msg . '<br>' . $error_message );
		}
	}


	private function port_test( $port, $domain, $strict = false ) {

		$result = $this->check_port( $domain, $port );
		if ( strpos( $result, 'open' ) !== false ) {
			$this->success( sprintf( 'Port %s is open an can be used!', '<strong>' . $port . '</strong>' ) . ' <code>' . $result . '</code>' );
		} else {
			$message = sprintf( 'Port %s is NOT open an cannot be used!', '<strong>' . $port . '</strong>' ) . ' <code>' . $result . '</code>';
			if ( $strict ) {
				$this->error( $message );
			} else {
				$this->notice( $message );
			}
		}
	}

	public function check_port( $host, $port ) {

		if ( ! function_exists( 'fsockopen' ) ) {
			return 'requires fsockopen to check ports.';
		}

		$conn = @fsockopen( $host, $port, $errno, $errstr, 5 );

		$return = ( is_resource( $conn ) ? '(' . getservbyport( $port, 'tcp' ) . ') open.' : 'closed [' . $errstr . ']' );

		is_resource( $conn ) ? fclose( $conn ) : '';

		return $return;
	}

	private function get_test_mail_data() {

		$user = wp_get_current_user();

		if ( ! $user ) {
			$this->error( 'No current user found for test mail.' );
			return;
		}
		if ( ! is_email( $user->user_email ) ) {
			$this->error( 'The current user doesn\'t have a valid email address to send a test mail.' );
			return;
		}

		$return = array(
			'to'      => $user->user_email,
			'subject' => 'This is a test mail from the Mailster Test page',
			'message' => sprintf( "This message has been sent from\n\n%s.\n\nYou can delete this message.", admin_url( 'admin.php?page=mailster-tester' ) ),
		);

		return $return;
	}
}
