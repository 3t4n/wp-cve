<?php
if ( ! defined( 'ABSPATH' ) || class_exists( 'WC_Payever_Notification_Entity' ) ) {
	return;
}

use Payever\Sdk\Payments\Http\RequestEntity\NotificationRequestEntity;

class WC_Payever_Notification_Entity extends NotificationRequestEntity {

	/**
	 * @param $available_notification_type
	 *
	 * @return $this
	 */
	public function add_available_notification_type( $available_notification_type ) {
		$this->notificationTypesAvailable[] = $available_notification_type;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function setNotificationType( $notificationType ) {
		$this->notificationType = $notificationType;

		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getNotificationTypesAvailable() {
		return $this->notificationTypesAvailable;
	}

	/**
	 * @inheritDoc
	 */
	public function getNotificationType() {
		return $this->notificationType;
	}
}
