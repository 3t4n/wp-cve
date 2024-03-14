<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Workflow\Components\Filters;

use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;
use WPDesk\ShopMagic\Workflow\Filter\Builtin\CustomerFilter;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\ComparisonType;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\SelectManyToManyType;

final class CustomerNotSubscribedToListFilter extends CustomerFilter {

	/** @var SubscriberObjectRepository */
	private $repository;

	/** @var AudienceListRepository */
	private $list_repository;

	public function __construct(
		SubscriberObjectRepository $repository,
		AudienceListRepository $list_repository
	) {
		$this->repository      = $repository;
		$this->list_repository = $list_repository;
	}

	public function get_id(): string {
		return 'customer_not_subscribed';
	}

	public function get_name(): string {
		return esc_html__( 'Customer - Not Subscribed to List', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return esc_html__( 'Run automation if customer is not subscribed to selected marketing list.', 'shopmagic-for-woocommerce' );
	}

	public function passed(): bool {
		if ( ! $this->is_customer_provided() ) {
			$this->logger->warning( 'No customer provided for class ' . self::class );

			return false;
		}

		$not_subscribed_lists = [];

		foreach ( $this->fields_data->get( SelectManyToManyType::VALUE_KEY ) as $list_id ) {
			if ( ! $this->repository->is_subscribed_to_list( $this->get_customer()->get_email(), (int) $list_id ) ) {
				$not_subscribed_lists[] = $list_id;
			}
		}

		return $this->get_type()->passed(
			$this->fields_data->get( SelectManyToManyType::VALUE_KEY ),
			$this->fields_data->get( SelectManyToManyType::CONDITION_KEY ),
			$not_subscribed_lists
		);
	}

	protected function get_type(): ComparisonType {
		return new SelectManyToManyType( $this->list_repository->get_as_select_options() );
	}

}
