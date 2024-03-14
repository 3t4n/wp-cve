<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br>
    </div>
    <h2><?php _e('Onclick Popup', 'onclick-popup'); ?></h2>
    <?php

	$onclickpopup_title = get_option('onclickpopup_title');
	$onclickpopup_setting1 = get_option('onclickpopup_setting1');
	$onclickpopup_setting1_left = get_option('onclickpopup_setting1_left');
	$onclickpopup_setting1_top = get_option('onclickpopup_setting1_top');
	
	$onclickpopup_setting2 = get_option('onclickpopup_setting2');
	$onclickpopup_setting2_left = get_option('onclickpopup_setting2_left');
	$onclickpopup_setting2_top = get_option('onclickpopup_setting2_top');
	
	$onclickpopup_setting3 = get_option('onclickpopup_setting3');
	$onclickpopup_setting3_left = get_option('onclickpopup_setting3_left');
	$onclickpopup_setting3_top = get_option('onclickpopup_setting3_top');
	
	$onclickpopup_setting4 = get_option('onclickpopup_setting4');
	$onclickpopup_setting4_left = get_option('onclickpopup_setting4_left');
	$onclickpopup_setting4_top = get_option('onclickpopup_setting4_top');
	
	$onclickpopup_setting5 = get_option('onclickpopup_setting5');
	$onclickpopup_setting5_left = get_option('onclickpopup_setting5_left');
	$onclickpopup_setting5_top = get_option('onclickpopup_setting5_top');
	
	if (isset($_POST['onclickpopup_form_submit']) && $_POST['onclickpopup_form_submit'] == 'yes')
	{
		//	Just security thingy that wordpress offers us
		check_admin_referer('onclickpopup_form_setting');
			
		$onclickpopup_title = stripslashes(sanitize_text_field($_POST['onclickpopup_title']));
		$onclickpopup_setting1 = stripslashes(sanitize_text_field($_POST['onclickpopup_setting1']));
		$onclickpopup_setting1_left = stripslashes(sanitize_text_field($_POST['onclickpopup_setting1_left']));
		$onclickpopup_setting1_top = stripslashes(sanitize_text_field($_POST['onclickpopup_setting1_top']));
		
		$onclickpopup_setting2 = stripslashes(sanitize_text_field($_POST['onclickpopup_setting2']));
		$onclickpopup_setting2_left = stripslashes(sanitize_text_field($_POST['onclickpopup_setting2_left']));
		$onclickpopup_setting2_top = stripslashes(sanitize_text_field($_POST['onclickpopup_setting2_top']));
		
		$onclickpopup_setting3 = stripslashes(sanitize_text_field($_POST['onclickpopup_setting3']));
		$onclickpopup_setting3_left = stripslashes(sanitize_text_field($_POST['onclickpopup_setting3_left']));
		$onclickpopup_setting3_top = stripslashes(sanitize_text_field($_POST['onclickpopup_setting3_top']));
		
		$onclickpopup_setting4 = stripslashes(sanitize_text_field($_POST['onclickpopup_setting4']));
		$onclickpopup_setting4_left = stripslashes(sanitize_text_field($_POST['onclickpopup_setting4_left']));
		$onclickpopup_setting4_top = stripslashes(sanitize_text_field($_POST['onclickpopup_setting4_top']));
		
		$onclickpopup_setting5 = stripslashes(sanitize_text_field($_POST['onclickpopup_setting5']));
		$onclickpopup_setting5_left = stripslashes(sanitize_text_field($_POST['onclickpopup_setting5_left']));
		$onclickpopup_setting5_top = stripslashes(sanitize_text_field($_POST['onclickpopup_setting5_top']));

		if(!is_numeric($onclickpopup_setting1_left )) { $onclickpopup_setting1_left  = 400; }
		if(!is_numeric($onclickpopup_setting1_top)) { $onclickpopup_setting1_top = 50; }
		
		if(!is_numeric($onclickpopup_setting2_left)) { $onclickpopup_setting2_left = 400; }
		if(!is_numeric($onclickpopup_setting2_top)) { $onclickpopup_setting2_top = 50; }
		
		if(!is_numeric($onclickpopup_setting3_left)) { $onclickpopup_setting3_left = 400; }
		if(!is_numeric($onclickpopup_setting3_top)) { $onclickpopup_setting3_top = 50; }
		
		if(!is_numeric($onclickpopup_setting4_left)) { $onclickpopup_setting4_left = 400; }
		if(!is_numeric($onclickpopup_setting4_top)) { $onclickpopup_setting4_top = 50; }
		
		if(!is_numeric($onclickpopup_setting5_left)) { $onclickpopup_setting5_left = 400; }
		if(!is_numeric($onclickpopup_setting5_top)) { $onclickpopup_setting5_top = 50; }

		update_option('onclickpopup_title', $onclickpopup_title );
		update_option('onclickpopup_setting1', $onclickpopup_setting1 );
		update_option('onclickpopup_setting1_left', $onclickpopup_setting1_left );
		update_option('onclickpopup_setting1_top', $onclickpopup_setting1_top );
		
		update_option('onclickpopup_setting2', $onclickpopup_setting2 );
		update_option('onclickpopup_setting2_left', $onclickpopup_setting2_left );
		update_option('onclickpopup_setting2_top', $onclickpopup_setting2_top );
		
		update_option('onclickpopup_setting3', $onclickpopup_setting3 );
		update_option('onclickpopup_setting3_left', $onclickpopup_setting3_left );
		update_option('onclickpopup_setting3_top', $onclickpopup_setting3_top );
		
		update_option('onclickpopup_setting4', $onclickpopup_setting4 );
		update_option('onclickpopup_setting4_left', $onclickpopup_setting4_left );
		update_option('onclickpopup_setting4_top', $onclickpopup_setting4_top );
		
		update_option('onclickpopup_setting5', $onclickpopup_setting5 );
		update_option('onclickpopup_setting5_left', $onclickpopup_setting5_left );
		update_option('onclickpopup_setting5_top', $onclickpopup_setting5_top );
		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'onclick-popup'); ?></strong></p>
		</div>
		<?php
	}
	?>
	<form name="onclickpopup_form" method="post" action="">
		<h3><?php _e('Widget setting', 'onclick-popup'); ?></h3>
		
		<label for="tag-title"><?php _e('Widget title', 'onclick-popup'); ?></label>
		<input name="onclickpopup_title" type="text" id="onclickpopup_title" size="50" value="<?php echo $onclickpopup_title; ?>" />
		<p><?php _e('Please enter widget title.', 'onclick-popup'); ?></p>
		
		<div style="height:10px;"></div>
		<h3><?php _e('Setting 1', 'onclick-popup'); ?></h3>
		<label for="tag-title"><?php _e('CSS setting 1', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting1" type="text" id="onclickpopup_setting1" size="110" value="<?php echo $onclickpopup_setting1; ?>" />
		<p>Example: width: 320px; background: #FFF; text-align: center; font-family: Arial,sans-serif; padding: 10px; border: 2px solid #666;</p>
		<label for="tag-title"><?php _e('Left position', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting1_left" type="text" id="onclickpopup_setting1_left" size="10" maxlength="4" value="<?php echo $onclickpopup_setting1_left; ?>" />
		<p><?php _e('Please enter your popup window LEFT position for setting 1', 'onclick-popup'); ?></p>
		<label for="tag-title"><?php _e('Top position', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting1_top" type="text" id="onclickpopup_setting1_top" size="10" maxlength="4" value="<?php echo $onclickpopup_setting1_top; ?>" />
		<p><?php _e('Please enter your popup window TOP position for setting 1', 'onclick-popup'); ?></p>
		<div style="height:10px;"></div>
		
		<div style="height:10px;"></div>
		<h3><?php _e('Setting 2', 'onclick-popup'); ?></h3>
		<label for="tag-title"><?php _e('CSS setting 2', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting2" type="text" id="onclickpopup_setting2" size="110" value="<?php echo $onclickpopup_setting2; ?>" />
		<p>Example: width: 320px; background: #FFF; text-align: center; font-family: Arial,sans-serif; padding: 10px; border: 2px solid #666;</p>
		<label for="tag-title"><?php _e('Left position', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting2_left" type="text" id="onclickpopup_setting2_left" size="10" maxlength="4" value="<?php echo $onclickpopup_setting2_left; ?>" />
		<p><?php _e('Please enter your popup window LEFT position for setting 2', 'onclick-popup'); ?></p>
		<label for="tag-title"><?php _e('Top position', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting2_top" type="text" id="onclickpopup_setting2_top" size="10" maxlength="4" value="<?php echo $onclickpopup_setting2_top; ?>" />
		<p><?php _e('Please enter your popup window TOP position for setting 2', 'onclick-popup'); ?></p>
		<div style="height:10px;"></div>
		
		<div style="height:10px;"></div>
		<h3><?php _e('Setting 3', 'onclick-popup'); ?></h3>
		<label for="tag-title"><?php _e('CSS setting 3', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting3" type="text" id="onclickpopup_setting3" size="110" value="<?php echo $onclickpopup_setting3; ?>" />
		<p>Example: width: 320px; background: #FFF; text-align: center; font-family: Arial,sans-serif; padding: 10px; border: 2px solid #666;</p>
		<label for="tag-title"><?php _e('Left position', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting3_left" type="text" id="onclickpopup_setting3_left" size="10" maxlength="4" value="<?php echo $onclickpopup_setting3_left; ?>" />
		<p><?php _e('Please enter your popup window LEFT position for setting 3', 'onclick-popup'); ?></p>
		<label for="tag-title"><?php _e('Top position', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting3_top" type="text" id="onclickpopup_setting3_top" size="10" maxlength="4" value="<?php echo $onclickpopup_setting3_top; ?>" />
		<p><?php _e('Please enter your popup window TOP position for setting 3', 'onclick-popup'); ?></p>
		<div style="height:10px;"></div>
		
		<div style="height:10px;"></div>
		<h3><?php _e('Setting 4', 'onclick-popup'); ?></h3>
		<label for="tag-title"><?php _e('CSS setting 4', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting4" type="text" id="onclickpopup_setting4" size="110" value="<?php echo $onclickpopup_setting4; ?>" />
		<p>Example: width: 320px; background: #FFF; text-align: center; font-family: Arial,sans-serif; padding: 10px; border: 2px solid #666;</p>
		<label for="tag-title"><?php _e('Left position', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting4_left" type="text" id="onclickpopup_setting4_left" size="10" maxlength="4" value="<?php echo $onclickpopup_setting4_left; ?>" />
		<p><?php _e('Please enter your popup window LEFT position for setting 4', 'onclick-popup'); ?></p>
		<label for="tag-title"><?php _e('Top position', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting4_top" type="text" id="onclickpopup_setting4_top" size="10" maxlength="4" value="<?php echo $onclickpopup_setting4_top; ?>" />
		<p><?php _e('Please enter your popup window TOP position for setting 4', 'onclick-popup'); ?></p>
		<div style="height:10px;"></div>
		
		<div style="height:10px;"></div>
		<h3><?php _e('Setting 5', 'onclick-popup'); ?></h3>
		<label for="tag-title"><?php _e('CSS setting 5', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting5" type="text" id="onclickpopup_setting5" size="110" value="<?php echo $onclickpopup_setting5; ?>" />
		<p>Example: width: 320px; background: #FFF; text-align: center; font-family: Arial,sans-serif; padding: 10px; border: 2px solid #666;</p>
		<label for="tag-title"><?php _e('Left position', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting5_left" type="text" id="onclickpopup_setting5_left" size="10" maxlength="4" value="<?php echo $onclickpopup_setting5_left; ?>" />
		<p><?php _e('Please enter your popup window LEFT position for setting 5', 'onclick-popup'); ?></p>
		<label for="tag-title"><?php _e('Top position', 'onclick-popup'); ?></label>
		<input name="onclickpopup_setting5_top" type="text" id="onclickpopup_setting5_top" size="10" maxlength="4" value="<?php echo $onclickpopup_setting5_top; ?>" />
		<p><?php _e('Please enter your popup window TOP position for setting 5', 'onclick-popup'); ?></p>
		<div style="height:10px;"></div>
		
		<div style="height:10px;"></div>
		<input type="hidden" name="onclickpopup_form_submit" value="yes"/>
		<input name="onclickpopup_submit" id="onclickpopup_submit" class="button add-new-h2" value="<?php _e('Submit', 'onclick-popup'); ?>" type="submit" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="onclickpopup_help()" value="<?php _e('Help', 'onclick-popup'); ?>" type="button" />
		<?php wp_nonce_field('onclickpopup_form_setting'); ?>
	</form>
  </div>
</div>