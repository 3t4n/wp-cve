<?php
/* 
 * Plugin Name:   Readers From RSS 2 BLOG
 * Version:       3.0.1.4 
 * Plugin URI:    http://marketplace.wpsmartapps.com/33/readers-from-rss-2-blog/
 * Description:   <strong>Increase Your SALES And BLOG Audience</strong> By Turning Your RSS FEED Into A Powerful <strong>Marketing</strong> Machine, Adjust your settings <a href="tools.php?page=readers-from-rss-2-blog/readers-from-rss-2-blog.php">here</a>.
 * Author:        WpSmartApps.com
 * Author URI:    http://www.wpsmartapps.com
 *
 */ 

$rfr2b_path     = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', __FILE__);
$rfr2b_path     = str_replace('\\','/',$rfr2b_path);
$rfr2b_dir      = substr($rfr2b_path,0,strrpos($rfr2b_path,'/'));
$rfr2b_siteurl  = get_bloginfo('wpurl');
$rfr2b_siteurl  = (strpos($rfr2b_siteurl,'http://') === false) ? get_bloginfo('siteurl') : $rfr2b_siteurl;
$rfr2b_fullpath = $rfr2b_siteurl.'/wp-content/plugins/'.$rfr2b_dir.'/';
$rfr2b_libpath  = $rfr2b_fullpath.'wpsmartapps-lic/';
$rfr2b_relpath  = str_replace('\\','/',dirname(__FILE__));
$rfr2b_abspath  = str_replace("\\","/",ABSPATH); 
define('RFR2B_ABSPATH', $rfr2b_abspath);
define('RFR2B_PATH', $rfr2b_path);
define('RFR2B_FULLPATH', $rfr2b_fullpath);
define('RFR2B_LIBPATH', $rfr2b_libpath);
define('RFR2B_SITEURL', $rfr2b_siteurl);
define('RFR2B_REALPATH', $rfr2b_relpath);
define('RFR2B_NAME', 'Readers From rss 2 blog');
define('RFR2B_VERSION', '3.0.1.4');
require_once($rfr2b_relpath.'/wpsmartapps-lic/readers-from-rss-2-blog.cls.php');
/**
 * Readers From Rss 2 Blog Wordpress Connected Class
 * Holds all the necessary functions and variables
 */
