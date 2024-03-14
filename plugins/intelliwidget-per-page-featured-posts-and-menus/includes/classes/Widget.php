<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * class-widget-intelliwidget.php - IntelliWidget Widget Class
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
class IntelliWidgetWidget extends WP_Widget {

    var $admin;
    var $recursions = 0;
    var $menu_map;
    /**
     * Constructor
     */
    function __construct() {
        $widget_ops          = array( 'description' => __( 'Menus, Featured Posts, HTML and more, customized per page or site-wide.', 'intelliwidget' ) );
        $control_ops         = array( 'width' => 400, 'height' => 350 );
        if ( is_admin() )
            $this->admin = new IntelliWidgetWidgetAdmin();
        // only add hooks once in case widget init fires more than once
        if ( ! has_action( 'intelliwidget_action_post_list' ) ):
            // default content actions
            add_action( 'intelliwidget_action_post_list',   array( $this, 'action_post_list' ),             10, 3 );
            add_action( 'intelliwidget_action_nav_menu',    array( $this, 'action_nav_menu' ),              10, 3 );
            add_action( 'intelliwidget_action_tax_menu',    array( $this, 'action_taxonomy_menu' ),         10, 3 );
            add_action( 'intelliwidget_above_content',      array( $this, 'action_addltext_above' ),        10, 3 );
            add_action( 'intelliwidget_below_content',      array( $this, 'action_addltext_below' ),        10, 3 );
            add_filter( 'intelliwidget_before_widget',      array( $this, 'filter_before_widget' ),         10, 3 );
            add_filter( 'intelliwidget_title',              array( $this, 'filter_title' ),                 10, 3 );
            add_filter( 'intelliwidget_custom_text',        array( $this, 'filter_custom_text' ),           10, 3 );
            add_filter( 'intelliwidget_classes',            array( $this, 'filter_classes' ),               10, 3 );
            add_filter( 'intelliwidget_content',            array( $this, 'filter_content' ),               10, 3 );
            add_filter( 'intelliwidget_trim_excerpt',       array( $this, 'filter_trim_excerpt' ),          10, 3 );
            if ( !defined( 'INTELLIWIDGET_PRO_VERSION' ) || INTELLIWIDGET_PRO_VERSION >= '2.0.0' )
                add_filter( 'theme_mod_nav_menu_locations', array( $this, 'theme_mod_nav_menu_locations' ), 10 );
        endif;
        parent::__construct( 'intelliwidget', $this->iw()->pluginName, $widget_ops, $control_ops );
    }
    
    function iw() {
        return IntelliWidget::$instance;
    }
    /**
     * intelliwidget_extension_settings filter allows widget instance to be replaced by extensions
     */
    function widget( $args, $instance ) {
        $instance = $this->iw()->defaults( $instance ); 
        $instance = apply_filters( 'intelliwidget_extension_settings', $instance, $args );
        // should we hide?
        if ( !empty( $instance[ 'hide_if_empty' ] ) )
            return;
        echo $this->iw()->build_widget( $args, $instance );
    }
    
    /**
     * Widget Update method
     */
    function update( $new_instance, $old_instance ) {
        return $this->admin->update( $new_instance, $old_instance );
    }
    /**
     * Output Widget form
     */
    function form( $instance ) {
        if ( is_object( $this->admin ) ) $this->admin->render_form( $this, $instance );
    }
                
    function filter_custom_text( $custom_text, $instance = array(), $args = array() ) {
        $custom_text = apply_filters( 'widget_text', $custom_text, $instance );
        if ( !empty( $instance[ 'filter' ] ) )
            $custom_text = wpautop( $custom_text );
        return '<div class="textwidget">' . $custom_text . '</div>';
    }
    
    function filter_before_widget( $before_widget, $instance = array(), $args = array() ) {
        if ( !empty( $instance[ 'container_id' ] ) ):
            $before_widget = preg_replace( '/id=".+?"/', 'id="' . $instance[ 'container_id' ] . '"', $before_widget );
        endif;
        $before_widget = preg_replace( '/class="/', 'class="' 
            . apply_filters( 'intelliwidget_classes', $instance[ 'classes' ] ) . ' ', $before_widget );
        return $before_widget;
    }
    
    function filter_classes( $classes ) {
        return preg_replace( "/[, ;]+/", ' ', $classes );
    }
        
    function filter_title( $title, $instance = array(), $args = array() ) {
        if ( !empty( $title ) ) {
            if ( !empty( $instance[ 'link_title' ] ) ) {
                return get_the_intelliwidget_taxonomy_link( $title, $instance );
            } else {
                return apply_filters( 'widget_title', $title );
            }
        }
        return $title;
    }
    
