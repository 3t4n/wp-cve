<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Automation;

use WPDesk\ShopMagic\Components\Database\Abstraction\DAO\ObjectRepository;
use WPDesk\ShopMagic\Components\Database\Abstraction\InvalidEntity;
use WPDesk\ShopMagic\Components\Database\Abstraction\PostObjectManager;
use WPDesk\ShopMagic\Helper\PostMetaContainer;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Filter\Filter;

/**
 * @extends PostObjectManager<Automation>
 */
class AutomationObjectManager extends PostObjectManager {

	/** @var \WPDesk\ShopMagic\Components\Validator\AutomationValidator */
	private $validator;

	/**
	 * @param \WPDesk\ShopMagic\Components\Validator\AutomationValidator $validator
	 * @param ObjectRepository<Automation>                               $repository
	 */
	public function __construct(
		\WPDesk\ShopMagic\Components\Validator\AutomationValidator $validator,
		ObjectRepository $repository
	) {
		$this->validator = $validator;
		parent::__construct( $repository );
	}

	/**
	 * Performs soft delete of automation object.
	 * If object is already trashed, deletes it completely.
	 * If object has children, deletes them too.
	 */
	protected function do_delete( object $item ): bool {
		if ( ! $item->exists() ) {
			throw new InvalidEntity( esc_html__( 'Cannot delete non-existing automation.', 'shopmagic-for-woocommerce' ) );
		}

		if ( $item->get_status() === 'trash' ) {
			$result = (bool) wp_delete_post( $item->get_id() );
		} else {
			$result = (bool) wp_trash_post( $item->get_id() );
		}

		$children = $this->repository->find_by( [ 'post_parent' => $item->get_id() ] );

		foreach ( $children as $child ) {
			$result = $result && $this->do_delete( $child );
		}

		return $result;
	}

	public function can_handle( object $item ): bool {
		return $item instanceof Automation;
	}

	protected function expected_post_type(): ?string {
		return AutomationPostType::TYPE;
	}

	protected function do_save( object $item ): bool {
		$valid = $this->validator->validate( $item );
		if ( ! $valid ) {
			throw new AutomationNotSaved( 'Validation not passed' );
		}

		$id = wp_insert_post(
			[
				'ID'           => $item->get_id(),
				'post_parent'  => $item->get_parent(),
				'post_title'   => $item->get_name(),
				'post_content' => '',
				'post_type'    => AutomationPostType::TYPE,
				'post_status'  => $item->get_status(),
			]
		);
		$item->set_id( $id );

		$meta = new PostMetaContainer( $item->get_id() );
		$meta->set( '_event', $item->get_event()->get_id() );
		$meta->set( '_event_data', $item->get_event()->get_parameters()->all() );

		if ( $item->has_language() ) {
			$meta->set( 'lang', $item->get_language() );
		}
		$meta->set( 'filters', $this->normalize_filters( $item->get_filters_group() ) );
		if ( $item->is_recipe() ) {
			$meta->set( 'shopmagic_source_recipe', $item->get_id() );
		}

		$actions = [];
		foreach ( $this->normalize_actions( $item->get_actions() ) as $key => $action_data ) {
			$data_with_deprecation = apply_filters_deprecated(
				'shopmagic_settings_save',
				[ $action_data, $action_data ],
				'3.0.0',
				'shopmagic/core/settings/before_save'
			);
			$actions[ $key ]       = apply_filters( 'shopmagic/core/settings/before_save', $data_with_deprecation );
		}

		$meta->set( '_actions', $actions );

		return true;
	}

	private function normalize_actions( array $actions ): array {
		return array_map(
			static function ( Action $action ) {
				return array_merge(
					$action->get_parameters()->all(),
					[ '_action' => $action->get_id() ]
				);
			},
			array_values( $actions )
		);
	}

	private function normalize_filters( AutomationFiltersGroup $filters ): array {
		return array_map(
			static function ( array $filters ) {
				return array_map(
					static function ( Filter $filter ) {
						return [
							/**
							 * At the moment object manager handles required normalization but
							 * this should be moved to other class.
							 * In general, to achieve compatibility between SM 2.x and 3.x all
							 * array values saved in filter MUST be a list instead of associative
							 * array.
							 */
							'data'        => array_map(
								static function ( $setting ) {
									if ( is_array( $setting ) ) {
										return array_values( $setting );
									}

									return $setting;
								},
								$filter->get_parameters()->all()
							),
							'filter_slug' => $filter->get_id(),
						];
					},
					array_values( $filters )
				);
			},
			$filters->get_filters()
		);
	}
}
