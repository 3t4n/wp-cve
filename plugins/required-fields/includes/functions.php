<?php

function rf_enabled_settings()
{
	$opt = get_option( 'rf_settings' );
	if (!isset($opt['rf_enabled_settings']))
	{
		$value = '';	
	} else {
		$value = 'checked';
	}
	?>
		<div class="rf_field">
			<span class="main_control"><?php _e('Require The Posts'); ?>
				<div class="slideThree floatright main_btns">
					<input type="checkbox" class="ch_location main_input" value="" id="rf_enabled_settings" style="display: none;" name="rf_settings[rf_enabled_settings]" <?php echo $value; ?> />
					<label for="rf_enabled_settings"></label>
				</div>
			</span>
		</div>
<?php
}

function rf_for_page_enabled()
{
	/* ENABLED FOR PAGE */
	$opt = get_option( 'rf_settings' );
	if (!isset($opt['rf_for_page_enabled']))
	{
		$value = '';
		
	} else {
		$value = 'checked';
	}	
?>
		<div class="rf_field">
			<span class="main_control"><?php _e('Require The Pages'); ?>
				<div class="slideThree floatright main_btns">
					<input type="checkbox" class="ch_location main_input" value="" id="rf_for_page_enabled" style="display: none;" name="rf_settings[rf_for_page_enabled]" <?php echo $value; ?> />
					<label for="rf_for_page_enabled"></label>
				</div>
			</span>
		</div>
<?php
	/* END ENABLED FOR PAGE */
}

function rf_for_page_text()
{
	$opt = get_option( 'rf_settings' );
	
	/* FOR PAGE TITLE */
	if (!isset($opt['rf_title_for_page']))
	{
		$rf_title_for_page = '';
	} else {
		$rf_title_for_page = 'checked';
	}
?>		
		<div class="rf_field">
			<span class="main_control"><?php _e('Require The Title'); ?>
				<div class="slideThree floatright main_btns">
					<input type="checkbox" class="ch_location main_input" value="" id="rf_title_for_page" style="display: none;" name="rf_settings[rf_title_for_page]" <?php echo $rf_title_for_page; ?> />
					<label for="rf_title_for_page"></label>
				</div>
			</span>
		</div>
<?php
		
	/* END FOR PAGE TITLE */
	
	$opt = get_option( 'rf_settings' );
	/* FOR PAGE IMAGE */
	if (!isset($opt['rf_image_for_page']))
	{
		$rf_image_for_page = '';
	} else {
		$rf_image_for_page = 'checked';
	}
?>
		<div class="rf_field">
			<span class="main_control"><?php _e('Require The Image'); ?>
				<div class="slideThree floatright main_btns">
					<input type="checkbox" class="ch_location main_input" value="" id="rf_image_for_page" style="display: none;" name="rf_settings[rf_image_for_page]" <?php echo $rf_image_for_page; ?> />
					<label for="rf_image_for_page"></label>
				</div>
			</span>
		</div>
<?php
	/* END FOR PAGE IMAGE */
}

function rf_main_section_text(){
	rf_title_settings();
	rf_excerpt_settings();
	rf_category_settings();
	rf_tag_settings();
	rf_image_settings();
}

function rf_error_logs_text()
{
	$opt = get_option( 'rf_settings' );
	
	/* Title Error Alerts */
	$rf_title_error = $opt['rf_title_error'];

?>
		<div class="rf_error_field">
			<input type="text"  value="<?php echo $rf_title_error; ?>" id="rf_title_error" name="rf_settings[rf_title_error]" />
			<label for="rf_title_error"><?php _e('Set Error Alert For Title'); ?></label>
		</div>
<?php
			  
	/* END Title Error Alerts */
	
	/* Excerpt Error Alerts */
	$rf_excerpt_error = $opt['rf_excerpt_error'];
?>	
		<div class="rf_error_field">
			<input type="text"  value="<?php echo $rf_excerpt_error; ?>" id="rf_excerpt_error" name="rf_settings[rf_excerpt_error]" />
			<label for="rf_excerpt_error"><?php _e('Set Error Alert For Excerpt'); ?></label>
		 </div>
<?php
	/* END Excerpt Error Alerts */
	
	/* Category Error Alerts */
	$rf_cat_error = $opt['rf_cat_error'];
?>
		<div class="rf_error_field">
			<input type="text"  value="<?php echo $rf_cat_error; ?>" id="rf_cat_error" name="rf_settings[rf_cat_error]" />
			<label for="rf_cat_error"><?php _e('Set Error Alert For Category'); ?></label>
		</div>
<?php
	/* END Category Error Alerts */
	
	/* Tag Error Alerts */
	$rf_tag_error = $opt['rf_tag_error'];
?>
		<div class="rf_error_field">
			<input type="text"  value="<?php echo $rf_tag_error; ?>" id="rf_tag_error" name="rf_settings[rf_tag_error]" />
			<label for="rf_tag_error"><?php _e('Set Error Alert For Tags'); ?></label>
		</div>
<?php
	/* END Tag Error Alerts */
	
	/* Post Image Error Alerts */
	$rf_img_error = $opt['rf_img_error'];
?>
		<div class="rf_error_field">
			<input type="text"  value="<?php echo $rf_img_error; ?>" id="rf_img_error" name="rf_settings[rf_img_error]" />
			<label for="rf_img_error"><?php _e('Set Error Alert For Featured Image'); ?></label>
		</div>
<?php
	/* END Post Image Error Alerts */
}

