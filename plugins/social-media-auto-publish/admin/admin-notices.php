<?php
if( !defined('ABSPATH') ){ exit();}
function wp_smap_admin_notice()
{
	add_thickbox();
	$sharelink_text_array = array
						(
						"I use Social Media Auto Publish wordpress plugin from @xyzscripts and you should too.",
						"Social Media Auto Publish wordpress plugin from @xyzscripts is awesome",
						"Thanks @xyzscripts for developing such a wonderful social media auto publishing wordpress plugin",
						"I was looking for a social media publishing plugin and I found this. Thanks @xyzscripts",
						"Its very easy to use Social Media Auto Publish wordpress plugin from @xyzscripts",
						"I installed Social Media Auto Publish from @xyzscripts,it works flawlessly",
						"Social Media Auto Publish wordpress plugin that i use works terrific",
						"I am using Social Media Auto Publish wordpress plugin from @xyzscripts and I like it",
						"The Social Media Auto Publish plugin from @xyzscripts is simple and works fine",
						"I've been using this social media plugin for a while now and it is really good",
						"Social Media Auto Publish wordpress plugin is a fantastic plugin",
						"Social Media Auto Publish wordpress plugin is easy to use and works great. Thank you!",
						"Good and flexible  social media auto publish plugin especially for beginners",
						"The best social media auto publish wordpress plugin I have used ! THANKS @xyzscripts",
						);
$sharelink_text = array_rand($sharelink_text_array, 1);
$sharelink_text = $sharelink_text_array[$sharelink_text];
$xyz_smap_link = admin_url('admin.php?page=social-media-auto-publish-settings&smap_blink=en');
$xyz_smap_link = wp_nonce_url($xyz_smap_link,'smap-blk');
$xyz_smap_notice = admin_url('admin.php?page=social-media-auto-publish-settings&smap_notice=hide');
$xyz_smap_notice = wp_nonce_url($xyz_smap_notice,'smap-shw');
	echo '
	<script type="text/javascript">
			function xyz_smap_shareon_tckbox(){
			tb_show("Share on","#TB_inline?width=500&amp;height=75&amp;inlineId=show_share_icons_smap&class=thickbox");
		}
	</script>
	<div id="smap_notice_td" class="error" style="color: #666666;margin-left: 2px; padding: 5px;line-height:16px;">' ?>
	<p><?php
	   $smap_url="https://wordpress.org/plugins/social-media-auto-publish/";
	   $smap_xyz_url="https://xyzscripts.com/";
	   $smap_wp="Social Media Auto Publish";
	   $smap_xyz_com="xyzscripts.com";
	   $smap_thanks_msg=sprintf( __('Thank you for using <a href="%s" target="_blank"> %s </a> plugin from <a href="%s" target="_blank"> %s </a>. Would you consider supporting us with the continued development of the plugin using any of the below methods?','social-media-auto-publish'),$smap_url,$smap_wp,$smap_xyz_url,$smap_xyz_com); 
	   echo $smap_thanks_msg; ?></p>
	<p>
	<a href="https://wordpress.org/support/plugin/social-media-auto-publish/reviews" class="button xyz_rate_btn" target="_blank"> <?php _e('Rate it 5★\'s on wordpress','social-media-auto-publish'); ?> </a>
	<?php if(get_option('xyz_credit_link')=="0") ?>
		<a href="<?php echo $xyz_smap_link; ?>" class="button xyz_backlink_btn xyz_blink"> <?php _e('Enable Backlink','social-media-auto-publish'); ?> </a>
	
	<a class="button xyz_share_btn" onclick=xyz_smap_shareon_tckbox();> <?php _e('Share on','social-media-auto-publish'); ?> </a>
		<a href="https://xyzscripts.com/donate/5" class="button xyz_donate_btn" target="_blank"> <?php _e('Donate','social-media-auto-publish'); ?> </a>
	
	<a href="<?php echo $xyz_smap_notice; ?>" class="button xyz_show_btn"> <?php _e('Don\'t Show This Again','social-media-auto-publish'); ?> </a>
	</p>
	
	<div id="show_share_icons_smap" style="display: none;">
	<a class="button" style="background-color:#3b5998;color:white;margin-right:4px;margin-left:100px;margin-top: 25px;" href="http://www.facebook.com/sharer/sharer.php?u=https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/" target="_blank"> <?php _e('Facebook','social-media-auto-publish'); ?> </a>
	<a class="button" style="background-color:#00aced;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://twitter.com/share?url=https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/&text='.$sharelink_text.'" target="_blank"> <?php _e('Twitter','social-media-auto-publish'); ?> </a>
	<a class="button" style="background-color:#007bb6;color:white;margin-right:4px;margin-left:20px;margin-top: 25px;" href="http://www.linkedin.com/shareArticle?mini=true&url=https://xyzscripts.com/wordpress-plugins/social-media-auto-publish/" target="_blank"> <?php _e('LinkedIn','social-media-auto-publish'); ?> </a>
	</div>
	<?php echo '</div>';
	
	
}
$smap_installed_date = get_option('smap_installed_date');
if ($smap_installed_date=="") {
	$smap_installed_date = time();
}
if($smap_installed_date < ( time() - (20*24*60*60) ))
{
	if (get_option('xyz_smap_dnt_shw_notice') != "hide")
	{
		add_action('admin_notices', 'wp_smap_admin_notice');
	}
}
