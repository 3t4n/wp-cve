<style>
.ssa_likes{
	background: url('<?php echo RFR2B_LIBPATH; ?>images/like-button.png') no-repeat;
	padding-left: 19px;
}
.ssa_likes span{
	display: none;
}
.ssa_likes:hover span{
	display: inline;
	padding:10px;
	margin: -15px 0px 0px -50px;
	position:absolute;
	background:#ffffff;
	border:1px solid #cccccc;
	-webkit-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09);
	-moz-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09);
	box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09);
	-moz-border-radius: 5px;
	border-radius: 5px;
}
.ssa_likes2{
	background: url('<?php echo RFR2B_LIBPATH; ?>images/like-button.png') no-repeat;
	padding-left: 19px;
}
.ssa_likes2 span{
	display: none;
}
.ssa_likes2:hover span{
	display: inline;
	padding:10px;
	margin: -15px 0px 0px -50px;
	position:absolute;
	background:#ffffff;
	border:1px solid #cccccc;
	-webkit-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09);
	-moz-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09);
	box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09);
	-moz-border-radius: 5px;
	border-radius: 5px;
}
</style>

<script type="text/javascript">
		jQuery(document).ready(function(){
			var social = '<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FTheWpSmartApps&amp;width=292&amp;height=62&amp;show_faces=false&amp;colorscheme=light&amp;stream=false&amp;border_color&amp;header=true" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe>';
			
			jQuery('.ssa_likes').live('mouseenter', function(){
				if(jQuery('.ssa_likes span').length == 0)
					jQuery(this).prepend('<span>' + social + '</span>');
			});
			jQuery('.ssa_likes2').live('mouseenter', function(){
				if(jQuery('.ssa_likes2 span').length == 0)
					jQuery(this).prepend('<span>' + social + '</span>');
			});
						
		});
</script>

