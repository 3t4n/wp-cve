<?php
/**
 * Admin
 *
 * @package GamiPress\Reset_User\Admin
 * @since 1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Reset user markup from their profile screen
 *
 * @since  1.0.0
 * @param  object $user The current user's $user object
 * @return void
 */
function gamipress_reset_user_profile( $user = null ) {
    ?>

    <?php // Verify user meets minimum role to manage earned achievements
    if ( current_user_can( gamipress_get_manager_capability() ) ) :
        $reset_url = add_query_arg( array(
            'action'         => 'gamipress_reset_user',
            'user_id'        => absint( $user->ID ),
        ) ); ?>

        <h2 style="color:#a00"><?php _e( 'Reset user GamiPress data', 'gamipress-reset-user' ); ?></h2>

        <p>
        <button type="button" class="button" onclick="jQuery('#gamipress-reset-user-information').slideDown();" style="color: #fff;border-color: #a00;background: #da3f3f;"><?php _e( 'Remove all GamiPress data from this account', 'gamipress-reset-user' ); ?></button>
        </p>
        <p id="gamipress-reset-user-information" style="display: none;">
            <?php _e( 'All user earnings (points, achievements and ranks) and logs will be <strong>removed permanently</strong>, without possibility to revert back.', 'gamipress-reset-user' ); ?><br>
            <?php _e( 'Do you want to continue?', 'gamipress-reset-user' ); ?><br><br>
            <a href="<?php echo $reset_url; ?>" class="button button-primary" style="margin-right: 5px;"><?php _e( 'Yes', 'gamipress-reset-user' ); ?></a>
            <button type="button" class="button" onclick="jQuery('#gamipress-reset-user-information').slideUp();"><?php _e( 'No', 'gamipress-reset-user' ); ?></button>
        </p>

        <hr>

    <?php endif; ?>

    <?php

}
add_action( 'show_user_profile', 'gamipress_reset_user_profile' );
add_action( 'edit_user_profile', 'gamipress_reset_user_profile' );

/**
 * Process the user reset on the user profile page
 *
 * @since  1.0.0
 */
function gamipress_reset_user_process_user_data() {

    global $wpdb;

    // Verify user meets minimum role to view earned achievements
    if ( current_user_can( gamipress_get_manager_capability() ) ) {

        // Process reset user action
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'gamipress_reset_user' &&  isset( $_GET['user_id'] ) ) {

            $user_id = absint( $_GET['user_id'] );

            $logs 		= GamiPress()->db->logs;
            $logs_meta 	= GamiPress()->db->logs_meta;

            // Delete all user logs
            $wpdb->query( "DELETE FROM {$logs} WHERE user_id = {$user_id}" );

            // Delete orphaned log metas
            $wpdb->query( "DELETE lm FROM {$logs_meta} lm LEFT JOIN {$logs} l ON l.log_id = lm.log_id WHERE l.log_id IS NULL" );

            $user_earnings      = GamiPress()->db->user_earnings;
            $user_earnings_meta = GamiPress()->db->user_earnings_meta;

            // Delete all user earnings
            $wpdb->query( "DELETE FROM {$user_earnings} WHERE user_id = {$user_id}" );

            // Delete orphaned user earnings metas
            $wpdb->query( "DELETE uem FROM {$user_earnings_meta} uem LEFT JOIN {$user_earnings} ue ON ue.user_earning_id = uem.user_earning_id WHERE ue.user_earning_id IS NULL" );

            // Delete user metas
            $wpdb->query( "DELETE FROM {$wpdb->usermeta} WHERE user_id = {$user_id} AND meta_key LIKE '%gamipress%'" );

            // Redirect back to the user editor
            wp_redirect( add_query_arg( 'user_id', absint( $_GET['user_id'] ), admin_url( 'user-edit.php' ) ) );
            exit();

        }

    }

}
add_action( 'admin_init', 'gamipress_reset_user_process_user_data' );