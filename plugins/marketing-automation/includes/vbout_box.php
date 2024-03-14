<?php
	$postId = !empty($_GET['post']) ? $_GET['post'] : 0;

	$post_type = get_post_type( $postId );
	$post = get_post( $postId );
?>

<?php if ($vb_template == 'standalone'): ?>
<div class="wrap">
<?php endif; ?>


	<?php if ($vb_template == 'standalone'): ?>
	<h2>Send to Vbout: <?php echo $post->post_title; ?></h2>
	
	<div class="update-nag" style="display: block; margin-top: 10px;">
        <p style="color: #f00; font-weight: bold; margin: 0; text-align: center; text-transform: uppercase;">Note that any changes applied below are strictly for the content of the email campaign and your post or page will remain unchanged.</p>
	</div>
	<?php else: ?>
	<p style="background-color: #ffe9e9; border: 2px solid red; font-size: 14px; font-weight: bold; margin: 10px 0; padding: 5px;">
		N.B. <span style="color: red;">The <?php echo ucfirst($post_type); ?> will be sent once you save the <?php echo $post_type; ?>.</span>
		<br /><span style="color: transparent;">N.B.</span> <span style="color: red;">All HTML tags (including images, font styles, etc. etc.) will be striped from the social media post.</span>
	</p>
	<?php endif; ?>
	
	<?php if ($vb_template == 'standalone'): ?>
	<form method="post" action="options.php" style="padding-top: 15px;">
		<input type="hidden" name="post_id" value="<?php echo $postId; ?>" />
	<?php endif; ?>
		<input type="hidden" name="vb_template" value="<?php echo $vb_template; ?>" />
		<input type="hidden" name="vb_post_id" value="<?php echo $postId; ?>" />
		<input type="hidden" name="vb_post_title" value="<?php //echo $post->post_title; ?>" />
		<input type="hidden" name="vb_post_description" value="<?php //echo preg_replace('/\s+?(\S+)?$/', 'xxx', substr(strip_tags($post->post_content), 0, 201)); ?>" />
		<input type="hidden" name="vb_post_url" value="<?php echo get_permalink($postId); ?>" />
		<input type="hidden" name="vb_photo_url" value="" />
		<input type="hidden" name="vb_photo_alt" value="<?php echo get_permalink($postId); ?>" />

		<?php if ($vb_template == 'standalone'): ?>
		<input type="hidden" name="option_page" value="vbout-schedule" />
		<input type="hidden" name="action" value="update" />
		<?php echo wp_nonce_field('vbout-schedule-options', '_wpnonce', true, false); ?>
		<?php endif; ?>

		<input type="hidden" name="facebook_post_description" value="<?php //echo preg_replace('/\s+?(\S+)?$/', '', substr(strip_tags($post->post_content), 0, 201)); ?>" />
		<input type="hidden" name="facebook_post_title" value="<?php //echo $post->post_title; ?>" />
		<input type="hidden" name="facebook_post_url" value="<?php echo get_permalink($postId); ?>" />
		<input type="hidden" name="facebook_photo_url" value="" />
		<input type="hidden" name="facebook_post_summary" value="<?php //echo preg_replace('/\s+?(\S+)?$/', '', substr(strip_tags($post->post_content), 0, 201)); ?>" />
		
		<input type="hidden" name="twitter_post_description" value="<?php //echo preg_replace('/\s+?(\S+)?$/', '', substr(strip_tags($post->post_content), 0, 201)); ?>" />
		<input type="hidden" name="twitter_photo_url" value="" />
				
		<input type="hidden" name="linkedin_post_description" value="<?php //echo preg_replace('/\s+?(\S+)?$/', '', substr(strip_tags($post->post_content), 0, 201)); ?>" />
		<input type="hidden" name="linkedin_post_title" value="<?php //echo $post->post_title; ?>" />
		<input type="hidden" name="linkedin_post_url" value="<?php echo get_permalink($postId); ?>" />
		<input type="hidden" name="linkedin_photo_url" value="" />
		<input type="hidden" name="linkedin_post_summary" value="<?php //echo preg_replace('/\s+?(\S+)?$/', '', substr(strip_tags($post->post_content), 0, 201)); ?>" />


            <?php if ($vb_template == 'standalone'): ?>
		
		<div id="titlediv">
			<input type="text" name="post_title" size="30" value="<?php echo htmlspecialchars($post->post_title)?>" id="title" spellcheck="true" autocomplete="off">
		</div>
            <?php
                wp_editor( $post->post_content, 'content', $settings = array() );
            ?>
		<br />
		<?php endif; ?>
		
		<?php if ($emailMarketingActivated): ?>
		<div id="vbout_post_to_campaign_box" class="postbox" style="padding: 0 10px;">
			<h3>
				<span>
					<label><input type="checkbox" name="vb_post_to_campaign" id="vb_post_to_campaign" /><?php _e( 'Send as an email campaign?', 'vblng' ); ?></label>
				</span>
			</h3>

			<div class="inside" style="display: none;">
				<table class="form-table">
					<tr scope="row">
						<th><?php _e( 'Choose list to email the campaign to:', 'vblng' ); ?></th>
						<td>
							<?php if (isset($lists['lists']) && $lists['lists'] != NULL): ?>
							<select id="campaigns" class="chosen-select" style="width:350px;" tabindex="2" name="campaign[]" multiple="multiple">
							<?php	foreach($lists['lists'] as $list): ?>
							<?php		if (($lists['default'] == NULL) || ($lists['default'] != NULL && in_array($list['value'], $lists['default']))): ?>
								<option value="<?php echo $list['value']; ?>"><?php echo $list['label']; ?></option>
							<?php		endif; ?>
							<?php	endforeach; ?>
							</select>
							<?php else: ?>
							<a href="admin.php?page=vbout-connect">Configure Connection</a>
							<?php endif; ?>
						</td>
					</tr>

					<tr scope="row">
						<th scope="row" style="width: auto;">
							<label for="vb_post_schedule_emailsubject"><?php _e( 'Campaign Name', 'vblng' ); ?></label>
						</th>
						<td>
							<input type="text" name="vb_post_schedule_emailname" id="vb_post_schedule_emailname"  value="<?php echo get_option('vbout_em_emailname'); ?>" class="regular-text" />
                        </td>
					</tr>
					
					<tr scope="row">
						<th scope="row" style="width: auto;">
							<label for="vb_post_schedule_emailsubject"><?php _e( 'Email Subject', 'vblng' ); ?></label>
						</th>
						<td>
							<input type="text" name="vb_post_schedule_emailsubject" id="vb_post_schedule_emailsubject" value="<?php echo get_option('vbout_em_emailsubject'); ?>" class="regular-text" />
                        </td>
					</tr>
					
					<tr scope="row">
						<th scope="row" style="width: auto;">
							<label for="vb_post_schedule_fromemail"><?php _e( 'From Email', 'vblng' ); ?></label>
						</th>
						<td>
							<input type="text" name="vb_post_schedule_fromemail" id="vb_post_schedule_fromemail"   value="<?php echo get_option('vbout_em_fromemail'); ?>" class="regular-text" />
                        </td>
					</tr>
					
					<tr scope="row">
						<th scope="row" style="width: auto;">
							<label for="vb_post_schedule_fromname"><?php _e( 'From Name', 'vblng' ); ?></label>
						</th>
						<td>
							<input type="text" name="vb_post_schedule_fromname" id="vb_post_schedule_fromname" value="<?php echo get_option('vbout_em_fromname'); ?>" class="regular-text" />
                        </td>
					</tr>
					
					<tr scope="row">
						<th scope="row" style="width: auto;">
							<label for="vb_post_schedule_replyto"><?php _e( 'Reply to', 'vblng' ); ?></label>
						</th>
						<td>
							<input type="text" name="vb_post_schedule_replyto" id="vb_post_schedule_replyto" value="<?php echo get_option('vbout_em_replyto'); ?>" class="regular-text" /></td>
					</tr>
					