<!--Main Div-->
<div style="width:785px;margin:15px;position:relative;">
	<?php include('process.php'); ?>
	<br>

	<!--Global RSS-->
	<div class="rfr2b-content">
		<div class="rfr2b-section">
			<!--Start Global Content-->
				<!--Send Us Feedback-->
				<div style="font-size:11px; background-color:#FFFBCC; padding:10px 10px 10px 10px;font-family: Candara, Tahoma, Geneva, sans-serif;">
					<div style="float:left; vertical-align:top;">
					
					<span style="background:#ECEEF5; padding:5px; border:1px solid #CAD4E7;">
					<a href="https://www.facebook.com/TheWpSmartApps" class="ssa_likes2" target="_blank" style="text-decoration:none; font-size:12px; color:#3B59AF;">Like</a></span>
					
					
					<!--<strong>Follow Us On:</strong>&nbsp; <a href="http://twitter.com/wpsmartapps" target="_blank"><img src="<?php //echo RFR2B_LIBPATH; ?>images/followtweet.png" style="border:0px;"></a> &nbsp;&nbsp; <a href="http://www.facebook.com/TheWpSmartApps" target="_blank"><img src="<?php //echo RFR2B_LIBPATH; ?>images/followfacebook.png" style="border:0px;"></a>  &nbsp;&nbsp; <a href="http://feeds.feedburner.com/WpsmartappsTheBloggersInsight" target="_blank"><img src="<?php //echo RFR2B_LIBPATH; ?>images/followrss.png" style="border:0px;"></a>-->
					</div>

					<div style="float:right"> 
					<span class="placementLinks">
					<?php echo $this->rfr2b_img_rightarrow; ?> &nbsp;&nbsp;
					<?php echo $this->rfr2b_img_rightarrow; ?> &nbsp;&nbsp;
					<?php echo $this->rfr2b_img_rightarrow; ?> &nbsp;&nbsp;
					<a onClick="__rfr2b_ShowHide('wpsmartapps_help_section', 'wpsmartapps_help_section_img', 2, '<?php echo RFR2B_LIBPATH;?>');" style="cursor:hand;cursor:pointer"><img src="<?php echo RFR2B_LIBPATH; ?>images/minus-small.gif" id="wpsmartapps_help_section_img" border="0"  align="top" /><strong>WpSmartApps Resource Section</strong></a>&nbsp;&nbsp;&nbsp;<span style="color:#FF0000; font-weight:bold;">[IMPORTANT]</span>
					</span>
				
					</div>
				<div>&nbsp;</div>
				</div>
				
				<div id="wpsmartapps_help_section" style="display:none; background:#F9F7EC; min-height:150px; border-bottom:1px solid #E4E3DF;border-top:1px solid #E4E3DF; ">
				
				
				<table width="100%" border="0">
				  <tr>
				  
					<td width="29%" valign="top" style="padding:10px 0px 0px 10px;">
					<span style="border-bottom:1px dashed #999999; padding-bottom:5px;"><strong>Installed Version</strong></span>
						<ul class="rfr2b_sidebar_right placementLinks" style="background:none; float:none; border-left:none;">
							<li><?php echo RFR2B_NAME; ?> <span style="color:#CC0000;"><?php echo RFR2B_VERSION; ?></span></li>
							<li style="color: #666666;">by <a href="http://www.WpSmartApps.com/" target="_blank">WpSmartApps.com</a></li>
						</ul>					
					</td>
					
					<td width="33%" valign="top" style="padding:10px 0px 0px 10px;">
					<span style="border-bottom:1px dashed #999999; padding-bottom:5px;"><strong>Help Resources</strong></span>
					<ul class="elbpro_sidebar_right_menu placementLinks">
						<li><a href="http://wiki.wpsmartapps.com/" target="_blank">Getting Started Guides</a></li>
						<li><a href="http://community.wpsmartapps.com/forumdisplay.php?f=3" target="_blank">Community Support</a></li>
					</ul>
					
									
					</td>
					
					<td width="38%" valign="top" style="padding:10px 0px 0px 10px;">
					<span style="border-bottom:1px dashed #999999; padding-bottom:5px;"><strong>Our Premium WordPress Products</strong></span>
					<ul class="elbpro_sidebar_right_menu placementLinks">
				<li><?php echo $this->rfr2b_img_rightarrow; ?>&nbsp;<a href="http://immediatelistbuildingpro.com" target="_blank">Immediate List Building Pro</a></li>
				<li><?php echo $this->rfr2b_img_rightarrow; ?>&nbsp;<a href="http://www.readersfromrss2blog.com" target="_blank">Readers From RSS 2 BLOG Pro</a></li>
				<li><?php echo $this->rfr2b_img_rightarrow; ?>&nbsp;<a href="http://www.headerbardomination.com" target="_blank">Header Bar Domination</a></li>
					</ul>					
					</td>
					
				  </tr>
				  
				  <tr>
				    <td colspan="3" valign="top" style="padding:10px 0px 0px 10px;">
					
					<span class="placementLinks">
					<a onClick="__rfr2b_ShowHide('affiliate_section', 'affiliateSection_img', 2, '<?php echo RFR2B_LIBPATH;?>');" style="cursor:hand;cursor:pointer"><img src="<?php echo RFR2B_LIBPATH; ?>images/plus-small.gif" id="affiliateSection_img" border="0"  align="top" />&nbsp;&nbsp;<span style="border-bottom:1px dashed #999999; padding-bottom:5px;"><strong>Affiliate Program</strong></span>
					</a>
					</span>
					
					<div style="display:block;" id="affiliate_section">
					
					<div class="elbpro_sidebar_right_menu placementLinks" style="float:right; font-size:11px; padding:0px 10px 0px 0px;">
					<img src="<?php echo RFR2B_LIBPATH; ?>images/marketplace-small.png" style="border:0px; vertical-align:middle;">&nbsp;&nbsp;<a href="http://marketplace.wpsmartapps.com/" target="_blank">WpSmartApps Marketplace</a>
					</div>
					
					<ul class="elbpro_sidebar_right_menu placementLinks">
