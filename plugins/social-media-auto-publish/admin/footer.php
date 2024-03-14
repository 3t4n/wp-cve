<?php 
if( !defined('ABSPATH') ){ exit();}
?></div>
<?php
if(get_option('xyz_smap_premium_version_ads')==1){?>
<div id="xyz-wp-smap-premium">

	<div style="float: left; padding: 0 5px">
		<h2 style="vertical-align: middle;">
		<a target="_blank" href="https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/features"> 
		<?php $smap_plugin="XYZ WP SMAP Premium";
		      $smap_plugin_msg=sprintf(__('Fully Featured %s Plugin','social-media-auto-publish'),$smap_plugin); 
	     echo $smap_plugin_msg; ?> </a> - <?php _e('Just 39 USD','social-media-auto-publish'); ?>
		</h2>
	</div>
	<div class="xyz-smap-buy-now">
<a target="_blank" href="https://xyzscripts.com/members/product/purchase/XYZWPSMPPRE"><img 
            src="<?php  echo plugins_url("images/orange_buynow.png",XYZ_SMAP_PLUGIN_FILE); ?>">
		</a>
	</div>
	<div style="float: left; padding: 0 5px">
<h2 style="vertical-align: middle;text-shadow: 1px 1px 1px #686868">
( <a 	href="<?php echo admin_url('admin.php?page=social-media-auto-publish-about');?>"> <?php _e('Compare Features','social-media-auto-publish'); ?> </a>) 

</h2>		
	</div>
</div>
<?php }?>

<div style="clear: both;"></div>



<div style="width: 100%">
		
<div class="xyz_smap_social_media">
<h3 class="xyz_smap_inner_head"> <?php _e('Follow Us','social-media-auto-publish'); ?> </h3>
   <a target="_blank" href="http://facebook.com/xyzscripts" class="xyz_home_fbook"></a>
   <a target="_blank" href="http://twitter.com/xyzscripts" class="xyz_home_twitt"></a>
 <a style="margin-right:0px;" target="_blank" href="https://www.linkedin.com/company/xyzscripts" class="xyz_home_linkedin"></a>
</div>

<div class="xyz_smap_sugession">
<h3 class="xyz_smap_inner_head"> <?php _e('Support','social-media-auto-publish'); ?> </h3>
<p style="width:100%;"><a target="_blank" href="https://xyzscripts.com/donate/5" > <?php _e('Donate','social-media-auto-publish'); ?> </a></p>
<p style="width:100%;"><a target="_blank" href="https://xyzscripts.com/support/" > <?php _e('Contact Us','social-media-auto-publish'); ?> </a></p>
<p><a target="_blank" href="https://wordpress.org/support/plugin/social-media-auto-publish/reviews/" > <?php _e('Please Rate Us','social-media-auto-publish'); ?> 
 </a></p>
<a target="_blank" href="https://wordpress.org/support/plugin/social-media-auto-publish/reviews/" ><span class="xyz_new_star"></span></a>

<p style="width:100%;"><a target="_blank" href="https://wordpress.org/support/plugin/social-media-auto-publish/" > <?php _e('Create a ticket at Wordpress forum','social-media-auto-publish'); ?> </a></p>
</div>
<div class="xyz_smap_new_subscribe">
<h3 class="xyz_smap_inner_head"> <?php _e('Stay tuned for our updates','social-media-auto-publish'); ?> </h3>

<script language="javascript">
function check_email(emailString)
{
    var mailPattern = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,})$/;
    var matchArray = emailString.match(mailPattern);
    if (emailString.length == 0)
    return false;
       
    if (matchArray == null)    {
    return false;
    }else{
    return true;
    }
}

   
function verify_lists(form)
{
   
    var total=0;
    var checkBox=form['chk[]'];
   
    if(checkBox.length){
   
    for(var i=0;i<checkBox.length;i++){
    checkBox[i].checked?total++:null;
    }
    }else{
       
    checkBox.checked?total++:null;
       
    }
    if(total>0){
    return true;
    }else{
    return false;
    }

}
   
