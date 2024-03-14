<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Modules\Mulitilingual\Marketing;

use WPDesk\ShopMagic\Components\Collections\Collection;
use WPDesk\ShopMagic\Customer\CustomerProvider;
use WPDesk\ShopMagic\Exception\CannotProvideCustomerException;
use WPDesk\ShopMagic\Frontend\Interceptor\CurrentCustomer;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceList;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListFactory;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;

class MultilingualAudienceListRepository extends AudienceListRepository {

	/** @var CustomerProvider */
	private $inteceptor;

	public function __construct( AudienceListFactory $factory, CurrentCustomer $interceptor ) {
		parent::__construct( $factory );
		$this->inteceptor = $interceptor;
	}

	/** @return Collection<int, AudienceList> */
	public function find_checkout_viewable_items(): Collection {
		try {
			$language = substr( $this->inteceptor->get_customer()->get_language(), 0, 2 );
		} catch ( CannotProvideCustomerException $e ) {
			$language = get_bloginfo( 'language' );
		}

		return $this->find_by( [
			'post_status' => 'publish',
			'meta_query'  => [
				'relation' => 'AND',
				[
					'key'   => 'checkout_available',
					'value' => '1',
				],
				[
					'key'   => 'type',
					'value' => AudienceList::TYPE_OPTIN,
				],
				[
					'key'     => 'lang',
					'value'   => $language,
					'compare' => 'LIKE',
				],
			],
		] );
	}

}
