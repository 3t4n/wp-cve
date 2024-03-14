<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Recipe;

use WPDesk\ShopMagic\Workflow\Action\ActionList;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Automation\AutomationFiltersGroup;
use WPDesk\ShopMagic\Workflow\Event\EventsList;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;
use WPDesk\ShopMagic\Workflow\Filter\FiltersList;

/**
 * Converts recipe JSON file to automation object.
 * May also be used to convert old automation JSON to newer one.
 */
class RecipeConverter {

	/** @var EventsList */
	private $events;

	/** @var ActionList */
	private $actions;

	/** @var FiltersList */
	private $filters;

	public function __construct(
		EventsList $events,
		ActionList $actions,
		FiltersList $filters
	) {
		$this->events  = $events;
		$this->actions = $actions;
		$this->filters = $filters;
	}

	public function to_automation( array $decoded ): Automation {
		$as_automation = new Automation();
		$as_automation->set_status( "draft" );
		$as_automation->set_name( $decoded['name'] );
		$as_automation->set_description( $decoded['description'] );

		$event = $this->events[ $decoded['event']['slug'] ];
		$event->set_parameters( new FieldValuesBag( $decoded['event']['data'] ) );
		$as_automation->set_event( $event );

		$as_automation->set_actions( array_map( function ( $action_data ) {
			$action = $this->actions[ $action_data['_action'] ];
			$action->set_parameters( new FieldValuesBag( $action_data ) );

			return $action;
		}, $decoded['actions'] ) );

		if ( ! empty( $decoded['filters'] ) ) {
			$as_automation->set_filters_group(
				new AutomationFiltersGroup(
					array_map( function ( $outer_group ) {
						return array_map( function ( $inner_group ) {
							$filter = $this->filters[ $inner_group['filter_slug'] ];
							$filter->set_parameters( new FieldValuesBag( $inner_group['data'] ) );

							return $filter;
						}, array_values( $outer_group ?? [] ) );
					}, array_values( $decoded['filters'] ) )
				)
			);
		} else {
			$as_automation->set_filters_group( new AutomationFiltersGroup() );
		}

		$as_automation->set_recipe( true );

		return $as_automation;
	}

}