    function filter_content( $content ) {
        if ( strpos( $content, '<!--nextpage-->' ) ) {
            $content = preg_replace( "#\s*<!\-\-nextpage\-\->.*#s", '', $content );
        }
        // remove intelliwidget shortcode to stop endless recursion
        if ( $this->recursions > 2 ) return $content;
        $this->recursions++;
        // otherwise, parse shortcodes
        $content = do_shortcode( $content ); // preg_replace( "#\[intelliwidget.*?\]#s", '', $content ) );
        $this->recursions--;
        return $content;
    }
            
    /**
     * Trim the content to a set number of words.
     */
    function filter_trim_excerpt( $text, $length, $tags = '' ) {
        if ( is_array( $length ) ) $length = isset( $length[ 'length' ] ) ? $length[ 'length' ] : 15; // backward compat
        $moretag = '<!--more-->';
        $length = empty( $length ) ? 15 : intval( $length );
        $allowed_tags = '';
        if ( !empty( $tags ) ):
            $tags = explode( ',', $tags );
            foreach ( $tags as $tag ):
                $allowed_tags .= '<' . trim( $tag ) . '>';
            endforeach;          
        endif;
        //$text       = strip_shortcodes( $text );
        $text       = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $text );
        $textarr    = explode( $moretag, $text, 2 );
        $more       = ( count( $textarr ) > 1 );
        $text       = $textarr[ 0 ];
        
