<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * class-intelliwidget-post.php - Edit Post Settings
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
class IntelliWidgetPost {
    var $admin;
    
    /**
     * Object constructor
     */
    function __construct() {
        add_filter( 'intelliwidget_extension_settings', array( $this, 'get_post_settings' ), 10, 3 );
    }

    // helper function
    function iw() {
        return IntelliWidget::$instance;
    }
    
    function get_post_settings( $instance, $args ) {
        global $post;
        // if there are post-specific settings for this widget, use them
        if ( is_singular() // this is an individual post, custom post type or page
            && is_object( $post ) 
            && isset( $args[ 'widget_id' ] ) ):
            // if this page is using another page's settings and they exist for this widget, use them
            $other_post_id = $this->iw()->get_meta( $post->ID, '_intelliwidget_', 'post', 'widget_page_id' );
            $post_data = $this->iw()->get_settings_data( $post->ID, $args[ 'widget_id' ], 'post' );

            // check for no-copy override
            if ( $other_post_id && empty( $post_data[ 'nocopy' ] ) ):
                if ( $post_data = $this->iw()->get_settings_data( $other_post_id, $args[ 'widget_id' ], 'post' ) ):
                    return $post_data;
                endif;
            endif;
            if ( !empty( $post_data ) ) return $post_data;
        endif;
        return $instance;
    }
    
    
}
