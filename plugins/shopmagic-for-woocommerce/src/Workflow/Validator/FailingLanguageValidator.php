<?php

declare(strict_types=1);

namespace WPDesk\ShopMagic\Workflow\Validator;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;

/**
 * When multilang module is not enabled, but automation is designed to
 * targed specific language, fail validation.
 */
final class FailingLanguageValidator extends WorkflowValidator {

	/** @var PersistentContainer */
	private $modules;

	/** @var LoggerInterface */
	private $logger;


	public function __construct( PersistentContainer $modules, LoggerInterface $logger ) {
		$this->modules = $modules;
		$this->logger  = $logger;
	}

	public function valid( DataLayer $resources = null ): bool {
		$resources = $resources ?? $this->resources;
		if ( $resources === null ) {
			return false;
		}

		if ( $this->modules->has( 'multilingual-module' ) ) {
			return parent::valid( $resources );
		}

		if ( ! $resources->has( Automation::class ) ) {
			return parent::valid( $resources );
		}

		$this->logger->debug( 'Checking if automation has language settings, when multilingual module disabled.' );

		$automation = $resources->get( Automation::class );

		if ( $automation->has_language() ) {
			$this->logger->notice(
				'Automation targets specific language ({language}), but mulitilingual module is disabled. Preventing execution.',
				[ 'language' => $automation->get_language() ]
			);
			return false;
		}

		$this->logger->debug( 'No language settings for automation #{id} detected, while multilingual module is disabled.', [ 'id' => $automation->get_id() ] );
		return parent::valid( $resources );
	}
}
