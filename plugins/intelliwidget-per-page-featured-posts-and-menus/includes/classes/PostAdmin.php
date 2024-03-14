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

class IntelliWidgetPostAdmin extends IntelliWidgetAdmin {

    /**
     * Object constructor
     */
    function __construct() {
        // these actions only apply to admin users
        add_action( 'load-post.php',                array( $this, 'post_form_actions' ) );
        add_action( 'load-post-new.php',            array( $this, 'post_form_actions' ) );
        add_action( 'save_post',                    array( $this, 'post_save_data' ), 1, 2 );
        add_action( 'wp_ajax_iw_post_cdfsave',      array( $this, 'ajax_post_save_cdf_data' ) );
        add_action( 'wp_ajax_iw_post_save',         array( $this, 'ajax_post_save_data' ) );
        add_action( 'wp_ajax_iw_post_copy',         array( $this, 'ajax_post_copy_data' ) );
        add_action( 'wp_ajax_iw_post_delete',       array( $this, 'ajax_post_delete_tabbed_section' ) );
        add_action( 'wp_ajax_iw_post_add',          array( $this, 'ajax_post_add_tabbed_section' ) );
        add_action( 'wp_ajax_iw_post_menus',        array( $this, 'ajax_get_post_select_menu_form' ) );
        add_action( 'wp_ajax_iw_post_select_menu',  array( $this, 'ajax_post_get_select_menu' ) );
    }

    function post_form_actions() {
        if ( !isset( $this->objecttype ) ) $this->admin_init( 'post', 'post_ID' );
        add_action( 'add_meta_boxes', array( &$this, 'post_main_meta_box' ) );
        add_action( 'add_meta_boxes', array( &$this, 'post_cdf_meta_box' ) );
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
    }
    /**
     * Generate input form that applies to entire page ( add new, copy settings )
     * @return  void
     */
    function post_main_meta_box() {
        // set up meta boxes
        $this->form_init();
        foreach ( $this->post_types as $type ):
            add_meta_box( 
                'intelliwidget_main_meta_box',
                $this->form->get_label( 'metabox_title' ),
                array( &$this, 'post_meta_box_form' ),
                $type,
                'side',
                'low'
            );
        endforeach;
    }
    
    /**
     * Generate input form that applies to posts
     * @return  void
     */
    function post_cdf_meta_box() {
        global $post;
        foreach ( $this->post_types as $type ):
            add_meta_box( 
                'intelliwidget_post_meta_box',
                $this->form->get_label( 'cdf_title' ),
                array( &$this, 'post_cdf_meta_box_form' ),
                $type,
                'side',
                'low'
            );
        endforeach;
        add_filter( 'default_hidden_meta_boxes', array( &$this, 'hide_post_meta_box' ) );
    }
    
    /**
     * Hide Custom Post Fields Meta Box by default
     */
    function hide_post_meta_box( $hidden ) {
        $hidden[] = 'intelliwidget_post_meta_box';
        return $hidden;
    }
    
    /**
     * Output the form in the page-wide meta box. Params are passed by add_meta_box() function
     */
    function post_meta_box_form( $post, $metabox ) {
        $this->form->copy_form( $this, $post->ID, $this->get_id_list( $post ) );
        $this->render_tabbed_sections( $post->ID );
    }
    
    function get_id_list( $post ) {
        $copy_id = $this->iw()->get_meta( $post->ID, '_intelliwidget_', 'post', 'widget_page_id' );
        return '
  <select style="width:75%" name="intelliwidget_widget_page_id" id="intelliwidget_widget_page_id">
    <option value="">' . __( 'This form', 'intelliwidget' ) . '</option>
      ' . $this->get_posts_list( array( 'post_types' => $this->post_types, 'page' => $copy_id ), TRUE ) . '
  </select>';
    }
    
    /**
     * Output the form in the post meta box. Params are passed by add_meta_box() function
     */
    function post_cdf_meta_box_form( $post, $metabox ) {
        $this->form->post_cdf_form( $this, $post );
    }
    
    /**
     * Parse POST data and update page-specific data using custom fields
     */
    function post_save_data( $id, $post ) {
        /**
         * Skip auto-save and revisions. wordpress saves each post twice, once for the revision and once to update
         * the actual post record. The parameters passed by the 'save_post' action are for the revision, so 
         * we must use the post_ID passed in the form data, and skip the revision. 
         */
        if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
            || ( !empty( $post ) && !in_array( $post->post_type, array( 'post','page' ) ) ) ) return FALSE;

        $post_id = isset( $_POST[ 'post_ID' ] ) ? intval( $_POST[ 'post_ID' ] ) : NULL;
        if ( empty( $post_id )
            // skip nonce test on non-ajax post
            //|| !$this->validate_post( 'iwpage_' . $post_id, 'iwpage', 'edit_post', FALSE, $post_id )
         ) return FALSE;

