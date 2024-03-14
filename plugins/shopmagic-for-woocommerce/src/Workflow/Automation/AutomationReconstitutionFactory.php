<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Automation;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\ShopMagic\Exception\AutomationNotFound;
use WPDesk\ShopMagic\Helper\PostMetaContainer;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Action\ActionList;
use WPDesk\ShopMagic\Workflow\Event\EventsList;
use WPDesk\ShopMagic\Workflow\Event\NullEvent;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;
use WPDesk\ShopMagic\Workflow\Filter\FiltersList;

class AutomationReconstitutionFactory {

	/** @var EventsList */
	private $events;

	/** @var ActionList */
	private $actions;

	/** @var FiltersList */
	private $filters;

	/** @var LoggerInterface */
	private $logger;

	public function __construct(
		EventsList $events,
		ActionList $actions,
		FiltersList $filters,
		LoggerInterface $logger
	) {
		$this->events  = $events;
		$this->actions = $actions;
		$this->filters = $filters;
		$this->logger  = $logger;
	}

	public function with_post(
		\WP_Post $automation_post,
		ContainerInterface $meta = null
	): Automation {
		$automation = new Automation( $automation_post->ID );
		if ( ! $automation->exists() ) {
			throw new AutomationNotFound( esc_html__( 'Automation does not exists.', 'shopmagic-for-woocommerce' ) );
		}

		if ( $meta === null ) {
			$meta = new PostMetaContainer( $automation->get_id() );
		}

		$automation->set_name( $automation_post->post_title );
		$automation->set_status( $automation_post->post_status ?: Automation::STATUS_DRAFT );
		$automation->set_parent( $automation_post->post_parent ?: null );

		if ( ! $meta->has( '_event' ) ) {
			$event = new NullEvent();
		} else {
			$event = $this->events[ $meta->get( '_event' ) ];
		}

		if ( $meta->has( '_event_data' ) ) {
			$event_config = new FieldValuesBag( $meta->get( '_event_data' ) );
		} else {
			$event_config = new FieldValuesBag( [] );
		}
		$event->update_fields_data( $event_config );
		$automation->set_event( $event );

		if ( $meta->has( 'filters' ) ) {
			$filters     = $meta->get( 'filters' );
			$new_filters = [];
			foreach ( $filters as $or_group_index => $and_group ) {
				$new_filters[ $or_group_index ] = [];
				foreach ( $and_group as $and_group_index => $filter_data ) {
					if ( ! empty( $filter_data['filter_slug'] ) ) {
						$filter     = $this->filters[ $filter_data['filter_slug'] ];
						$parameters = array_map(
							static function ( $setting ) {
								if ( is_array( $setting ) ) {
									return array_values( $setting );
								}

								return $setting;
							},
							(array) $filter_data['data'] ?? []
						);
						$filter->update_fields_data( new FieldValuesBag( $parameters ) );

						$new_filters[ $or_group_index ][ $and_group_index ] = $filter;
					}
				}
			}

			$filter_group_logic = new AutomationFiltersGroup( $new_filters );
			$filter_group_logic->setLogger( $this->logger );
			$automation->set_filters_group( $filter_group_logic );
		} else {
			$filters_group = new AutomationFiltersGroup( [] );
			$filters_group->setLogger( $this->logger );
			$automation->set_filters_group( $filters_group );
		}

		if ( ! $meta->has( '_actions' ) ) {
			$actions = [];
		} else {
			$actions = array_filter( (array) $meta->get( '_actions' ), 'is_array' );
		}

		$automation->set_actions( array_map(
			function ( array $action_data ): Action {
				$action = $this->actions[ $action_data['_action'] ];
				$action->update_fields_data( new FieldValuesBag( $action_data ) );

				return $action;
			},
			$actions
		) );

		if ( $automation->has_parent() && $meta->has( 'lang' ) ) {
			$automation->set_language( $meta->get( 'lang' ) );
		}

		$automation->set_recipe( $meta->has( 'shopmagic_source_recipe' ) );

		return $automation;
	}

}
