<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Controller;

use WPDesk\ShopMagic\Admin\Form\FieldsCollection;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\JsonSchemaNormalizer;
use WPDesk\ShopMagic\Components\Routing\HttpProblemException;
use WPDesk\ShopMagic\DataSharing\FieldValuesBag;
use WPDesk\ShopMagic\DataSharing\TestProvider;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Action\ActionList;
use WPDesk\ShopMagic\Workflow\Action\TestableAction;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Automation\AutomationRepository;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\Event\EventsList;
use WPDesk\ShopMagic\Workflow\Filter\Filter;
use WPDesk\ShopMagic\Workflow\Filter\FiltersList;
use WPDesk\ShopMagic\Workflow\Placeholder\Placeholder;
use WPDesk\ShopMagic\Workflow\Placeholder\PlaceholderProcessor;
use WPDesk\ShopMagic\Workflow\Placeholder\PlaceholdersList;

class ResourcesController {

	/** @var EventsList */
	private $events;

	/** @var FiltersList */
	private $filters;

	/** @var PlaceholdersList */
	private $placeholders;

	/** @var ActionList */
	private $actions;

	/** @var JsonSchemaNormalizer */
	private $normalizer;

	public function __construct(
		EventsList $events,
		FiltersList $filters,
		PlaceholdersList $placeholders,
		ActionList $actions,
		JsonSchemaNormalizer $normalizer
	) {
		$this->events       = $events;
		$this->filters      = $filters;
		$this->placeholders = $placeholders;
		$this->actions      = $actions;
		$this->normalizer   = $normalizer;
	}

	public function events(): \WP_REST_Response {
		$iterator_to_array = iterator_to_array(
			$this->events->map(
				function ( Event $event, string $key ) {
					return [
						'label'       => $event->get_name(),
						'value'       => $key,
						'description' => $event->get_description(),
						'settings'    => $this->normalizer->normalize( new FieldsCollection( $event->get_fields() ) ),
						'group'       => $event->get_group_slug(),
					];
				}
			),
			false
		);

		return new \WP_REST_Response( $iterator_to_array );
	}

	public function filters( string $event_slug = '' ): \WP_REST_Response {
		$filters = $this->filters->match_receivers( $this->events[ $event_slug ] )->map(
			function (
				Filter $filter,
				string $key
			) {
				return [
					'label'       => $filter->get_name(),
					'description' => $filter->get_description(),
					'value'       => $key,
					'settings'    => $this->normalizer->normalize( new FieldsCollection( $filter->get_fields() ) ),
					'group'       => $filter->get_group_slug(),
				];
			}
		);

		return new \WP_REST_Response( iterator_to_array( $filters, false ) );
	}

	public function placeholders( string $event_slug = '' ): \WP_REST_Response {
		$filters = $this->placeholders->match_receivers( $this->events[ $event_slug ] )->map(
			function ( Placeholder $placeholder ) {
				return [
					'label'       => $placeholder->get_name(),
					'description' => $placeholder->get_description(),
					'group'       => $placeholder->get_group_slug(),
					'fields'      => $this->normalizer->normalize( new FieldsCollection( $placeholder->get_supported_parameters() ) ),
				];
			}
		);

		return new \WP_REST_Response( iterator_to_array( $filters, false ) );
	}

	public function actions(): \WP_REST_Response {
		$actions = $this->actions->map(
			function ( Action $action ) {
				return [
					'label'       => $action->get_name(),
					'description' => $action->get_description(),
					'value'       => $action->get_id(),
					'settings'    => $this->normalizer->normalize( new FieldsCollection( $action->get_fields() ) ),
					'testable'    => $action instanceof TestableAction,
				];
			}
		);

		$iterator_to_array = iterator_to_array( $actions, false );

		return new \WP_REST_Response( $iterator_to_array );
	}

	public function test_action(
		string $action,
		\WP_REST_Request $request,
		PlaceholderProcessor $processor,
		AutomationRepository $repository,
		TestProvider $provider
	): \WP_REST_Response {
		/** @var Action $tested_action */
		$tested_action = $this->actions[ $action ];

		if ( ! $tested_action instanceof TestableAction ) {
			return new \WP_REST_Response( [], \WP_Http::UNPROCESSABLE_ENTITY );
		}

		$field_values = new FieldValuesBag( $request->get_param( 'config' ) );

		$data_layer = $provider->get_provided_data();
		$data_layer->set( Automation::class, $repository->find( $request->get_param( 'automation' ) ) );
		$processor->set_data_layer( $data_layer );
		try {
			$tested_action->execute_test(
				$field_values,
				$data_layer,
				$processor
			);
		} catch ( \Throwable $e ) {
			throw new HttpProblemException( [
				"title"  => __( "Unable to test action", 'shopmagic-for-woocommerce' ),
				"detail" => $e->getMessage(),
			], \WP_Http::UNPROCESSABLE_ENTITY );
		}

		return new \WP_REST_Response( null, \WP_Http::NO_CONTENT );
	}

}
