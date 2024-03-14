<?php

if( !class_exists('PVTFW_CART' ) ):

    class PVTFW_CART{

        protected static $_instance = null;

        public function __construct(){
            $this->register();
        }
        

        public static function instance() {
            if( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }

            return self::$_instance;
        }


        /**
         * ====================================================
         * Add To Cart JS Function 
         * ====================================================
         */

        function ajax_wc_button_script() {
        ?>
            <script id="pvtfw-add-to-cart-js">
            jQuery(document).ready(function($) {
                $(document.body).on('click', '.pvtfw_variant_table_cart_btn', function(e) {
                    e.preventDefault();
                    var $thisbutton = $(this);
            
                    var id = $(this).data("product-id");
                    var site_url = $(this).data("url");
                    var variant_id = $(this).data("variant");
                    var qty = $(this).closest('.variant tbody tr').find('.qty').val();

                    // @note: checking if quantity is `0`. Then don't run rest of script
                    if( qty == 0 ){
                        return;
                    }
            
                
            
                    var data = {
                        action: 'pvtfw_woocommerce_ajax_add_to_cart',
            
                        product_id: id,
            
                        product_sku: '',
            
                        quantity: qty,
            
                        variation_id: variant_id,
                    };
            
            
                    $.ajax({
            
                        type: 'post',
            
                        url: "<?php echo esc_attr( admin_url( 'admin-ajax.php' ) ); ?>",
            
                        data: data,
            
                        beforeSend: function(response) {
            
                            $thisbutton.prop('disabled', true).find('.spinner-wrap').css('display',
                                'inline-block').show();
            
                        },
            
                        complete: function(response) {
            
                            $thisbutton.find('.spinner-wrap').hide();
                            $thisbutton.removeClass("success_pvtfw_btn").prop('disabled', false);
            
                        },
            
                        success: function(response) {
                            // console.log(response);
            
                            if (response.error & response.product_url) {
            
                                window.location = response.product_url;
            
                                return;
            
                            } else {

                                // Trigger Function (Located Below)
                                <?php $this->added_to_cart_trigger(); ?>

                                $(document.body).trigger('wc_fragment_refresh');
            
                                // Remove existing notices
                                $('.woocommerce-error, .woocommerce-message, .woocommerce-info').remove();
            
                                $('.woocommerce-notices-wrapper').html(response.fragments.notices_html);
            
                                // Returning success with error so removed the success
            
                                if ($('.woocommerce-notices-wrapper').find('.woocommerce-error').length > 0) {
                                    $('.woocommerce-notices-wrapper .woocommerce-message').remove();
                                }
            
                                <?php $scrollToTop = PVTFW_COMMON::pvtfw_get_options()->scrollToTop; if($scrollToTop == "on"): ?>
                                        $("html, body").animate({
                                            scrollTop: 0
                                        }, "slow");
                                        return false;
                                <?php endif; ?>

            
                            }
            
                        },
            
                    });
                })
            });
            </script>
        <?php
        }


        /**
         * ====================================================
         * Ajax Carting Code.
         * ====================================================
         */

        function woocommerce_ajax_add_to_cart() {  

            $product_id = apply_filters('pvtfw_woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        
            $prepare_quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);

            $quantity = apply_filters('pvtfw_ajax_cart_prepare_quantity', $prepare_quantity);
        
            $variation_id = absint($_POST['variation_id']);
        
            $passed_validation = apply_filters('pvtfw_woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        
            $product_status = get_post_status($product_id); 
        
            if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) { 
        
                do_action('pvtfw_woocommerce_ajax_added_to_cart', $product_id);
        
                    if ('yes' === get_option('pvtfw_woocommerce_cart_redirect_after_add')) { 
        
                        return wc_add_to_cart_message(array($product_id => $quantity), true); 
                    } 
        
                    wc_add_to_cart_message(  $product_id,  $quantity ,  $return = false ); 
                    WC_AJAX :: get_refreshed_fragments(); 
        
            } 
            else { 
        
                // $data = array( 
        
                // 	'error' => true,
        
                // 	'product_url' => apply_filters('pvtfw_woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id),
        
        
                // );
        
                // echo wp_send_json($data);
                wc_add_to_cart_message(  $product_id,  $quantity ,  $return = false ); 
                WC_AJAX :: get_refreshed_fragments(); 
        
            }
        
            wp_die();
        
        }

        /**
         * ====================================================
         * Add fragments for notices.
         * ====================================================
         */
        function ajax_add_to_cart_add_fragments( $fragments ) {

            $cartNotice = PVTFW_COMMON::pvtfw_get_options()->cartNotice;

                $all_notices  = WC()->session->get( 'wc_notices', array() );
            
            
                $notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );

                if($cartNotice == 'on'):
                    ob_start();
                    foreach ( $notice_types as $notice_type ) {
                        if ( wc_notice_count( $notice_type ) > 0 ) {
                            wc_get_template( "notices/{$notice_type}.php", array(
                                'notices' => array_filter( $all_notices[ $notice_type ] ),
                            ) );
                        }
                    }
                    $fragments['notices_html'] = ob_get_clean();

                    wc_clear_notices();

                    return $fragments;
                else:
                    return false;
                endif;
        }

        /**
         * ====================================================
         * Added cart JS trigger
         * ====================================================
         */
        function added_to_cart_trigger(){
            //============== Added Cart Trigger ===============
            
            $cart_url = apply_filters('pvtfw_cart_redirect_url', site_url('/cart') );

            if ('yes' === get_option('woocommerce_cart_redirect_after_add')) { 
                
                $script = sprintf('window.location.href = "%s"; return;', $cart_url);
            }

            else{

                $script = sprintf('$(document.body).trigger("added_to_cart", [response.fragments, response.cart_hash, $thisbutton]);');

            }


            echo apply_filters('pvtfw_added_cart_filter', $script);
            //============== Added Cart Trigger ===============
        }


        /**
        *====================================================
        * Register
        *====================================================
        **/

        public function register(){
            // Cart Hooks
            add_action('wp_footer', array( $this, 'ajax_wc_button_script' ), 99 );
            add_action('wp_ajax_pvtfw_woocommerce_ajax_add_to_cart',  array( $this, 'woocommerce_ajax_add_to_cart' ) ); 
            add_action('wp_ajax_nopriv_pvtfw_woocommerce_ajax_add_to_cart', array( $this, 'woocommerce_ajax_add_to_cart' ) ); 

            // Add to cart Fragment
            add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'ajax_add_to_cart_add_fragments' ) );

        }

    }

    $pvtfw_cart = PVTFW_CART::instance();

endif;