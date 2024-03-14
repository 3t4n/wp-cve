<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/*
    Plugin Name: IntelliWidget Per Page Custom Menus and Dynamic Content
    Plugin URI: http://www.lilaeamedia.com/plugins/intelliwidget
    Description: Display custom menus, featured posts, custom post types and other dynamic content on a per page, per post or site-wide basis.
    Version: 2.3.9.2
    Author: Lilaea Media
    Author URI: http://www.lilaeamedia.com/
    Text Domain: intelliwidget
    Domain Path: /lang
    License: GPLv2
    * *************************************************************************
    Copyright (C) 2013-2017 Lilaea Media LLC
    Portions inspired by Featured Page Widget 
    Copyright (C) 2009-2011 GrandSlambert http://grandslambert.com/
*/

class IntelliWidget {
    static $instance;
    var $pluginName;
    var $shortName;
    var $menuName;
    var $admin_hook;
    var $dir;
    var $shortcode_id = 0;
    var $pro_link;
    var $term_children = array();
    
    /**
     * Object constructor
     */
    function __construct() {
        self::$instance = $this;
        // activate autoloader
        spl_autoload_register( 'IntelliWidget::autoload' );
        /* Plugin Details */
        $this->pluginName   = __( 'IntelliWidget', 'intelliwidget' );
        $this->shortName    = __( 'IntelliWidget', 'intelliwidget' );
        $this->menuName     = 'intelliwidget';
        $this->pro_link     = '<a href="' . LILAEAMEDIA_URL . '" target="_blank">' . __( 'our website', 'intelliwidget' ) . '</a>';     
        add_shortcode( 'intelliwidget',     array( $this, 'intelliwidget_shortcode' ) );
        add_action( 'plugins_loaded',       array( $this, 'intelliwidget_activate' ) );
        add_action( 'after_setup_theme',    array( $this, 'ensure_post_thumbnails_support' ) );
        add_action( 'clean_term_cache',     array( $this, 'clean_term_cache' ), 10, 2 );
        // Load the language support
        add_action( 'init' ,                array( $this, 'load_lang' ) );
        // initialize the widget
        add_action( 'widgets_init',         array( $this, 'register_widget' ) );
        // use custom field settings for post links
        add_filter( 'post_link',            array( $this, 'post_link' ), 100, 3 );
        // use custom field settings for post titles
        add_filter( 'the_title',            array( $this, 'post_title' ), 100, 2 );
        // activate post-specific settings and admin
        if ( is_admin() ):
            new IntelliWidgetPostAdmin();
        else:
            new IntelliWidgetPost();
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        endif;
    }

    /**
     * autoloader
     */
    static function autoload( $class ) {
        $file = dirname( __FILE__ ) . '/includes/classes/' . str_replace( 'IntelliWidget', '', $class, $count ) . '.php';
        if ( $count && file_exists( $file ) )
            include_once( $file );
    }     
    /**
     * Stub for plugin activation
     */
    function intelliwidget_activate() {
        // notice to upgrade to IntelliWidget Pro if using old ATX plugin
        if ( defined( 'INTELLIWIDGET_ATX_VERSION' ) ):
            add_action( 'admin_notices',         array( $this, 'install_warning' ) );
            add_action( 'network_admin_notices', array( $this, 'install_warning' ) );
            // disable ATX as it will cause errors
            add_action( 'admin_init',            array( $this, 'deactivate_atx' ) );
            
        endif;
    }

    function install_warning() {
?>
<div class="error">
  <p>
<?php printf( __( 'IntelliWidget for Multi Post Pages is not compatible with this version of IntelliWidget. Please visit %s for a free upgrade to IntelliWidget Pro.','intelliwidget' ), $this->pro_link ); ?>
  </p>
</div>
<?php
    }

    function load_lang() {
        load_plugin_textdomain( 'intelliwidget', FALSE, basename( INTELLIWIDGET_DIR ) . '/lang' );
    }

