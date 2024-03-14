<?php

namespace Dropp\Actions;

use Dropp\Models\Dropp_Consignment;
use Dropp\Models\Dropp_Location;
use Dropp\Order_Adapter;

/**
 * Get Consignment from API
 */
class Convert_Dropp_Order_Ids_To_Consignments_Action {
	public Order_Adapter $adapter;

	/**
	 * Construct
	 *
	 * @param Order_Adapter $adapter Order adapter.
	 */
	public function __construct( Order_Adapter $adapter ) {
		$this->adapter = $adapter;
	}

	/**
	 * Handle
	 */
	public function handle() {
		$shipping_items = $this->adapter->get_shipping_items();

		if ( $this->adapter->count_consignments( true ) > 0 ) {
			return;
		}

		foreach ( $shipping_items as $shipping_item ) {
			if ( empty( $shipping_item->get_id() ) ) {
				continue;
			}
			$dropp_order_ids = $shipping_item->get_meta( 'dropp_consignments' );

			if ( empty( $dropp_order_ids ) ) {
				continue;
			}
			foreach ( $dropp_order_ids as $dropp_order_id ) {
				try {
					$consignment = Dropp_Consignment::remote_find(
						$shipping_item->get_id(),
						$dropp_order_id
					);

					if ( ! $consignment ) {
						continue;
					}

					$location = Dropp_Location::remote_find( $consignment->location_id );
					if ( $location ) {
						$shipping_item->add_meta_data( 'dropp_location', $location->to_array(), true );
						$shipping_item->save();
					}

					$consignment->save();
				} catch ( \Exception $e ) {
					// Silent fail.
				}
			}
		}
	}
}
