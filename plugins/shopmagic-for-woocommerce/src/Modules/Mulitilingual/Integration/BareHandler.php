<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Modules\Mulitilingual\Integration;

use WPDesk\ShopMagic\Components\Collections\ArrayCollection;
use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Modules\Mulitilingual\Language;
use WPDesk\ShopMagic\Workflow\Automation\Automation;

class BareHandler implements \WPDesk\ShopMagic\Modules\Mulitilingual\LanguageHandler {

	/** @var ObjectRepository<Automation> */
	private $repository;

	/**
	 * @param ObjectRepository<Automation> $repository
	 */
	public function __construct( ObjectRepository $repository ) {
		$this->repository = $repository;
	}

	public function default_language(): Language {
		return new Language( get_bloginfo( 'language' ) );
	}

	/**
	 * @return Language[]
	 */
	public function supported_languages(): array {
		return $this->repository
			->find_by( [ 'post_parent' => null ] )
			->reduce(
				static function ( ArrayCollection $languages, Automation $automation ): ArrayCollection {
					if ( $automation->get_language() !== null ) {
						$languages[] = new Language( $automation->get_language() );
					}

					return $languages;
				},
				// By default, we push site's default language to the list.
				new ArrayCollection( [ $this->default_language() ] )
			)
			->unique()
			->to_array();
	}
}
