<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use WPDesk\ShopMagic\Customer\Guest\GuestRepository;
use WPDesk\ShopMagic\Database\DatabaseTable;
use WPDesk\ShopMagic\Helper\DateIterator;
use WPDesk\ShopMagic\Helper\WordPressFormatHelper;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Workflow\Queue\ActionSchedulerQueue;
use WPDesk\ShopMagic\Workflow\Queue\Queue;

class StatisticsController {
	public function outcomes(): \WP_REST_Response {
		global $wpdb;
		$table_name = DatabaseTable::automation_outcome();
		$cutoff     = ( new \DateTime() )->sub( new \DateInterval( 'P30D' ) )->format( WordPressFormatHelper::MYSQL_DATE_FORMAT );
		$outcome    = $wpdb->get_results( "
			SELECT
			    count(*) as count,
			    DATE(created) as date
			FROM $table_name
			WHERE success = 1
			AND created > '$cutoff'
			GROUP BY DATE(created)",
			ARRAY_A );

		$stats = array_fill_keys( $this->get_labels(), 0 );

		foreach ( $outcome as $item ) {
			if ( isset( $stats[ $item['date'] ] ) ) {
				$stats[ $item['date'] ] = (int) $item['count'];
			}
		}

		return new \WP_REST_Response( [
			'labels' => array_keys( $stats ),
			'plot'   => array_values( $stats ),
		] );
	}

	private function get_labels(): array {
		return array_map(
			static function ( \DateTimeInterface $date ): string {
				return $date->format( WordPressFormatHelper::MYSQL_DATE_FORMAT );
			},
			iterator_to_array( $this->get_date_iterator() )
		);
	}

	private function get_date_iterator(): DateIterator {
		return new DateIterator();
	}

	public function email( \wpdb $wpdb ): \WP_REST_Response {
		$emails_table = DatabaseTable::tracked_emails();
		$cutoff       = ( new \DateTime() )->sub( new \DateInterval( 'P30D' ) )->format( WordPressFormatHelper::MYSQL_DATE_FORMAT );
		$mails        = $wpdb->get_results( "
			select count(*) as mails,
			       count(opened_at) as opened,
			       DATE(dispatched_at) as date
			from $emails_table
			WHERE dispatched_at > '$cutoff'
			group by date(dispatched_at)",
			ARRAY_A );
		$click_table  = DatabaseTable::tracked_emails_clicks();
		$clicks       = $wpdb->get_results( "
			select count(*) as clicks,
			       DATE(clicked_at) as date
			from $click_table
			WHERE clicked_at > '$cutoff'
			GROUP BY DATE(clicked_at)",
			ARRAY_A );

		$stats = array_fill_keys( $this->get_labels(), [ 'sent' => 0, 'opens' => 0, 'clicks' => 0 ] );

		foreach ( $mails as $mail ) {
			if ( isset( $stats[ $mail['date'] ] ) ) {
				$stats[ $mail['date'] ]['opens'] = (int) $mail['opened'];
				$stats[ $mail['date'] ]['sent']  = (int) $mail['mails'];
			}
		}

		foreach ( $clicks as $click ) {
			if ( isset( $stats[ $click['date'] ] ) ) {
				$stats[ $click['date'] ]['clicks'] = (int) $click['clicks'];
			}
		}

		return new \WP_REST_Response( [
			'labels' => array_keys( $stats ),
			'plot'   => array_values( $stats ),
		] );
	}

	public function top_stats(
		Queue $queue,
		GuestRepository $guests_repository,
		AudienceListRepository $subscribers_repository
	): \WP_REST_Response {
		$statistics = [
			[
				'name'  => esc_html__( 'Automations queued', 'shopmagic-for-woocommerce' ),
				'value' => $this->get_queued( $queue ),
				'tooltip' => esc_html__('Total number of emails and automations queued to be sent.', 'shopmagic-for-woocommerce')
			],
			[
				'name'  => esc_html__( 'Active carts', 'shopmagic-for-woocommerce' ),
				'value' => 0,
				'tooltip' => esc_html__('Sum of all active carts in your store at the moment.', 'shopmagic-for-woocommerce')
			],
			[
				'name'  => esc_html__( 'Guests captured', 'shopmagic-for-woocommerce' ),
				'value' => $guests_repository->get_count(),
				'tooltip' => esc_html__('Sum of unregistred users, recorded by ShopMagic.', 'shopmagic-for-woocommerce')
			],
			[
				'name'  => esc_html__( 'Optins', 'shopmagic-for-woocommerce' ),
				'value' => count( $subscribers_repository->find_by( [ 'active' => 1 ] ) ),
				'tooltip' => esc_html__('Sum of all users, who have subscribed to your lists.', 'shopmagic-for-woocommerce')
			],
		];

		$statistics = apply_filters( 'shopmagic/core/statistics/top_stats', $statistics );

		return new \WP_REST_Response( [
			'top_stats' => $statistics,
		] );
	}

	private function get_queued( Queue $queue ): int {
		return count( $queue->search( [
			'group'    => ActionSchedulerQueue::GROUP,
			'status'   => \ActionScheduler_Store::STATUS_PENDING,
			'per_page' => - 1,
		] ) );
	}

}
