<?php
/*
Plugin Name: Tab Me
Plugin URI: 
Description: Provides a shortcode which allows tabbed content
Version: 1.0.2 
Author: Micah Blu
Author URI: http://www.micahblu.com/
License: GPL
Copyright: Micah Blu
*/

/**
 * Shortcode: [tab]
 *
 * @usage: [tab title="title 1"]Your content goes here...[/tab]
 * @since 0.5
 * @param '$atts'    (Array)   |  array of attributes
 * @param '$content' (String)  |  string contents of the tabs
 */ 
function tab_func( $atts, $content = null ) {
    extract(shortcode_atts(array(
	    'title'  => '',
      'link'   => '' ,
      'target' => ''
    ), $atts));
    global $single_tab_array;
    $single_tab_array[] = array('title' => $title, 'link' => $link, 'content' => trim(do_shortcode($content)));
    return $single_tab_array;
}
add_shortcode('tab', 'tab_func');

/* Shortcode: tabs
 * Usage:   
 *  [tabs]
 * 	  [tab title="title 1"]Your content goes here...[/tab]
 *    [tab title="title 2"]Your content goes here...[/tab]
 * 	[/tabs]
 */
function tabs_func( $atts, $content = null ) {
    global $single_tab_array;
    $single_tab_array = array(); // clear the array
    
    $tabs_nav = '';
    $tabs_content = '';
    $tabs_output = '';
 
    $tabs_nav = '<div class="tab-me-wrapper">';
    $tabs_nav .= '<ul class="tab-me-tabs">';
    
     // execute the '[tab]' shortcode first to get the title and content - acts on global $single_tab_array
    do_shortcode($content);
    
    //declare our vars to be super clean here
    foreach ($single_tab_array as $tab => $tab_attr_array) {

    	$random_id = rand(1000,2000); // potential duplicate issue.. need to fix
    	
    	$default = ( $tab == 0 ) ? ' class="active"' : '';
    	
      if($tab_attr_array['link'] != ""){
        $tabs_nav .= '<li'.$default.'><a class="tab-me-link" href="' . $tab_attr_array["link"] . '" target="' . $tab_attr_array["target"] . '" rel="tab'.$random_id.'"><span>'.$tab_attr_array['title'].'</span></a></li>';
      }else{
      	$tabs_nav .= '<li'.$default.'><a href="javascript:void(0)" rel="tab'.$random_id.'"><span>'.$tab_attr_array['title'].'</span></a></li>';
      	$tabs_content .= '<div class="tab-me-tab-content" id="tab' . $random_id . '" ' . ( $tab!=0 ? 'style="display:none"' : '') . '>'.$tab_attr_array['content'].'</div>';
      }
    }
    $tabs_nav .= '</ul><!-- .tab-me-tabs -->';
    
    $tabs_output = $tabs_nav . '<div class="tab-me-content-wrapper">' . $tabs_content . '</div>';
    $tabs_output .= '</div><!-- .tabs-wrapper -->';

    return $tabs_output;
}
add_shortcode('tabs', 'tabs_func');


function tab_me_scripts(){
	wp_enqueue_style('tab-me-styles', plugins_url( 'tab-me.css', __FILE__) );
	
	wp_enqueue_script('tab-me-js', plugins_url( 'tab-me.js', __FILE__), array('jquery'), null, true );
}

add_action('wp_enqueue_scripts', 'tab_me_scripts');