<?php
/**
 * The file that connects with the AltText.ai API
 *
 *
 * @link       https://alttext.ai
 * @since      1.0.0
 *
 * @package    ATAI
 * @subpackage ATAI/includes
 */

/**
 * The API management class.
 *
 * This is used to connect with the AltText.ai API.
 *
 *
 * @since      1.0.0
 * @package    ATAI
 * @subpackage ATAI/includes
 * @author     AltText.ai <info@alttext.ai>
 */
class ATAI_API {
  /**
	 * The API key used to connect wit hte client.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $api_key    The API key for connecting with the client.
	 */
	private $api_key;

  /**
	 * The API client URL.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $base_url    The base URL of the API client.
	 */
	private $base_url;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $api_key ) {
    $this->api_key = $api_key;
    $this->base_url = 'https://alttext.ai/api/v1';
	}

  /**
   * Fetch account information.
   *
   * @since 1.0.0
   * @access public
   */
  public function get_account() {
    $response = wp_remote_get(
      $this->base_url . '/account',
      array(
        'headers'       => array(
          'Content-Type'  => 'application/json',
          'X-Api-Key'     => $this->api_key
        )
      )
    );

    if ( ! is_array( $response ) || is_wp_error( $response ) ) {
      return false;
    }

    /**
     * Response code is not 2xx
     */
    if ( substr( wp_remote_retrieve_response_code( $response ), 0, 1 ) != '2' ) {
      return false;
    }

    $response = json_decode( wp_remote_retrieve_body( $response ), true );
    $account = array(
      'plan'          => 'Free Trial',
      'expires_at'    => 'never',
      'usage'         => $response['usage'],
      'quota'         => $response['usage_limit'],
      'whitelabel'    => $response['whitelabel'] ?? false,
      'available'     => ( $response['usage_limit'] > $response['usage'] ) ? $response['usage_limit'] - $response['usage'] : 0,
    );

    // Plan is other than Free Trial
    if ( $response['subscription'] !== null ) {
      $account['plan']        = $response['subscription']['plan_name'];
      $account['expires_at']  = $response['subscription']['expires_at'];
    }

    if ( $account['available'] > 0 ) {
      delete_transient( 'atai_insufficient_credits' );
    }

    return $account;
  }

  /**
   * Get alt text for image.
   *
   * @since 1.0.0
   * @access public
   *
   * @param string  $attachment_id  ID of the image to request alt text for (or NULL to use just URL).
   * @param string  $attachment_url  URL of the image to request alt text for.
   */
  public function create_image( $attachment_id, $attachment_url, $api_options ) {
    if ( empty($attachment_id) || get_option( 'atai_public' ) === 'yes' ) {
      // If the site is public, get ALT by sending the image URL to the server
      $body = array(
        'webhook_url' => '',
        'image' => array(
          'url' => $attachment_url
        )
      );
    } else {
      // If the site is private, get ALT by sending the image file (base64) to the server
      $body = array(
        'image' => array(
          'raw' => base64_encode( file_get_contents( get_attached_file( $attachment_id ) ) )
        )
      );
    }

    $body = array_merge( $body, $api_options );
    $response = wp_remote_post(
      $this->base_url . '/images',
      array(
        'headers'       => array(
          'Content-Type'  => 'application/json',
          'X-Api-Key'     => $this->api_key
        ),
        'timeout' => 15,
        'body'          => wp_json_encode( $body )
      )
    );

    $attachment_edit_url = empty($attachment_id) ? $attachment_url : get_edit_post_link( $attachment_id );

    if ( ! is_array( $response ) || is_wp_error( $response ) ) {
      error_log( print_r( $response, true ) );

      ATAI_Utility::log_error(
        sprintf(
          '<a href="%s" target="_blank">Image #%d</a>: %s',
          esc_url( $attachment_edit_url ),
          (int) $attachment_id,
          esc_html__( 'Unknown error.', 'alttext-ai' )
        )
      );

      return false;
    }

    $response_code = wp_remote_retrieve_response_code( $response );
    $response_body = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( $response_code == '422' ) {
      $error_message = '';

      foreach( $response_body['errors'] as $key => $error ) {
        $error_message = $error[0];
        break;
      }

      if ( $error_message === 'account has insufficient credits' ) {
        error_log( print_r( $response, true ) );

        ATAI_Utility::log_error(
          sprintf(
            '[%d] <a href="%s" target="_blank">Image #%d</a>: %s',
            (int) $response_code,
            esc_url( $attachment_edit_url ),
            (int) $attachment_id,
            esc_html( $error_message )
          )
        );

        if ( get_option( 'atai_no_credit_warning' ) != 'yes' ) {
          set_transient( 'atai_insufficient_credits', TRUE, MONTH_IN_SECONDS );
        }

        return 'insufficient_credits';
      }

      error_log( print_r( $response, true ) );

      ATAI_Utility::log_error(
        sprintf(
          '[%d] <a href="%s" target="_blank">Image #%d</a>: %s',
          (int) $response_code,
          esc_url( $attachment_edit_url ),
          (int) $attachment_id,
          esc_html( $error_message )
        )
      );

      return false;
    } elseif ( substr( $response_code, 0, 1 ) != '2' ) {
      error_log( print_r( $response, true ) );

      ATAI_Utility::log_error(
        sprintf(
          '[%d] <a href="%s" target="_blank">Image #%d</a>: %s',
          (int) $response_code,
          esc_url( $attachment_edit_url ),
          (int) $attachment_id,
          esc_html__( 'API error.', 'alttext-ai' )
        )
      );

      return false;
    }

    return $response_body;
  }

  /**
   * Register API endpoint for webhook notification callback
   *
   * @since 1.0.5
   * @access public
   */
  public function register_webhook_endpoint() {
    register_rest_route( 'alttext-ai', '/notification', array(
      'methods' => 'POST',
      'callback' => [ $this, 'handle_webhook_notification' ],
      'permission_callback' => '__return_true',
    ) );
  }

  /**
   * Register webhook notification callback URL in AltText.ai.
   *
   * @since 1.0.5
   * @access public
   */
  public function send_webhook_url( $action = 'register' ) {
    $webhook_url = '';

    if ( $action === 'register' ) {
      $webhook_url = get_rest_url( null, 'alttext-ai/notification' );
    }

    $body = wp_json_encode( array(
      'account' => array(
        'webhook_url' => $webhook_url
      )
    ) );

    $response = wp_remote_request(
      $this->base_url . '/account',
      array(
        'method'        => 'PUT',
        'headers'       => array(
          'Content-Type'  => 'application/json',
          'X-Api-Key'     => $this->api_key
        ),
        'body'          => $body
      )
    );

    if ( ! is_array( $response ) || is_wp_error( $response ) ) {
      return false;
    }

    $response_code = wp_remote_retrieve_response_code( $response );

    /**
     * Response code is not 2xx
     */
    if ( substr( $response_code, 0, 1 ) != '2' ) {
      return false;
    }

    $response = json_decode( wp_remote_retrieve_body( $response ), true );

    if ( is_array( $response['errors'] ) && count( $response['errors'] ) ) {
      if ( isset( $response['webhook_url'] ) ) {
        return 'Invalid webhook URL.';
      }

      return false;
    }

    return $response['webhook_url'] === $webhook_url;
  }

  /**
   * Handle payload sent by AltText.ai webhook notification
   *
   * @since 1.0.5
   * @access public
   *
   * @param WP_REST_Request $request  The webhook notification request object
   */
  public function handle_webhook_notification( WP_REST_Request $request ) {
    $notification = $request->get_json_params();

    if ( $notification['event'] !== 'reviewed' ) {
      return array(
        'status' => 'error',
        'message' => 'Invalid event.',
      );
    }

    $asset_id = $notification['data']['images'][0]['asset_id'];
    $attachment_id = ATAI_Utility::find_atai_asset($asset_id);
    if ( empty($attachment_id) ) {
      return array(
        'status' => 'error',
        'message' => 'No image found.',
      );
    }

    update_post_meta( $attachment_id, '_wp_attachment_image_alt', $notification['data']['images'][0]['alt_text'] );

    return array(
      'status' => 'success',
      'message' => 'Updated Image #' . $attachment_id,
    );
  }
}
