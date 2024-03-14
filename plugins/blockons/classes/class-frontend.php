<?php

/**
 * Frontend functions.
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Frontend class.
 */
class Blockons_Frontend
{
    /**
     * Constructor function.
     */
    public function __construct()
    {
        $blockonsSavedOptions = get_option( 'blockons_options' );
        $blockonsOptions = ( $blockonsSavedOptions ? json_decode( $blockonsSavedOptions ) : '' );
        add_filter( 'body_class', array( $this, 'blockons_frontend_body_classes' ) );
        if ( isset( $blockonsOptions->bttb->enabled ) && $blockonsOptions->bttb->enabled == true ) {
            add_action(
                'wp_footer',
                array( $this, 'blockons_add_footer_bttb' ),
                10,
                1
            );
        }
        if ( isset( $blockonsOptions->scrollindicator->enabled ) && $blockonsOptions->scrollindicator->enabled == true ) {
            
            if ( isset( $blockonsOptions->scrollindicator->position ) && $blockonsOptions->scrollindicator->enabled == 'bottom' ) {
                add_action(
                    'wp_footer',
                    array( $this, 'blockons_add_scroll_indicator' ),
                    10,
                    1
                );
            } else {
                add_action(
                    'wp_body_open',
                    array( $this, 'blockons_add_scroll_indicator' ),
                    10,
                    1
                );
            }
        
        }
        
        if ( isset( $blockonsOptions->pageloader->enabled ) && $blockonsOptions->pageloader->enabled == true ) {
            add_filter( 'body_class', array( $this, 'blockons_add_loader_body_class' ) );
            add_action( 'wp_head', array( $this, 'blockons_add_header_loader_style' ) );
            add_action(
                'wp_body_open',
                array( $this, 'blockons_add_footer_page_loader' ),
                10,
                1
            );
        }
        
        if ( Blockons_Admin::blockons_is_plugin_active( 'woocommerce.php' ) ) {
            if ( isset( $blockonsOptions->sidecart->enabled ) && $blockonsOptions->sidecart->enabled == true ) {
                add_action(
                    'wp_footer',
                    array( $this, 'blockons_pro_add_footer_sidecart' ),
                    10,
                    1
                );
            }
        }
    }
    
    /**
     * Function to check for active plugins
     */
    public function blockons_frontend_body_classes( $classes )
    {
        $classes[] = sanitize_html_class( 'blockons-free' );
        return $classes;
    }
    
    /**
     * Add Page Loader Functionality
     */
    public function blockons_add_loader_body_class( $classes )
    {
        $classes[] = sanitize_html_class( 'blockons-page-loading' );
        return $classes;
    }
    
    public function blockons_add_header_loader_style()
    {
        $blockonsSavedOptions = get_option( 'blockons_options' );
        $blockonsOptions = ( $blockonsSavedOptions ? json_decode( $blockonsSavedOptions ) : '' );
        ?>
		<style type="text/css">body.blockons-page-loading { background-color: <?php 
        echo  ( isset( $blockonsOptions->pageloader->enabled ) ? esc_attr( $blockonsOptions->pageloader->bgcolor ) : esc_attr( 'inherit' ) ) ;
        ?>; }</style><?php 
    }
    
    public function blockons_add_footer_page_loader()
    {
        $allowed_html = array(
            'div' => array(
            'id' => array(),
        ),
        );
        $html = '<div id="blockons-pageloader"></div>';
        echo  wp_kses( $html, $allowed_html ) ;
    }
    
    /**
     * Add Back to Top Button
     */
    public function blockons_add_footer_bttb()
    {
        $allowed_html = array(
            'div' => array(
            'id' => array(),
        ),
        );
        $html = '<div id="blockons-bttb"></div>';
        echo  wp_kses( $html, $allowed_html ) ;
    }
    
    /**
     * Add Back to Top Button
     */
    public function blockons_add_scroll_indicator()
    {
        $allowed_html = array(
            'div' => array(
            'id' => array(),
        ),
        );
        $html = '<div id="blockons-scroll-indicator"></div>';
        echo  wp_kses( $html, $allowed_html ) ;
    }
    
    /**
     * PREMIUM: Side Cart Elements
     */
    public function blockons_pro_add_footer_sidecart()
    {
        $allowed_html = array(
            'div'  => array(
            'class' => array(),
            'id'    => array(),
            'style' => array(),
        ),
            'a'    => array(
            'class' => array(),
            'href'  => array(),
        ),
            'span' => array(
            'class' => array(),
        ),
        );
        
        if ( !has_block( 'blockons/wc-mini-cart' ) ) {
            $html = '<div class="blockons-hidden" style="width: 0; height: 0; overflow: hidden;">' . blockons_wc_minicart_item() . '</div>';
            // Add Cart & Mini Cart to site footer
            echo  wp_kses( $html, $allowed_html ) ;
            $html2 = '<div class="blockons-hidden" style="width: 0; height: 0; overflow: hidden;">' . blockons_wc_cart_amount() . '<div class="blockons-mini-crt"><div class="widget_shopping_cart_content">';
            $html3 = '</div></div></div>';
            echo  wp_kses( $html2, $allowed_html ) ;
            woocommerce_mini_cart();
            echo  wp_kses( $html3, $allowed_html ) ;
        }
        
        // Add Side Cart Element
        $html4 = '<div id="blockons-side-cart" class="blockons-side-cart-wrap"></div>';
        echo  wp_kses( $html4, $allowed_html ) ;
    }

}
new Blockons_Frontend();