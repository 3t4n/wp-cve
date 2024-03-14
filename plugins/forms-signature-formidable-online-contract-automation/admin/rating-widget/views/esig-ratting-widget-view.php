<input type="hidden" id="formidable-feedback-url" value="<?php echo $data['feedback_url']?>">
<input type="hidden" id="rating-formidable-url" value="<?php echo $data['plugin_url']?>">
<input type="hidden" id="formidable-plugin-name" value="<?php echo $data['form_name']?>">


<div id="formidable-esign-ratting" class="postbox esign_ratting_section">
	<div class="postbox-header"><h2 class="hndle esign_ratting_widget_head"><b>WP E-Signature & <?php echo esc_attr($data['form_name']) ?> Integration</b></h2>
	</div>
	<div class="inside">
		<div class="esign_formidable_ratting_widget_info">
		
		Hey, I noticed you've gotten quite a few documents signed with WP E-Signature & <?php echo esc_attr($data['form_name'])?>s! Are you enjoying WP E-Signature?

		</div>

		<div class="row esign_ratting_widget_button">

		<div class="col-sm-1 esign_formidable_ratting_widget_yes"><input type="submit" id="esig-formidable-action-ratting-widget" class="button action esign_formidable_ratting_widget_yes_button" value="Yes"></div>
    	<div class="col-sm-4 esign_formidable_ratting_widget_no"><a class="esign_formidable_ratting_widget_no_button" href="#">Not Really</a></div>
		<div class="col-sm-7 esign_monster_icon"><img src="<?php echo esc_url(plugins_url("esign-monster.png",__FILE__)); ?>" ></div>


		
	</div>
		

	</div>
</div>
