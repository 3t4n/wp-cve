<?php

/** 
* Emma API Class
*
* future home of all function calls to Emma's new API via the WP HTTP API
* mimcs e2ma's semantics and nomenclature
* adapter pattern
*
* the governing purpose is to have a class that handles all the Emma API interaction, and JSON formatting, and returns PHP 
*
* Link: http://myemma.com/api-docs/
*/

class Emma_API {

	/**
     * PROPERTIES
     */
    private $_account_id;

	private $_publicAPIkey;
	
	private $_privateAPIkey;
	
	public $_signup_ID;
	
	private $_headers;

	// base URL for API requests w/ trailing slash
    // gd php. $this->self self::$this
	const REQUEST_URL_BASE = 'https://api.e2ma.net/';

	// THE CONSTRUCTOR
	public function __construct( $_account_id, $_publicAPIkey, $_privateAPIkey, $_signup_ID = '' ) {

		// on construction, pass in public and private API keys, assign them to class properties, 
		$this->_account_id = $_account_id;
		$this->_publicAPIkey = $_publicAPIkey;
		$this->_privateAPIkey = $_privateAPIkey;
		$this->_signup_ID = $_signup_ID;
		
		// All API calls must include an HTTP Basic authentication header containing the public & private API keys for your account.
		// build HTTP Basic Auth headers
		$this->_headers = array(
			'Content-Type: application/json; charset=utf-8',
			'Accept:application/json, text/javascript, */*; q=0.01',
			'Authorization' => 'Basic ' . base64_encode( $_publicAPIkey . ':' . $_privateAPIkey ),
			// Add x-forwarded-for as requested by Emma
			'X-Forwarded-For' => $_SERVER['REMOTE_ADDR'],
		);
		
	} // end __construct()
	
	// THE DESTRUCTOR
	public function __destruct() {} // end destruct()

	/**
     * METHODS
     */

    /**
     * list_groups
     * path: GET /#account_id/groups
     * get a basic listing of all active member groups for a single account.
     * @return array|mixed|string|\WP_Error : array
     */
	public function list_groups() {
	 
		// build request url for list_groups()
		$request_url = self::REQUEST_URL_BASE . $this->_account_id .'/groups?group_types=g';
		
		$request_args = array (
			'method' => 'GET',
			'timeout' => 11,
			'blocking' => true,
			'headers' => $this->_headers,
			'body' => null,
			'compress' => false,
			'decompress' => true,
			'sslverify' => false
		);
		
		// make the call, tyty WP HTTP API
		$response = $emma_response = wp_remote_request( $request_url, $request_args );
		if (is_array($response)) {
			$decoded_response = json_decode($response['body']);
		}

        #todo - @ this point, the emma api class should just return the object, let the class calling it handle the return. ( is_wp_error vs emma response object )

		// check to see if it throws a wordpress error
		if( is_wp_error( $response ) ) {

			$status_txt =  '<div class="e2ma-error">Something misfired. Please check your API keys and try again,</div>';
			// get the wordpress error
			$status_txt .= '<pre>' . $response->get_error_message() . '</pre>';

			$response = $status_txt;

		} elseif ( !empty( $decoded_response ) ){
		
			// decode the JSON from the response body
            // it'll be a stdClass Object of available groups
			$groups = json_decode( $response['body'] );

            // empty the response, type it as an array
            $response = array();

            // loop thru response array, create key value array for wp database
            foreach( $groups as $group ) {
                // format the response as an array of key value pairs, member_group_id => group_name
                $response[ $group->member_group_id ] = $group->group_name;
            }

		
		} else {
			$response = 'No groups found. Check your API Keys, and your Account ID.';
		} // end if / else
		
		return $response;
		
	} // end list_groups
	
	
	
