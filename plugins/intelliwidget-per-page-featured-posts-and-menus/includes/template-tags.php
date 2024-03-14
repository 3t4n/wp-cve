<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * template-tags.php - Global functions for the IntelliWidget plugin.
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */

    /**
     * Return the post ID
     *
     * @return <integer> post ID
     */
    function get_the_intelliwidget_ID() {
        global $intelliwidget_post;
        return $intelliwidget_post->ID;
    }

    /**
     * Display the post ID
     */
    function the_intelliwidget_ID() {
        echo get_the_intelliwidget_ID();
    }

    /**
     * Return the featured post image with link to the full image.
     *
     * @global <object> $intelliwidget_post
     * @return <string> image link if exists, <boolean> FALSE if none
     */
    function get_the_intelliwidget_image( $link = TRUE, $size = 'thumbnail' ) {
        // FIXME: phase out globals
        global $intelliwidget_post;
        // backward compatibility for custom templates ( global profile has been deprecated )
        if ( $size != 'none' && has_intelliwidget_image() ) :
            return apply_filters( 'intelliwidget_image', 
                ( $link ? 
                '<a title="' . esc_attr( strip_tags( get_the_intelliwidget_title() ) )
                . '" href="' . get_the_intelliwidget_url() . '">' : '' )
                . get_the_intelliwidget_thumbnail( $size )
                . ( $link ? '</a>' : '' ) );
        endif;
        return FALSE;
    }

    function get_the_intelliwidget_thumbnail( $size = 'thumbnail', $classes = array() ) {
        global $intelliwidget_post;
        $classes[] = 'intelliwidget-image-'. $size;
        return get_the_post_thumbnail(
            $intelliwidget_post->ID, 
            $size, 
            array(
                'class' => implode( ' ', $classes ),
            )
        );
    }
    
    function the_intelliwidget_thumbnail( $size = 'thumbnail', $classes = array() ) {
        echo get_the_intelliwidget_thumbnail( $size, $classes );
    }
    
    function get_the_intelliwidget_thumbnail_src( $size = 'thumbnail' ) {
        global $intelliwidget_post;
        if ( ( $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $intelliwidget_post->ID ), $size ) )
            && current( $thumb ) )
            return current( $thumb );
        return FALSE;    
    }
    /**
     * Check if the post has a featured image.
     * 
     * @global <object> $intelliwidget_post
     * @return <boolean>
     */
    function has_intelliwidget_image() {
        // FIXME: phase out globals
        global $intelliwidget_post;
        return ( !empty( $intelliwidget_post->thumbnail_id ) );
    }


    /**
     * Display the featured post image with link to the full image.
     */
    function the_intelliwidget_image( $link = TRUE, $size = NULL ) {
        echo get_the_intelliwidget_image( $link, $size );
    }

    /**
     * Return meta data from the post author
     */
    function get_the_intelliwidget_author_meta( $meta ) {
        global $intelliwidget_post;
        if ( $value = get_the_author_meta( $meta, $intelliwidget_post->post_author ) ) 
            return $value;
        return FALSE;
    }

    /**
     * Display meta data from the post author
     */
    function the_intelliwidget_author_meta( $meta = 'display_name' ) {
        if ( $value = get_the_intelliwidget_author_meta( $meta ) ) echo $value;
    }

    /**
     * Return the excerpt to display with the current post.
     *
     * @global <object> $intelliwidget_post
     * @return <string>
     */
    function get_the_intelliwidget_excerpt( $length = 15, $tags = '' ) {
        // FIXME: phase out globals
        global $intelliwidget_post;
        // use excerpt text if it exists otherwise parse the main content
        $excerpt = empty( $intelliwidget_post->post_excerpt ) ?
            get_the_intelliwidget_content() : 
                apply_filters( 'intelliwidget_content', $intelliwidget_post->post_excerpt );
        return apply_filters( 'intelliwidget_trim_excerpt', $excerpt, $length, $tags );
    }

    /**
     * Display the excerpt for the featured post.
     */
    function the_intelliwidget_excerpt( $length = 15, $tags = '' ) {
        echo get_the_intelliwidget_excerpt( $length, $tags );
    }

    /**
     * Return the excerpt to display with the current post.
     *
     * @global <object> $intelliwidget_post
     * @return <string>
     */
    function get_the_intelliwidget_content() {
        global $intelliwidget_post;
        return apply_filters( 'intelliwidget_content', $intelliwidget_post->post_content );
    }
    
    /**
     * Display the excerpt for the featured post.
     */
    function the_intelliwidget_content() {
        echo get_the_intelliwidget_content();
    }

    /**
     * This function has been deprecated in favor of the new
     * get_intelliwidget_link( $text, $custom, $classes )
     */
    function get_the_intelliwidget_link( 
        $post_id        = NULL, 
        $link_text      = NULL, 
        $category_id    = NULL, 
        $custom         = 1 
        ) {
	    _deprecated_function( __FUNCTION__, '2.3.5', 'get_the_intelliwidget_post_link()' );
        return get_the_intelliwidget_post_link( $link_text, $custom );
    }

    /**
     * Return a link for a post based on parameters
     */
    function get_the_intelliwidget_post_link( $link_text = NULL, $custom = 1, $classes = array() ) {
        global $intelliwidget_post;
        
        if ( empty( $link_text ) )
            $link_text = get_the_intelliwidget_title( $custom );

        $title_text = trim( esc_attr( strip_tags( $link_text ) ) );
        
        if ( empty ( $title_text ) ) 
            return '';
        
        if ( !empty( $intelliwidget_post->link_classes ) )
            $classes[] = $intelliwidget_post->link_classes;
        $target = empty( $intelliwidget_post->link_target ) ? 
            '' : ' target="' . $intelliwidget_post->link_target . '"';
        $content = '<a title="' . $title_text . '" href="' . get_the_intelliwidget_url()
            . '" class="' . implode( ' ', $classes ) . '" ' . $target . '>' . $link_text .  '</a>';
        return apply_filters( 'intelliwidget_link', $content, $title_text, $classes, $target );
    }

    /**
     * Backward compatible echo version
     */
    function the_intelliwidget_link(
        $post_id        = NULL, 
        $text           = NULL, 
        $category_id    = NULL, 
        $custom         = 1 
        ) {
        echo get_the_intelliwidget_post_link( $text, $custom );
    }

    function the_intelliwidget_post_link( $text = NULL, $custom = 1, $classes = array() ) {
        echo get_the_intelliwidget_post_link( $text, $custom, $classes );
    }
    /**
     * Return a url for a post based on parameters
     */
    function get_the_intelliwidget_url() {
        global $intelliwidget_post;
        return empty( $intelliwidget_post->external_url ) ? 
            get_permalink( $intelliwidget_post->ID ) : $intelliwidget_post->external_url;
    }

    function get_the_intelliwidget_taxonomy_link( $title, $instance ) {
        $title = apply_filters( 'widget_title', $title );
        if ( !empty( $instance[ 'terms' ] ) && '-1' != $instance[ 'terms' ] ):
            if ( $term = $instance[ 'query' ]->terms_query( $instance[ 'terms' ] ) ):
                $url = get_term_link( $term );
                $title_text = esc_attr( strip_tags( $title ) );
                return '<a title="' . $title_text . '" href="' . $url . '">' . $title .  '</a>';
            endif;
        endif;
        return $title;
    }

    /**
     * Display a url for a post based on parameters
     */
    function the_intelliwidget_url() {
        echo get_the_intelliwidget_url();
    }

    /**
     * Get the title for the current featured post, use alt title if it exists.
     *
     * @global <object> $intelliwidget_post
     * @return <string>
     */
    function get_the_intelliwidget_title( $custom = 1 ) {
        global $intelliwidget_post;
        $title = ( $custom && !empty( $intelliwidget_post->alt_title ) ) ? 
            $intelliwidget_post->alt_title : $intelliwidget_post->post_title;
        return $title; //esc_attr( $title );
    }
    
    /**
     * Display the title for the current featured post, use alt title if it exists.
     */
    function the_intelliwidget_title( $custom = 1 ) {
        echo get_the_intelliwidget_title( $custom );
    }

    /**
     * Get the event date for the post if it exists, otherwise return the post date.
     */
    function get_the_intelliwidget_date( $format = 'j', $publish = FALSE ) {
        global $intelliwidget_post;
        if ( $publish ) 
            $date = $intelliwidget_post->post_date;
        else
            $date = empty( $intelliwidget_post->event_date ) ? 
            $intelliwidget_post->post_date : $intelliwidget_post->event_date;
        return date_i18n( $format, strtotime( $date ) );
    }

    /**
     * Display the event date if it exists otherwise display post date.
     */
    function the_intelliwidget_date( $format = 'j', $publish = FALSE ) {
        echo get_the_intelliwidget_date( $format, $publish );
    }

    /**
     * Get the event date for the post if it exists, otherwise return the post date.
     */
    function get_the_intelliwidget_exp_date( $format = 'j' ) {
        global $intelliwidget_post;
        if ( empty( $intelliwidget_post->expire_date ) || 
            ( date_i18n( 'j', strtotime( $intelliwidget_post->event_date ) ) == date( 'j', strtotime( $intelliwidget_post->expire_date ) ) 
                && date_i18n( 'm', strtotime( $intelliwidget_post->event_date ) ) == date( 'm', strtotime( $intelliwidget_post->expire_date ) ) ) ):
            return FALSE;
        else:
            return date_i18n( $format, strtotime( $intelliwidget_post->expire_date ) );
        endif;
    }

    /**
     * Display the event date if it exists otherwise display post date.
     */
    function the_intelliwidget_exp_date( $format = 'j' ) {
        if ( $exp = get_the_intelliwidget_exp_date( $format ) ) echo $exp;
    }

    function intelliwidget_post_classes( $seq, $cols = 1, $classes = array() ) {
        // backward compatability: previously contained IntelliWidgetQuery object
        if ( is_object( $seq ) ) 
            $seq = $seq->current_post + 1;
            
        $classes[] = 'post-seq-' . $seq;
        $classes[] = ( $seq % 2 === 0 ) ? 'even' : 'odd';
        if ( $cols > 1 ):
            $row_len = intval( $cols );
            $classes[] = 'cell';
            $classes[] = 'width-1-' 
                . ( in_array( $row_len, array( 7,9,11 ) ) ? --$row_len : $row_len );
            if ( $seq % $row_len === 0 ):
                $classes[] = 'end';
            elseif ( $seq % $row_len === 1 ):
                $classes[] = 'clear';
            endif;
        endif;
        // add menu classes
        global $post, $intelliwidget_post;
        $id = ( int ) $intelliwidget_post->ID;
        if ( ( int ) $post->ID == $id ):
            $classes[] = 'intelliwidget-current-menu-item';
        endif;
        if ( is_post_type_hierarchical( $post->post_type ) ):
            $ancestors = get_post_ancestors( $post->ID );
            if ( in_array( $id, $ancestors ) ):
                $classes[] = 'intelliwidget-current-menu-ancestor';
            endif;
            if ( $id == current( $ancestors ) ):
                $classes[] = 'intelliwidget-current-menu-parent';
            endif;
        endif;
        
        return implode( ' ', $classes );
    }

    function the_intelliwidget_post_classes( $seq, $cols = 1, $classes = array() ) {
        echo intelliwidget_post_classes( $seq, $cols, $classes );
    }

    function get_the_intelliwidget_postmeta( $meta = NULL ) {
        global $intelliwidget_post;
        if ( $meta && ( $value = get_post_meta( $intelliwidget_post->ID, $meta, TRUE ) ) )
            return $value;
        return FALSE;
    }

    function the_intelliwidget_postmeta( $meta = NULL ) {
        echo get_the_intelliwidget_postmeta( $meta );
    }
