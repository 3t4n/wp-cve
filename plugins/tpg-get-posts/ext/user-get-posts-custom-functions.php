<?php
/*
 * copy this file to the root directory of your theme (or child theme) 
 * and modify for your use.  
 *
 * By placing this file in your theme folder,it will not
 * be lost in an upgrade of the plugin
 * 
 * you can modify the content directly, remove any filters attached
 * to the filter the_content or add a new filter process
 * 
 * The cfp must be parsed in the routine and is a string
 * where you define the format.
 *
 * @since 2.2
 *
 */

/**
 *	tpg_filter_title
 *
 * filter the title
 *
 * @param	string	title
 * @param	object	post
 * @return	string  title
 *
 */
function tgp_filter_title($content,$post) {

	return $content;
}
/**
 *	tpg_filter_byline
 *
 * filter the byline
 *
 * @param	string	byline
 * @param	object	post
 * @return	string  byline
 *
 */
function tgp_filter_byline($content,$post) {

    return $content;
}

/**
 *	tpg_filter_content
 *
 * filter the content
 *
 * @param	string	content
 * @param	object	post
 * @return	string  content
 *
 */
function tgp_filter_content($content,$post) {

    return $content;
}

/**
 *	tpg_filter_metadata
 *
 * filter the metadata
 *
 * @param	string	metadata
 * @param	object	post
 * @return	string  metadata
 *
 */
function tgp_filter_metadata($content,$post) {

	return $content;
}

/**
 *	tpg_filter_thumbnail
 *
 * filter the thumbnail
 *
 * @param	string	thumbnail
 * @param	object	post
 * @return	string  thumbnail
 *
 */
function tgp_filter_thumbnail($content,$post) {

    return $content;
}

/**
 *	tpg_gp_pst_content_filter
 *
 * allow custom function to be invoke before content 
 * 
 * @param	string	content
 * @param	string	custom function parm
 * @return	string  content
 *
 */
function tpg_gp_pst_title_filter($content,$cfp=null) {
	
	return $content;
}

/**
 *	tpg_gp_pst_content_filter
 *
 * allow custom function to be invoke before content 
 * 
 * @param	string	content
 * @param	string	custom function parm
 * @return	string  content
 *
 */
function tpg_gp_pst_byline_filter($content,$cfp=null) {
	
	return $content;
}

/**
 *	tpg_gp_pst_content_filter
 *
 * allow custom function to be invoke before content 
 * 
 * @param	string	content
 * @param	string	custom function parm
 * @return	string  content
 *
 */
function tpg_gp_pst_content_filter($content,$cfp=null) {
	
	return $content;
}

/**
 *	tpg_gp_pst_metadata_filter
 *
 * allow custom function to be invoke before content 
 * 
 * @param	string	content
 * @param	string	custom function parm
 * @return	string  content
 *
 */
function tpg_gp_pst_metadata_filter($content,$cfp=null) {
	
	return $content;
}

/**
 *	tpg_gp_pre_post_filter
 *
 * allow custom function to be invoke before content 
 * 
 * @param	string	content
 * @param	string	custom function parm
 * @return	string  content
 *
 */
function tpg_gp_pre_post_filter($content,$cfp=null) {

	return $content;
}

/**
 *	tpg_gp_pst_post_filter
 *
 * allow custom function to be invoke before content 
 * 
 * @param	string	content
 * @param	string	custom function parm
 * @return	string  content
 *
 */
function tpg_gp_pst_post_filter($content,$cfp=null) {

	return $content;
}

/**
 *	tpg_gp_pre_plugin_filter
 *
 * allow custom function to be invoke before content 
 * 
 * @param	string	content
 * @param	string	custom function parm
 * @return	string  content
 *
 */
function tpg_gp_pre_plugin_filter($content,$cfp=null) {
	/*
	 * the following code is a sampe to turn off MPR & Yet Another Related post plugins
	 * uncomment to activate sample
	*/
    //remove_filter('the_content','MRP_auto_related_posts');
    //global $yarpp; 
    //remove_filter('the_content', array($yarpp,'the_content'), 1200);
	return $content;
}

/**
 *	tpg_gp_pst_plugin_filter
 *
 * allow custom function to be invoke before content 
 * 
 * @param	string	content
 * @param	string	custom function parm
 * @return	string  content
 *
 */
function tpg_gp_pst_plugin_filter($content,$cfp=null) {
	/*
	 * the following code is a sample to add the MPR & Yet Another Related post plugins
	 * uncomment to activate sample
	*/
    //add_filter('the_content','MRP_auto_related_posts');
    //global $yarpp; 
    //add_filter('the_content', array($yarpp,'the_content'), 1200);
	return $content;
}
	

?>
