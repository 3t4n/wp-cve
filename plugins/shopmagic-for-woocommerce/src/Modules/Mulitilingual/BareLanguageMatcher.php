<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Modules\Mulitilingual;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Workflow\Automation\Automation;

class BareLanguageMatcher implements LanguageMatcher {

	/** @var ObjectRepository<Automation> */
	private $repository;

	/**
	 * @param ObjectRepository<Automation> $repository
	 */
	public function __construct( ObjectRepository $repository ) {
		$this->repository = $repository;
	}

	/**
	 * To check if our automation matches customer target language we need to take two aspects
	 * under consideration.
	 * 1. If our automation is child automation, we are safe to check for automation language, as
	 * there are no nested children in automations and our target language is exhausted by that
	 * check.
	 * 2. More complex situation is when we need to determine if default automation should be run.
	 * As validator has no knowledge about validation order (i.e. child automation may be
	 * validated after parent automation), we need to fetch all children automations and match
	 * target language against each of them -- only through that exhaustive verification we will
	 * be able to determine if default automation applies.
	 *
	 * @param Automation $automation
	 * @param Language   $language
	 *
	 * @return bool
	 */
	public function matches( Automation $automation, Language $language ): bool {
		if ( $automation->has_parent() ) {
			if ( ! $automation->has_language() ) {
				return false;
			}

			return $language->equals( new Language( $automation->get_language() ) );
		}

		if ( $this->seek_matching_child( $automation, $language ) === null ) {
			return true;
		}

		return false;
	}

	/**
	 * @param Automation $automation
	 * @param Language   $language
	 *
	 * @return Automation|null
	 */
	public function seek_matching_child( Automation $automation, Language $language ): ?Automation {
		return $this->repository
			->find_by( [ 'post_parent' => $automation->get_id() ] )
			->find_first(
				function ( $_, Automation $automation ) use ( $language ): bool {
					return $this->matches( $automation, $language );
				}
			);
	}
}
