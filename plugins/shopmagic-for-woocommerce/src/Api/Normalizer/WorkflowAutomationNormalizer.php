<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Api\Normalizer;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\CountableRepository;
use WPDesk\ShopMagic\Components\UrlGenerator\UrlGenerator;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Automation\Automation;
use WPDesk\ShopMagic\Workflow\Filter\Filter;

/**
 * @implements Normalizer<Automation>
 */
class WorkflowAutomationNormalizer implements Normalizer {

	/** @var CountableRepository<Automation> */
	private $repository;

	/** @var UrlGenerator */
	private $url_generator;

	/**
	 * @param CountableRepository<Automation> $repository
	 * @param UrlGenerator                    $url_generator
	 */
	public function __construct(
		CountableRepository $repository,
		UrlGenerator $url_generator
	) {
		$this->repository    = $repository;
		$this->url_generator = $url_generator;
	}

	public function normalize( object $object ): array {
		if ( ! $this->supports_normalization( $object ) ) {
			throw InvalidArgumentException::invalid_object( Automation::class, $object );
		}

		$links = [];

		$children_count = $this->repository->count(
			[
				'post_parent' => $object->get_id(),
				'post_status' => [ 'publish', 'draft', 'trash' ],
			]
		);
		if ( $children_count > 0 ) {
			$links['children'] = [
				'href' => $this->url_generator->generate(
					'/automations/' . $object->get_id() . '/children/'
				),
			];
		}

		if ( $object->has_parent() ) {
			$links['parent'] = [
				'href' => $this->url_generator->generate(
					'/automations/' . $object->get_parent()
				),
			];
		}

		return [
			'id'          => $object->get_id(),
			'parent'      => $object->get_parent(),
			'name'        => $object->get_name(),
			'description' => $object->get_description(),
			'status'      => $object->get_status(),
			'event'       => [
				'name'     => $object->get_event()->get_id(),
				'settings' => $object->get_event()->get_parameters()->all(),
			],
			'actions'     => array_map(
				static function ( Action $action ) {
					return [
						'name'     => $action->get_id(),
						'settings' => array_filter(
							$action->get_parameters()->all(),
							static function ( $item, $key ) {
								if ( $key === 'attachment' && empty( $item ) ) {
									return false;
								}

								return true;
							},
							ARRAY_FILTER_USE_BOTH
						),
					];
				},
				array_values( $object->get_actions() )
			),
			'filters'     => array_map(
				static function ( array $filters ) {
					return array_map(
						static function ( Filter $filter ) {
							return array_merge(
								$filter->get_parameters()->all(),
								[ 'id' => $filter->get_id() ]
							);
						},
						array_values( $filters )
					);
				},
				array_values( $object->get_filters_group()->get_filters() )
			),
			'language'    => $object->has_parent() ? $object->get_language() : null,
			'recipe'      => $object->is_recipe(),
			'_links'      => $links,
		];
	}

	public function supports_normalization( object $object ): bool {
		return $object instanceof Automation;
	}

}
