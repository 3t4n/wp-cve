<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\CountableRepository;
use WPDesk\ShopMagic\Workflow\Action\ActionList;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Automation\AutomationFiltersGroup;
use WPDesk\ShopMagic\Workflow\Event\EventsList;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;
use WPDesk\ShopMagic\Workflow\Filter\FiltersList;

/**
 * @implements Denormalizer<Automation>
 */
class WorkflowAutomationDenormalizer implements Denormalizer {

	/** @var EventsList */
	private $events;

	/** @var ActionList */
	private $actions;

	/** @var FiltersList */
	private $filters;

	/** @var string[] */
	private $errors = [];

	/** @var CountableRepository<Automation> */
	private $repository;

	/**
	 * @param EventsList                      $events
	 * @param ActionList                      $actions
	 * @param FiltersList                     $filters
	 * @param CountableRepository<Automation> $repository
	 */
	public function __construct(
		EventsList $events,
		ActionList $actions,
		FiltersList $filters,
		CountableRepository $repository
	) {
		$this->events     = $events;
		$this->actions    = $actions;
		$this->filters    = $filters;
		$this->repository = $repository;
	}

	public function denormalize( array $payload ): object {
		if ( ! $this->supports_denormalization( $payload ) ) {
			throw new InvalidArgumentException(
				empty( $this->errors )
					?
					esc_html__( '', 'shopmagic-for-woocommerce' )
					:
					implode( '\n', $this->errors )
			);
		}

		$automation = new Automation( isset( $payload['id'] ) ? (int) $payload['id'] : null );

		// Required properties
		$event = $this->events[ $payload['event']['name'] ];
		$event->update_fields_data( new FieldValuesBag( $payload['event']['settings'] ) );
		$automation->set_event( $event );

		$actions = [];
		foreach ( $payload['actions'] ?? [] as $action ) {
			$action_object = $this->actions[ $action['name'] ];
			$action_object->update_fields_data( new FieldValuesBag( $action['settings'] ) );
			$actions[] = $action_object;
		}
		$automation->set_actions( $actions );

		// Optional elements
		$filters = [];
		foreach ( array_values( $payload['filters'] ?? [] ) as $k => $filter_group ) {
			$filters[ $k ] = [];
			foreach ( $filter_group as $filter ) {
				$filter_object = $this->filters[ $filter['id'] ];
				$filter_object->update_fields_data( new FieldValuesBag( $filter ) );
				$filters[ $k ][] = $filter_object;
			}
		}
		$automation->set_filters_group( new AutomationFiltersGroup( $filters ) );

		if ( isset( $payload['status'] ) && $payload['status'] === Automation::STATUS_PUBLISH ) {
			$automation->set_status( Automation::STATUS_PUBLISH );
		} else {
			$automation->set_status( Automation::STATUS_DRAFT );
		}

		$automation->set_name( sanitize_text_field( $payload['name'] ) );

		if ( isset( $payload['description'] ) ) {
			$automation->set_description( sanitize_text_field( $payload['description'] ) );
		}

		if ( isset( $payload['language'] ) ) {
			$automation->set_language( $payload['language'] );
		}

		if ( isset( $payload['recipe'] ) ) {
			$automation->set_recipe( (bool) $payload['recipe'] );
		}

		if ( isset( $payload['parent'] ) ) {
			$automation->set_parent( (int) $payload['parent'] );
		}

		return $automation;
	}

	/**
	 * FIXME: This method contains too much business logic for being placed in application layer.
	 */
	public function supports_denormalization( array $data ): bool {
		$this->errors = [];
		if ( ! isset( $data['event'], $data['event']['name'] ) ) {
			$this->errors[] = esc_html__( 'Your automation is missing an event. Without this it cannot trigger any workflow.', 'shopmagic-for-woocommerce' );
		}

		if ( empty( $data['actions'] ) ) {
			$this->errors[] = esc_html__( 'Your automation is missing actions. Add at least one action to create working automation.', 'shopmagic-for-woocommerce' );
		}

		// At the moment we support parent->child relationship only in context of multilingual
		// support but this may change in time and this part of code will need refactor.
//		if ( isset( $data['parent'] ) && empty( $data['language'] ) ) {
//			$this->errors[] = esc_html__( 'You are creating a child automation, which requires to pick automation language. Set the language and save automation again or save your automation as main one by removing parent automation in settings.', 'shopmagic-for-woocommerce' );
//		}

		// If either parent or language is set, we need to check for the other parameter as well.
		if ( isset( $data['parent'] ) xor isset( $data['language'] ) ) {
			$this->errors[] = esc_html__( 'Language and automation relationship can only be set together. Please, set both language and parent automation or remove both.', 'shopmagic-for-woocommerce' );
		}

		if ( isset( $data['parent'] ) ) {
			try {
				$parent_automation = $this->repository->find( $data['parent'] );
				if ( $parent_automation->has_parent() ) {
					$this->errors[] = esc_html__(
						'You are trying to create a child automation for a child automation. This is not supported. Please, save your automation as main one by removing parent automation in settings.',
						'shopmagic-for-woocommerce'
					);
				}
			} catch ( \Exception $e ) {
				// We can safely ignore, as non-existing parent automations are not validated here.
			}
		}

		// This check is only valid for existing automations, thus those which have ID assigned.
		if ( isset( $data['id'], $data['parent'] ) ) {
			$children_count = $this->repository->count( [
				'post_parent' => $data['id'],
				'post_status' => [ 'publish', 'draft', 'trash' ],
			] );

			if ( $children_count > 0 ) {
				$this->errors[] = esc_html__(
					'You are trying to create a child automation for a parent automation that already has a child automation. This is not supported. Please, save your automation as main one by removing parent automation in settings.',
					'shopmagic-for-woocommerce'
				);
			}
		}

		if ( empty( $this->errors ) ) {
			return true;
		}

		return false;
	}
}
