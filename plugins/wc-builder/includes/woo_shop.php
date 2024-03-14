<?php
/**
*  WooCommerce Templates Overwrite
*/
class WPBForWPbakery_Woo_Custom_Template_Layout{
    public static $wpb_woo_wpbakery_template = array();

    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    function __construct(){
        add_action('init', array( $this, 'init' ) );
    }

    public function init(){
	    add_action('template_redirect', array($this, 'wpbforwpbakery_product_archive_template'), 999);

	    // set our archive-product-fullwidth.php file 
	    add_filter('template_include', array($this, 'wpbforwpbakery_redirect_product_archive_template'), 999);

	    // add page content by hook located in woo-templates/archive-product.php
	    add_action( 'wpbforwpbakery_woocommerce_archive_product_content', array( $this, 'wpbforwpbakery_archive_product_page_content') );

	    // add product data generate by hook
	    add_action( 'wpbforwpbakery_woocommerce_archive_product_content', array( $this, 'wl_get_default_product_data'), 15 );

	    // set our single-product-fullwidth.php file 
		add_filter( 'template_include', array( $this, 'wpbforwpbakery_single_product_template' ), 100 );

	    // set our content-product.php file 
		add_filter( 'wc_get_template_part', array( $this, 'wpbforwpbakery_get_product_page_template' ), 99, 3 );

		// add page content by hook located in woo-templates/content-single-product.php
		add_action( 'wpbforwpbakery_woocommerce_product_content', array( $this, 'wpbforwpbakery_get_product_content_wpbakery' ), 5 );
    }

    /*
    * Archive Page
    */
    public function wpbforwpbakery_product_archive_template() {
        $archive_template_id = 0;
        if ( defined('WOOCOMMERCE_VERSION') ) {
            if ( is_shop() || ( is_tax('product_cat') && is_product_category() ) || ( is_tax('product_tag') && is_product_tag() ) ) {
                $product_achive_custom_page_id = wpbforwpbakery_get_option( 'productarchivepage', 'wpbforwpbakery_woo_template_tabs', '0' );

                // Meta value
                $wpbtermlayoutid = 0;
                $termobj = get_queried_object();
                if(( is_tax('product_cat') && is_product_category() ) || ( is_tax('product_tag') && is_product_tag() )){
                    $wpbtermlayoutid = get_term_meta( $termobj->term_id, 'wpbforwpbakery_selectcategory_layout', true ) ? get_term_meta( $termobj->term_id, 'wpbforwpbakery_selectcategory_layout', true ) : '0';
                }
                if( $wpbtermlayoutid != '0' ){ 
                    $archive_template_id = $wpbtermlayoutid; 
                }else{
                    if (!empty($product_achive_custom_page_id)) {
                        $archive_template_id = $product_achive_custom_page_id;
                    }
                }
                return $archive_template_id;
            }
            return $archive_template_id;
        }
    }

    public function wpbforwpbakery_redirect_product_archive_template($template){
    	$archive_template_id = $this->wpbforwpbakery_product_archive_template();
    	$layout = get_post_meta($archive_template_id, 'wpbforwpbakery_tmpl_layout', true);
    	$enablecustomlayout = wpbforwpbakery_get_option( 'enablecustomlayout', 'wpbforwpbakery_woo_template_tabs', '0' );
    	
        if( $archive_template_id &&  $enablecustomlayout == 'on' && $layout == 'Full Width'){
            $template = WPBFORWPBAKERY_ADDONS_PL_PATH . '/woo-templates/archive-product-fullwidth.php';
        } elseif($archive_template_id &&  $enablecustomlayout == 'on' && $layout == 'Default'){
        	 $template = WPBFORWPBAKERY_ADDONS_PL_PATH . '/woo-templates/archive-product.php';
        }
        
        return $template;
    }

    // Archive Page Content
    public function wpbforwpbakery_archive_product_page_content( $post ){
        $archive_template_id = $this->wpbforwpbakery_product_archive_template();
        if( $archive_template_id ){
        	if ($wpb_custom_css = get_post_meta($archive_template_id, '_wpb_post_custom_css', true)) {
        		echo '<style type="text/css">' . $wpb_custom_css . '</style>';
        	}
        	if ($wpb_shortcodes_custom_css = get_post_meta($archive_template_id, '_wpb_shortcodes_custom_css', true)) {
        		echo '<style type="text/css">' . $wpb_shortcodes_custom_css . '</style>';
        	}

        	$product_archive_custom_page = get_post($archive_template_id);
            $content = $product_archive_custom_page->post_content;
            $content = apply_filters( 'the_content', $content );
            $content = str_replace( ']]>', ']]&gt;', $content );
            echo $content;
        } else{ 
        	the_content();
        }
    }

