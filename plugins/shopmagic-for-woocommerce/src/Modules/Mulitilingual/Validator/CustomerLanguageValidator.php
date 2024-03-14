<?php

namespace WPDesk\ShopMagic\Modules\Mulitilingual\Validator;

use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Modules\Mulitilingual\Language;
use WPDesk\ShopMagic\Modules\Mulitilingual\LanguageMatcher;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Validator\WorkflowValidator;

/**
 * Match Customer language against current automation language.
 */
final class CustomerLanguageValidator extends WorkflowValidator {

	/** @var LanguageMatcher */
	private $matcher;

	public function __construct( LanguageMatcher $matcher ) {
		$this->matcher = $matcher;
	}

	/**
	 * @param $resources DataLayer|null
	 */
	public function valid( DataLayer $resources = null ): bool {
		if ( $resources === null ) {
			@trigger_error(
				sprintf(
					'Calling %s without arguments is deprecated since 3.0.13 and will be removed in 4.0.0. Pass %s as argument instead.',
					__CLASS__ . '::' . __METHOD__,
					DataLayer::class
				),
				E_USER_DEPRECATED
			);
			// We assume that DataLayer is already passed to this validator.
			$resources = $this->resources;
		}

		if ( $resources === null ) {
			return false;
		}

		if ( ! $resources->has( Customer::class ) || ! $resources->has( Automation::class ) ) {
			return parent::valid( $resources );
		}

		$customer   = $resources->get( Customer::class );
		$automation = $resources->get( Automation::class );

		if (
			$this->matcher->matches( $automation, new Language( $customer->get_language() ) )
		) {
			return parent::valid( $resources );
		}

		return false;
	}
}