class WpSmartApps_ReadersFromRss2BlogPlugin extends WSA_LIC_ReadersFromRss2Blog
{
	/**
	 * Constructor.
	 */
	function WpSmartApps_ReadersFromRss2BlogPlugin() {
		global $table_prefix, $wp_version, $wpdb;
		// Wordpress tables
		$this->options_table = $wpdb->options;
		$this->posts_table   = $wpdb->posts;
		// Plugin table
		$this->rfr2b_options_table  = $table_prefix.$this->rfr2b_options_table;
		$this->rfr2b_target_rss_table = $table_prefix.$this->rfr2b_target_rss_table; 
		// Define plugin page
		$this->rfr2b_plugin_page        = ($wp_version >= 2.7) ? 'tools.php' : 'edit.php';
		// Plugin activate  actions, filters.
		add_action('activate_'.RFR2B_PATH, array(&$this, 'rfr2b_active')); 
		add_filter('plugin_action_links', array(&$this,'rfr2b_actions'), 10, 2 );
		add_action('admin_menu', array(&$this, 'rfr2b_admin_menu'));
		// Define Images
		$this->rfr2b_img_logo = '<img src="'.RFR2B_LIBPATH.'images/rfr2b-logo.png" border="0" align="absmiddle" alt="'.RFR2B_NAME.'" title="'.RFR2B_NAME.'" >';
		$this->rfr2b_img_upgrade = '<img src="'.RFR2B_LIBPATH.'images/upgrade.gif" border="0" align="absmiddle">';
		$this->rfr2b_img_leftarrow = '<img src="'.RFR2B_LIBPATH.'images/left-arrow.png" border="0" align="absmiddle">';
		$this->rfr2b_img_rightarrow = '<img src="'.RFR2B_LIBPATH.'images/right-arrow.png" border="0" align="absmiddle">';
		$this->rfr2b_img_global_preview = '<img src="'.RFR2B_LIBPATH.'images/close-form.gif" border="0" id="global_demo" align="absmiddle">';
		$this->rfr2b_img_google_reader = '<img src="'.RFR2B_LIBPATH.'admin-pg/demo/googlereader.jpg" border="0" id="global_demo" align="absmiddle">';
		$this->rfr2b_img_edit = '<img src="'.RFR2B_LIBPATH.'images/tick.png" border="0" id="global_demo" align="absmiddle">';
		// Global update.
		add_action('admin_notices', array(&$this, '__rfr2b_update_notify'));
		// Display
		add_filter('the_content', array(&$this, 'rfr2b_display_Feed'), 30);
		add_filter('the_excerpt_rss', array(&$this, 'rfr2b_display_Feed'), 30);
		/*
		 * Add Page On Feed
		*/
		add_filter('posts_where', array(&$this,'rfr2b_posts_where'));
		/*
		* Deal with Last Post Modified so feeds will validate.  WP default just checks for posts, not pages.
		*/
		add_filter('get_lastpostmodified', array(&$this,'rfr2b_get_lastpostmodified'),10,2);
		// We do this because is_feed is not set when calling get_lastpostmodified.
		add_action('rss2_ns', array(&$this,'rfr2b_feed_true'));
		add_action('atom_ns', array(&$this,'rfr2b_feed_true'));
		add_action('rdf_ns', array(&$this,'rfr2b_feed_true'));
		// We won't mess with comment feeds.
		add_action ('rss2_comments_ns', array(&$this,'rfr2b_feed_false'));
		add_action ('atom_comments_ns', array(&$this,'rfr2b_feed_false'));
	}
	
	
	/*
	* Add Page On Feed
	*/
	function rfr2b_get_lastpostmodified($lastpostmodified, $timezone){
		$this->__rfr2b_fetch_Options('rfr2b_control_options');
		if( $this->fetch_rfr2b_control_options['rfr2b_include_pages'] == 1 ) { 
			global $ma_feed, $wpdb;
			if (!($ma_feed)){
				return $lastpostmodified;
			}
			//queires taken from wp-includes/post.php  modified to include pages
			$lastpostmodified = $wpdb->get_var("SELECT post_modified_gmt FROM $wpdb->posts WHERE post_status = 'publish' AND (post_type = 'post' OR post_type = 'page') ORDER BY post_modified_gmt DESC LIMIT 1");
			$lastpostdate = $wpdb->get_var("SELECT post_date_gmt FROM $wpdb->posts WHERE post_status = 'publish' AND (post_type = 'post' OR 'page') ORDER BY post_date_gmt DESC LIMIT 1");
			if ( $lastpostdate > $lastpostmodified ) {
					$lastpostmodified = $lastpostdate;
			}
		} // Eof include Pages	
		return $lastpostmodified;
	}
	
	function rfr2b_posts_where($var){
		$this->__rfr2b_fetch_Options('rfr2b_control_options');
		$pagesArray = $this->fetch_rfr2b_control_options['displayINpageID'];
		$count_array = count($this->fetch_rfr2b_control_options['displayINpageID']);
		if( $count_array == 0 ) {
			$pageID_on_commas = 'null'; 
		} else {
			$pageID_on_commas = implode(",", $pagesArray); 
		}
		
		
		if (!is_feed()){ // check if this is a feed
			return $var; // if not, return an unmodified variable
		} else {
			if( $this->fetch_rfr2b_control_options['rfr2b_include_pages'] == 1 ) { 
				global $table_prefix; // get the table prefix
				$find = $table_prefix . 'posts.post_type = \'post\''; // find where the query filters by post_type
				///$replace = '(' . $find . ' OR ' . $table_prefix . 'posts.post_type = \'page\')'; // add OR post_type 'page' to the query
				$replace = '(' . $find . ' OR (' . $table_prefix . 'posts.post_type = \'page\' AND '.$table_prefix.'posts.ID IN ('.$pageID_on_commas.') ))'; // add OR post_type 'page' to the query
				$var = str_replace($find, $replace, $var); // change the query
			}
		}
		return $var; // return the variable
	}
	
