<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * class-intelliwidget-form.php - Outputs widget form
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
class IntelliWidgetForm {

    function render_form( $adminobj, $widgetobj, $instance, $is_widget = FALSE ) {
        if ( !has_action( 'intelliwidget_form_all_before' ) ):
            add_action( 'intelliwidget_form_all_before', array( $this, 'general_settings' ), 10, 5 );
            add_action( 'intelliwidget_form_post_list',  array( $this, 'post_selection_settings' ), 5, 5 );
            add_action( 'intelliwidget_form_post_list',  array( $this, 'appearance_settings' ), 10, 5 );
            add_action( 'intelliwidget_form_nav_menu',   array( $this, 'nav_menu' ), 10, 5 );
            add_action( 'intelliwidget_form_tax_menu',   array( $this, 'tax_menu' ), 10, 5 );
            add_action( 'intelliwidget_form_all_after',  array( $this, 'addl_text_settings' ), 10, 5 );
            if ( isset( $_POST[ 'widget-id' ] ) ) add_action( 'intelliwidget_post_selection_menus', array( $this, 'post_selection_menus' ), 10, 4 );
        endif;
        include( INTELLIWIDGET_DIR . '/includes/forms/main.php' );
    }

    function section_header( $adminobj, $widgetobj, $sectionkey, $is_widget = FALSE ) {
        printf(
            '<div class="postbox iw-collapsible closed panel-%4$s" id="%1$s-panel" '
            . 'title="' . __( 'Click to toggle', 'intelliwidget' ) . '">'
            . '<div class="iw-toggle" title="' . __( 'Click to toggle', 'intelliwidget' ) . '"></div>'
            . '<h4 title="%2$s">%3$s</h4><div id="%1$s-panel-inside" class="inside">', 
            $widgetobj->get_field_id( $sectionkey ),
            $this->get_tip( $sectionkey ),
            $this->get_label( $sectionkey ),
            $sectionkey
        );
    }
        
    function section_footer() {
        echo "</div></div>\n";
    }
        
    function get_label( $key = '' ) {
        return IntelliWidgetStrings::get_label( $key );
    }

    function get_tip( $key = '' ) {
        return IntelliWidgetStrings::get_tip( $key );
    }
    
    function general_settings( $adminobj, $widgetobj, $instance, $is_widget = FALSE ) { 
        include( INTELLIWIDGET_DIR . '/includes/forms/general-settings.php' );
    }
    
    function addl_text_settings( $adminobj, $widgetobj, $instance, $is_widget = FALSE ) { 
        include( INTELLIWIDGET_DIR . '/includes/forms/addl-text.php' );
    }

    function appearance_settings( $adminobj, $widgetobj, $instance, $is_widget = FALSE ) { 
        include( INTELLIWIDGET_DIR . '/includes/forms/appearance-settings.php' );
    }

    function post_selection_settings( $adminobj, $widgetobj, $instance, $is_widget = FALSE ) { 
        include( INTELLIWIDGET_DIR . '/includes/forms/post-settings.php' );
    }

    function post_selection_menus( $adminobj, $widgetobj, $instance ) {       
        include( INTELLIWIDGET_DIR . '/includes/forms/post-menu.php' );
    }
    
    function nav_menu( $adminobj, $widgetobj, $instance, $is_widget = FALSE ) { 
        include( INTELLIWIDGET_DIR . '/includes/forms/nav-menu.php' );
    }

    function tax_menu( $adminobj, $widgetobj, $instance, $is_widget = FALSE ) { 
        include( INTELLIWIDGET_DIR . '/includes/forms/tax-menu.php' );
    }
    
    function post_cdf_form( $adminobj, $post ) {
        include( INTELLIWIDGET_DIR . '/includes/forms/custom-fields.php' );
    }

    function timestamp( $field = 'intelliwidget_event_date', $post_date = NULL ) {
        include( INTELLIWIDGET_DIR . '/includes/forms/timestamp.php' );
    }
    
    function copy_form( $obj, $id, $id_list ) {
        include( INTELLIWIDGET_DIR . '/includes/forms/copy.php' );
    }
    
    function add_form( $obj, $id ) {
        include( INTELLIWIDGET_DIR . '/includes/forms/add.php' );
}
}