<?php
/**
 * WC_FreePay_API class
 */

class WC_FreePay_API {

	/**
	 * Contains the API url
	 * @access protected
	 */
	protected $api_url = 'https://mw.freepay.dk/api/v2/';


	/**
	 * Contains a resource data object
	 * @access protected
	 */
	protected $resource_data;

	/**
	 * @var null
	 */
	protected $api_key = null;

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct( $api_key = null ) {
		if ( empty( $api_key ) ) {
			$this->api_key = WC_FP_MAIN()->s( 'freepay_apikey' );
		} else {
			$this->api_key = $api_key;
		}

		// Instantiate an empty object ready for population
		$this->resource_data = new stdClass();
	}

	/**
	 * get function.
	 *
	 * Performs an API GET request
	 *
	 * @access public
	 *
	 * @param      $path
	 * @param bool $return_array
	 *
	 * @return object
	 * @throws FreePay_API_Exception
	 */
	public function get( $path, $return_array = false ) {
		return $this->execute( $path, 'GET', $return_array );
	}


	/**
	 * post function.
	 *
	 * Performs an API POST request
	 *
	 * @access public
	 *
	 * @param       $path
	 * @param array $form
	 * @param bool $return_array
	 *
	 * @return object
	 * @throws FreePay_API_Exception
	 */
	public function post( $path, $form = [], $return_array = false, $override_api_url = '' ) {
		return $this->execute( $path, 'POST', $form, $return_array, $override_api_url );
	}


	/**
	 * put function.
	 *
	 * Performs an API PUT request
	 *
	 * @access public
	 *
	 * @param       $path
	 * @param array $form
	 * @param bool $return_array
	 *
	 * @return object
	 * @throws FreePay_API_Exception
	 */
	public function put( $path, $form = [], $return_array = false ) {
		return $this->execute( $path, 'PUT', $form, $return_array );
	}

	/**
	 * patch function.
	 *
	 * Performs an API PATCH request
	 *
	 * @access public
	 *
	 * @param $path
	 * @param array $form
	 * @param bool $return_array
	 *
	 * @return object
	 * @throws FreePay_API_Exception
	 */
	public function patch( $path, $form = [], $return_array = false ) {
		return $this->execute( $path, 'PATCH', $form, $return_array );
	}

	/**
	 * delete function.
	 *
	 * Performs an API DELETE request
	 *
	 * @access public
	 *
	 * @param $path
	 * @param array $form
	 * @param bool $return_array
	 *
	 * @return object
	 * @throws FreePay_API_Exception
	 */
	public function delete( $path, $form = [], $return_array = false ) {
		return $this->execute( $path, 'DELETE', $form, $return_array );
	}


	/**
	 * execute function.
	 *
	 * Executes the API request
	 *
	 * @access public
	 *
	 * @param string $request_type
	 * @param array $form
	 * @param boolean $return_array - if we want to retrieve an array with additional
	 *
	 * @return object|array
	 * @throws FreePay_API_Exception
	 */
	public function execute( $path, $request_type, $form = [], $return_array = false, $override_api_url = null ) {
		$args = array(
			'method'	=> $request_type,
			'headers'	=> array(
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'Authorization' => $this->api_key,
			)
		);

		$request_form_data = json_encode($form);

		if ( is_array( $form ) && ! empty( $form ) ) {
			$args['body'] = $request_form_data;
		}

		$url = (empty($override_api_url) ? $this->api_url : $override_api_url) . trim( $path, '/' );

		$response_raw = wp_remote_request( $url, $args );
		$response_body = wp_remote_retrieve_body( $response_raw );
		$response_code = wp_remote_retrieve_response_code( $response_raw );

		//decode the response to JSON
		$this->resource_data = json_decode( $response_body );

		// If the HTTP response code is higher than 299, the request failed.
		// Throw an exception to handle the error
		if ( $response_code > 299 ) {
			throw new FreePay_API_Exception( $response_body, $response_code, null, $url, $request_form_data, $response_raw );
		}

		if(isset($this->resource_data->IsSuccess) && !is_null($this->resource_data->IsSuccess) && isset($this->resource_data->ErrorCode) && !is_null($this->resource_data->ErrorCode) && !empty($this->resource_data->ErrorCode) && $this->resource_data->IsSuccess === false) {
			throw new FreePay_API_Exception( $response_body, $response_code, null, $url, $request_form_data, $response_raw );
		}

		// Everything went well, return the resource data object.
		if ( $return_array ) {
			$return_data = [
				$this->resource_data,
				$url,
				$request_form_data,
				$response_raw,
				null,
			];
		} else {
			$return_data = $this->resource_data;
		}

		return $return_data;
	}
}