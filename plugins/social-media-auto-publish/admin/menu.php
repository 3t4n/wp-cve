<?php
if( !defined('ABSPATH') ){ exit();}
add_action('admin_menu', 'xyz_smap_menu');

function xyz_smap_add_admin_scripts()
{
	wp_enqueue_script('jquery');
	wp_register_script( 'xyz_notice_script_smap', plugins_url('social-media-auto-publish/js/notice.js') );
	wp_enqueue_script( 'xyz_notice_script_smap' );
	$smap_smapsolution_var="SMAPSolutions";
	$smap_xyzscripts_var="xyzscripts";
	wp_localize_script('xyz_notice_script_smap','xyz_script_smap_var',array(
	    'alert1' => __('Please check whether the email is correct.','social-media-auto-publish'),
	    'alert2' => __('Select atleast one list.','social-media-auto-publish'),
	    'alert3' => __('You do not have sufficient permissions','social-media-auto-publish'),
	    'html1'  => sprintf(__('Account details successfully deleted from %s','social-media-auto-publish'),$smap_smapsolution_var),
	    'html2'  => __('Thank you for enabling backlink !','social-media-auto-publish'),
	    'html3'  => sprintf(__('Please connect your %s member account','social-media-auto-publish'),$smap_xyzscripts_var),
	    'html4'  => sprintf(__('In-active Facebook account successfully deleted from %s','social-media-auto-publish'),$smap_smapsolution_var),
	    'html5'  => sprintf(__('In-active LinkedIn account successfully deleted from %s','social-media-auto-publish'),$smap_smapsolution_var),
	    'html6'  => sprintf(__('In-active Twitter account successfully deleted from %s','social-media-auto-publish'),$smap_smapsolution_var),
	    'html7'  => sprintf(__('In-active Instagram account successfully deleted from %s','social-media-auto-publish'),$smap_smapsolution_var),
	));
	wp_register_style('xyz_smap_style', plugins_url('social-media-auto-publish/css/style.css'));
	wp_enqueue_style('xyz_smap_style');
	wp_register_style( 'xyz_smap_font_style',plugins_url('social-media-auto-publish/css/font-awesome.min.css'));
	wp_enqueue_style('xyz_smap_font_style');
}

add_action("admin_enqueue_scripts","xyz_smap_add_admin_scripts");


function xyz_smap_menu()
{
	add_menu_page('Social Media Auto Publish - Manage settings', 'Social Media Auto Publish', 'manage_options', 'social-media-auto-publish-settings', 'xyz_smap_settings',plugin_dir_url( XYZ_SMAP_PLUGIN_FILE ) . 'images/smap.png');
	$page=add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - Manage settings', __('Settings','social-media-auto-publish'), 'manage_options', 'social-media-auto-publish-settings' ,'xyz_smap_settings');
	if(get_option('xyz_smap_xyzscripts_hash_val')!=''&& get_option('xyz_smap_xyzscripts_user_id')!='')
	   add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - Manage Authorizations', __('Manage Authorizations','social-media-auto-publish'), 'manage_options', 'social-media-auto-publish-manage-authorizations' ,'xyz_smap_manage_authorizations');
	   add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - Logs', __('Logs','social-media-auto-publish'), 'manage_options', 'social-media-auto-publish-log' ,'xyz_smap_logs'); 
	   add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - About', __('About','social-media-auto-publish'), 'manage_options', 'social-media-auto-publish-about' ,'xyz_smap_about');
	   add_submenu_page('social-media-auto-publish-settings', 'Social Media Auto Publish - Suggest Feature', __('Suggest a Feature','social-media-auto-publish'), 'manage_options', 'social-media-auto-publish-suggest-features' ,'xyz_smap_suggest_feature');
}


function xyz_smap_settings()
{
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);	
	$_POST = xyz_trim_deep($_POST);
	$_GET = xyz_trim_deep($_GET);
	
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/settings.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}



function xyz_smap_about()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/about.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}

