<?php
/**
 * $Desc$
 *
 * @version    $Id$
 * @package    opalportfolios
 * @author     Opal  Team <opalwordpressl@gmail.com >
 * @copyright  Copyright (C) 2016 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/support/forum.html
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action( 'elementor/widgets/widgets_registered', "opalportfolio_include_single_widgets" );
if( !function_exists("opalportfolio_include_single_widgets") ){
    function opalportfolio_include_single_widgets ( $widgets_manager ){
    	$files = glob( PE_PLUGIN_INC_DIR ."widgets/*.php");
    	foreach ( $files as $file ) {
    		$class = "PE_Portfolio_Widget_".ucfirst( basename( str_replace('.php','',$file) ) );
    		require_once( $file );
    		if( class_exists($class) ){
    			$widgets_manager->register_widget_type( new $class() );
    		}
    	

    	}
    }
}

if( !function_exists("opalportfolio_terms_related") ){
    function opalportfolio_terms_related($post_id, $class_items= ''){
      $item_cats = get_the_terms( $post_id, PE_CAT );
      if ( !empty($item_cats) && !is_wp_error($item_cats) ){
        foreach((array)$item_cats as $item_cat){
            
            if( isset($item_cat->slug ) ){
              $class_items .= $item_cat->slug . ' ';
            }
        }
      }
      return $class_items;
    }
}

if( !function_exists("opalportfolio_get_template_part") ){
    function opalportfolio_get_template_part($slug, $name = null) {
    	do_action("ccm_get_template_part_{$slug}", $slug, $name);
    	$templates = array();
    	if (isset($name))
    	  	$templates[] = "{$slug}-{$name}.php";
    	$templates[] = "{$slug}.php";
    	opalportfolio_get_template_path($templates, true, false);
    }
}

/* Extend locate_template from WP Core 
* Define a location of your plugin file dir to a constant in this case = PLUGIN_DIR_PATH 
* Note: PLUGIN_DIR_PATH - can be any folder/subdirectory within your plugin files 
*/ 
if( !function_exists("opalportfolio_get_template_path") ){
    function opalportfolio_get_template_path($template_names, $load = false, $require_once = true ) {
        $located = ''; 
        foreach ( (array) $template_names as $template_name ) { 
          if ( !$template_name ) 
            continue; 
          /* search file within the PLUGIN_DIR_PATH only */ 
          if ( file_exists(PE_PLUGIN_DIR . $template_name)) { 
            $located = PE_PLUGIN_DIR . $template_name; 
            break; 
          } 
        }
        if ( $load && '' != $located )
            load_template( $located, $require_once );
        return $located;
    }
}


/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 *
 * @param  string $key Options array key
 *
 * @return mixed        Option value
 */
if( !function_exists("portfolio_get_option") ){
    function portfolio_get_option( $key = '', $default = false ) {
        global $portfolio_options;
        $value = ! empty( $portfolio_options[ $key ] ) ? $portfolio_options[ $key ] : $default;
        $value = apply_filters( 'portfolio_get_option', $value, $key, $default );

        return apply_filters( 'portfolio_get_option_' . $key, $value, $key, $default );
    }
}
/**
 * Update an option
 *
 * Updates an portfolio setting value in both the db and the global variable.
 * Warning: Passing in an empty, false or null string value will remove
 *          the key from the portfolio_options array.
 *
 * @since 1.0
 *
 * @param string          $key   The Key to update
 * @param string|bool|int $value The value to set the key to
 *
 * @return boolean True if updated, false if not.
 */
function portfolio_update_option( $key = '', $value = false ) {

    // If no key, exit
    if ( empty( $key ) ) {
        return false;
    }

    if ( empty( $value ) ) {
        $remove_option = portfolio_delete_option( $key );

        return $remove_option;
    }

    // First let's grab the current settings
    $options = get_option( 'portfolio_settings' );

    // Let's let devs alter that value coming in
    $value = apply_filters( 'portfolio_update_option', $value, $key );

    // Next let's try to update the value
    $options[ $key ] = $value;
    $did_update      = update_option( 'portfolio_settings', $options );

    // If it updated, let's update the global variable
    if ( $did_update ) {
        global $portfolio_options;
        $portfolio_options[ $key ] = $value;
    }

    return $did_update;
}

/**
 * Remove an option
 *
 * Removes an portfolio setting value in both the db and the global variable.
 *
 * @since 1.0
 *
 * @param string $key The Key to delete
 *
 * @return boolean True if updated, false if not.
 */

function portfolio_delete_option( $key = '' ) {

    // If no key, exit
    if ( empty( $key ) ) {
        return false;
    }

    // First let's grab the current settings
    $options = get_option( 'portfolio_settings' );

    // Next let's try to update the value
    if ( isset( $options[ $key ] ) ) {

        unset( $options[ $key ] );

    }

    $did_update = update_option( 'portfolio_settings', $options );

    // If it updated, let's update the global variable
    if ( $did_update ) {
        global $portfolio_options;
        $portfolio_options = $options;
    }

    return $did_update;
}


