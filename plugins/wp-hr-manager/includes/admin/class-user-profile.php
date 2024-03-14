<?php
namespace WPHR\HR_MANAGER\Admin;

/**
 * Loads HR users admin area
 *
 * @package WP-wphr\HR
 * @subpackage Administration
 */
class User_Profile {

    /**
     * The HR users admin loader
     *
     * @package WP-wphr\HR
     * @subpackage Administration
     */
    public function __construct() {
        $this->setup_actions();
    }

    /**
     * Setup the admin hooks, actions and filters
     *
     * @return void
     */
    function setup_actions() {

        // Bail if in network admin
        if ( is_network_admin() ) {
            return;
        }

        // User profile edit/display actions
        add_action( 'edit_user_profile', [ $this, 'role_display' ] );
        add_action( 'show_user_profile', [ $this, 'role_display' ] );
        add_action( 'profile_update', [ $this, 'profile_update_role' ] );
    }

    /**
     * Default interface for setting a HR role
     *
     * @param WP_User $profileuser User data
     *
     * @return bool Always false
     */
    public static function role_display( $profileuser ) {

        // Bail if current user cannot edit users
        if ( ! current_user_can( 'edit_user', $profileuser->ID ) || !current_user_can( 'manage_options') ) {
            return;
        }

        ?>

        <h3><?php esc_html_e( 'WPHR Manager Role', 'wphr' ); ?></h3>

        <table class="form-table">
            <tbody>
                <tr>
                    <th><label for="wphr-hr-role"><?php esc_html_e( 'Role', 'wphr' ); ?></label></th>
                    <td>
                        <?php do_action( 'wphr_user_profile_role', $profileuser ); ?>
                    </td>
                </tr>

            </tbody>
        </table>

        <?php
    }

    public static function profile_update_role( $user_id = 0 ) {
        // Bail if no user ID was passed
        if ( empty( $user_id ) ) {
            return;
        }

        do_action( 'wphr_update_user', $user_id, $_POST );
    }
}