function xyz_smap_logs()
{
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);
	$_POST = xyz_trim_deep($_POST);
	$_GET = xyz_trim_deep($_GET);
	
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/logs.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}
function xyz_smap_suggest_feature()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/suggest_feature.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}
function xyz_smap_manage_authorizations()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/manage-auth.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}
add_action('wp_head', 'xyz_smap_insert_og_twitter_card');
function xyz_smap_insert_og_twitter_card(){

 	global $post;
 	if (empty($post))
 		$post=get_post();
 		if (!empty($post)){
	$postid= $post->ID;
	$excerpt='';$attachmenturl='';$name='';
	if(isset($postid ) && $postid>0)
	{
	    $get_post_meta_insert_og=0;
	    $get_post_meta_insert_twitter_card=0;
		$xyz_smap_apply_filters=get_option('xyz_smap_std_apply_filters');
		$get_post_meta_future_tw_data=get_post_meta($postid,"xyz_smap_tw_future_to_publish",true);
		$get_post_meta_future_fb_data=get_post_meta($postid,"xyz_smap_fb_future_to_publish",true);
		$get_post_meta_future_ln_data=get_post_meta($postid,"xyz_smap_ln_future_to_publish",true);//echo "<pre>";print_r($get_post_meta_future_ln_data);die;
		$xyz_smap_free_enforce_twitter_cards=get_option('xyz_smap_free_enforce_twitter_cards');//echo $xyz_smap_free_enforce_twitter_cards;die;
		$xyz_smap_free_enforce_og_tags=get_option('xyz_smap_free_enforce_og_tags');//echo $xyz_smap_free_enforce_og_tags;die;
		$get_post_meta_insert_og=get_post_meta($postid,"xyz_smap_insert_og",true); 
		$get_post_meta_insert_twitter_card=get_post_meta($postid,"xyz_smap_insert_twitter_card",true);
		if ((!empty($get_post_meta_future_fb_data) && ( $xyz_smap_free_enforce_og_tags==1 ))|| (!empty($get_post_meta_future_ln_data) && ( $xyz_smap_free_enforce_og_tags==1 )) 
		    || (!empty($get_post_meta_future_tw_data) && ( $xyz_smap_free_enforce_twitter_cards==1 )))
		{
			$ar2=explode(",",$xyz_smap_apply_filters);
			$excerpt = $post->post_excerpt;
			if(in_array(2, $ar2))
				$excerpt = apply_filters('the_excerpt', $excerpt);
				$excerpt = html_entity_decode($excerpt, ENT_QUOTES, get_bloginfo('charset'));
				$excerpt = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $excerpt);
				if($excerpt=="")
				{
					$content = $post->post_content;
					if(in_array(1, $ar2))
						$content = apply_filters('the_content', $content);
						if($content!="")
						{
							$content1=$content;
							$content1=strip_tags($content1);
							$content1=strip_shortcodes($content1);
							$content1 = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $content1);
							$content1=  preg_replace("/\\[caption.*?\\].*?\\[.caption\\]/is", "", $content1);
							$content1 = preg_replace('/\[.+?\]/', '', $content1);
							$excerpt=implode(' ', array_slice(explode(' ', $content1), 0, 50));
						}
				}
				else
				{
					$excerpt=strip_tags($excerpt);
					$excerpt=strip_shortcodes($excerpt);
				}
				$excerpt=str_replace("&nbsp;","",$excerpt);
				$name = $post->post_title;
				if(in_array(3, $ar2))
					$name = apply_filters('the_title', $name,$postid);
					$name = html_entity_decode($name, ENT_QUOTES, get_bloginfo('charset'));
					$name=strip_tags($name);
					$name=strip_shortcodes($name);
			$attachmenturl=xyz_smap_getimage($postid, $post->post_content);
			if ((($get_post_meta_insert_og==1) && (strpos($_SERVER["HTTP_USER_AGENT"], "facebookexternalhit/") !== false || strpos($_SERVER["HTTP_USER_AGENT"], "Facebot") !== false 
			    || strpos($_SERVER["HTTP_USER_AGENT"], "LinkedInBot") !== false))) 
			{
			if(!empty( $name ))
				echo '<meta property="og:title" content="'.$name.'" />';
			if (!empty($excerpt))
				echo '<meta property="og:description" content="'.$excerpt.'" />';
			if(!empty($attachmenturl))
				echo '<meta property="og:image" content="'.$attachmenturl.'" />';
				update_post_meta($postid, "xyz_smap_insert_og", "0");
		}
			if (($get_post_meta_insert_twitter_card==1) && strpos($_SERVER["HTTP_USER_AGENT"], "Twitterbot") !== false && ($xyz_smap_free_enforce_twitter_cards==1))
			{
			    echo '<meta name="twitter:card" content="summary_large_image" />';
			    if(!empty( $name ))
			        echo '<meta name="twitter:title" content="'.$name.'" />';
			        if (!empty($excerpt))
			            echo '<meta name="twitter:description" content="'.$excerpt.'" />';
			                if(!empty($attachmenturl))
			                    echo '<meta name="twitter:image" content="'.$attachmenturl.'" />';
	}
}
	}
}
}