<?php
if( !defined('ABSPATH') ){ exit();}
?>
<h1 style="visibility: visible;">Social Media Auto Publish (V <?php echo xyz_smap_plugin_get_version(); ?>)</h1>

<div style="width: 99%">
<p style="text-align: justify">
<?php $wp_smap="Social Media Auto Publish";
      $smap_pub_msg=sprintf( __('%s automatically publishes posts from your blog to your Facebook, Twitter , Instagram and LinkedIn pages. It allows you to filter posts based on post-types and categories. %s is developed and maintained by','social-media-auto-publish'),$wp_smap,$wp_smap);
 echo $smap_pub_msg; ?> <a href="http://xyzscripts.com">XYZScripts</a>.</p>

 

<p style="text-align: justify">
	<?php $smap_url="https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/features";
	$smap_plugin = "XYZ Social Media Auto Publish";
	$smap_feature_msg=sprintf( __('If you would like to have more features , please try <a href="%s" target="_blank">%s</a> which is a premium version of this plugin. We have included a quick comparison of the free and premium plugins for your reference.','social-media-auto-publish'),$smap_url,$smap_plugin); 
	echo $smap_feature_msg; ?>
</p>
 </div>
 <table class="xyz-premium-comparison" cellspacing=0 style="width: 99%;">
	<tr style="background-color: #EDEDED">
		<td><h2> <?php _e('Feature group','social-media-auto-publish'); ?> </h2></td>
		<td><h2> <?php _e('Feature','social-media-auto-publish'); ?> </h2></td>
		<td><h2> <?php  $smap="SMAP";
		                $smap_free_msg=sprintf( __('%s Free','social-media-auto-publish'),$smap);
		           echo $smap_free_msg; ?></h2></td>
		</td>
		<td><h2> <?php $smap_premium_msg=sprintf( __('%s premium','social-media-auto-publish'),$smap); 
			      echo $smap_premium_msg; ?></h2></td>
		<td><h2><?php  $smap_premium_plus_msg=sprintf( __('%s premium','social-media-auto-publish'),$smap);
			      echo $smap_premium_plus_msg; ?>+</h2></td>
	</tr>
	<!-- Supported Media  -->
	<tr>
		<td rowspan="6"><h4> <?php _e('Supported Media','social-media-auto-publish'); ?> </h4></td>
		<td> <?php _e('Facebook','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Twitter','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('LinkedIn','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Instagram','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Tumblr','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Pinterest','social-media-auto-publish'); ?> <span style="color: #FF8000;font-size: 14px;font-weight: bold;">*</span></td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>

	
		<!-- Posting Options  -->
	<tr>
		<td rowspan="16"><h4> <?php _e('Posting Options','social-media-auto-publish'); ?> </h4></td>
		<td> <?php _e('Publish to facebook pages','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to facebook groups','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to twitter profile','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to linkedin profile','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish to instagram Business accounts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish to linkedin company pages','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to tumblr profile','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to pinterest boards','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to add twitter image description for visually impaired people','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to republish existing posts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish to multiple social media accounts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Seperate message formats for publishing to multiple social media accounts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Save auto publish settings of individual posts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Hash Tags support for Facebook, Twitter, Linkedin, Instagram, Tumblr and Pinterest','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Option to use post tags as hash tags','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Enable/Disable SSL peer verification','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
		
	<!-- Image Options  -->
	
	<tr>
	<td rowspan="5"><h4> <?php _e('Image Options','social-media-auto-publish'); ?> </h4></td>
		<td> <?php _e('Publish images along with post content','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Separate default image url for publishing to multiple social media accounts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
		<tr>
		<td> <?php _e('Option to specify preference from featured image, post content, post meta and open graph tags','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Publish multiple images to facebook, tumblr, linkedin and twitter along with post content','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to specify multiphoto preference from post content and post meta','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<!-- Video Options  -->
	
	<tr>
	<td rowspan="4"><h4> <?php _e('Video/Audio Options','social-media-auto-publish'); ?> </h4></td>
		<td> <?php _e('Publish video to facebook, tumblr,Linkedin, Instagram and twitter along with post content','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>		
	</tr>
	
	<tr>
		<td> <?php _e('Option to specify preference from post content, post meta and open graph tags','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Publish audio to tumblr along with post content','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Option to specify audio preference from  post content, post meta and open graph tags','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<!-- Filter Options  -->
		
	<tr>
	<td rowspan="9"><h4> <?php _e('Filter Options','social-media-auto-publish'); ?> </h4></td>
		<td> <?php _e('Filter posts to publish based on categories','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Filter posts to publish based on custom post types','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Filter posts to publish based on sticky posts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Configuration to enable/disable page publishing','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Category filter for individual accounts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Custom post type filter for individual accounts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Enable/Disable page publishing for individual accounts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Override auto publish scheduling for individual accounts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Override auto publish based on sticky posts for individual accounts','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<!-- Scheduling  -->
		
	<tr>
	<td rowspan="4"><h4> <?php _e('Scheduling','social-media-auto-publish'); ?> </h4></td>
		<td> <?php _e('Instantaneous post publishing','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Scheduled post publishing using cron','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Status summary of auto publish tasks by mail','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Configurable auto publishing time interval','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	
	
	
	<!-- Publishing History  -->
	<tr>
		<td rowspan="4"><h4> <?php _e('Publishing History','social-media-auto-publish'); ?> </h4></td>
		<td> <?php _e('View auto publish history','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('View auto publish error logs','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to republish post','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<tr>
		<td> <?php _e('Option to reschedule publishing','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	
	<!-- Installation and Support -->
	<tr>
		<td rowspan="2"><h4> <?php _e('Installation and Support','social-media-auto-publish'); ?> </h4></td>
		<td> <?php _e('Free Installation','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Privilege customer support','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<!-- Addons and Support -->
	<tr>
		<td rowspan="3"><h4> <?php _e('Addon Features','social-media-auto-publish'); ?> </h4></td>
		<td> <?php _e('Advanced Autopublish Scheduler','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		</tr>
		<tr>
		<td> <?php _e('URL-Shortener','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td> <?php _e('Privilege Management','social-media-auto-publish'); ?> </td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/cross.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
		<td><img src="<?php echo plugins_url("images/tick.png",XYZ_SMAP_PLUGIN_FILE);?>">
		</td>
	</tr>
	<tr>
		<td rowspan="3"><h4> <?php _e('Other','social-media-auto-publish'); ?> </h4></td>
		<td><?php $smap_smapsolution="SMAPSOLUTIONS"; 
		          $smap_package_msg=sprintf(__('%s API package for 1 year, worth 40 USD(1 fb account @ 10 api calls per hour, 1 linkedin account @ 25 api per day,1 twitter account @ 10 api calls per hour,1 instagram account @ 25 api per day)','social-media-auto-publish'),$smap_smapsolution);
		     echo $smap_package_msg; ?></td>
		<td> <?php _e('1 month free subscription','social-media-auto-publish'); ?> </td>
		<td colspan='2'> <?php _e('1 year free subscription','social-media-auto-publish'); ?> </td>
	</tr>
	<tr>
		<td> <?php _e('Price','social-media-auto-publish'); ?> </td>
		<td> <?php _e('FREE','social-media-auto-publish'); ?> </td>
		<td> <?php _e('Starts from 39 USD','social-media-auto-publish'); ?> </td>
		<td> <?php _e('Starts from 69 USD','social-media-auto-publish'); ?> </td>
	</tr>
	<tr>
		<td> <?php _e('Purchase','social-media-auto-publish'); ?> </td>
		<td></td>
		<td style="padding: 2px" colspan='2' ><a target="_blank"href="https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/purchase"  class="xyz-smap-buy-button"> <?php _e('Buy Now','social-media-auto-publish'); ?> </a>
		</td>
	</tr>
		
</table>
<br/>
<div style="clear: both;"></div>
<span style="color: #FF8000;font-size: 14px;font-weight: bold;"> * </span> <?php _e('Pinterest is added on experimental basis.','social-media-auto-publish'); ?>

