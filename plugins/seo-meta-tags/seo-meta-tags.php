<?php
/*
Plugin Name: Seo Meta Tags
Plugin URI: http://wpapi.com/
Description: This plugin will add post excerpt as a meta tags of each individual post. 
License: GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl.html
Version: 1.4
Author: Purab Kharat
Author URI: http://wpapi.com
*/

include_once 'seometa_site_admin_options.php';
include_once 'seometatags_webmastertool.php';
include_once 'seometatag_xmlsitemap.php';
include_once 'seometatag_social.php';

define(SMT_HOME_KEYWORDS,'smt_home_keywords');
define(SMT_HOME_DESCRIPTION,'smt_home_description');

// Hook for adding admin menus
add_action('admin_menu', 'seometatags_pages');

// action function for above hook
//help from http://codex.wordpress.org/Administration_Menus
function seometatags_pages() {
    global $submenu;
    // Add a new top-level menu (ill-advised):
    add_menu_page(__('Seo Meta Tags','menu-seometatags'), __('Seo Meta Tags','menu-seometatags'), 'manage_options', 'seometatags-dashboard', 'seometa_site_admin_options' );

    // Add a submenu to the custom top-level menu:
    add_submenu_page('seometatags-dashboard', __('Webmaster Tools','menu-seometatags'), __('Webmaster Tools','menu-seometatags'), 'manage_options', 'seometatag-webmaster-tool', 'seometatag_webmaster_tool');

    // Add a second submenu to the custom top-level menu:
    add_submenu_page('seometatags-dashboard', __('Social Information','menu-seometatags'), __('Social Info','menu-seometatags'), 'manage_options', 'seometatag-social', 'seometatag_social');
    
    // Add a second submenu to the custom top-level menu:
    add_submenu_page('seometatags-dashboard', __('XML sitemap','menu-seometatags'), __('XML sitemap','menu-seometatags'), 'manage_options', 'seometatag-xmlsitemap', 'seometatag_xmlsitemap');
    $submenu['seometatags-dashboard'][0][0] = 'Dashboard';
}

/*
 * Ref taken - http://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
 */
function load_smt_wp_admin_style() {
    if(is_admin()){
        wp_register_style( 'custom_smt_admin_css', plugins_url() . '/seo-meta-tags/smt_custom.css', false, '1.0.0' );
        wp_enqueue_style( 'custom_smt_admin_css' );
    }
}
add_action( 'admin_enqueue_scripts', 'load_smt_wp_admin_style' );

/*
 * Add seo meta tags to wordpress site
 */
add_action( 'wp_head', 'seo_meta_tags_hook' );
function seo_meta_tags_hook()
{   
    if (is_single() || is_page() )
    {   
        get_seometatags_common();
        get_seometatags_for_posts();        
    }
    else
    {
        get_seometatags_homepage();
        get_seometatags_common();
    }   
}

function get_seometatags_homepage() {
    $seo_meta_tags_description=  get_option(SMT_HOME_DESCRIPTION);
    $seo_meta_tags_keywords=  get_option(SMT_HOME_KEYWORDS);        
    echo '<meta name="description" content="'.$seo_meta_tags_description.'" />
<meta name="keywords" content="'.$seo_meta_tags_keywords.'" />';
}

function get_seometatags_common() {
    echo '<meta property="og:locale" content="en_US" />
<link rel="publisher" href="'.get_option('smt_google_publisher_page').'" />
<meta name="google-site-verification" content="'.get_option('smt_google_varification').'" />
<meta name="msvalidate.01" content="'.get_option('smt_bing_webmaster').'" />
<meta name="alexaVerifyID" content="'.get_option('smt_alexa_varification').'" />
<meta name="yandex-verification" content="'.get_option('smt_yandex_webmaster').'" />
<meta name="p:domain_verify" content="'.get_option('smt_pinterest_webmaster').'" />
<meta property="og:site_name" content="'.  get_bloginfo('name').'" />
<meta property="article:publisher" content="'.get_option('smt_facebookpage_url').'" />
<meta property="article:author" content="'.get_option('smt_facebookpage_url').'" />
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:site" content="@'.get_option('smt_twitter_username').'"/>
<meta name="twitter:domain" content="'.get_option('smt_twitter_username').'"/>
<meta name="twitter:creator" content="@'.get_option('smt_twitter_username').'"/>    
<meta name="robots" content="index, follow" />
<meta name="revisit-after" content="21 days" />
<meta name="creator" content="Name,Designer,Email Address,or Company" />
<meta name="publisher" content="Designer, Company or Website Name" />
';
}

