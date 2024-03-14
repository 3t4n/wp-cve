   <?php 

      global $product;
        global $wp_query;
        $settings = $this->get_settings_for_display();
        $allowed_tags = wp_kses_allowed_html('post');       
        $paged = get_query_var('paged');
        $paged = wpsection_set($_REQUEST, 'paged') ? esc_attr($_REQUEST['paged']) : $paged;
        $this->add_render_attribute( 'wrapper', 'class', 'templatepath-wpsection' );
    
    //Column Settings Area   

        if($settings['wps_columns'] == '10') {
                        $columns_markup = ' mr_column_10 ';
                    }      
         else if($settings['wps_columns'] == '9') {
                        $columns_markup = ' mr_column_9 ';
                    } 
        else if($settings['wps_columns'] == '8') {
                        $columns_markup = ' mr_column_8 ';
                    }   
         else if($settings['wps_columns'] == '7') {
                        $columns_markup = ' mr_column_7 ';
                    } 
        else if($settings['wps_columns'] == '6') {
                        $columns_markup = 'col-lg-2 ';
                    }
        else if($settings['wps_columns'] == '5') {
                        $columns_markup = ' mr_column_5 ';
                    } 
        else if($settings['wps_columns'] == '4') {
                        $columns_markup = 'col-lg-3 ';
                    }   
         else if($settings['wps_columns'] == '3') {
                        $columns_markup = 'col-lg-4 ';
                    }
        else if($settings['wps_columns'] == '2') {
                        $columns_markup = 'col-lg-6 ';
                    } 
        else if($settings['wps_columns'] == '1') {
                        $columns_markup = 'col-lg-12 ';
                    }

// Tab Column 

  //Column Settings Area   

        if($settings['wps_columns_tab'] == '10') {
                        $columns_markup_tab = ' mr_column_10 ';
                    }      
         else if($settings['wps_columns_tab'] == '9') {
                        $columns_markup_tab = ' mr_column_9 ';
                    } 
        else if($settings['wps_columns_tab'] == '8') {
                        $columns_markup_tab = ' mr_column_8 ';
                    }   
         else if($settings['wps_columns_tab'] == '7') {
                        $columns_markup_tab = ' mr_column_7 ';
                    } 
        else if($settings['wps_columns_tab'] == '6') {
                        $columns_markup_tab = ' col-md-2';
                    }
        else if($settings['wps_columns_tab'] == '5') {
                        $columns_markup_tab = ' mr_column_5 ';
                    } 
        else if($settings['wps_columns_tab'] == '4') {
                        $columns_markup_tab = ' col-md-3 ';
                    }   
         else if($settings['wps_columns_tab'] == '3') {
                        $columns_markup_tab = ' col-md-4';
                    }
        else if($settings['wps_columns_tab'] == '2') {
                        $columns_markup_tab = ' col-md-6';
                    } 
        else if($settings['wps_columns_tab'] == '1') {
                        $columns_markup_tab = ' col-md-12';
                    }


$columns_markup_print = $columns_markup . ' ' . $columns_markup_tab;




     // Call the setting and make variable 
        $product_per_page = $settings['query_number'];
        $product_order_by = $settings['query_orderby'];
        $product_order    = $settings['query_order'];
        $product_grid_type = $settings['product_grid_type'];
        $query_category = $settings['query_category'];
      // Argument for $args 
        if ( $product_grid_type == 'sale_products' ) {
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => $product_per_page,
                'meta_query'     => array(
                    'relation' => 'OR',
                    array(// Simple products type
                        'key'     => '_sale_price',
                        'value'   => 0,
                        'compare' => '>',
                        'type'    => 'numeric',
                    ),
                    array(// Variable products type
                        'key'     => '_min_variation_sale_price',
                        'value'   => 0,
                        'compare' => '>',
                        'type'    => 'numeric',
                    ),
                ),
                'orderby'        => $product_order_by,
                'order'          => $product_order,
            );
        }
        if ( $product_grid_type == 'best_selling_products' ) {
            $args = array(
                'post_type'      => 'product',
                'meta_key'       => 'total_sales',
                'orderby'        => 'meta_value_num',
                'posts_per_page' => $product_per_page,
                'order'          => $product_order,
            );
        }
        if ( $product_grid_type == 'recent_products' ) {
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => $product_per_page,
                'orderby'        => $product_order_by,
                'order'          => $product_order,
            );
        }
        if ( $product_grid_type == 'featured_products' ) {
            $args = array(
                'post_type'      => 'product',
                'posts_per_page' => $product_per_page,
                'tax_query'      => array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured',
                    ),
                ),
                'orderby'        => $product_order_by,
                'order'          => $product_order,
            );

        }
        if ( $product_grid_type == 'top_rated_products' ) {
            $args = array(
                'posts_per_page' => $product_per_page,
                'no_found_rows'  => 1,
                'post_status'    => 'publish',
                'post_type'      => 'product',
                'meta_key'       => '_wc_average_rating',
                'orderby'        => 'meta_value_num',
                'order'          => $product_order,
                'meta_query'     => WC()->query->get_meta_query(),
                'tax_query'      => WC()->query->get_tax_query(),
            );
        }
        if ( $product_grid_type == 'product_category' ) {
     
                $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => $product_per_page,
                        'orderby' => $product_order_by,
                        'order' => $product_order,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field' => 'term_id',
                                'terms' => $query_category,
                                'operator' => 'IN',
                            ),
                        ),
                    );
               
        }
        // End of args
  $enable_pagination = $settings['wps_block_pagination'] === 'yes';