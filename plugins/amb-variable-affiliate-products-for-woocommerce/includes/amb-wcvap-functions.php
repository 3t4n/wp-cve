<?php

defined( 'ABSPATH' ) or die( 'No.' );

if ( !class_exists( 'AMB_WC_Product_Variable_Affiliate' ) ) {
    class AMB_WC_Product_Variable_Affiliate {

        public function add_variation_custom_fields( $loop, $variation_data, $variation ) {
            $value = (string) esc_url_raw( get_post_meta( $variation->ID, '_amb_wpvap_variation_url', true ) );            
            echo "<div class='amb_wpvap_variation_url'>";
            woocommerce_wp_text_input(
                array(
                    'id'                    => '_amb_wpvap_variation_url[' . $variation->ID . ']',
                    'label'                 => __( 'Product URL', 'woocommerce' ),
                    'placeholder'           => 'http://example.com/product',
                    'desc_tip'              => 'true',
                    'description'           => __( 'Enter the affiliate buy URL for this product. Defaults to the parent product URL if empty. Only necessary if "Affiliate Product" is enabled in the "Inventory" tab.', 'woocommerce' ),
                    'class'                 => 'show_if_variable',
                    'value'                 => $value
                )
            );
            echo "</div>";
        }

        public function add_custom_fields( $types ){
            global $woocommerce, $post;

            echo "<div class='amb_wpvap_opt'>";
            woocommerce_wp_checkbox(
                array(
                    'id'            => '_amb_vap_prod',
                    'label'         => __( 'Affiliate Product', 'woocommerce' ),
                    'description'   => __( 'Enable', 'woocommerce' ),
                    )
                );
            echo "</div>";

            echo "<div class='amb_wpvap_url'>";
            woocommerce_wp_text_input(
                array(
                    'id'                    => '_amb_vap_prod_url',
                    'label'                 => __( 'Product URL (required)', 'woocommerce' ),
                    'placeholder'           => 'http://example.com/product',
                    'desc_tip'              => 'true',
                    'description'           => __( 'Enter the affiliate buy URL for this product.', 'woocommerce' )
                )
            );
            echo "</div>";

            echo "<div class='ambv_wpvap_cart_text'>";

            woocommerce_wp_text_input(
                array(
                    'id'                => '_amb_vap_prod_cart_text',
                    'label'             => __( 'Buy button text', 'woocommerce' ),
                    'placeholder'       => 'Buy',
                    'desc_tip'          => 'true',
                    'description'       => __( 'Enter the text you want for the buy button.', 'woocommerce' )
                )
            );
            echo "</div>";

        }

        public function remove_add_to_cart_message( $message, $products ) {
            if ( is_array( $products ) ) {
                reset( $products );
                $product_id = key( $products );
            }            
            if ( get_post_meta( $product_id, '_amb_vap_prod', true ) === 'yes' ) {
                return;
            } else {
                return $message;
            }
        }

        public function redirect( $url ) {
            global $woocommerce;

            if ( isset( $_REQUEST['variation_id'] ) && $_REQUEST['variation_id'] !== 0 ) {

                // This is a variation, so we're going to try to get the variation URL first.
                $id = absint( $_REQUEST['variation_id'] );
                if ( isset( $_REQUEST['add-to-cart'] ) ) {
                    $parent_id = absint( $_REQUEST['add-to-cart'] );
                } else {
                    return $url;
                }
            } else {
                return $url;
            }

            if ( ! $id ) {
                return $url;
            }

            $enabled = get_post_meta( $parent_id, '_amb_vap_prod', true );

            if ( $enabled == 'yes' ) {

                $id = apply_filters( 'woocommerce_add_to_cart_product_id', $id );

                $rurl = $this->get_url( $id, $parent_id );

                if ( ! empty( $rurl ) ) {

                    // Remove item from cart.
                    $items = $woocommerce->cart->get_cart();
                    foreach ( $items as $key => $data ) {
                        if ( $data['product_id'] == $id || $data['variation_id'] == $id ) {
                            $woocommerce->cart->remove_cart_item( $key );
                        }
                    }

                    wp_redirect( $rurl );
                    exit;

                } else {

                    // Remove item from cart.
                    $items = $woocommerce->cart->get_cart();
                    foreach ( $items as $key => $data ) {
                        if ( $data['product_id'] == $id || $data['variation_id'] == $id ) {
                            $woocommerce->cart->remove_cart_item( $key );
                        }
                    }
                    return $url;
                }
            } else {
                return $url;
            }
        }

        public function get_url( $id, $parent_id ) {
            $enabled = get_post_meta( $parent_id, '_amb_vap_prod', true );
                if ( $enabled == 'yes' ) {
                    if ( $aff_url = get_post_meta( $id, '_amb_wpvap_variation_url', true ) ) {
                        return esc_url_raw( $aff_url );
                    } elseif ( $aff_url = get_post_meta( $parent_id, '_amb_vap_prod_url', true ) ) {
                        return esc_url_raw( $aff_url );
                    } else {
                        return false;
                    }
                }
            return false;
        }

        public function cart_text( $text ) {
            global $product;
            $prod_id = $product->get_id();

            $enabled = get_post_meta( $prod_id, '_amb_vap_prod', true );

            if ( $enabled == 'yes' ) {
                if ( $cart_text = get_post_meta( $prod_id, '_amb_vap_prod_cart_text', true ) ) {
                    if ( ! empty( $cart_text ) ) {
                        return __( esc_attr( $cart_text ), 'woocommerce' );
                    }
                }
            }

            return $text;
        }

        public function admin_notices() {
            if ( $screen = get_current_screen() ) {
                if ( $screen->id === 'product' ) {
                    $empty_url = get_option( 'amb_wcvap_empty_url', false );
                    if ( $empty_url ) {
                        $class = 'notice notice-error is-dismissible ambvapemptyurl';
                        $message = __( 'Affiliate product option disabled because Product URL field is empty. Even if using variation URLs, a fallback URL is required.', 'woocommerce' );

                        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                        delete_option( 'amb_wcvap_empty_url' );
                    }

                    $manage_stock = get_option( 'amb_wpvap_manage_stock_admin_notice', false );

                    if ( $manage_stock ) {
                        $class = 'notice notice-error is-dismissible ambvapmanagestock';
                        $message = __( 'Manage stock option disabled because you cannot manage stock for a Variable Affiliate Product.', 'woocommerce' );

                        printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                        delete_option( 'amb_wpvap_manage_stock_admin_notice' );
                    }
                }
            }

            if ( ! class_exists( 'WooCommerce' ) ) {
                $class = 'notice notice-error is-dismissible ambvapwooco';
                $message = __( 'WooCommerce is required for \'AMB Variable Affiliate Products for WooCommerce\' to function. Please activate WooCommerce, then re-activate this plugin.', 'woocommerce' );

                printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
                deactivate_plugins( AMB_WCVAP_PLUGIN_PATH );
            }
        }

        public function after_save_processes( $product_id ) {
            $product = wc_get_product( $product_id );
            if ( $product ) {
                if ( ! $product->is_type( 'variable' ) ) {
                    $this->delete_all_meta( $product_id );
                } else {
                    if ( array_key_exists( '_amb_vap_prod', $_POST ) ) {
                        update_post_meta( $product_id, '_amb_vap_prod', 'yes' );
                        $this->disable_stock_meta( $product_id );
                        if ( array_key_exists( '_amb_vap_prod_url', $_POST ) ) {
                            $affiliate_url = esc_url_raw( $_POST['_amb_vap_prod_url'] );
                            if ( ! empty( $affiliate_url ) && substr( $affiliate_url, 0, 4 ) === 'http' ) {
                                update_post_meta( $product_id, '_amb_vap_prod_url', $affiliate_url );
                            } else {

                                // Disable the affiliate option completely and remove all fields.
                                $this->delete_all_meta( $product_id );

                                // Display admin notice.
                                update_option( 'amb_wcvap_empty_url', true );

                                return;
                            }
                        }
                        if ( array_key_exists( '_amb_vap_prod_cart_text', $_POST ) ) {
                            $buy_button_text = sanitize_text_field( $_POST['_amb_vap_prod_cart_text'] );
                            if ( ! empty( $buy_button_text ) ) {
                                update_post_meta( $product_id, '_amb_vap_prod_cart_text', esc_attr( $buy_button_text ) );
                            } else {
                                update_post_meta( $product_id, '_amb_vap_prod_cart_text', null );
                            }
                        }
                    } elseif ( ! array_key_exists( '_amb_vap_prod', $_POST ) && array_key_exists( '_amb_wpvap_variation_url', $_POST ) && array_key_exists( 'variable_post_id', $_POST ) ) {
                        $variable_post_id = reset( $_POST['variable_post_id'] );
                        $this->after_save_variation_processes( absint( $variable_post_id ), $_POST );
                    } else {
                        $this->delete_all_meta( $product_id );
                    }
                }
            }
        }

        public function after_save_variation_processes( $variation_id, $postdata = '' ) {
            if ( empty( $_POST ) && ! empty( $postdata ) ) {
                $_POST = $postdata;
            }
            if ( array_key_exists( '_amb_wpvap_variation_url', $_POST ) ) {
                if ( array_key_exists( $variation_id, $_POST['_amb_wpvap_variation_url'] ) ) {
                    $variation_url = (string) esc_url_raw( $_POST['_amb_wpvap_variation_url'][ $variation_id ] );
                    if ( substr( $variation_url, 0, 4 ) !== 'http' ) {
                        $variation_url = null;
                    }
                } else {
                    $variation_url = null;
                }
            }

            if ( array_key_exists( '_amb_vap_prod', $_POST ) ) {
                $enabled = sanitize_text_field( $_POST['_amb_vap_prod'] );
                $enabled = esc_attr( $enabled );
                if ( $enabled == 'yes' ) {
                    $this->disable_stock_meta( $variation_id, true );
                }              
            } else {
                $var_product = wc_get_product( $variation_id );
                if ( $var_product ) {
                    $parent_product = $var_product->get_parent_id();
                    if ( get_post_meta( $parent_product, '_amb_vap_prod', true ) == 'yes' ) {
                        $this->disable_stock_meta( $variation_id, true );
                    }
                }
            }

            if ( array_key_exists( '_amb_vap_prod_url', $_POST ) ) {
                $parent_url = esc_url_raw( $_POST['_amb_vap_prod_url'] );
            }

            if ( ! empty( $variation_url ) && $variation_url != '' ) {
                update_post_meta( $variation_id, '_amb_wpvap_variation_url', $variation_url );
            } else {
                update_post_meta( $variation_id, '_amb_wpvap_variation_url', null );
            }
        }

        public function delete_all_meta( $product_id ) {
            $importing = get_option( 'wcvap_enable_importing_mode', 'no' );
            $product   = wc_get_product( $product_id );
            
            if ( $product ) {
                if ( $importing == 'no' || ! in_array( $product->get_type(), [ 'variable', 'variation'] ) ) {
                    delete_post_meta( $product_id, '_amb_vap_prod' );
                    delete_post_meta( $product_id, '_amb_vap_prod_url' );
                    delete_post_meta( $product_id, '_amb_vap_prod_cart_text' );
                    delete_post_meta( $product_id, '_amb_wpvap_variation_url' );
                }
            }
            
        }

        public function disable_stock_meta( $id, $is_variation = false ) {
            if ( get_post_meta( $id, '_manage_stock', true ) === 'yes' ) {
                update_option( 'amb_wpvap_manage_stock_admin_notice', true );
            }
            update_post_meta( $id, '_manage_stock', false );
            update_post_meta( $id, '_stock_status', 'instock' );
            update_post_meta( $id, '_backorders', 'no' );
            if ( ! $is_variation ) {
                update_post_meta( $id, '_sold_individually', 'yes' );
            }
        }

        public function importing_mode_option( $settings ) {
            $settings[] = array(
                'title'	=>	'Variable Affiliate Products',
                'type'	=>	'title',
                'desc'	=>	'',
                'id'	=>	'wcvap_product_options'
            );
        
            $settings[] = array(
                'name'     => __( 'Importing Mode', 'woocommerce' ),
                'desc_tip' => __( 'Enable this option before importing products in bulk with WP All Import or the WooCommerce Importer.', 'woocommerce' ),
                'id'       => 'wcvap_enable_importing_mode',
                'type'     => 'checkbox',
                'std'      => 'no',
                'default'  => 'no',
                'desc'     => __( 'Enable importing mode', 'woocommerce' ),
            );
        
            $settings[] = array(
                'type'	=>	'sectionend',
                'id'	=>	'wcvap_product_options'
            );
        
            return $settings;
        }
    }
}