<li>
			<form action="" method="post">
			<div style="padding:8px 8px 0px 8px;color:#666666;">
			<strong>ClickBank ID:</strong> 
			<input type="text" name="rfr2b[cbid]" id="elbpro_cbid" value="<?php echo $this->fetch_rfr2b_affiliateOptions['cbid']; ?>" style="width:190px; border:1px solid #CCCCCC;" /><br>
			<small style="font-weight:normal; color:#8D8B8B;"><i>Brand Powered by link and Earn 50% commission by promoting Readers From RSS 2 Blog Pro.<br>
		<a href="http://wpsmartapps.com/affiliates/" target="_blank">Join our affiliate program</a></i></small>
			</div>
			
			<br>
			<div style="padding-top:7px;">
			<span style="color:#000000">
				<input type="checkbox" name="rfr2b[no_pwd_by]" id="no_pwd_by" value="1" <?php echo $no_poweredby_chk; ?> /> <strong>Show Powered By "<?php echo RFR2B_NAME; ?>" link</strong></span>&nbsp;<span style="color:#999999"><strong>(Give Credit)</strong></span>
			</div>
			
			<div align="left" style="padding:7px 40px 0px 0px;">
			<input type="submit" name="rfr2b[SaveAffiliateData]" value="Submit"   style="overflow:visible;padding:5px 10px 6px 7px;    background-color: #5872A7; color:#fff;
									background-position: 0 -96px;
									border-color: 1px solid #1A356E; font-weight:bold; cursor:pointer;
									"  />
			</div>
			</form>
		</li>		
	</ul>
	
	</div>
					
					</td>
			      </tr>
				</table>

				
				</div>
				
				<!--STEP 1 :: Preview-->					
				<h3 class="heading">
				<span class="rfr2b_step_indicator rfr2b_step_active">1</span>&nbsp;&nbsp;Global RSS Campaign: Sample Preview On Google Reader &nbsp;&nbsp;&nbsp;
				</h3>
				
				<div style="background-color:#F8F8F8; padding:10px 5px 5px 10px; min-height:20px; margin-bottom:20px; display:block;" >
					<div style="float:right; padding-right:5px; cursor:pointer;">
					<i style="font-size:10px; font-style:italic;">Click To View Demo Preview</i>&nbsp;
					<?php echo $this->rfr2b_img_rightarrow; ?> &nbsp;
					<?php echo $this->rfr2b_img_rightarrow; ?> &nbsp;
					<a onClick="__rfr2b_ShowHide('global_demo_show', 'global_demo', 3, '<?php echo RFR2B_LIBPATH;?>');" style="cursor:hand;cursor:pointer"><?php echo $this->rfr2b_img_global_preview; ?></a>			
					</div>
					<div id="global_demo_show" align="center" style="padding:10px 10px 10px 10px; display:none;">
					<?php echo $this->rfr2b_img_google_reader; ?>
					<br><br>
					<span style="color:#FF3333;"><strong>IMPORTANT:</strong> <i>Result may take few minutes or hours to appear depanding upon your RSS FEED READER.</i></span>
					</div>
				</div>
				
		<form action="" name="rfr2b_global_form" method="post" >
		
		
		
				<!--STEP 2 :: Incude pages on RSS-->					
		  <h3 class="heading placementLinks">
				<span class="rfr2b_step_indicator rfr2b_step_active">2</span>
				&nbsp;&nbsp;<input name="rfr2b[rfr2b_include_pages]" type="checkbox" value="1" <?php echo $rssIncludePages_chk; ?> onclick="__rfr2b_showHidediv(this,'includepages_content','includepages_content')" />
			&nbsp;&nbsp;Include Pages On RSS</a>&nbsp;&nbsp;&nbsp;
			<small style="font-weight:normal; color:#8D8B8B;">If enable, Added pages  will display on your RSS FEED.</small>	</h3>
			
			<div id="includepages_content" style="display:<?php echo ($rssIncludePages_chk_display?$rssIncludePages_chk_display:'none'); ?>;">
			<?php $this->rfr2b_page_list('displayINpageID',$this->fetch_rfr2b_control_options['displayINpageID']); ?>
			</div>
				

				<!--STEP 3 :: Feed Item-->					
		  <h3 class="heading">
				<span class="rfr2b_step_indicator rfr2b_step_active">3</span>&nbsp;&nbsp;<input name="rfr2b[rfr2b_display_post_tags]" type="checkbox" value="1" <?php echo $rfr2b_display_post_tags_chk; ?> />
				&nbsp;&nbsp;Display Post Tags&nbsp;&nbsp;&nbsp;
			<small style="font-weight:normal; color:#8D8B8B;">If enable, Tag associate with each post will display on your RSS FEED.</small>			</h3>
	
	
				<!--STEP 3 :: Feed Item-->					
		  <h3 class="heading">
				<span class="rfr2b_step_indicator rfr2b_step_active">4</span>
				<span style="color:#999999; font-weight:bold;">&nbsp;&nbsp;<span style="color:#FF3333; font-size:10px;">Only Available On Pro Version</span>&nbsp; <span class="placementLinks"> 
				
				<a href="http://www.readersfromrss2blog.com/" target="_blank"><input type="button" value="Download Pro Version Now"  style="overflow:visible;padding:5px 10px 6px 7px;    background-color: #F18B39; color:#fff;
									background-position: 0 -96px;
									border-color: 1px solid #1A356E; font-weight:bold; cursor:pointer;
									"  />
					</a>				
									
									</span>	</h3>
	
				
				<!--STEP 4 :: "X Comments" Labels-->					
				<h3 class="heading placementLinks">
				<span class="rfr2b_step_indicator rfr2b_step_active">5</span>
				&nbsp;&nbsp;<input name="rfr2b[display_x_comments]" type="checkbox" value="1" <?php echo $display_x_comment_chk; ?> onclick="__rfr2b_showHidediv(this,'x_comment_content','x_comment_content')">&nbsp;&nbsp;"X Comments" Labels</a>&nbsp;&nbsp;&nbsp;
				</h3>
				
				<div id="x_comment_content" style="-moz-border-radius: 8px; -khtml-border-radius: 8px; -webkit-border-radius: 8px; background-color:#F9F8F3; padding:10px 5px 5px 10px; min-height:20px; margin-bottom:20px; display:<?php echo ( $display_x_comment_chk_display?$display_x_comment_chk_display:'none' ); ?>;">
				
				<table width="100%" border="0">
				  <tr>
					<td style="padding-bottom:10px;">No Comment Text: </td>
					<td style="padding-bottom:10px;"><input name="rfr2b[rfr2b_no_Comments]" type="text" style="width:300px;"  value="<?php echo $this->fetch_rfr2b_no_Comments; ?>" /></td>
				  </tr>
				  
				  <tr>
					<td style="padding-bottom:10px;">1 Comment Text: </td>
					<td style="padding-bottom:10px;"><input name="rfr2b[rfr2b_one_Comments]" type="text" style="width:300px;"  value="<?php echo $this->fetch_rfr2b_one_Comments; ?>" /></td>
				  </tr>
				  
				  <tr>
					<td style="padding-bottom:10px;">More then 1 Comment Text: </td>
					<td style="padding-bottom:10px;"><input name="rfr2b[rfr2b_more_Comments]" type="text" style="width:300px;" value="<?php echo $this->fetch_rfr2b_more_Comments; ?>" />
					<small style="font-weight:normal; color:#8D8B8B;">
					where '%' is replace by the number of comments
					</small>
					</td>
				  </tr>
				</table>
				
				</div>

				
				<!--STEP 5 :: Social Links-->					
				<h3 class="heading placementLinks">
				<span class="rfr2b_step_indicator rfr2b_step_active">6</span>
				&nbsp;&nbsp;<a onClick="__rfr2b_ShowHide('social_links_content', 'social_links_small', 2, '<?php echo RFR2B_LIBPATH;?>');" style="cursor:hand;cursor:pointer"><img src="<?php echo RFR2B_LIBPATH; ?>images/plus-small.gif" id="social_links_small" border="0"  align="top" />&nbsp;Social Links / Icons</a>&nbsp;&nbsp;&nbsp;
				</h3>
				
