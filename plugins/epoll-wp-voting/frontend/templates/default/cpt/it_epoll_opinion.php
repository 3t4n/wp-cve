<?php
it_epoll_init_unique_vote_session();
get_header();
while ( have_posts() ) : the_post();
			$poll_id = $post->ID;
			
			
			$it_epoll_option_names = array();
			if(get_post_meta( $poll_id, 'it_epoll_poll_option', true )){
				$it_epoll_option_names = get_post_meta( $poll_id, 'it_epoll_poll_option', true );
			}
			
			$it_epoll_poll_status = get_post_meta( $poll_id, 'it_epoll_poll_status', true );
			$it_epoll_poll_description = get_post_meta( $poll_id, 'it_epoll_poll_description', true );
			$it_epoll_poll_theme = get_post_meta( $poll_id, 'it_epoll_poll_theme', true );
			$it_epoll_poll_option_id = get_post_meta( $poll_id, 'it_epoll_poll_option_id', true );
			$it_epoll_vote_end_date_time = get_post_meta( $poll_id, 'it_epoll_vote_end_date_time', true );
			$it_epoll_poll_vote_total_count = get_post_meta($poll_id, 'it_epoll_vote_total_count',true);
			$it_epoll_result_visibility = get_post_meta($poll_id, 'it_epoll_poll_result_visibility',true);


			//color scheme
			$it_epoll_poll_option_text_color = get_post_meta($poll_id,'it_epoll_poll_option_text_color',true);
			$it_epoll_poll_button_text_color = get_post_meta($poll_id,'it_epoll_poll_button_text_color',true);
			$it_epoll_poll_color_primary = get_post_meta($poll_id,'it_epoll_poll_color_primary',true);
			$it_epoll_poll_color_secondary = get_post_meta($poll_id,'it_epoll_poll_color_secondary',true);
			$it_epoll_poll_color_mouseover = get_post_meta($poll_id,'it_epoll_poll_color_mouseover',true);
			$it_epoll_poll_color_result_color = get_post_meta($poll_id,'it_epoll_poll_color_result_color',true);


		
			if($it_epoll_poll_vote_total_count > 1){
				$it_epoll_poll_vote_total_count_text = sprintf(it_epoll_poll_get_ttext('it_epoll_settings_vote_numbers_text'),$it_epoll_poll_vote_total_count);
			}else{
				$it_epoll_poll_vote_total_count_text = sprintf(it_epoll_poll_get_ttext('it_epoll_settings_vote_number_text'),$it_epoll_poll_vote_total_count);
			}

			if($it_epoll_poll_status == 'live'){
				$epoll_hide_element = 'epoll_hide_element';
			}

			$it_epoll_vote_end_date_time_left ="";
			if($it_epoll_vote_end_date_time){

				$it_epoll_gen_date=strtotime($it_epoll_vote_end_date_time);//Converted to a PHP date (a second count)
				//Calculate difference
				$it_epoll_gen_diff=$it_epoll_gen_date-time();//time returns current time in seconds
				$it_epoll_gen_days=floor($it_epoll_gen_diff/(60*60*24));//seconds/minute*minutes/hour*hours/day)
				$it_epoll_gen_hours=round(($it_epoll_gen_diff-$it_epoll_gen_days*60*60*24)/(60*60));
				if($it_epoll_gen_days >= 0){
					if($it_epoll_gen_days > 0){
						if($it_epoll_gen_days > 1) $it_epoll_gen_days_text = __(' Days','it_epoll'); else $it_epoll_gen_days_text = __(' Day','it_epoll');
						$it_epoll_vote_end_date_time_left = $it_epoll_gen_days.$it_epoll_gen_days_text;
	
					}else if($it_epoll_gen_hours > 1){
						if($it_epoll_gen_hours > 1) $it_epoll_gen_hours_text = __(' Hours','it_epoll'); else $it_epoll_gen_hours_text = __(' Hour','it_epoll');
						$it_epoll_vote_end_date_time_left = $it_epoll_gen_hours.$it_epoll_gen_hours_text;
					}else{
						$it_epoll_gen_mins = $it_epoll_gen_hours/60;
						if($it_epoll_gen_mins > 1) $it_epoll_gen_mins_text = __(' Minutes','it_epoll'); else $it_epoll_gen_mins_text = __(' Minute','it_epoll');
						$it_epoll_vote_end_date_time_left = $it_epoll_gen_mins.$it_epoll_gen_mins_text;
					}
				}else{
					
					$it_epoll_vote_end_date_time_left = $it_epoll_vote_end_date_time_left = wp_kses('Voting Ended on <strong>'.$it_epoll_vote_end_date_time.'</strong>',array('strong'));
				}
				
							
			}
		?>
			<style type="text/css">
			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .ec_poll_border_top {
					border-color:<?php echo esc_attr($it_epoll_poll_color_primary,'it_epoll');?>;
			}
			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?>  .eg_button, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .epoll_powered_by_Link{
				background:<?php echo esc_attr($it_epoll_poll_color_primary,'it_epoll');?>;
				color:<?php echo esc_attr($it_epoll_poll_button_text_color,'it_epoll');?>;
				border-color:<?php echo esc_attr($it_epoll_poll_color_primary,'it_epoll');?>;
			}
			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .epoll_sc_share_container li a{
				color:<?php echo esc_attr($it_epoll_poll_color_primary,'it_epoll');?>;
			}
			 <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .eg_badge,.eg_button_secondary, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .eg_button:hover, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .eg_button:focus, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .eg_button:active, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .epoll_poll_option_wrapper ul.epoll_poll_options li .it_epoll_poll_opt, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .epoll_poll_option_wrapper ul.epoll_poll_options li .it_epoll_opt_radio_wrap .it_epoll_otp_result_right{
				color:<?php echo esc_attr($it_epoll_poll_color_primary,'it_epoll');?>;
				background:<?php echo esc_attr($it_epoll_poll_color_secondary,'it_epoll');?>;
				outline-color: <?php echo esc_attr($it_epoll_poll_color_mouseover,'it_epoll');?>;
				border-color: <?php echo esc_attr($it_epoll_poll_color_mouseover,'it_epoll');?>;
			}

			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_opt_radio_wrap [type="radio"]:checked + label, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_opt_radio_wrap [type="radio"]:not(:checked) + label {
				color: <?php echo esc_attr($it_epoll_poll_option_text_color,'it_epoll');?>;
			}

			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_opt_radio_wrap [type="radio"]:checked + label{
				color:<?php echo esc_attr($it_epoll_poll_color_primary,'it_epoll');?>;
			}
			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .epoll_badge_danger {
				background: #ff9b94;
				color: #a11f15;
				border: 0;
			}
			
			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .epoll_poll_option_wrapper ul.epoll_poll_options li .it_epoll_poll_opt:hover, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .epoll_poll_option_wrapper ul.epoll_poll_options li .it_epoll_poll_opt:active, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .epoll_poll_option_wrapper ul.epoll_poll_options li .it_epoll_poll_opt:focus{
				background:<?php echo esc_attr($it_epoll_poll_color_result_color,'it_epoll');?>;
			}

			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_otp_result_wrap, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_opt_radio_wrap [type="radio"]:checked + label:before, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_opt_radio_wrap [type="radio"]:not(:checked) + label:before{
				background: <?php echo esc_attr($it_epoll_poll_color_mouseover,'it_epoll');?>;
    			box-shadow: 0 0 2px <?php echo esc_attr($it_epoll_poll_color_mouseover,'it_epoll');?>;
			}
			
			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_opt_radio_wrap [type="radio"]:checked + label:before {
    			box-shadow: 0 0 2px <?php echo esc_attr($it_epoll_poll_color_primary,'it_epoll');?>;
			}

			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_otp_result_right{
				color:<?php echo esc_attr($it_epoll_poll_option_text_color,'it_epoll');?>;
			}
			<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_opt_radio_wrap [type="radio"]:checked + label:after, <?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> .it_epoll_opt_radio_wrap [type="radio"]:not(:checked) + label:after{
				background: <?php echo esc_attr($it_epoll_poll_color_primary,'it_epoll');?>;
			}
			<?php do_action('it_epoll_poll_add_custom_css',array('poll_id'=>$poll_id));?>
			
		</style>
			<div class="eg_main_content" id="it_epoll_container_<?php echo esc_attr($poll_id,'it_epoll');?>">