function verify_fields()
{

    if(check_email(document.email_subscription.email.value) == false){
    alert(xyz_script_smap_var.alert1);
    document.email_subscription.email.select();
    return false;
    }else if(verify_lists(document.email_subscription)==false){
    alert(xyz_script_smap_var.alert2);
    }
    else{
    document.email_subscription.submit();
    }

}
</script>
<?php global $current_user; wp_get_current_user(); ?>
<form action="https://xyzscripts.com/newsletter/index.php?page=list/subscribe" method="post" name="email_subscription" id="email_subscription" target="_blank">
<input type="hidden" name="fieldNameIds" value="1,">
<input type="hidden" name="redirActive" value="https://xyzscripts.com/subscription/pending/XYZWPSMPPRE">
<input type="hidden" name="redirPending" value="https://xyzscripts.com/subscription/active/XYZWPSMPPRE">
<input type="hidden" name="mode" value="1">
<input class="xyz_smap_name"  type="text" placeholder="<?php _e('Name','social-media-auto-publish'); ?>" name="field1" value="<?php
if ($current_user->user_firstname != "" || $current_user->user_lastname != "") 
{
    echo $current_user->user_firstname . " " . $current_user->user_lastname;
} 
else if (strcasecmp($current_user->display_name, 'admin')!=0 && strcasecmp($current_user->display_name , "administrator")!=0 ) 
{
    echo $current_user->display_name;
} 
else if (strcasecmp($current_user->user_login ,"admin")!=0 && strcasecmp($current_user->user_login , "administrator")!=0 ) 
{
    echo $current_user->user_login;
}
?>"  >

<input class="xyz_smap_email" name="email"
type="text" placeholder="<?php _e('Email','social-media-auto-publish'); ?>" value="<?php    echo $current_user->user_email; ?>" />

<input id="xyz_smap_submit_smap" class="sbmt_btn" type="submit" value="<?php _e('Subscribe','social-media-auto-publish'); ?>" name="Submit"  onclick="javascript: if(!verify_fields()) return false; " />

<input type="hidden" name="listName" value="7,1,"/>
</form>
</div>   
<div class="xyz_smap_inmotion">
   <a target="_blank" href="http://inmotion-hosting.evyy.net/c/1150074/260033/4222">
   <img src="<?php echo plugins_url()?>/social-media-auto-publish/images/xyz.png" class="xyz_inmotion_label"></a></div>
</div>

<div style="clear: both;"></div>
<div style="width: 100%">

<div class="xyz_our_plugins_new">
<p class="xyz_plugin_head"> <?php _e('Our Plugins','social-media-auto-publish'); ?>: </p>

    <a target="_blank"  href="https://wordpress.org/plugins/social-media-auto-publish/"><span>1</span>Social Media Auto Publish</a>
    <a target="_blank"  href="https://wordpress.org/plugins/facebook-auto-publish/"><span>2</span>WP2Social Auto Publish</a>
    <a target="_blank"  href="https://wordpress.org/plugins/twitter-auto-publish/"><span>3</span>WP Twitter Auto Publish</a>
    <a target="_blank"  href="https://wordpress.org/plugins/linkedin-auto-publish/"><span>4</span>WP to LinkedIn Auto Publish</a>
    <a target="_blank"  href="https://wordpress.org/plugins/insert-html-snippet/"><span>5</span>Insert HTML Snippet</a>
    <a target="_blank"  href="https://wordpress.org/plugins/insert-php-code-snippet/"><span>6</span>Insert PHP Code Snippet</a>
    <a target="_blank"  href="https://wordpress.org/plugins/contact-form-manager/"><span>7</span>Contact Form Manager</a>
    <a target="_blank"  href="https://wordpress.org/plugins/newsletter-manager/"><span>8</span>Newsletter Manager</a>
    <a target="_blank"  href="https://wordpress.org/plugins/lightbox-pop/"><span>9</span>Lightbox Pop</a>
    <a target="_blank"  href="https://wordpress.org/plugins/full-screen-popup/"><span>10</span>Full Screen Popup</a>
    <a target="_blank"  href="https://wordpress.org/plugins/popup-dialog-box/"><span>11</span>Popup Dialog Box</a>
    <a target="_blank"  href="https://wordpress.org/plugins/quick-bar/"><span>12</span>Quick Bar</a>
    <a target="_blank"  href="https://wordpress.org/plugins/quick-box-popup/"><span>13</span>Quick Box Popup</a>
    <a target="_blank"  href="https://wordpress.org/plugins/custom-field-manager/"><span>14</span> Custom Field Manager</a>
    <a target="_blank"  href="https://wordpress.org/plugins/wp-filter-posts/"><span>15</span>  WP Filter Posts</a>
    <a target="_blank"  href="https://wordpress.org/plugins/wp-gallery-manager/"><span>16</span>  WP Gallery Manager</a>
</div>
</div>
<div class="xyz_smap_poweredBy">  
<?php _e('Powered by','social-media-auto-publish'); ?> <a href="http://xyzscripts.com" target="_blank">XYZScripts</a>
</div>
<div style="clear: both;"></div>
<p style="clear: both;"></p>
