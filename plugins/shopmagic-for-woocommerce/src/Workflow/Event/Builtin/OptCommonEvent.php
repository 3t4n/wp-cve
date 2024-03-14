<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Event\Builtin;

use WPDesk\ShopMagic\Components\HookProvider\HookTrait;
use WPDesk\ShopMagic\Customer\Customer;
use WPDesk\ShopMagic\FormField\Field\SelectField;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SingleListSubscriber;
use WPDesk\ShopMagic\Workflow\Event\ManualGlobalEvent;


abstract class OptCommonEvent extends UserCommonEvent implements ManualGlobalEvent {
	use HookTrait;

	/** @var string */
	private const PARAM_COMMUNICATION_TYPE = 'communication_type';

	/** @var AudienceListRepository */
	private $repository;

	public function __construct( AudienceListRepository $repository ) {
		$this->repository = $repository;
	}

	public static function trigger( array $args ): void {
	}

	public function get_fields(): array {
		return [
			( new SelectField() )
				->set_label( __( 'List', 'shopmagic-for-woocommerce' ) )
				->set_name( self::PARAM_COMMUNICATION_TYPE )
				->set_placeholder( __( 'Any list', 'shopmagic-for-woocommerce' ) )
				->set_options( $this->get_options() )
				->set_description_tip(
					__(
						'Choose the list to which the customer opted in, or from which he opted out.',
						'shopmagic-for-woocommerce'
					)
				),
		];
	}

	/**
	 * Save params and run actions.
	 *
	 * @param SingleListSubscriber $subscriber
	 */
	public function process_event( SingleListSubscriber $subscriber ): void {
		try {
			$this->resources->set(
				Customer::class,
				$this->customer_repository->find_by_email( $subscriber->get_email() )
			);
		} catch ( \Exception $e ) {
			$this->logger->error(
				'Error during setting a customer from list subscriber. {error}',
				[
					'error' => $e->getMessage(),
				]
			);

			return;
		}

		if ( ! $this->fields_data->has( self::PARAM_COMMUNICATION_TYPE ) ) {
			$this->logger->warning(
				sprintf(
					"Prevented event dispatch due to insufficient configuration. Missing '%s' parameter",
					self::PARAM_COMMUNICATION_TYPE
				)
			);
			return;
		}

		$expected_list_id = $this->fields_data->get( self::PARAM_COMMUNICATION_TYPE );
		if ( empty( $expected_list_id ) || (int) $expected_list_id === $subscriber->get_list_id() ) {
			$this->trigger_automation();
		}
	}

	/** @return array<int, string> */
	private function get_options(): array {
		$lists  = $this->repository->find_by( [] );
		$result = [
			0 => __( 'Any list', 'shopmagic-for-woocommerce' ),
		];

		foreach ( $lists as $list ) {
			if ( $list->exists() ) {
				// translators: %d is list ID, when name is not given
				$result[ $list->get_id() ] = $list->get_name() ?: sprintf( esc_html__( 'List #%d', 'shopmagic-for-woocommerce' ), $list->get_id() );
			}
		}

		return $result;
	}
}