	function rfr2b_feed_true(){
		global $ma_feed;
		$ma_feed = true;
	}
	
	function rfr2b_feed_false(){
		global $ma_feed;
		$ma_feed = false;
	}
	/*
	* Eof Add Page On Feed
	*/
	
	/**
	 * Called when plugin is activated. 
	 */
	function rfr2b_active() {
		$table_options = $this->__rfr2b_options_table();
		$table_target = $this->__rfr2b_target_rss_table();
		if ( $table_options == true ) $this->__rfr2b_DefaultOptinData();
		return true;
	}
	
	/**
	 * Adds Custom settings option on Manage Plugins page.
	 */
	function rfr2b_actions( $links, $file ) {
		if( $file == 'readers-from-rss-2-blog/readers-from-rss-2-blog.php' && function_exists( "admin_url" ) ) {
			$settings_link = '<a href="' . admin_url( 'tools.php?page=readers-from-rss-2-blog/readers-from-rss-2-blog.php' ) . '">' .'Settings' . '</a>';
			array_unshift( $links, $settings_link ); // before other links
		}
		return $links;
	}	
	
	/**
	 * Adds the plugins link in admin's Manage menu
	 */
	function rfr2b_admin_menu() {
		add_management_page(RFR2B_NAME, RFR2B_NAME, 9, RFR2B_PATH, array(&$this, 'rfr2b_admin_options'));
	}
	
	/**
	 * Displays the plugins options : 
	 */
	function rfr2b_admin_options() {
		$this->rfr2b_process_msg = $this->__rfr2b_processPg();
		echo '<div class="wrap">';
		$this->__rfr2b_displayDashboardPg();
		echo '</div>';
	}
	
	/**
	 * Diplay Ads based on Global RSS Display Configuration
	 */
	function rfr2b_display_Feed( $post_content ) {
	
		global $post;
		$this->rfr2b_tags = array();
		$this->blog_name  = get_bloginfo('name');
		$this->blogurl    = get_bloginfo('wpurl');
		$this->blog_url   = trim($blogurl) == '' ? get_bloginfo('siteurl') : $blogurl;
		$this->year       = date ("Y");
		$this->post_url   = get_permalink($post->ID);
		$this->postTitle  = $post->post_title;
		$this->postID = $post->ID;
		// add
		$tags = get_the_tag_list('',', ');
		$noof_Post_comments = get_comments_number($post->ID);
		
		if ( is_feed() ) {
			$post_content = $this->__rfr2b_display_Feed($post_content, $post->ID, $post->post_title, $noof_Post_comments, $tags);
		}
		
		return $post_content;
	
	}
	
	
	function rfr2b_display_editor( $targetContentValue, $rss_content ){
		// Take over: make sure the_editor is not unnecessary big
		$old_rows = get_option('default_post_edit_rows');
		update_option('default_post_edit_rows',7);
		// Take over: tell the visual editor to shut up
		add_filter('user_can_richedit', create_function('','return false;'));
		// wordpress editor
		the_editor( $targetContentValue, $rss_content);
		// End of take over
		update_option('default_post_edit_rows',$old_rows);
	}
	
	
	/**
	 * Diplay Random Post
	 */
	function rfr2b_ramdom_post ( $num_posts, $postID, $before_post='<ul style="margin:0; padding:0; padding-top:10px; padding-bottom:5px;">', $after_post='</ul>', $before_title='<li style="list-style-type: none;">', $after_title='</li>' ) {
		$rand_posts = get_posts( 'numberposts='.$num_posts.'&orderby=rand' );
		foreach ($rand_posts as $rel_post) {
			if ( $rel_post->ID != $postID ) {
				$rss_random_post .= $before_title .'<img src="'.RFR2B_LIBPATH.'images/tick.png" border="0" align="absmiddle"> &nbsp;<a href="';
				$rss_random_post .= get_permalink($rel_post->ID);
				$rss_random_post .= '" >'.$rel_post->post_title;
				$rss_random_post .= '</a>'. $after_title;
			}
		}
		$display_random_posts = $before_post . $rss_random_post . $after_post;
		return array( $num_rows,$display_random_posts );
	}
	
	
	/**
	 * Page Recursive
	 */
	
