<?php
/*
Plugin Name: Publish And Redirect To Add New Post
Plugin URI: http://www.mindstien.com
Description: By default when you add new post and hit 'publish' or 'save draft' button, wordpress redirects to 'post edit screen' of the same post, but this little plugin will help you go to add new post screen each time you add new post to help you keep adding multiple posts more faster.
Version: 1.5
Author: Mindstien Technologies
Author URI: http://www.mindstien.com
*/

add_action( 'post_submitbox_misc_actions', 'prn_post_submitbox_misc_actions_function',999);
add_action( 'admin_head', 'prn_admin_head_function');
add_action( 'redirect_post_location', 'prn_wp_insert_post_function');

function prn_post_submitbox_misc_actions_function()
{
	global $post;
	//echo "<pre>".print_r($post,true)."</pre>";
	echo "<div style='padding:5px;'>
	<input type='hidden' name='prn_redirect_post_type' id='prn_redirect_post_type' value='".$post->post_type."'>
	<input type='hidden' name='prn_redirect' id='prn_redirect' value='no'>
	<input type='button' id='prn_draft' name='submit-new' value='Save & Add New' class='button button-primary button-large prn_button'>
	<input type='button' id='prn_visit' name='submit-new' value='Publish & Visit Now' class='button button-primary button-large prn_button'>
	<input type='button' id='prn_publish' name='submit-new' value='Publish & Add New' class='button button-primary button-large prn_button'>
	</div>";
}
function prn_admin_head_function()
{
	?>
	<style>
		.prn_button
		{
			line-height: 14px !important;
			padding:5px !important;
			width:30% !important;
			white-space: normal !important;
			height:auto !important;
		}
	</style>
	<script>
		jQuery(document).ready(function() {
			jQuery('#prn_publish').click(function(){
				jQuery('#prn_redirect').val('yes');
				jQuery('#publish').trigger('click');
			});
			
			jQuery('#prn_draft').click(function(){
				jQuery('#prn_redirect').val('yes');
				jQuery('#save-post').trigger('click');
			});
			
			jQuery('#prn_visit').click(function(){
				jQuery('#prn_redirect').val('visit');
				jQuery('#publish').trigger('click');
			});
		
		});
	</script>
	
	<?php
}
function prn_wp_insert_post_function($url)
{
	if(isset($_POST['prn_redirect']) AND $_POST['prn_redirect']=='yes')
	{
		wp_redirect(admin_url('post-new.php?post_type='.$_POST['prn_redirect_post_type']));
		die();
	}
	elseif(isset($_POST['prn_redirect']) AND $_POST['prn_redirect']=='visit')
	{
		wp_redirect(get_permalink($_POST['post_ID']));
		die();
	}
	return $url;
}
?>