    function deactivate_atx() {
        if ( isset( $_GET[ 'action' ] ) && 'activate' == $_GET[ 'action' ] && 'intelliwidget-multi-post/intelliwidget-multi-post.php' == $_GET[ 'plugin' ] )
            unset( $_GET[ 'action' ] );
        elseif ( isset( $_GET[ 'activate' ] ) )
            unset( $_GET[ 'activate' ] );
        if ( current_user_can( 'activate_plugins' ) )
            deactivate_plugins( 'intelliwidget-multi-post/intelliwidget-multi-post.php' );
    }
    
    function register_widget() {
        register_widget( "IntelliWidgetWidget" );
    }

    // semaphore to create options page once
    function set_admin_hook( $hook ) {
        $this->admin_hook = $hook;
    }
    
    function get_meta( $id, $optionname, $objecttype, $index = NULL ) {
        // are there settings for this widget?
        if ( !empty( $id ) && !empty( $objecttype ) ):
            switch( $objecttype ):
                case 'post':               
                    if ( isset( $index ) ) $optionname .= $index;
                    $instance = maybe_unserialize( get_post_meta( $id, $optionname, TRUE ) );
                    break;
                default:
                    $optionname = 'intelliwidget_data_' . $objecttype . '_' . $id;
                    if ( $data = get_option( $optionname ) ):
                        if ( isset( $index ) && isset( $data[ $index ] ) ):
                            $instance = $data[ $index ];
                        endif;
                    endif;
            endswitch;
            if ( isset( $instance ) ):
                if ( is_array( $instance ) && isset( $instance[ 'custom_text' ] ) )
                    // base64 encoding saves us from markup serialization heartburn
                    $instance[ 'custom_text' ] = stripslashes( base64_decode( $instance[ 'custom_text' ] ) );
                return $instance;
            endif;
        endif;
        return FALSE;
    }

    function get_box_map( $id, $objecttype ) {
        if ( !empty( $id ) && !empty( $objecttype ) ):
            if ( ( $data = $this->get_meta( $id, '_intelliwidget_', $objecttype, 'map' ) ) && is_array( $data ) ) 
                return $data;
        endif;
        return array();
    }
    
    /**
     * For customized pages, retrieve the page-specific instance settings for the particular widget
     * being replaced
     */
    function get_settings_data( $id, $widget_id, $objecttype ) {
        // the box map stores meta box => widget id relations in page meta data
        $box_map = $this->get_box_map( $id, $objecttype );
        if ( is_array( $box_map ) ):
            $widget_map = array_flip( $box_map );
            // if two boxes point to the same widget, the second gets clobbered here
            if ( array_key_exists( $widget_id, $widget_map ) ):
                $box_id = $widget_map[ $widget_id ];
                // are there settings for this widget?
                if ( $instance = $this->get_meta( $id, '_intelliwidget_data_', $objecttype, $box_id ) ):
                    return $instance;
                endif;
            endif;
        endif;
        // all failures fall through gracefully
        return FALSE;
    }