	function rfr2b_page_list_recursive($parentid=0,$exclude='',$selected=array(),$show_in_page, $page_name){  
		$pages = get_pages('parent='.$parentid.'&child_of='.$parentid.'&exclude='.$exclude);
		if(count($pages) > 0){
			$str = '
		<ul style="padding:10px 0px 0px 20px;">';
		$page_count =1;
		$total_page = count($pages);
			foreach($pages as $p){
				$sel = false;
				if(isset($selected) && in_array($p->ID,$selected))
					$sel = true;
			
			if( $page_count > 4 ) { 
				if( $page_count == 5 ) {
					$str .='<a style="cursor:pointer; color:#0033CC" onclick="__rfr2b_catpage_openit(\''.$page_name.$p->ID.'_openit\',\''.$page_name.$p->ID.'_closeit\')" id="'.$page_name.$p->ID.'_closeit"><strong>More [+]</strong></a>';
					$str .='<span id="'.$page_name.$p->ID.'_openit" style="display:none;">';
					$closePgID = $page_name.$p->ID;
				} 
				
				$str .= '
			<li><input type="checkbox" name="rfr2b['.$show_in_page.'][]" value="'.$p->ID.'" id="pageid_'.$p->ID.'"'.(($sel)?' checked="checked"':'').' /> <label for="pageid_'.$p->ID.'">'.wp_specialchars($p->post_title).'</label>'.$this->rfr2b_page_list_recursive($p->ID,$exclude,$selected,$show_in_page,$page_name).'</li>';
			
				if( $total_page == $page_count ) {
					$str .='<a style="cursor:pointer; color:#0033CC" onclick="__rfr2b_catpage_closeit(\''.$closePgID.'_openit\',\''.$closePgID.'_closeit\')" ><strong>Close</strong></a></span>';
				}
			
			
			} else if( $page_count <= 4 ) { 
				$str .= '
			<li><input type="checkbox" name="rfr2b['.$show_in_page.'][]" value="'.$p->ID.'" id="pageid_'.$p->ID.'"'.(($sel)?' checked="checked"':'').' /> <label for="pageid_'.$p->ID.'">'.wp_specialchars($p->post_title).'</label>'.$this->rfr2b_page_list_recursive($p->ID,$exclude,$selected,$show_in_page,$page_name).'</li>';
			
			}
			
			$page_count++;
			}
			$str .= '
		</ul>';
			return $str;
		}
	}
	
	/**
	 * Category Recursive
	 */