        //$text     = str_replace( ']]>', ']]&gt;', $text );
        $text       = strip_tags( $text, $allowed_tags );
        if ( empty( $allowed_tags ) && !$more ):
            $words  = preg_split( '/[\r\n\t ]+/', $text, $length + 1 );
            if ( count( $words ) > $length ):
                array_pop( $words );
                array_push( $words, '...' );
                $text = implode( ' ', $words );
            endif;
        elseif ( $allowed_tags ):
            $text = $this->get_words_html( $text, $length, $more );
        endif; 
        return $text;
    }

    function action_addltext_above( $instance, $args ) {
        if ( ( 'above' == $instance[ 'text_position' ] || 'only' == $instance[ 'text_position' ] ) ):
            echo apply_filters( 'intelliwidget_custom_text', $instance[ 'custom_text' ], $instance, $args );
        endif;
    }

    function action_post_list( $instance, $args = array(), $post_id = NULL ) {
        // skip to after widget content if this is custom text only
        if ( 'only' == $instance[ 'text_position' ] ) return;
        if ( !empty( $instance[ 'template' ] ) ):
            $instance[ 'query' ]->get_posts( $instance );
            // backward compatibility
            $selected = $instance[ 'query' ];
            // action hook for IntelliWidget Pro or third party templates
            if ( has_action( 'intelliwidget_action_' . $instance[ 'template' ] ) ):
                do_action( 'intelliwidget_action_' . $instance[ 'template' ] , $instance );
            // default and custom templates
            elseif ( $template = $this->get_template( $instance[ 'template' ] ) ):
                include ( $template );
            endif;
        endif;
    }

    function action_addltext_below( $instance, $args ) {
        if ( 'below' == $instance[ 'text_position' ] ):
            echo apply_filters( 'intelliwidget_custom_text', 
                $instance[ 'custom_text' ], $instance, $args );
        endif;
    }
    
    function action_nav_menu( $instance, $args = array(), $post_id = NULL ) {
        // skip to after widget content if this is custom text only
        if ( 'only' == $instance[ 'text_position' ] ) return;
        if ( !empty( $instance[ 'nav_menu' ] ) ):
            if ( '-1' == $instance[ 'nav_menu' ] ):
                wp_page_menu( array( 
                    'show_home' => TRUE, 
                    'menu_class' => apply_filters( 'intelliwidget_menu_classes', 
                        'iw-menu', $instance ),
                    )
                );
            else:
                wp_nav_menu( array( 
                    'fallback_cb'   => '', 
                    'menu'          => $instance[ 'nav_menu' ], 
                    'menu_class'    => apply_filters( 'intelliwidget_menu_classes', 
                        'iw-menu', $instance ),
                    )
                );
            endif;
        endif;
    }

    function action_taxonomy_menu( $instance, $args = array(), $post_id = NULL ) {
        // skip to after widget content if this is custom text only
        if ( isset( $instance[ 'text_position' ] ) && 'only' == $instance[ 'text_position' ] ) return;
        if ( !empty( $instance[ 'taxonomy' ] ) && taxonomy_exists( $instance[ 'taxonomy' ] ) ):
            $current_term_id = NULL;
            $current_ancestors = array();
            $queried_object = get_queried_object();
            if ( is_object( $queried_object ) 
                && isset( $queried_object->term_taxonomy_id ) ):
                $current_term_id      = $queried_object->term_id;
                $current_ancestors    = get_ancestors( $queried_object->term_id, $queried_object->taxonomy );
            endif;

            if ( is_singular() ):
                global $post;
                $terms = wp_get_object_terms( $post->ID, $instance[ 'taxonomy' ], array( 'orderby' => 'parent' ) );
                if ( $terms ):
                    $current_term   = end( $terms );
                    $current_term_id = $current_term->term_id;
                    $current_ancestors = get_ancestors( $current_term_id, $current_term->taxonomy );
                endif;
            endif;

            echo '<ul class="intelliwidget-taxonomy-menu">';

            wp_list_categories( apply_filters( 'intelliwidget_tax_menu_args', array( 
                'walker'            => new IntelliWidgetTaxonomyWalker,
                'title_li'          => '',
                'pad_counts'        => 1,
                'show_option_none'  => __( 'None', 'intelliwidget' ),
                'current_term_id'   => $current_term_id,
                'current_ancestors' => $current_ancestors,
                'taxonomy'          => $instance[ 'taxonomy' ],
                'hide_empty'        => $instance[ 'hide_empty' ],
                'current_only'      => ( isset( $instance[ 'current_only' ] ) ? $instance[ 'current_only' ]  : 0 ),
                'show_count'        => ( isset( $instance[ 'show_count' ] )     && $instance[ 'show_count' ] )              ? 1         : 0, 
                'hierarchical'      => ( isset( $instance[ 'hierarchical' ] )   && $instance[ 'hierarchical' ] )            ? TRUE      : FALSE, 
                'show_descr'        => ( isset( $instance[ 'show_descr' ] )     && $instance[ 'show_descr' ] )              ? 1         : 0,
                'menu_order'        => ( isset( $instance[ 'sortby' ] )         && 'menu_order' == $instance[ 'sortby' ] )  ? 'asc'     : FALSE,
                'orderby'           => ( isset( $instance[ 'sortby' ] )         && 'title' == $instance[ 'sortby' ] )       ? 'title'   : NULL,
            ) ) );

            echo '</ul>';
        endif;
    }

    function get_words_html( $text, $length, $more = FALSE ) {
        $opentags   = array();
        $excerpt    = '';
        $text       = preg_replace( '/<(br|hr)[ \/]*>/', "<$1/>", $text );
        preg_match_all( '/(<[^>]+?>)?([^<]*)/', $text, $elements );
        if ( !empty( $elements[ 2 ] ) ):
            $count = 0;
            foreach( $elements[ 2 ] as $string ):
                $html = array_shift( $elements[ 1 ] );
                if ( preg_match( '/<(\w+)[^\/]*>/', $html, $matches ) ):
                    $opentags[] = $matches[ 1 ];
                elseif ( preg_match( '/<\/(\w+)/', $html, $matches ) ):
                    $close = array_pop( $opentags );
                endif;
                $excerpt .= $html;
                $words = preg_split( '/[\r\n\t ]+/', $string );
                foreach ( $words as $word ):
                    if ( empty( $word ) ) continue;
                    $count++;
                    if ( $count <= $length || $more ):
                        $excerpt .= $word . ' ';
                    else:
                        $excerpt .= ' ...';
                        break;
                    endif;
                endforeach;
                if ( $count > $length && !$more ) break;
            endforeach;
            while ( count( $opentags ) ):
                $close = array_pop( $opentags );
                $excerpt .= '</' . $close . '>';
            endwhile;
        endif;
        return $excerpt;
    }
    
    /**
     * Retrieve a template file from either the theme or the plugin directory.
     * First, check if an action hook exists for this template value and execute
     * Second check if file exists. If no file exists, return FALSE
     */
    function get_template( $template = NULL ) {
        if ( NULL == $template ) return FALSE;
        $themeFile  = get_stylesheet_directory() . '/intelliwidget/' . $template . '.php';
        $parentFile = get_template_directory() . '/intelliwidget/' . $template . '.php';
        $pluginFile = INTELLIWIDGET_DIR . '/templates/' . $template . '.php';
        if ( file_exists( $themeFile ) ) return $themeFile;
        if ( file_exists( $parentFile ) ) return $parentFile;
        if ( file_exists( $pluginFile ) ) return $pluginFile;
        return FALSE;
    }
  
    function theme_mod_nav_menu_locations( $mods ) {
        if ( !is_admin() && is_array( $mods ) ):
            if ( !isset( $this->menu_map ) ):
                $this->menu_map = array();
                foreach ( $mods as $location => $menu ):
                    $args = array( 'widget_id' => 'nav_menu_location-' . $location );
                    $instance = apply_filters( 'intelliwidget_extension_settings', array(), $args );
                    if ( !empty( $instance ) 
                        && !empty( $instance[ 'menu_location' ] ) 
                            && $instance[ 'menu_location' ] == $location 
                                && !empty( $instance[ 'nav_menu' ] ) ):
                        $this->menu_map[ $location ] = $instance[ 'nav_menu' ];
                    endif;
                endforeach;
            endif;
            $mods = array_merge( $mods, $this->menu_map );
        endif;
        return $mods;
    }
}


