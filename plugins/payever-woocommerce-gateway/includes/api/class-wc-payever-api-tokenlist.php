<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Api_TokenList' ) ) {
	return;
}

use Payever\Sdk\Core\Authorization\OauthToken;
use Payever\Sdk\Core\Authorization\OauthTokenList;

class WC_Payever_Api_TokenList extends OauthTokenList {

	use WC_Payever_WP_Wrapper_Trait;

	/**
	 * {@inheritdoc}
	 */
	public function load() {
		$savedTokens = $this->getTokenStorage();

		if ( is_array( $savedTokens ) ) {
			foreach ( $savedTokens as $name => $token ) {
				$this->add(
					$name,
					$this->create()->load( $token )
				);
			}
		}

		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function save() {
		$saved_tokens = array();

		/** @var OauthToken $token */
		foreach ( $this->getAll() as $name => $token ) {
			$saved_tokens[ $name ] = $token->getParams();
		}

		return $this->get_wp_wrapper()->update_option( WC_Payever_Helper::PAYEVER_OAUTH_TOKEN, wp_json_encode( $saved_tokens ) );
	}

	/**
	 * @return array|mixed
	 */
	private function getTokenStorage() {
		return $this->get_wp_wrapper()->get_option( WC_Payever_Helper::PAYEVER_OAUTH_TOKEN ) ?
			(array) json_decode( $this->get_wp_wrapper()->get_option( WC_Payever_Helper::PAYEVER_OAUTH_TOKEN, true ) ) : array();
	}
}
