<?php

class Pi_Edd_Template_Engine{
    private $location;

    private $color;

    private $bg_color;

    function __construct(){
        
        add_action( 'wp_enqueue_scripts', array($this,'register_plugin_styles') );

    }

    static function message($location, $message, $return = false){
        
        $location_class = self::locationClassName($location);
        
        if($return){
            $msg ="";
            if($message != ""){
            $msg .= '<div class="pi-edd '.$location_class.'">';
            $msg .= $message;
            $msg .= '</div>';
            }
            return $msg;
        }

        if($message != ""){
        echo '<div class="pi-edd '.$location_class.'">';
        echo $message;
        echo '</div>';
        }
    }

    static function locationClassName($location){
       
        if($location == 'single' || $location == 'single_range'){
            return 'pi-edd-product';
        }

        if($location == 'loop' || $location == 'loop_range'){
            return 'pi-edd-loop';
        }

        if($location == 'cart' || $location == 'cart_range'){
            return 'pi-edd-cart';
        }

        return 'pi-edd-product';
    }

    function register_plugin_styles(){
        wp_register_style( 'pi-edd-template', false );
        wp_enqueue_style( 'pi-edd-template' );

        $padding_x = 5;
        $padding_y = 5;

        $css = '
            .pi-edd{
                text-align:center;
                margin-top:5px;
                margin-bottom:5px;
                font-size:12px;
                border-radius:6px;
            }

            .pi-edd span{
                font-weight:bold;
            }

            .pi-edd-product{
                background:'.get_option('pi_product_bg_color','#f0947e').';
                color:'.get_option('pi_product_text_color','#ffffff').';
                padding: '.$padding_y.'px '.$padding_x.'px;
                margin-top:1rem;
                margin-bottom:1rem;
                clear:both;
            }

            .pi-edd-loop{
                background:'.get_option('pi_loop_bg_color','#f0947e').';
                color:'.get_option('pi_loop_text_color','#ffffff').';
                padding: '.$padding_y.'px '.$padding_x.'px;
            }

            .pi-edd-cart{
                background:'.get_option('pi_cart_bg_color','#f0947e').';
                color:'.get_option('pi_cart_text_color','#ffffff').';
                padding: '.$padding_y.'px '.$padding_x.'px;
                text-align:left;
                display:inline-block;
                padding:0px 10px;
            }
        ';
        

        wp_add_inline_style( 'pi-edd-template', $css );
    }
}

new Pi_Edd_Template_Engine();