    /**
     * Shortcode handler
     */
    function intelliwidget_shortcode( $atts ) {
        global $post, $intelliwidget_post;
        // prevent recursion
        if ( is_object( $post ) && is_object( $intelliwidget_post ) && $post->ID == $intelliwidget_post->ID ) return;
        
        $thispost = $post;
        $save_intelliwidget_post = $intelliwidget_post;
        $iwpost = 0;
        if ( isset( $atts[ 'iwpost' ] ) ):
            $iwpost = 1;
            $thispost = $intelliwidget_post;
        endif;
        // section parameter lets us use page-specific IntelliWidgets in shortcode without all the params
        if ( is_object( $thispost ) && !empty( $atts[ 'section' ] ) ):
            $section = intval( $atts[ 'section' ] );
            $other_post_id = $this->get_meta( $thispost->ID, '_intelliwidget_', 'post', 'widget_page_id' );
            $shortcodePostID = $other_post_id ? $other_post_id : $thispost->ID;
            $atts = $this->get_meta( $shortcodePostID, '_intelliwidget_data_', 'post', $section );
            if ( empty( $atts ) ): 
                if ( $iwpost ) $intelliwidget_post = $save_intelliwidget_post;
                return;
            endif;
        else:
            $section = ++$this->shortcode_id;
            if ( !empty( $atts[ 'custom_text' ] ) ) unset( $atts[ 'custom_text' ] );
            if ( !empty( $atts[ 'text_position' ] ) ) unset( $atts[ 'text_position' ] );
            if ( !empty( $atts[ 'title' ] ) ) $atts[ 'title' ] = strip_tags( $atts[ 'title' ] );
            if ( !empty( $atts[ 'link_text' ] ) ) $atts[ 'link_text' ] = strip_tags( $atts[ 'link_text' ] );
            // backwards compatability: if nav_menu has value, add attr 'content=nav_menu' 
            if ( !empty( $atts[ 'nav_menu' ] ) ) $atts[ 'content' ] = 'nav_menu';
            // backwards compatability: if category has value, change to terms 
            if ( !empty( $atts[ 'category' ] ) ) $atts[ 'terms' ] = $atts[ 'category' ];
        endif;
        $atts = $this->defaults( $atts );
        // FIXME: phase out globals
        $args = array(
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
            'before_widget' => empty( $atts[ 'nav_menu' ] ) ? '<div id="intelliwidget_' . $section . '" class="widget_intelliwidget">' : '',
            'after_widget'  => empty( $atts[ 'nav_menu' ] ) ? '</div>' : '',
        );
        // generate widget from arguments
        $content = $this->build_widget( $args, $atts );
        $intelliwidget_post = $save_intelliwidget_post;
        // return widget content
        return $content;
    }
    
    /**
     * Output the widget using selected template
     */
    function build_widget( $args, $instance, $post_id = NULL ) {
        extract( $args, EXTR_SKIP );
        $instance[ 'query' ] = new IntelliWidgetQuery();
        ob_start();
        // render before widget argument
        echo apply_filters( 'intelliwidget_before_widget', $before_widget, $instance, $args );
        // handle title
        if ( !empty( $instance[ 'title' ] ) && empty( $instance[ 'hide_title' ] ) ):
            echo apply_filters( 'intelliwidget_before_title', $before_title, $instance, $args );
            echo apply_filters( 'intelliwidget_title', $instance[ 'title' ], $instance, $args );
            echo apply_filters( 'intelliwidget_after_title', $after_title, $instance, $args );
        endif;
        // handle custom text above content
        do_action( 'intelliwidget_above_content', $instance, $args );
        // use action hook to render content
        if ( has_action( 'intelliwidget_action_' . $instance[ 'content' ] ) )
            do_action( 'intelliwidget_action_' . $instance[ 'content' ], $instance, $args, $post_id );
        // handle custom text below content
        do_action( 'intelliwidget_below_content', $instance, $args );
        // render after widget argument
        echo apply_filters( 'intelliwidget_after_widget', $after_widget, $instance, $args );
        // retrieve widget content from buffer
        $content = ob_get_contents();
        // close output buffer and flush
        ob_end_clean();
        // skip if no posts retrieved option
        if ( $instance[ 'query' ]->post_count || empty( $instance[ 'hide_no_posts' ] ) )
            return $content;
    }
    
    /**
     * Ensure that "post-thumbnails" support is available for those themes that don't register it.
     */
    public function ensure_post_thumbnails_support () {
        if ( ! current_theme_supports( 'post-thumbnails' ) ) { add_theme_support( 'post-thumbnails' ); }
    } // End ensure_post_thumbnails_support()