<!--					<tr scope="row">-->
<!--						<th scope="row" style="width: auto;">-->
<!--							<label for="vb_post_schedule_returnsummary">--><?php //_e( 'Do you wish to send a post summary?', 'vblng' ); ?><!--</label>-->
<!--						</th>-->
<!--						<td>-->
<!--							<input type="checkbox" name="vb_post_schedule_returnsummary" id="vb_post_schedule_returnsummary" value="yes" />-->
<!--						</td>-->
<!--					</tr>-->
					

				</table>
			</div>
		</div>
		<?php endif; ?>
				
		<?php if ($socialMediaActivated && $channels != NULL): ?>
			<?php //if (!isset($_GET['action']) || (isset($_GET['action']) && $_GET['action']!='edit')): //do not show on "Edit Post" ?>
			<div id="vbout_post_to_channels_box" class="postbox" style="padding: 0 10px;">
				<h3>
					<span>
						<label><input type="checkbox" name="vb_post_to_channels" id="vb_post_to_channels" /><?php _e( 'Post to social media?', 'vblng' ); ?></label>
					</span>
				</h3>

				<div class="inside" style="display: none;">
					<p>Please choose which social channel you want to post to:</p>
					<table class="form-table">
						<?php	if (isset($channels['Facebook']) && $channels['Facebook'] != NULL): ?>
						<tr scope="row" class="facebook-channels-row">
							<th>Facebook:</th>
							<td>
								<?php 
								$showALLchannels = true; 
								$hasDefault = false; 
								if(isset($channels['default']['Facebook'])){
									$showALLchannels = false; 
									$hasDefault = true;
									$matched = false;
									foreach($channels['default']['Facebook'] as $default){
										foreach($channels['Facebook'] as $page){
											if($default==$page['value']){
												$matched = true;
											}
										}
									}
									if(!$matched) $showALLchannels = true;
								}
								?>
								<select name="channels[facebook][]" class="chosen-select channels facebook-profiles-channels" multiple="multiple">
								<?php	foreach($channels['Facebook'] as $page): ?>
									<?php if($showALLchannels || ($hasDefault && in_array($page['value'], $channels['default']['Facebook'])) ): ?>
									<?php //if (!isset($channels['default']['Facebook']) || (isset($channels['default']['Facebook']) && in_array($page['value'], $channels['default']['Facebook']))): ?>
									<option value="<?php echo $page['value']; ?>"><?php echo $page['label']; ?></option>
									<?php	endif; ?>
								<?php	endforeach; ?>
								</select>
								<div class="livePreviewCanvas" id="FacebookLivePreview" style="padding-top: 15px;">
									<a href="javascript:showFb()" style="color: #9bc035; display: block; margin-bottom: 10px;">Preview before you publish</a>
									<div id = "livePreviewBarFB" class="livePreviewBar" style="display: none;">
										<div class="facebook_livepreview_box">
											<div class="fbBorderTop"></div>
											<div class="timelineUnitContainer">
												<div class="">
													<div role="article">
														<div class="clearfix fbPostHeader">
															<a tabindex="0" href="javascript://" class="facebookAvatar">
																<img alt="" src="https://www.vbout.com/images/livepreview/facebook/facebook_page.png" class="_s0 _50c7 _54rt img">
															</a>
															
															<div class="headerInfo">
																<h5>
																	<span class="fcg">
																		<span class="fwb">
																			<a href="javascript://">Facebook Page Name</a>
																		</span>
																	</span> 
																	
																	<span class="fcg"></span>
																</h5>
																
																<div class="postby fsm fwn fcg">
																	<a href="javascript://" class="uiLinkSubtle">
																		<abbr title="">Just Now</abbr>
																	</a>
																	<a href="javascript://" class="uiStreamPrivacy"><i></i></a>
																</div>
															</div>
														</div>
														
														<div class="userContentContainer">
															<div class="userContentWrapper">
																<div class="_wk">
																	<div id="Facebook_ContentPost" class="userContent"></div><!-- Please insert a text to share... -->
																</div>
															</div>
														</div>
													</div>
													
													<div class="photo" href="javascript://" style="display: none; margin: 0 -9px;">
														<div class="letterboxedImage photoWrap" style="width:486px;height:504px; position: relative; background: #f2f2f2;">
															<div class="uiScaledImageContainer scaledImage" style="width: 336px;height: 504px;margin: 0 auto;">
																<img id="Facebook_ContentPhoto" style="height: 100%; min-height: 100%;position: relative;" class="img" src="" style="left:0px;" alt="" width="337" height="504">
																
																<div class="tablenav" style="bottom: 10px; position: absolute; right: 10px; z-index: 10;">
																	<div class="tablenav-pages">
																		<div class="pagination-links">
																			<a data-channel="facebook" class="prev-page" href="#">�</a>
																			<a data-channel="facebook" class="next-page" href="#">�</a>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<div class="share" style="display: none;">
														<div style="color: #141823; direction: ltr; line-height: 1.28; text-align: left; word-wrap: break-word;">
															<div>
																<div class="mtm" style="margin-top: 10px;">
																	<div id="u_ps_0_0_5" class="_6m2 _1zpr clearfix _dcs _4_w4 _59ap _2ec0" style="box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.15) inset, 0 1px 4px rgba(0, 0, 0, 0.1); background-color: #fff; overflow: hidden; position: relative; z-index: 0;">
																		<div class="clearfix _2r3x">
																			<div class="lfloat _ohe" style="width: 100%; float: left;">
																				<span class="_3m6-">
																					<div class="_6ks" style="line-height: 0; position: relative; z-index: 1;">
																						
																							<div class="_6l- __c_">
																								<div style="width:100%;height:246px; overflow: hidden; position: relative; background-position: 50% 50%; background-repeat: no-repeat; display: block;" class="uiScaledImageContainer _6m5 fbStoryAttachmentImage shareLink">
																									<img id="Facebook_SharePhoto" width="470" height="246" alt="" src="" class="scaledImageFitWidth img" style="border: 0 none; min-height: 100%; position: relative; height: auto; width: 100%;">
																									
																									<div class="tablenav" style="bottom: 10px; position: absolute; right: 10px; z-index: 10;">
																										<div class="tablenav-pages">
																											<div class="pagination-links">
																												<a data-channel="facebook" class="prev-page" href="#">�</a>
																												<a data-channel="facebook" class="next-page" href="#">�</a>
																											</div>
																										</div>
																									</div>
																								</div>
																							</div>
																						
																					</div>
																					
																					<div class="_3ekx">
																						<div class="_6m3" style="height: auto; margin: 10px 12px; font-size: 12px;position: relative;">
																							<div class="mbs _6m6">
																								<div id="Facebook_ShareTitle" class="shareHeaderTitle" href="javascript://"></div>
																							</div>
																							
																							<div class="_6ma">
																								<div id="Facebook_ShareSummary" class="_6m7 shareHeaderContent">
																									
																								</div>
																								
																								<div class="_59tj">
																									<div id="Facebook_ShareURL" class="_6lz _6mb shareHeaderLink ellipsis"></div>
																								</div>
																							</div>
																						</div>
																					</div>
																				</span>
																			</div>
																			
																			<div class="_42ef"><span class="_3c21"></span></div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													
													<div class="fbTimelineUFI uiCommentContainer">
														<div class="fbTimelineFeedbackHeader">
															<div class="clearfix fbTimelineFeedbackActions">
																<div class="clearfix">
																	<div class="_4bl7 _4bl8"></div>
																	<div class="_4bl9">
																		<span class="UFIBlingBoxTimeline"><span data-reactid=".13"></span></span>
																		<span class="UIActionLinks UIActionLinks_bottom">
																			<a class="like_link" title="Like this item" href="javascript://">Like</a> � <a class="comment_link" title="Leave a comment" href="javascript://">Comment</a>
																		</span>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
											
											<div class="fbBorderBottom"></div>
										</div>
									</div>
								</div>							
							</td>
						</tr>
						<?php	endif; ?>
						
						<?php	if (isset($channels['Twitter']) && $channels['Twitter'] != NULL): ?>
						<tr scope="row" class="twitter-channels-row">
							<th>Twitter:</th>
							<td>
								<?php 
								$showALLchannels = true; 
								$hasDefault = false; 
								if(isset($channels['default']['Twitter'])){
									$showALLchannels = false; 
									$hasDefault = true;
									$matched = false;
									foreach($channels['default']['Twitter'] as $default){
										foreach($channels['Twitter'] as $profile){
											if($default==$profile['value']){
												$matched = true;
											}
										}
									}
									if(!$matched) $showALLchannels = true;
								}
								?>
								<select name="channels[twitter][]" class="chosen-select channels twitter-profiles-channels" multiple="multiple">
								<?php	foreach($channels['Twitter'] as $profile): ?>
									<?php if($showALLchannels || ($hasDefault && in_array($profile['value'], $channels['default']['Twitter'])) ): ?>
									<?php //if (!isset($channels['default']['Twitter']) || (isset($channels['default']['Twitter']) && in_array($profile['value'], $channels['default']['Twitter']))): ?>
									<option value="<?php echo $profile['value']; ?>"><?php echo $profile['label']; ?></option>
									<?php	endif; ?>
								<?php	endforeach; ?>
								</select>
								
								<div class="livePreviewCanvas da-form-item" id="TwitterLivePreview" style="padding-top: 15px;">
									<a href="javascript:showTwitter();" style="color: #9bc035; display: block; margin-bottom: 10px;">Preview before you publish</a>
									<div id = "livePreviewBarTwitter" class="livePreviewBar" style="display: none; width: 512px;">
										<div class="twitter_livepreview_box">
											<div class="Grid" style="display: block; font-size: 0; margin: 0; padding: 0; text-align: left;">
												<div class="Grid-cell u-size3of3" style="box-sizing: border-box; display: inline-block; font-size: 14px; margin: 0; padding: 0; text-align: left; vertical-align: top; width: 100%;">
													<div class="StreamItem js-stream-item" style="position: relative;">
														<div class="ProfileTweet u-textBreak js-tweet js-stream-tweet js-actionable-tweet ProfileTweet--low" style="background-color: #fff; border: 1px solid #e1e8ed; box-sizing: border-box; line-height: 1.375em; padding: 13px 15px 15px; position: relative; word-wrap: break-word !important; margin-bottom: -1px;">
															<div class="ProfileTweet-header clearfix" style="color: #8899a6; margin: 0; transition: color 0.15s ease 0s; line-height: 1.375em;">
																<div class="ProfileTweet-authorDetails  clearfix" style="padding-bottom: 5px; line-height: 14px; padding-top: 2px; color: #8899a6;">
																	<a href="/vbouttestaccoun" class="ProfileTweet-originalAuthorLink u-linkComplex js-nav js-user-profile-link" style="float: left; color: #0084b4; text-decoration: none !important;">
																		<img style="border-radius: 4px; float: left; height: 24px; margin: 0 6px 0 0; width: 24px;" alt="" src="https://abs.twimg.com/sticky/default_profile_images/default_profile_1_normal.png" class="ProfileTweet-avatar js-action-profile-avatar">
																		
																		<span class="ProfileTweet-originalAuthor u-pullLeft u-textTruncate js-action-profile-name" style="overflow: hidden !important; text-overflow: ellipsis !important; white-space: nowrap !important; word-wrap: normal !important; display: block; float: left;">
																			<b class="ProfileTweet-fullname u-linkComplex-target" style="color: #292f33; font-size: 14px; font-weight: bold;float: left;">Twitter Account Name</b>
																			<span dir="ltr" class="ProfileTweet-screenname u-inlineBlock u-dir" style="color: #8899a6; font-size: 13px; display: inline-block !important; max-width: 100%;float: left;">
																				<span class="at">&nbsp;@&nbsp;</span>TwitterAccountName
																			</span>
																		</span>
																	</a>

																	<span style="float: left !important;" class="u-pullLeft">&nbsp;�&nbsp;</span>
																	<span style="float: left !important;" class="u-pullLeft">
																		<a style="color: #8899a6; display: inline-block; font-size: 13px; transition: color 0.15s ease 0s; white-space: nowrap;text-decoration: none;" title="2:06 PM - 15 Jun 2014" href="/vbouttestaccoun/status/478282536418680832" class="ProfileTweet-timestamp js-permalink js-nav js-tooltip">
																			<span data-long-form="true" data-time="1402866417" class="js-short-timestamp ">Just Now</span>
																		</a>		
																	</span>
																</div>
															</div>
															
															<div class="ProfileTweet-contents" style="margin-left: 30px; margin-top: -5px;">
																<p id="Twitter_ContentPost" style="font-size: 16px; font-weight: 400; line-height: 22px; color: #292f33; margin-bottom: 5px; white-space: pre-wrap;" dir="ltr" class="ProfileTweet-text js-tweet-text u-dir editable"></p><!-- Please insert a text to share... -->
															</div>
															
															<div class="TwitterPhoto-media" style="display: none; background-color: #fff; border: 1px solid #e1e8ed; border-radius: 5px; box-sizing: border-box; max-height: 262px; overflow: hidden;text-align: center;">
																<a style="display: inline-block; outline: 0 none;cursor: zoom-in; font-size: 0; max-width: 100%;" href="javascript://" class="TwitterPhoto-link media-thumbnail twitter-timeline-link">
																	<img id="Twitter_ContentPhoto" lazyload="1" style="margin-top: -34.0px;max-width: 100%; vertical-align: middle;display: inline-block;" alt="Embedded image permalink" src="" class="TwitterPhoto-mediaSource">
																</a>
																
																<div class="tablenav" style="bottom: 45px; position: absolute; right: 20px; z-index: 10;">
																	<div class="tablenav-pages">
																		<span class="pagination-links">
																			<a data-channel="twitter" class="prev-page" href="#">�</a>
																			<a data-channel="twitter" class="next-page" href="#">�</a>
																		</span>
																	</div>
																</div>
															</div>
															
															<div class="TwitterFakeButtons" style="background: url('https://www.vbout.com/images/twitter_sharelinks.png') no-repeat 0 0; height: 14px; margin-left: 30px; margin-top: 10px; position: relative;">
																<a style=" display: block; height: 16px; left: 0; position: absolute; top: 0; width: 16px;" href="javascript://"></a>
																<a style=" display: block; height: 16px; left: 53px; position: absolute; top: 0; width: 16px;" href="javascript://"></a>
																<a style=" display: block; height: 16px; left: 106px; position: absolute; top: 0; width: 16px;" href="javascript://"></a>
																<a style=" display: block; height: 16px; left: 156px; position: absolute; top: 0; width: 16px;" href="javascript://"></a>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>
						<?php	endif; ?>
						
						<?php	if (isset($channels['Linkedin']) && $channels['Linkedin'] != NULL): ?>
						<tr scope="row" class="linkedin-channels-row">
							<th>Linkedin:</th>
							<td>
								<?php 
								$showALLchannels = true; 
								$hasDefault = false; 
								if(isset($channels['default']['Linkedin']['profiles'])){
									$showALLchannels = false; 
									$hasDefault = true;
									$matched = false;
									foreach($channels['default']['Linkedin']['profiles'] as $default){
										foreach($channels['Linkedin']['profiles'] as $profile){
											if($default==$profile['value']){
												$matched = true;
											}
										}
									}
									if(!$matched) $showALLchannels = true;
								}
								?>
								<select name="channels[linkedin][]" class="chosen-select channels linkedin-profiles-channels" style="width:350px;" multiple="multiple">
								<?php	foreach($channels['Linkedin']['profiles'] as $profile): ?>
									<?php if($showALLchannels || ($hasDefault && in_array($profile['value'], $channels['default']['Linkedin']['profiles'])) ): ?>
									<?php //if (!isset($channels['default']['Linkedin']['profiles']) || (isset($channels['default']['Linkedin']['profiles']) && in_array($profile['value'], $channels['default']['Linkedin']['profiles']))): ?>
									<option value="<?php echo $profile['value']; ?>"><?php echo $profile['label']; ?></option>
									<?php	endif; ?>
								<?php	endforeach; ?>
								</select>
							</td>
						</tr>
                            <?php	if (isset($channels['Linkedin']['companies']) && $channels['Linkedin']['companies'] != NULL): ?>
                                <tr scope="row" class="linkedin-companies-channels-row">
                                    <th>Linkedin Companies:</th>
                                    <td>
                                        <?php
                                        $showALLchannels = true;
                                        $hasDefault = false;
                                        if(isset($channels['default']['Linkedin']['companies'])){
                                            $showALLchannels = false;
                                            $hasDefault = true;
                                            $matched = false;
                                            foreach($channels['default']['Linkedin']['companies'] as $default){
                                                foreach($channels['Linkedin']['companies'] as $profile){
                                                    if($default==$profile['value']){
                                                        $matched = true;
                                                    }
                                                }
                                            }
                                            if(!$matched) $showALLchannels = true;
                                        }
                                        ?>
                                        <select name="channels[linkedin_companies][]" class="chosen-select channels linkedin-companies-channels" style="width:350px;" multiple="multiple">
                                            <?php	foreach($channels['Linkedin']['companies'] as $profile): ?>
                                                <?php if($showALLchannels || ($hasDefault && in_array($profile['value'], $channels['default']['Linkedin']['companies'])) ): ?>
                                                    <?php //if (!isset($channels['default']['Linkedin']['companies']) || (isset($channels['default']['Linkedin']['companies']) && in_array($profile['value'], $channels['default']['Linkedin']['companies']))): ?>
                                                    <option value="<?php echo $profile['value']; ?>"><?php echo $profile['label']; ?></option>
                                                <?php	endif; ?>
                                            <?php	endforeach; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php	endif; ?>
						<tr scope="row">
							<th style="padding: 0;">&nbsp;</th>
							<td style="padding-top: 0; padding-bottom: 0;">
								<div class="livePreviewCanvas da-form-item" id="LinkedinLivePreview" style="padding-top: 15px;">
									<a href="javascript:showLinkedIn()" style="color: #9bc035; display: block; margin-bottom: 10px;">Preview before you publish</a>
									<div id ="livePreviewBarLinkedIn" class="livePreviewBar" style="display: none; border-bottom: 1px solid #eee; border-top: 1px solid #eee; padding: 20px 0; width: 512px;">
										<div class="linkedin_livepreview_box">
											<img style="display: block; float: left; border-color: #eee #eee #eee -moz-use-text-color; border-image: none; border-style: solid solid solid none; border-width: 1px 1px 1px 0; margin-right: 15px; padding: 0;" src="https://static.licdn.com/scds/common/u/images/themes/katy/ghosts/connections/ghost_connections_65x65_v1.png" />
											
											<div class="feed-item linkedin_share">
												<div class="feed-body feed-uscp" style="margin-left: 20px; margin-right: 0; overflow: hidden;">
													<div class="feed-content" style="line-height: 17px; margin-right: 20px; margin-top: 0; padding: 0; font-size: 13px;">
														<div class="annotated-body" style="display: inline;">
															<strong style="color: #333; font-size: 12px; font-weight: bold; line-height: 14px;"><a style="color: #0077b5; font-size: 13px; padding-top: 0;text-decoration:none;" href="javascript://">Linkedin Account Name</a></strong>
														</div>
														
														<div id="Linkedin_ContentPost" class="share-body editable" style="word-wrap: break-word;margin-top: 6px;"></div><!-- Please insert a text to share... -->
														
														<div class="share-object linkedin-article" style="position: relative; margin-right: 20px; margin-bottom: 0; margin-top: 8px;overflow: hidden;width: auto; display: none;">
															<a style="color: #0077b5; font-size: 13px; padding-top: 0; margin-right: 15px; float: left; overflow: hidden; text-decoration: none; display: none; max-width: 180px; position: relative; text-align: left; width: auto;" target="_blank" rel="nofollow" class="image " href="javascript://">
																<img id="Linkedin_SharePhoto" width="180" height="110" alt="Difference between art and design" src="">
																
																
																<div class="tablenav" style="bottom: 10px; position: absolute; left: 100px; z-index: 10;">
																	<div class="tablenav-pages">
																		<div class="pagination-links">
																			<a data-channel="linkedin" class="prev-page" href="#">�</a>
																			<a data-channel="linkedin" class="next-page" href="#">�</a>
																		</div>
																	</div>
																</div>
															</a>
															
															<div class="properties" style="overflow: hidden;">
																<div class="share-title" style="color: #000; font-size: 14px; font-weight: normal; line-height: 17px; font-family: Arial,sans-serif; display: none;">
																	<a id="Linkedin_ShareTitle" style="margin-bottom: 7px; display: block; color: #000; font-size: 14px; font-weight: bold; line-height: 14px; text-decoration: none;" data-contentpermalink="" rel="nofollow" class="title editable" nohref="javascript://">Difference between art and design</a>
																</div>
																
																<a id="Linkedin_ShareURL" style="display: none; color: #999; float: left; font-size: 13px; font-weight: bold; line-height: 14px; margin-top: 1px; text-decoration: none; vertical-align: middle;" class="share-link editable" rel="nofollow" nohref="javascript://">siteforbiz.com</a>
																<span class="u-pullLeft" style="float: left; padding-left: 6px; padding-right: 6px;"> � </span>
																
																<p class="share-desc" style="color: #666; font-size: 13px; font-weight: normal; line-height: 17px;">
																	<span id="Linkedin_ShareSummary" class="description" style="color: #333;"></span>
																</p>
															</div>
														</div>
													</div>
													
													<div class="feed-item-meta" style="border: 0 none; clear: left; margin: 7px 0 0; padding: 0;">
														<ul class="feed-actions" style="overflow: hidden;">
															<li class="feed-like" style="font-size: 13px; margin: 0 0 0 0; padding: 0 0 0 0; display: block; float: left;">
																<span class="show-like">
																	<a role="button" class="unlike" href="javascript://" style="text-decoration:none;">Like </a>
																	<span class="u-pullLeft" style="display: inline-block; padding-left: 10px; padding-right: 10px;"> � </span>
																</span>
															</li>
															
															<li class="feed-comment" style="font-size: 13px; margin: 0 0 0 0; padding: 0 0 0 0; display: block; float: left;">
																<a role="button" data-li-trk-code="feed-comment" data-li-num-commented="0" title="Click to comment on this update" class="focus-comment-form" href="javascript://" style="text-decoration:none;">Comment </a>
																<span class="u-pullLeft" style="display: inline-block; padding-left: 10px; padding-right: 10px;"> � </span>
															</li>
															
															<li class="feed-share" style="font-size: 13px; margin: 0 0 0 0; padding: 0 0 0 0; display: block; float: left;">
																<a style="text-decoration:none;" role="button" data-li-trk-code="feed-share" title="Share" href="#" id="control_gen_5">Share</a>
																<span class="u-pullLeft" style="display: inline-block; padding-left: 10px; padding-right: 10px;"> � </span>
															</li>
															
															<li class="feed-share" style="font-size: 13px; margin: 0 0 0 0; padding: 0 0 0 0; display: block; float: left;">
																<a class="nus-timestamp" style="text-decoration:none;color: #8b8b8b; font-size: 13px; margin-left: 0; padding-right: 14px;" href="javascript://">Just Now</a>
															</li>
														</ul>									
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</td>
						</tr>					
						<?php	endif; ?>

						<?php	if (isset($channels['Instagram']['profiles']) && $channels['Instagram']['profiles'] != NULL): ?>
						<tr scope="row" class="instagram-channels-row">
							<th>Instagram:</th>
							<td>
								<?php
								$showALLchannels = true;
								$hasDefault = false;

								if(isset($channels['default']['Instagram']['profiles'])){
									$showALLchannels = false;
									$hasDefault = true;
									$matched = false;
									foreach($channels['default']['Instagram']['profiles'] as $default){
										foreach($channels['Instagram']['profiles'] as $profile){
											if($default==$profile['value']){
												$matched = true;
											}
										}
									}
									if(!$matched) $showALLchannels = true;
								}
								?>
								<select name="channels[instagram][]" class="chosen-select channels instagram-profiles-channels" style="width:350px;" multiple="multiple">
									<?php	foreach($channels['Instagram']['profiles'] as $profile): ?>
										<?php if($showALLchannels || ($hasDefault && in_array($profile['value'], $channels['default']['Instagram']['profiles'])) ): ?>
											<option value="<?php echo $profile['value']; ?>"><?php echo $profile['label']; ?></option>
										<?php	endif; ?>
									<?php	endforeach; ?>
								</select>

								<div style="display: none;" class="post-featured-image-required">
									<span style="color: red; font-weight: "><?php _e( 'Posting to "Instagram" require exist Featured Image', 'vblng' ); ?></span>
								</div>
							</td>
						</tr>
						<?php	endif; ?>

						<?php	if (isset($channels['Pinterest']['boards']) && $channels['Pinterest']['boards'] != NULL): ?>
						<tr scope="row" class="pinterest-channels-row">
							<th>Pinterest:</th>
							<td>
								<?php
								$showALLchannels = true;
								$hasDefault = false;
								if(isset($channels['default']['Pinterest']['boards'])){
									$showALLchannels = false;
									$hasDefault = true;
									$matched = false;
									foreach($channels['default']['Pinterest']['boards'] as $default){
										foreach($channels['Pinterest']['boards'] as $profile){
											if($default==$profile['value']){
												$matched = true;
											}
										}
									}
									if(!$matched) $showALLchannels = true;
								}
								?>
								<select name="channels[pinterest][]" class="chosen-select channels pinterest-boards-channels" style="width:350px;" multiple="multiple">
									<?php	foreach($channels['Pinterest']['boards'] as $board): ?>
										<?php if($showALLchannels || ($hasDefault && in_array($board['value'], $channels['default']['Pinterest']['boards'])) ): ?>
											<option value="<?php echo $board['value']; ?>"><?php echo $board['label']; ?></option>
										<?php	endif; ?>
									<?php	endforeach; ?>
								</select>

								<div style="display: none;" class="post-featured-image-required">
									<span style="color: red; font-weight: "><?php _e( 'Posting to "Pinterest" require exist Featured Image', 'vblng' ); ?></span>
								</div>
							</td>
						</tr>
						<?php	endif; ?>

						<tr scope="row">
							<th scope="row">
								<label for="vb_post_schedule_shortenurls"><?php _e( 'Use tracking URLs?', 'vblng' ); ?></label>
								<img class="alignright vb_tooltip" alt="The link to this post will be masked with a tracking url, <br />ex: https://www.vbout.com/goto/UO  so we can track clicks and social media conversion." src="<?php echo VBOUT_URL; ?>/images/tooltip-icon.png" style="cursor: pointer;" />
							</th>
							<td>
								<label for="vb_post_schedule_shortenurls">
									<input type="checkbox" name="vb_post_schedule_shortenurls" id="vb_post_schedule_shortenurls" value="yes" />
								</label>
							</td>
						</tr>
					</table>
				</div>
			</div>
			<?php //endif; ?>
		<?php endif; ?>
		
		<div>
			<table class="form-table">
				<tr scope="row">
					<th scope="row">
						<label for="vb_post_schedule_isscheduled"><?php _e( 'Do you wish to schedule it for the future?', 'vblng' ); ?></label>
					</th>
					<td>
						<label for="vb_post_schedule_isscheduled">
							<input type="checkbox" name="vb_post_schedule_isscheduled" id="vb_post_schedule_isscheduled" value="yes" />
						</label>
					</td>
				</tr>
				
				<tr scope="row" class="ScheduleDateTime" style="display: none;">
					<th scope="row" style="width: auto;">
						<label for="vb_post_schedule_date"><?php _e( 'Schedule Date', 'vblng' ); ?></label>
					</th>
					<td>
						<input type="text" name="vb_post_schedule_date" id="vb_post_schedule_date" value="" class="" />
					</td>
				</tr>

				<tr scope="row" class="ScheduleDateTime" style="display: none;">
					<th scope="row" style="width: auto;">
						<label for="vb_post_schedule_date"><?php _e( 'Schedule Time', 'vblng' ); ?></label>
					</th>
					<td>
						<select name="vb_post_schedule_time[Hours]" id="vb_post_schedule_time_hours">
							<option selected="selected" value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
							<option value="6">6</option>
							<option value="7">7</option>
							<option value="8">8</option>
							<option value="9">9</option>
							<option value="10">10</option>
							<option value="11">11</option>
							<option value="12">12</option>
						</select>
						&nbsp;&nbsp;
						<select name="vb_post_schedule_time[Minutes]" id="vb_post_schedule_time_minutes">
							<option selected="selected" value="00">00</option>
							<option value="05">05</option>
							<option value="10">10</option>
							<option value="15">15</option>
							<option value="20">20</option>
							<option value="25">25</option>
							<option value="30">30</option>
							<option value="35">35</option>
							<option value="40">40</option>
							<option value="45">45</option>
							<option value="50">50</option>
							<option value="55">55</option>
						</select>
						&nbsp;
						<input checked="checked" type='radio' class='' id='TimeAmPm_Am' name='vb_post_schedule_time[TimeAmPm]' value="am"><label for='TimeAmPm_Am'>AM</label>
						<input type='radio' class='' id='TimeAmPm_Pm' name='vb_post_schedule_time[TimeAmPm]' value="pm"><label for='TimeAmPm_Pm'>PM</label>
					</td>
				</tr>

				<?php if ($vb_template == 'standalone'): ?>
				<tr valign="top">
					<th scope="row">
						<input type="button" class="button-primary" id="Cancel" value="Cancel" />
						&nbsp;&nbsp;
						<input type="submit" class="button-primary" id="Submit" value="Submit" />
					</th>
					<td>&nbsp;</td>
				</tr>
				<?php endif; ?>
			</table>


            <table class="form-table">
                <tr id="SummaryCanvas" scope="row">
                    <th scope="row" style="width: auto;">
                        Content:
                    </th>
                    <td>
                        <?php

                        $summaryContent = $post->post_content;
                        $summaryContent .= "<br>";
                        $summaryContent .='...<a href="'.get_permalink($postId).'">read more</a>';
                        ?>
                        <?php wp_editor(  $summaryContent, 'summary', $settings = array('textarea_rows'=>20) ); ?>
                    </td>
                </tr>
            </table>

        </div>
		
	<?php if ($vb_template == 'standalone'): ?>
	</form>
	<?php endif; ?>

