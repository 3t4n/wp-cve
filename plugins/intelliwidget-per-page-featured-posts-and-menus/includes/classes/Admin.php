<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * class-intelliwidget-admin.php - Administration class
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
class IntelliWidgetAdmin {

    var $form;
    var $menus;
    var $terms;
    var $templates;
    var $post_types;
    var $intelliwidgets;
    var $objecttype;
    var $tax_menu;
    var $metabox;       // backwards compatability
    var $post_admin;    // backwards compatability

    function iw() {
        return IntelliWidget::$instance;
    }
    /**
     * Configures the admin object for this request
     */
    function admin_init( $objecttype = '', $idfield = '' ) {
        // this property tells IW how to set/get options ( post uses post_meta, others use options table )
        $this->objecttype       = $objecttype;
        // cache post types
        $this->post_types       = $this->get_eligible_post_types();
        // cache menus
        $this->menus            = $this->get_nav_menus();
        // cache templates
        $this->templates        = $this->get_widget_templates();
        // cache intelliwidgets
        $this->intelliwidgets   = $this->get_intelliwidgets();
        // enqueue JS and CSS
        $this->admin_scripts( $idfield );
        // backward compatibility support for multi post ( pro ) < 1.1.0
        if ( 'IntelliWidgetMultiPostAdmin' == get_class( $this ) ):
            $this->post_admin = new IntelliWidgetPostAdmin();
        endif;
    }

