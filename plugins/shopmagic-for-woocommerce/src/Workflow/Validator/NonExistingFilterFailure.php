<?php

declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Validator;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Filter\NullFilter;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;

/**
 * If user has automation with filters which are not valid (e.g. from
 * deactivated plugin), fail automation.
 */
final class NonExistingFilterFailure extends WorkflowValidator {

	/** @var LoggerInterface */
	private $logger;

	public function __construct( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	public function valid( DataLayer $resources = null ): bool {
		$resources = $resources ?? $this->resources;
		if ( $resources === null ) {
			return false;
		}

		if ( ! $resources->has( Automation::class ) ) {
			return parent::valid( $resources );
		}

		$this->logger->debug( "Checking if automation's filters are available." );

		$automation = $resources->get( Automation::class );
		$filters    = $automation->get_filters_group();

		foreach ( $filters as $and_cluster ) {
			foreach ( $and_cluster as $filter ) {
				if ( $filter instanceof NullFilter ) {
					$this->logger->notice(
						'Automation contains at least one filter which is unavailable at the moment: {filter}. Preventing execution.',
						[ 'filter' => $filter->get_id() ]
					);
					return false;
				}
			}
		}

		$this->logger->debug(
			'All filters used in automation #{id} are available.',
			[ 'id' => $automation->get_id() ]
		);
		return parent::valid( $resources );
	}
}
