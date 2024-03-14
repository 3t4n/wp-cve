<?php
namespace Elementor;

// Create Related Product Manager into Elementor.
function init_related_product_lists_category() {
    Plugin::instance()->elements_manager->add_category(
        'related-product-manager', [
            'title' => esc_html__('ProWcPlugins', RPMW_TEXTDOMAIN),
            'icon' => 'font'
        ], 1
    );
}

add_action('elementor/init', 'Elementor\init_related_product_lists_category');
?>