	function rfr2b_cat_list_recursive($parentid=0,$selected=array(),$display_in_cat,$cat_page_name){
		$cats = get_categories('hide_empty=0&child_of='.$parentid.'&parent='.$parentid);
		if(count($cats) > 0){
			$str = '
				<ul style="padding:10px 0px 0px 20px;">';
			$cat_count =1;	
			$total_categ = count($cats);
			foreach($cats as $c){
				$sel = false;
				if(isset($selected) && in_array($c->cat_ID,$selected))
					$sel = true;
					
				if( $cat_count > 4 ) { 
				if( $cat_count == 5 ) {
					$str .='<a style="cursor:pointer; color:#0033CC" onclick="elbp_catpage_openit(\''.$cat_page_name.'_openit\',\''.$cat_page_name.'_closeit\')" id="'.$cat_page_name.'_closeit"><strong>More...</strong></a>';
					$str .='<span id="'.$cat_page_name.'_openit" style="display:none;">';
				} 
				
				$str .= '
			<li><input type="checkbox" name="rfr2b['.$display_in_cat.'][]" value="'.$c->cat_ID.'" id="catid_'.$c->cat_ID.'"'.(($sel)?' checked="checked"':'').' /> <label for="catid_'.$c->cat_ID.'">'.wp_specialchars($c->cat_name).'</label>'.$this->rfr2b_cat_list_recursive($c->cat_ID,$selected,$display_in_cat,'').'</li>';
			
				if( $total_categ == $cat_count ) {
					$str .='<a style="cursor:pointer; color:#0033CC" onclick="elbp_catpage_closeit(\''.$cat_page_name.'_openit\',\''.$cat_page_name.'_closeit\')" ><strong>Close</strong></a></span>';
				}
			
			
			} else if( $cat_count <= 4 ) {	
				$str .= '
					<li><input type="checkbox" name="rfr2b['.$display_in_cat.'][]" value="'.$c->cat_ID.'" id="catid_'.$c->cat_ID.'"'.(($sel)?' checked="checked"':'').' /> <label for="catid_'.$c->cat_ID.'">'.wp_specialchars($c->cat_name).'</label>'.$this->rfr2b_cat_list_recursive($c->cat_ID,$selected,$display_in_cat,'').'</li>';
			}	
				
			$cat_count++;	
				
				}
			$str .= '</ul>';
			return $str;
		}
		return '';
	}
	
	
	/**
	 * Show Page List
	 */
	 
	function rfr2b_page_list( $display_in_page, $selected_pageid ){   // $display_in_page, $selected_pageid
		
		$ex_pages = '';
		$str = '<ul class="'.$rfr2b_class_showlist.'">
			    <li>';
				
		$recursive_page_name = $display_in_post.'12';
		$str .= '<li style="background-color:#F9F8F3; padding:10px 10px 10px 10px; -moz-border-radius: 8px; -khtml-border-radius: 8px; -webkit-border-radius: 8px;"><strong>Pages</strong>:'.$this->rfr2b_page_list_recursive(0,$ex_pages,$selected_pageid,$display_in_page,$recursive_page_name).'</li>';
		
		$str .= '</ul>';
		echo $str;
	}
	
	/**
	 * Target Your Display
	 */
	
