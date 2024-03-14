<?php // phpcs:disable WordPress.Security.NonceVerification.Recommended

namespace Oblak\WooCommerce\Product;

/**
 * Enables custom query vars for product query.
 */
abstract class Query_Vars_Extender {
    /**
     * Array of query vars to add.
     *
     * Query var, and variable type in key => value format.
     *
     * @var array<string, string>
     */
    protected array $vars = array();

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
            array( $this, 'modify_product_query_vars' ),
            100,
            2,
        );
    }

    /**
     * Registers custom query vars for product query.
     *
     * @param  array $query_vars Query vars.
     * @return array             Modified query vars.
     *
     * @hook woocommerce_product_object_query_args
     * @type filter
     */
    public function add_product_query_vars( $query_vars ) {
        $request_arr = \wc_clean( \wp_unslash( $_REQUEST ) );

        foreach ( \array_keys( $this->vars ) as $var ) {
            $query_vars[ $var ] ??= $request_arr[ $var ] ?? '';
        }

        return $query_vars;
    }

    /**
     * Modifies the query vars to include the custom ones.
     *
     * @param  array $query      The query vars.
     * @param  array $query_vars The query object.
     * @return array             The modified query vars.
     *
     * @hook woocommerce_product_data_store_cpt_get_products_query
     * @type filter
     */
    public function modify_product_query_vars( array $query, array $query_vars ): array {
        foreach ( $this->vars as $var => $type ) {
            if ( '' === $query_vars[ $var ] ) {
                continue;
            }

            match ( true ) {
                \taxonomy_exists( $type ) => $query['tax_query'][]  = $this->set_query_var(
                    $query_vars[ $var ],
                    $var,
                    $type,
                ),
                'meta' === $type          => $query['meta_query'][] = $this->set_query_var(
                    $query_vars[ $var ],
                    $var,
                    $type,
                ),
                default                   => $query[ $var ]         = $this->set_query_var(
                    $query_vars[ $var ],
                    $var,
                    $type,
                ),
            };
        }

        return $query;
    }

    /**
     * Sets the query var.
     *
     * @param  mixed  $value The value to set.
     * @param  string $key   The key of the value.
     * @param  string $type  The type of the value.
     */
    protected function set_query_var( $value, string $key, string $type ) {
        $return_value = match ( true ) {
            \taxonomy_exists( $type ) => $this->set_taxonomy_value( $value, $key, $type ),
            'meta' === $type          => $this->set_meta_value( $value, $key ),
            default                   => $value,
        };

        /**
         * Filters the query var value before it is added to the query.
         *
         * @param  mixed  $return The return value.
         * @param  mixed  $value  The query var value.
         * @param  string $key    The query var key.
         * @param  string $type   The query var type.
         * @return mixed          The modified query var value.
         *
         * @since 1.3.0
         */
        return \apply_filters( 'woocommerce_product_query_custom_var', $return_value, $value, $key, $type );
    }

    /**
     * Sets the taxonomy value.
     *
     * @param  mixed  $value The value to set.
     * @param  string $key   The key of the value.
     * @param  string $type  The type of the value.
     * @return array         The taxonomy value.
     */
    protected function set_taxonomy_value( $value, string $key, string $type ): array {
        $field = \str_starts_with( $key, 'product_' ) && \str_ends_with( $key, '_id' ) ? 'term_id' : 'slug';
        return array(
            'field'    => $field,
            'taxonomy' => $type,
            'terms'    => $value,
        );
    }

    /**
     * Sets the meta value.
     *
     * @param  mixed  $value The value to set.
     * @param  string $key   The key of the value.
     * @return array         The meta value.
     */
    protected function set_meta_value( $value, string $key ) {
        return \is_array( $value ) &&
            \count(
                \wp_array_slice_assoc( $value, array( 'value', 'compare' ) ),
            ) > 0
        ? \array_merge( array( 'key' => "_{$value}" ), $value )
        : array(
            'key'   => "_{$key}",
            'value' => $value,
        );
    }
}
