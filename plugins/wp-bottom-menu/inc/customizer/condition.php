<?php 

defined( 'ABSPATH' ) || die();

class WPBottomMenu_Condition {

    function wpbm_condition_render( $rule ){

        $id = false;
        $reverse = get_option( 'wpbottommenu_condition_reverse', false );

        $condition = $rule['condition'];
        $archives_condition = is_array($rule['archives_condition']) ? 'all' : $rule['archives_condition'];
        $singular_condition = is_array($rule['singular_condition']) ? 'all' : $rule['singular_condition'];
        $woocommerce_condition = is_array($rule['woocommerce_condition']) ? 'all' : $rule['woocommerce_condition'];
        $singular_page_condition = $rule['singular_page_condition'];
        $singular_post_condition = $rule['singular_post_condition'];
        $singular_product_condition = $rule['singular_product_condition'];

        $cpts = apply_filters( 'wpbm_cpt_condition', array( 'post' ) );

        switch ($condition) {
            // entire
            case 'entire':
                $id = true;
            break;
            // archives
            case 'archives':
                if ( is_archive() && $archives_condition === 'all' ) {
                    $id = true;
                } elseif ( is_author() && $archives_condition === 'author' ){
                    $id = true;
                } elseif ( is_category() && $archives_condition === 'cats' ){
                    $id = true;
                } elseif ( is_tag() && $archives_condition === 'tags' ){
                    $id = true;
                } else  {
                    foreach ( $cpts as $cpt ) {
                        if ( is_archive() && $archives_condition === $cpt && get_post_type() === $cpt ) {
                            $id = true;
                        }
                    }
                }
                break;
            // singular
            case 'singular':
                if ( is_singular() ) {
                    if ( is_page() && $singular_condition === 'pages' ){
                        //$id = true;
                        if ( ! empty( $singular_page_condition ) && is_page($singular_page_condition )){
                            $id = true;
                        }
                    } elseif ( is_single() && in_array( $singular_condition, $cpts ) ){
                        foreach ( $cpts as $cpt ) {
                            if ( $singular_condition === $cpt ){
                                if ( ! empty( $rule['singular_' . $cpt . '_condition']) && in_array( get_the_ID(), $rule['singular_' . $cpt . '_condition'] ) ){
                                    $id = true;
                                }
                            }
                        }
                    } elseif ( $singular_condition === 'all' ){
                        $id = true;
                    } elseif ( is_front_page() && $singular_condition === 'front-page' ){
                        $id = true;
                    }
                } elseif ( is_search() && $singular_condition === 'search' ) {
                    $id = true;
                } elseif ( is_404() && $singular_condition === 'page-404') {
                    $id = true;
                }
            break;
            // woocommerce
            case 'woocommerce':
                if ( class_exists( 'WooCommerce' ) ){
                    if ( $woocommerce_condition === 'all' && ( is_woocommerce() || is_shop() || is_product_category() || is_product_tag() || is_product() || is_cart() || is_checkout() || is_account_page() ) ) {
                        $id = true;
                    } elseif ( $woocommerce_condition === 'archive' && ( is_product_category() || is_product_tag() ) ){
                        $id = true;
                    } elseif ( $woocommerce_condition === 'shop' && is_shop() ){
                        $id = true;
                    } elseif ( $woocommerce_condition === 'cats' && is_product_category() ){
                        $id = true;
                    } elseif ( $woocommerce_condition === 'tags' && is_product_tag() ){
                        $id = true;
                    } elseif ( $woocommerce_condition === 'products' && is_product() ){
                        $id = true;
                    } elseif ( $woocommerce_condition === 'product' && is_product() && ( ( is_array( $singular_product_condition ) && array_search( get_the_ID() , $singular_product_condition ) !== false ) || $singular_product_condition === 'all' ) ){
                        $id = true;
                    }
                }
            break;
        }

        if ( $reverse ){
            $id = !$id;
        }
    
        return $id;

    }

    function wpbm_user_role_condition( $allowed_roles ){

        if ( !is_array( $allowed_roles ) || empty( $allowed_roles ) ){
            return true;
        } 

        if ( ($key = array_search('all', $allowed_roles)) !== false ) {
            return true;
        }

        $user = wp_get_current_user();
        if ( array_intersect( $allowed_roles, $user->roles ) ) {
            return true;
        }

        return false;

    }

    function get_condition(){

        // always show for in customize preview mode
        if ( is_customize_preview() ){
            return true;
        }

        $rule = [
            'condition' => get_option( 'wpbottommenu_condition', 'entire' ),
            'archives_condition' => get_option( 'wpbottommenu_archives_condition', 'all' ),
            'singular_condition' => get_option( 'wpbottommenu_singular_condition', 'all' ),
            'woocommerce_condition' => get_option( 'wpbottommenu_woocommerce_condition', 'all' ),
            'singular_page_condition' => get_option( 'wpbottommenu_singular_page_condition', 'all' ),
            'singular_post_condition' => get_option( 'wpbottommenu_singular_post_condition', 'all' ),
            'singular_product_condition' => get_option( 'wpbottommenu_singular_product_condition', 'all' ),
        ];

        $roles = get_option( 'wpbottommenu_user_role_condition', array( 'all' ) );

        if ( $this->wpbm_condition_render( $rule ) ){
            if ( $this->wpbm_user_role_condition( $roles ) ){
                return $this->wpbm_user_role_condition( $roles );
            }
        }

    }

}