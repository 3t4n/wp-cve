<?php 

// Add admin settings page menu
function wclp_admin_settings_menu() {
	$page_title = esc_html__( 'Variation Price Display Settings', 'woo-disable-variable-product-price-range' );
	$menu_title = esc_html__( 'Variation Price', 'woo-disable-variable-product-price-range' );
	
	$settings_link = esc_url( add_query_arg( array(
                'page'    => 'wc-settings',
                'tab'     => 'products',
                'section' => 'woo-variable-lowest-price'
            ), admin_url( 'admin.php' ) ) );
	
	add_menu_page( $page_title, $menu_title, 'edit_theme_options', $settings_link, '', 'dashicons-money-alt', 35 );
}
add_action( 'admin_menu', 'wclp_admin_settings_menu' );


// Add settings page
function wclp_settings_add_section( $sections ) {
    $sections['woo-variable-lowest-price'] = __( 'Variation Price Display', 'woo-disable-variable-product-price-range' );

    return $sections;
}
add_filter( 'woocommerce_get_sections_products', 'wclp_settings_add_section' );


// Add settings filed
function wclp_settings_add_field( $settings, $current_section ) {
    if ( $current_section == 'woo-variable-lowest-price' ) {

        $settings_lowest_price = array(
            array(
                'title' => __( 'Variation Price Display Settings', 'woo-disable-variable-product-price-range' ),
                'desc'  => '<p>' . __( 'The following options control the Variation Price Display for WooCommerce extension.', 'woo-disable-variable-product-price-range' ) . '<p>' 
                . '<p>'
                . sprintf('<a href="%1$s" target="_blank">%2$s</a>', esc_url('https://wpxpress.net/docs/woocommerce-variation-price-display/'), __( 'Documentation', 'woo-disable-variable-product-price-range' ) ) . ' | '
                . sprintf('<a href="%1$s" target="_blank">%2$s</a>', esc_url('https://wpxpress.net/submit-ticket/'), __( 'Get Help &amp; Support', 'woo-disable-variable-product-price-range' ) )
                . wvp_settings_page_get_pro_link() . '</p>',
                'type' => 'title',
                'id'   => 'woo-variable-lowest-price',
            ),
            array(
                'name'    => __( 'Enable Features', 'woo-disable-variable-product-price-range' ),
                'id'      => 'wclp_enable',
                'type'    => 'checkbox',
                'default' => 'yes',
                'desc'    => __( 'Disable variation price range.', 'woo-disable-variable-product-price-range' ),
            ),
            array(
                'name'    => __( 'Enable on Shop', 'woo-disable-variable-product-price-range' ),
                'id'      => 'wclp_enable_shop',
                'type'    => 'checkbox',
                'default' => 'yes',
                'desc'    => __( 'Disable variation price range on the Shop/Archive pages.', 'woo-disable-variable-product-price-range' ),
            ),
            array(
                'name'  => __( 'Price Types', 'woo-disable-variable-product-price-range' ),
                'id'    => 'wclp_price_types',
                'type'  => 'radio',
                'required'  => true,
                'options'   => array(
                    'min'   => __( 'Show Only Minimum (Lowest) Price.', 'woo-disable-variable-product-price-range' ),
                    'max'   => __( 'Show Only Maximum (Highest) Price.', 'woo-disable-variable-product-price-range' ),
                    'min-to-max'   => __( 'Minimum to Maximum Price.', 'woo-disable-variable-product-price-range' ),
                    'max-to-min'   => __( 'Maximum to Minimum Price.', 'woo-disable-variable-product-price-range' ),
                ),
                'default'   => 'min',
            ),
            array(
                'name'    => __( 'Text Before Price', 'woo-disable-variable-product-price-range' ),
                'id'      => 'wclp_title_before',
                'type'    => 'text',
                'default' => 'From:',
            ),
            array(
                'name'    => __( 'Text After Price', 'woo-disable-variable-product-price-range' ),
                'id'      => 'wclp_title_after',
                'type'    => 'text',
                'default' => '',
            ),
            array(
                'name'    => __( 'Hide Crossed Out Price', 'woo-disable-variable-product-price-range' ),
                'id'      => 'wclp_crossed_price',
                'type'    => 'checkbox',
                'default' => 'no',
                'desc'    => __( 'Hide the crossed out regular price <code><del>$100</del> $50</code> of the variable sale product.', 'woo-disable-variable-product-price-range' ),
            ),

            array(
                'name'    => __( 'Hide Reset Link', 'woo-disable-variable-product-price-range' ),
                'id'      => 'wclp_hide_reset',
                'type'    => 'checkbox',
                'default' => 'no',
                'desc'    => __( 'Hide the "Clear" link that appears when select a variation.', 'woo-disable-variable-product-price-range' ),
            ),
            array( 
                'type' => 'sectionend', 
                'id' => 'woo-variable-lowest-price' 
            ),
        );

        return apply_filters( "woocommerce_get_settings_woo-variable-lowest-price", $settings_lowest_price );

    } else {

        return $settings;

    }
}
add_filter( 'woocommerce_get_settings_products', 'wclp_settings_add_field', 10, 2 );


if ( ! function_exists( 'wvp_settings_page_get_pro_link' ) ) {
    function wvp_settings_page_get_pro_link() {
        if ( ! class_exists( 'Woo_Disable_Variable_Price_Range_Pro' ) ) {
            $html = ' | ' . sprintf('<a href="%1$s" target="_blank" style="color:#d63638"><b>%2$s</b></a>', esc_url('https://wpxpress.net/products/woocommerce-variation-price-display/'), __( 'Get Pro Features', 'woo-disable-variable-product-price-range' ) );

            return $html;
        }

        return '';
    }
}