<?php

function qem_short_desc_filter( $desc, $caption, $style )
{
    return '<p class="desc" ' . $style . '>' . $caption . do_shortcode( $desc ) . '</p>';
}

function qem_description_filter( $content )
{
    return do_shortcode( $content );
}

function qem_enqueue_scripts()
{
    global  $qem_fs ;
    wp_enqueue_style(
        'event_style',
        plugins_url( 'quick-event-manager.css', __FILE__ ),
        array(),
        QUICK_EVENT_MANAGER_PLUGIN_VERSION
    );
    wp_add_inline_style( 'event_style', wp_strip_all_tags( qem_generate_css() ) );
    wp_enqueue_script(
        'event_script',
        plugins_url( 'quick-event-manager.js', __FILE__ ),
        array( 'jquery', 'wp-i18n' ),
        QUICK_EVENT_MANAGER_PLUGIN_VERSION,
        true
    );
    wp_set_script_translations( 'event_script', 'quick-event-manager' );
    wp_enqueue_script( 'jquery-ui-datepicker' );
}

function qem_add_custom_types( $query )
{
    
    if ( !is_admin() && $query->is_category() || $query->is_tag() && $query->is_main_query() ) {
        $query->set( 'post_type', array( 'post', 'event', 'nav_menu_item' ) );
        return $query;
    }

}

function event_plugin_action_links( $links, $file )
{
    
    if ( $file == QUICK_EVENT_MANAGER_PLUGIN_FILE ) {
        $event_links = '<a href="' . get_admin_url() . 'options-general.php?page=' . QUICK_EVENT_MANAGER_PLUGIN_NAME . '">' . __( 'Settings', 'quick-event-manager' ) . '</a>';
        array_unshift( $links, $event_links );
    }
    
    return $links;
}
