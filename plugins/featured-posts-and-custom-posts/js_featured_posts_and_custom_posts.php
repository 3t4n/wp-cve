<?php /**
    
    Plugin Name:   Featured Posts and Custom Posts
    Plugin URI:    http://www.reactivedevelopment.net/snippets/featured-posts-custom-posts
    Description:   Allows the user to feature posts and custom posts. When a post is featured it gets the post metta _jsFeaturedPost
    Version:       2.0
 
 *
 
    Author:        Jeremy Selph, Reactive Development LLC
    Author URI:    http://www.reactivedevelopment.net/

    License:       GNU General Public License, v3 (or newer)
    License URI:   http://www.gnu.org/licenses/gpl-3.0.html

 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 

    Note: go here http://www.reactivedevelopment.net/snippets/featured-posts-custom-posts for documentation 
          or for paid support go here http://www.reactivedevelopment.net/contact/

    *
    
    Activation Instructions
    
    1. Download the feature-posts-and-custom-posts.zip file to your computer.
    2. Unzip the file.
    3. Upload the feature-posts-and-custom-posts folder to your /wp-content/plugins/ directory.
    4. Activate the plugin through the Plugins menu in WordPress.

    *

    Change log
    
    01. updated code and tested new version on wordpress 4.1.1                                                      ver 2.0 | 02/25/2015
    02. added extra sanitation to js_featured_posts_link_add_ajax_call_to_wp()                                      ver 2.0 | 02/25/2015
    03. added js_featured_is_post_featured( userID[int] ) function                                                  ver 2.0 | 02/25/2015
    04. added short cut is_post_featured( userID[int] ) function                                                    ver 2.0 | 02/25/2015
    05. added js_featured_return_all_featured() function that returns an array of featured posts                    ver 2.0 | 02/25/2015
    06. added widget by extending widget class with featuredPostWidget                                              ver 2.0 | 02/25/2015
    07. added js_featured_register_widgets() function to init out featuredPostWidget                                ver 2.0 | 02/25/2015
    08. added js_featured_add_post_class() class function that addes the jsFeatured class if the post is featured   ver 2.0 | 02/25/2015
    09. added [jsFeaturedPosts posts_per_page="1" wrap_before="<ul>" wrap_after="</ul>" link_before="<li>"          ver 2.0 | 02/25/2015
        link_after="</li>" link_atts="rel='bookmark'" link_title="Link to"] shortcode

    *

    Image Credits
    Star image is from: http://www.iconfinder.com/icondetails/9662/24/bookmark_star_icon and http://www.iconfinder.com/icondetails/9604/24/star_icon

**/

    /**
     
    when we activate the plugin do this

     *
     * @package Featured Posts and Custom Posts
     * @subpackage install_js_featured_posts
     * @since 1.0
    */

    function install_js_featured_posts() {          
        
        $currentJSoption                    = unserialize( get_option( 'jsFeaturedPosts' ) );
        if ( empty( $currentJSoption ) ){
            
            $js_featured_posts_option       = array( 'installed' => 'yes', 'jsFeaturedPosts' => array() );            
            add_option( 'jsFeaturedPosts', serialize( $js_featured_posts_option ), '', 'yes' ); 

        } 
            
    } register_activation_hook( __FILE__,'install_js_featured_posts' );

    /**
     
    add fetured colum to posts

     *
     * @package Featured Posts and Custom Posts
     * @subpackage add_js_featured_colum
     * @since 1.0
    */

    function add_js_featured_colum( $columns ){

        $columns[ 'featured_js_posts' ]     = __( 'Featured' ); return $columns;

    } add_filter( 'manage_posts_columns', 'add_js_featured_colum', 2 );

    /**
     
    add the content to our new colum

     *
     * @package Featured Posts and Custom Posts
     * @subpackage add_js_featured_post_column_content
     * @since 1.0
    */

    function add_js_featured_post_column_content( $col, $id ){
        
        if ( $col == 'featured_js_posts' ){

            $class                          = '';
            $jsFeaturedPost                 = get_post_meta( $id, '_jsFeaturedPost', true );
            if ( !empty( $jsFeaturedPost ) ){ 

                $class                      = ' selected'; 

            } echo '<a id="postFeatured_' . $id . '" class="featured_posts_star' . $class . '"></a>';

        }
        
    } add_action( 'manage_posts_custom_column', 'add_js_featured_post_column_content', 10, 2 );

    /**
     
    get of this this themes cpts and loop through them to create the correct action and filters

     *
     * @package Featured Posts and Custom Posts
     * @subpackage js_featured_posts_get_and_loop_through_post_types
     * @version 2.0 ??
     * @since 1.0
    */

    function js_featured_posts_get_and_loop_through_post_types(){
        
        /* ** 

            need to update this code to fix the double star issue found sometimes. 02/25/2015 JS

        ** */

        $post_types                         = get_post_types( array( 'public' => true, '_builtin' => false ), 'names' );
        foreach ( $post_types as $post_type ) {            
            
            add_filter( 'manage_' . $post_type . '_posts_columns',          'add_js_featured_colum',                2       );
            add_action( 'manage_' . $post_type . '_posts_custom_column',    'add_js_featured_post_column_content',  10, 2   );

        }
        
    } add_action( 'admin_init', 'js_featured_posts_get_and_loop_through_post_types' );

    /**
     
    chnage the width of our colum

     *
     * @package Featured Posts and Custom Posts
     * @subpackage js_featured_posts_colum_width
     * @since 1.0
    */

    function js_featured_posts_colum_width(){
         
        $imgSrc                             = plugins_url( 'img/star.png' , __FILE__ ); ?>
        <style>
            #featured_js_posts, .column-featured_js_posts{ width:100px; text-align: center !important; }
            .featured_posts_star{ display:block; height:24px; width:24px; margin:8px auto 0 auto; border:none; 
                background: transparent url(<?php echo $imgSrc; ?>) 0 -24px no-repeat; cursor:pointer; }
            .featured_posts_star.selected, .featured_posts_star:active{ background-position:0 0; }
        </style><?php

    } add_action( 'admin_head','js_featured_posts_colum_width' );

    /**
     
    add jquery function to admin head to save

     *
     * @package Featured Posts and Custom Posts
     * @subpackage js_featured_posts_add_jquery_to_head
     * @since 1.0
    */

    function js_featured_posts_add_jquery_to_head(){
        
        if ( current_user_can( 'administrator' ) ){ ?>                    
            
            <script type="text/javascript" language="javascript">                
                
                jQuery( document ).ready( function(){                
                    
                    // when the checkbox is clicked save the meta option for this post
                    jQuery( ".featured_posts_star" ).click( function() {
                        
                        var selected        = "yes";
                        if ( jQuery( this ).hasClass( "selected" ) ){ 
                            
                            jQuery( this ).removeClass( "selected" );
                            selected        = "no"; 
                        
                        } else { jQuery( this ).addClass( "selected" ); }                        
                        
                        // get id
                        var tempID          = jQuery( this ).attr( "id" );
                            tempID          = tempID.split( "_" );            
                        
                        jQuery.post( ajaxurl, "action=jsfeatured_posts&post=" + tempID[1] + "&jsFeaturedPost=" + selected ); 
                            
                    });

                });
            
            </script> <?php

        }

    } add_action( 'admin_head', 'js_featured_posts_add_jquery_to_head' );

    /**
     
    add ajax call to wp in order to save the remove delete post link

     *
     * @package Featured Posts and Custom Posts
     * @subpackage js_featured_posts_link_add_ajax_call_to_wp
     * @version 2.0
     * @since 1.0
    */

    function js_featured_posts_link_add_ajax_call_to_wp(){      
        
        /*  found this example in the dont-break-the-code-example */            
        $jsFeaturedPost                     = sanitize_text_field( $_POST[ 'jsFeaturedPost' ] );
        $currentJSPostID                    = intval( $_POST[ 'post' ] );
        if( $currentJSPostID > 0 && $jsFeaturedPost !== NULL ) {
            
            if ( $jsFeaturedPost == 'no' ){ delete_post_meta( $currentJSPostID, '_jsFeaturedPost' ); }
            else { add_post_meta( $currentJSPostID, '_jsFeaturedPost', 'yes' ); }

        } exit;

    } add_action( 'wp_ajax_jsfeatured_posts', 'js_featured_posts_link_add_ajax_call_to_wp' );

    /**
     
    return array of featured posts

     *
     * @package Featured Posts and Custom Posts
     * @subpackage js_featured_return_all_featured
     * @since 2.0
    */

    function js_featured_return_all_featured( $pPerPage=-1 ){

        $featPosts                          = get_posts( 

            array( 'post_type' => 'any', 'posts_per_page' => $pPerPage, 'meta_key' => '_jsFeaturedPost', 'meta_value' => 'yes' ) 

        ); if ( !empty( $featPosts ) ){ return $featPosts; } return false;

    }

    /**
     
    js_featured_is_post_featured function

     *
     * @package Featured Posts and Custom Posts
     * @subpackage js_featured_is_post_featured
     * @since 2.0
    */

    function js_featured_is_post_featured( $postID=0 ){

        $thisID                             = intval( $postID );
        if ( $thisID == 0 ){ global $post; $thisID = $post->ID; }
        if ( $thisID > 0 ){

            $featPosts                      = js_featured_return_all_featured();
            if( !empty( $featPosts ) ){
                foreach( $featPosts as $fp ){

                    if ( $thisID == $fp->ID ){ return true; }

                }
            }

        } return false;

    }

    /**
     
    is post featured function

     *
     * @package Featured Posts and Custom Posts
     * @subpackage is_post_featured
     * @since 2.0
    */

    if( !function_exists( 'is_post_featured' ) ){

        function is_post_featured( $postID=0 ){ return js_featured_is_post_featured( $postID ); }

    }

    /**
     
    featured posts sidebar widget

     *
     * @package Featured Posts and Custom Posts
     * @subpackage featuredPostWidget EXTEND WP_Widget
     * @since 2.0
     * @comment major cudos to wpbeginner.com http://www.wpbeginner.com/wp-tutorials/how-to-create-a-custom-wordpress-widget/
    */

    class featuredPostWidget extends WP_Widget {

        function __construct() {
        
            parent::__construct(
            
                // Base ID of your widget
                'featuredPost_widget', 

                // Widget name will appear in UI
                __( 'Featured Posts Widget' ),

                // Widget description
                array( 'description'        => __( 'UL list of featured post links.' ), ) 

            );

        }

        public function widget( $args, $instance ) {

            $title                          = apply_filters( 'widget_title', $instance['title'] );
            $featPosts                      = js_featured_return_all_featured();

            if( !empty( $featPosts ) ){

                echo $args['before_widget'];
                
                    if ( !empty( $title ) ){ echo $args['before_title'] . $title . $args['after_title']; } ?>

                        <ul>
                    <?php   foreach( $featPosts as $fp ){ ?>
                            <li>
                                <a href="<?php echo esc_url( get_permalink( $fp->ID ) ); ?>" rel="bookmark" title="<?php _e( 'Link to' ); ?> <?php 
                                    echo get_the_title( $fp->ID ); ?>"><?php echo get_the_title( $fp->ID ); ?></a>
                            </li>
                    <?php   } ?>
                        </ul>

                    <?php
                    
                echo $args['after_widget'];

            }
        
        }

        public function form( $instance ) {

            if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ]; }
            else { $title = __( 'Featured Posts' ); } ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p> <?php 

        }

        public function update( $new_instance, $old_instance ) {
        
            $instance = array();
            $instance[ 'title' ] = ( !empty( $new_instance[ 'title' ] ) ) ? strip_tags( $new_instance[ 'title' ] ) : '';
            return $instance;

        }
    
    }

    /**
     
    init featured posts sidebar widget

     *
     * @package Featured Posts and Custom Posts
     * @subpackage js_featured_register_widgets
     * @since 2.0
    */

    function js_featured_register_widgets(){

        register_widget( 'featuredPostWidget' );

    } add_action( 'widgets_init', 'js_featured_register_widgets' );

    /**
     
    add shortcode for featured posts

     *
     * @package Featured Posts and Custom Posts
     * @subpackage js_featured_shortcode_display
     * @since 2.0
    */

    function js_featured_shortcode_display( $atts ){
        
        /* [jsFeaturedPosts posts_per_page="1" wrap_before="<ul>" wrap_after="</ul>" link_before="<li>" link_after="</li>" link_atts="rel='bookmark'" link_title="Link to"] */
        extract( shortcode_atts( 

            array( 

                'posts_per_page'    => -1,
                'wrap_before'       => '<ul>',
                'wrap_after'        => '</ul>',
                'link_before'       => '<li>',
                'link_after'        => '</li>',
                'link_atts'         => 'rel="bookmark"',
                'link_title'        => __( 'Link to' )

            ), 

        $atts ) );

        $send                           = '';
        $featPosts                      = js_featured_return_all_featured( $posts_per_page );
        if( !empty( $featPosts ) ){

            $send .= $wrap_before;
            
            foreach( $featPosts as $fp ){

                $send .= $link_before;
                $send .= '<a href="' . esc_url( get_permalink( $fp->ID ) ) . '" ' . $link_atts . ' title="' . $link_title . ' ' . get_the_title( $fp->ID ) . '">' . get_the_title( $fp->ID ) . '</a>';
                $send .= $link_after;

            }
            
            $send .= $wrap_after;

        } return $send;
    
    }

    /**
     
    init shortcode for featured posts

     *
     * @package Featured Posts and Custom Posts
     * @subpackage js_featured_shortcode_init
     * @since 2.0
    */

    function js_featured_shortcode_init(){

        add_shortcode( 'jsFeaturedPosts', 'js_featured_shortcode_display' );

    } add_action( 'init', 'js_featured_shortcode_init');

    /**
     
    add class to featured posts in archive view

     *
     * @package Featured Posts and Custom Posts
     * @subpackage js_featured_add_post_class
     * @since 2.0
    */

    function js_featured_add_post_class( $classes ) {
        
        global $post;
        if( js_featured_is_post_featured( $post->ID ) ) {

            $classes[] = 'jsFeatured';

        } return $classes;

    } add_filter( 'post_class', 'js_featured_add_post_class' );

    /**
     
    when we deactivate the plugin do this

     *
     * @package Featured Posts and Custom Posts
     * @subpackage remove_js_featured_posts
     * @since 1.0
    */

    function remove_js_featured_posts() {

        delete_option( 'jsFeaturedPosts' );

    } register_deactivation_hook( __FILE__, 'remove_js_featured_posts' );

    /**
    
    End code
    
    */

?>