/**
 * Get Settings
 *
 * Retrieves all portfolio plugin settings
 *
 * @since 1.0
 * @return array portfolio settings
 */
function portfolio_get_settings() {

    $settings = get_option( 'portfolio_settings' );

    return (array) apply_filters( 'portfolio_get_settings', $settings );

}

function portfolio_media_settings(){
	// Enable support for Post Thumbnails, and declare two sizes.
	if ( function_exists( 'add_theme_support' ) ) {
	    add_theme_support( 'post-thumbnails' );
	    set_post_thumbnail_size( portfolio_get_option('thumbnail-w'), portfolio_get_option('thumbnail-h'), true ); // default Featured Image dimensions (cropped)
	 
	    // additional image sizes
	    // delete the next line if you do not need additional image sizes
	    	    
	    add_image_size( 'portfolio-medium', portfolio_get_option('medium-w'), portfolio_get_option('medium-h'), true );
	    add_image_size( 'portfolio-large', portfolio_get_option('large-w'), portfolio_get_option('large-h'), true );
	}
}
add_action('init', 'portfolio_media_settings');

/**
 * Get Settings
 *
 * Portfolio Pagination
 *
 * @since 1.0
 * @return array portfolio settings
 */

if( !function_exists("portfolio_pagination") ){
    function portfolio_pagination( $numpages = '', $pagerange = '', $paged='' ) {

        if ( empty( $pagerange ) ) {
            $pagerange = 2;
        }
        global $paged;
        if ( empty( $paged ) ) {
            $paged = 1;
        }
        if ( $numpages == '' ) {
            global $wp_query;
            $numpages = $wp_query->max_num_pages;
            if ( ! $numpages ) {
                $numpages = 1;
            }
        }

        $pagination_args = array(
            'base'            => get_pagenum_link(1) . '%_%',
            'format'          => 'page/%#%',
            'total'           => $numpages,
            'current'         => $paged,
            'show_all'        => False,
            'end_size'        => 1,
            'mid_size'        => $pagerange,
            'prev_next'       => false,
            'prev_text'       => __('&laquo;'),
            'next_text'       => __('&raquo;'),
            'type'            => 'plain',
            'add_args'        => false,
            'add_fragment'    => ''
        );

        $paginate_links = paginate_links( $pagination_args );

        if ( $paginate_links ) {
            echo "<nav class='custom-pagination'>";
            echo "<span class='page-num'>Page " . $paged . 
                " of " .  $numpages . "</span> ";
            echo $paginate_links;
            echo "</nav>";
        }

    }
}
/**
 * Get Settings
 *
 * Portfolio navigation
 *
 * @since 1.0
 * @return array portfolio settings
 */
if( !function_exists("opalportfolio_single_navigation") ){
    function opalportfolio_single_navigation() { 
        ?>
        <div class="portfolio-nav-links">
            <div class="nav-list">
        <?php
        $prev_post = get_previous_post(); 
        if($prev_post){
            $id = $prev_post->ID ;
            $prev_permalink = get_permalink( $id ); ?>
                <div class="nav-item prev">
                    <div class="inner">
                        <a href="<?php echo esc_url($prev_permalink);?> ">
                            <span><?php echo  esc_html('Prev', 'opalportfolios' );?></span>
                            <h6><?php echo esc_attr($prev_post->post_title); ?></h6> 
                        </a>   
                    </div>                       
                </div>
        <?php 
        }

        $next_post = get_next_post();
        if($next_post){
            $nid = $next_post->ID ;
            $next_permalink = get_permalink($nid); ?>
                <div class="nav-item next">
                    <div class="inner">
                        <a href="<?php echo esc_url($next_permalink);?> ">
                            <span><?php echo  esc_html('Next', 'opalportfolios' );?></span>
                            <h6><?php echo esc_attr($next_post->post_title); ?></h6> 
                        </a>    
                    </div>                      
                </div>
                
        <?php 
        } ?>
            </div>
        </div>
        <?php
    }
}
/**
 * Get Settings
 *
 * Portfolio Share social
 *
 * @since 1.0
 * @return array portfolio settings
 */
if( !function_exists("opalportfolio_single_share") ){
    function opalportfolio_single_share() { 
        $share = get_theme_mod( 'opalportfolio_share_single_position' ) ? 'yes' : 'no' ;
        if($share === 'no') : ?>
            <div class="portfolio-share">
                <h6 class="portfolio-share-title"><?php echo esc_html( 'Share', 'opalportfolios' ); ?></h6>
                <div class="portfolio-sharing-list">
                    <?php echo Opalportfolio_Template_Loader::get_template_part( 'social-share/social-share'); ?>       
                </div>
            </div>
        <?php endif;
    }
}
/**
 * Set sidebar position
 */
function opalportfolio_sidebar_archive_position( $pos ){
    if( is_single() && get_post_type() == 'portfolio' ){
        return get_theme_mod( 'opalportfolio_sidebar_single_position' );
    }
    return $pos; 
}
add_filter( 'opalportfolio_sidebar_archive_position', 'opalportfolio_sidebar_archive_position' );

