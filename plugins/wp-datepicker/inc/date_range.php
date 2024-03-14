<?php
	if(array_key_exists('wpdp_range', $_POST)){
		
		if (! isset( $_POST['wpdp_daterange_field'] ) || ! wp_verify_nonce( $_POST['wpdp_daterange_field'], 'wpdp_daterange_action' )) {
			print __('Sorry, your nonce did not verify.', 'wp-datepicker'); exit;
		}else{
			
			$wpdp_range = sanitize_wpdp_data($_POST['wpdp_range']);
			//pree($wpdp_range);exit;
			
			update_option('wpdp_range', $wpdp_range);
			
	
		}		
	}
				   
	$wpdp_get_datepicker_list = wpdp_get_datepicker_list();
	if(!empty($wpdp_get_datepicker_list)){
		
		$all_selectors = array();

		foreach ($wpdp_get_datepicker_list as $index => $datepicker){

			$datepicker_value = maybe_unserialize($datepicker->option_value);
			
			if(array_key_exists('wp_datepicker', $datepicker_value)){
				//pree($datepicker_value);
				$selectors = $datepicker_value['wp_datepicker'];
				$selectors_arr = explode(',', $selectors);
				
				$selectors_arr = array_map('trim', $selectors_arr);
				
				$all_selectors = array_merge($all_selectors, $selectors_arr);
			}
			
			
		}
		
		if(!empty($all_selectors)){
			
			$wpdp_range = get_option('wpdp_range');
			$wpdp_range = is_array($wpdp_range)?$wpdp_range:array();
			
?>
<form method="post">

<?php wp_nonce_field( 'wpdp_daterange_action', 'wpdp_daterange_field' ); ?>
<table class="wpdp-daterange-functions">
<?php
			
			foreach($all_selectors as $selector_index => $main_selector){
				$selector_date_range = 'wpdp-date-range-'.$selector_index;
				$wpdp_range_this = array_key_exists($selector_date_range, $wpdp_range)?$wpdp_range[$selector_date_range]:array();
				$wpdp_range_target = array_key_exists('target', $wpdp_range_this)?$wpdp_range_this['target']:'';
				$wpdp_range_d = array_key_exists('day', $wpdp_range_this)?$wpdp_range_this['day']:'';
				$wpdp_range_m = array_key_exists('month', $wpdp_range_this)?$wpdp_range_this['month']:'';
				$wpdp_range_y = array_key_exists('year', $wpdp_range_this)?$wpdp_range_this['year']:'';
				$wpdp_range_status = array_key_exists('status', $wpdp_range_this)?$wpdp_range_this['status']:'';
?>
<tr class="wpdp-range-<?php echo ($wpdp_range_status?'enabled':'disabled'); ?> wpdp-range-wrapper">
<td><input type="text" value="<?php echo $main_selector; ?>" readonly /> <a class="linkage linked"><i class="fas fa-link"></i></a> 
<select name="wpdp_range[<?php echo $selector_date_range; ?>][target]">
<?php foreach($all_selectors as $selector_sub => $selector){ $selector_sub_range = 'wpdp-date-range-'.$selector_sub; ?>	
<?php if($selector_date_range!=$selector_sub_range){ ?>
	<option value="<?php echo $selector_sub_range; ?>" <?php selected($wpdp_range_target==$selector_sub_range); ?>><?php echo $selector; ?></option>
<?php } ?>    
<?php } ?>    
</select>

<div class="wpdp_range_specs">

<table cellpadding="0" cellspacing="0">
<tr>
<th class="wpdp-dd"><?php _e('Days', 'wp-datepicker'); ?></th>
<th></th>
<th class="wpdp-mm"><?php _e('Months', 'wp-datepicker'); ?></th>
<th></th>
<th class="wpdp-yy"><?php _e('Years', 'wp-datepicker'); ?></th>
</tr>
<tr>
<td class="wpdp-dd"><input type="number" name="wpdp_range[<?php echo $selector_date_range; ?>][day]" max="31" min="0" value="<?php echo $wpdp_range_d; ?>" /></td>
<td></td>
<td class="wpdp-mm"><input type="number" name="wpdp_range[<?php echo $selector_date_range; ?>][month]" max="12" min="0" value="<?php echo $wpdp_range_m; ?>" /></td>
<td></td>
<td class="wpdp-yy"><input type="number" name="wpdp_range[<?php echo $selector_date_range; ?>][year]" min="0" value="<?php echo $wpdp_range_y; ?>" /></td>
</tr>
</table>
</div>

<div class="form-check">
  <input name="wpdp_range[<?php echo $selector_date_range; ?>][status]" class="form-check-input" type="checkbox" value="1" <?php checked($wpdp_range_status); ?> />
</div>
</td>
</tr>	
<?php 
			}
?>			
</table>
<input type="submit" value="<?php _e('Save Changes', 'wp-datepicker'); ?>" class="button button-primary" />
<a class="wpdp-dr-yt" href="https://www.youtube.com/embed/60hbh837wDU" target="_blank" title="<?php _e('How it works?', 'wp-datepicker'); ?>"><i class="fab fa-youtube"></i></a>
<br /><br />
<a href="https://wordpress.org/support/plugin/wp-datepicker/" target="_blank"><?php _e('Do you need help or want to suggest something?', 'wp-datepicker'); ?></a>
</form>
<?php			
			
		}
		
	}