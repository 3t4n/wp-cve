<?php


// Creates new user role CLients
function ucd_default_client_roles() {
    global $wp_roles;
    add_role( 'client', __(
    'Client' ),
    array(
    'read' => true,
    )
    );

    // Gets role Client
    $role = get_role( 'client' );

    // Gives Client role default user capabilites
    $role->add_cap( 'read' );
    $role->add_cap( 'edit_published_posts' );
    $role->add_cap( 'upload_files' );
    $role->add_cap( 'publish_posts' );
    $role->add_cap( 'delete_published_posts' );
    $role->add_cap( 'edit_posts' );
    $role->add_cap( 'delete_posts' );
    $role->add_cap( 'moderate_comments' );
    $role->add_cap( 'manage_categories' );
    $role->add_cap( 'manage_links' );
    $role->add_cap( 'edit_others_posts' );
    $role->add_cap( 'edit_pages' );
    $role->add_cap( 'edit_others_pages' );
    $role->add_cap( 'edit_published_pages' );
    $role->add_cap( 'publish_pages' );
    $role->add_cap( 'delete_pages' );
    $role->add_cap( 'delete_others_pages' );
    $role->add_cap( 'delete_published_pages' );
    $role->add_cap( 'delete_others_posts' );
    $role->add_cap( 'delete_private_posts' );
    $role->add_cap( 'edit_private_posts' );
    $role->add_cap( 'read_private_posts' );
    $role->add_cap( 'delete_private_pages' );
    $role->add_cap( 'edit_private_pages' );
    $role->add_cap( 'unfiltered_html' );
}
add_action( 'ucd_extension_activation', 'ucd_default_client_roles');


// Adds Appearance Capability
function ucd_client_caps_appearance() {
$dash_client_appearance = get_option('ucd_client_appearance');
    global $wp_roles;
    $role = get_role( 'client' );
    if (!empty($dash_client_appearance)) {
        $role->add_cap( 'edit_theme_options' );
    } else {
        $role->remove_cap( 'edit_theme_options' );
    }
}
add_action( 'admin_init', 'ucd_client_caps_appearance');


// Adds Settings Capability
function ucd_client_caps_settings() {
$dash_client_settings = get_option('ucd_client_settings');
    global $wp_roles;
    $role = get_role( 'client' );
    if (!empty($dash_client_settings)) {
        $role->add_cap( 'manage_options' );
    } else {
        $role->remove_cap( 'manage_options' );
    }
}
add_action( 'admin_init', 'ucd_client_caps_settings');


// Adds Manage Users Capability
function ucd_client_caps_users() {
$dash_manage_users = get_option('ucd_client_manage_users');
    global $wp_roles;
    $role = get_role( 'client' );
    if (!empty($dash_manage_users)) {
        $role->add_cap( 'edit_users' );
        $role->add_cap( 'create_users' );
        $role->add_cap( 'delete_users' );
        $role->add_cap( 'list_users' );
    } else {
        $role->remove_cap( 'edit_users' );
        $role->remove_cap( 'create_users' );
        $role->remove_cap( 'delete_users' );
        $role->remove_cap( 'list_users' );
    }
}
add_action( 'admin_init', 'ucd_client_caps_users');


// Adds Manage Plugins Capability
function ucd_client_caps_plugins() {
$dash_manage_plugins = get_option('ucd_client_manage_plugins');
    global $wp_roles;
    $role = get_role( 'client' );
    if (!empty($dash_manage_plugins)) {
        $role->add_cap( 'install_plugins' );
        $role->add_cap( 'delete_plugins' );
        $role->add_cap( 'activate_plugins' );
        $role->add_cap( 'update_plugins' );
    } else {
        $role->remove_cap( 'install_plugins' );
        $role->remove_cap( 'delete_plugins' );
        $role->remove_cap( 'activate_plugins' );
        $role->remove_cap( 'update_plugins' );
    }
}
add_action( 'admin_init', 'ucd_client_caps_plugins');


