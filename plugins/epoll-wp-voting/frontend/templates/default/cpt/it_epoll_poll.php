<?php
it_epoll_init_unique_vote_session();
get_header();
while ( have_posts() ) : the_post();
			$it_epoll_option_names = array();
			$poll_id = get_the_id();
			if(get_post_meta( $poll_id, 'it_epoll_poll_option', true )){
				$it_epoll_option_names = get_post_meta( $poll_id, 'it_epoll_poll_option', true );
			}
			$it_epoll_option_imgs = array();
			$it_epoll_option_imgs = get_post_meta( $poll_id, 'it_epoll_poll_option_img', true );
			$it_epoll_poll_option_cover_img = array();
			$it_epoll_poll_option_cover_img = get_post_meta( $poll_id, 'it_epoll_poll_option_cover_img', true );
			$it_epoll_poll_status = get_post_meta( $poll_id, 'it_epoll_poll_status', true );
			$it_epoll_poll_option_id = get_post_meta( $poll_id, 'it_epoll_poll_option_id', true );
			$it_epoll_poll_style = get_post_meta( $poll_id, 'it_epoll_poll_style', true );
			$it_epoll_poll_vote_total_count = (int)get_post_meta($poll_id, 'it_epoll_vote_total_count',true); 
			$it_epoll_result_visibility = get_post_meta($poll_id, 'it_epoll_poll_result_visibility',true); 

			//Color Scheme
			$it_epoll_poll_container_color_primary = get_post_meta( $poll_id, 'it_epoll_poll_container_color_primary', true );
			$it_epoll_poll_container_color_secondary   = get_post_meta( $poll_id, 'it_epoll_poll_container_color_secondary', true );
			$it_epoll_poll_button_color_primary  = get_post_meta( $poll_id, 'it_epoll_poll_button_color_primary', true );
			$it_epoll_poll_button_color_secondary = get_post_meta( $poll_id, 'it_epoll_poll_button_color_secondary', true );
			$it_epoll_poll_container_text_color = get_post_meta( $poll_id, 'it_epoll_poll_container_text_color', true );
			$it_epoll_poll_button_text_color = get_post_meta( $poll_id, 'it_epoll_poll_button_text_color', true );
			if($it_epoll_poll_status =='live'){
				$epoll_hide_element = true;
			}else{
				$epoll_hide_element = false;
			}
			do_action('it_epoll_poll_ui_before_variable_define',array('poll_id'=>$poll_id)); // add extra meta option here
			?>
			<style type="text/css">
					<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_container{
						color:<?php echo esc_attr($it_epoll_poll_container_text_color,'it_epoll');?> !important;
						background: -webkit-linear-gradient(40deg,<?php echo esc_attr($it_epoll_poll_container_color_primary,'it_epoll');?>,<?php echo esc_attr($it_epoll_poll_container_color_secondary,'it_epoll');?>)!important;
						background: -o-linear-gradient(40deg,<?php echo esc_attr($it_epoll_poll_container_color_primary,'it_epoll');?>,<?php echo esc_attr($it_epoll_poll_container_color_secondary,'it_epoll');?>)!important;
						background: linear-gradient(40deg,<?php echo esc_attr($it_epoll_poll_container_color_primary,'it_epoll');?>,<?php echo esc_attr($it_epoll_poll_container_color_secondary,'it_epoll');?>)!important;
					}
					
					
					<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_orange_gradient, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_big_cover img, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_big_cover{
						background: -webkit-linear-gradient(50deg,<?php echo esc_attr($it_epoll_poll_button_color_primary,'it_epoll');?>,<?php echo esc_attr($it_epoll_poll_button_color_secondary,'it_epoll');?>)!important;
						background: -o-linear-gradient(50deg,<?php echo esc_attr($it_epoll_poll_button_color_primary,'it_epoll');?>,<?php echo esc_attr($it_epoll_poll_button_color_secondary,'it_epoll');?>)!important;
						background: linear-gradient(40deg,<?php echo esc_attr($it_epoll_poll_button_color_primary,'it_epoll');?>,<?php echo esc_attr($it_epoll_poll_button_color_secondary,'it_epoll');?>)!important;
						color:<?php echo esc_attr($it_epoll_poll_button_text_color,'it_epoll');?> !important;
					}
					<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_title_exact, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .epoll_poll_contest_powered_by a{
						color:<?php echo esc_attr($it_epoll_poll_button_text_color,'it_epoll');?> !important;
					}
					
					<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_card_front .it_epoll_green_gradient {
						background: -webkit-linear-gradient(50deg,#aee86a,#4CAF50) !important;
						background: -o-linear-gradient(50deg,#aee86a,#4CAF50) !important;
						background: linear-gradient(40deg,#aee86a,#4CAF50) !important;
					}
					<?php do_action('it_epoll_poll_add_custom_css',array('poll_id'=>$poll_id));?>
				</style>
				<div class="eg_main_content" id="it_epoll_container_<?php echo esc_attr($poll_id,'it_epoll');?>">
			<div class="it_epoll_container">
			<?php do_action('it_epoll_poll_ui_before_title',array('poll_id'=>$poll_id)); // add extra meta option here?>
	
				<div class="it_epoll_title">
						<span class="it_epoll_survey-stage">
						<?php if($it_epoll_poll_status == 'live'){?>
							<span class="it_epoll_stage it_epoll_live it_epoll_active"><?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_live_badge_text'),'it_epoll');?></span>
						<?php }elseif($it_epoll_poll_status == 'upcoming'){?>
							<span class="it_epoll_stage it_epoll_ended it_epoll_active"><?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_upcoming_badge_text'),'it_epoll');?></span>
						<?php }else{?>
							<span class="it_epoll_stage it_epoll_ended it_epoll_active"><?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_end_badge_text'),'it_epoll');?></span>
						<?php }?>
						</span>
						<span class="it_epoll_title_exact"><?php the_title();?></span>
					
						<?php if(get_option('it_epoll_settings_voting_social_sharing')  && get_post_meta($poll_id,'it_epoll_social_sharing_opt',true)){?>
						<div class="epoll_grid eg_grid_auto_right eg_text-right eg_p-0 eg_p_relative it_epoll_poll_share_btn" id="it_epoll_opinion_share_btn">	
								<span class="eg_badge eg_badge_btn eg_badge_btn_share"><?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_share_badge_text'),'it_epoll');?> &nbsp;<i class="eicon eicon-right-arrow"></i></span>
						</div>
					<?php }?>
				</div>
				<?php do_action('it_epoll_poll_ui_after_title',array('poll_id'=>$poll_id)); // add extra meta option here?>
	
				<div class="it_epoll_inner">
				<ul class="it_epoll_surveys <?php if($it_epoll_poll_style == 'list') echo esc_attr('it_epoll_list','it_epoll'); else echo esc_attr('it_epoll_grid','it_epoll');?>">
		<?php
			$i=0;
			if($it_epoll_option_names){
			foreach($it_epoll_option_names as $it_epoll_option_name):
			$it_epoll_poll_vote_count = (int)get_post_meta($poll_id, 'it_epoll_vote_count_'.$it_epoll_poll_option_id[$i],true);
		
			$it_epoll_poll_vote_percentage =0;
			if($it_epoll_poll_vote_count == 0){
			$it_epoll_poll_vote_percentage =0;
			}else{
				if($it_epoll_poll_vote_count >= 1 && $it_epoll_poll_vote_total_count){
					$it_epoll_poll_vote_percentage = (int) $it_epoll_poll_vote_count*100/$it_epoll_poll_vote_total_count; 
				}else{
					$it_epoll_poll_vote_percentage = 0;
				}
			}
			$it_epoll_poll_vote_percentage = (int)$it_epoll_poll_vote_percentage;
			?>
			<?php if($it_epoll_poll_style == 'list'){?>
				<li class="it_epoll_survey-item" id="epoll_poll_option_id_<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>">
				<input type="hidden" name="it_epoll_multivoting" value="<?php echo esc_attr(get_post_meta($poll_id,'it_epoll_poll_multichoice',true),'it_epoll');?>" id="it_epoll_multivoting">
				<div class="it_epoll_survey-item-inner">
			<?php if(isset($it_epoll_option_imgs[$i])){
					if(!empty($it_epoll_option_imgs[$i])){
					?>
				<div class="it_epoll_survey-icon it_epoll_list_icon">
						<img src="<?php echo esc_url($it_epoll_option_imgs[$i]);?>">
					</div>
					<?php }}?>
					<?php do_action('it_epoll_poll_ui_before_option_name',array('poll_id'=>$poll_id)); // add extra meta option here?>
	
				<div class="it_epoll_survey-name">
				  <?php echo esc_html( $it_epoll_option_name );?>
				</div>
				<?php do_action('it_epoll_poll_ui_after_option_name',array('poll_id'=>$poll_id)); // add extra meta option here?>
	

			<div class="it_epoll_survey-item-action <?php if(it_epoll_check_for_unique_voting($poll_id,$it_epoll_poll_option_id[$i])) echo esc_attr('it_epoll_survey-item-action-disabled','it_epoll');?>">
				<?php if(!it_epoll_check_for_unique_voting($poll_id,$it_epoll_poll_option_id[$i])):?>
				
				<?php do_action('it_epoll_poll_list_ui_vote_button',array('poll_id'=>$poll_id,'option_id'=>$it_epoll_poll_option_id[$i])); // add extra meta option here?>

				<?php else:?>
					<span class="it_epoll_green_gradient it_epoll_list_style_already_voted">
						<?php esc_attr_e('Already Voted','it_epoll');?>
					</span>
				<?php endif;?>
			</div>
			<div class="it_epoll_spinner">
				<svg version="1.1" id="it_epoll_tick" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					viewBox="0 0 37 37" xml:space="preserve">
				<path class="it_epoll_circ it_epoll_path" style="fill:none;stroke: #ffffff;stroke-width:2;stroke-linejoin:round;stroke-miterlimit:10;" d="
				M30.5,6.5L30.5,6.5c6.6,6.6,6.6,17.4,0,24l0,0c-6.6,6.6-17.4,6.6-24,0l0,0c-6.6-6.6-6.6-17.4,0-24l0,0C13.1-0.2,23.9-0.2,30.5,6.5z"
				/>
					<polyline class="it_epoll_tick it_epoll_path" style="fill:none;stroke: #ffffff;stroke-width:2;stroke-linejoin:round;stroke-miterlimit:10;" points="
				11.6,20 15.9,24.2 26.4,13.8 "/>
				</svg>
			</div>
			<?php do_action('it_epoll_poll_ui_before_progress_bar',array('poll_id'=>$poll_id)); // add extra meta option here?>
				
			<?php if((!get_option('it_epoll_settings_hide_voting_result') && $it_epoll_result_visibility == 'public') || ($it_epoll_result_visibility == 'after_vote_end' && !$epoll_hide_element)){?>
					
				<div class="it_epoll_pull">

				  <span class="it_epoll_survey-progress">
					<span class="it_epoll_survey-progress-bg">
					  <span class="it_epoll_survey-progress-fg it_epoll_orange_gradient" style="width:<?php echo esc_attr( $it_epoll_poll_vote_percentage );?>%;"></span>
				  </span>

				  <span class="it_epoll_survey-progress-labels">
					  <span class="it_epoll_survey-progress-label">
					  <?php echo esc_html( $it_epoll_poll_vote_percentage );?>%
					  </span>

				  <input type="hidden" name="it_epoll_poll_e_vote_count" id="it_epoll_poll_e_vote_count" value="<?php echo esc_attr( $it_epoll_poll_vote_count );?>"/>
				  <span class="it_epoll_survey-completes">
						<?php echo esc_attr(it_epoll_number_shorten($it_epoll_poll_vote_count)." / ".it_epoll_number_shorten($it_epoll_poll_vote_total_count),'it_epoll');?>
					  </span>
				  </span>
				  </span>
				</div>
				<?php }?>
				<?php do_action('it_epoll_poll_ui_after_progress_bar',array('poll_id'=>$poll_id)); // add extra meta option here?>
	
				</div>
		  </li>
			<?php }else{?>
		  <li class="it_epoll_survey-item" id="epoll_poll_option_id_<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>">
		  <input type="hidden" name="it_epoll_multivoting" value="<?php echo esc_attr(get_post_meta($poll_id,'it_epoll_poll_multichoice',true),'it_epoll');?>" id="it_epoll_multivoting">
				
		  <?php do_action('it_epoll_poll_before_front_card',array('poll_id'=>$poll_id,'option_id'=>$it_epoll_poll_option_id[$i])); // add extra meta option here?>
	
			<div class="it_epoll_survey-item-inner it_epoll_card_front">
				<div class="it_epoll_big_cover">
					  <?php if($it_epoll_poll_option_cover_img){
	
						 	if(isset($it_epoll_poll_option_cover_img[$i])){
								if(!empty($it_epoll_poll_option_cover_img[$i])){?>
									<img src="<?php echo esc_url($it_epoll_poll_option_cover_img[$i],'it_epoll');?>"/>
								<?php }
							 	
							 }
					}?>		
				</div>
				
				<?php if(isset($it_epoll_option_imgs[$i])){
					if(!empty($it_epoll_option_imgs[$i])){
					?>
				<div class="it_epoll_survey-country it_epoll_grid-only">
				  <img src="<?php echo esc_url($it_epoll_option_imgs[$i]);?>">
				  <div class="it_epoll_spinner">
				  	<svg version="1.1" id="it_epoll_tick" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 				viewBox="0 0 37 37" xml:space="preserve">
					<path class="it_epoll_circ it_epoll_path" style="fill:none;stroke: #ffffff;stroke-width:1.5;stroke-linejoin:round;stroke-miterlimit:10;" d="
				M30.5,6.5L30.5,6.5c6.6,6.6,6.6,17.4,0,24l0,0c-6.6,6.6-17.4,6.6-24,0l0,0c-6.6-6.6-6.6-17.4,0-24l0,0C13.1-0.2,23.9-0.2,30.5,6.5z"
				/>
					<polyline class="it_epoll_tick it_epoll_path" style="fill:none;stroke: #ffffff;stroke-width:1.5;stroke-linejoin:round;stroke-miterlimit:10;" points="
				11.6,20 15.9,24.2 26.4,13.8 "/>
				</svg>
				  </div>
				</div>

				<?php 
					}
			}?>
			<?php do_action('it_epoll_poll_ui_before_option_name',array('poll_id'=>$poll_id)); // add extra meta option here?>
	
				<div class="it_epoll_survey-name">
				  <?php echo esc_html($it_epoll_option_name);?>
				</div>
			
			

				<div class="it_epoll_survey-subtitle">
					<?php do_action('it_epoll_poll_ui_after_option_name',array('poll_id'=>$poll_id)); // add extra meta option here?>
				</div>
				

				<?php do_action('it_epoll_poll_ui_before_vote_button',array('poll_id'=>$poll_id)); // add extra meta option here?>
				
				<div class="it_epoll_survey-item-action<?php if(it_epoll_check_for_unique_voting($poll_id,$it_epoll_poll_option_id[$i])) echo esc_attr(' it_epoll_survey-item-action-disabled','it_epoll');?>">
					<?php 
					if(!it_epoll_check_for_unique_voting($poll_id,$it_epoll_poll_option_id[$i])){?>
						
						<?php do_action('it_epoll_poll_ui_vote_button',array('poll_id'=>$poll_id,'option_id'=>$it_epoll_poll_option_id[$i])); // add extra meta option here?>

						<?php }else{ ?>
							<div class="it_epoll_already_voted">
								<?php esc_attr_e('You Already Participated!','it_epoll');?>
							</div>
						<?php }?>
				</div>
				
				
				
				<?php do_action('it_epoll_poll_ui_after_vote_button',array('poll_id'=>$poll_id)); // add extra meta option here?>
				<?php if((!get_option('it_epoll_settings_hide_voting_result') && $it_epoll_result_visibility == 'public') || ($it_epoll_result_visibility == 'after_vote_end' && !$epoll_hide_element)){?>
						<div class="it_epoll_pull-right">

						<span class="it_epoll_survey-progress">
							<span class="it_epoll_survey-progress-bg">
							<span class="it_epoll_survey-progress-fg it_epoll_orange_gradient" style="width:<?php echo esc_attr( $it_epoll_poll_vote_percentage);?>%;"></span>
							<span class="it_epoll_survey-progress-label epoll_green_badge epoll_progress_bar_tip"  style="<?php  if($it_epoll_poll_vote_percentage > 50) echo esc_attr(' left: calc( '.$it_epoll_poll_vote_percentage.'% - 38px); border-bottom-right-radius: 0;','it_epoll'); else echo esc_attr(' left: calc( '.$it_epoll_poll_vote_percentage.'% - 1px);  border-bottom-left-radius: 0;','it_epoll');?>"><?php echo esc_attr( $it_epoll_poll_vote_percentage,'it_epoll');?>%</span>
						</span>

						<span class="it_epoll_survey-progress-labels">
							<span class="it_epoll_survey-progress-label">
								<?php echo esc_html( $it_epoll_poll_vote_percentage );?>%
							</span>
							<input type="hidden" name="it_epoll_poll_e_vote_count" id="it_epoll_poll_e_vote_count" value="<?php echo esc_attr( $it_epoll_poll_vote_count,'it_epoll');?>"/>
						<span class="it_epoll_survey-complete">
								<?php echo esc_attr(it_epoll_number_shorten($it_epoll_poll_vote_count),'it_epoll');?>/ <?php echo esc_attr(it_epoll_number_shorten($it_epoll_poll_vote_total_count),'it_epoll');?>
							</span>
						</span>
						</span>
					</div>
				<?php }?>
					
					<?php do_action('it_epoll_poll_ui_after_progress_bar',array('poll_id'=>$poll_id)); // add extra meta option here?>
				
				</div>
				<?php do_action('it_epoll_poll_after_front_card',array('poll_id'=>$poll_id,'option_id'=>$it_epoll_poll_option_id[$i])); // add extra meta option here?>
	
		  </li>
		<?php }?>
<?php
	$i++;
	endforeach;?>
	</ul> 
	<div style="clear:both;"></div>	
			<?php }else{
				if( current_user_can('author') || current_user_can('editor') || current_user_can('administrator') ){
					echo esc_attr('<p class="it_epoll_short_code">Please add some questions or may be you missed the option field.</p><br><a href="'.get_edit_post_link($poll_id).'" class="it_epoll_survey-notfound-button" style="width:auto;max-width:100%;">Edit This Poll</a>','it_epoll');
				}else{
					echo esc_attr('<p class="it_epoll_short_code">This Poll is not yet ready contact site administrator</p>','it_epoll');
				}
				
			}?>

		<?php do_action('it_epoll_poll_ui_after_options',array('poll_id'=>$poll_id)); // add extra meta option here?>
	</div>
	<?php if(get_option('it_epoll_settings_voting_social_sharing')){?>
		<div class="it_epoll_container_alert it_epoll_container_alert_top" id="it_epoll_share_alert">
									<div class="it_epoll_container_alert_inner epoll_no_shadow">
								<h3>
									
									<span><?php esc_attr_e('Share on Social Media','it_epoll');?></span>
									<span class="it_epoll_container_alert_close"><i class="eicon eicon-cancel"></i></span> 
								</h3>
				<div id="it_epoll_opinion_share_btn" class="it_epoll_poll_share_btn_container">
					<ul class="epoll_poll_share_container">
						<?php if(!get_option('it_epoll_settings_social_option_facebook') && !get_option('it_epoll_settings_social_option_twitter') && !get_option('it_epoll_settings_social_option_whatsapp')){?> 
								<p><?php esc_attr_e('Please Enable social media sharing platforms eg facebook, twitter from epoll settings','it_epoll');?></p>
							<?php }?> 
						<?php if(get_option('it_epoll_settings_social_option_facebook')){?>
							<li>
								<a href="<?php echo esc_url('https://www.facebook.com/sharer/sharer.php?u='.get_the_permalink().'&t='.it_epoll_get_branding_sharer_text(),'it_epoll');?>" target="_blank"><i class="eicon eicon-facebook"></i> <?php esc_attr_e('Share on Facebook','it_epoll');?></a>
							</li>
						<?php }?>
						<?php if(get_option('it_epoll_settings_social_option_twitter')){?>
							<li>
								<a href="<?php echo esc_url('http://twitter.com/share?text='.get_the_title().it_epoll_get_branding_sharer_text().'&url='.get_the_permalink(),'it_epoll');?>" target="_blank"><i class="eicon eicon-twitter"></i> <?php esc_attr_e('Share on Twitter','it_epoll');?></a>
							</li>
						<?php }?>
						<?php if(get_option('it_epoll_settings_social_option_whatsapp')){?>
							<li>
								<a href="<?php echo esc_url('https://wa.me/?text='.get_the_title().' You Can Vote Here : '.get_the_permalink().it_epoll_get_branding_sharer_text(),'it_epoll');?>" target="_blank"><i class="eicon eicon-whatsapp"></i> <?php esc_attr_e('Share on WhatsApp','it_epoll');?></a>
							</li>
						<?php }?>
						<?php do_action('it_epoll_opinion_ui_social_links',array('poll_id'=>$poll_id)); // add extra meta option here?>
					</ul>
					</div>
					
					</div>
								</div>
				<?php }?>
					
			<div class="epoll_poll_contest_powered_by">
				<a href="<?php echo esc_url('https://wordpress.org/plugins/epoll-wp-voting/','it_epoll');?>" target="_blank" rel="nofollow"><?php echo esc_attr(it_epoll_get_branding_text(),'it_epoll');?></a>
			</div>
	<?php do_action('it_epoll_after_end_of_poll',array('poll_id'=>$poll_id)); // add extra ui here?>
</div>
<?php do_action('it_epoll_after_poll',array('poll_id'=>$poll_id)); // add extra ui here?>
		<?php 
		if(get_option('it_epoll_settings_enable_comments')){?>

		<div class="it_epoll_comment_area it_epoll_comment_area_less">
			<?php
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;?>
		</div>
		<?php 
		}?>
	</div>
	<?php endwhile;

get_footer();
?>