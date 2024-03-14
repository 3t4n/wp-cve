<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Tracker\Provider;

use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Automation\AutomationRepository;
use WPDesk\ShopMagic\Workflow\FieldValuesBag;

/**
 * Provides info about automations.
 */
class AutomationDataProvider implements \WPDesk_Tracker_Data_Provider {

	/** @var AutomationRepository */
	private $repository;

	public function __construct( AutomationRepository $repository ) {
		$this->repository = $repository;
	}

	/**
	 * @inheritDoc
	 */
	public function get_data() {
		$automations_info = $this->automations_info();

		return [
			'shopmagic_automations'        => $automations_info,
			'shopmagic_automations_counts' => $this->counts( $automations_info ),
		];
	}

	/**
	 * @return array
	 */
	private function automations_info(): array {
		$automations = $this->repository->find_by( [ 'post_status' => Automation::STATUS_PUBLISH ] );
		$result      = [];
		foreach ( $automations as $automation ) {
			$result[] = [
				'event'      => $automation->get_event()->get_id(),
				'event_data' => $automation->get_event()->get_parameters()->all(),
				'filters'    => $this->get_filters_slugs( $automation ),
				'actions'    => $this->actions_info( $automation->get_actions() ),
			];
		}

		return $result;
	}

	private function get_filters_slugs( Automation $automation ): array {
		$filters_slugs = [];
		foreach ( $automation->get_filters_group() as $orData ) {
			foreach ( $orData as $filter ) {
				$filters_slugs[] = $filter->get_id();
			}
		}

		return array_unique( $filters_slugs );
	}

	/**
	 * @param Action[] $actions
	 *
	 * @return array
	 */
	private function actions_info( array $actions ): array {
		$result = [];
		foreach ( $actions as $action ) {
			$parameters = $action->get_parameters();
			$result[]   = [
				'slug'                => $action->get_id(),
				'template_type'       => $parameters->has( 'template_type' ) ? $parameters->get( 'template_type' ) : '',
				'action_delayed'      => $parameters->has( '_action_delayed' ) ? $parameters->get( '_action_delayed' ) : 'no',
				'delay_schedule_type' => $parameters->has( '_action_schedule_type' ) ? $parameters->get( '_action_schedule_type' ) : null,
				'delay_offset'        => $parameters->has( '_action_delayed_offset_time' ) ? $parameters->get( '_action_delayed_offset_time' ) : null,
				'delay_step'          => $parameters->has( '_action_delay_step' ) ? $parameters->get( '_action_delay_step' ) : null,
				'delay_string'        => $parameters->has( '_action_variable_string' ) ? $parameters->get( '_action_variable_string' ) : null,
				'placeholders'        => $this->placeholders_info( $parameters ),
			];
		}

		return $result;
	}

	/**
	 * @param $action_parameters
	 *
	 * @return array
	 */
	private function placeholders_info( $action_parameters ): array {
		if ( ! is_object( $action_parameters ) || ! $action_parameters instanceof FieldValuesBag ) {
			return [];
		}
		$placeholders = [];
		foreach ( $action_parameters as $action_value ) {
			if ( ! is_string( $action_value ) ) {
				continue;
			}
			if ( preg_match_all( '/{{[ ]*([^}]+)[ ]*}}/', $action_value, $matches ) ) {
				if ( \is_array( $matches[1] ) ) {
					foreach ( $matches[1] as $placeholder_string ) {
						if ( preg_match( '/([a-zA-Z0-9._]+)/', $placeholder_string, $match ) ) {
							$name                  = $match[1];
							$placeholders[ $name ] = isset( $placeholders[ $name ] ) ? $placeholders[ $name ] + 1 : 1;
						}
					}
				}
			}
		}

		return $placeholders;
	}

	/**
	 * @param array $automations_info
	 *
	 * @return array
	 */
	private function counts( $automations_info ) {
		$placeholders_count = [];
		$actions_count      = [];
		foreach ( $automations_info as $automation ) {
			foreach ( $automation['actions'] as $action ) {
				$action_slug                   = $action['slug'];
				$actions_count[ $action_slug ] = isset( $actions_count[ $action_slug ] ) ? $actions_count[ $action_slug ] + 1 : 1;

				foreach ( $action['placeholders'] as $key => $count ) {
					$placeholders_count[ $key ] = isset( $placeholders_count[ $key ] ) ? $placeholders_count[ $key ] + $count : $count;
				}
			}
		}

		return [
			'automations_all'    => \count( $automations_info ),
			'actions_all'        => array_reduce(
				$automations_info,
				static function ( $carry, $automation ) {
					return \count( $automation['actions'] ) + $carry;
				},
				0
			),
			'actions_count'      => $actions_count,
			'placeholders_count' => $placeholders_count,
		];
	}
}

