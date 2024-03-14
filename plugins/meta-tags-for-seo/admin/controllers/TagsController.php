<?php

namespace Pagup\MetaTags\Controllers;

use  Pagup\MetaTags\Core\Option ;
class TrackingController
{
    public function __construct()
    {
        add_action( 'wp_head', array( &$this, 'meta_tags' ) );
    }
    
    public function meta_tags()
    {
        
        if ( Option::check( 'meta_tags' ) && count( Option::get( 'meta_tags' ) ) > 0 ) {
            echo  "\n<!-- Meta Tags for SEO -->\n" ;
            foreach ( Option::get( 'meta_tags' ) as $tag ) {
                
                if ( $tag['post_type'] == 'everywhere' ) {
                    if ( empty(Option::post_meta( 'pmt_disable_tags' )) ) {
                        echo  $this->meta( $tag ) ;
                    }
                } elseif ( is_singular( $tag['post_type'] ) ) {
                    if ( empty(Option::post_meta( 'pmt_disable_tags' )) ) {
                        echo  $this->meta( $tag ) ;
                    }
                }
            
            }
            echo  "\n" ;
        }
    
    }
    
    protected function meta( $tag )
    {
        $site_title = ( isset( $tag['site_title'] ) && !empty($tag['site_title']) ? ", " . get_bloginfo( 'name' ) : '' );
        $post_title = ( isset( $tag['post_title'] ) && !empty($tag['post_title']) && is_singular() ? ", " . esc_html( get_the_title() ) : '' );
        $focus_keyword = '';
        if ( is_singular() && $tag['focus_keyword'] == 'yoast_focus_keyword' ) {
            $focus_keyword = $this->yoast();
        }
        if ( is_singular() && $tag['focus_keyword'] == 'rankmath_focus_keyword' ) {
            $focus_keyword = $this->rankmath();
        }
        $product_sku = '';
        if ( class_exists( 'woocommerce' ) && is_singular( 'product' ) && isset( $tag['product_sku'] ) && $tag['product_sku'] == 'product_sku' ) {
            $product_sku = $this->product_sku();
        }
        $product_cats = '';
        if ( class_exists( 'woocommerce' ) && is_singular( 'product' ) && isset( $tag['product_cats'] ) && $tag['product_cats'] == 'product_cats' ) {
            $product_cats = $this->product_categories();
        }
        $product_tags = '';
        if ( class_exists( 'woocommerce' ) && is_singular( 'product' ) && isset( $tag['product_tags'] ) && $tag['product_tags'] == 'product_tags' ) {
            $product_tags = $this->product_tags();
        }
        return "<meta {$tag['type']}='{$tag['value']}' content='{$tag['content']}{$focus_keyword}{$post_title}{$site_title}{$product_sku}{$product_cats}{$product_tags}'>\n";
    }
    
    protected function yoast()
    {
        global  $post ;
        
        if ( class_exists( 'WPSEO_Meta' ) ) {
            $fkw = \WPSEO_Meta::get_value( 'focuskw', $post->ID );
            if ( isset( $fkw ) && !empty($fkw) ) {
                return ", " . $fkw;
            }
        }
        
        return;
    }
    
    protected function rankmath()
    {
        global  $post ;
        
        if ( class_exists( 'RankMath' ) ) {
            $fkw = get_post_meta( $post->ID, 'rank_math_focus_keyword', true );
            if ( isset( $fkw ) && !empty($fkw) ) {
                return ", " . $fkw;
            }
        }
        
        return;
    }
    
    protected function product_sku()
    {
        global  $post ;
        
        if ( class_exists( 'woocommerce' ) ) {
            $product = wc_get_product( $post );
            $sku = $product->get_sku();
            if ( isset( $sku ) && !empty($sku) ) {
                return ", " . $product->get_sku();
            }
        }
        
        return;
    }
    
    protected function product_categories()
    {
        global  $post ;
        
        if ( class_exists( 'woocommerce' ) ) {
            $cats_list = get_the_terms( $post->ID, 'product_cat' );
            $categories = join( ', ', wp_list_pluck( $cats_list, 'name' ) );
            if ( isset( $categories ) && !empty($categories) ) {
                return ", " . $categories;
            }
        }
        
        return;
    }
    
    protected function product_tags()
    {
        global  $post ;
        
        if ( class_exists( 'woocommerce' ) ) {
            $tags_list = get_the_terms( $post->ID, 'product_tag' );
            $tags = join( ', ', wp_list_pluck( $tags_list, 'name' ) );
            if ( isset( $tags ) && !empty($tags) ) {
                return ", " . $tags;
            }
        }
        
        return;
    }

}
$TrackingControllers = new TrackingController();