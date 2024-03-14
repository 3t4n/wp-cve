<?php
/**
 * Tool
 *
 * @package     GamiPress\LearnDash\Points_Importer\Tool
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register tool meta boxes
 *
 * @since  1.0.0
 *
 * @param array $meta_boxes
 *
 * @return array
 */
function gamipress_learndash_points_importer_tool_meta_boxes( $meta_boxes ) {

    $prefix = 'gamipress_learndash_points_importer_';

    $meta_boxes['learndash-points-importer'] = array(
        'title' => __( 'LearnDash Points Importer', 'gamipress-learndash-points-importer' ),
        'fields' => apply_filters( 'gamipress_learndash_points_importer_tool_fields', array(
            $prefix . 'points_type' => array(
                'name' => __( 'Points type to import', 'gamipress-learndash-points-importer' ),
                'desc' => __( 'Choose the GamiPress points type where you want to import the LearnDash user points balances.', 'gamipress-learndash-points-importer' ),
                'type' => 'select',
                'options_cb' => 'gamipress_options_cb_points_types',
                'option_all' => false,
                'option_none' => true,
            ),
            $prefix . 'workflow' => array(
                'name' => __( 'How should user points balances be imported?', 'gamipress-learndash-points-importer' ),
                'desc' => __( 'Check the way you want to import the points balance.', 'gamipress-learndash-points-importer' ),
                'type' => 'select',
                'options' => array(
                    'sum' => __( 'Sum LearnDash and GamiPress points balances (User balance will be LD points + GP points)', 'gamipress-learndash-points-importer' ),
                    'override' => __( 'Override GamiPress points balances with the LearnDash points balances (User balance will be the same as in LearnDash)', 'gamipress-learndash-points-importer' ),
                ),
            ),
            $prefix . 'run' => array(
                'label' => __( 'Import LearnDash Points', 'gamipress-learndash-points-importer' ),
                'type' => 'button',
                'button' => 'primary'
            ),
        ) )
    );

    return $meta_boxes;

}
add_filter( 'gamipress_tools_import_export_meta_boxes', 'gamipress_learndash_points_importer_tool_meta_boxes' );

/**
 * AJAX handler for process this tool
 *
 * @since 1.0.0
 */
function gamipress_ajax_learndash_points_importer_import() {

    global $wpdb;

    // Check user capabilities
    if( ! current_user_can( gamipress_get_manager_capability() ) ) {
        wp_send_json_error( __( 'You are not allowed to perform this action.', 'gamipress-learndash-points-importer' ) );
    }

    // Check parameters received
    if( ! isset( $_POST['points_type'] ) || empty( $_POST['points_type'] ) ) {
        wp_send_json_error( __( 'Please, choose a points type.', 'gamipress-learndash-points-importer' ) );
    }

    if( ! isset( $_POST['workflow'] ) || empty( $_POST['workflow'] ) ) {
        wp_send_json_error( __( 'Please, choose the way this tool should work.', 'gamipress-learndash-points-importer' ) );
    }

    $points_types = gamipress_get_points_types();
    $points_type = $_POST['points_type'];
    $workflow = $_POST['workflow'];

    if( ! isset( $points_types[$points_type] ) ) {
        wp_send_json_error( __( 'Choose a valid points type.', 'gamipress-learndash-points-importer' ) );
    }

    $loop = ( ! isset( $_POST['loop'] ) ? 0 : absint( $_POST['loop'] ) );
    $limit = 100;
    $offset = $limit * $loop;
    $run_again = false;

    ignore_user_abort( true );

    if ( ! gamipress_is_function_disabled( 'set_time_limit' ) ) {
        set_time_limit( 0 );
    }

    // Get all stored users
    $users = $wpdb->get_results( "SELECT u.ID FROM {$wpdb->users} AS u ORDER BY u.ID ASC LIMIT {$offset}, {$limit}" );

    // Return a success message if finished, else run again
    if( empty( $users ) && $loop !== 0 ) {
        wp_send_json_success( __( 'Import process finished successfully.', 'gamipress-learndash-points-importer' ) );
    } else {
        $run_again = true;
    }

    if( empty( $users ) ) {
        wp_send_json_error( __( 'Could not find users.', 'gamipress-learndash-points-importer' ) );
    }

    // Let's to bulk revoke
    foreach( $users as $user ) {

        $learndash_points = learndash_get_user_course_points( $user->ID );

        if( $workflow === 'sum' ) {

            // Award the LearnDash points to sum them to the current balance
            gamipress_award_points_to_user( $user->ID, $learndash_points, $points_type );

        } else if( $workflow === 'override' ) {

            // Override the current balance with the LearnDash points
            gamipress_update_user_points( $user->ID, $learndash_points, get_current_user_id(), null, $points_type );

        }

    }

    if( $run_again ) {

        $awarded_users = $limit * ( $loop + 1 );

        $users_count = absint( $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->users} AS u ORDER BY u.ID ASC" ) );

        $remaining_users = $users_count - $awarded_users;

        // Return a run again message (just when revoking to all users)
        wp_send_json_success( array(
            'run_again' => $run_again,
            'message' => sprintf( __( '%d remaining users', 'gamipress-learndash-points-importer' ), ( $remaining_users > 0 ? $remaining_users : 0 ) ),
        ) );

    } else {
        // Return a success message
        wp_send_json_success( __( 'Import process finished successfully.', 'gamipress-learndash-points-importer' ) );
    }


}
add_action( 'wp_ajax_gamipress_learndash_points_importer_import', 'gamipress_ajax_learndash_points_importer_import' );