        $this->admin_init( 'post', 'post_ID' );

        $this->save_data( $post_id );
        // save custom post data if it exists
        $this->post_save_cdf_data( $post_id );
        // save copy page id ( i.e., "use settings from ..." ) if it exists
        $this->save_copy_id( $post_id );
    }

    function post_save_cdf_data( $post_id ) {
        // reset the data array
        $prefix    = 'intelliwidget_';
        foreach ( IntelliWidgetStrings::get_fields( 'custom' ) as $cfield ):
            $cdfield = $prefix . $cfield;
            if ( array_key_exists( $cdfield, $_POST ) ):
                if ( empty( $_POST[ $cdfield ] ) || '' == $_POST[ $cdfield ] ):
                    $this->delete_meta( $post_id, $cdfield );
                else:
                    $newdata = $_POST[ $cdfield ];
                    if ( !current_user_can( 'unfiltered_html' ) ):
                        $newdata = stripslashes( 
                        wp_filter_post_kses( addslashes( $newdata ) ) ); 
                    endif;
                    $this->update_meta( $post_id, $cdfield, $newdata );
                endif;
            endif;
        endforeach;
    }
    
    // ajax save for posts only - duplicate this for other types
    function ajax_post_save_data() {
        $this->admin_init( 'post', 'post_ID' );
        $post_id = isset( $_POST[ 'post_ID' ] ) ? intval( $_POST[ 'post_ID' ] ) : NULL;
        $box_id_key = current( preg_grep( "/_box_id$/", array_keys( $_POST ) ) );
        $box_id = isset( $_POST[ $box_id_key ] ) ? intval( $_POST[ $box_id_key ] ) : NULL;
        if ( empty( $post_id ) || empty( $box_id ) || 
            !$this->validate_post( 'iwpage_' . $post_id, 'iwpage', 'edit_post', TRUE, $post_id ) ) die( json_encode( 'fail' ) );
        $this->ajax_save_data( $post_id, $box_id );
    }
    
    // ajax copy for posts only - duplicate this for other types
    function ajax_post_copy_data() {
        $this->admin_init( 'post', 'post_ID' );
        $post_id = isset( $_POST[ 'post_ID' ] ) ? intval( $_POST[ 'post_ID' ] ) : NULL;
        if ( empty( $post_id ) ||  
            !$this->validate_post( 'iwpage_' . $post_id, 'iwpage', 'edit_post', TRUE, $post_id ) ) die( json_encode( 'fail' ) );

        if ( FALSE === $this->save_copy_id( $post_id ) ) die( json_encode( 'fail' ) );
        die( json_encode( 'success' ) );
    }
    
    // posts only
    function ajax_post_save_cdf_data() {
        $this->admin_init( 'post', 'post_ID' );
        $post_id = isset( $_POST[ 'post_ID' ] ) ? intval( $_POST[ 'post_ID' ] ) : NULL;
        if ( empty( $post_id ) || 
            !$this->validate_post( 'iwpage_' . $post_id, 'iwpage', 'edit_post', TRUE, $post_id ) ) die( json_encode( 'fail' ) );
        if ( FALSE === $this->post_save_cdf_data( $post_id ) ) die( json_encode( 'fail' ) );
        die( json_encode( 'success' ) );
    }
    
    // ajax delete for posts only - duplicate this for other types
    function ajax_post_delete_tabbed_section() {
        $this->admin_init( 'post', 'post_ID' );
        // note that the query string version uses "post" instead of "post_ID"
        $post_id = isset( $_POST[ 'objid' ] ) ? intval( $_POST[ 'objid' ] ) : NULL;
        $box_id = isset( $_POST[ 'iwdelete' ] ) ? intval( $_POST[ 'iwdelete' ] ) : NULL;
        if ( empty( $post_id ) || //empty( $box_id ) || 
            !$this->validate_post( 'iwdelete', '_wpnonce', 'edit_post', TRUE, $post_id ) ) die( json_encode( 'fail' ) );
        if ( FALSE === $this->delete_tabbed_section( $post_id, $box_id ) ) die( json_encode( 'fail' ) );
        die( json_encode( 'success' ) );
    }

    // ajax add for posts only - duplicate this for other types
    function ajax_post_add_tabbed_section() {
        $this->admin_init( 'post', 'post_ID' );
        // note that the query string version uses "post" instead of "post_ID"
        $post_id = isset( $_POST[ 'objid' ] ) ? intval( $_POST[ 'objid' ] ) : NULL;
        if ( empty( $post_id ) 
            || !$this->validate_post( 'iwadd', '_wpnonce', 'edit_post', TRUE, $post_id ) ) die( json_encode( 'fail' ) );
        // note that the query string version uses "post" instead of "post_ID"
        $this->ajax_add_tabbed_section( $post_id );
    }
    
    /*
     * ajax_get_post_select_menus
     * This is an important improvement to the application for performance.
     * We now dynamically load all walker-generated menus when the panel is opened
     * and reuse the same DOM element to render them on the page. Since only one panel
     * is ever in use at a time, we remove them from any panels not currently in use
     * and reload them when they are focus()ed again. The reused DOM element also prevents
     * memory leakage from multiple xhr refreshes of multiple copies of the same huge lists.
     */
    function ajax_get_post_select_menus( $id, $box_id ) {
        $this->form_init();
        $instance = $this->iw()->defaults( $this->iw()->get_meta( $id, '_intelliwidget_data_', $this->objecttype, $box_id ) );
        $section = new IntelliWidgetSection( $id, $box_id );
        ob_start();
        $this->form->post_selection_menus( $this, $section, $instance );
        $form = ob_get_contents();
        ob_end_clean();
        die( json_encode( $form ) );
    }
    
    function ajax_get_post_select_menu_form() {
        $this->admin_init( 'post', 'post_ID' );
        $post_id                = isset( $_POST[ 'post_ID' ] ) ? intval( $_POST[ 'post_ID' ] ) : NULL;
        $box_id_key             = current( preg_grep( "/_box_id$/", array_keys( $_POST ) ) );
        $box_id                 = isset( $_POST[ $box_id_key ] ) ? intval( $_POST[ $box_id_key ] ) : NULL;
        if ( empty( $post_id ) || empty( $box_id ) || !$this->validate_post( 'iwpage_' . $post_id, 'iwpage', 'edit_post', TRUE, $post_id ) ) 
            die( json_encode( 'fail' ) );
        $this->ajax_get_post_select_menus( $post_id, $box_id );
    }
    
    /**
     * Get multi select menu options for specific instance. To cut down on response size,
     * The result set is limited to 200 items. Items that are truncated from the default
     * set can be retreived in subsquent ajax requests by passing a filter value. This solution
     * was chosen in favor of more complicated paging and caching to keep the interface
     * as simple as possible.
     * 
     * For backward compatibility with IW Pro, we are determining
     * admin_init parameters by the fields passed.
     * Ideally there would be a separate ajax action method for each object type.
     */
    function ajax_post_get_select_menu() {
        foreach ( array( 'post', 'condset', 'term' ) as $obj ):
            $key = $obj . ( 'term' == $obj ? '_taxonomy' : '' ) . '_ID';
            if ( array_key_exists( $key, $_POST ) ):
                $this->admin_init( $obj, $key );
                break;
            endif;
        endforeach;
        $post_id    = isset( $_POST[ $key ] ) ? intval( $_POST[ $key ] ) : NULL;
        $box_id_key = current( preg_grep( "/_box_id$/", array_keys( $_POST ) ) );
        $box_id     = isset( $_POST[ $box_id_key ] ) ? intval( $_POST[ $box_id_key ] ) : NULL;
        // validate for all object types
        if ( 
            empty( $this->objecttype ) || 
            empty( $post_id ) || 
            empty( $box_id ) || 
            ( 'post' == $this->objecttype && !$this->validate_post( 'iwpage_' . $post_id, 'iwpage', 'edit_post', TRUE, $post_id ) ) ||
            ( 'term' == $this->objecttype && !$this->validate_post( 'iwpage_' . $post_id, 'iwpage', 'manage_options', TRUE ) ) ||
            ( 'condset' == $this->objecttype && !$this->validate_post( 'iwpage_' . $post_id, 'iwpage', 'manage_options', TRUE ) ) 
            )
            die( json_encode( 'fail' ) );
        $instance = $this->iw()->defaults( $this->iw()->get_meta( $post_id, '_intelliwidget_data_', $this->objecttype, $box_id ) );
        $type                   = 'terms' == $_POST[ 'menutype' ] ? 'terms' : 'page';
        /**
         * To allow for filtering of the results, any selected items must be passed in the request and then passed back
         * in the result set. This is done by temporarily modifying the instance with the selections from the request. 
         * In addition, the filter search string is added to the instance and passed to the function. The result is a
         * combination of the search results and the currently selected items.
         */
        // FIXME -- use get_field_id function here?
        $selectedkey = 'intelliwidget_' . $post_id . '_' . $box_id . '_' . $type;
        $instance[ $type ] = $this->filter_sanitize_input( $_POST[ $selectedkey ] );
        $instance[ $type . 'search' ] = $this->filter_sanitize_input( $_POST[ $selectedkey . 'search' ] );
        $function = 'terms' == $type ? 'get_terms_list' : 'get_posts_list' ;
        ob_start();
        echo $this->{ $function }( $instance );
        $form = ob_get_contents();
        ob_end_clean();
        die( json_encode( $form ) );
    }
    
    
    function begin_tab_container() {
        echo apply_filters( 'intelliwidget_start_tab_container', 
            '<div class="iw-tabbed-sections"><a class="iw-larr">&#171</a><a class="iw-rarr">&#187;</a><ul class="iw-tabs">' );
    }
    
    function end_tab_container() {
        echo apply_filters( 'intelliwidget_end_tab_container', '</ul>' );
    }
    
    function begin_section_container() {
        echo apply_filters( 'intelliwidget_start_section_container', '' );
    }

    function end_section_container() {
        echo apply_filters( 'intelliwidget_end_section_container', '</div>' );
    }
    
    function render_tabbed_sections( $id ) {
        $this->form->add_form( $this, $id );
        // box_map contains map of meta boxes to their related widgets
        $box_map = $this->iw()->get_box_map( $id, $this->objecttype );
        if ( is_array( $box_map ) ):
            ksort( $box_map );
            $tabs = $form = '';
            foreach( $box_map as $box_id => $sidebar_widget_id ):
                $instance   = $this->iw()->defaults(
                    $this->iw()->get_meta(
                        $id, '_intelliwidget_data_', 
                        $this->objecttype, $box_id
                    )
                );
                $section = new IntelliWidgetSection( $id, $box_id );
                $section->set_title( empty( $this->intelliwidgets[ $instance[ 'replace_widget' ] ] ) ? 
                    $this->intelliwidgets[ 'none' ] : 
                        $this->intelliwidgets[ $instance[ 'replace_widget' ] ] );

                $tabs .= $section->get_tab() . "\n";
                $form .= $section->begin_section() 
                    . $this->get_form( $section, $instance )
                    . $section->end_section() . "\n";
            endforeach;
            $this->begin_tab_container();
            echo $tabs;
            $this->end_tab_container();
            $this->begin_section_container();
            echo $form;
            $this->end_section_container();
        endif;
    }
    
    function get_form( $section, $instance ) {
        ob_start();
        $this->form->render_form( $this, $section, $instance );
        $form = ob_get_contents();
        ob_end_clean();
        return $form;
    }
    
    function delete_tabbed_section( $id, $box_id ) {
        $box_map = $this->iw()->get_box_map( $id, $this->objecttype );
        $this->delete_meta( $id, '_intelliwidget_data_', $box_id );
        unset( $box_map[ NULL == $box_id ? '' : $box_id ] );
        $this->update_meta( $id, '_intelliwidget_', $box_map, 'map' );
    }

    function add_tabbed_section( $id ) {
        $box_map = $this->iw()->get_box_map( $id, $this->objecttype );

        if ( count( $box_map ) ): 
            $newkey = max( array_keys( $box_map ) ) + 1;
        else: 
            $newkey = 1;
        endif;
        $box_map[ $newkey ] = '';
        $this->update_meta( $id, '_intelliwidget_', $box_map, 'map' );
        return $newkey;
        //return FALSE;
    }
    
    // use this for all saves
    function ajax_save_data( $id, $box_id ) {
        if ( FALSE === $this->save_data( $id ) ) die( json_encode( 'fail' ) ); 
        $this->form_init();
        add_action( 'intelliwidget_post_selection_menus', array( $this->form, 'post_selection_menus' ), 10, 4 );
        $instance = $this->iw()->defaults( $this->iw()->get_meta( $id, '_intelliwidget_data_', $this->objecttype, $box_id ) );
        $section = new IntelliWidgetSection( $id, $box_id );
        $section->set_title( empty( $this->intelliwidgets[ $instance[ 'replace_widget' ] ] ) ? 
            $this->intelliwidgets[ 'none' ] : 
                $this->intelliwidgets[ $instance[ 'replace_widget' ] ] );

        die( json_encode( array(
            'tab'   => $section->get_tab(),
            'form'  => $this->get_form( $section, $instance ),
        ) ) );
    }
    
    // use this for all adds
    function ajax_add_tabbed_section( $id ) {
        if ( !( $box_id = $this->add_tabbed_section( $id ) ) ) die( json_encode( 'fail' ) );
        $this->form_init();
        $instance = $this->iw()->defaults();
        $section = new IntelliWidgetSection( $id, $box_id );
        $section->set_title( empty( $this->intelliwidgets[ $instance[ 'replace_widget' ] ] ) ? 
            $this->intelliwidgets[ 'none' ] : 
                $this->intelliwidgets[ $instance[ 'replace_widget' ] ] );

        $response = array(
                'tab'   => $section->get_tab(),
                'form'  => $section->begin_section( $id, $box_id ) . $this->get_form( $section, $instance ) . $section->end_section(),
            );
        die( json_encode( $response ) );
    }
}