    public function post_link( $permalink, $post, $leavetitle ) {
        global $intelliwidget_post;
        // do not use filter on intelliwidgets
        if ( is_object( $intelliwidget_post ) && $post->ID == $intelliwidget_post->ID ) return $permalink;
        if ( ( $use = get_post_meta( $post->ID, 'intelliwidget_all_links', TRUE ) ) 
            && ( $val = get_post_meta( $post->ID, 'intelliwidget_external_url', TRUE ) ) )
            return $val;
        return $permalink;
    }
    
    public function post_title( $title, $id = NULL ) {
        global $intelliwidget_post;
        // do not use filter on intelliwidgets
        if ( empty( $id ) || ( is_object( $intelliwidget_post ) && $id == $intelliwidget_post->ID ) ) 
            return $title;
	    if ( ( $post = get_post( $id ) )
            && ( $use = get_post_meta( $post->ID, 'intelliwidget_all_titles', TRUE ) ) 
            && ( $val = get_post_meta( $post->ID, 'intelliwidget_alt_title', TRUE ) ) )
            return $val;
        return $title;
    }
    /**
     * Widget Defaults
     * This will utilize an options form in a future release for customization
     */
    public function defaults( $instance = array() ) {
        //if ( empty( $instance ) ) $instance = array();
        $defaults = apply_filters( 'intelliwidget_defaults', array(
        
            // these apply to all intelliwidgets
            'content'           => 'post_list', // this is the main control, determines hook to use
            'nav_menu'          => '',          // built-in extension, uses wordpress menu instead of post_list
            'title'             => '',
            'link_title'        => 0,
            'classes'           => '',
            'container_id'      => '',
            'custom_text'       => '',
            'text_position'     => '',
            'filter'            => 0,
            'hide_if_empty'     => 0,           // applies to site-wide intelliwidgets
            'replace_widget'    => 'none',      // applies to post-specific intelliwidgets
            'nocopy'            => 0,           // applies to post-specific intelliwidgets
            'hide_title'        => 0,
            
            // these apply to post_list intelliwidgets
            'post_types'        => array( 'page', 'post' ),
            'template'          => 'menu',
            'page'              => array(),     // misnomer, stores any post_type, not just pages
            //'category'          => -1,        // REMOVED: legacy value, convert to tax_id
            'terms'             => array(),
            'items'             => 5,
            'sortby'            => 'title',
            'sortorder'         => 'ASC',
            'skip_expired'      => 0,
            'skip_post'         => 0,
            'future_only'       => 0,
            'active_only'       => 0,
            'include_private'   => 0,
            'allterms'          => 0,
            'same_term'         => 0,
            'hide_no_posts'     => 0,
            'same_tax'          => 0,
            //'querystr'          => '',        // not using for now
            
            // these apply to post_list items
            'length'            => 15,
            'link_text'         => __( 'Read More', 'intelliwidget' ),
            'allowed_tags'      => '',
            'imagealign'        => 'none',
            'image_size'        => 'none',
            'no_img_links'      => 0,
            'keep_title'        => 0,
            
            // these apply to taxonomy menus
            'hide_empty'        => 1,
            'show_count'        => 0,
            'current_only'      => 0,
            'show_descr'        => 0,
            'taxonomy'          => '',
            'hierarchical'      => 1,
            'menu_location'     => '',
            
        ) );
        // backwards compatibility: add content=nav_menu if nav_menu param set
        if ( empty( $instance[ 'content' ] ) && !empty( $instance[ 'nav_menu' ] ) && '' != ( $instance[ 'nav_menu' ] ) ) 
            $instance[ 'content' ] = 'nav_menu';
        // convert shortcode and legacy scalar attributes to arrays
        foreach ( array( 'page', 'post_type', 'terms' ) as $att )
            if ( !empty( $instance[ $att ] ) && is_scalar( $instance[ $att ] ) )
                $instance[ $att ] = preg_split( "/,\s*/", $instance[ $att ] );
        // standard WP function for merging argument lists
        $merged = wp_parse_args( $instance, $defaults );
        return $merged;
    }
    