<div id="social_links_content" style="-moz-border-radius: 8px; -khtml-border-radius: 8px; -webkit-border-radius: 8px; background-color:#FFFFFF; padding:10px 5px 5px 10px; min-height:20px; margin-bottom:20px; display:none;">



<!--Social Icon Preview-->
<div style="padding:5px 5px 5px 5px;">
<table align="left" width="100%" cellspacing="0" cellpadding="0" bgcolor="#f1f1f1"  border="0px;">
	<tbody>
	<tr bgcolor="#ffffff">
	
		<td align="center" width="17%" valign="top">
		<span class="sb_title">Del.icio.us</span><br>
		<a href="http://del.icio.us/post?url=%post-url%&title=%post-title%">
		<img src="<?php echo RFR2B_LIBPATH ?>images/delicious.gif" border="0" align="absmiddle">
		</a> 
		</td>
		
		<td align="center" width="17%" valign="top">
		<span class="sb_title">Facebook</span><br>
		<a href="#"><img src="<?php echo RFR2B_LIBPATH ?>images/facebook_icon.png" border="0" align="absmiddle"></a>  
		</td>
		
		<td align="center" width="17%" valign="top">
		<span class="sb_title">TweetThis</span><br>
		<a href="#"><img src="<?php echo RFR2B_LIBPATH ?>images/tweet.png" border="0" align="absmiddle"></a>  
		</td>
		
		<td align="center" width="17%" valign="top">
		<span class="sb_title">Digg</span><br>
		<a href="#"><img src="<?php echo RFR2B_LIBPATH ?>images/digg.png" border="0" align="absmiddle"></a>  
		</td>
		
		<td align="center" width="17%" valign="top">
		<span class="sb_title">StumbleUpon</span><br>
		<a href="#"><img src="<?php echo RFR2B_LIBPATH ?>images/stumble.gif" border="0" align="absmiddle"></a>  
		</td>
		
	</tr>
	
