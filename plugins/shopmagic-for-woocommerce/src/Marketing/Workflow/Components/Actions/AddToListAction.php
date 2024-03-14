<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Workflow\Components\Actions;

use ShopMagicVendor\WPDesk\Forms\Field\CheckboxField;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\Exception\CannotModifyList;
use WPDesk\ShopMagic\Exception\CannotProvideItemException;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ConfirmationDispatcher;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SingleListSubscriber;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SingleListSubscriberHydrator;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriptionManager;

final class AddToListAction extends AbstractListAction {

	/** @var AudienceListRepository */
	private $marketing_list_repository;

	/** @var ConfirmationDispatcher */
	private $confirmation_dispatcher;

	/** @var SingleListSubscriberHydrator */
	protected $hydrator;

	public function __construct(
		AudienceListRepository $marketing_list_manager,
		SubscriptionManager $manager,
		SingleListSubscriberHydrator $factory,
		ConfirmationDispatcher $confirmation_dispatcher
	) {
		parent::__construct( $manager, $marketing_list_manager );
		$this->marketing_list_repository = $marketing_list_manager;
		$this->confirmation_dispatcher   = $confirmation_dispatcher;
		$this->hydrator                  = $factory;
	}

	public function get_id(): string {
		return 'shopmagic_add_to_list_action';
	}

	public function get_name(): string {
		return esc_html__( 'Add E-mail to List', 'shopmagic-for-woocommerce' );
	}

	public function get_description(): string {
		return esc_html__( 'Add customer email to selected marketing list. Also features double opt-in option.', 'shopmagic-for-woocommerce' );
	}

	/**
	 * @return \ShopMagicVendor\WPDesk\Forms\Field[]
	 */
	public function get_fields(): array {
		return array_merge(
			parent::get_fields(),
			[
				( new CheckboxField() )
					->set_name( 'doubleoptin' )
					->set_label( esc_html__( 'Double opt-in', 'shopmagic-for-woocommerce' ) ),
			]
		);
	}

	/**
	 * TODO: refactor whole abstract method (with signature). Actual customer and list should be
	 * passed
	 */
	protected function do_list_action( string $email, int $list_id, string $list_name ): bool {
		try {
			/** @var SingleListSubscriber $customer_opt */
			$customer_opt = $this->repository->get_subscribed_to_list( $email, $list_id );
		} catch ( CannotProvideItemException $cannotProvideItemException ) {
			$customer_opt = $this->hydrator->create_for_email_and_list( $email, $list_id );
		}

		if ( $customer_opt->get_id() > 0 && $customer_opt->is_active() ) {
			throw new CannotModifyList(
				sprintf(
				// translators: %1$s Customer email, %2$s Marketing List name.
					esc_html__( 'Customer email %1$s is already subscribed to this list: %2$s.',
						'shopmagic-for-woocommerce' ),
					$email,
					$list_name
				)
			);
		}

		if ( $this->fields_data->has( 'doubleoptin' ) ) {
			if (
				in_array( $this->fields_data->get( 'doubleoptin' ), [
					CheckboxField::VALUE_TRUE,
					'1',
				] ) &&
				$this->resources->has( Customer::class )
			) {
				$target_list = $this->marketing_list_repository->find( $list_id );
				$this->confirmation_dispatcher
					->dispatch_confirmation_email(
						$this->resources->get( Customer::class ),
						$target_list
					);

				return true;
			}

		}

		$customer_opt->set_active( true );
		$this->manager->save( $customer_opt );

		if ( $this->logger !== null ) {
			$this->logger->info(
				sprintf(
				// translators: %1$s Customer email, %2$s Marketing List name.
					esc_html__( 'Customer email %1$s successfully added to list: %2$s.', 'shopmagic-for-woocommerce' ),
					$email,
					$list_name
				)
			);
		}

		return true;
	}
}
