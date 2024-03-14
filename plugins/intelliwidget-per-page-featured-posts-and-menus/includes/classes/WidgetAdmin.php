<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * class-intelliwidget-widget-admin.php - IntelliWidget Widget Admin Class
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */

class IntelliWidgetWidgetAdmin extends IntelliWidgetAdmin {

    /**
     * Constructor
     */
    function __construct() {
        add_action( 'load-widgets.php',                 array( &$this, 'admin_init' ) );
        add_action( 'wp_ajax_iw_widget_menus',          array( &$this, 'ajax_get_widget_select_menu_form' ) );
        add_action( 'wp_ajax_iw_widget_select_menu',    array( &$this, 'ajax_get_widget_select_menu' ) );
        $this->form_init();
    }
    
    /**
     * Widget Update method
     */
    function update( $new_instance, $old_instance ) {
        foreach ( $new_instance as $name => $value ):
            // special handling for text inputs
            if ( in_array( $name, IntelliWidgetStrings::get_fields( 'text' ) ) ):
                if ( current_user_can( 'unfiltered_html' ) ):
                    $old_instance[ $name ] =  $value;
                else:
                    // raw html parser/cleaner-upper: see WP docs re: KSES
                    $old_instance[ $name ] = stripslashes( 
                    wp_filter_post_kses( addslashes( $value ) ) ); 
                endif;
            elseif ( 0 === strpos( $name, 'iw' ) || in_array( $name, array( 'pagesearch', 'termsearch', 'profiles_only' ) ) ):
                unset( $old_instance[ $name ] );
            else:
                $old_instance[ $name ] = $this->filter_sanitize_input( $value );
            endif;
            // handle multi selects that may not be passed or may just be empty
            if ( 'page_multi' == $name && empty( $new_instance[ 'page' ] ) )
                $old_instance[ 'page' ] = array();
            if ( 'terms_multi' == $name && empty( $new_instance[ 'terms' ] ) )
                $old_instance[ 'terms' ] = array();
        endforeach;
        foreach ( IntelliWidgetStrings::get_fields( 'checkbox' ) as $name )
            $old_instance[ $name ] = isset( $new_instance[ $name ] );
        //$iwq = new IntelliWidgetQuery(); // do not use for now ( 2.3.4 )
        //$old_instance[ 'querystr' ] = $iwq->iw_query( $old_instance );
        return $old_instance;
    }
    /**
     * Output Widget form
     */
    function render_form( $obj, $instance ) {
        // initialize admin object in case form is called outside of widgets page
        if ( !isset( $this->objecttype ) ) $this->admin_init();
        $instance = $this->iw()->defaults( $instance );
        $this->form->render_form( $this, $obj, $instance, TRUE );
    }
        
    // widgets only
    function get_widget_instance( &$widget, &$instance ) {
        global $wp_registered_widgets;
        $widget_id          = sanitize_text_field( $_POST[ 'widget-id' ] );
        if ( isset( $_POST[ 'wp_customize' ] ) && 'on' == $_POST[ 'wp_customize' ] ):
            $action         = 'update-widget';
            $nonce          = 'nonce';
            $is_customizer  = TRUE;
        else:
            $action         = 'save-sidebar-widgets';
            $nonce          = '_wpnonce_widgets';
            $is_customizer  = FALSE;
        endif;
        if ( empty( $widget_id )
            || !$this->validate_post( $action, $nonce, 'edit_theme_options', TRUE ) 
            ) return FALSE;
        // getting to the widget info is a complicated task ...
        if ( isset( $wp_registered_widgets[ $widget_id ] ) ):
            if ( isset( $wp_registered_widgets[ $widget_id ][ 'callback' ] ) && isset( $wp_registered_widgets[ $widget_id ][ 'params' ] )
                && count( $wp_registered_widgets[ $widget_id ][ 'callback' ] ) && count( $wp_registered_widgets[ $widget_id ][ 'params' ] ) ):
                $widget     = array_shift( $wp_registered_widgets[ $widget_id ][ 'callback' ] );
                $params     = array_shift( $wp_registered_widgets[ $widget_id ][ 'params' ] );
                $settings   = $widget->get_settings( $widget_id );
                $instance   = $settings[ $params[ 'number' ] ];
                // FIXME: why don't the global wp_registered_widgets callbacks return the correct id and number???!!!
                // this is a heinous kludge to force correct widget object instance
                $widget->_set( $params[ 'number' ] );
                return $params[ 'number' ];
            endif;
        elseif ( $is_customizer ):
            // this must be a new customizer widget so create a temporary instance
            $widget         = new IntelliWidgetWidget();
            $instance       = $this->iw()->defaults( $instance );
            $number         = str_replace( $widget->id_base . '-', '', $widget_id );
            $widget->_set( $number );
            return $number;
        endif;
        return FALSE;
    }
    
    function ajax_get_widget_select_menu_form() {
        $this->admin_init();
        $widget     = NULL;
        $instance   = array();
        if ( $this->get_widget_instance( $widget, $instance ) ):
            $this->form_init();
            ob_start();
            $this->form->post_selection_menus( $this, $widget, $instance );
            $form = ob_get_contents();
            ob_end_clean();
            die( json_encode( $form ) );
        endif;
        die( json_encode( 'fail' ) );
    }
    
    function ajax_get_widget_select_menu() {
        $this->admin_init();
        $widget     = NULL;
        $instance   = array();
        if ( ( $number = $this->get_widget_instance( $widget, $instance ) ) ):
            $type = 'terms' == $_POST[ 'menutype' ] ? 'terms' : 'page';
            // FIXME -- use get_field_id function here?
            $selectedkey            = 'widget-intelliwidget-' . $number . '-' . $type;
            $instance[ $type ]      = $this->filter_sanitize_input( $_POST[ $selectedkey ] );
            $instance[ $type . 'search' ] = $this->filter_sanitize_input( $_POST[ $selectedkey . 'search' ] );
            $function               = 'terms' == $type ? 'get_terms_list' : 'get_posts_list' ;
            $this->form_init();
            ob_start();
            echo $this->{ $function }( $instance );
            $form                   = ob_get_contents();
            ob_end_clean();
            die( json_encode( $form ) );
        endif;
        die( json_encode( 'fail' ) );
    }

  
}
