<?php

// If this file is called directly, abort.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * WHSM_Shipping_Method_Page class.
 */
if ( !class_exists( 'WHSM_Shipping_Method_Page' ) ) {
    class WHSM_Shipping_Method_Page
    {
        /**
         * Output the Admin UI
         *
         * @since 3.5
         */
        const  post_type = 'wc_whsm' ;
        private static  $admin_object = null ;
        /**
         * Display output
         *
         * @since 3.5
         *
         * @uses Woo_Hide_Shipping_Methods_Admin
         * @uses whsmsmp_sz_save_method
         * @uses whsmsmp_sz_add_shipping_method_form
         * @uses whsmsmp_sz_edit_method_screen
         * @uses whsmsmp_sz_delete_method
         * @uses whsmsmp_sz_duplicate_method
         * @uses whsmsmp_sz_list_methods_screen
         * @uses Woo_Hide_Shipping_Methods_Admin::whsma_updated_message()
         *
         * @access   public
         */
        public static function whsmsmp_sz_output()
        {
            self::$admin_object = new Woo_Hide_Shipping_Methods_Admin( '', '' );
            $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $post_id_request = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
            $cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_whsm_add = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $message = filter_input( INPUT_GET, 'message', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            
            if ( isset( $action ) && !empty($action) ) {
                
                if ( 'add' === $action ) {
                    self::whsmsmp_sz_save_method();
                    self::whsmsmp_sz_add_shipping_method_form();
                } elseif ( 'edit' === $action ) {
                    
                    if ( isset( $cust_nonce ) && !empty($cust_nonce) ) {
                        $getnonce = wp_verify_nonce( $cust_nonce, 'edit_' . $post_id_request );
                        
                        if ( isset( $getnonce ) && 1 === $getnonce ) {
                            self::whsmsmp_sz_edit_method_screen( $post_id_request );
                        } else {
                            wp_safe_redirect( add_query_arg( array(
                                'page' => 'whsm-start-page',
                                'tab'  => 'woo_hide_shipping',
                            ), admin_url( 'admin.php' ) ) );
                            exit;
                        }
                    
                    } elseif ( isset( $get_whsm_add ) && !empty($get_whsm_add) ) {
                        
                        if ( !wp_verify_nonce( $get_whsm_add, 'whsm_add' ) ) {
                            $message = 'nonce_check';
                        } else {
                            self::whsmsmp_sz_edit_method_screen( $post_id_request );
                        }
                    
                    }
                
                } elseif ( 'delete' === $action ) {
                    self::whsmsmp_sz_delete_method( $post_id_request );
                } elseif ( 'duplicate' === $action ) {
                    self::whsmsmp_sz_duplicate_method( $post_id_request );
                } else {
                    self::whsmsmp_sz_list_methods_screen();
                }
            
            } else {
                self::whsmsmp_sz_list_methods_screen();
            }
            
            if ( isset( $message ) && !empty($message) ) {
                self::$admin_object->whsma_updated_message( $message, $get_tab, "" );
            }
        }
        
        /**
         * Delete shipping method
         *
         * @param int $id
         *
         * @access   public
         * @uses Woo_Hide_Shipping_Methods_Admin::whsma_updated_message()
         *
         * @since    3.5
         *
         */
        public static function whsmsmp_sz_delete_method( $id )
        {
            $cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $getnonce = wp_verify_nonce( $cust_nonce, 'del_' . $id );
            
            if ( isset( $getnonce ) && 1 === $getnonce ) {
                wp_delete_post( $id );
                wp_safe_redirect( add_query_arg( array(
                    'page'    => 'whsm-start-page',
                    'tab'     => 'woo_hide_shipping',
                    'message' => 'deleted',
                ), admin_url( 'admin.php' ) ) );
                exit;
            } else {
                self::$admin_object->whsma_updated_message( 'nonce_check', $get_tab, "" );
            }
        
        }
        
        /**
         * Duplicate shipping method
         *
         * @param int $id
         *
         * @access   public
         * @uses Woo_Hide_Shipping_Methods_Admin::whsma_updated_message()
         *
         * @since    1.0.0
         *
         */
        public static function whsmsmp_sz_duplicate_method( $id )
        {
            $cust_nonce = filter_input( INPUT_GET, 'cust_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $getnonce = wp_verify_nonce( $cust_nonce, 'duplicate_' . $id );
            $whsm_add = wp_create_nonce( 'whsm_add' );
            $post_id = ( isset( $id ) ? absint( $id ) : '' );
            $new_post_id = '';
            
            if ( isset( $getnonce ) && 1 === $getnonce ) {
                
                if ( !empty($post_id) || "" !== $post_id ) {
                    $post = get_post( $post_id );
                    $current_user = wp_get_current_user();
                    $new_post_author = $current_user->ID;
                    
                    if ( isset( $post ) && null !== $post ) {
                        $args = array(
                            'comment_status' => $post->comment_status,
                            'ping_status'    => $post->ping_status,
                            'post_author'    => $new_post_author,
                            'post_content'   => $post->post_content,
                            'post_excerpt'   => $post->post_excerpt,
                            'post_name'      => $post->post_name,
                            'post_parent'    => $post->post_parent,
                            'post_password'  => $post->post_password,
                            'post_status'    => 'draft',
                            'post_title'     => $post->post_title . '-duplicate',
                            'post_type'      => self::post_type,
                            'to_ping'        => $post->to_ping,
                            'menu_order'     => $post->menu_order,
                        );
                        $new_post_id = wp_insert_post( $args );
                        $post_meta_data = get_post_meta( $post_id );
                        if ( 0 !== count( $post_meta_data ) ) {
                            foreach ( $post_meta_data as $meta_key => $meta_data ) {
                                if ( '_wp_old_slug' === $meta_key ) {
                                    continue;
                                }
                                $meta_value = maybe_unserialize( $meta_data[0] );
                                update_post_meta( $new_post_id, $meta_key, $meta_value );
                            }
                        }
                    }
                    
                    wp_safe_redirect( add_query_arg( array(
                        'page'     => 'whsm-start-page',
                        'tab'      => 'woo_hide_shipping',
                        'action'   => 'edit',
                        'post'     => $new_post_id,
                        '_wpnonce' => esc_attr( $whsm_add ),
                        'message'  => 'duplicated',
                    ), admin_url( 'admin.php' ) ) );
                    exit;
                } else {
                    wp_safe_redirect( add_query_arg( array(
                        'page'    => 'whsm-start-page',
                        'tab'     => 'woo_hide_shipping',
                        'message' => 'failed',
                    ), admin_url( 'admin.php' ) ) );
                    exit;
                }
            
            } else {
                self::$admin_object->whsma_updated_message( 'nonce_check', $get_tab, "" );
            }
        
        }
        
        /**
         * Count total shipping method
         *
         * @return int $count_method
         * @since    3.5
         *
         */
        public static function whsmsmp_sm_count_method()
        {
            $shipping_method_args = array(
                'post_type'      => self::post_type,
                'post_status'    => array( 'publish', 'draft' ),
                'posts_per_page' => -1,
                'orderby'        => 'ID',
                'order'          => 'DESC',
            );
            $sm_post_query = new WP_Query( $shipping_method_args );
            $shipping_method_list = $sm_post_query->posts;
            return count( $shipping_method_list );
        }
        
        /**
         * Save shipping method when add or edit
         *
         * @param int $method_id
         *
         * @return bool false when nonce is not verified, $zone id, $zone_type is blank, Country also blank, Postcode field also blank, saving error when form submit
         * @uses whsmsmp_sm_count_method()
         *
         * @since    3.5
         *
         * @uses Woo_Hide_Shipping_Methods_Admin::whsma_updated_message()
         */
        private static function whsmsmp_sz_save_method( $method_id = 0 )
        {
            global  $sitepress ;
            $action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $get_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $whsm_save = filter_input( INPUT_POST, 'whsm_save', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            $woocommerce_save_method_nonce = filter_input( INPUT_POST, 'woocommerce_save_method_nonce', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( isset( $action ) && !empty($action) ) {
                
                if ( isset( $whsm_save ) ) {
                    if ( empty($woocommerce_save_method_nonce) || !wp_verify_nonce( sanitize_text_field( $woocommerce_save_method_nonce ), 'woocommerce_save_method' ) ) {
                        self::$admin_object->whsma_updated_message( 'nonce_check', $get_tab, '' );
                    }
                    $sm_status = filter_input( INPUT_POST, 'sm_status', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $fee_settings_product_fee_title = filter_input( INPUT_POST, 'fee_settings_product_fee_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
                    $shipping_method_count = self::whsmsmp_sm_count_method();
                    settype( $method_id, 'integer' );
                    
                    if ( isset( $sm_status ) ) {
                        $post_status = 'publish';
                    } else {
                        $post_status = 'draft';
                    }
                    
                    
                    if ( '' !== $method_id && 0 !== $method_id ) {
                        $fee_post = array(
                            'ID'          => $method_id,
                            'post_title'  => sanitize_text_field( $fee_settings_product_fee_title ),
                            'post_status' => $post_status,
                            'menu_order'  => $shipping_method_count + 1,
                            'post_type'   => self::post_type,
                        );
                        $method_id = wp_update_post( $fee_post );
                    } else {
                        $fee_post = array(
                            'post_title'  => sanitize_text_field( $fee_settings_product_fee_title ),
                            'post_status' => $post_status,
                            'menu_order'  => $shipping_method_count + 1,
                            'post_type'   => self::post_type,
                        );
                        $method_id = wp_insert_post( $fee_post );
                    }
                    
                    
                    if ( '' !== $method_id && 0 !== $method_id ) {
                        
                        if ( $method_id > 0 ) {
                            $fees = filter_input(
                                INPUT_POST,
                                'fees',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $get_condition_key = filter_input(
                                INPUT_POST,
                                'condition_key',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $shipping_method_list = filter_input(
                                INPUT_POST,
                                'shipping_method_list',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $get_cost_rule_match = filter_input(
                                INPUT_POST,
                                'cost_rule_match',
                                FILTER_SANITIZE_FULL_SPECIAL_CHARS,
                                FILTER_REQUIRE_ARRAY
                            );
                            $cost_rule_match = ( isset( $get_cost_rule_match ) ? array_map( 'sanitize_text_field', $get_cost_rule_match ) : array() );
                            $get_shipping_method_list = ( isset( $shipping_method_list ) ? array_map( 'sanitize_text_field', $shipping_method_list ) : array() );
                            $feesArray = array();
                            $conditions_values_array = array();
                            $condition_key = ( isset( $get_condition_key ) ? $get_condition_key : array() );
                            $fees_conditions = $fees['product_fees_conditions_condition'];
                            $conditions_is = $fees['product_fees_conditions_is'];
                            $conditions_values = ( isset( $fees['product_fees_conditions_values'] ) && !empty($fees['product_fees_conditions_values']) ? $fees['product_fees_conditions_values'] : array() );
                            $size = count( $fees_conditions );
                            foreach ( array_keys( $condition_key ) as $key ) {
                                if ( !array_key_exists( $key, $conditions_values ) ) {
                                    $conditions_values[$key] = array();
                                }
                            }
                            uksort( $conditions_values, 'strnatcmp' );
                            foreach ( $conditions_values as $v ) {
                                $conditions_values_array[] = $v;
                            }
                            for ( $i = 0 ;  $i < $size ;  $i++ ) {
                                $feesArray[] = array(
                                    'product_fees_conditions_condition' => $fees_conditions[$i],
                                    'product_fees_conditions_is'        => $conditions_is[$i],
                                    'product_fees_conditions_values'    => $conditions_values_array[$i],
                                );
                            }
                            update_post_meta( $method_id, 'shipping_method_list', $get_shipping_method_list );
                            update_post_meta( $method_id, 'cost_rule_match', maybe_serialize( $cost_rule_match ) );
                            update_post_meta( $method_id, 'sm_metabox', $feesArray );
                            if ( !empty($sitepress) ) {
                                do_action(
                                    'wpml_register_single_string',
                                    'woo-hide-shipping-methods',
                                    sanitize_text_field( $fee_settings_product_fee_title ),
                                    sanitize_text_field( $fee_settings_product_fee_title )
                                );
                            }
                            $getSortOrder = get_option( 'sm_sortable_order' );
                            
                            if ( !empty($getSortOrder) ) {
                                foreach ( $getSortOrder as $getSortOrder_id ) {
                                    settype( $getSortOrder_id, 'integer' );
                                }
                                array_unshift( $getSortOrder, $method_id );
                            }
                            
                            update_option( 'sm_sortable_order', $getSortOrder );
                        }
                    
                    } else {
                        echo  '<div class="updated error"><p>' . esc_html__( 'Error saving shipping method.', 'woo-hide-shipping-methods' ) . '</p></div>' ;
                        return false;
                    }
                    
                    $whsm_add = wp_create_nonce( 'whsm_add' );
                    
                    if ( 'add' === $action ) {
                        wp_safe_redirect( add_query_arg( array(
                            'page'     => 'whsm-start-page',
                            'tab'      => 'woo_hide_shipping',
                            'action'   => 'edit',
                            'post'     => $method_id,
                            '_wpnonce' => esc_attr( $whsm_add ),
                            'message'  => 'created',
                        ), admin_url( 'admin.php' ) ) );
                        exit;
                    }
                    
                    
                    if ( 'edit' === $action ) {
                        wp_safe_redirect( add_query_arg( array(
                            'page'     => 'whsm-start-page',
                            'tab'      => 'woo_hide_shipping',
                            'action'   => 'edit',
                            'post'     => $method_id,
                            '_wpnonce' => esc_attr( $whsm_add ),
                            'message'  => 'saved',
                        ), admin_url( 'admin.php' ) ) );
                        exit;
                    }
                
                }
            
            }
        }
        
        /**
         * Edit shipping method screen
         *
         * @param string $id
         *
         * @uses whsmsmp_sz_save_method()
         * @uses whsmsmp_sz_edit_method()
         *
         * @since    3.5
         *
         */
        public static function whsmsmp_sz_edit_method_screen( $id )
        {
            self::whsmsmp_sz_save_method( $id );
            self::whsmsmp_sz_edit_method();
        }
        
        /**
         * Edit shipping method
         *
         * @since    3.5
         */
        private static function whsmsmp_sz_edit_method()
        {
            include plugin_dir_path( __FILE__ ) . 'form-whsm.php';
        }
        
        /**
         * list_shipping_methods function.
         *
         * @since    3.5
         *
         * @uses WC_Shipping_Methods_Table class
         * @uses WC_Shipping_Methods_Table::process_bulk_action()
         * @uses WC_Shipping_Methods_Table::prepare_items()
         * @uses WC_Shipping_Methods_Table::search_box()
         * @uses WC_Shipping_Methods_Table::display()
         *
         * @access public
         *
         */
        public static function whsmsmp_sz_list_methods_screen()
        {
            if ( !class_exists( 'WC_Shipping_Methods_Table' ) ) {
                require_once plugin_dir_path( dirname( __FILE__ ) ) . 'list-tables/class-wc-hide-shipping-methods-table.php';
            }
            $link = add_query_arg( array(
                'page'   => 'whsm-start-page',
                'tab'    => 'woo_hide_shipping',
                'action' => 'add',
            ), admin_url( 'admin.php' ) );
            require_once plugin_dir_path( __FILE__ ) . 'header/plugin-header.php';
            ?>
			<div class="whsm-section-left">
	            <h1 class="wp-heading-inline">
					<?php 
            echo  esc_html( __( 'Hide Shipping Rules', 'woo-hide-shipping-methods' ) ) ;
            ?>
	            </h1>
	            <a href="<?php 
            echo  esc_url( $link ) ;
            ?>"
	               class="page-title-action dots-btn-with-brand-color"><?php 
            echo  esc_html__( 'Add New', 'woo-hide-shipping-methods' ) ;
            ?></a>
				<?php 
            $request_s = filter_input( INPUT_GET, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS );
            if ( isset( $request_s ) && !empty($request_s) ) {
                echo  sprintf( '<span class="subtitle">' . esc_html__( 'Search results for &#8220;%s&#8221;', 'woo-hide-shipping-methods' ) . '</span>', esc_html( $request_s ) ) ;
            }
            ?>
				<?php 
            $WC_Shipping_Methods_Table = new WC_Shipping_Methods_Table();
            $WC_Shipping_Methods_Table->process_bulk_action();
            $WC_Shipping_Methods_Table->prepare_items();
            $WC_Shipping_Methods_Table->search_box( esc_html__( 'Search', 'woo-hide-shipping-methods' ), 'whsm-shipping' );
            $WC_Shipping_Methods_Table->display();
            ?>
			</div>
			</div>
			</div>
			</div>
			</div>
			<?php 
        }
        
        /**
         * add_shipping_method_form function.
         *
         * @since    3.5
         */
        public static function whsmsmp_sz_add_shipping_method_form()
        {
            include plugin_dir_path( __FILE__ ) . 'form-whsm.php';
        }
    
    }
}