    // product data
    public function wl_get_default_product_data() {
        WC()->structured_data->generate_product_data();
    }

    // Single Product template
    public function wpbforwpbakery_single_product_template( $template ) {
    	$custom_single_page_id = wpbforwpbakery_get_option( 'singleproductpage', 'wpbforwpbakery_woo_template_tabs', '0' );
    	$individual_product_tmpl_id = get_post_meta( get_the_id(), '_selectproduct_layout', true );
    	if(!empty($individual_product_tmpl_id)){
    		$custom_single_page_id = $individual_product_tmpl_id;
    	}

    	$layout = get_post_meta($custom_single_page_id, 'wpbforwpbakery_tmpl_layout', true);
    	$enablecustomlayout = wpbforwpbakery_get_option( 'enablecustomlayout', 'wpbforwpbakery_woo_template_tabs', '0' );

        if ( is_embed() ) {
            return $template;
        }
        if ( $enablecustomlayout == 'on' && $layout == 'Full Width' && (is_singular( 'product' ) && (int) $custom_single_page_id)) {
             $template = WPBFORWPBAKERY_ADDONS_PL_PATH . 'woo-templates/single-product-fullwidth.php';
        }
        return $template;
    }


    /*
    * Single Product Page
    */
    public function wpbforwpbakery_get_product_page_template( $template, $slug, $name ) {
    	$custom_single_page_id = wpbforwpbakery_get_option( 'singleproductpage', 'wpbforwpbakery_woo_template_tabs', '0' );
    	$layout = get_post_meta($custom_single_page_id, 'wpbforwpbakery_tmpl_layout', true);
    	$enablecustomlayout = wpbforwpbakery_get_option( 'enablecustomlayout', 'wpbforwpbakery_woo_template_tabs', '0' );

        if ( $enablecustomlayout == 'on' && ($layout == 'Default' ||  $layout == 'Full Width' ) && ('content' === $slug && 'single-product' === $name) ) {
            if ( WPBForWPbakery_Woo_Custom_Template_Layout::wl_woo_custom_product_template() ) {
                $template = WPBFORWPBAKERY_ADDONS_PL_PATH . 'woo-templates/content-single-product.php';
            }
        } 
        return $template;
    }

    public static function wpbforwpbakery_get_product_content_wpbakery( $post ) {
        if ( WPBForWPbakery_Woo_Custom_Template_Layout::wl_woo_custom_product_template() ) {
            $wpbtemplateid = wpbforwpbakery_get_option( 'singleproductpage', 'wpbforwpbakery_woo_template_tabs', '0' );
            $wpbindividualid = get_post_meta( get_the_ID(), '_selectproduct_layout', true ) ? get_post_meta( get_the_ID(), '_selectproduct_layout', true ) : '0';
            if( $wpbindividualid != '0' ){ $wpbtemplateid = $wpbindividualid; }



        	if ($wpb_custom_css = get_post_meta($wpbtemplateid, '_wpb_post_custom_css', true)) {
        		echo '<style type="text/css">' . $wpb_custom_css . '</style>';
        	}
        	if ($wpb_shortcodes_custom_css = get_post_meta($wpbtemplateid, '_wpb_shortcodes_custom_css', true)) {
        		echo '<style type="text/css">' . $wpb_shortcodes_custom_css . '</style>';
        	}

        	$product_archive_custom_page = get_post($wpbtemplateid);
            $content = $product_archive_custom_page->post_content;
            $content = apply_filters( 'the_content', $content );
            $content = str_replace( ']]>', ']]&gt;', $content );
            echo $content;
        } else {
            the_content();
        }
    }

    public static function wl_woo_custom_product_template() {
        $templatestatus = false;
        if ( is_product() ) {
            global $post;
            if ( ! isset( self::$wpb_woo_wpbakery_template[ $post->ID ] ) ) {
                $single_product_default = wpbforwpbakery_get_option( 'singleproductpage', 'wpbforwpbakery_woo_template_tabs', '0' );
                if ( ! empty( $single_product_default ) && 'default' !== $single_product_default ) {
                    $templatestatus                              = true;
                    self::$wpb_woo_wpbakery_template[ $post->ID ] = true;
                }
            } else {
                $templatestatus = self::$wpb_woo_wpbakery_template[ $post->ID ];
            }
        }
        return true;
    }

}

WPBForWPbakery_Woo_Custom_Template_Layout::instance();