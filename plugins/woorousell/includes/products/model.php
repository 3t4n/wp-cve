<?php

/**
 * products Model Class
 *
 * @author 		MojofyWP
 * @package 	includes/products
 * 
 */

if ( !class_exists( 'WRSL_Products_Model' ) ) {
    class WRSL_Products_Model
    {
        /**
         * instance
         *
         * @access private
         * @var array
         */
        private  $instance = null ;
        /**
         * Hook prefix
         *
         * @access private
         * @var string
         */
        private  $_hook_prefix = null ;
        /**
         * Get instance
         *
         * @access public
         * @return array
         */
        public function get_instance()
        {
            return $this->instance;
        }
        
        /**
         * Class Constructor
         *
         * @access private
         */
        function __construct( $instance = array() )
        {
            // setup variables
            $this->instance = $instance;
            $this->_hook_prefix = wrsl()->plugin_hook() . 'products/model/';
        }
        
        /**
         * Get query
         *
         * @access public
         * @return array
         */
        public function get_query()
        {
            global  $woorousell_fs ;
            $query_args = array(
                'post_type'      => ( $this->instance['hide_on_sale'] && $this->instance['hide_on_sale'] == 'only-sale' ? array( $this->instance['post_type'] ) : $this->instance['post_type'] ),
                'post_status'    => 'publish',
                'posts_per_page' => $this->instance['posts_per_page'],
                'paged'          => 1,
            );
            // setup query order
            if ( isset( $this->instance['order'] ) ) {
                switch ( $this->instance['order'] ) {
                    case 'newest-first':
                    default:
                        $query_args['orderby'] = 'date';
                        $query_args['order'] = 'desc';
                        break;
                    case 'oldest-first':
                        $query_args['orderby'] = 'date';
                        $query_args['order'] = 'asc';
                        break;
                    case 'title-asc':
                        $query_args['orderby'] = 'title';
                        $query_args['order'] = 'asc';
                        break;
                    case 'title-desc':
                        $query_args['orderby'] = 'title';
                        $query_args['order'] = 'desc';
                        break;
                    case 'price-asc':
                        $query_args['meta_key'] = '_price';
                        $query_args['orderby'] = 'meta_value_num';
                        $query_args['order'] = 'asc';
                        break;
                    case 'price-desc':
                        $query_args['meta_key'] = '_price';
                        $query_args['orderby'] = 'meta_value_num';
                        $query_args['order'] = 'desc';
                        break;
                    case 'best-selling':
                        break;
                    case 'top-rated':
                        break;
                    case 'random':
                        break;
                }
            }
            // on sale product filter
            
            if ( $this->instance['hide_on_sale'] && $this->instance['hide_on_sale'] == 'on' ) {
                
                if ( isset( $query_args['meta_query'] ) ) {
                    $query_args['meta_query']['relation'] = "AND";
                    $query_args['meta_query'][] = array(
                        'key'     => '_sale_price',
                        'value'   => '',
                        'compare' => '=',
                    );
                } else {
                    $query_args['meta_query'] = array( array(
                        'key'     => '_sale_price',
                        'value'   => '',
                        'compare' => '=',
                    ) );
                }
                
                // end - $query_args[ 'meta_query' ]
            } else {
                
                if ( $this->instance['hide_on_sale'] && $this->instance['hide_on_sale'] == 'only-sale' ) {
                    // use wc functions to get discount IDs
                    $product_ids_on_sale = ( function_exists( 'wc_get_product_ids_on_sale' ) ? wc_get_product_ids_on_sale() : array() );
                    
                    if ( !empty($product_ids_on_sale) ) {
                        $query_args['post__in'] = $product_ids_on_sale;
                    } else {
                        
                        if ( isset( $query_args['meta_query'] ) ) {
                            $query_args['meta_query']['relation'] = "AND";
                            $query_args['meta_query'][] = array(
                                'key'     => '_sale_price',
                                'value'   => '',
                                'compare' => '!=',
                            );
                        } else {
                            $query_args['meta_query'] = array( array(
                                'key'     => '_sale_price',
                                'value'   => '',
                                'compare' => '!=',
                            ) );
                        }
                        
                        // end - $query_args[ 'meta_query' ]
                    }
                
                }
            
            }
            
            // end - $this->instance['hide_on_sale']
            // hide oos product
            
            if ( $this->instance['hide_oos'] ) {
                
                if ( isset( $query_args['meta_query'] ) ) {
                    $query_args['meta_query']['relation'] = "AND";
                    $query_args['meta_query'][] = array(
                        'key'     => '_stock_status',
                        'value'   => 'outofstock',
                        'compare' => 'NOT IN',
                    );
                } else {
                    $query_args['meta_query'] = array( array(
                        'key'     => '_stock_status',
                        'value'   => 'outofstock',
                        'compare' => 'NOT IN',
                    ) );
                }
                
                // end - $query_args[ 'meta_query' ]
            }
            
            // end - $this->instance['hide_oos']
            // Do the special taxonomy array()
            
            if ( isset( $this->instance['category'] ) && '' != $this->instance['category'] && 0 != $this->instance['category'] ) {
                
                if ( isset( $query_args['tax_query'] ) ) {
                    $query_args['tax_query']['relation'] = "AND";
                    $query_args['tax_query'][] = array( array(
                        "taxonomy" => $this->instance['taxonomy'],
                        "field"    => "id",
                        "terms"    => explode( ',', $this->instance['category'] ),
                        'operator' => ( isset( $this->instance['category_relation'] ) && 'AND' == $this->instance['category_relation'] ? $this->instance['category_relation'] : 'IN' ),
                    ) );
                } else {
                    $query_args['tax_query'] = array( array(
                        "taxonomy" => $this->instance['taxonomy'],
                        "field"    => "id",
                        "terms"    => explode( ',', $this->instance['category'] ),
                        'operator' => ( isset( $this->instance['category_relation'] ) && 'AND' == $this->instance['category_relation'] ? $this->instance['category_relation'] : 'IN' ),
                    ) );
                }
                
                // end - $query_args[ 'meta_query' ]
            }
            
            // Do the WP_Query
            $query_args = apply_filters( $this->_hook_prefix . 'get_query/args', $query_args, $this );
            $post_query = new WP_Query( $query_args );
            return apply_filters( $this->_hook_prefix . 'get_query', $post_query, $this );
        }
        
        /**
         * woocommerce_order_by_rating_post_clauses function.
         *
         * @access public
         * @return array
         */
        function order_by_rating_post_clauses( $args )
        {
            return WC_Shortcodes::order_by_rating_post_clauses( $args );
        }
    
    }
    // end - class WRSL_Products_Model
}

// end - !class_exists('WRSL_Products_Model')