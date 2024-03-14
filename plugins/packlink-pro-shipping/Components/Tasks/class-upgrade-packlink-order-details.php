<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Components\Tasks;

use Logeecom\Infrastructure\Http\Exceptions\HttpUnhandledException;
use Logeecom\Infrastructure\Logger\Logger;
use Logeecom\Infrastructure\ServiceRegister;
use Logeecom\Infrastructure\TaskExecution\Task;
use Logeecom\Infrastructure\Utility\TimeProvider;
use Packlink\BusinessLogic\Http\DTO\Shipment;
use Packlink\BusinessLogic\Http\Proxy;
use Packlink\BusinessLogic\OrderShipmentDetails\OrderShipmentDetailsService;
use Packlink\BusinessLogic\ShippingMethod\Utility\ShipmentStatus;

/**
 * Class UpgradePacklinkOrderDetails
 *
 * @package Packlink\WooCommerce\Components\Tasks
 */
class Upgrade_Packlink_Order_Details extends Task {

	const INITIAL_PROGRESS_PERCENT = 5;
	const DEFAULT_BATCH_SIZE       = 200;

	/**
	 * Order shipment details service.
	 *
	 * @var OrderShipmentDetailsService
	 */
	private $order_shipment_details_service;
	/**
	 * Proxy instance.
	 *
	 * @var Proxy
	 */
	private $proxy;
	/**
	 * Batch size.
	 *
	 * @var int
	 */
	private $batch_size = self::DEFAULT_BATCH_SIZE;
	/**
	 * Current progress.
	 *
	 * @var int
	 */
	private $current_progress = self::INITIAL_PROGRESS_PERCENT;
	/**
	 * Total orders count.
	 *
	 * @var int
	 */
	private $total_orders_count;
	/**
	 * Start date timestamp.
	 *
	 * @var int
	 */
	private $start_date;
	/**
	 * Order ids.
	 *
	 * @var array
	 */
	private $order_ids;

	/**
	 * UpgradePacklinkOrderDetails constructor.
	 *
	 * @param array $order_ids Order ids.
	 */
	public function __construct( array $order_ids ) {
		/**
		 * Time provider.
		 *
		 * @var TimeProvider $time_provider
		 */
		$time_provider            = ServiceRegister::getService( TimeProvider::CLASS_NAME );
		$this->order_ids          = $order_ids;
		$this->total_orders_count = count( $order_ids );
		$this->start_date         = $time_provider->getDateTime( strtotime( '-60 days' ) )->getTimestamp();
	}

	/**
	 * @inheritDoc
	 */
	public static function fromArray( array $array ) {
		return new static( $array['order_ids'] );
	}

	/**
	 * @inheritDoc
	 */
	public function toArray() {
		return array( 'order_ids' => $this->order_ids );
	}

	/**
	 * String representation of object.
	 *
	 * @link https://php.net/manual/en/serializable.serialize.php
	 *
	 * @return string the string representation of the object or null.
	 * @since 5.1.0
	 */
	public function serialize() {
		return serialize(
			array(
				$this->batch_size,
				$this->order_ids,
				$this->total_orders_count,
				$this->current_progress,
				$this->start_date,
			)
		);
	}

	/**
	 * Constructs the object.
	 *
	 * @param string $serialized Serialized object string.
	 */
	public function unserialize( $serialized ) {
		list(
			$this->batch_size,
			$this->order_ids,
			$this->total_orders_count,
			$this->current_progress,
			$this->start_date,
		) = unserialize( $serialized );
	}

	/**
	 * Runs task logic.
	 */
	public function execute() {
		$this->reportProgress( $this->current_progress );
		$this->report_progress_when_no_order_ids();

		$count = count( $this->order_ids );
		while ( $count > 0 ) {
			$order_ids = $this->get_batch_order_ids();
			$this->reportAlive();

			foreach ( $order_ids as $order_id ) {
				$order     = \WC_Order_Factory::get_order( $order_id );
				$reference = get_post_meta( $order_id, '_packlink_draft_reference', true );
				if ( ! $order || ! $reference ) {
					continue;
				}

				$inactive    = $order->has_status( array( 'completed', 'failed', 'cancelled', 'refunded' ) );
				$modified_at = $order->get_date_modified();

				// Check if older than 60 days, if not fetch shipment details.
				$in_time_limit = $modified_at && $modified_at->getTimestamp() >= $this->start_date;
				if ( $in_time_limit && ! $inactive ) {
					try {
						$shipment = $this->get_proxy()->getShipment( $reference );
						if ( $shipment ) {
							$this->set_shipment_details( $order, $shipment );
						}
					} catch ( \Exception $e ) {
						Logger::logError( $e->getMessage(), 'Integration' );
					}
				} else {
					$order->update_meta_data( '_is_packlink_shipment', 'yes' );
					$order->update_meta_data( '_packlink_shipment_reference', $reference );
				}

				delete_post_meta( $order_id, '_packlink_draft_reference' );
			}

			// If batch is successful orders in batch should be removed.
			$this->remove_finished_batch();

			// If upload is successful progress should be reported for that batch.
			$this->report_progress_for_batch();

			$count = count( $this->order_ids );
		}

		$this->reportProgress( 100 );
	}

