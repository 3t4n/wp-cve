<?php

require_once plugin_dir_path( __FILE__ ) . 'class-sendgrid-tools.php';

class Sendgrid_NLVX
{
  const NLVX_API_URL = 'https://api.sendgrid.com/v3/marketing';

  /**
   * Returns the appropriate header value of authorization depending on the available credentials.
   *
   * @return  mixed   string of the header value if successful, false otherwise.
   */
  protected static function get_auth_header_value()
  {
    if ( 'false' == Sendgrid_Tools::get_mc_opt_use_transactional() ) {
      $mc_api_key = Sendgrid_Tools::get_mc_api_key();

      if ( false != $mc_api_key ) {
        return 'Bearer ' . $mc_api_key;
      }
    }

    $api_key = Sendgrid_Tools::get_api_key();
    if ( false == $api_key ) {
      return false;
    }

    return 'Bearer ' . $api_key;
  }

  /**
   * Returns the contact lists from SendGrid
   *
   * @return  mixed   an array of lists if the request is successful, false otherwise.
   */
  public static function get_all_lists()
  {
    $auth = Sendgrid_NLVX::get_auth_header_value();

    if ( false == $auth ) {
      return false;
    }

    $args = array(
        'headers' => array(
          'Authorization' => $auth
        ),
        'decompress' => false,
        'timeout' => Sendgrid_Tools::get_request_timeout()
    );

    $url = Sendgrid_NLVX::NLVX_API_URL . '/lists';

    $response = wp_remote_get( $url, $args );

    if ( ! is_array( $response ) or ! isset( $response['body'] ) ) {
      return false;
    }

    $lists_response = json_decode($response['body'], true);
    if ( isset( $lists_response['result'] ) ) {
      return $lists_response['result'];
    }

    return false;
  }

  /**
   * Adds a recipient in the SendGrid MC contact db
   *
   * @param   string $email          The email of the recipient
   * @param   string $first_name     The first name of the recipient
   * @param   string $last_name      The last name of the recipient
   *
   * @return  bool   True if successful, false otherwise.
   */
  public static function add_recipient($email, $first_name = '', $last_name = '', $list_id = null)
  {
    $auth = Sendgrid_NLVX::get_auth_header_value();

    if ( false == $auth ) {
      return false;
    }

    $args = array(
		'method' => 'PUT',
        'headers' => array(
          'Authorization' => $auth,
		  'Content-Type' => 'application/json'
        ),
        'decompress' => false,
        'timeout' => Sendgrid_Tools::get_request_timeout()
    );

    $url = Sendgrid_NLVX::NLVX_API_URL . '/contacts';

    $contact = array('email' => $email);

    if ( '' != $first_name ) {
      $contact['first_name'] = $first_name;
    }

    if ( '' != $last_name ) {
      $contact['last_name'] = $last_name;
    }

    $req_body = ['contacts' => array($contact)];
	if ($list_id) {
		$req_body['list_ids'] = [ $list_id ];
	}
    $args['body'] = json_encode($req_body);

    $response = wp_remote_post( $url, $args );

    if ( ! is_array( $response ) or ! isset( $response['body'] ) ) {
      return false;
    }

    $recipient_response = json_decode($response['body'], true);

    return !empty($recipient_response['job_id']);
  }

  /**
   * Adds a recipient in the SendGrid MC contact db and adds it to the list
   *
   * @param   string $email          The email of the recipient
   * @param   string $first_name     The first name of the recipient
   * @param   string $last_name      The last name of the recipient
   *
   * @return  bool   True if successful, false otherwise.
   */
  public static function create_and_add_recipient_to_list($email, $first_name = '', $last_name = '')
  {
    $list_id = Sendgrid_Tools::get_mc_list_id();
    if ( false == $list_id ) {
      return false;
    }

    return (bool) Sendgrid_NLVX::add_recipient($email, $first_name, $last_name, $list_id);
  }
}