<tr bgcolor="#ffffff">
  <td align="center" valign="top"><br><input name="rfr2b[rfr2b_social_del]" type="checkbox" value="1" <?php echo $social_del_check; ?> /></td>
  <td align="center" valign="top"><br><input name="rfr2b[rfr2b_social_facebook]" type="checkbox" value="2" <?php echo $social_facebook_check; ?>  /></td>
  <td align="center" valign="top"><br><input name="rfr2b[rfr2b_social_tweet]" type="checkbox" value="3"  <?php echo $social_tweet_check; ?> /></td>
  <td align="center" valign="top"><br><input name="rfr2b[rfr2b_social_digg]" type="checkbox" value="4"  <?php echo $social_digg_check; ?> /></td>
  <td align="center" valign="top"><br><input name="rfr2b[rfr2b_social_stumble]" type="checkbox" value="5" <?php echo $social_stumble_check; ?>  /></td>
</tr>	
</tbody></table>
</div>

<div style="clear:both"></div>
<br>
<!--Eof Social Icon Preview-->
<!--<textarea name="rfr2b[rfr2b_social_links]" cols="100" rows="8"><?php //echo $this->fetch_rfr2b_social_links; ?></textarea>
-->
<div align="left" style="padding-left:20px;">&nbsp;<i><span style=" border-bottom:1px dashed #0033CC">Click On Check Box If You Wish Not to Show Icons On RSS Feed</span></i></div>
				
		  </div>				
				
				
				
				
				
				
				<!--STEP 6 :: Display Related Post-->					
				<h3 class="heading">
				<span class="rfr2b_step_indicator rfr2b_step_active">7</span>&nbsp;&nbsp; Display <input name="rfr2b[related_post]" type="text" style="width:40px; font-weight:normal;" value="<?php echo $this->fetch_rfr2b_control_options['related_post']; ?>" /> 
				Random Posts  &nbsp;
				<small style="font-weight:normal; color:#8D8B8B;"> <?php echo $this->rfr2b_img_leftarrow; ?> (Leave blank to disable)</small>
				&nbsp;&nbsp; 
				<span style="font-weight:normal; background:#F9F8F3; padding:10px 5px 10px 5px;-moz-border-radius: 8px; -khtml-border-radius: 8px; -webkit-border-radius: 8px;">
				<b>Display Title As:</b> <input name="rfr2b[rfr2b_randompost_title]" type="text" value="<?php echo $this->fetch_rfr2b_randompost_title; ?>" style="font-weight:normal; width:230px;" />
				</span>
				
				</h3>
				
				
				<!--STEP 7 :: Copyright Notice-->					
				<h3 class="heading placementLinks">
				<span class="rfr2b_step_indicator rfr2b_step_active">8</span>&nbsp;&nbsp;
				<a onClick="__rfr2b_ShowHide('display_copyright_notice', 'display_copyright_notice_img', 2, '<?php echo RFR2B_LIBPATH;?>');" style="cursor:hand;cursor:pointer"><img src="<?php echo RFR2B_LIBPATH; ?>images/plus-small.gif" id="display_copyright_notice_img" border="0"  align="top" /> Copyright Notice</a>				</h3>
				
				<!--Start Of CopyRight Notice Content-->
				<div align="center" id="display_copyright_notice" style="display:block; background:#F9F8F3; padding:10px 0px 10px 0px; -moz-border-radius: 8px; -khtml-border-radius: 8px; -webkit-border-radius: 8px; display:none;">
				<textarea name="rfr2b[rfr2b_copyright_notice]" cols="88" rows="3"><?php echo $this->fetch_copyright_notice; ?></textarea>
								&nbsp;&nbsp;
				<div align="left" class="placementLinks" style="padding-left:20px;">
				<a onClick="__rfr2b_ShowHide('copyright_tags', 'copyright_tags_img', 2, '<?php echo RFR2B_LIBPATH;?>');" style="cursor:hand;cursor:pointer"><img src="<?php echo RFR2B_LIBPATH; ?>images/plus-small.gif" id="copyright_tags_img" border="0"  align="top" />&nbsp;Tags you can use</a></div>
				
				<div align="left" id="copyright_tags" style="display:none;padding-left:20px;" >
				 <table width="70%" border="0">
						<tr>
						  <td style="font-size:11px; color:#999999;  font-family:Arial, Helvetica, sans-serif"><input name="" type="text" onclick="this.select()" value="%blog-name%" /></td>
						  <td style="font-size:11px; color:#999999;  font-family:Arial, Helvetica, sans-serif"><input name="" type="text" onclick="this.select()" value="%blog-url%" /></td>
						  <td style="font-size:11px; color:#999999;  font-family:Arial, Helvetica, sans-serif"><input name="" type="text" onclick="this.select()" value="%year%" /></td>
						  <td style="font-size:11px; color:#999999;  font-family:Arial, Helvetica, sans-serif"><input name="" type="text" onclick="this.select()" value="%post-url%" /></td>
						  <td style="font-size:11px; color:#999999;  font-family:Arial, Helvetica, sans-serif"><input name="" type="text" onclick="this.select()" value="%post-title%" /></td>
						</tr>
				  </table>
				</div>
				
				</div>
				<!--Eof STEP 7 :: Copyright Notice-->		
				
		
				<br>
				<br>
				
				
				
				
				<table>
				<tr>
				  <td width="127" valign="middle">
				   <input name="rfr2b[rss_global_data_submit]" type="submit" value="Save Global Changes"   
							  style="overflow:visible;padding:5px 10px 6px 7px;    background-color: #5872A7; color:#fff;
									background-position: 0 -96px;
									border-color: 1px solid #1A356E; font-weight:bold; cursor:pointer;
									"  />
				  </td>
				 <td width="719" colspan="2" style="padding-left:20px;">
					<span style="font-size:11px; color:#999999; font-family:Arial, Helvetica, sans-serif">
					<strong>How Can I See Plugin In Action?</strong> <br>
					You can see the plugin in action by subscribing to your blog RSS Feed using Google Reader or FeedBurner.<br>
					For <strong>quick results</strong> use IE to display your BLOG RSS Feed. <br><br>
					
					<strong>Worried About Finding Your Blog RSS URL?</strong><br>
					Please visit WordPress site for finding your Blog Feed URL: <a href="http://codex.wordpress.org/WordPress_Feeds" target="_blank" style="color:#0066CC"><i>http://codex.wordpress.org/WordPress_Feeds</i></a><br><br>
					
					OR View WpSmartApps Manual On <a href="http://wiki.wpsmartapps.com/index.php?title=How_to_test_the_plugin_and/or_see_what_it_looks_like_to_our_readers_before_implementing_it_on_the_site" target="_blank" style="color:#0066CC"><i>How Can I See Plugin In Action?</i></a>
					
					</span>									</td>
				</tr>
				</table>				
				
				
				
			   
									
		</form>	
		
		
							
				<br>
				<br>
				
			<!--Eof Global Content-->
		</div>			
	</div>
	<!--Eof Global RSS-->

		
				
