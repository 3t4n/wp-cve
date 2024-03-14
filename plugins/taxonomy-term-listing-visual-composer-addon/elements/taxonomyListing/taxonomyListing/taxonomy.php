<?php
/**
 * Taxonomy term listing
 *
 * @package WordPress
 */

namespace taxonomyListing\taxonomyListing;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit;
}

use VisualComposer\Framework\Illuminate\Support\Module;
use VisualComposer\Framework\Container;
use VisualComposer\Helpers\Traits\EventsFilters;
use VisualComposer\Helpers\Traits\WpFiltersActions;
use VisualComposer\Modules\Elements\Traits\AddShortcodeTrait;

/**
 * Declare class
 */
class Taxonomy_Listing extends Container implements Module {
	use EventsFilters;
	use WpFiltersActions;
	use AddShortcodeTrait;

	/**
	 * Class construct.
	 */
	public function __construct() {
		$this->addFilter( 'vcv:editor:variables vcv:editor:variables/teamProfiles', 'getVariables' );
	}

	/**
	 * Get variables.
	 *
	 * @param  array $variables visual composer field setting.
	 * @return array
	 */
	protected function getVariables( $variables ) {
		$args = array(
			'public'   => true,
		);
		$taxonomies = get_taxonomies( $args, 'objects' );
		$taxonomy_list = [
			[
				'label' => __( 'Select Taxonomy', 'taxonomy-term-listing-visual-composer-addon' ),
				'value' => 0,
			],
		];
		if ( $taxonomies ) {
			foreach ( $taxonomies as $key => $taxonomy ) {
				$taxonomy_obj = $taxonomies[ $key ];
				if ( $taxonomy_obj->show_in_rest ) {
					$taxonomy_list[] = [
						'label' => $taxonomy_obj->name,
						'value' => $taxonomy_obj->rest_base ? $taxonomy_obj->rest_base : $taxonomy_obj->name,
					];
				}
			}
		} else {
			$taxonomy_list = [
				[
					'label' => __( 'No taxonomy found', 'taxonomy-term-listing-visual-composer-addon' ),
					'value' => 0,
				],
			];
		}
		$variables[] = [
			'key' => 'TaxonomyListing',
			'value' => $taxonomy_list,
		];
		return $variables;
	}
}
