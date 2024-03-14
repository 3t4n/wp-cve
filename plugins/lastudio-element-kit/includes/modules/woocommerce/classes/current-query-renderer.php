<?php
namespace LaStudioKitThemeBuilder\Modules\Woocommerce\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Current_Query_Renderer extends Base_Products_Renderer {

	const DEFAULT_COLUMNS_AND_ROWS = 4;

	public function __construct( $settings = [], $type = 'current_query' ) {

        $this->settings = $settings;
        $this->type = $type;
        $this->attributes = $this->parse_attributes( [
            'paginate' => $settings['paginate'],
            'cache' => false,
        ] );
        $this->query_args = $this->parse_query_args();

        $this->override_hook_to_init();

	}

	/**
	 * Override the original `get_query_results`
	 * with modifications that:
	 * 1. Remove `pre_get_posts` action if `is_added_product_filter`.
	 *
	 * @return bool|mixed|object
	 */
	protected function get_query_results() {

		$query = $GLOBALS['wp_query'];

		$paginated = ! $query->get( 'no_found_rows' );

		// Check is_object to indicate it's called the first time.
		if ( ! empty( $query->posts ) && is_object( $query->posts[0] ) ) {
			$query->posts = array_map( function ( $post ) {
				return $post->ID;
			}, $query->posts );
		}

		$results = (object) array(
			'ids'          => wp_parse_id_list( $query->posts ),
			'total'        => $paginated ? (int) $query->found_posts : count( $query->posts ),
			'total_pages'  => $paginated ? (int) $query->max_num_pages : 1,
			'per_page'     => (int) $query->get( 'posts_per_page' ),
			'current_page' => $paginated ? (int) max( 1, $query->get( 'paged', 1 ) ) : 1,
		);

		return apply_filters( 'woocommerce_shortcode_products_query_results', $results, $this );

	}

	protected function parse_query_args() {
		$settings = $this->settings;

		if ( ! is_page( wc_get_page_id( 'shop' ) ) ) {
			$query_args = $GLOBALS['wp_query']->query_vars;
		}

		add_action( "woocommerce_shortcode_before_{$this->type}_loop", function () {
			wc_set_loop_prop( 'is_shortcode', false );
		} );

		if ( 'yes' === $settings['paginate'] ) {
			$page = get_query_var( 'paged', 1 );

			if ( 1 < $page ) {
				$query_args['paged'] = $page;
			}

            if( lastudio_kit()->get_theme_support('elementor::product-grid-v2') ){
                remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
                remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
            }
            else{
                if ( 'yes' !== $settings['allow_order'] ) {
                    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
                }

                if ( 'yes' !== $settings['show_result_count'] ) {
                    remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
                }
            }
		}

		// Always query only IDs.
		$query_args['fields'] = 'ids';

		// fallback to the widget's default settings in case settings was left empty:
		$query_args['posts_per_page'] = $this->get_limit();

		return $query_args;
	}

}