    /**
     * Output scripts to the admin. 
     */
    function admin_scripts( $idfield ) {
        if ( !wp_script_is( 'intelliwidget-js', 'enqueued' ) ): // prevent multiple initialization by other plugins
            wp_enqueue_style( 'intelliwidget-js', trailingslashit( INTELLIWIDGET_URL ) . 'templates/intelliwidget-admin.min.css', array(), INTELLIWIDGET_VERSION );
            wp_enqueue_script( 'intelliwidget-js', trailingslashit( INTELLIWIDGET_URL ) . 'js/intelliwidget' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.js', array( 'jquery-ui-tabs' ), INTELLIWIDGET_VERSION, FALSE );
            wp_localize_script( 'intelliwidget-js', 'IWAjax', array(
                'ajaxurl'   => admin_url( 'admin-ajax.php' ),
                'objtype'   => $this->objecttype,
                'idfield'   => $idfield,
            ) );
        endif;
    }
    
    function save_data( $id ) {
        // reset the data array
        $post_data = array();
        $prefix    = 'intelliwidget_';
        // since we can now save a single meta box via ajax post, 
        // we need to manipulate the existing boxmap
        $box_map = $this->iw()->get_box_map( $id, $this->objecttype );
        // allow customization of input fields
        /***
         * Here is some perlesque string handling. Using grep gives us a subset of relevant data fields
         * quickly. We then iterate through the fields, parsing out the actual data field name and the 
         * box_id from the input key.
         */
        foreach( preg_grep( '#^' . $prefix . '#', array_keys( $_POST ) ) as $field ):
            // find the box id and field name in the post key with a perl regex
            preg_match( '#^' . $prefix . '(preview|\d+)_(\d+)_([\w\-]+)$#', $field, $matches );
            if ( count( $matches ) ):
                if ( !( $id == $matches[ 1 ] ) || !( $box_id = $matches[ 2 ] ) ) continue;
                $name      = $matches[ 3 ];
            else: 
                continue;
            endif;
            // organize this into a 2-dimensional array for later
            // raw html parser/cleaner-upper: see WP docs re: KSES
            if ( in_array( $name, IntelliWidgetStrings::get_fields( 'text' ) ) ):
                if ( current_user_can( 'unfiltered_html' ) ):
                    $post_data[ $box_id ][ $name ] = $_POST[ $field ];
                else:
                    $post_data[ $box_id ][ $name ] = stripslashes( wp_filter_post_kses( addslashes( $_POST[ $field ] ) ) );
                endif;
            else:
                $post_data[ $box_id ][ $name ] = $this->filter_sanitize_input( $_POST[ $field ] );
            endif;
        endforeach;
        // track meta boxes updated
        $boxcounter = 0;
        // additional processing for each box data segment
        foreach ( $post_data as $box_id => $new_instance ):
            // get current values
            if ( ! ( $old_instance = $this->iw()->get_meta( $id, '_intelliwidget_data_', $this->objecttype, $box_id ) ) )
                $old_instance = array();
            foreach ( $new_instance as $name => $value ):
                $old_instance[ $name ] = $value;
                // make sure at least one post type exists
                if ( 'post_types' == $name && empty( $new_instance[ $name ] ) )
                    $old_instance[ 'post_types' ] = array( 'post' );
                if ( 'replace_widget' == $name ) $box_map[ $box_id ] = $new_instance[ 'replace_widget' ];
                // handle multi selects that may not be passed or may just be empty
                if ( 'page_multi' == $name && empty( $new_instance[ 'page' ] ) )
                    $old_instance[ 'page' ] = array();
                if ( 'terms_multi' == $name && empty( $new_instance[ 'terms' ] ) )
                    $old_instance[ 'terms' ] = array();
            endforeach;
            // special handling for checkboxes:
            foreach( IntelliWidgetStrings::get_fields( 'checkbox' ) as $name )
                $old_instance[ $name ] = isset( $new_instance[ $name ] );
            if ( isset( $old_instance[ 'custom_text' ] ) ) $old_instance[ 'custom_text' ] = base64_encode( $old_instance[ 'custom_text' ] );
            // update query cache // do not use for now (2.3.4)
            // $iwq = new IntelliWidgetQuery();
            // $old_instance[ 'querystr' ] = $iwq->iw_query( $old_instance );
            // save new data
            $this->update_meta( $id, '_intelliwidget_data_', $old_instance, $box_id );
            // increment box counter
            $boxcounter++;
        endforeach;
        if ( $boxcounter )
            // if we have updates, save new map
            $this->update_meta( $id, '_intelliwidget_', $box_map, 'map' );
    }

    function save_copy_id( $id ) {
        $copy_id = isset( $_POST[ 'intelliwidget_widget_page_id' ] ) ? intval( $_POST[ 'intelliwidget_widget_page_id' ] ) : NULL;
        if ( isset( $copy_id ) && $copy_id != $id ) // do not allow post to copy itself
            $this->update_meta( $id, '_intelliwidget_', $copy_id, 'widget_page_id' );
    }
    
    function validate_post( $action, $noncefield, $capability, $is_ajax = FALSE, $post_id = NULL, $get = FALSE ) {
        
        return ( ( $get ? 'GET' : 'POST' ) == $_SERVER[ 'REQUEST_METHOD' ] 
            && ( $is_ajax ? check_ajax_referer( $action, $noncefield, FALSE ) : check_admin_referer( $action, $noncefield, FALSE ) )
            && current_user_can( $capability, $post_id )
            );
    }
    
    function get_nonce_url( $id, $action, $box_id = NULL ) {
        global $pagenow; 
        $val = 'delete' == $action ? $box_id : 1;
        return wp_nonce_url( admin_url( $pagenow . '?iw' . $action . '=' . $val . '&objid=' . $id ), 'iw' . $action );
    }
    
    function get_intelliwidgets() {
        global $wp_registered_sidebars;
        $widgets = array();
        foreach ( IntelliWidgetStrings::get_menu( 'replaces' ) as $value => $label ):
            $widgets[ $value ] = $label;
        endforeach;
        foreach( wp_get_sidebars_widgets() as $sidebar_id => $sidebar_widgets ): 
            if ( FALSE === strpos( $sidebar_id, 'wp_inactive' ) 
                && FALSE === strpos( $sidebar_id, 'orphaned' )
                && is_array( $sidebar_widgets ) ):
                $count = 0;
                foreach ( $sidebar_widgets as $sidebar_widget_id ):
                    if ( FALSE !== strpos( $sidebar_widget_id, 'intelliwidget' ) && isset( $wp_registered_sidebars[ $sidebar_id ] ) ):
                        $widgets[ $sidebar_widget_id ] = $wp_registered_sidebars[ $sidebar_id ][ 'name' ] . ' [' . ++$count . ']';
                    endif; 
                endforeach; 
            endif; 
        endforeach;
        return $widgets;
    }
    
    /**
     * Get list of posts as select options. Selects all posts of the type( s ) specified in the instance data
     * and returns them as a multi-select menu. To enable filtering, any selected posts must be passed back
     * with the return set.
     */
    function get_posts_list( $instance = NULL, $profiles = FALSE ) {
        $instance[ 'page' ]             = $this->val2array( isset( $instance[ 'page' ] ) ? $instance[ 'page' ] : '' );
        $instance[ 'profiles_only' ]    = $profiles;
        $iwq                            = new IntelliWidgetQuery();
        $posts                          = $iwq->post_list_query( $instance );
    	$output                         = '';
	    if ( ! empty( $posts ) ) {
            $args = array( $posts, 0, $instance );
            $walker = new IntelliWidgetWalker(); 
	        $output .= call_user_func_array( array( $walker, 'walk' ), $args );
	    }

	    return $output;
    }
    
    /**
     * All terms are retrieved and cached and then each instance is filtered by the Walker class.
     */
    function get_terms_list( $instance = NULL ) {
    	$output = '';
        $post_types = $this->val2array( isset( $instance[ 'post_types' ] ) ? $instance[ 'post_types' ] : '' );
        $instance[ 'terms' ]   = $this->val2array( isset( $instance[ 'terms' ] ) ? $instance[ 'terms' ] : '' );
        $this->load_terms();
        $terms = array();
        foreach ( $this->val2array( preg_grep( '/post_format/', get_object_taxonomies( $post_types ), PREG_GREP_INVERT ) ) as $tax ):
            if ( isset( $this->terms[ $tax ] ) )
                $terms = array_merge( $terms, $this->terms[ $tax ] );
        endforeach;
	    if ( ! empty( $terms ) ) {
            $args = array( $terms, 0, $instance );
            $walker = new IntelliWidgetWalkerTerms(); 
            
	        $output .= call_user_func_array( array( $walker, 'walk' ), $args );
	    }
	    return $output;
    }
    
    /**
     * Return a list of template files in the theme folder( s ) and plugin folder.
     * Templates actually render the output to the widget based on instance settings
     *
     * @return <array>
     */
    function get_widget_templates() {
        $templates  = array();
        $paths      = array();
        $parentPath = get_template_directory() . '/intelliwidget';
        $themePath  = get_stylesheet_directory() . '/intelliwidget';
        $paths[]    = INTELLIWIDGET_DIR . '/templates/';
        $paths[]    = $parentPath;
        if ( $parentPath != $themePath ) $paths[] = $themePath;
        foreach ( $paths as $path ):
            if ( file_exists( $path ) && ( $handle = opendir( $path ) ) ):
                while ( FALSE !== ( $file = readdir( $handle ) ) ):
                    if ( ! preg_match( "/^\./", $file ) && preg_match( '/\.php$/', $file ) ):
                        $file = str_replace( '.php', '', $file );
                        $name = str_replace( '-', ' ', $file );
                        $templates[ $file ] = ucfirst( $name );
                    endif;
                endwhile;
                closedir( $handle );
            endif;
        endforeach;
        // hook custom actions into templates menu
        $templates = apply_filters( 'intelliwidget_templates', $templates );
        asort( $templates, phpversion() < 5.4 ? NULL : SORT_NATURAL | SORT_FLAG_CASE );
        return $templates;
    }
    

    function get_eligible_post_types() {
        $eligible = array();
        if ( function_exists( 'get_post_types' ) ):
            $args   = array( 'public' => TRUE );
            $types  = get_post_types( $args );
        else:
            $types  = array( 'post', 'page' );
        endif;
        foreach( $types as $type ):
            if ( post_type_supports( $type, 'custom-fields' ) ):
                $eligible[] = $type;
            endif;
        endforeach;
        return apply_filters( 'intelliwidget_post_types', $eligible );
    }
    
    function delete_meta( $id, $optionname, $index = NULL ) {
        if ( !empty( $id ) && !empty( $optionname ) ):
            switch( $this->objecttype ):
                case 'post':
                    if ( isset( $index ) ) $optionname .= $index;
                    return delete_post_meta( $id, $optionname );
                default:
                    $optionname = 'intelliwidget_data_' . $this->objecttype . '_' . $id;
                    if ( isset( $index ) && ( $data = get_option( $optionname ) ) ):
                        unset( $data[ $index ] );
                        update_option( $optionname, $data );
                    endif;
            endswitch;
        endif;
    }
    
    function update_meta( $id, $optionname, $data, $index = NULL ) {
        if ( empty( $id ) || empty( $optionname ) ) return FALSE;
        switch( $this->objecttype ):
            case 'post':
                if ( isset( $index ) ) $optionname .= $index;
                $serialized = maybe_serialize( $data );
                
                update_post_meta( $id, $optionname, $serialized );
                break;
            default:
                $optionname = 'intelliwidget_data_' . $this->objecttype . '_' . $id;
                if ( isset( $index ) ):
                    if ( !( $option = get_option( $optionname ) ) )
                        $option = array();
                    $option[ $index ] = $data;
                    update_option( $optionname, $option );
                endif;
        endswitch;
    }
            
    function get_nav_menu() {
        $defaults = IntelliWidgetStrings::get_menu( 'default_nav' );
        return $defaults + $this->menus;
    }
    
    function get_nav_menus() {
        $nav_menus = array();
        $menus = get_terms( 'nav_menu', array( 'hide_empty' => FALSE ) );
        foreach ( $menus as $menu )
            $nav_menus[ $menu->term_id ] = $menu->name;
        return $nav_menus;
    }

    function get_tax_menu() {
        if ( isset( $this->tax_menu ) ) return $this->tax_menu;
        $this->load_terms();
        $menu = array( '' => __( 'None', 'intelliwidget' ) );
        foreach ( array_keys( $this->terms ) as $name ):
            $taxonomy = get_taxonomy( $name );
            $menu[ $name ] = $taxonomy->label;
        endforeach;
        $this->tax_menu = $menu;
        return $menu;
    }

    /**
     * Stub for data validation
     */
    function filter_sanitize_input( $unclean = NULL ) {
        if ( is_array( $unclean ) ):
            return array_map( array( $this, __FUNCTION__ ), $unclean );
        else:
            return sanitize_text_field( $unclean );
        endif;
    }
    
    // Backwards compatability: replaces original category value with new term taxonomy id value
    function map_category_to_tax( $category ) {
        $catarr = $this->val2array( $category );
        $tax = array( 'category' );
        if ( !isset( $this->terms ) ) $this->load_terms();
        return array_map( array( $this, 'lookup_term' ), $catarr, $tax );
    }
    
    function lookup_term( $id, $tax ) {
        foreach( $this->terms[ $tax ] as $term ):
            if ( $term->term_id == $id ) return $term->term_taxonomy_id;
        endforeach;
        return -1;
    }
    
    function load_terms() {
        // cache all available terms ( Lightweight IW objects )
        if ( isset( $this->terms ) ) return;
        $indexarray = array();
        if ( ( $terms = get_terms( array_intersect(
            preg_grep( '/post_format/', get_object_taxonomies( $this->post_types ), PREG_GREP_INVERT ), 
                get_taxonomies( array( 'public' => TRUE, 'query_var' => TRUE ) )
            ), array( 'hide_empty' => FALSE ) ) ) ):
            foreach ( $terms as $object ):
                $indexarray[ $object->taxonomy ][] = $object;
            endforeach;
        endif;
        $this->terms = $indexarray;
    }
    
        
    function val2array( $value ) {
        $value = empty( $value ) ? 
            array() : ( is_array( $value ) ?
                $value : explode( ',', $value ) );
        sort( $value );
        return $value;
    }

    function form_init() {        
        if ( isset( $this->form ) ) return;
        $this->form = new IntelliWidgetForm();
    }
    
    /**
     * backward compatability functions 
     */
    function metabox_init() {
        $this->post_admin->form_init();
        $this->metabox = $this->post_admin->form;
    }
    
    function render_tabbed_sections( $id ) {
        $this->post_admin->render_tabbed_sections( $id );
    }
    
    function get_tip( $key = '' ) {
        return $this->form->get_tip( $key );
    }
    
    function get_label( $key = '' ) {
        return $this->form->get_label( $key );
    }
    
}
