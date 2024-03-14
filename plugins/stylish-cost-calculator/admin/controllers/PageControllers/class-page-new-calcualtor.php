<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
require_once __DIR__ . '/class-pages-breadcrumbs.php';

class PageNew extends PagesBreadcrumbs {

    public function __construct() {
        parent::__construct();
        // scc-add-calculator.js
        wp_register_script( 'scc-add-calculator', SCC_URL . '/assets/js/scc-add-calculator.js', [ 'jquery' ], STYLISH_COST_CALCULATOR_VERSION, true );
        // Adding inline script to scc-add-calculator
        wp_add_inline_script( 'scc-add-calculator', 'const previewImagesBaseUrl = "' . esc_url( SCC_TEMPLATE_PREVIEW_BASEURL ) . '";' );
        wp_enqueue_script( 'scc-add-calculator' );
        require dirname( __DIR__, 2 ) . '/views/adminHeader.php';
        require dirname( __DIR__, 2 ) . '/views/addCalculator.php';
        require dirname( __DIR__, 2 ) . '/views/adminFooter.php';
    }

    // PASAR SI VERSION PAGA O NO

    public function isGranted() {
        // GRANTED SC
    }
}
new PageNew();
