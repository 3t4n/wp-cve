<?php
/**
 * Google API Client.
 *
 * @package GoogleAnalytics
 */

/**
 * Google API Client.
 */
abstract class Ga_Lib_Api_Client {

	/**
	 * Keeps error messages.
	 *
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Returns errors array.
	 *
	 * @return array
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Calls private API method from context client.
	 *
	 * @param callable $callback Callable function.
	 * @param array    $args     Array of arguments.
	 *
	 * @return Ga_Lib_Api_Response
	 */
	abstract public function call_api_method( $callback, $args );

	/**
	 * Calls api methods.
	 *
	 * @param string $callback Callback method name.
	 * @param mixed  $args     Arguments.
	 *
	 * @return mixed
	 */
	public function call( $callback, $args = null ) {
		try {
			delete_option( 'googleanalytics_sherethis_error_log' );
			return $this->call_api_method( $callback, $args );
		} catch ( Ga_Lib_Api_Client_Exception $e ) {
			$this->add_error( $e );

			return new Ga_Lib_Api_Response( Ga_Lib_Api_Response::$empty_response );
		} catch ( Ga_Lib_Api_Request_Exception $e ) {
			$this->add_error( $e );

			return new Ga_Lib_Api_Response( Ga_Lib_Api_Response::$empty_response );
		} catch ( Exception $e ) {
			$this->add_error( $e );

			return new Ga_Lib_Api_Response( Ga_Lib_Api_Response::$empty_response );
		}
	}

	/**
	 * Prepares error data.
	 *
	 * @param Exception $e Exception.
	 */
	protected function add_error( Exception $e ) {
		$this->errors[ $e->getCode() ] = array(
			'class'   => get_class( $e ),
			'message' => $e->getMessage(),
		);
		do_action( 'st_support_save_error', $e );
	}

	/**
	 * Add own error.
	 *
	 * @param string $code    Code string.
	 * @param string $message Message.
	 * @param string $class   Class name.
	 *
	 * @return void
	 */
	public function add_own_error( $code, $message, $class = '' ) {
		$this->errors[ $code ] = array(
			'class'   => $class,
			'message' => $message,
		);
	}
}