	function rfr2b_target_display($display_in_all,$display_in_home,$display_in_post,$display_in_archive,$display_in_search,$display_in_other,$showOnPostWithID,$dontShowOnPostWithID,$display_optin_in_cat,$display_in_cat,$display_in_page,$rfr2b_class_showlist,$display_everywhere,$chk_in_all,$chk_in_home,$chk_in_post,$chk_in_arch,$chk_in_search,$chk_in_other, $showOnPostWithIDValue, $dontShowOnPostWithIDValue, $selected_pageid ,$selected_display_catIN,$selected_in_cat,$display_selected){
	
		$ex_pages = '';
		$catstr = ''; $selectedcat = isset($selected_display_catIN) ? $selected_display_catIN : 0;
		$opts = array('Selected Category');
		//$opts = array('Both','Category page','Post page within the categories');
		foreach($opts as $a => $b){
			$catstr .= '
					<option value="'.$a.'"'.(($a==$selectedcat)?' selected="selected"':'').'>'.$b.'</option>';
		}
		
		$recursive_cat_name = $display_in_post.'65';
		$cats = $this->rfr2b_cat_list_recursive(0,$selected_in_cat,$display_in_cat,$recursive_cat_name);
		
		$str = '<ul class="'.$rfr2b_class_showlist.'">
			    <li>';
				
				
		if( $display_selected == 1 ) {
			$str .= '	<div class="innerhide" style="background-color:#F9F8F3; padding:10px 10px 10px 10px; -moz-border-radius: 8px; -khtml-border-radius: 8px; -webkit-border-radius: 8px;">
					<table cellspacing="1" cellpadding="3" border="0" align="center" width="100%" >
					<tbody><tr><td valign="top">
					<table cellpadding="3" cellspacing="1" border="0" width="100%" style="padding:0;">
						<tr>
						  <td width="8%"><input type="checkbox" name="rfr2b['.$display_in_all.']" id= "'.$display_everywhere.'" '.$chk_in_all.' value="all"  /> <label for="display_rss_new_in_all">All RSS Posts</label>  </td>
						</tr>
				   </table> 
					</td></tr>
					</tbody></table>
				</div> 
			';
		} else {	
			$str .= '<div class="innerhide" style="background-color:#F9F8F3; padding:10px 10px 10px 10px; -moz-border-radius: 8px; -khtml-border-radius: 8px; -webkit-border-radius: 8px;">
					<table cellspacing="1" cellpadding="3" border="0" align="center" width="100%" >
					<tbody><tr><td valign="top">
					<table cellpadding="3" cellspacing="1" border="0" width="100%" style="padding:0;">
						<tr>
						  <td width="8%"><input type="checkbox" name="rfr2b['.$display_in_all.']" id= "'.$display_everywhere.'" '.$chk_in_all.' value="all"  /> <label for="display_rss_new_in_all">All RSS Posts</label>  </td>
							<td width="15%">&nbsp;</td>
						</tr>
				   </table> 
					</td></tr>
					</tbody></table>
				</div>';
		}	
		$str .= '</li>
			<li>
				<div style="padding:12px 4px 15px 4px; ">
					Show on these Posts only: <input name="rfr2b['.$showOnPostWithID.']" type="text" style="width:150px;" value="'.$showOnPostWithIDValue.'" />
					<small style="color:#999999; font-size:xx-small;">(Enter the post ID&prime;s separated by commas)</small> 
				</div>
			</li>
			<li>
				<div style="padding:5px 4px 15px 4px; ">
					<b>Do not show</b> on these Posts: <input name="rfr2b['.$dontShowOnPostWithID.']" type="text" style="width:150px;" value="'.$dontShowOnPostWithIDValue.'" />
					<small style="color:#999999; font-size:xx-small;">(Enter the post ID&prime;s separated by commas)</small> 
				</div>
			</li>';
			
		$recursive_page_name = $display_in_post.'12';
		$str .= '<small style="color:#FF0000;">Before you select pages, Please don\'t forget to ACTIVATE pages from <strong>Global RSS Campaign point Number 2</strong>  </small>';
		$str .= '<li style="background-color:#F9F8F3; padding:10px 10px 10px 10px; -moz-border-radius: 8px; -khtml-border-radius: 8px; -webkit-border-radius: 8px;"><strong>Pages</strong>:'.$this->rfr2b_page_list_recursive(0,$ex_pages,$selected_pageid,$display_in_page,$recursive_page_name).'</li>';
		
		if( !empty($cats) && $display_selected != 1 ){
			$str .= '
				<li><label><strong><br>Categories</strong>:&nbsp;</label>
					<label for="rfr2b_show_caton">Show on:</label>&nbsp;
					<select name="rfr2b['.$display_optin_in_cat.']" >'.$catstr.'
					</select>
					'.$cats.'
				</li>';
		}
		
		$str .= '</ul>';
		echo $str;
	}
	
	function rfr2b_chkRSS(){ 
		$rss_use_excerptChk = get_option( 'rss_use_excerpt' );
		if( $rss_use_excerptChk == 1 ) $displayMsg = 1;
		else if( $rss_use_excerptChk == 0 ) $displayMsg = 2;
		else  $displayMsg = 2;
		return $displayMsg;
	}
	

} // Eof Class

$WpSmartApps_ReadersFromRss2BlogPlugin = new WpSmartApps_ReadersFromRss2BlogPlugin();

