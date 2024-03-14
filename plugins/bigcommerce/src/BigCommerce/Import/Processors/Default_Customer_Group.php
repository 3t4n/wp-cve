<?php

namespace BigCommerce\Import\Processors;

use Bigcommerce\Api\Client;
use BigCommerce\Api\v3\Api\PriceListsApi;
use BigCommerce\Api\v3\Model\PriceListAssignment;
use BigCommerce\Logging\Error_Log;
use BigCommerce\Taxonomies\Channel\Channel;
use BigCommerce\Taxonomies\Channel\Connections;

class Default_Customer_Group {

	const DEFAULT_GROUP = 'bigcommerce_customers_default_group';

	/**
	 * @var \BigCommerce\Api\v3\Api\PriceListsApi
	 */
	private $price_list_api;

	public function __construct( PriceListsApi $price_list_api ) {
		$this->price_list_api = $price_list_api;
	}

	public function run() {
		$default_group = $this->get_default_group();

		if ( empty( $default_group ) ) {
			$default_group = 0;
		}

		update_option( self::DEFAULT_GROUP, $default_group );
	}

	/**
	 * Retrieve group_ids from Price Collection Assignments and get first default customer group
	 *
	 * @return false|mixed
	 */
	protected function get_default_group() {
		try {
			$connections = new Connections();
			$channel     = $connections->current();
			$channel_id  = get_term_meta( $channel->term_id, Channel::CHANNEL_ID, true);
			$assignments = $this->price_list_api->getPriceListCollectionAssignments( [
				'channel_id' => $channel_id
			] )->getData();

			if ( empty( $assignments ) ) {
				do_action( 'bigcommerce/log', Error_Log::INFO, __( 'Channel does not have price list assignments', 'bigcommerce' ), [
					'channel_id' => $channel_id
				] );
				return null;
			}

			$group_ids = array_filter( array_map( function ( PriceListAssignment $assignment ) {
				return $assignment->getCustomerGroupId();
			}, $assignments) );

			$default_groups = $this->get_default_customer_groups();

			if ( empty( $default_groups ) ) {
				return null;
			}

			// Trying to get assigned group
			foreach ( $default_groups as $id ) {
				if ( in_array( $id, $group_ids ) ) {
					return $id;
				}
			}

			// Return 1st default group in case everything else is empty
			return reset( $default_groups );
		} catch ( \Throwable $exception ) {
			do_action( 'bigcommerce/log', Error_Log::DEBUG, __( 'Could not retrieve default group', 'bigcommerce' ), [
				'message' => $exception->getMessage(),
				'trace'   => $exception->getTraceAsString(),
			] );
			return null;
		}
	}

	/**
	 * @return array|null
	 */
	protected function get_default_customer_groups() {
		$customer_groups = Client::getCustomerGroups();

		if ( empty( $customer_groups ) ) {
			do_action( 'bigcommerce/log', Error_Log::INFO, __( 'Customer groups are empty', 'bigcommerce' ), [] );
			return null;
		}

		return array_filter( array_map( function ( $group ) {
			// Exit if group is not default
			if ( ! $group->is_default ) {
				return;
			}

			return $group->id;
		}, $customer_groups ) );
	}

}
