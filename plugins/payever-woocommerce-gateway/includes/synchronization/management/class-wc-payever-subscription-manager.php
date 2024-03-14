<?php

if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Subscription_Manager' ) ) {
	return;
}

use Payever\Sdk\Core\PseudoRandomStringGenerator;
use Payever\Sdk\Core\Enum\ChannelSet;
use Payever\Sdk\ThirdParty\Enum\ActionEnum;
use Payever\Sdk\ThirdParty\Http\MessageEntity\SubscriptionActionEntity;
use Payever\Sdk\ThirdParty\Http\RequestEntity\SubscriptionRequestEntity;
use Payever\Sdk\ThirdParty\Http\ResponseEntity\SubscriptionResponseEntity;

class WC_Payever_Subscription_Manager {

	use WC_Payever_Generic_Manager_Trait;
	use WC_Payever_Synchronization_Queue_Trait;
	use WC_Payever_ThirdParty_Api_Client_Trait;

	const PARAM_ACTION = 'action';
	const PARAM_EXTERNAL_ID = 'token';

	/**
	 * @return bool
	 */
	public function toggle_subscription() {
		$this->clean_messages();
		$is_enabled = $this->is_products_sync_enabled();
		$result = ! $is_enabled;
		try {
			$is_enabled ? $this->disable() : $this->enable();
		} catch ( \Exception $exception ) {
			$this->errors[] = $exception->getMessage();
			$result = false;
		}
		$this->log_messages();

		return $result;
	}

	/**
	 * @return void
	 */
	public function disable() {
		try {
			$this->get_third_party_api_client()->unsubscribe( $this->get_subscription_entity() );
		} catch ( \Exception $exception ) {
			$this->get_logger()->warning( 'Unable to unsubscribe', array( $exception->getMessage() ) );
		} finally {
			$this->cleanup();
		}
	}

	/**
	 * @return array
	 */
	public function get_supported_actions() {
		$result = array();
		try {
			$result = array_diff(
				ActionEnum::enum(),
				array(
					ActionEnum::ACTION_PRODUCTS_SYNC,
				)
			);
		} catch ( \ReflectionException $reflectionException ) {
			$this->get_logger()->warning( $reflectionException->getMessage() );
		}

		return $result;
	}

	/**
	 * @throws \Exception
	 * @throws \ReflectionException
	 */
	private function enable() {
		$subscription_entity = $this->get_subscription_entity();
		foreach ( $this->get_supported_actions() as $action_name ) {
			$action_url = WC()->api_request_url( 'payever_synchronization_incoming' );
			$action_url .= strpos( $action_url, '?' ) ? '&' : '?';
			$action_url .= http_build_query(
				array(
					self::PARAM_EXTERNAL_ID => $subscription_entity->getExternalId(),
					self::PARAM_ACTION      => $action_name,
				)
			);
			$subscription_entity->addAction(
				new SubscriptionActionEntity(
					array(
						'name'   => $action_name,
						'url'    => $action_url,
						'method' => 'POST',
					)
				)
			);
		}
		$subscription_entity_response = $this->get_third_party_api_client()->subscribe( $subscription_entity );
		/** @var SubscriptionResponseEntity $subscription_response_entity */
		$subscription_response_entity = $subscription_entity_response->getResponseEntity();
		$this->get_wp_wrapper()->update_option(
			WC_Payever_Helper::PAYEVER_PRODUCTS_SYNC_ENTITY,
			wp_json_encode( $subscription_response_entity )
		);
		$this->get_wp_wrapper()->update_option(
			WC_Payever_Helper::PAYEVER_PRODUCTS_SYNC_TOKEN,
			$subscription_entity->getExternalId()
		);
		$this->get_wp_wrapper()->update_option(
			WC_Payever_Helper::PAYEVER_PRODUCTS_SYNC_ENABLED,
			(bool) $subscription_response_entity
		);
	}

	/**
	 * @return SubscriptionRequestEntity
	 * @throws \Exception
	 */
	private function get_subscription_entity() {
		$external_id = $this->get_wp_wrapper()->get_option( WC_Payever_Helper::PAYEVER_PRODUCTS_SYNC_TOKEN );
		if ( ! $external_id ) {
			$random_source = new PseudoRandomStringGenerator();
			$external_id = $random_source->generate();
		}
		$subscriptionEntity = new SubscriptionRequestEntity();
		$subscriptionEntity->setExternalId( $external_id );
		$subscriptionEntity->setBusinessUuid(
			$this->get_wp_wrapper()->get_option( WC_Payever_Helper::PAYEVER_BUSINESS_ID )
		);
		$subscriptionEntity->setThirdPartyName( ChannelSet::CHANNEL_WOOCOMMERCE );

		return $subscriptionEntity;
	}

	/**
	 * @return void
	 */
	private function cleanup() {
		try {
			$this->get_sync_queue_manager()->emptyQueue();
			$this->get_wp_wrapper()->update_option(
				WC_Payever_Helper::PAYEVER_PRODUCTS_SYNC_TOKEN,
				null
			);
			$this->get_wp_wrapper()->update_option(
				WC_Payever_Helper::PAYEVER_PRODUCTS_SYNC_ENABLED,
				false
			);
		} catch ( \Exception $exception ) {
			$this->get_logger()->warning( $exception->getMessage() );
		}
	}
}
