<?php

/**
 * Our Team Members by WpBean
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 



/**
 * Get template part implementation for WPB Post Slider
 *
 * Looks at the theme directory first
 */
function wpb_otm_get_template_part( $slug, $name = '' ) {
    $wpb_our_team_members = wpb_our_team_members::init();

    $templates = array();
    $name = (string) $name;

    // lookup at theme/slug-name.php or our-team-members/slug-name.php
    if ( '' !== $name ) {
        $templates[] = "{$slug}-{$name}.php";
        $templates[] = $wpb_our_team_members->theme_dir_path . "{$slug}-{$name}.php";
    }

    $template = locate_template( $templates );

    // fallback to plugin default template
    if ( !$template && $name && file_exists( $wpb_our_team_members->template_path() . "{$slug}-{$name}.php" ) ) {
        $template = $wpb_our_team_members->template_path() . "{$slug}-{$name}.php";
    }

    // if not yet found, lookup in slug.php only
    if ( !$template ) {
        $templates = array(
            "{$slug}.php",
            $wpb_our_team_members->theme_dir_path . "{$slug}.php"
        );

        $template = locate_template( $templates );
    }

    if ( $template ) {
        load_template( $template, false );
    }
}

/**
 * Include a template by precedance
 *
 * Looks at the theme directory first
 *
 * @param  string  $template_name
 * @param  array   $args
 *
 * @return void
 */
function wpb_otm_get_template( $template_name, $args = array() ) {
    $wpb_our_team_members = wpb_our_team_members::init();

    if ( $args && is_array($args) ) {
        extract( $args );
    }

    $template = locate_template( array(
        $wpb_our_team_members->theme_dir_path . $template_name,
        $template_name
    ) );

    if ( ! $template ) {
        $template = $wpb_our_team_members->template_path() . $template_name;
    }

    if ( file_exists( $template ) ) {
        include $template;
    }
}




/**
 * Get CS Meta value
 * 
 * $meta_section : (string) (Required) metabox section key
 * 
 * $meta_field : (string) (Required) metabox field key
 *
 * $id : int (Optional) Loop post id. Default value: null
 * 
 * $default_value : (string) (Optional) metabox default falue
 * 
 * $single : (bool) (Optional) Whether to return a single value. Default value: true

 */

if( !function_exists('wpb_otm_get_post_meta') ){
    function wpb_otm_get_post_meta ( $meta_section, $meta_field, $id = null, $default_value = null, $single = true ){

        if( !is_search() && !is_404() ){
            if( $id ){
                $values = get_post_meta( $id, $meta_section, true );
            }else {
                global $wp_query;
                $id = $wp_query->post->ID;
                $values = get_post_meta( $id, $meta_section, true );
                wp_reset_postdata();
            }

            $value = $default_value;

            if( isset($values) && is_array($values) ){
                if ( array_key_exists( $meta_field, $values ) ) {
                    $value = $values[$meta_field];
                }
            }
        }else {
            $value = $default_value;
        } 
        
        return $value;
    }
}





/**
 * Add generated shortcode on shortcode post column
 */



add_filter( 'manage_edit-wpb_otm_shortcode_columns', 'wpb_otm_edit_shortcode_post_type_columns' ) ;
add_action( 'manage_wpb_otm_shortcode_posts_custom_column', 'wpb_otm_manage_shortcode_post_type_columns', 10, 2 );



if( !function_exists('wpb_otm_edit_shortcode_post_type_columns') ){
    function wpb_otm_edit_shortcode_post_type_columns( $columns ) {

        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'title'     => esc_html__( 'Shortcode Name', 'our-team-members' ),
            'shortcode' => esc_html__( 'Shortcode', 'our-team-members' ),
            'date'      => esc_html__( 'Date', 'our-team-members' ),
        );

        return $columns;
    }
}

if( !function_exists('wpb_otm_manage_shortcode_post_type_columns') ){
    function wpb_otm_manage_shortcode_post_type_columns( $column, $post_id ) {
        global $post;

        switch( $column ) {

            /* If displaying the 'shortcode' column. */
            case 'shortcode' :

                /* Get the post meta. */
                $shortcode = wpb_otm_get_post_meta( '_wpb_show_team_members_shortcode', 'wpb_otm_shortcode',  $post_id );

                /* If no shortcode is found, output a default message. */
                if ( empty( $shortcode ) )
                    echo esc_html__( 'No Shortcode found.', 'our-team-members' );

                /* If there is a shortcode, append 'minutes' to the text string. */
                else

                    printf( '<input type="text" name="wpb_otm_shortcode_'. $post_id .'" id="wpb_otm_shortcode_'. $post_id .'" value="%s" class="regular-text wpb-otm-the-shortcode" size="30" readonly="readonly"/>', esc_attr( $shortcode ) );

                break;

            /* Just break out of the switch statement for everything else. */
            default :
                break;
        }
    }
}


/**
 * Order posts by the last word in the post_title. 
 * Activated when orderby is 'wpb_otm_last_word' 
 * @link https://wordpress.stackexchange.com/a/198624/26350
 */
add_filter( 'posts_orderby', 'wpb_otm_custom_post_orderby', 10, 2 );

function wpb_otm_custom_post_orderby( $orderby, \WP_Query $q ){
    
    if( 'wpb_otm_last_word' === $q->get( 'orderby' ) && $get_order =  $q->get( 'order' ) ) {
        if( in_array( strtoupper( $get_order ), ['ASC', 'DESC'] ) ) {
            global $wpdb;
            $orderby = " SUBSTRING_INDEX( {$wpdb->posts}.post_title, ' ', -1 ) " . $get_order;
        }
    }

    return $orderby;
}