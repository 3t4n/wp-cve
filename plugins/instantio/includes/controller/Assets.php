<?php  
namespace INS\Controller;

class Assets {
    public function __construct() { 
        WC()->frontend_includes();
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_custom_js_scripts' ), 99999 ); 
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_custom_css_scripts' ), 99999 ); 
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 ); 
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) ); 
    } 
    public function enqueue_scripts() { 

        wp_enqueue_style( 'ins-style', apply_filters( 'ins_style_min_status_checked', INS_ASSETS_URL.'/app/css/instantio-style.css' ), array(), INSTANTIO_VERSION ); 
        // wp_enqueue_style( 'ins-style-modern', INS_ASSETS_URL.'/app/css/instantio-modern-style.css', array(), INSTANTIO_VERSION ); 
        wp_enqueue_script( 'ins-gsap-script', INS_ASSETS_URL.'/app/js/gsap.min.js', array('jquery'), INSTANTIO_VERSION, true ); 
        wp_enqueue_script( 'ins-script', apply_filters( 'ins_script_min_status_checked', INS_ASSETS_URL.'/app/js/instantio-script.js' ), array('jquery'), INSTANTIO_VERSION, true ); 
        wp_localize_script( 'ins-script', 'ins_params',
            array( 
                'ins_ajax_nonce'                => wp_create_nonce( 'ins_ajax_nonce' ),
                'ajax_url'                      => admin_url( 'admin-ajax.php' ),
                'cartajax_url'                  => WC()->ajax_url(),
                'wc_ajax_url'                   => \WC_AJAX::get_endpoint( '%%endpoint%%' ),
				'update_shipping_method_nonce'  => wp_create_nonce( 'update-shipping-method' ),
            )
        ); 

        // Enqueue variation scripts.
		wp_enqueue_script( 'wc-add-to-cart-variation' );
        
    }
    
    public function admin_enqueue_scripts() { 
        wp_enqueue_style( 'ins-admin', INS_ASSETS_URL.'/admin/css/instantio-admin-style.css', array(), INSTANTIO_VERSION ); 
        wp_enqueue_script( 'ins-admin-script', INS_ASSETS_URL.'/admin/js/instantio-admin-script.js', array('jquery'), INSTANTIO_VERSION, true ); 

        wp_localize_script( 'ins-admin-script', 'tf_admin_params', 
            array( 
                'ins_nonce' => wp_create_nonce( 'updates' ),
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            ) 
        );
    }

    public function enqueue_custom_css_scripts(){
        $ins_checkout_theme = !empty(insopt( 'ins-toggle-panel-tab' )['ins_panel_Theme_color']) ? insopt( 'ins-toggle-panel-tab' )['ins_panel_Theme_color'] : '#e9570a'; 
       
        $ins_toggle_bg = !empty(insopt( 'ins-toggle-tab' )['wi-header-bg-colors']['regular']) ? insopt( 'ins-toggle-tab' )['wi-header-bg-colors']['regular'] : $ins_checkout_theme; 
        $ins_toggle_bg_hover = !empty(insopt( 'ins-toggle-tab' )['wi-header-bg-colors']['hover']) ? insopt( 'ins-toggle-tab' )['wi-header-bg-colors']['hover'] : '#fffdfd';
        $ins_toggle_border = !empty(insopt( 'ins-toggle-tab' )['wi-header-border-colors']['regular']) ? insopt( 'ins-toggle-tab' )['wi-header-border-colors']['regular'] : '#FEF2EB'; 
        $ins_toggle_border_hover = !empty(insopt( 'ins-toggle-tab' )['wi-header-border-colors']['hover']) ? insopt( 'ins-toggle-tab' )['wi-header-border-colors']['hover'] : '#FEF2EB';
        $ins_toggle_icon = !empty(insopt( 'ins-toggle-tab' )['ins-tog-icon-colors']['regular']) ? insopt( 'ins-toggle-tab' )['ins-tog-icon-colors']['regular'] : '#fff';
        $ins_toggle_icon_hover = !empty(insopt( 'ins-toggle-tab' )['ins-tog-icon-colors']['hover']) ? insopt( 'ins-toggle-tab' )['ins-tog-icon-colors']['hover'] : $ins_checkout_theme;
        $ins_toggle_icon_size = !empty(insopt( 'ins-toggle-tab' )['wi-header-icon-size']) ? insopt( 'ins-toggle-tab' )['wi-header-icon-size'].'px' : '24px';
        $ins_toggle_item_bg = !empty(insopt( 'ins-toggle-tab' )['ins-tog-item-bg']['regular']) ? insopt( 'ins-toggle-tab' )['ins-tog-item-bg']['regular'] : '#ffd200';
        $ins_toggle_item_bg_hover = !empty(insopt( 'ins-toggle-tab' )['ins-tog-item-bg']['hover']) ? insopt( 'ins-toggle-tab' )['ins-tog-item-bg']['hover'] : '#ffd200';
        $ins_toggle_item_color = !empty(insopt( 'ins-toggle-tab' )['wi-header-text-colors']['regular']) ? insopt( 'ins-toggle-tab' )['wi-header-text-colors']['regular'] : '#000';
        $ins_toggle_item_color_hover = !empty(insopt( 'ins-toggle-tab' )['wi-header-text-colors']['hover']) ? insopt( 'ins-toggle-tab' )['wi-header-text-colors']['hover'] : '#000';
        $ins_toggle_item_size = !empty(insopt( 'ins-toggle-tab' )['wi-header-text-size']) ? insopt( 'ins-toggle-tab' )['wi-header-text-size'].'px' : '14px';
  
        $output = '';
        $output .= '
        :root {
            --ins_checkout_theme: '.$ins_checkout_theme.';
            --ins_toggle_bg: '.$ins_toggle_bg.';
            --ins_toggle_hover_bg: '.$ins_toggle_bg_hover.';
            --ins_toggle_border: '.$ins_toggle_border.';
            --ins_toggle_hover_border: '.$ins_toggle_border_hover.';
            --ins_toggle_icon: '.$ins_toggle_icon.';
            --ins_toggle_icon_hover: '.$ins_toggle_icon_hover.';
            --ins_toggle_icon_size: '.$ins_toggle_icon_size.';
            --ins_toggle_item_bg: '.$ins_toggle_item_bg.';
            --ins_toggle_item_bg_hover: '.$ins_toggle_item_bg_hover.';
            --ins_toggle_item_color: '.$ins_toggle_item_color.';
            --ins_toggle_item_color_hover: '.$ins_toggle_item_color_hover.';
            --ins_toggle_item_size: '.$ins_toggle_item_size.'; 
          } 
        ';  

        // ins-toggle-panel-tab
        $ins_toggle_panel_tab = insopt('ins-toggle-panel-tab');

        // ins-toggle-panel-tab
        $ins_panel_border_option = isset(insopt('ins-toggle-panel-tab')['ins_panel_border_option']) && !empty(insopt('ins-toggle-panel-tab')['ins_panel_border_option']) ? insopt('ins-toggle-panel-tab')['ins_panel_border_option'] : false;
       
        if($ins_panel_border_option == true){ 
            $ins_panel_border_top = !empty(insopt( 'ins-toggle-panel-tab' )['ins-panel-border-top']) ? insopt( 'ins-toggle-panel-tab' )['ins-panel-border-top'] : '0';
            $ins_panel_border_right = !empty(insopt( 'ins-toggle-panel-tab' )['ins-panel-border-right']) ? insopt( 'ins-toggle-panel-tab' )['ins-panel-border-right'] : '0';
            $ins_panel_border_bottom = !empty(insopt( 'ins-toggle-panel-tab' )['ins-panel-border-bottom']) ? insopt( 'ins-toggle-panel-tab' )['ins-panel-border-bottom'] : '0';
            $ins_panel_border_left = !empty(insopt( 'ins-toggle-panel-tab' )['ins-panel-border-left']) ? insopt( 'ins-toggle-panel-tab' )['ins-panel-border-left'] : '0';
            $ins_panel_border_color = !empty(insopt( 'ins-toggle-panel-tab' )['ins_panel_border_color']) ? insopt( 'ins-toggle-panel-tab' )['ins_panel_border_color'] : 'transparent';
            $totalleftButton = 90 + (int)$ins_panel_border_left;
			$totalleftButton2 = 80 + (int)$ins_panel_border_left; 


            $output .= '
            
                .ins-checkout-layout {
                    border-left: ' . $ins_panel_border_left . 'px solid !important; 
                    border-right: ' . $ins_panel_border_right . 'px solid !important;
                    border-top: ' . $ins_panel_border_top . 'px solid !important; 
                    border-bottom: ' . $ins_panel_border_bottom . 'px solid !important;
                    border-color: ' . $ins_panel_border_color . ' !important;
                }
                .ins-checkout-layout.ins-hori-right .ins-toggle-btn.tog-1{
                    left: -'. $totalleftButton . 'px;
                }
                .ins-checkout-popup.ins-var-cart-bottom .ins-toggle-btn.tog-2{
                    left: -'. $totalleftButton2 . 'px;
                }
            '; 
        }

        // Panel Style
        $wi_zindex = isset($ins_toggle_panel_tab['wi-zindex']) ? $ins_toggle_panel_tab['wi-zindex'] : '9999';
        $panel_width_1200 = isset($ins_toggle_panel_tab['panel-width-1200']) ? $ins_toggle_panel_tab['panel-width-1200'] : '690';
        $panel_width_1024 = isset($ins_toggle_panel_tab['panel-width-1024']) ? $ins_toggle_panel_tab['panel-width-1024'] : '500';
        $panel_width_767 = isset($ins_toggle_panel_tab['panel-width-767']) ? $ins_toggle_panel_tab['panel-width-767'] : '400';
        $wi_inner_bg_colors_regular = isset($ins_toggle_panel_tab['wi-container-bg']) ? $ins_toggle_panel_tab['wi-container-bg'] : '';
        $ins_panel_text_color = isset($ins_toggle_panel_tab['ins-panel-text-color']) ? $ins_toggle_panel_tab['ins-panel-text-color'] : '#665F5C';
        
        $output .= '
        :root {
            --ins_panel_width_1200: '.$panel_width_1200.'px;
            --ins_panel_width_1024: '.$panel_width_1024.'px;
            --ins_panel_width_767: '.$panel_width_767.'px; 
          } 
        ';  
        $output .= '
                .ins-checkout-layout {
                    background-color: '.$wi_inner_bg_colors_regular.' !important;
                    color: '.$ins_panel_text_color.' !important; 
                    z-index: '.$wi_zindex.' !important;
                }
                .ins-checkout-header-title{
                    color: '.$ins_panel_text_color.' !important;
                }
                .ins-checkout-modern .ins-checkout-layout.slide.ins-hori-left {
                    left: -'.$panel_width_1200.'px  !important;
                }
                .ins-checkout-modern .ins-checkout-layout.slide.ins-hori-left.active {
                    left: 0  !important;
                } 

                @media (max-width: 1024px) {
                    .ins-checkout-modern .ins-checkout-layout.slide.ins-hori-left {
                        left: -'.$panel_width_767.'px  !important;
                    } 
                  }
                
            '; 

        // Panel Button Color
        $ins_panel_button_bg_regular = isset($ins_toggle_panel_tab['ins-panel-button-bg']['regular']) && !empty($ins_toggle_panel_tab['ins-panel-button-bg']['regular']) ? $ins_toggle_panel_tab['ins-panel-button-bg']['regular'] : $ins_checkout_theme;
        $ins_panel_button_bg_hover = isset($ins_toggle_panel_tab['ins-panel-button-bg']['hover']) && !empty($ins_toggle_panel_tab['ins-panel-button-bg']['hover']) ? $ins_toggle_panel_tab['ins-panel-button-bg']['hover'] : $ins_checkout_theme;
        $ins_panel_button_border_regular = isset($ins_toggle_panel_tab['ins-panel-button-border']['regular']) && !empty($ins_toggle_panel_tab['ins-panel-button-border']['regular']) ? $ins_toggle_panel_tab['ins-panel-button-border']['regular'] : $ins_checkout_theme;
        $ins_panel_button_border_hover = isset($ins_toggle_panel_tab['ins-panel-button-border']['hover']) && !empty($ins_toggle_panel_tab['ins-panel-button-border']['hover']) ? $ins_toggle_panel_tab['ins-panel-button-border']['hover'] : $ins_checkout_theme;
        $ins_panel_button_text_regular = isset($ins_toggle_panel_tab['ins-panel-button-text']['regular']) && !empty($ins_toggle_panel_tab['ins-panel-button-text']['regular']) ? $ins_toggle_panel_tab['ins-panel-button-text']['regular'] : '#FCF9F7';
        $ins_panel_button_text_hover = isset($ins_toggle_panel_tab['ins-panel-button-text']['hover']) && !empty($ins_toggle_panel_tab['ins-panel-button-text']['hover']) ? $ins_toggle_panel_tab['ins-panel-button-text']['hover'] : '#FCF9F7';

        $output .= '
                .ins-cart-btns a, .ins-cart-btns button {
                    background-color: '.$ins_panel_button_bg_regular.' !important;
                    color: '.$ins_panel_button_text_regular.' !important;
                    border-color: '.$ins_panel_button_border_regular.' !important;
                }
                .ins-cart-btns a:hover, .ins-cart-btns button:hover {
                    background-color: '.$ins_panel_button_bg_hover.' !important;
                    color: '.$ins_panel_button_text_hover.' !important;
                    border-color: '.$ins_panel_button_border_hover.' !important;
                }
            ';

         // Cart Customize Color
         $cart_header_bg = isset($ins_toggle_panel_tab['cart-header-bg']) && !empty($ins_toggle_panel_tab['cart-header-bg']) ? $ins_toggle_panel_tab['cart-header-bg'] : '#FCF9F7';
         $cart_header_text = isset($ins_toggle_panel_tab['cart-header-text']) && !empty($ins_toggle_panel_tab['cart-header-text']) ? $ins_toggle_panel_tab['cart-header-text'] : '';
 
         $cart_item_bg_wrap = isset($ins_toggle_panel_tab['cart-item-bg-wrap']) && !empty($ins_toggle_panel_tab['cart-item-bg-wrap']) ? $ins_toggle_panel_tab['cart-item-bg-wrap'] : '';
         $cart_item_bg = isset($ins_toggle_panel_tab['cart-item-bg']) && !empty($ins_toggle_panel_tab['cart-item-bg']) ? $ins_toggle_panel_tab['cart-item-bg'] : '';
         $cart_item_text_color = isset($ins_toggle_panel_tab['cart-item-text-color']) && !empty($ins_toggle_panel_tab['cart-item-text-color']) ? $ins_toggle_panel_tab['cart-item-text-color'] : '';
         $cart_input_bg = isset($ins_toggle_panel_tab['cart-input-bg']) && !empty($ins_toggle_panel_tab['cart-input-bg']) ? $ins_toggle_panel_tab['cart-input-bg'] : '';
         $cart_input_text_color = isset($ins_toggle_panel_tab['cart-input-text-color']) && !empty($ins_toggle_panel_tab['cart-input-text-color']) ? $ins_toggle_panel_tab['cart-input-text-color'] : '';
         $cart_pricing_bg = isset($ins_toggle_panel_tab['cart-pricing-bg']) && !empty($ins_toggle_panel_tab['cart-pricing-bg']) ? $ins_toggle_panel_tab['cart-pricing-bg'] : '';
         $cart_pricing_text = isset($ins_toggle_panel_tab['cart-pricing-text']) && !empty($ins_toggle_panel_tab['cart-pricing-text']) ? $ins_toggle_panel_tab['cart-pricing-text'] : ''; 
 
         $cart_button_background_colors_regular = isset($ins_toggle_panel_tab['cart-button-background-colors']['regular']) && !empty($ins_toggle_panel_tab['cart-button-background-colors']['regular']) ? $ins_toggle_panel_tab['cart-button-background-colors']['regular'] : $ins_panel_button_bg_regular;
         $cart_button_background_colors_hover = isset($ins_toggle_panel_tab['cart-button-background-colors']['hover']) && !empty($ins_toggle_panel_tab['cart-button-background-colors']['hover']) ? $ins_toggle_panel_tab['cart-button-background-colors']['hover'] : $ins_panel_button_bg_hover;
 
         $cart_button_text_colors_regular = isset($ins_toggle_panel_tab['cart-button-text-colors']['regular']) && !empty($ins_toggle_panel_tab['cart-button-text-colors']['regular']) ? $ins_toggle_panel_tab['cart-button-text-colors']['regular'] : $ins_panel_button_text_regular;
         $cart_button_text_colors_hover = isset($ins_toggle_panel_tab['cart-button-text-colors']['hover']) && !empty($ins_toggle_panel_tab['cart-button-text-colors']['hover']) ? $ins_toggle_panel_tab['cart-button-text-colors']['hover'] : $ins_panel_button_text_hover;
 
         $output .= ' 
             .ins-checkout-header {
                 background-color: '.$cart_header_bg.' !important;
             }
             
             .ins-checkout-header .ins-checkout-header-title {
                 color: '.$cart_header_text .' !important;
             }
             .ins-checkout-header path {
                fill: '.$cart_header_text .' !important;
             }
             .ins-cart-inner.ins-cart-step .ins-cart-content-wrap {
                 background-color: '.$cart_item_bg_wrap.' !important;
                 color: '.$cart_item_text_color.' !important;
             }
             .ins-checkout-modern .ins-single-cart-item { 
                background-color: '.$cart_item_bg.' !important;
            }
             .ins-cart-inner.ins-cart-step .ins-cart-item-heading span  { 
                 color: '.$cart_item_text_color.' !important;
             }
             .ins-cart-inner.ins-cart-step .ins-cart-qty-wrap .quantity input[type="number"] {
                 background-color: '.$cart_input_bg.' !important;
                 color: '.$cart_input_text_color.' !important;
             }
             .ins-cart-inner.ins-cart-step .ins-cart-footer-content {
                 background-color: '.$cart_pricing_bg.' !important;
                 color: '.$cart_pricing_text.' !important;
             }
             .ins-cart-inner.ins-cart-step .cart_totals h2, .ins-cart-inner.ins-cart-step td,  .ins-cart-inner.ins-cart-step th {
                 background-color: '.$cart_pricing_bg.' !important;
                 color: '.$cart_pricing_text.' !important;
             }
             .ins-cart-inner.ins-cart-step .ins-cart-btns a, .ins-cart-btns a.active {
                 background-color: '.$cart_button_background_colors_regular.' !important;
                 color: '.$cart_button_text_colors_regular.' !important; 
             }
             .ins-cart-inner.ins-cart-step .ins-cart-btns a:hover {
                 background-color: '.$cart_button_background_colors_hover.' !important;
                 color: '.$cart_button_text_colors_hover.' !important; 
             }
         '; 

         
         // Quick View Variation
         $wi_quickview_bg = insopt('wi-quickview-bg');
         $ins_quickview_color = insopt('ins-quickview-color'); 
 
         
         $wi_quickview_bg = isset($wi_quickview_bg) && !empty($wi_quickview_bg) ? $wi_quickview_bg : '#fff';
         $ins_quickview_color = isset($ins_quickview_color) && !empty($ins_quickview_color) ? $ins_quickview_color : '#000'; 
  
         $output .= ' 
             .ins-quick-view {
                 background-color: '.$wi_quickview_bg.' !important;
                 color: '.$ins_quickview_color.' !important;
             }
         '; 

         // custom css from admin option
        $custom_css = !empty(insopt('wi-custom-css')) ? insopt('wi-custom-css') : '';
        $output .= $custom_css; 

        wp_add_inline_style( 'ins-style', $output );
    }

    public function enqueue_custom_js_scripts(){

        $output = ''; 
        $auto_open_toggle = insopt( 'auto-tog-panel' ); 
        $disable_ajax_add_cart = insopt( 'wi-disable-ajax-add-cart' ); 
        $cart_fly_anim = isset(insopt( 'cart-fly' )['cart-fly-anim']) ? insopt( 'cart-fly' )['cart-fly-anim'] : false; 
        $cart_fly_icon = isset(insopt( 'cart-fly' )['cart-fly-icon']) ? insopt( 'cart-fly' )['cart-fly-icon'] : false; 
         
        $noquickview = insopt('woins-quickview-disable');
        $inssinglesetp = insopt( 'ins-layout-step' );

        $ins_empty_cart = !empty(insopt( 'ins-cart-emty-hide' )) ? insopt( 'ins-cart-emty-hide' ) : false;
        if($cart_fly_icon == false){
            $cart_icon = !empty(insopt( 'ins-toggle-tab' )['cart-icon']) ? insopt( 'ins-toggle-tab' )['cart-icon'] : 'shopping-bag';
            $wi_icon_choice = !empty(insopt( 'ins-toggle-tab' )['wi-icon-choice']) ? insopt( 'ins-toggle-tab' )['wi-icon-choice'] : 'icon';
            $wi_icon_choice_uploder = !empty(insopt( 'ins-toggle-tab' )['wi-icon-choice-uploder']) ? insopt( 'ins-toggle-tab' )['wi-icon-choice-uploder'] : '';
            if($cart_icon == 'shopping-bag'){

                $cart_fly_icon = instantio_svg_icon($cart_icon);
            }else{
                $cart_fly_icon = '<i class="'.$cart_icon.'"></i>';
            }
            if($wi_icon_choice == 'image' && $wi_icon_choice_uploder !=''){
                $cart_fly_icon = '<img src="'.$wi_icon_choice_uploder.'" alt="Icon Image">';
            }
        } 
        $output .=  isset($ins_empty_cart) && !empty($ins_empty_cart) ? 'var hide_toggler = '.$ins_empty_cart.';' : 'var hide_toggler = false;';
        $output .=  isset($auto_open_toggle) && $auto_open_toggle == true ? 'var auto_open_toggle = true;' : 'var auto_open_toggle = false;';
        $output .=  isset($cart_fly_anim) && $cart_fly_anim == true ? 'var cart_fly_anim = true;' : 'var cart_fly_anim = false;';
        $output .=  isset($cart_fly_icon) && $cart_fly_icon != false ? 'var cart_fly_icon = `'.$cart_fly_icon.'`;' : 'var cart_fly_icon = false;';  
        $output .=  isset($disable_ajax_add_cart) && $disable_ajax_add_cart != false ? 'var disable_ajax_add_cart = '.$disable_ajax_add_cart.';' : 'var disable_ajax_add_cart = false;';  

        $output .=  isset($noquickview) && $noquickview != false ? 'var noquickview = '.$noquickview.';' : 'var  noquickview = false;';
        
        $output .=  isset($inssinglesetp) && $inssinglesetp != false ? 'var inslayoutstep = '.$inssinglesetp.';' : 'var  inslayoutstep = false;'; 

        
         
		wp_add_inline_script( 'ins-script', $output );
    }

    
}

?>