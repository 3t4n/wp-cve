<?php
if( !defined('ABSPATH') ){ exit();}
?>

<h1 style="visibility: visible;">WP to LinkedIn Auto Publish (V <?php echo xyz_lnap_plugin_get_version(); ?>)</h1>

<div style="width: 99%">
<p style="text-align: justify">
<?php $wp_lnap="WP to LinkedIn Auto Publish";
$lnap_pub_msg=sprintf( __('%s automatically publishes posts from your blog to your LinkedIn pages. It allows you to filter posts based on post-types and categories. %s is developed and maintained by','linkedin-auto-publish'),$wp_lnap,$wp_lnap); 
      echo $lnap_pub_msg; ?> <a href="http://xyzscripts.com">XYZScripts</a>.</p>

 

<p style="text-align: justify">
<?php $lnap_smap_url="https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/features";
	$lnap_smap_plugin = "XYZ Social Media Auto Publish";
	$lnap_feature_msg=sprintf( __('If you would like to have more features , please try <a href="%s" target="_blank">%s</a> which is a premium version of this plugin. We have included a quick comparison of the free and premium plugins for your reference.','linkedin-auto-publish'),$lnap_smap_url,$lnap_smap_plugin); 
	echo $lnap_feature_msg; ?>
	
</p>
 </div>
 <table class="xyz-premium-comparison" cellspacing=0 style="width: 99%;">
	<tr style="background-color: #EDEDED">
		<td><h2> <?php _e('Feature group','linkedin-auto-publish'); ?> </h2></td>
		<td><h2> <?php _e('Feature','linkedin-auto-publish'); ?> </h2></td>
		<td><h2> <?php _e('Free','linkedin-auto-publish'); ?> </h2>
		</td>
		<td><h2> <?php _e('Premium','linkedin-auto-publish'); ?> </h2></td>
		<td><h2> <?php $lnap_smap="SMAP";
		               $lnap_premium_msg=sprintf( __('%s Premium','linkedin-auto-publish'),$lnap_smap);
		               echo $lnap_premium_msg; ?>+</h2></td>
	</tr>
	<!-- Supported Media  -->
	<tr>
		<td rowspan="6"><h4> <?php _e('Supported Media','linkedin-auto-publish'); ?> </h4></td>
		<td> <?php _e('Facebook','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Twitter','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('LinkedIn','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Instagram','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Tumblr','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Pinterest','linkedin-auto-publish'); ?> <span style="color: #FF8000;font-size: 14px;font-weight: bold;">*</span></td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>

		<!-- Posting Options  -->
	<tr>
		<td rowspan="15"><h4> <?php _e('Posting Options','linkedin-auto-publish'); ?> </h4></td>
		<td> <?php _e('Publish to facebook pages','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
		<tr>
		<td> <?php _e('Publish to facebook groups','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish to twitter profile','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to linkedin profile','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	
	<tr>
		<td> <?php _e('Publish to linkedin company pages','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish to instagram Business accounts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
		<tr>
		<td> <?php _e('Publish to tumblr profile','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to pinterest boards','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to republish existing posts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to multiple social media accounts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Seperate message formats for publishing to multiple social media accounts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Save auto publish settings of individual posts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Hash Tags support for Facebook, Twitter, Linkedin, Instagram, Tumblr and Pinterest','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Option to use post tags as hash tags','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Enable/Disable SSL peer verification','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<!-- Image Options  -->
	
	<tr>
	<td rowspan="5"><h4> <?php _e('Image Options','linkedin-auto-publish'); ?> </h4></td>
		<td> <?php _e('Publish images along with post content','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	
	<tr>
		<td> <?php _e('Separate default image url for publishing to multiple social media accounts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
		<tr>
		<td> <?php _e('Option to specify preference from featured image, post content, post meta and open graph tags','linkedin-auto-publish'); ?></td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
		<tr>
		<td> <?php _e('Publish multiple images to facebook, tumblr, linkedin and twitter along with post content','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Option to specify multiphoto preference from post content and post meta','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
		<!-- Video Options  -->
	
	<tr>
	<td rowspan="4"><h4> <?php _e('Video/Audio Options','linkedin-auto-publish'); ?> </h4></td>
		<td> <?php _e('Publish video to facebook, tumblr, Linkedin, Instagram and twitter along with post content','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to specify preference from post content, post meta and open graph tags','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish audio to tumblr along with post content','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to specify audio preference from  post content, post meta and open graph tags','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
		
	<!-- Filter Options  -->
		
	<tr>
	<td rowspan="9"><h4> <?php _e('Filter Options','linkedin-auto-publish'); ?> </h4></td>
		<td> <?php _e('Filter posts to publish based on categories','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Filter posts to publish based on custom post types','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Filter posts to publish based on sticky posts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Configuration to enable/disable page publishing','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Category filter for individual accounts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Custom post type filter for individual accounts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Enable/Disable page publishing for individual accounts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Override auto publish scheduling for individual accounts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Override auto publish based on sticky posts for individual accounts','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<!-- Scheduling  -->
		
	<tr>
	<td rowspan="4"><h4> <?php _e('Scheduling','linkedin-auto-publish'); ?> </h4></td>
		<td> <?php _e('Instantaneous post publishing','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Scheduled post publishing using cron','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
    <tr>
		<td> <?php _e('Status summary of auto publish tasks by mail','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Configurable auto publishing time interval','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	
	
	
	<!-- Publishing History  -->
	<tr>
		<td rowspan="4"><h4> <?php _e('Publishing History','linkedin-auto-publish'); ?> </h4></td>
		<td> <?php _e('View auto publish history','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('View auto publish error logs','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to republish post','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to reschedule publishing','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<!-- Installation and Support -->
	<tr>
		<td rowspan="2"><h4> <?php _e('Installation and Support','linkedin-auto-publish'); ?> </h4></td>
		<td> <?php _e('Free Installation','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Privilege customer support','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<!-- Addons and Support -->
	<tr>
		<td rowspan="3"><h4> <?php _e('Addon Features','linkedin-auto-publish'); ?> </h4></td>
		<td> <?php _e('Advanced Autopublish Scheduler','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		</tr>
		<tr>
		<td> <?php _e('URL-Shortener','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
		<tr>
		<td> <?php _e('Privilege Management','linkedin-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td rowspan="3"><h4> <?php _e('Other','linkedin-auto-publish'); ?> </h4></td>
		<td><?php $lnap_smapsolution="SMAPSOLUTIONS"; 
		$lnap_package_msg=sprintf(__('%s API package for 1 year, worth 10 USD(1 linkedin account @ 25 api per day)','linkedin-auto-publish'),$lnap_smapsolution);
		echo $lnap_package_msg; ?></td>

		
		<td> <?php _e('1 month free subscription','linkedin-auto-publish'); ?>
		</td>
		<td> <?php _e('3 months free subscription','linkedin-auto-publish'); ?>
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_LNAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Price','linkedin-auto-publish'); ?> </td>
		<td> <?php _e('FREE','linkedin-auto-publish'); ?> </td>
		<td> <?php _e('Starts from 39 USD','linkedin-auto-publish'); ?> </td>
		<td> <?php _e('Starts from 69 USD','linkedin-auto-publish'); ?> </td>
	</tr>
	<tr>
		<td> <?php _e('Purchase','linkedin-auto-publish'); ?> </td>
		<td></td>
		<td style="padding: 2px" colspan='2' ><a target="_blank"href="https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/purchase"  class="xyz-lnap-buy-button"> <?php _e('Buy Now','linkedin-auto-publish'); ?> </a>
		</td>
	</tr>
</table>
<br/>
<div style="clear: both;"></div>
<span style="color: #FF8000;font-size: 14px;font-weight: bold;"> * </span> <?php _e('Pinterest is added on experimental basis.','linkedin-auto-publish'); ?>
