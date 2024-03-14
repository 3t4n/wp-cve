<?php


namespace BigCommerce\Taxonomies\Channel;

use BigCommerce\Exceptions\Channel_Not_Found_Exception;
use BigCommerce\Logging\Error_Log;

class Connections {

	/**
	 * Get the channel set as primary
	 *
	 * @return \WP_Term
	 */
	public function primary() {
		$terms = get_terms( [
			'taxonomy'   => Channel::NAME,
			'meta_query' => [
				[
					'key'   => Channel::STATUS,
					'value' => Channel::STATUS_PRIMARY,
				],
			],
			'hide_empty' => false,
			'number'     => 1,
		] );
		if ( empty( $terms ) ) {
			throw new Channel_Not_Found_Exception( __( 'Primary channel is not set.', 'bigcommerce' ) );
		}

		return reset( $terms );
	}

	/**
	 * Get the channel currently in play
	 *
	 * @return \WP_Term
	 */
	public function current() {
		$channel = $this->primary();
		if ( ! Channel::multichannel_enabled() ) {
			return $channel;
		}

		/**
		 * Filter the channel to use for the current request. This only
		 * fires if multi-channel support is enabled.
		 *
		 * @see bigcommerce/channel/enable-multi-channel
		 *
		 * @param \WP_Term $channel The WP term associated with the BigCommerce channel
		 */
		return apply_filters( 'bigcommerce/channel/current', $channel );
	}


	/**
	 * @return \WP_Term[]
	 */
	public function active() {
		if ( ! Channel::multichannel_enabled() ) {
			try {
				return [ $this->primary() ];
			} catch ( Channel_Not_Found_Exception $e ) {
				return [];
			}
		}

		$args = [
			'taxonomy'   => Channel::NAME,
			'meta_query' => [
				[
					'key'     => Channel::STATUS,
					'value'   => [ Channel::STATUS_PRIMARY, Channel::STATUS_CONNECTED ],
					'compare' => 'IN',
				],
			],
			'hide_empty' => false,
			'orderby'    => 'name',
		];

		$terms = get_terms( $args );

		if ( ! is_array( $terms ) ) {
			return [];
		}

		return $terms;
	}

	/**
	 * @return int|mixed
	 */
	public function get_primary_channel_id() {
		try {
			$term       = $this->primary();
			$channel_id = get_term_meta( $term->term_id, Channel::CHANNEL_ID, true );

			if ( empty( $channel_id ) || is_wp_error( $channel_id) ) {
				return 0;
			}

			return $channel_id;
		} catch ( \Throwable $exception ) {
			do_action( 'bigcommerce/log', Error_Log::ERROR, __( 'Could not retrieve primary channel id', 'bigcommerce' ), [
				'code'    => $exception->getCode(),
				'message' => $exception->getMessage(),
			] );
			return 0;
		}
	}
}