// Adds Manage Themes Capability
function ucd_client_caps_themes() {
$dash_manage_themes = get_option('ucd_client_manage_themes');
    global $wp_roles;
    $role = get_role( 'client' );
    if (!empty($dash_manage_themes)) {
        $role->add_cap( 'install_themes' );
        $role->add_cap( 'switch_themes' );
        $role->add_cap( 'update_themes' );
        $role->add_cap( 'edit_themes' );
        $role->add_cap( 'delete_themes' );
    } else {
        $role->remove_cap( 'install_themes' );
        $role->remove_cap( 'switch_themes' );
        $role->remove_cap( 'update_themes' );
        $role->remove_cap( 'edit_themes' );
        $role->remove_cap( 'delete_themes' );
    }
}
add_action( 'admin_init', 'ucd_client_caps_themes');


// Adds Manage Update Capability
function ucd_client_caps_update() {
$dash_update_capability = get_option('ucd_client_update_capability');
    global $wp_roles;
    $role = get_role( 'client' );
    if (!empty($dash_update_capability)) {
        $role->add_cap( 'update_core' );
    } else {
        $role->remove_cap( 'update_core' );
    }
}
add_action( 'admin_init', 'ucd_client_caps_update');


// Adds Update Files Capability
function ucd_client_caps_files() {
$dash_edit_files = get_option('ucd_client_edit_files');
    global $wp_roles;
    $role = get_role( 'client' );
    if (!empty($dash_edit_files)) {
        $role->add_cap( 'edit_files' );
        $role->add_cap( 'edit_themes' );
        $role->add_cap( 'edit_plugins' );
    } else {
        $role->remove_cap( 'edit_files' );
        $role->remove_cap( 'edit_themes' );
        $role->remove_cap( 'edit_plugins' );
    }
}
add_action( 'admin_init', 'ucd_client_caps_files');


// Adds Import Capability
function ucd_client_caps_import() {
$dash_import = get_option('ucd_client_import');
    global $wp_roles;
    $role = get_role( 'client' );
    if (!empty($dash_import)) {
        $role->add_cap( 'import' );
    } else {
        $role->remove_cap( 'import' );
    }
}
add_action( 'admin_init', 'ucd_client_caps_import');


// Adds Export Capability
function ucd_client_caps_export() {
$dash_export = get_option('ucd_client_export');
    global $wp_roles;
    $role = get_role( 'client' );
    if (!empty($dash_export)) {
        $role->add_cap( 'export' );
    } else {
        $role->remove_cap( 'export' );
    }
}
add_action( 'admin_init', 'ucd_client_caps_export');


// Allow client user role to create, edit, or delete admin account
function ucd_client_map_meta_cap( $caps, $cap, $user_id, $args ) {
$ucd_client_manage_admin = get_option('ucd_client_manage_administrators');
  	$check_caps = [
    		'edit_user',
    		'remove_user',
    		'promote_user',
    		'delete_user',
    		'delete_users'
  	];
  	if( !in_array( $cap, $check_caps ) || current_user_can('administrator') ) {
        if(!empty($ucd_client_manage_admin)) {
        		return $caps;
        }
  	}
  	$other = get_user_by( 'id', empty( $args[0] ) ? false : $args[0] );
  	$current_user = wp_get_current_user();
  	if( $other && $other->has_cap('administrator') ) {
    		if ( in_array( 'client', $current_user->roles ) ) {
            if(empty($ucd_client_manage_admin)) {
			           $caps[] = 'do_not_allow';
            }
        }
    }
  	return $caps;
}
add_filter('map_meta_cap', 'ucd_client_map_meta_cap', 10, 4 );

add_filter( 'editable_roles', 'ucd_client_editable_roles');
function ucd_client_editable_roles( $roles ){
$ucd_client_manage_admin = get_option('ucd_client_manage_administrators');
  	$current_user = wp_get_current_user();
    if(empty($ucd_client_manage_admin)) {
      	if ( in_array( 'client', $current_user->roles ) ) {
        		 unset( $roles['administrator'] );
      	}
  	return $roles;
    } else {
        return $roles;
    }
}
