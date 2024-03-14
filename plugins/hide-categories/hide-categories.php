<?php
/*************************************************************************

Plugin Name:  Hide Categories
Plugin URI:	  http://www.thedeveloperinside.com/resources/hide-categories/
Description:  Hide one o more categories when you use the_category tag or wp_list_categories tag. No exclude post, but only hide a category name in template view.
Version:      1.2
Author:       Eduardo Chiaro
Author URI:   http://www.eduardochiaro.it/

**************************************************************************/


require_once('hide-categories.admin.php');

function HideCategories(){
	//add_filter('the_category','hide_categories_filter',10,2);
	add_filter("get_terms_args","hide_categories_excludes");
	add_filter("get_the_terms","hide_categories_terms");
}
function hide_categories_terms($code){
	$options = get_option('HideCategories');
	$exclude = $options['excludeCategories'];
	
	if(!is_array($exclude)){
		$exclude=array($options['excludeCategories']);
	}
	
	if (!is_admin()) {
		foreach($code as $key => $r){
			if($r->taxonomy == "category"){
				if(in_array($key, $exclude)) unset($code[$key]);
			}
		}
	}
	
	return $code;
}
function hide_categories_excludes($code){
	if($code["class"] == "categories"){
		
		if (!is_admin()) {
			$options = get_option('HideCategories');
			$exclude = $options['excludeCategories'];
	
			if(!is_array($exclude)){
				$exclude=array($options['excludeCategories']);
			}
			
			foreach($exclude as $ex){
				$newext	= @explode(',',get_category_children($ex,","));
				foreach($newext as $n){
					if($n) $exclude[]=$n;
				}
			}
			
			if(is_array($code["exclude_tree"])){
				$code["exclude_tree"] = array_merge($code["exclude_tree"],$exclude);
			}else{
				if($code["exclude_tree"] == ""){
					$code["exclude_tree"] = $exclude;
				}else{
					$code["exclude_tree"] .= ",".implode($exclude,",");
				}
			}
		}
	}
	return $code;
}

function HideCategories_admin_menu() {
	if ( function_exists('add_submenu_page') ){
		add_submenu_page('plugins.php', __('Hide Categories Configuration'), __('Hide Categories Configuration'), 10, 'HideCategories-config', 'HideCategories_config');
	}

}

function HideCategories_plugin_actions( $links, $file ){
	$this_plugin = plugin_basename(__FILE__);
	
	if ( $file == $this_plugin ){
		$settings_link = '<a href="plugins.php?page=HideCategories-config">' . __('Settings') . '</a>';
		array_unshift( $links, $settings_link );
	}
	return $links;
}


add_action('plugin_action_links','HideCategories_plugin_actions',10, 2);
add_action('admin_menu', 'HideCategories_admin_menu');


add_action( 'init', 'HideCategories' );
?>