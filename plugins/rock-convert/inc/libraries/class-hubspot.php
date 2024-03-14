<?php
/**
 * The HubsPot integration class.
 *
 * @package    Rock_Convert\Inc\libraries
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\inc\libraries;

/**
 * Class Hubspot
 *
 * This class sends a lead to Hubspot through a form
 *
 * @see     https://knowledge.hubspot.com/forms-user-guide-v2/how-to-create-a-form
 * @since   2.0.0
 * @package Rock_Convert\inc\libraries
 */
class Hubspot {

	/**
	 * Hubspot form url
	 *
	 * @since 2.0.0
	 *
	 * @var null
	 */
	public $form_url;

	/**
	 * Page URL
	 *
	 * @var null
	 */
	public $page_url;

	/**
	 * Hubspot constructor.
	 *
	 * @param string $form_url Hubspot URL form.
	 * @param string $page_url Page of blog/site.
	 */
	public function __construct( $form_url, $page_url = null ) {
		$this->form_url = $form_url;
		$this->page_url = $page_url;
	}

	/**
	 * Send new lead
	 *
	 * @param string $email Email from subscriber.
	 * @param array  $custom_context Context from subscrition.
	 * @param string $life_cycle Life cycle.
	 *
	 * @return array|\WP_Error
	 */
	public function new_lead(
		$email,
		$custom_context = array(),
		$life_cycle = 'subscriber'
	) {

		$context = $this->build_context( $custom_context );

		return wp_remote_post(
			$this->form_url,
			array(
				'headers' => array( 'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8' ),
				'body'    => $this->get_post_body( $email, $context, $life_cycle ),
				'method'  => 'POST',
			)
		);
	}

	/**
	 * Build context
	 *
	 * @param array $custom_context Custom context if needed.
	 *
	 * @return string
	 */
	private function build_context( $custom_context = array() ) {
		/**
		 * Visitors cookie
		 */
		$hubspotutk = isset( $_COOKIE['hubspotutk'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['hubspotutk'] ) ) : null;

		/**
		 * Current visitor IP address
		 */
		$ip_addr = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : null;

		/**
		 * Merge custom context with both cookie and IP Address
		 */
		$hs_context = array(
			'hutk'      => $hubspotutk,
			'ipAddress' => $ip_addr,
			'pageUrl'   => $this->page_url,
		);

		$context = array_merge( $hs_context, $custom_context );

		return wp_json_encode( $context );
	}

	/**
	 * Get post body
	 *
	 * @param string $email Email from subscriber.
	 * @param string $context Context from subscrition.
	 * @param string $life_cycle Life cycle.
	 *
	 * @return string
	 */
	private function get_post_body( $email, $context, $life_cycle ) {
		return 'email=' . rawurlencode( $email )
			. '&lifecyclestage=' . $life_cycle
			. '&hs_context=' . rawurlencode( $context );
	}
}