function rf_save_draft_text()
{
	$opt = get_option('rf_settings');
	if (!isset($opt['rf_save_draft']))
	{
		$value = '';
	} else {
		$value = 'checked';
	}
?>
		<div class="rf_field">
			<span class="main_control"><?php _e('Require The Drafts For Pages & Posts'); ?></span>
				<div class="slideThree floatright main_btns">
					<input type="checkbox" class="ch_location main_input" value="" id="rf_save_draft" style="display: none;" name="rf_settings[rf_save_draft]" <?php echo $value; ?> />
					<label for="rf_save_draft"></label>
				</div>
			
		</div>
<?php
}

function rf_title_settings()
{
	$opt = get_option('rf_settings');
	if (!isset($opt['rf_title_settings']))
	{
		$value = '';
	} else {
		$value = 'checked';
	}
?>
		<div class="rf_field">
			<span class="main_control"><?php _e('Require The Title'); ?></span>
			<div class="slideThree floatright main_btns">
				<input type="checkbox" class="ch_location main_input" value="" id="rf_title_settings" style="display: none;" name="rf_settings[rf_title_settings]" <?php echo $value; ?> />
				<label for="rf_title_settings"></label>
			</div>
			
		</div>
<?php
}

function rf_excerpt_settings()
{
	$opt = get_option('rf_settings');
	if (!isset($opt['rf_excerpt_settings']))
	{
		$value = '';
	} else {
		$value = 'checked';
	}
?>	
		<div class="rf_field">
			<span class="main_control"><?php _e('Require The Excerpt'); ?></span>
			<div class="slideThree floatright main_btns">
				<input type="checkbox" class="ch_location main_input" value="" id="rf_excerpt_settings" style="display: none;" name="rf_settings[rf_excerpt_settings]" <?php echo $value; ?> />
				<label for="rf_excerpt_settings"></label>
			</div>
		</div>
<?php
}

function rf_category_settings()
{
	$opt = get_option('rf_settings');
	if (!isset($opt['rf_category_settings']))
	{
		$value = '';
	} else {
		$value = 'checked';
	}
?>
		<div class="rf_field">
			<span class="main_control"><?php _e('Require The Category'); ?></span>
			<div class="slideThree floatright main_btns">
				<input type="checkbox" class="ch_location main_input" value="" id="rf_category_settings" style="display: none;" name="rf_settings[rf_category_settings]" <?php echo $value; ?> />
				<label for="rf_category_settings"></label>
			</div>
		</div>
<?php
}

function rf_tag_settings()
{
	$opt = get_option('rf_settings');
	if (!isset($opt['rf_tag_settings']))
	{
		$value = '';
	} else {
		$value = 'checked';
	}
?>
		<div class="rf_field">
				<span class="main_control"><?php _e('Require The Tag'); ?></span>
				<div class="slideThree floatright main_btns">
					<input type="checkbox" class="ch_location main_input" value="" id="rf_tag_settings" style="display: none;" name="rf_settings[rf_tag_settings]" <?php echo $value; ?> />
					<label for="rf_tag_settings"></label>
				</div>
			</div>
<?php
}

function rf_image_settings()
{
	$opt = get_option('rf_settings');
	if (!isset($opt['rf_image_settings']))
	{
		$value = '';
	} else {
		$value = 'checked';
	}
?>		
		<div class="rf_field">
				<span class="main_control"><?php _e('Require The Featured Image'); ?></span>
				<div class="slideThree floatright main_btns">
					<input type="checkbox" class="ch_location main_input" value="" id="rf_image_settings" style="display: none;" name="rf_settings[rf_image_settings]" <?php echo $value; ?> />
					<label for="rf_image_settings"></label>
				</div>
			</div>
<?php
}