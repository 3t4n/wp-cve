<?php

if ( !function_exists( 'filr_fs' ) ) {
    // Create a helper function for easy SDK access.
    function filr_fs()
    {
        global  $filr_fs ;
        
        if ( !isset( $filr_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $filr_fs = fs_dynamic_init( array(
                'id'              => '5843',
                'slug'            => 'filr-protection',
                'type'            => 'plugin',
                'public_key'      => 'pk_09421f79b04aa92c18913b15581bf',
                'is_premium'      => false,
                'has_addons'      => false,
                'has_paid_plans'  => true,
                'has_affiliation' => 'selected',
                'menu'            => array(
                'slug'    => 'edit.php?post_type=filr',
                'contact' => false,
                'support' => false,
            ),
                'is_live'         => true,
            ) );
        }
        
        return $filr_fs;
    }
    
    // Init Freemius.
    filr_fs();
    // Signal that SDK was initiated.
    do_action( 'filr_fs_loaded' );
}

/**
 * Remove freemius pages.
 *
 * @param  bool $is_visible indicates if visible or not.
 * @param  int  $submenu_id current submenu id.
 * @return bool
 */
function filr_is_submenu_visible( $is_visible, $submenu_id )
{
    return false;
}

filr_fs()->add_filter(
    'is_submenu_visible',
    'filr_is_submenu_visible',
    10,
    2
);
/**
 * Add custom icon for Freemius.
 *
 * @return string
 */
function filr_custom_icon()
{
    return FILR_PATH . '/assets/filr-logo.svg';
}

filr_fs()->add_filter( 'plugin_icon', 'filr_custom_icon' );
/**
 * Clean up after uninstallation
 *
 * @return void
 */
function filr_cleanup()
{
    $options = wp_parse_args( get_option( 'filr_status' ), filr\FILR_Admin::get_defaults( 'filr_status' ) );
    
    if ( 'on' === $options['filr_uninstall_delete'] ) {
        delete_option( 'filr_status' );
        delete_option( 'filr_shortcode' );
        $args = array(
            'post_type'      => 'filr',
            'posts_per_page' => -1,
            'fields'         => 'ids',
        );
        // Clean posts and directories.
        $files = get_posts( $args );
        if ( isset( $files ) && !empty($files) ) {
            foreach ( $files as $file_id ) {
                wp_delete_post( $file_id, true );
            }
        }
        // Clean global directorys and files.
        filr\FILR_Filesystem::delete_filr_directory();
        filr\FILR_Filesystem::delete_index_file();
        filr\FILR_Filesystem::delete_htaccess_file();
    }

}

filr_fs()->add_action( 'after_uninstall', 'filr_cleanup' );