<?php
/**
 * The MailChimp lists class.
 *
 * @package    Rock_Convert\Inc\libraries
 * @link       https://rockcontent.com
 * @since      1.0.0
 *
 * @author     Rock Content
 */

namespace Rock_Convert\inc\libraries;

/**
 * MailChimp class final.
 */
class MailChimp extends MailChimp_Core {

	/**
	 * Get lists from account
	 *
	 * @return array
	 */
	public function getLists() {
		if ( ! empty( $this->api_key ) ) {
			$result = $this->get( 'lists' );

			return $result['lists'];
		}

		return array();
	}

	/**
	 * Subscribe a user to a list
	 *
	 * @param string $email Subscriber email.
	 * @param string $list Subscribers list.
	 *
	 * @return array|false
	 */
	public function newLead( $email, $list ) {
		return $this->post(
			"lists/$list/members",
			array(
				'email_address' => $email,
				'status'        => 'subscribed',
			)
		);
	}
}
