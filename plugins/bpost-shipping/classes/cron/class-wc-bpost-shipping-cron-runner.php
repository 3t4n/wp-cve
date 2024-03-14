<?php

namespace WC_BPost_Shipping\Cron;

use WC_BPost_Shipping\Adapter\WC_BPost_Shipping_Adapter_Woocommerce;
use WC_BPost_Shipping\Options\WC_BPost_Shipping_Options_Base;

/**
 * Class WC_BPost_Shipping_Cron_Runner handles cleaning of bpost attachments after a certain amount of time
 * @package WC_BPost_Shipping\Cron
 */
class WC_BPost_Shipping_Cron_Runner {
	/** @var WC_BPost_Shipping_Adapter_Woocommerce */
	private $adapter;
	/** @var WC_BPost_Shipping_Options_Base */
	private $options;


	/**
	 * WC_BPost_Shipping_Cron_Runner constructor.

	 *
*@param WC_BPost_Shipping_Adapter_Woocommerce $adapter
	 * @param WC_BPost_Shipping_Options_Base $options
	 */
	public function __construct( WC_BPost_Shipping_Adapter_Woocommerce $adapter, WC_BPost_Shipping_Options_Base $options ) {
		$this->adapter = $adapter;
		$this->options = $options;
	}

	/**
	 * Run the cleaning
	 * @return int nb posts removed by the process (-1 if cleaning is disabled)
	 */
	public function execute() {
		$expiration_date = $this->get_expiration_date_from_options();

		if ( ! $expiration_date ) { //no cleaning requested, quit
			return -1;
		}

		$nb_cleaned = 0;

		foreach ( $this->get_bpost_attachments() as $post ) {
			//they are sorted, so can quit the loop
			if ( $this->extract_datetime( $post ) >= $expiration_date ) {
				break;
			}

			$nb_cleaned++;
			$this->adapter->wp_delete_attachment( $post->ID, true );
		}

		return $nb_cleaned;
	}

	/**
	 * Build request/filter array for the get_posts call
	 * only pdf attachement with the tag bpost and ordered by date (oldest first)
	 * @return string[]
	 */
	private function build_request_params() {
		return array(
			'post_type'      => 'attachment',
			'post_mime_type' => 'application/pdf',
			'tag'            => 'bpost',
			'orderby'        => 'date',
			'order'          => 'ASC',
			'numberposts'    => 20,
			'post_status'    => 'any',
		);
	}

	/**
	 * Return all bpost attachements @see build_request_params to get more data on filtering
	 * @return \WP_Post[]
	 */
	private function get_bpost_attachments() {
		return $this->adapter->get_posts( $this->build_request_params() );
	}

	/**
	 * Retrieve the cache time string from option (It is a DateInterval string or empty to disable it)
	 * And compute the date to
	 * @return \DateTime|false if $date interval is not valid
	 */
	public function get_expiration_date_from_options() {

		$label_cache_time = $this->options->get_label_cache_time();
		if ( ! $label_cache_time ) {
			return false;
		}

		$date_interval = new \DateInterval( $label_cache_time );
		$date_now      = new \DateTime();

		return $date_now->sub( $date_interval );
	}

	/**
	 *
	 * @param \WP_Post $post
	 *
	 * @return \DateTime
	 */
	private function extract_datetime( \WP_Post $post ) {
		return new \DateTime( $post->post_date );
	}
}
