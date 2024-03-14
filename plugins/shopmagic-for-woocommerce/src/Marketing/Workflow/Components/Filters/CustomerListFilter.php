<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Workflow\Components\Filters;

use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;
use WPDesk\ShopMagic\Workflow\Filter\Builtin\CustomerFilter;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\ComparisonType;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\SelectManyToManyType;


final class CustomerListFilter extends CustomerFilter {

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
		return 'customer_communication_type';
	}

	public function get_name(): string {
		return __( 'Customer - Subscribed to List', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return esc_html__( 'Run automation if customer is subscribed to selected marketing list.', 'shopmagic-for-woocommerce' );
	}

	public function passed(): bool {
		if ( ! $this->is_customer_provided() ) {
			$this->logger->warning( 'No customer provided for class ' . self::class );

			return false;
		}

		$active_subscriptions = $this->repository->find_by(
			[
				'email'  => $this->get_customer()->get_email(),
				'active' => '1',
			]
		);

		$active_ids = [];
		foreach ( $active_subscriptions as $active ) {
			$active_ids[] = $active->get_list_id();
		}

		return $this->get_type()->passed(
			$this->fields_data->get( SelectManyToManyType::VALUE_KEY ),
			$this->fields_data->get( SelectManyToManyType::CONDITION_KEY ),
			$active_ids
		);
	}

	protected function get_type(): ComparisonType {
		return new SelectManyToManyType( $this->list_repository->get_as_select_options() );
	}

}