function get_seometatags_for_posts() {   
    global $post;    
    //fetch seo excerpt
    $meta_seo_excerpt = (!empty( $post->post_excerpt ) ) ? strip_tags( $post->post_excerpt ) : substr( strip_shortcodes( strip_tags( $post->post_content )), 0, 155 );    
    $meta_seo_excerpt_final= preg_replace('/(\s\s+|\t|\n)/', ' ', $meta_seo_excerpt);
    //get current post category
    $current_cat='';
    $category = get_the_category($post->ID);     
    if(is_array($category))
        $current_cat= !empty($category[0]->cat_name) ? $category[0]->cat_name : '';
    
    //get current tags
    $tags = get_the_tags();
    $seometa_tags='';
    if ($tags) {
        $tag_array = array();
        foreach($tags as $tag) {
            //$tag_id = $tag->term_id;            
            $tag_array[]=$tag->name;
        }
        $seometa_tags = implode(", ", $tag_array);        
    }    
    
    $smt_feat_image_full = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );    
    $smt_feat_image_thumb= wp_get_attachment_thumb_url( get_post_thumbnail_id( $post->ID ) );
    
    echo '<meta property="og:type" content="article" />
<meta property="og:title" content="'.  $post->post_title.'" />
<meta property="og:description" content="'.  $meta_seo_excerpt_final.'" />
<meta property="og:url" content="'.  get_permalink( $post->ID).'" />
<meta property="article:section" content="'.$current_cat.'"  />
<meta property="article:published_time" content="'.  $post->post_date.'"/>
<meta property="article:modified_time" content="'.  $post->post_modified .'" />
<meta property="og:updated_time" content="'.  $post->post_modified_gmt .'" />
<meta property="og:image" content="'.$smt_feat_image_thumb.'" />
<meta property="og:image" content="'.$smt_feat_image_full.'" />
<meta name="twitter:image:src" content="'.$smt_feat_image_thumb.'" />
<meta itemprop="name" content="'.  $post->post_title.'">
<meta itemprop="description" content="'.  $meta_seo_excerpt_final.'">
<meta itemprop="image" content="'.$smt_feat_image_thumb.'" >
<meta name="classification" content="'.$seometa_tags.'" />
<meta name="distribution" content="'.$current_cat.'" />
<meta name="rating" content="'.$current_cat.'" />
';
}

function smt_input_text_field($smt_fieldname, $smt_fieldlabel,$helptext=null) {    
   $smt_fieldname_value= get_option($smt_fieldname);
   $smt_input = "<tr valign='top'>
			<th scope='row'>$smt_fieldlabel</th>
				<td>
				<input style='width:400px' name='$smt_fieldname' type='text' value='$smt_fieldname_value' />
				</td>
                                <label for='$smt_fieldname'><font color='blue'>$helptext</font></label>
			</tr>";
   echo $smt_input;
}

function smt_input_textarea_field($smt_fieldname, $smt_fieldlabel,$helptext=null) {    
   $smt_fieldname_value= get_option($smt_fieldname);
   $smt_input = "<tr valign='top'>
			<th scope='row'>$smt_fieldlabel</th>
				<td>
                                 <textarea name='$smt_fieldname' rows='4' cols='50'>$smt_fieldname_value</textarea>
				</td>
                                <label for='$smt_fieldname'><font color='blue'>$helptext</font></label>
			</tr>";
   echo $smt_input;
}