	/**
	* Create one or more new member groups.
	* @param array $params		Array of options
	* @access public
	* @return 	An array of the new group ids and group names.
	*/
	function groupsAdd($data) {
		
		// encode the data, get it ready for transport.
		$data = json_encode( $data );
		
		$request_url = self::REQUEST_URL_BASE . $this->_account_id .'/groups';
		
		$request_args = array (
			'method' => 'POST',
			'timeout' => 11,
			'blocking' => true,
			'headers' => $this->_headers,
			'body' => $data,
			'compress' => false,
			'decompress' => true,
			'sslverify' => false

		);
		
		// make the call, tyty WP HTTP API
		$response = wp_remote_request( $request_url, $request_args );
		
		// check to see if it throws a wordpress error
		if( is_wp_error( $response ) ) {

			$status_txt =  '<div class="e2ma-error">Something misfired. Please check your API keys and try again,</div>';
			// get the wordpress error
			$status_txt .= '<pre>' . $response->get_error_message() . '</pre>';

			$response = $status_txt;

		} else {
		
			// decode the JSON from the response body
			$response = json_decode( $response['body'] );
		
		} // end if / else
		
		return $response;
	}
	

	
	/**
     * import_single_member
     * path: POST /#account_id/members/add
     * adds or updates an audience member
     * returns the member_id of the new or updated member, and whether the member was added or an existing member was updated
     * parameters:
        email (string) Email address of member to add or update
        fields (dictionary) Names and values of user-defined fields to update
        group_ids (array of integers) Optional. Add imported members to this list of groups.
        signup_form_id Optional. Indicate that this member used a particular signup form. This is important if you have custom confirmation messages for a particular signup form and so that signup-based triggers will be fired.
     * raises :    Http404 if no member is found.
     * @param $data
     * @return array|mixed|string|\WP_Error
     */
     public function import_single_member( $data ) {
		
		// encode the data, get it ready for transport.
		$data = json_encode( $data );

		$request_url = self::REQUEST_URL_BASE . $this->_account_id .'/members/signup';
		
		$request_args = array (
			'method' => 'POST',
			'timeout' => 11,
			'blocking' => true,
			'headers' => $this->_headers,
			'body' => $data,
			'compress' => false,
			'decompress' => true,
			'sslverify' => false

		);
		
		// make the call, tyty WP HTTP API
		$response = wp_remote_request( $request_url, $request_args );
		
		return $response;
	
	} // end import_single_member()
	/*
public function import_single_member( $data ) {
		
		// encode the data, get it ready for transport.
		$data = json_encode( $data );

		$request_url = self::REQUEST_URL_BASE . $this->_account_id .'/members/add';
		
		$request_args = array (
			'method' => 'POST',
			'timeout' => 11,
			'blocking' => true,
			'headers' => $this->_headers,
			'body' => $data,
			'compress' => false,
			'decompress' => true,
			'sslverify' => false

		);

		// make the call, tyty WP HTTP API
		$response = wp_remote_request( $request_url, $request_args );

		return $response;
	
	} // end import_single_member()
*/
	
	
	/**
     * get_member_detail
     * path: GET /#account_id/members/#member_id
     * description: Get detailed information on a particular member, including all custom fields.
     * returns: A single member if one exists.
     * params: deleted (boolean) � Accepts True or 1. Optional flag to include deleted members.
     * raises: Http404 if no member is found.
     * @param $member_id
     * @return array|mixed|string|\WP_Error
     */
	public function get_member_detail( $member_id ) {
		
		// build get member request url, w/ account number and member id for get_member_detail()
		$request_url = self::REQUEST_URL_BASE . $this->_account_id .'/members/'. $member_id;

		// setup args for wp_remote_request, get recently added member for confirmation.
		$request_args = array(
			'method' =>'GET',
			'timeout' => 11,
			'blocking' => true,
			'headers' => $this->_headers,
			'body' => null,
			'compress' => false,
			'decompress' => true,
			'sslverify' => false
		);

		// make the call again,
		$response = wp_remote_request( $request_url, $request_args );
		
		return $response;
	
	} // end get_member_detail() 

} // end class Emma_API
