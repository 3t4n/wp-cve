<?php
	global $directorypress_object;
	$directorypress_faqs = get_post_meta($directorypress_object->current_listing->post->ID, '_listing_faqs', true);
	//print_r($faqs);
	if(!empty($directorypress_faqs)){
		$faqtitle =$directorypress_faqs['faqtitle'];
		$faqanswer =$directorypress_faqs['faqanswer'];
	}
	$listing_faq_text = 'faq';
	$listing_faq_tabs_text = 'faqs'; 
?>	
<?php if($DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_faqs']): ?>
	<div class="field-wrap">
		<p class="directorypress-submit-field-title">
			<?php _e('Frequently Asked Questions', 'directorypress-frontend'); ?>
			<?php do_action('directorypress_listing_submit_admin_info', 'listing_faqs'); ?>
		</p>
		<div id="faq-wrapper" class="clearfix pos-relative">
			<div id="accordion">
				<?php
				$is_data = false;
				if(!empty($faqtitle) && !empty($faqanswer)){
					foreach($faqtitle as $faqdata){
						if($faqdata == ""){
													
						}else{
							$is_data = true;
						}
					}
				}
				if($is_data==true){

					$n=count($faqtitle);
					if($n>1){
						$j=1;
						while($j <= $n){
							$faqQ =$faqtitle[$j];
							if(!empty($faqQ)){ ?>
													
								<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
											<a data-toggle="collapse" data-parent="#faq-wrapper" href="#collapse<?php echo esc_attr($j); ?>" data-faq-heading="Faq"><?php echo sprintf(esc_html__('Faq %s', 'DIRECTORYPRESS'), $j); ?></a>
										</h4>
									</div>
									<div id="collapse<?php echo esc_attr($j); ?>" class="panel-collapse collapse">
										<div class="panel-body">
											<input type="text" class="form-control" name="faqtitle[<?php echo esc_attr($j); ?>]" placeholder="<?php echo _e('FAQ', 'DIRECTORYPRESS'); ?>" value="<?php echo esc_attr($faqtitle[$j]); ?>">									
											<textarea class="form-control" placeholder="<?php echo _e('Answer', 'DIRECTORYPRESS'); ?>" name="faqanswer[<?php echo esc_attr($j); ?>]" rows="8"><?php echo esc_attr($faqanswer[$j]); ?></textarea>	
										</div>
									</div>
								</div>
							<?php }
							$j++;
						}

					}else { ?>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h4 class="panel-title">
									<a data-toggle="collapse" data-parent="#faq-wrapper" href="#collapse1" data-faq-heading="Faq"><?php echo esc_html__('Faq 1', 'DIRECTORYPRESS'); ?></a>
								</h4>
							</div>
							<div id="collapse1" class="panel-collapse collapse in">
								<div class="panel-body">
									<input type="text" class="form-control" name="faqtitle[1]" placeholder="<?php echo _e('FAQ', 'DIRECTORYPRESS'); ?>" value="<?php echo esc_attr($faqtitle[1]); ?>">									
									<textarea class="form-control" placeholder="<?php echo _e('Answer', 'DIRECTORYPRESS'); ?>" name="faqanswer[1]" rows="8"><?php echo esc_attr($faqanswer[1]); ?></textarea>	
								</div>
							</div>
						</div>
					<?php } ?>	
				<?php  }else{ ?>                                                                          
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#faq-wrapper" href="#collapse1" data-faq-heading="Faq"><?php echo esc_html__('Faq 1', 'DIRECTORYPRESS'); ?></a>
							</h4>
						</div>
						<div id="collapse1" class="panel-collapse collapse in">
							<div class="panel-body">
								<input type="text" class="form-control" name="faqtitle[1]" placeholder="<?php echo _e('FAQ', 'DIRECTORYPRESS'); ?>">									
								<textarea class="form-control" placeholder="<?php echo _e('Answer', 'DIRECTORYPRESS'); ?>" name="faqanswer[1]" rows="8"></textarea>	
							</div>
						</div>
					</div>
				<?php  } ?>
			</div>
			<a id="faqsbtn" class="submit-listing-button"><?php echo esc_html__('+ Add FAQs', 'DIRECTORYPRESS'); ?></a>
		</div>
	</div>	
  <?php do_action('directorypress_faqs_metabox_html', $listing); ?>
<?php endif; ?>
