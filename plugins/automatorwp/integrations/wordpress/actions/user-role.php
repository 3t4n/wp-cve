<?php
/**
 * User Role
 *
 * @package     AutomatorWP\Integrations\WordPress\Actions\User_Role
 * @author      AutomatorWP <contact@automatorwp.com>, Ruben Garcia <rubengcdev@gmail.com>
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

class AutomatorWP_WordPress_User_Role extends AutomatorWP_Integration_Action {

    public $integration = 'wordpress';
    public $action = 'wordpress_user_role';

    /**
     * Register the trigger
     *
     * @since 1.0.0
     */
    public function register() {

        $role_options = array();
        $editable_roles = apply_filters( 'editable_roles', wp_roles()->roles );

        foreach( $editable_roles as $role => $details ) {
            /* translators: %1$s: Role key (subscriber, editor). %2$s: Role name (Subscriber, Editor). */
            $role_options[] = sprintf( __( '<code>%1$s</code> for %2$s', 'automatorwp' ), $role, translate_user_role( $details['name'] ) );
        }

        automatorwp_register_action( $this->action, array(
            'integration'       => $this->integration,
            'label'             => __( 'Add, change or remove role to user', 'automatorwp' ),
            'select_option'     => __( 'Add, change or remove <strong>role</strong> to user', 'automatorwp' ),
            /* translators: %1$s: Operation (add, change or remove). %2$s: Role. %3$s: User. */
            'edit_label'        => sprintf( __( '%1$s role %2$s to %3$s', 'automatorwp' ), '{operation}', '{role}', '{user}' ),
            /* translators: %1$s: Operation (add, change or remove). %2$s: Role. %3$s: User. */
            'log_label'         => sprintf( __( '%1$s role %2$s to %3$s', 'automatorwp' ), '{operation}', '{role}', '{user}' ),
            'options'           => array(
                'operation' => array(
                    'from' => 'operation',
                    'fields' => array(
                        'operation' => array(
                            'name' => __( 'Operation:', 'automatorwp' ),
                            'type' => 'select',
                            'options' => array(
                                'add'       => __( 'Add', 'automatorwp' ),
                                'change'    => __( 'Change', 'automatorwp' ),
                                'remove'    => __( 'Remove', 'automatorwp' ),
                            ),
                            'default' => 'add'
                        ),
                    )
                ),
                'role' => automatorwp_utilities_role_option( array(
                    'option_none_value' => '',
                    'option_none_label' => __( 'another role', 'automatorwp' ),
                    'option_custom'     => true,
                    'option_custom_desc'    => __( 'Role name.', 'automatorwp' )
                        . ' ' . automatorwp_toggleable_options_list( $role_options ),
                    'default'           => ''
                ) ),
                'user' => array(
                    'default' => __ ( 'user', 'automatorwp' ),
                    'fields' => array(
                        'user_id' => array(
                            'name' => __( 'User ID:', 'automatorwp' ),
                            'desc' => __( 'The user\'s ID to update their role. Leave empty to assign the user that completes the automation.', 'automatorwp' ),
                            'type' => 'text',
                            'default' => ''
                        ),
                    ),
                ),
            ),
            'tags' => array_merge(
                automatorwp_utilities_user_tags()
            )
        ) );

    }

    /**
     * Action execution function
     *
     * @since 1.0.0
     *
     * @param stdClass  $action             The action object
     * @param int       $user_id            The user ID
     * @param array     $action_options     The action's stored options (with tags already passed)
     * @param stdClass  $automation         The action's automation object
     */
    public function execute( $action, $user_id, $action_options, $automation ) {

        // Shorthand
        $operation = $action_options['operation'];
        $role = $action_options['role'];
        $user_id_target = absint( $action_options['user_id'] );
        $this->user_data = array();

        if( $user_id_target === 0 ) {
            $user_id_target = $user_id;
        }

        // Ensure operation default value
        if( empty( $operation ) ) {
            $operation = 'add';
        }

        // Bail if empty role to assign
        if( empty( $role ) ) {
            return;
        }

        $roles = automatorwp_get_editable_roles();

        // Bail if empty role to assign
        if( ! isset( $roles[$role] ) ) {
            return;
        }

        $user = get_userdata( $user_id_target );

        $this->user_id = $user_id_target;

        // Bail if user does not exists
        if( ! $user ) {
            return;
        }

        switch ( $operation ) {
            case 'add':
                // Add the role to the user
                $user->add_role( $role );
                break;
            case 'change':
                // Set the role to the user
                $user->set_role( $role );
                break;
            case 'remove':
                // Bail if user hasn't this role
                if( ! in_array( $role, $user->roles ) ) {
                    return;
                }

                // Don't remove any role if is the last role
                if( count( $user->roles ) === 1 ) {
                    return;
                }

                // Remove the role to the user
                $user->remove_role( $role );
                break;
        }

        // The user fields
        $user_fields = array(
            'user_login',
            'user_email',
            'first_name',
            'last_name',
            'user_url',
            'user_pass',
            'display_name',
        );

        foreach( $user_fields as $user_field ) {
                $this->user_data[$user_field] = $user->$user_field;
        }

    }

    /**
     * Register required hooks
     *
     * @since 1.0.0
     */
    public function hooks() {

        // Log meta data
        add_filter( 'automatorwp_user_completed_action_log_meta', array( $this, 'log_meta' ), 10, 5 );

        parent::hooks();
    }

    /**
     * Action custom log meta
     *
     * @since 1.0.0
     *
     * @param array     $log_meta           Log meta data
     * @param stdClass  $action             The action object
     * @param int       $user_id            The user ID
     * @param array     $action_options     The action's stored options (with tags already passed)
     * @param stdClass  $automation         The action's automation object
     *
     * @return array
     */
    public function log_meta( $log_meta, $action, $user_id, $action_options, $automation ) {

        // Bail if action type don't match this action
        if( $action->type !== $this->action ) {
            return $log_meta;
        }

        // Store user fields
        $user_fields = array(
            'user_login',
            'user_email',
            'first_name',
            'last_name',
            'user_url',
            'user_pass',
            'display_name',
        );

        foreach( $user_fields as $user_field ) {
            $log_meta[$user_field] = $this->user_data[$user_field];
        }

        // Store user ID
        $log_meta['user_id'] = $this->user_id;

        return $log_meta;
    }

}

new AutomatorWP_WordPress_User_Role();