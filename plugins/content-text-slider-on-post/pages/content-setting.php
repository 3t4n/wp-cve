<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"><br>
    </div>
    <h2><?php _e('Content text slider on post', 'content-text-slider-on-post'); ?></h2>
    <?php
	$ctsop_height_display_length_s1 = get_option('ctsop_height_display_length_s1');
	$ctsop_height_display_length_s2 = get_option('ctsop_height_display_length_s2');
	$ctsop_height_display_length_s3 = get_option('ctsop_height_display_length_s3');
	
	$ctsop_speed = get_option('ctsop_speed');
	$ctsop_waitseconds = get_option('ctsop_waitseconds');
	
	$ctsop_height_display_length_s1_new = explode("_", $ctsop_height_display_length_s1);
	$ctsop_height_1 = @$ctsop_height_display_length_s1_new[0];
	$ctsop_display_1 = @$ctsop_height_display_length_s1_new[1];
	$ctsop_length_1 = @$ctsop_height_display_length_s1_new[2];
	
	$ctsop_height_display_length_s2 = explode("_", $ctsop_height_display_length_s2);
	$ctsop_height_2 = @$ctsop_height_display_length_s2[0];
	$ctsop_display_2 = @$ctsop_height_display_length_s2[1];
	$ctsop_length_2 = @$ctsop_height_display_length_s2[2];
	
	$ctsop_height_display_length_s3 = explode("_", $ctsop_height_display_length_s3);
	$ctsop_height_3 = @$ctsop_height_display_length_s3[0];
	$ctsop_display_3 = @$ctsop_height_display_length_s3[1];
	$ctsop_length_3 = @$ctsop_height_display_length_s3[2];
	
	if (isset($_POST['ctsop_form_submit']) && $_POST['ctsop_form_submit'] == 'yes')
	{
		$ctsop_height_1 = stripslashes(sanitize_text_field($_POST['ctsop_height_1']));
		$ctsop_display_1 = stripslashes(sanitize_text_field($_POST['ctsop_display_1']));
		$ctsop_length_1 = stripslashes(sanitize_text_field($_POST['ctsop_length_1']));
		
		if(!is_numeric($ctsop_height_1)) { $ctsop_height_1 = 200; }
		if(!is_numeric($ctsop_display_1)) { $ctsop_display_1 = 2; }
		if(!is_numeric($ctsop_length_1)) { $ctsop_length_1 = 500; }
		
		$ctsop_height_2 = stripslashes(sanitize_text_field($_POST['ctsop_height_2']));
		$ctsop_display_2 = stripslashes(sanitize_text_field($_POST['ctsop_display_2']));
		$ctsop_length_2 = stripslashes(sanitize_text_field($_POST['ctsop_length_2']));
		
		if(!is_numeric($ctsop_height_2)) { $ctsop_height_2 = 190; }
		if(!is_numeric($ctsop_display_2)) { $ctsop_display_2 = 1; }
		if(!is_numeric($ctsop_length_2)) { $ctsop_length_2 = 500; }
		
		$ctsop_height_3 = stripslashes(sanitize_text_field($_POST['ctsop_height_3']));
		$ctsop_display_3 = stripslashes(sanitize_text_field($_POST['ctsop_display_3']));
		$ctsop_length_3 = stripslashes(sanitize_text_field($_POST['ctsop_length_3']));
		
		if(!is_numeric($ctsop_height_3)) { $ctsop_height_3 = 190; }
		if(!is_numeric($ctsop_display_3)) { $ctsop_display_3 = 3; }
		if(!is_numeric($ctsop_length_3)) { $ctsop_length_3 = 500; }
		
		$ctsop_speed = stripslashes(sanitize_text_field($_POST['ctsop_speed']));
		$ctsop_waitseconds = stripslashes(sanitize_text_field($_POST['ctsop_waitseconds']));
		
		if(!is_numeric($ctsop_speed)) { $ctsop_speed = 2; }
		if(!is_numeric($ctsop_waitseconds)) { $ctsop_waitseconds = 2; }
		
		$ctsop_height_display_length_s1 = $ctsop_height_1 . "_" . $ctsop_display_1. "_" . $ctsop_length_1;
		$ctsop_height_display_length_s2 = $ctsop_height_2 . "_" . $ctsop_display_2. "_" . $ctsop_length_2;
		$ctsop_height_display_length_s3 = $ctsop_height_3 . "_" . $ctsop_display_3. "_" . $ctsop_length_3;
		
		update_option('ctsop_height_display_length_s1', $ctsop_height_display_length_s1 );
		update_option('ctsop_height_display_length_s2', $ctsop_height_display_length_s2 );
		update_option('ctsop_height_display_length_s3', $ctsop_height_display_length_s3 );
		
		update_option('ctsop_speed', $ctsop_speed );
		update_option('ctsop_waitseconds', $ctsop_waitseconds );
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'content-text-slider-on-post'); ?></strong></p>
		</div>
		<?php
	}
	?>
	<form name="ctsop_form" method="post" action="">
		
		<h3><?php _e('Setting 1', 'content-text-slider-on-post'); ?></h3>
		
		<label for="tag-title"><?php _e('Record height in scroll', 'content-text-slider-on-post'); ?></label>
		<input name="ctsop_height_1" type="text" id="ctsop_height_1" value="<?php echo $ctsop_height_1; ?>" maxlength="4" />
		<p><?php _e('This is the height of the each record in the scroll.', 'content-text-slider-on-post'); ?></p>
		
		<label for="tag-title"><?php _e('Display records', 'content-text-slider-on-post'); ?></label>
		<input name="ctsop_display_1" type="text" id="ctsop_display_1" value="<?php echo $ctsop_display_1; ?>" maxlength="4" />
		<p><?php _e('No of records you want to show in the screen at same time.', 'content-text-slider-on-post'); ?></p>
		
		<label for="tag-title"><?php _e('Text Length', 'content-text-slider-on-post'); ?></label>
		<input name="ctsop_length_1" type="text" id="ctsop_length_1" value="<?php echo $ctsop_length_1; ?>" maxlength="4" />
		<p><?php _e('This is to maintain the record description length in the scroll.', 'content-text-slider-on-post'); ?></p>
		
		<h3><?php _e('Setting 2', 'content-text-slider-on-post'); ?></h3>
		
		<label for="tag-title"><?php _e('Record height in scroll', 'content-text-slider-on-post'); ?></label>
		<input name="ctsop_height_2" type="text" id="ctsop_height_2" value="<?php echo $ctsop_height_2; ?>" maxlength="4" />
		<p><?php _e('This is the height of the each record in the scroll.', 'content-text-slider-on-post'); ?></p>
		
		<label for="tag-title"><?php _e('Display records', 'content-text-slider-on-post'); ?></label>
		<input name="ctsop_display_2" type="text" id="ctsop_display_2" value="<?php echo $ctsop_display_2; ?>" maxlength="4" />
		<p><?php _e('No of records you want to show in the screen at same time.', 'content-text-slider-on-post'); ?></p>
		
		<label for="tag-title"><?php _e('Text Length', 'content-text-slider-on-post'); ?></label>
		<input name="ctsop_length_2" type="text" id="ctsop_length_2" value="<?php echo $ctsop_length_2; ?>" maxlength="4" />
		<p><?php _e('This is to maintain the record description length in the scroll.', 'content-text-slider-on-post'); ?></p>
		
		<h3><?php _e('Setting 3', 'content-text-slider-on-post'); ?></h3>
		
		<label for="tag-title"><?php _e('Record height in scroll', 'content-text-slider-on-post'); ?></label>
		<input name="ctsop_height_3" type="text" id="ctsop_height_3" value="<?php echo $ctsop_height_3; ?>" maxlength="4" />
		<p><?php _e('This is the height of the each record in the scroll.', 'content-text-slider-on-post'); ?></p>
		
		<label for="tag-title"><?php _e('Display records', 'content-text-slider-on-post'); ?></label>
		<input name="ctsop_display_3" type="text" id="ctsop_display_3" value="<?php echo $ctsop_display_3; ?>" maxlength="4" />
		<p><?php _e('No of records you want to show in the screen at same time.', 'content-text-slider-on-post'); ?></p>
		
		<label for="tag-title"><?php _e('Text Length', 'content-text-slider-on-post'); ?></label>
		<input name="ctsop_length_3" type="text" id="ctsop_length_3" value="<?php echo $ctsop_length_3; ?>" maxlength="4" />
		<p><?php _e('This is to maintain the record description length in the scroll.', 'content-text-slider-on-post'); ?></p>
		
		
		<h3><?php _e('Scroll Setting', 'content-text-slider-on-post'); ?></h3>
		
		<label for="ctsop_speed"><?php _e( 'Scrolling speed', 'content-text-slider-on-post' ); ?></label>
		<?php _e( 'Slow', 'content-text-slider-on-post' ); ?> 
		<input name="ctsop_speed" type="range" value="<?php echo $ctsop_speed; ?>"  id="ctsop_speed" min="1" max="10" /> 
		<?php _e( 'Fast', 'content-text-slider-on-post' ); ?> 
		<p><?php _e( 'Set how fast you want to scroll.', 'content-text-slider-on-post' ); ?></p>
		
		<label for="cas_waitseconds"><?php _e( 'Seconds to wait', 'content-text-slider-on-post' ); ?></label>
		<input name="ctsop_waitseconds" type="text" value="<?php echo $ctsop_waitseconds; ?>" id="ctsop_waitseconds" maxlength="4" />
		<p><?php _e( 'How many seconds you want the wait to scroll', 'content-text-slider-on-post' ); ?> (<?php _e( 'Example', 'content-text-slider-on-post' ); ?>: 5)</p>
		
		<div style="height:5px;"></div>
		<input type="hidden" name="ctsop_form_submit" value="yes"/>
		<input name="ctsop_submit" id="ctsop_submit" class="button add-new-h2" value="<?php _e('Submit', 'content-text-slider-on-post'); ?>" type="submit" />&nbsp;
		<input name="publish" lang="publish" class="button add-new-h2" onclick="ctsop_redirect()" value="<?php _e('Cancel', 'content-text-slider-on-post'); ?>" type="button" />&nbsp;
		<input name="Help" lang="publish" class="button add-new-h2" onclick="ctsop_help()" value="<?php _e('Help', 'content-text-slider-on-post'); ?>" type="button" />
		<div style="height:5px;"></div>
		<?php wp_nonce_field('ctsop_form_setting'); ?>
    </form>
	 </div>
</div>