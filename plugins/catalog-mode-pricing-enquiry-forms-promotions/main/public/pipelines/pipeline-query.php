<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WModes_Pipeline_Query' ) && !defined( 'WMODES_PREMIUM_ADDON' ) ) {

    class WModes_Pipeline_Query {

        private $transient_id = 'wmodes_on_sale';
        private $cached_data;

        public function __construct() {

            $this->cached_data = array();

            add_filter( 'transient_wc_products_onsale', array( $this, 'get_on_sale_product_ids' ), 99999, 2 );
        }

        public function get_on_sale_product_ids( $product_ids, $transient_name ) {

            $settings = $this->get_settings();

            if ( 'no' == $settings[ 'enable' ] ) {

                return $product_ids;
            }

            $cache_key = $this->get_cache_key();

            if ( isset( $this->cached_data[ $cache_key ] ) ) {

                return $this->cached_data[ $cache_key ];
            }

            $cache_data = $this->load_cache( $cache_key );

            if ( !$cache_data ) {

                $cache_data = $this->load_from_db( $settings[ 'query_limit' ] );

                $this->store_cache( $cache_data, $cache_key, $settings[ 'cache_duration' ] );
            }

            $this->cached_data[ $cache_key ] = $cache_data;

            return $this->cached_data[ $cache_key ];
        }

        private function load_from_db( $query_limit ) {

            if ( !is_numeric( $query_limit ) ) {

                $query_limit = 50;
            }

            $query_product_ids = $this->query_db( $query_limit );

            if ( !is_array( $query_product_ids ) ) {

                return false;
            }

            $product_ids = array();

            foreach ( $query_product_ids as $product_id ) {

                $product = wc_get_product( $product_id );

                if ( !$product ) {

                    continue;
                }

                if ( $product->is_on_sale() ) {

                    $product_ids[] = $product_id;
                }
            }

            if ( !count( $product_ids ) ) {

                return false;
            }

            return $product_ids;
        }

        private function query_db( $query_limit ) {

            global $wpdb;

            $limit = esc_sql( $query_limit );
            $exclude_term_ids = array();
            $outofstock_join = '';
            $outofstock_where = '';
            $non_published_where = '';
            $product_visibility_term_ids = wc_get_product_visibility_term_ids();

            if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && $product_visibility_term_ids[ 'outofstock' ] ) {
                $exclude_term_ids[] = $product_visibility_term_ids[ 'outofstock' ];
            }

            if ( count( $exclude_term_ids ) ) {
                $outofstock_join = " LEFT JOIN ( SELECT object_id FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ( " . implode( ',', array_map( 'absint', $exclude_term_ids ) ) . ' ) ) AS exclude_join ON exclude_join.object_id = id';
                $outofstock_where = ' AND exclude_join.object_id IS NULL';
            }

            // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
            $on_sale_products = $wpdb->get_results(
                    "
			SELECT posts.ID as id, posts.post_parent as parent_id
			FROM {$wpdb->posts} AS posts
			INNER JOIN {$wpdb->wc_product_meta_lookup} AS lookup ON posts.ID = lookup.product_id
			$outofstock_join
			WHERE posts.post_type IN ( 'product', 'product_variation' )
			AND posts.post_status = 'publish'
			$outofstock_where
			AND posts.post_parent NOT IN (
				SELECT ID FROM `$wpdb->posts` as posts
				WHERE posts.post_type = 'product'
				AND posts.post_parent = 0
				AND posts.post_status != 'publish'
			)
			GROUP BY posts.ID
                        LIMIT {$limit}
			"
            );
            // phpcs:enable WordPress.DB.PreparedSQL.InterpolatedNotPrepared


            return wp_parse_id_list( array_merge( wp_list_pluck( $on_sale_products, 'id' ), array_diff( wp_list_pluck( $on_sale_products, 'parent_id' ), array( 0 ) ) ) );
        }

        private function get_cache_key() {

            $settings = $this->get_settings();

            if ( !isset( $settings[ 'cache_parameters' ] ) || !is_array( $settings[ 'cache_parameters' ] ) ) {

                return '';
            }

            return $this->get_cache_hash( $settings[ 'cache_parameters' ] );
        }

        private function get_cache_hash( $cache_parameters ) {

            $cache_hash_data = array();

           $cache_hash_data[ 'param' ] = apply_filters( 'wmodes/get-query-cache-parameter', false, '' );

            return md5( wp_json_encode( $cache_hash_data ) );
        }

        private function load_cache( $key ) {

            if ( $this->can_store_in_session() ) {

                $session_data = $this->get_session( $this->transient_id, false );

                if ( !$session_data ) {

                    return false;
                }

                if ( $key != $session_data[ 'key' ] ) {

                    return false;
                }

                if ( current_time( 'U' ) > $session_data[ 'expiration' ] ) {

                    return false;
                }

                return $session_data[ 'cache_data' ];
            }

            $transient_key = $this->get_transient_key( $key );

            return get_transient( $transient_key );
        }

        private function store_cache( $cache_data, $key, $duration ) {

            if ( $this->can_store_in_session() ) {

                $session_data = array(
                    'cache_data' => $cache_data,
                    'key' => $key,
                    'expiration' => $this->get_expiration( $duration )
                );

                WC()->session->set( $this->transient_id, $session_data );
            }

            $transient_key = $this->get_transient_key( $key );

            set_transient( $transient_key, $cache_data, $this->get_expiration( $duration ) );
        }

        private function can_store_in_session() {

            $has_session = $this->get_session( 'customer', false );

            if ( !$has_session ) {

                return false;
            }

            return true;
        }

        private function get_expiration( $duration ) {

            if ( !is_numeric( $duration ) ) {

                $duration = 30;
            }


            $total_seconds = 5;

            if ( $duration > 0 ) {

                $total_seconds = $duration * MINUTE_IN_SECONDS;
            }

            return current_time( 'U' ) + $total_seconds;
        }

        private function get_transient_key( $key ) {

            if ( empty( $key ) ) {

                return $this->transient_id . '_' . $key;
            }

            return $this->transient_id;
        }

        private function get_session( $key, $default ) {

            return WC()->session->get( $key, $default );
        }

        private function get_settings() {

            $default = array(
                'enable' => 'no',
            );

            return WModes::get_option( 'product_query', $default );
        }

    }

    new WModes_Pipeline_Query();
}