	/**
	 * Determines whether task can be reconfigured.
	 *
	 * @return bool TRUE if task can be reconfigured; otherwise, FALSE.
	 */
	public function canBeReconfigured() {
		return $this->batch_size > 1;
	}

	/**
	 * Reduces batch size.
	 *
	 * @throws HttpUnhandledException Thrown when batch size can't be reduced.
	 */
	public function reconfigure() {
		$batch_size = $this->batch_size;
		if ( $batch_size >= 100 ) {
			$this->batch_size -= 50;
		} elseif ( $batch_size > 10 && $batch_size < 100 ) {
			$this->batch_size -= 10;
		} elseif ( $batch_size > 1 && $batch_size <= 10 ) {
			-- $this->batch_size;
		} else {
			throw new HttpUnhandledException( 'Batch size can not be smaller than 1' );
		}
	}

	/**
	 * Report progress when there are no orders for sync
	 */
	private function report_progress_when_no_order_ids() {
		if ( count( $this->order_ids ) === 0 ) {
			$this->current_progress = 100;
			$this->reportProgress( $this->current_progress );
		}
	}

	/**
	 * Returns array of order ids that should be processed in this batch.
	 *
	 * @return array Batch of order ids.
	 */
	private function get_batch_order_ids() {
		return array_slice( $this->order_ids, 0, $this->batch_size );
	}

	/**
	 * Remove finished batch orders
	 */
	private function remove_finished_batch() {
		$this->order_ids = array_slice(
			$this->order_ids,
			$this->batch_size
		);
	}

	/**
	 * Report progress for batch
	 */
	private function report_progress_for_batch() {
		$synced = $this->total_orders_count - count( $this->order_ids );

		$progress_step = $synced * ( 100 - self::INITIAL_PROGRESS_PERCENT ) / $this->total_orders_count;

		$this->current_progress = self::INITIAL_PROGRESS_PERCENT + $progress_step;

		$this->reportProgress( $this->current_progress );
	}

	/**
	 * Sets order shipment details.
	 *
	 * @param \WC_Order $order Order object.
	 * @param Shipment  $shipment Shipment details.
	 *
	 * @throws \Packlink\BusinessLogic\OrderShipmentDetails\Exceptions\OrderShipmentDetailsNotFound
	 */
	private function set_shipment_details( \WC_Order $order, Shipment $shipment ) {
		if ( $this->set_reference( $order, $shipment->reference ) ) {
			$this->set_shipping_status( $shipment );
			$this->set_tracking_info( $shipment );
		}
	}

	/**
	 * Sets reference number for order.
	 *
	 * @param \WC_Order $order Order object.
	 * @param string    $reference Shipment reference number.
	 *
	 * @return bool Success flag.
	 */
	private function set_reference( \WC_Order $order, $reference ) {
		$order->update_meta_data( '_is_packlink_shipment', 'yes' );
		$order->save();

		$this->get_order_shipment_details_service()->setReference( (string)$order->get_id(), $reference );

		return true;
	}

	/**
	 * Sets order shipment status.
	 *
	 * @param Shipment $shipment Shipment details.
	 *
	 * @throws \Packlink\BusinessLogic\OrderShipmentDetails\Exceptions\OrderShipmentDetailsNotFound
	 */
	private function set_shipping_status( Shipment $shipment ) {
		$shipping_status = ShipmentStatus::getStatus( $shipment->status );
		$this->get_order_shipment_details_service()->setShippingStatus( $shipment->reference, $shipping_status );
	}

	/**
	 * Sets order shipment tracking info.
	 *
	 * @param Shipment $shipment Shipment details.
	 */
	private function set_tracking_info( Shipment $shipment ) {
		try {
			$tracking_info = $this->get_proxy()->getTrackingInfo( $shipment->reference );
			$this->get_order_shipment_details_service()->setTrackingInfo( $shipment, '', $tracking_info );
		} catch ( \Exception $e ) {
			Logger::logError( $e->getMessage(), 'Integration' );
		}
	}

	/**
	 * Returns order repository instance.
	 *
	 * @return OrderShipmentDetailsService Order repository.
	 */
	private function get_order_shipment_details_service() {
		if ( ! $this->order_shipment_details_service ) {
			$this->order_shipment_details_service = ServiceRegister::getService(
				OrderShipmentDetailsService::CLASS_NAME
			);
		}

		return $this->order_shipment_details_service;
	}

	/**
	 * Returns proxy instance.
	 *
	 * @return Proxy Proxy instance.
	 */
	private function get_proxy() {
		if ( ! $this->proxy ) {
			$this->proxy = ServiceRegister::getService( Proxy::CLASS_NAME );
		}

		return $this->proxy;
	}
}