    /**
     * fires whenever terms are updated to update term_taxonomy children cache
     */
    function clean_term_cache( $ids, $taxonomy ) {
        delete_option( "{$taxonomy}_iw_children" );
        $this->_get_term_hierarchy( $taxonomy );
    }
    
    /**
     * variation of the core taxonomy function that caches and returns term_taxonomy_ids instead of term_ids
     */
    function _get_term_hierarchy( $taxonomy ) {
	    if ( !is_taxonomy_hierarchical( $taxonomy ) )
		    return array();
	    $children   = get_option( "{$taxonomy}_iw_children" );
	    if ( !is_array( $children ) ):
            $children   = array();
            // using array_reduce this way performs like array_map but returns assoc array
            $terms      = array_reduce( 
                get_terms( 
                    $taxonomy, 
                    array( 
                        'get'       => 'all', 
                        'orderby'   => 'id', 
                        'fields'    => 'all' 
                    ) 
                ), 
                array( $this, '_map_terms' ), 
                array() 
            );
            foreach ( $terms as $termid => $term ):
                if ( $term[ 'parent' ] > 0 && isset( $terms[ $term[ 'parent' ] ] ) ):
                    $children[ $terms[ $term[ 'parent' ] ][ 'ttid' ] ][] = $term[ 'ttid' ];
                endif;
            endforeach;
            update_option( "{$taxonomy}_iw_children", $children );
        endif;
	    return $children;
    }
    
    /**
     * return lookup array 
     */
    function _map_terms( $res, $el ) {
        $res[ $el->term_id ] = array( 
            'ttid'      => $el->term_taxonomy_id, 
            'parent'    => $el->parent 
        );
        return $res;
    }

    /**
     * recurse terms array to get children of given term
     */
    function get_term_children( $ttid, &$terms ) {
        $ttid = intval( $ttid );
        if ( isset( $this->term_children[ $ttid ] ) ) return $this->term_children[ $ttid ];
        if ( !isset( $terms[ $ttid ] ) )
            return array();
        $children = $terms[ $ttid ];
        foreach ( (array) $terms[ $ttid ] as $child ):
            if ( $ttid == $child )
                continue;
            if ( isset( $terms[ $child ] ) )
                $children = array_merge( $children, $this->get_term_children( $child, $terms ) );
        endforeach;
        $this->term_children[ $ttid ] = $children;
        return $children;
    }
    /**
     * Front-end css
     */
    function enqueue_styles() {
        wp_enqueue_style( 'intelliwidget', trailingslashit( INTELLIWIDGET_URL ) . 'templates/intelliwidget.css', array(), INTELLIWIDGET_VERSION );
        $file   = 'intelliwidget/intelliwidget.css';
        if ( is_child_theme() && file_exists( trailingslashit( get_template_directory() ) . $file ) )
            wp_enqueue_style( 'intelliwidget-custom-parent', trailingslashit( get_template_directory_uri() ) . $file, array(), INTELLIWIDGET_VERSION );
        if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $file ) )
            wp_enqueue_style( 'intelliwidget-custom', trailingslashit( get_stylesheet_directory_uri() ) . $file, array(), INTELLIWIDGET_VERSION );
    }
}
defined( 'LF' ) || define( 'LF', "\n" );
define( 'INTELLIWIDGET_VERSION', '2.3.9.2' );
defined( 'LILAEAMEDIA_URL' ) || define( 'LILAEAMEDIA_URL', 'http://www.lilaeamedia.com' );
define( 'INTELLIWIDGET_DIR', dirname( __FILE__ ) );
define( 'INTELLIWIDGET_MAX_MENU_POSTS', 250 );
define( 'INTELLIWIDGET_URL', plugin_dir_url( __FILE__ ) );

include_once( INTELLIWIDGET_DIR . '/includes/template-tags.php' );
new IntelliWidget();