// Widget
$chk_isrfr2b_pluginreg = get_option('rfr2b_activate');
if ( intval($chk_isrfr2b_pluginreg) == 22191 ) { 

function rfr2b_displayFeedburnerOptinForm( $sidebar_subscribe_main_widget_title, $sidebar_subscribe_title, $sidebar_subscribe_body, $sidebar_feedburner_title_name, $sidebar_subscribe_footer, $before_widget='', $after_widget='', $before_title='', $after_title='' ) {
	if ( !is_admin() ) {
		echo $before_widget;
		if ( trim( $sidebar_subscribe_main_widget_title ) != '' ) echo $before_title.$sidebar_subscribe_main_widget_title.$after_title;
		?>
		
<div style="border-top:4px solid #C3C3C3; line-height:24px; background:#F4F4F4; padding:5px 5px 5px 5px; background:#F4FCFE;">
	<h2 align="left" style="font-family: HelveticaNeue-Light,Helvetica Neue Light,Helventica,sans-serif !important; font-size: 16px; font-weight: bold; line-height: 24px; color:#0067B0; color: #333; font-weight: bold;margin: 0 0 0.4em;  text-shadow: 1px 1px 3px #DDDDDD;  -moz-text-shadow: 1px 1px 3px #DDDDDD; -webkit-text-shadow: 1px 1px 3px #DDDDDD; padding-left:5px;"><?php echo $sidebar_subscribe_title; ?></h2>
	<p style="padding-left:5px; font-family: Lucida Grande,Lucida Sans,sans-serif; font-size: 11px; line-height: 19px; color:#444; font-weight:normal; text-shadow:none;  -moz-text-shadow: none; -webkit-text-shadow:none; text-align:left; padding-bottom:0px; ">
	<?php echo $sidebar_subscribe_body; ?>
	</p>
	<form id="feedburner-zframefeedburner" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=<?php echo $sidebar_feedburner_title_name; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
	<input type="text" value="Your email here" onfocus="if(this.value=='Your email here')this.value='';" onblur="if(this.value=='')this.value='Your email here';" id="email-zframefeedburner" name="email" style="width:95%; background:#FFFFCC; border:1px solid #BEBEBE;">
	<input type="hidden" value="<?php echo $sidebar_feedburner_title_name; ?>" name="uri"/>
	<input type="hidden" name="loc" value="en_US"/>
	<input type="submit" id="imageField" name="imageField" value="Subscribe" alt="Submit" style="background-position:top left;background-repeat:repeat-x;background:#0057ac;border:1px solid #0057ac;color:#fff;text-decoration:none;font-style:normal;font-weight:normal;font-size:14px;font-family:Verdana, sans-serif; overflow:visible; border:0px; height:30px; cursor:pointer;
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	font-weight: bold;
	text-shadow: 1px 1px 3px #000;  
	-moz-text-shadow: 1px 1px 3px #000; 
	-webkit-text-shadow: 1px 1px 3px #000; width:90%;   
	 -moz-border-radius: 3px;
   -khtml-border-radius: 3px;
   -webkit-border-radius: 3px;
   ">
	</form>
	<p style="padding-left:5px; font-family: Lucida Grande,Lucida Sans,sans-serif; font-size: 11px; line-height: 19px; color:#444; font-weight:normal; text-shadow:none;  -moz-text-shadow: none; -webkit-text-shadow:none; text-align:left; ">
	<?php echo $sidebar_subscribe_footer; ?>
	</p>
</div>		
		<?php 
		echo $after_widget;
	}
}

if ( $wp_version >= 2.8 ) {
	class ReadersFromRSS2BlogWidget extends WP_Widget {
		function ReadersFromRSS2BlogWidget() {
			$widget_ops = array(
				'classname' => 'widget_rfr2b', 
				'description' => 'Readers From RSS 2 Blog' 
				);
			$control_ops = array (
				'width' => '380', 
				/*'height' => '400'*/
				);
			$this->WP_Widget('rfr2bFeedburner', 'Readers From RSS 2 Blog', $widget_ops, $control_ops);
		}
		function widget( $args, $instance ) {		
			extract( $args );
			$sidebar_subscribe_main_widget_title = $instance['rfr2b_subscribe_main_widget_title'];
			$sidebar_subscribe_title = $instance['rfr2b_subscribe_widget_title'];
			$sidebar_subscribe_body = $instance['rfr2b_subscribe_widget_body'];
			$sidebar_feedburner_title_name = $instance['rfr2b_feedburner_title_name'];
			$sidebar_subscribe_footer = $instance['rfr2b_subscribe_widget_footer'];
			// Eof global data
			if( $instance['rfr2b_sidebar_feedburner_widget'] == 1 ) {
				rfr2b_displayFeedburnerOptinForm( $sidebar_subscribe_main_widget_title, $sidebar_subscribe_title, $sidebar_subscribe_body, $sidebar_feedburner_title_name, $sidebar_subscribe_footer, $before_widget, $after_widget, $before_title, $after_title );
			}
		}
		function update( $new_instance, $old_instance ) {				
			global $wp_version;
			return $new_instance;
		}
		function form( $instance ) {
			error_reporting(E_ALL ^ E_NOTICE); 
			global $wp_version, $wpdb;
			?>
			<div style="background-color:#f8f8f8; padding:3px;">
			
	  			<div style="padding:8px 8px 8px 8px;">
				<strong>Widget Title:</strong> <br><input type="text" class="widefat" name="<?php echo $this->get_field_name("rfr2b_subscribe_main_widget_title"); ?>" id="<?php echo $this->get_field_id('rfr2b_subscribe_main_widget_title'); ?>" value="<?php echo $instance['rfr2b_subscribe_main_widget_title']; ?>" style="width:310px;" />
				</div >
			
	  			<div style="padding:8px 8px 8px 8px;">
				<strong>Title:</strong> <br><input type="text" class="widefat" name="<?php echo $this->get_field_name("rfr2b_subscribe_widget_title"); ?>" id="<?php echo $this->get_field_id('rfr2b_subscribe_widget_title'); ?>" value="<?php echo $instance['rfr2b_subscribe_widget_title']; ?>" style="width:310px;" />
				</div >

	  			<div style="padding:8px 8px 8px 8px;">
				<strong>Body Text:</strong> <br><textarea name="<?php echo $this->get_field_name("rfr2b_subscribe_widget_body"); ?>" id="<?php echo $this->get_field_id('rfr2b_subscribe_widget_body'); ?>" rows="4" class="widefat" ><?php echo htmlentities($instance['rfr2b_subscribe_widget_body']); ?></textarea>
				</div>
				
	  			<div style="padding:8px 8px 8px 8px;">
				<strong>Your Feedburner Title: (IMP)**</strong> <br><input type="text" class="widefat" name="<?php echo $this->get_field_name("rfr2b_feedburner_title_name"); ?>" id="<?php echo $this->get_field_id('rfr2b_feedburner_title_name'); ?>" value="<?php echo $instance['rfr2b_feedburner_title_name']; ?>" style="width:310px;" />
				</div>
				
	  			<div style="padding:8px 8px 8px 8px;">
				<strong>Footer Text:</strong> <br><textarea name="<?php echo $this->get_field_name("rfr2b_subscribe_widget_footer"); ?>" id="<?php echo $this->get_field_id('rfr2b_subscribe_widget_footer'); ?>" rows="4" class="widefat" ><?php echo htmlentities($instance['rfr2b_subscribe_widget_footer']); ?></textarea>
				</div>
				
			</div>	
				<input type="hidden" id="<?php echo $this->get_field_id('rfr2b_sidebar_feedburner_widget'); ?>" name="<?php echo $this->get_field_name('rfr2b_sidebar_feedburner_widget'); ?>" value="1" />	
			<?php
		}
	}
	add_action('widgets_init', create_function('', 'return register_widget("ReadersFromRSS2BlogWidget");'));
}

}

?>