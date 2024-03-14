<?php // phpcs:disable WordPress.Security.NonceVerification.Recommended
/**
 * Query_Vars class file.
 *
 * @package WooCommerce Utils
 * @subpackage Product
 */

namespace Oblak\WooCommerce\Product;

use WP_Query;

/**
 * Adds and modifies woocommerce query vars
 *
 * @deprecated 1.27.0 Use Query_Vars_Extender instead.
 */
abstract class Custom_Query_Vars {
    /**
     * Query var array
     *
     * @var string[]
     */
    protected $vars;

    /**
     * Meta query relation
     *
     * Can be `AND` or `OR`
     *
     * @var string
     */
    protected $meta_relation = 'OR';

    /**
     * Class constructor
     */
    public function __construct() {
        \add_filter(
            'woocommerce_product_object_query_args',
            array( $this, 'add_product_query_vars' ),
            100,
            2,
        );
        \add_filter(
            'woocommerce_product_data_store_cpt_get_products_query',
            array( $this, 'get_product_query_vars' ),
            100,
            2,
        );
        \add_filter( 'pre_get_posts', array( $this, 'enable_admin_filters' ), 99, 1 );
    }

    /**
     * Registers custom query vars for product query.
     *
     * @param  array $query_vars Query vars.
     * @return array             Modified query vars.
     */
    public function add_product_query_vars( $query_vars ) {
        $request_arr = \wc_clean( \wp_unslash( $_REQUEST ) );

        foreach ( $this->vars as $var ) {
            if ( empty( $request_arr[ $var ] ) ) {
                continue;
            }

            $query_vars[ $var ] = $request_arr[ $var ];
        }

        return $query_vars;
    }

    /**
     * Adds query vars to product query.
     *
     * @param  array $query      Product query.
     * @param  array $query_vars Query vars.
     * @return array             Modified product query.
     */
    public function get_product_query_vars( $query, $query_vars ) {
        static $relation_added = false;

        foreach ( $this->vars as $var ) {
            if ( empty( $query_vars[ $var ] ) ) {
                continue;
            }

            if ( ! $relation_added ) {
                $query['meta_query'][] = array( 'relation' => $this->meta_relation );
                $relation_added        = true;
            }

            $query_var_value = \is_array( $query_vars[ $var ] )
                ? array(
                    'compare' => $query_vars[ $var ]['compare'],
                    'key'     => "_{$var}",
                    'value'   => $query_vars[ $var ]['value'],
                )
                : array(
                    'key'   => "_{$var}",
                    'value' => $query_vars[ $var ],
                );

            /**
             * Filters the meta query value before it is added to the query.
             *
             * @param  array  $query_var_value The meta query value.
             * @param  string $var             The query var.
             * @param  array  $query_vars      The query vars.
             * @param  self   $this            The class instance.
             * @return array                   The modified meta query value.
             *
             * @since 1.27.0
             */
            $query['meta_query'][] = \apply_filters(
                'oblak_woocommerce_product_query_var_value',
                $query_var_value,
                $var,
                $query_vars,
                $this,
            );

        }

        $relation_added = false;

        return $query;
    }

    /**
     * Enables admin filters.
     *
     * @param WP_Query $wp_query WP_Query object.
     */
    public function enable_admin_filters( &$wp_query ) {
        if ( ! \is_admin() || ! $wp_query->is_main_query() || 'product' !== $wp_query->get( 'post_type' ) ) {
            return;
        }

        $request_arr = \wc_clean( \wp_unslash( $_REQUEST ) );

        $meta_query = $this->get_product_query_vars( array(), $request_arr )['meta_query'] ?? array();

        if ( empty( $meta_query ) ) {
            return;
        }

        $wp_query->set( 'meta_query', $meta_query );
    }
}
