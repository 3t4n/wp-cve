<?php
namespace WPHR\HR_MANAGER\HRM\Admin;

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
        add_action( 'wphr_user_profile_role', array( $this, 'role' ) );
        add_action( 'wphr_update_user', array( $this, 'update_user' ), 10, 2 );
    }

    function update_user( $user_id, $post ) {

        // HR role we want the user to have
        $new_hr_manager_role    = isset( $post['hr_manager'] ) ? sanitize_text_field( $post['hr_manager'] ) : false;

        if ( ! $new_hr_manager_role ) {
            return;
        }

        // Bail if current user cannot promote the passing user
        if ( ! current_user_can( 'promote_user', $user_id ) ) {
            return;
        }

        // Set the new HR role
        $user = get_user_by( 'id', $user_id );

        if ( $new_hr_manager_role ) {
            $user->add_role( $new_hr_manager_role );
        } else {
            $user->remove_role( wphr_hr_get_manager_role() );
        }
    }

    function role( $profileuser ) {
        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $checked = in_array( wphr_hr_get_manager_role(), $profileuser->roles ) ? 'checked' : '';
        ?>
        <label for="wphr-hr-manager">
            <input type="checkbox" id="wphr-hr-manager" <?php echo $checked; ?> name="hr_manager" value="<?php echo wphr_hr_get_manager_role(); ?>">
            <span class="description"><?php _e( 'HR Manager', 'wphr' ); ?></span>
        </label>
        <?php
    }

}