<div class="epoll_container" id="epoll_container_opinion">
			<!-- Start Category Grids-->
			<div class="epoll_grids">
			<!-- Start Category Grid-->
			<div class="epoll_grid">
				<!-- Start Category Card-->
				<div class="epoll_card ec_epoll_extra_radius ec_epoll_extra_radius_card">
					<!-- Start Category Card Inner-->
					<div class="epoll_card_front_face ec_epoll_extra_radius ec_poll_border_top">
						<!-- Start Category Gradient-->
						<div class="epoll_category_gradient epoll_poll_gradient ec_epoll_extra_radius_card epoll_hide_element">
							<?php do_action('it_epoll_opinion_ui_gradient_content',array('poll_id'=>$poll_id)); // add extra meta option here?>
						</div>
						<!-- Start Category Content-->
						<div class="epoll_category_content">
						<!-- Start Category Badges-->
						<div class="epoll_category_badges epoll_grids">
							<!-- Start Category Name-->
							<div class="epoll_grid eg_grid_auto_left eg_text-left eg_pb-0 eg_pt-2">
								<?php if($it_epoll_poll_status == 'live'){?>
										<span class="eg_badge epoll_badge_danger"><i class="eicon eicon-wifi"></i> <?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_live_badge_text'),'it_epoll');?></span>
									<?php }elseif($it_epoll_poll_status == 'upcoming'){?>
										<span class="eg_badge"><i class="eicon eicon-rotation"></i> <?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_end_badge_text'),'it_epoll');?></span>
									<?php }else{?>
										<span class="eg_badge"><i class="eicon eicon-rotation"></i> <?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_upcoming_badge_text'),'it_epoll');?></span>
									<?php } ?>
							</div>
							<!-- End Category Name-->
							<?php do_action('it_epoll_opinion_ui_before_social_links',array('poll_id'=>$poll_id)); ?>  
							<!-- Start Poll Type-->
							<div class="epoll_grid eg_grid_auto_right eg_text-right eg_pb-0 eg_pt-2 eg_p_relative" id="it_epoll_opinion_share_btn">
								<?php if(get_option('it_epoll_settings_poll_social_sharing') && get_post_meta($poll_id,'it_epoll_social_sharing_opt',true)){?>
								
								<span class="eg_badge eg_badge_btn eg_badge_btn_share"><?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_share_badge_text'),'it_epoll');?> &nbsp;<i class="eicon eicon-right-arrow"></i></span>
								<ul class="epoll_sc_share_container hide_epoll_sc_share_container">
									<?php if(get_option('it_epoll_settings_social_option_facebook')){?>
										<li>
											<a href="<?php echo esc_url('https://www.facebook.com/sharer/sharer.php?u='.get_the_permalink().'&t='.it_epoll_get_branding_sharer_text(),'it_epoll');?>" target="_blank"><i class="eicon eicon-facebook"></i> <?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_share_on_menu_text').' Facebook','it_epoll');?></a>
										</li>
									<?php }?>
									<?php if(get_option('it_epoll_settings_social_option_twitter')){?>
										<li>
											<a href="<?php echo esc_url('https://twitter.com/share?text='.get_the_title().' '.it_epoll_get_branding_sharer_text().'&url='.get_the_permalink(),'it_epoll');?>" target="_blank"><i class="eicon eicon-twitter"></i> <?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_share_on_menu_text').' Twitter','it_epoll');?></a>
										</li>
									<?php }?>
									<?php if(get_option('it_epoll_settings_social_option_whatsapp')){?>
										<li>
											<a href="<?php echo esc_url('https://wa.me/?text='.get_the_title().' You Can Vote Here : '.get_the_permalink().' '.it_epoll_get_branding_sharer_text(),'it_epoll');?>" target="_blank"><i class="eicon eicon-whatsapp"></i> <?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_share_on_menu_text').' WhatsApp','it_epoll');?></a>
										</li>
									<?php }?>
									<?php do_action('it_epoll_opinion_ui_social_links',array('poll_id'=>$poll_id)); // add extra meta option here?>
								</ul>
								<?php }?>
							</div>
							<!-- End Poll Type-->
							<div class="epoll-clr"></div>
						</div>
						<!-- End Category badges-->
						<!-- Start Poll Name-->
						<?php do_action('it_epoll_opinion_ui_before_title',array('poll_id'=>$poll_id)); // add extra meta option here?>
							
						<h3 class="epoll_poll_ttl epoll_poll_single_ttl eg_text-left eg_m-0">
							<?php the_title();?>
						</h3>
						<?php do_action('it_epoll_opinion_ui_after_title',array('poll_id'=>$poll_id)); // add extra meta option here?>
								
						<!-- End Poll Name-->
						<!-- Start Poll Date-->
						<p class="epoll_poll_sub_ttl epoll_poll_sub_single_ttl eg_text-left eg_py-0">
							<?php echo esc_html($it_epoll_poll_description,'it_epoll');?>
						</p>
						<?php do_action('it_epoll_opinion_ui_after_desc',array('poll_id'=>$poll_id)); // add extra meta option here?>
						
						<!-- End Poll Date-->
						</div>
						<!-- End Category Content-->
			<!-- Start Epoll Wrapper -->
			
			<form class="epoll_poll_opinion_form" name="epoll_poll_opinion_form" id="epoll_poll_opinion_form" method="post" action="">
			<div class="epoll_poll_option_wrapper">
				<input type="hidden" name="it_epoll_poll_id" id="it_epoll_poll-id" value="<?php echo esc_attr($poll_id,'it_epoll');?>" required/>	
			<ul class="epoll_poll_options">
			<?php do_action('it_epoll_opinion_ui_pre_options',array('poll_id'=>$poll_id)); // add extra meta option here?>
						
		<?php
		if(get_post_meta($poll_id,'it_epoll_poll_multichoice',true)){?>
		<input type="hidden" class="it_epoll_multi_vote"  name="it_epoll_multi_vote" value="1"/>
					
		<?php }else{?>
			<input type="hidden" class="it_epoll_multi_vote"  name="it_epoll_multi_vote" value="0"/>
		<?php }

			$i=0;
			if($it_epoll_option_names){
			foreach($it_epoll_option_names as $it_epoll_option_name):
			$it_epoll_poll_vote_count = (int)get_post_meta($poll_id, 'it_epoll_vote_count_'.$it_epoll_poll_option_id[$i],true);
			
			$it_epoll_poll_vote_percentage =0;
			if($it_epoll_poll_vote_count >= 1 && $it_epoll_poll_vote_total_count){
				$it_epoll_poll_vote_percentage = (int)$it_epoll_poll_vote_count*100/$it_epoll_poll_vote_total_count; 
				$it_epoll_poll_vote_count_text = sprintf(it_epoll_poll_get_ttext('it_epoll_settings_vote_number_text'),$it_epoll_poll_vote_count);
			}else{
				$it_epoll_poll_vote_percentage = 0; 
				$it_epoll_poll_vote_count_text = sprintf(it_epoll_poll_get_ttext('it_epoll_settings_votes_number_text'),$it_epoll_poll_vote_count); 
			}
			
			$it_epoll_poll_vote_percentage = (int)$it_epoll_poll_vote_percentage;
			if($it_epoll_poll_status =='live' && !it_epoll_check_for_unique_voting($poll_id,$it_epoll_poll_option_id[$i])){
				$epoll_hide_element = true;
			}else{
				$epoll_hide_element = false;
			}
			
			?>
			<!-- EPOLL poll option-->
			<li class="eg_text-left epoll_poll_option_item" id="epoll_poll_option_id_<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>">
				<!-- Start Option Wrapper-->
				<div class="it_epoll_poll_opt">
				<?php 
				
				if((!get_option('it_epoll_settings_hide_voting_result') && $it_epoll_result_visibility == 'public') || ($it_epoll_result_visibility == 'after_vote_end' && $it_epoll_poll_status == 'end')){?>
				
					<!-- Start Option Progressbar -->
					<div class="it_epoll_poll_opt_progressbar<?php if($epoll_hide_element) echo esc_attr(' epoll_hide_element','it_epoll');?>" data-count="<?php echo esc_html($it_epoll_poll_vote_percentage,'it_epoll');?>%">
					
					<span class="it_epoll_otp_result_wrap" style="width:0%;"></span>
						
					</div>
					<!-- End Option Progressbar -->
					<?php } ?>
					<!-- Start Checkbox Action-->
						<div class="it_epoll_opt_radio_wrap">
							<div class="it_epoll_opt_radio_wrap_inner">
								<?php if(get_post_meta($poll_id,'it_epoll_poll_multichoice',true)){?>
								<input type="checkbox" class="it_epoll_opt_radio" id="epoll_poll_option_label_<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>" value="<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>" name="it_epoll_option_id[]" required <?php if(!$epoll_hide_element) echo esc_attr('disabled','it_epoll');?>/>
								<?php }else{?>
								
								<input type="radio" class="it_epoll_opt_radio" id="epoll_poll_option_label_<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>" value="<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>" name="it_epoll_option_id[]" required <?php if(!$epoll_hide_element) echo esc_attr('disabled','it_epoll');?>/>
								<?php }?>
									<!-- Start Checkbox Action title-->
								<label for="epoll_poll_option_label_<?php echo esc_attr($it_epoll_poll_option_id[$i],'it_epoll');?>" class="epoll_show_radio<?php if(!$epoll_hide_element) echo esc_attr(' epoll_hide_radio','it_epoll');?>"><span><?php echo esc_html($it_epoll_option_name,'it_epoll');?></span></label>
								<!-- end Checkbox Action title-->
							</div>
							<!-- Start ProgressBar Count -->
							<div class="it_epoll_otp_result_right eg_text-right"><?php echo esc_html($it_epoll_poll_vote_percentage,'it_epoll');?>%
								<span><?php echo esc_html($it_epoll_poll_vote_count_text,'it_epoll');?></span>
							</div>
							<!-- End ProgressBar Count -->
						</div>
						<!-- end Checkbox Action-->
					</div>
					<!--End Option Wrapper-->
			</li>
			<!-- End poll option-->
	<?php
			do_action('it_epoll_opinion_ui_post_options',array('poll_id'=>$poll_id)); // add extra meta option here
					
		$i++;
		endforeach;?>
	
		<?php 
			}else{
				if( current_user_can('author') || current_user_can('editor') || current_user_can('administrator') ){
					echo esc_attr('<p class="it_epoll_short_code">Please add some questions or may be you missed the option field.</p><br><a href="'.get_edit_post_link($poll_id).'" class="it_epoll_survey-notfound-button" style="width:auto;max-width:100%;">Edit This Poll</a>','it_epoll');
				}else{
					echo esc_attr('<p class="it_epoll_short_code">This Poll is not yet ready contact site administrator</p>','it_epoll');
				}
				
			}?>
		</ul>
		<?php  if(!$epoll_hide_element){?>
			<script type="text/javascript">
				jQuery(document).ready(function(){

					
			jQuery('<?php echo esc_attr('#it_epoll_container_'.$poll_id,'it_epoll');?> #epoll_container_opinion').each(function(){
			var it_epoll_opionion_item = jQuery(this);
			jQuery(it_epoll_opionion_item).find('.epoll_poll_options li').each(function(){
				jQuery(this).find('.it_epoll_otp_result_wrap').css({width:'0%',opacity:'0'});


				var progress = jQuery(this).find('.it_epoll_poll_opt_progressbar').data('count');
				jQuery(this).find('.it_epoll_poll_opt_progressbar').removeClass('epoll_hide_element');
				jQuery(this).find('.it_epoll_otp_result_wrap').animate({width:progress,opacity:'1'});
				jQuery(this).find('.it_epoll_opt_radio_wrap [type="radio"] + label').animate({paddingLeft:'0px'});
			});
			});
			<?php do_action('it_epoll_opinion_ui_option_js_script',array('poll_id'=>$poll_id));?> // add extra meta option here

			});		
				</script>
		<?php }?>
		
