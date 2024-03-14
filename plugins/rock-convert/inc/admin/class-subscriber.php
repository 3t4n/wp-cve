<?php
/**
 * The Subscriber class
 *
 * @package    Rock_Convert\Inc\Admin
 * @link       https://rockcontent.com
 * @since      2.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\Inc\Admin;

use Rock_Convert\inc\core\Table_Structure;
use Rock_Convert\inc\libraries\Hubspot;
use Rock_Convert\inc\libraries\MailChimp;
use Rock_Convert\inc\libraries\RD_Station;

/**
 * Subscriber class
 *
 * @since 2.0.0
 */
class Subscriber {

	/**
	 * Email var
	 *
	 * @var null
	 */
	public $email = null;

	/**
	 * ID from post
	 *
	 * @var null
	 */
	public $post_id = null;

	/**
	 * URL from source
	 *
	 * @var null
	 */
	public $url = null;

	/**
	 * Name from form field
	 *
	 * @var null
	 */
	public $name_field = null;

	/**
	 * Name of custom field from form
	 *
	 * @var null
	 */
	public $custom_field = null;

	/**
	 * Subscriber constructor.
	 *
	 * @param string $email Email from subscriber.
	 * @param int    $post_id Post id from page.
	 * @param null   $url Url from source.
	 * @param null   $name_field Name of form.
	 * @param null   $custom_field Custom field of form.
	 */
	public function __construct( $email, $post_id = 0, $url = null, $name_field = null, $custom_field = null ) {
		$this->email        = $email;
		$this->post_id      = $post_id;
		$this->url          = $url;
		$this->name_field   = $name_field;
		$this->custom_field = $custom_field;
	}

	/**
	 * Send data to recipients
	 *
	 * @param null $source A source to a service.
	 *
	 * @return bool
	 */
	public function subscribe( $source = null ) {
		$response = false;

		if ( is_email( $this->email ) ) {
			$this->store_email( $this->post_id, $this->email );
			$this->send_to_hubspot( $this->email, $this->url );
			$this->send_to_rd_station( $this->email, $source );
			$this->send_to_mailchimp( $this->email );

			$response = true;
		}

		return $response;
	}

	/**
	 * Store subscriber on rock convert subscriber table
	 *
	 * @param int    $post_id ID from post.
	 * @param string $email Email from subscriber.
	 *
	 * @since 2.0.0
	 */
	private function store_email( $post_id, $email ) {
		$subscriptions = new Table_Structure();

		if ( ! isset( $this->url ) ) {
			$this->url = get_permalink( $post_id );
		}

		$data = array(
			'post_id'    => $post_id,
			'email'      => $email,
			'url'        => $this->url,
			'created_at' => gmdate( 'Y-m-d H:i:s' ),
		);

		if ( $this->name_field ) {
			$data['user_name'] = $this->name_field;
		}
		if ( $this->custom_field ) {
			$data['custom_field'] = $this->custom_field;
		}

		$subscriptions->insert( $data );
	}

	/**
	 * Send a lead to Hubspot
	 *
	 * @param string $email Email from subscriber.
	 * @param string $url URL from service (Hubspot).
	 *
	 * @example $this->send_to_hubspot('foo@example.com');
	 */
	private function send_to_hubspot( $email, $url ) {
		$form = get_option( '_rock_convert_hubspot_form_url' );

		if ( ! empty( $form ) ) {
			try {
				$hubspot = new Hubspot( $form, $url );
				$result  = $hubspot->new_lead( $email );

				if ( 200 !== $result['response']['code'] ) {
					Utils::logError( '[Hubspot] - Form: ' . $form . ' | ' . $result['response']['message'] );
				}
			} catch ( \Exception $e ) {
				Utils::logError( '[Hubspot] - Form: ' . $form . ' | ' . $e );
			}
		}
	}

	/**
	 * Send lead to RD Station
	 *
	 * For this to work, option _rock_convert_rd_public_token should be present
	 *
	 * @param string $email Email from subscriber.
	 * @param string $identifier Identifier for RDStation.
	 *
	 * @see   https://github.com/agendor/rdstation-php-client/blob/master/RDStationAPI.class.php
	 * @since 2.0.0
	 *
	 * @throws \Exception Exception from errors.
	 */
	private function send_to_rd_station( $email, $identifier ) {
		$token = get_option( '_rock_convert_rd_public_token' );

		if ( ! empty( $token ) ) {
			try {

				$rd_station_api = new RD_Station( $token );
				$result         = $rd_station_api->new_lead( $email, array( 'identificador' => $identifier ) );

				if ( is_wp_error( $result ) ) {
					throw new \Exception( $result->get_error_message() );
				}

				if ( 200 !== $result['response']['code'] ) {
					throw new \Exception( $result['response']['message'] );
				}
			} catch ( \Exception $e ) {
				Utils::logError( '[RD Station] - Token: ' . $token . ' | Message: ' . $e->getMessage() );
			}
		}
	}

	/**
	 * Send lead to Mailchimp
	 *
	 * For this to work, option _rock_convert_mailchimp_token and _rock_convert_mailchimp_list should be present
	 *
	 * @param string $email E-mail from subscriber.
	 *
	 * @see https://github.com/drewm/mailchimp-api
	 * @since 2.2.0
	 */
	private function send_to_mailchimp( $email ) {
		$token = get_option( '_rock_convert_mailchimp_token' );
		$list  = get_option( '_rock_convert_mailchimp_list' );

		if ( ! empty( $token ) && ! empty( $list ) ) {

			try {
				$mailchimp = new MailChimp( $token );
				$result    = $mailchimp->newLead( $email, $list );

				if ( 'subscribed' !== $result['status'] ) {
					Utils::logError( '[Mailchimp] - Token:  ' . $token . ' | List: ' . $list . ' | Message: ' . $result['detail'] );
				}
			} catch ( \Exception $e ) {
				Utils::logError( $e );
			}
		}
	}
}
