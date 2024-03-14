<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Automation;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use ShopMagicVendor\Psr\Log\NullLogger;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Filter\Filter;
use WPDesk\ShopMagic\Workflow\Filter\FilterLogic;

/**
 * @implements \IteratorAggregate<int, Filter[]>
 * Receives two level deep array of filters and can apply group logic to these filters.
 */
final class AutomationFiltersGroup implements FilterLogic, \IteratorAggregate {

	/** @var Filter[][] */
	private $filters;

	/** @var LoggerInterface */
	protected $logger;

	/**
	 * @param Filter[][] $filters Filters matrix. Outer array is for OR conditionals.
	 *                            [$or_condition_index][$and_condition_index] = Filter instance
	 */
	public function __construct( array $filters = [], LoggerInterface $logger = null ) {
		$this->filters = array_values( $filters );
		$this->logger  = $logger ?? new NullLogger();
	}

	/**
	 * Kept for compatibility with LoggerAwareInterface
	 *
	 * @deprecated 3.0.13 Set logger directly in constructor.
	 */
	public function setLogger( LoggerInterface $logger ): void {
		$this->logger = $logger;
	}

	public function get_filters(): array {
		return $this->filters;
	}

	/**
	 * @return mixed[]
	 */
	public function get_required_data_domains(): array {
		return [];
	}

	public function set_provided_data( DataLayer $resources ): void {
		foreach ( $this->filters as $or_group ) {
			foreach ( $or_group as $filter ) {
				$filter->set_provided_data( $resources );
			}
		}
	}

	public function passed(): bool {
		if ( empty( $this->filters ) ) {
			$this->logger->debug( 'No filters configured for the automation.' );

			return true;
		}

		$this->logger->debug( 'Validating if event passes filters conditions.' );
		foreach ( $this->filters as $k => $or_group ) {
			$this->logger->debug( sprintf( 'Validating #%d set of filters...', $k ) );
			$or_success = false;
			foreach ( $or_group as $filter ) {
				$this->logger->debug( sprintf( 'Running filter %s from group #%s.', get_class( $filter ), $k ) );
				$or_success = true;
				try {
					/** @var FilterLogic $filter */
					if ( ! $filter->passed() ) {
						$this->logger->debug( sprintf( 'Filter %s from group #%s failed.', get_class( $filter ), $k ) );
						$or_success = false;
						break;
					}
				} catch ( \Exception $exception ) {
					$this->logger->notice(
						sprintf( 'Event did not pass filters conditions due to `\Exception` in code. Direct reason: %s', $exception->getMessage() ),
						$exception->getTrace()
					);
					$or_success = false;
				}
				$this->logger->debug( sprintf( 'Filter %s from group #%s succeeded.', get_class( $filter ), $k ) );
			}

			if ( $or_success ) {
				$this->logger->debug( 'Event passed filters conditions. Ready to register action.' );

				return true;
			}
		}

		$this->logger->debug( 'Event did not pass filters conditions.' );

		return false;
	}

	/**
	 * @return \ArrayIterator<int, Filter[]>
	 */
	public function getIterator(): \ArrayIterator {
		return new \ArrayIterator( $this->filters );
	}
}
