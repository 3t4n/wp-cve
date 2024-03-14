<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\HookEmitter;

use ShopMagicVendor\Psr\Log\LoggerAwareInterface;
use ShopMagicVendor\Psr\Log\LoggerAwareTrait;
use ShopMagicVendor\Psr\Log\LoggerInterface;
use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectPersister;

/**
 * Allows deleting items from database on recurring schedule;
 */
abstract class RecurringCleaner implements Hookable, LoggerAwareInterface {

	use LoggerAwareTrait;

	/** @var string */
	protected const DEFAULT_EXPIRATION_TIME = '-30 days';

	/** @var ObjectPersister */
	protected $persister;

	final public function __construct(
		ObjectPersister $persister,
		LoggerInterface $logger
	) {
		$this->persister = $persister;
		$this->logger    = $logger;
	}

	final public function hooks(): void {
		add_action(
			'shopmagic/core/cron/weekly',
			function (): void {
				$this->clean_resources();
			}
		);
	}

	/**
	 * @throws \Exception
	 * @internal
	 */
	final public function clean_resources(): void {
		global $wpdb;
		$items_to_clean = $this->get_items_to_clean();
		if ( $items_to_clean->is_empty() ) {
			return;
		}

		$wpdb->query( 'START TRANSACTION' ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
		foreach ( $items_to_clean as $item ) {
			$this->persister->delete( $item );
		}

		$this->post_clean_hook( $items_to_clean );
		$wpdb->query( 'COMMIT' ); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
	}

	/** @return iterable<object> */
	abstract protected function get_items_to_clean(): iterable;

	/**
	 * Overwrite this method in child class if you need to perform additional actions.
	 * I.e. cleaning meta table associated with main table.
	 * Hook if fired before database transaction commit.
	 *
	 * @param iterable<object> $items
	 */
	protected function post_clean_hook( iterable $items ): void {
	}
}
