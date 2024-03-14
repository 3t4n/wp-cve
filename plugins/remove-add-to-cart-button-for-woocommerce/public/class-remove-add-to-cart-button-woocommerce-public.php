<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpartisan.net/
 * @since      1.0.0
 *
 * @package    Remove_Add_To_Cart_Button_Woocommerce
 * @subpackage Remove_Add_To_Cart_Button_Woocommerce/public
 */
/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Remove_Add_To_Cart_Button_Woocommerce
 * @subpackage Remove_Add_To_Cart_Button_Woocommerce/public
 * @author     wpArtisan
 */
class Remove_Add_To_Cart_Button_Woocommerce_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * To hide add to cart button from shop archive page.
     *
     * @since    1.0.5
     */
    public function ratcw_woocommerce_loop_add_to_cart_link( $output, $product, $args )
    {
        $product_id = $product->get_id();
        
        if ( $product_id ) {
            $ratcw_remove_cart_button_for = get_post_meta( $product_id, 'ratcw_remove_cart_button_for', true );
            
            if ( isset( $ratcw_remove_cart_button_for ) && !empty($ratcw_remove_cart_button_for) && $ratcw_remove_cart_button_for === 'for-all' ) {
                $output = '';
                $output .= $this->ratcw_woocommerce_after_shop_loop_item_text_instead_button( $product );
            }
            
            if ( isset( $ratcw_remove_cart_button_for ) && !empty($ratcw_remove_cart_button_for) && $ratcw_remove_cart_button_for === 'hide-only-for-visitor' ) {
                
                if ( !is_user_logged_in() ) {
                    $output = '';
                    $output .= $this->ratcw_woocommerce_after_shop_loop_item_text_instead_button( $product );
                }
            
            }
        }
        
        return $output;
    }
    
    /**
     * To hide add to cart button from shop single page.
     *
     * @since    1.0.0
     */
    public function ratcw_remove_add_to_cart_button_from_single_product()
    {
        global  $product ;
        $product_id = $product->get_id();
        $product_get_type = $product->get_type();
        
        if ( $product_id ) {
            $ratcw_remove_cart_button_for = get_post_meta( $product_id, 'ratcw_remove_cart_button_for', true );
            
            if ( isset( $ratcw_remove_cart_button_for ) && !empty($ratcw_remove_cart_button_for) && $ratcw_remove_cart_button_for === 'for-all' ) {
                remove_action( 'woocommerce_' . $product_get_type . '_add_to_cart', 'woocommerce_' . $product_get_type . '_add_to_cart', 30 );
                add_action( 'woocommerce_' . $product_get_type . '_add_to_cart', array( $this, 'ratcw_woocommerce_template_single_text_instead_button' ), 30 );
            }
            
            if ( isset( $ratcw_remove_cart_button_for ) && !empty($ratcw_remove_cart_button_for) && $ratcw_remove_cart_button_for === 'hide-only-for-visitor' ) {
                
                if ( !is_user_logged_in() ) {
                    remove_action( 'woocommerce_' . $product_get_type . '_add_to_cart', 'woocommerce_' . $product_get_type . '_add_to_cart', 30 );
                    add_action( 'woocommerce_' . $product_get_type . '_add_to_cart', array( $this, 'ratcw_woocommerce_template_single_text_instead_button' ), 30 );
                }
            
            }
        }
    
    }
    
    /**
     * Product add to cart modified text.
     *
     * @since    1.0.5
     */
    public function ratcw_woocommerce_after_shop_loop_item_text_instead_button( $product )
    {
        $msg = '';
        $product_id = $product->get_id();
        
        if ( $product_id ) {
            $ratcw_remove_cart_button_for = get_post_meta( $product_id, 'ratcw_remove_cart_button_for', true );
            
            if ( empty($ratcw_remove_cart_button_for) ) {
            } else {
                $ratcw_msg_instead_cart_button = get_post_meta( $product_id, 'ratcw_msg_instead_cart_button', true );
            }
            
            if ( !empty(trim( $ratcw_msg_instead_cart_button )) ) {
                $msg .= '<p class="ratcw-message">' . esc_html__( $ratcw_msg_instead_cart_button, 'remove-add-to-cart-button-woocommerce' ) . '</p>';
            }
            $ratcw_show_login_btn_when_cart_button_hidden = get_post_meta( $product_id, 'ratcw_show_login_btn_when_cart_button_hidden', true );
            if ( isset( $ratcw_show_login_btn_when_cart_button_hidden ) && !empty($ratcw_show_login_btn_when_cart_button_hidden) && $ratcw_show_login_btn_when_cart_button_hidden === 'yes' && !empty($ratcw_remove_cart_button_for) && $ratcw_remove_cart_button_for == 'hide-only-for-visitor' || !empty($ratcw_remove_cart_button_for) && $ratcw_remove_cart_button_for == 'hide-by-user-role' && !is_user_logged_in() ) {
                $msg .= $this->ratcw_get_login_reg_button();
            }
        }
        
        return $msg;
    }
    
    /**
     * Product add to cart modified text.
     *
     * @since    1.0.0
     */
    public function ratcw_woocommerce_template_single_text_instead_button()
    {
        global  $product ;
        $product_id = $product->get_id();
        
        if ( $product_id ) {
            $ratcw_remove_cart_button_for = get_post_meta( $product_id, 'ratcw_remove_cart_button_for', true );
            
            if ( empty($ratcw_remove_cart_button_for) ) {
            } else {
                $ratcw_msg_instead_cart_button = get_post_meta( $product_id, 'ratcw_msg_instead_cart_button', true );
            }
            
            if ( !empty(trim( $ratcw_msg_instead_cart_button )) ) {
                echo  '<p class="ratcw-message">' . esc_html__( $ratcw_msg_instead_cart_button, 'remove-add-to-cart-button-woocommerce' ) . '</p>' ;
            }
            $ratcw_show_login_btn_when_cart_button_hidden = get_post_meta( $product_id, 'ratcw_show_login_btn_when_cart_button_hidden', true );
            if ( isset( $ratcw_show_login_btn_when_cart_button_hidden ) && !empty($ratcw_show_login_btn_when_cart_button_hidden) && $ratcw_show_login_btn_when_cart_button_hidden === 'yes' && !empty($ratcw_remove_cart_button_for) && $ratcw_remove_cart_button_for == 'hide-only-for-visitor' || !empty($ratcw_remove_cart_button_for) && $ratcw_remove_cart_button_for == 'hide-by-user-role' && !is_user_logged_in() ) {
                echo  $this->ratcw_get_login_reg_button() ;
            }
        }
    
    }
    
    /**
     * Get Login/Register button.
     *
     * @since    1.0.1
     */
    public function ratcw_get_login_reg_button()
    {
        $ratcw_login_reg_page_url = get_option( 'ratcw_login_reg_page_url' );
        if ( empty($ratcw_login_reg_page_url) ) {
            $ratcw_login_reg_page_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
        }
        $ratcw_login_reg_button_text = get_option( 'ratcw_login_reg_button_text' );
        if ( empty($ratcw_login_reg_button_text) ) {
            $ratcw_login_reg_button_text = 'Login/Register';
        }
        return '<a href="' . esc_url( $ratcw_login_reg_page_url ) . '" class="wcuo-login-button button" title="' . esc_attr__( $ratcw_login_reg_button_text, 'remove-add-to-cart-button-woocommerce' ) . '">' . esc_html__( $ratcw_login_reg_button_text, 'remove-add-to-cart-button-woocommerce' ) . '</a>';
    }
    
    /**
     * Hide product price based on settings.
     *
     * @since    1.0.0
     */
    public function ratcw_hide_price_for_product( $price, $product )
    {
        
        if ( !is_admin() ) {
            $product_id = $product->get_id();
            $ratcw_hide_price = get_post_meta( $product_id, 'ratcw_hide_price', true );
            
            if ( isset( $ratcw_hide_price ) && !empty($ratcw_hide_price) && $ratcw_hide_price === 'yes' ) {
                $ratcw_remove_cart_button_for = get_post_meta( $product_id, 'ratcw_remove_cart_button_for', true );
                
                if ( isset( $ratcw_remove_cart_button_for ) && !empty($ratcw_remove_cart_button_for) ) {
                    if ( $ratcw_remove_cart_button_for === 'for-all' ) {
                        $price = '';
                    }
                    if ( $ratcw_remove_cart_button_for === 'hide-only-for-visitor' ) {
                        if ( !is_user_logged_in() ) {
                            $price = '';
                        }
                    }
                } else {
                    $price = '';
                }
            
            }
        
        }
        
        return $price;
    }
    
    /**
     * To hide the price from the cart and checkout pages.
     *
     * @since    1.0.0
     */
    public function ratcw_hide_cart_item_price( $price, $cart_item, $cart_item_key )
    {
        $product_id = $cart_item['product_id'];
        $ratcw_hide_price = get_post_meta( $product_id, 'ratcw_hide_price', true );
        
        if ( isset( $ratcw_hide_price ) && !empty($ratcw_hide_price) && $ratcw_hide_price === 'yes' ) {
            $ratcw_remove_cart_button_for = get_post_meta( $product_id, 'ratcw_remove_cart_button_for', true );
            
            if ( isset( $ratcw_remove_cart_button_for ) && !empty($ratcw_remove_cart_button_for) ) {
                if ( $ratcw_remove_cart_button_for === 'for-all' ) {
                    $price = '';
                }
                if ( $ratcw_remove_cart_button_for === 'hide-only-for-visitor' ) {
                    if ( !is_user_logged_in() ) {
                        $price = '';
                    }
                }
            } else {
                $price = '';
            }
        
        }
        
        return $price;
    }
    
    /**
     * To hide the price from the cart and checkout pages.
     *
     * @since    1.0.0
     */
    public function ratcw_hide_cart_item_subtotal( $price, $cart_item, $cart_item_key )
    {
        $product_id = $cart_item['product_id'];
        $ratcw_hide_price = get_post_meta( $product_id, 'ratcw_hide_price', true );
        
        if ( isset( $ratcw_hide_price ) && !empty($ratcw_hide_price) && $ratcw_hide_price === 'yes' ) {
            $ratcw_remove_cart_button_for = get_post_meta( $product_id, 'ratcw_remove_cart_button_for', true );
            
            if ( isset( $ratcw_remove_cart_button_for ) && !empty($ratcw_remove_cart_button_for) ) {
                if ( $ratcw_remove_cart_button_for === 'for-all' ) {
                    $price = '';
                }
                if ( $ratcw_remove_cart_button_for === 'hide-only-for-visitor' ) {
                    if ( !is_user_logged_in() ) {
                        $price = '';
                    }
                }
            } else {
                $price = '';
            }
        
        }
        
        return $price;
    }

}