</div>	

<br><br><br><br>

<!--<form method="post" action="http://www.aweber.com/scripts/addlead.pl">
	<input type="hidden" value="9022555" name="meta_web_form_id">
	<input type="hidden" value="" name="meta_split_id">
	<input type="hidden" value="wpsmartapps" name="listname">
	<input type="hidden" id="redirect_c725dffa4cac6db290580e7ef7570a79" value="http://wpsmartapps.com/almost-done/" name="redirect">
	<input type="hidden" value="codex.wordpress" name="meta_adtracking">
	<input type="hidden" value="1" name="meta_message">
	<input type="hidden" value="name,email" name="meta_required">
	<input type="hidden" value="" name="meta_tooltip">
	<input type="text" onblur="if (this.value == '') {this.value = 'Your name...';}" onfocus="if (this.value == 'Your name...') {this.value = '';}" value="<?php //echo ($current_user->user_nicename?$current_user->user_nicename:'Your name...');?>" id="name" name="name" class="text">
	<input type="text" onblur="if (this.value == '') {this.value = 'Your e-mail...';}" onfocus="if (this.value == 'Your e-mail...') {this.value = '';}" value="<?php //echo ($current_user->user_email?$current_user->user_email:'Your e-mail...');?>" id="elbproSidebarEmail" name="email" class="text">
	<input type="submit" value="Activate Now" style="overflow:visible;padding:5px 10px 6px 7px;    background-color: #5872A7; color:#fff;
									background-position: 0 -96px;
									border-color: 1px solid #1A356E; font-weight:bold; cursor:pointer;
									"  >
</form>-->