<?php if ($vb_template == 'standalone'): ?>
</div>
<?php endif; ?>
<!-- SET THIS SCRIPT IN THE BOTTOM OF THE PAGE AND NOT HERE.... -->
<script type="text/javascript">
	var buttonId = '<?php echo ($vb_template == 'standalone')?'Submit':'publish'; ?>';
	function showFb()
    {
        document.getElementById("livePreviewBarFB").style.display = "block";

    }
    function showTwitter()
    {
        document.getElementById("livePreviewBarTwitter").style.display = "block";

    }

    function showLinkedIn()
    {
        document.getElementById("livePreviewBarLinkedIn").style.display = "block";

    }
	jQuery(document).ready(function() { 
		jQuery('.chosen-select').chosen({'width':'90%'});
		
		///FACEBOOK
		jQuery('#Facebook_ContentPost').editable(function(value, settings) { 
			jQuery('[name=facebook_post_description]').val(value); 
			return value; 
		}, { 
			type   : 'textarea',
			onblur : 'submit',
			tooltip: 'Click to edit...',
			placeholder: 'Please insert a text to share custom post',
			cancel : 'cancel',
			height : '75px'
		});
		
		jQuery('#Facebook_ShareTitle').editable(function(value, settings) { 
			jQuery('[name=facebook_post_title]').val(value); 
			return value; 
		}, { 
			onblur : 'submit',
			tooltip: 'Click to edit...',
			cancel : 'cancel'
		});
		
		jQuery('#Facebook_ShareURL').editable(function(value, settings) { 
			jQuery('[name=facebook_post_url]').val(value); 
			return value; 
		}, { 
			type   : 'textarea',
			onblur : 'submit',
			tooltip: 'Click to edit...',
			cancel : 'cancel',
			height : '45px'
		});
		
		jQuery('#Facebook_ShareSummary').editable(function(value, settings) { 
			jQuery('[name=facebook_post_summary]').val(value); 
			return value; 
		}, { 
			type   : 'textarea',
			onblur : 'submit',
			tooltip: 'Click to edit...',
			cancel : 'cancel',
			height : '25px'
		});
		
		///TWITTER
		jQuery('#Twitter_ContentPost').editable(function(value, settings) { 
			jQuery('[name=twitter_post_description]').val(value); 
			return value; 
		}, { 
			type   : 'textarea',
			onblur : 'submit',
			tooltip: 'Click to edit...',
			placeholder: 'Please insert a text to share custom post',
			cancel : 'cancel',
			height : '75px'
		});
		
		///LINKEDIN
		jQuery('#Linkedin_ContentPost').editable(function(value, settings) { 
			jQuery('[name=linkedin_post_description]').val(value); 
			return value; 
		}, { 
			type   : 'textarea',
			onblur : 'submit',
			tooltip: 'Click to edit...',
			placeholder: 'Please insert a text to share custom post',
			cancel : 'cancel',
			height : '75px'
		});
		
		jQuery('#Linkedin_ShareTitle').editable(function(value, settings) { 
			jQuery('[name=linkedin_post_title]').val(value); 
			return value; 
		}, { 
			type   : 'textarea',
			onblur : 'submit',
			tooltip: 'Click to edit...',
			cancel : 'cancel'
		});
		
		jQuery('#Linkedin_ShareURL').editable(function(value, settings) { 
			jQuery('[name=linkedin_post_url]').val(value); 
			return value; 
		}, { 
			type   : 'textarea',
			onblur : 'submit',
			tooltip: 'Click to edit...',
			cancel : 'cancel'
		});
		
		jQuery('#Linkedin_ShareSummary').editable(function(value, settings) { 
			jQuery('[name=linkedin_post_summary]').val(value); 
			return value; 
		}, { 
			type   : 'textarea',
			onblur : 'submit',
			tooltip: 'Click to edit...',
			cancel : 'cancel',
			height : '25px'
		});
		///END
		
		jQuery('#vb_post_to_campaign').click(function() {
			if (jQuery('#summary_ifr').length) {
				if (parseInt(jQuery('#summary_ifr').css('height').replace('px', '')) < 240)
					jQuery('#summary_ifr').css('height', '240px');
			}
			
			// if (jQuery(this).attr('checked')) {
			// 	jQuery('#SummaryCanvas').show();
			// } else {
			// 	jQuery('#SummaryCanvas').hide();
			// }
		});
	
		jQuery('#Cancel').click(function() { 
			window.location = '<?php echo admin_url( 'edit.php?post_type='.$post_type, 'https' ); ?>';
		});
		
		jQuery('#'+buttonId).click(function() { 
			var submitToVbout = true;
			var submitToVboutErrMessage = '';
						 
			if (jQuery('#vb_post_to_channels').attr('checked') && jQuery('.channels option:selected').length == 0) {
				submitToVbout = false;
				submitToVboutErrMessage += 'Please choose one list or more to proceed. \n';
			}
			
			var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			
			if (jQuery('#vb_post_to_campaign').attr('checked')) {
				if (jQuery('#campaigns option:selected').length == 0) {
					submitToVbout = false;
					submitToVboutErrMessage += 'Please choose one list or more to proceed. \n';
				}
				
				if (jQuery('#vb_post_schedule_emailsubject').val() == '') {
					submitToVbout = false;
					submitToVboutErrMessage += 'Email Subject is required! \n';
				}
				
				if (jQuery('#vb_post_schedule_fromemail').val() == '') {
					submitToVbout = false;
					submitToVboutErrMessage += 'From Email is required! \n';
				}
				
				if (!regex.test(jQuery('#vb_post_schedule_fromemail').val())) {
					submitToVbout = false;
					submitToVboutErrMessage += 'From Email must be a valid email address! \n';
				}
				
				if (jQuery('#vb_post_schedule_fromname').val() == '') {
					submitToVbout = false;
					submitToVboutErrMessage += 'From Name is required! \n';
				}
				
				if (jQuery('#vb_post_schedule_replyto').val() == '') {
					submitToVbout = false;
					submitToVboutErrMessage += 'Reply to is required! \n';
				}
				
				if (!regex.test(jQuery('#vb_post_schedule_replyto').val())) {
					submitToVbout = false;
					submitToVboutErrMessage += 'Reply to must be a valid email address! \n';
				}
			}
			
			if (!submitToVbout)
				alert(submitToVboutErrMessage);
	
			return submitToVbout;
		});
		
		jQuery('#vb_post_schedule_date').datepicker({
			dateFormat : 'mm/dd/yy',
			changeMonth: true,
			changeYear: true
		});
		
		jQuery('#vb_post_schedule_isscheduled').change(function() { 
			if (!jQuery(this).is(':checked')) {
				jQuery('.ScheduleDateTime').hide();
			} else {
				jQuery('.ScheduleDateTime').show();
			}
		});
		
		jQuery('#vb_post_to_channels').change(function() { 
			if (!jQuery(this).is(':checked')) {
				jQuery('#vbout_post_to_channels_box .inside').hide();
			} else {
				jQuery('#vbout_post_to_channels_box .inside').show();
			}
		});
		
		jQuery('#vb_post_to_campaign').change(function() { 
			if (!jQuery(this).is(':checked')) {
				jQuery('#vbout_post_to_campaign_box .inside').hide();
			} else {
				jQuery('#vbout_post_to_campaign_box .inside').show();
			}
		});


		clearInterval( window.vbtCheckPostFeaturedImage );
		window.vbtCheckPostFeaturedImage = setInterval(function(){
			if( !jQuery('#vbout_post_to_channels_box').get(0) ) {
				return clearInterval( window.vbtCheckPostFeaturedImage ), true;
			}

			var img1 = !!jQuery('#postimagediv .inside img').get(0);
			var img2 = !!jQuery('.editor-post-featured-image .editor-post-featured-image__preview img').get(0);

			if(!(img1 || img2) ) {
				var instagram = !!(jQuery('#vbout_post_to_channels_box select.instagram-profiles-channels').val()||[]).length;
				var pinterest = !!(jQuery('#vbout_post_to_channels_box select.pinterest-boards-channels').val()||[]).length;

				jQuery('#vbout_post_to_channels_box .instagram-channels-row .post-featured-image-required')[instagram ? 'show' : 'hide']();
				jQuery('#vbout_post_to_channels_box .pinterest-channels-row .post-featured-image-required')[pinterest ? 'show' : 'hide']();
			}
			else {
				jQuery('#vbout_post_to_channels_box .instagram-channels-row .post-featured-image-required').hide();
				jQuery('#vbout_post_to_channels_box .pinterest-channels-row .post-featured-image-required').hide();
			}
		}, 1000);
	});
</script>