</div>
	<!-- Start Category Action-->
	<div class="epoll_category_action eg_pb-2">
	<?php if(get_option('it_epoll_settings_hcaptcha_voting')){
								$site_key = get_option('it_epoll_settings_hcaptcha_key');
								$seceret = get_option('it_epoll_settings_hcaptcha_salt');
								?>
								<script src='https://www.hCaptcha.com/1/api.js' async defer></script>
								<div class="it_epoll_container_alert" id="it_epoll_hcaptcha">
									<div class="it_epoll_container_alert_inner">
										<h3><span class="it_epoll_container_alert_close"><i class="eicon eicon-left-arrow"></i> Back</span> <span><?php esc_attr_e('Validate that you are not a Robot','it_epoll');?></span></h3>
										<div class="h-captcha it_epoll_hcaptcha" data-size="normal" data-sitekey="<?php echo esc_attr($site_key,'it_epoll');?>"></div>
									</div>
								</div>
								
								<?php }?>
						<!-- Start Action Grids-->
						<div class="epoll_grids eg_border_t eg_border_solid eg_border_accent eg_pt-2 eg_pb-2">
							<!-- Start Action Grid-->
							<div class="epoll_grid eg_col eg_col-8  eg_text-left eg_p-0 eg_mobile_text_center">
								<span class="epoll_category_count it_epoll_total_vote_count"><?php echo esc_attr($it_epoll_poll_vote_total_count_text,'it_epll');?></span>
								<span class="epoll_cat_delim">.</span>
								<span class="epoll_category_count"><?php echo esc_attr(sprintf(it_epoll_poll_get_ttext('it_epoll_settings_time_left_text'),$it_epoll_vote_end_date_time_left),'it_epoll');?></span>
								<?php do_action('it_epoll_opinion_ui_after_extra_meta',array('poll_id'=>$poll_id)); // add extra meta option here?>
				
							</div>
							<!-- End Action Grid-->
						
							
							
							<!-- Start Action Grid-->
							<div class="epoll_grid eg_col eg_col-4 eg_py-0 eg_pl-0 eg_text-right">
							<?php do_action('it_epoll_opinion_ui_before_vote_buttons',array('poll_id'=>$poll_id)); // add extra meta option here?>
				
							<?php 
							
							
							if($epoll_hide_element){?>
							<?php if((!get_option('it_epoll_settings_hide_voting_result') && $it_epoll_result_visibility == 'public') || ($it_epoll_result_visibility == 'after_vote_end' && $it_epoll_poll_status == 'end')){?>

							<button type="button" class="eg_button eg_button_big_x eg_button_secondary eg_mb-2_mobile eg_mt-1 eg_mr-2" id="epoll_opinion_show_result_button" style="<?php if(get_option('it_epoll_settings_hide_poll_result')) echo esc_attr('display:none;','it_epoll');?>"><i class="eicon eicon-pie-chart"></i> <?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_result_button_text'),'it_epoll');?></button>	
								
							<button type="button" class="eg_button eg_button_big_x eg_button_secondary eg_mb-2_mobile eg_mt-1 epoll_hide_element" id="epoll_opinion_hide_result_button" style="width:100%;"><i class="eicon eicon-left-arrow-1"></i>  <?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_result_back_button_text'),'it_epoll');?></button>	
							<?php }?>
							<button class="eg_button eg_button_big_x" type="submit" id="epoll_opinion_vote_button"><?php echo esc_attr(it_epoll_poll_get_ttext('it_epoll_settings_vote_button_text'),'it_epoll');?></button>
							
							<?php }?>
							<?php do_action('it_epoll_opinion_ui_after_vote_buttons',array('poll_id'=>$poll_id)); // add extra meta option here?>
				
							</div>
							
							<!-- End Action Grid-->
							
						</div>
						<?php do_action('it_epoll_opinion_ui_after_options',array('poll_id'=>$poll_id)); // add extra meta option here?>
						
							<a href="<?php echo esc_url('https://wordpress.org/plugins/epoll-wp-voting/','it_epoll');?>" target="_blank" class="epoll_powered_by_Link">
							<i class="eicon eicon-info"></i> <?php echo esc_attr(it_epoll_get_branding_text(),'it_epoll');?>
							</a>
						<!-- End Action Grids-->
						</div>
									
						<div class="epoll_poll_loader">
							<div class="epoll_loading"></div>
						</div>
						<!-- End Category Action-->
						</form>
						
					</div>
					<!-- End Category Card Inner-->
				</div>
				<!-- Start Category Card-->
			</div>
			<?php do_action('it_epoll_opinion_ui_after_poll',array('poll_id'=>$poll_id)); // add extra meta option here?>
					
			<!-- End Category Grid-->
			<?php do_action('it_epoll_after_end_of_poll',array('poll_id'=>$poll_id)); // add extra ui here?>
			</div>
			<!-- End Category Container-->
			
		</div>
		
<?php do_action('it_epoll_after_poll',array('poll_id'=>$poll_id)); // add extra ui here?>

<?php 
		if(get_option('it_epoll_settings_enable_comments')){?>

		<div class="it_epoll_comment_area">
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