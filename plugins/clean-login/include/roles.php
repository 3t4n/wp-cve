<?php

class CleanLogin_Roles{
    function __construct(){        
    }

    function load(){
        add_action( 'admin_init', array( $this, 'add_roles' ) );
    }

    function add_roles() {
        $create_standby_role = get_option( 'cl_standby' );
        $role = get_role( 'standby' );
    
        if ( $create_standby_role ) {
            // create if neccesary
            if ( !$role )
                $role = add_role('standby', 'StandBy');
            // and remove capabilities
            $role->remove_cap( 'read' );
        } else {
            // remove if exists
            if ( $role )
                remove_role( 'standby' );
        }
    }

    function get_non_admin_roles(){
        $roles = get_editable_roles();
        $result = array();

        unset( $roles['administrator'] );

        foreach( $roles as $key => $value )
            $result[ $key ] = $value['name'];

        return $result;        
    }

    static function get_current_user_roles(){
        $user = wp_get_current_user();
        return (array) $user->roles;
    }
}