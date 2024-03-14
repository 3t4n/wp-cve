<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * * Main class for all pages, the page classes inherit this class
 * todo: here must be enqueue most of the js and css
 */
class PagesBreadcrumbs {

    public $scc_icons;
    public function __construct() {
        $this->scc_icons = require SCC_DIR . '/assets/scc_icons/icon_rsrc.php';

        if ( is_admin() ) {
            wp_register_script( 'scc-bootstrap-min2', SCC_URL . 'lib/bootstrap/bootstrap.bundle.min.js', [ 'jquery' ], '5.1.3', true );
            wp_register_style( 'scc-bootstrap-min2', SCC_URL . 'lib/bootstrap/bootstrap.min.css', '5.1.3' );
            wp_register_style( 'scc-back-end', SCC_URL . 'assets/css/scc-back-end.css', [], STYLISH_COST_CALCULATOR_VERSION );
            wp_register_script( 'scc-sweet-alert', SCC_URL . 'lib/sweetalert2/sweetalert2.min.js', [ 'jquery' ], STYLISH_COST_CALCULATOR_VERSION, true );
            wp_register_style( 'scc-sweet-alert', SCC_URL . 'lib/sweetalert2/sweetalert2.min.css', [], STYLISH_COST_CALCULATOR_VERSION );
            wp_enqueue_style( 'scc-fonts', 'https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700;800&display=swap' );
            wp_enqueue_style( 'scc-material', 'https://fonts.googleapis.com/icon?family=Material+Icons|Material+Icons+Outlined' );
            wp_enqueue_style( 'scc-sweet-alert' );
            wp_enqueue_script( 'scc-sweet-alert' );
            wp_enqueue_script( 'wp-util' );
            wp_enqueue_script( 'scc-bootstrap-min2' );
            wp_enqueue_style( 'scc-bootstrap-min2' );
            wp_enqueue_style( 'scc-back-end' );
            wp_enqueue_style( 'dashicons' );
            wp_enqueue_style( 'scc-sweetalert' );
            wp_enqueue_script( 'scc-sweetalert' );
            wp_enqueue_script( 'jquery-ui-dialog' );
            wp_enqueue_style( 'scc-jquery-ui-css', SCC_URL . 'lib/jquery-ui/jquery-ui.css', [], STYLISH_COST_CALCULATOR_VERSION );
            wp_register_script( 'scc-backend', SCC_URL . 'assets/js/scc-backend.js', [ 'jquery' ], STYLISH_COST_CALCULATOR_VERSION, true );
            wp_enqueue_script( 'scc-backend' );

            wp_register_script( 'scc-tour', SCC_URL . 'lib/introjs/js/introjs.min.js', [], STYLISH_COST_CALCULATOR_VERSION, true );
            wp_register_style( 'scc-tour', SCC_URL . 'lib/introjs/css/introjs.min.css', [], STYLISH_COST_CALCULATOR_VERSION );
            wp_enqueue_style( 'scc-tour' );
            wp_enqueue_script( 'scc-tour' );

            wp_register_script( 'scc-tom-select-backend', SCC_URL . 'lib/tom-select/tom-select.base.js', [ 'jquery' ], STYLISH_COST_CALCULATOR_VERSION, true );
            wp_register_style( 'scc-tom-select-backend', SCC_URL . 'lib/tom-select/tom-select.css', [], STYLISH_COST_CALCULATOR_VERSION );
            wp_enqueue_script( 'scc-tom-select-backend' );
            wp_enqueue_style( 'scc-tom-select-backend' );
        }
    }
}
