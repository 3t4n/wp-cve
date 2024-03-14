<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Notification_Processor' ) ) {
	return;
}

use Payever\Sdk\Payments\Http\RequestEntity\NotificationRequestEntity;
use Payever\Sdk\Payments\Notification\NotificationRequestProcessor;

class WC_Payever_Notification_Processor extends NotificationRequestProcessor {

	const NOTIFICATION_TYPE = 'raw_request';

	/**
	 * @var bool
	 */
	private $is_valid_signature = false;

	/**
	 * @inheritDoc
	 * @param string $payload
	 *
	 * @return NotificationRequestEntity
	 */
	protected function unserializePayload( $payload ) {
		$notificationEntity = new WC_Payever_Notification_Entity( json_decode( $payload, true ) );

		$notificationEntity->add_available_notification_type( self::NOTIFICATION_TYPE );
		if ( $this->is_valid_signature ) {
			$notificationEntity->setNotificationType( self::NOTIFICATION_TYPE );
		}

		return $notificationEntity;
	}

	/**
	 * @param $payment_id
	 * @param $signature
	 *
	 * @return $this
	 * @throws Exception
	 */
	public function validate_signature( $payment_id, $signature ) {
		$signature_hash = WC_Payever_Helper::instance()->get_hash( $payment_id );

		$this->logger->debug( sprintf( 'Validating signature %s %s', $signature, $signature_hash ) );

		if ( $signature === $signature_hash ) {
			$this->is_valid_signature = true;
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	public function skip_signature_validation() {
		$this->is_valid_signature = true;

		return $this;
	}
}
