<?php
if( !defined('ABSPATH') ){ exit();}
add_action('admin_menu', 'xyz_lnap_menu');

function xyz_lnap_add_admin_scripts()
{
	wp_enqueue_script('jquery');
	wp_register_script( 'xyz_notice_script_lnap', plugins_url('linkedin-auto-publish/js/notice.js') );
	wp_enqueue_script( 'xyz_notice_script_lnap' );
	$lnap_smapsolution_var="SMAPSolutions";
	$lnap_xyzscripts_var="xyzscripts";
	wp_localize_script('xyz_notice_script_lnap','xyz_script_lnap_var',array(
	    'alert1' => __('Please check whether the email is correct.','linkedin-auto-publish'),
	    'alert2' => __('Select atleast one list.','linkedin-auto-publish'),
	    'alert3' => __('You do not have sufficient permissions','linkedin-auto-publish'),
	    'html1' => sprintf(__('Account details successfully deleted from %s','linkedin-auto-publish'),$lnap_smapsolution_var),
	    'html2' => sprintf(__('In-active LinkedIn account successfully deleted from %s','linkedin-auto-publish'),$lnap_smapsolution_var),    
	    'html3' => sprintf(__('Please connect your %s member account','linkedin-auto-publish'),$lnap_xyzscripts_var),
	    'html4' => __('Thank you for enabling backlink !','linkedin-auto-publish')
	    	    
	));
	wp_register_style('xyz_lnap_style', plugins_url('linkedin-auto-publish/css/style.css'));
	wp_enqueue_style('xyz_lnap_style');
	wp_register_style( 'xyz_lnap_font_style',plugins_url('linkedin-auto-publish/css/font-awesome.min.css'));
	wp_enqueue_style('xyz_lnap_font_style');
}

add_action("admin_enqueue_scripts","xyz_lnap_add_admin_scripts");

function xyz_lnap_menu()
{
	add_menu_page('LinkedIn Auto Publish - Manage settings', 'WP to LinkedIn Auto Publish', 'manage_options', 'linkedin-auto-publish-settings', 'xyz_lnap_settings',plugin_dir_url( XYZ_LNAP_PLUGIN_FILE ) . 'images/lnap.png');
	$page=add_submenu_page('linkedin-auto-publish-settings', 'LinkedIn Auto Publish - Manage settings', __('Settings','linkedin-auto-publish'), 'manage_options', 'linkedin-auto-publish-settings' ,'xyz_lnap_settings'); // 8 for admin
	add_submenu_page('linkedin-auto-publish-settings', 'Linkedin Auto Publish - Logs', __('Logs','linkedin-auto-publish'), 'manage_options', 'linkedin-auto-publish-log' ,'xyz_lnap_logs');
	if(get_option('xyz_lnap_xyzscripts_hash_val')!=''&& get_option('xyz_lnap_xyzscripts_user_id')!='')
		add_submenu_page('linkedin-auto-publish-settings', 'LinkedIn Auto Publish - Manage Authorizations', __('Manage Authorizations','linkedin-auto-publish'), 'manage_options', 'linkedin-auto-publish-manage-authorizations' ,'xyz_lnap_manage_authorizations');
	add_submenu_page('linkedin-auto-publish-settings', 'LinkedIn Auto Publish - About', __('About','linkedin-auto-publish'), 'manage_options', 'linkedin-auto-publish-about' ,'xyz_lnap_about'); // 8 for admin
	add_submenu_page('linkedin-auto-publish-settings', 'LinkedIn Auto Publish - Suggest Feature', __('Suggest a Feature','linkedin-auto-publish'), 'manage_options', 'linkedin-auto-publish-suggest-features' ,'xyz_lnap_suggest_feature');
}


function xyz_lnap_settings()
{
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);	
	$_POST = xyz_trim_deep($_POST);
	$_GET = xyz_trim_deep($_GET);
	
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/settings.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}



function xyz_lnap_about()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/about.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}


function xyz_lnap_logs()
{
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);
	$_POST = xyz_trim_deep($_POST);
	$_GET = xyz_trim_deep($_GET);

	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/logs.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}
function xyz_lnap_suggest_feature()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/suggest_feature.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}
function xyz_lnap_manage_authorizations()
{
	require( dirname( __FILE__ ) . '/header.php' );
	require( dirname( __FILE__ ) . '/manage-authorizations.php' );
	require( dirname( __FILE__ ) . '/footer.php' );
}
add_action('wp_head', 'xyz_lnap_insert_og_image_tag_for_ln');
function xyz_lnap_insert_og_image_tag_for_ln(){

	global $post;
	if (empty($post))
		$post=get_post();
		if (!empty($post) && get_option('xyz_lnap_enforce_og_tags')==1){
		$postid= $post->ID;
		$excerpt='';$attachmenturl='';$name='';
		if(isset($postid ) && $postid>0)
		{
			$xyz_lnap_apply_filters=get_option('xyz_lnap_apply_filters');
			$get_post_meta_insert_og=0;
			$get_post_meta_insert_og=get_post_meta($postid,"xyz_lnap_insert_og",true);
			if (($get_post_meta_insert_og==1) && (strpos($_SERVER["HTTP_USER_AGENT"], "LinkedInBot") !== false))
			{
			$ar2=explode(",",$xyz_lnap_apply_filters);
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
					$name = apply_filters('the_title', $name);
					$name = html_entity_decode($name, ENT_QUOTES, get_bloginfo('charset'));
					$name=strip_tags($name);
					$name=strip_shortcodes($name);

				$attachmenturl=xyz_lnap_getimage($postid, $post->post_content);
				if(!empty( $name ))
					echo '<meta property="og:title" content="'.$name.'" />';
				if (!empty($excerpt))
					echo '<meta property="og:description" content="'.$excerpt.'" />';
					if(!empty($attachmenturl))
						echo '<meta property="og:image" content="'.$attachmenturl.'" />';
						update_post_meta($postid, "xyz_lnap_insert_og", "0");
			}
		}
	}
}
?>