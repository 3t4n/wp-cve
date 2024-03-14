<?php
/**
 * Admin Ajax handlers
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
 */

function auxin_recent_portfolios_ajax_handler() {
    // Check nonce
    if ( ! isset( $_POST['n'] ) || ! wp_verify_nonce( $_POST['n'], 'aux_ajax_filterable_portfolio' ) ) {
        wp_send_json_error( 'Nonce check failed!', 403 );
    }
    
    if ( isset( $_POST['args'] ) && is_array( $_POST['args'] ) ) {
        $args = auxin_sanitize_input( $_POST['args'] );
    } else {
        $args = sanitize_text_field( $_GET['post_types'] );
    }

    if ( isset( $_POST['term'] ) ){
        $args['term'] = sanitize_text_field( $_POST['term'] );
    }

    $args['skip_wrappers'] = true;
    
    $acceptedTemplateFiles = apply_filters( 'auxin_recent_portfolios_accepted_template_files', [
        'recent-portfolio'
    ]);
    if ( ! in_array( $args['template_part_file'], $acceptedTemplateFiles ) ) {
        wp_send_json_error( 'template file is not valid.');
    }

    include auxin_get_template_file( $args['template_part_file'], '', $args['extra_template_path'] );

    $output = auxin_widget_recent_portfolios_grid_callback( $args );
    wp_send_json_success( $output );
    exit();
    
}

add_action( 'wp_ajax_aux_recent_portfolio_filter_content', 'auxin_recent_portfolios_ajax_handler' );
add_action( 'wp_ajax_nopriv_aux_recent_portfolio_filter_content', 'auxin_recent_portfolios_ajax_handler' );