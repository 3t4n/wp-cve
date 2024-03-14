<?php
class BeRocket_Compare_compat_product_preview {
    function __construct() {
        add_filter( 'br_product_preview_positions_elements', array(__CLASS__, 'br_product_preview_positions_elements') );
        add_action( 'br_build_preview_berocket_compare', array(__CLASS__, 'br_build_preview') );
    }
    public static function br_product_preview_positions_elements($elements) {
        $elements['berocket_compare'] = __( '<strong>BeRocket</strong> Compare', 'products-compare-for-woocommerce' );
        return $elements;
    }
    public static function br_build_preview() {
        echo '<div>';
        $BeRocket_Compare_Products = BeRocket_Compare_Products::getInstance();
        $BeRocket_Compare_Products->get_compare_button();
        echo '</div>
        <style>.br_product_preview_preview .br_compare_button{width:initial!important;}</style>';
    }
}
new BeRocket_Compare_compat_product_preview();
