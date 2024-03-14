<?php
if (!defined('ABSPATH')) {
    exit;
}
?>
<style type="text/css">
.wt_productfeed_schedule_now{ width:600px; text-align:left; }
.wt_productfeed_schedule_now_box{ width:100%; padding:15px; box-sizing:border-box; }
.wt_productfeed_schedule_now_formrow{float:left; width:100%; margin-bottom:15px; padding-left:5px; box-sizing:border-box;}	
.wt_productfeed_schedule_now_interval_radio_block{float:left; width:100%; margin:0px; padding:0px; margin-top:2px; }		
.wt_productfeed_schedule_now_box label{ width:100%; float:left; text-align:left; font-weight:bold; }
.wt_productfeed_schedule_now_interval_radio_block label{width:auto; float:left; margin-right:10px; margin-bottom:5px; text-align:left; font-weight:normal; }	
.wt_productfeed_schedule_now_box select, .wt_productfeed_schedule_now_box input[type="text"]{ width:auto; text-align:left; }	
.wt_productfeed_schedule_now .wt_productfeed_popup_footer{ margin-top:10px; float:left; margin-bottom:20px; }
.wt_productfeed_schedule_type_desc{ margin-top:0px; padding-left:5px; margin-bottom:0px; }
.wt_productfeed_schedule_type_box_single{ float:left; margin-top:5px; margin-bottom:10px;}
.wt_productfeed_schedule_type_box_single label{ color:#666; }
.wt_productfeed_schedule_now_trigger_url, .wt_productfeed_schedule_day_block, .wt_productfeed_schedule_custom_interval_block, .wt_productfeed_schedule_starttime_block{ display:none; }
.wt_productfeed_schedule_now_interval_sub_block{ float:left; width:100%; margin-top:3px; }
.wt_productfeed_cron_current_time{float:right; width:auto;}
.wt_productfeed_cron_current_time span{ display:inline-block; width:85px; }
</style>
<div class="wt_productfeed_schedule_now wt_productfeed_popup">
	<div class="wt_productfeed_popup_hd">
		<span style="line-height:40px;" class="dashicons dashicons-clock"></span>
		<span class="wt_productfeed_popup_hd_label"><?php _e('Schedule now');?></span>
		<div class="wt_productfeed_popup_close">X</div>
	</div>
	<div class="wt_productfeed_schedule_now_box">
		<div class="wt_productfeed_cron_current_time"><b><?php _e('Current server time:');?></b> <span>--:--:-- --</span><br/>
		
			<?php 
			$wt_time_zone = Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings('default_time_zone'); 
			if(!$wt_time_zone){
			?>
			<data><?php	esc_html_e( 'To switch to your website timezone', 'webtoffee-product-feed'); ?> <a href="<?php echo admin_url('admin.php?page=wt_import_export_for_woo');?>" target="_blank" style="text-decoration:none;"> <?php esc_html_e( 'click here', 'webtoffee-product-feed' ); ?> <em class="dashicons dashicons-external"></em></a></data>
			<?php } ?>
			
		</div>

		<label><?php _e('Schedule type');?></label>
		<div class="wt_productfeed_schedule_now_formrow">
			<div class="wt_productfeed_schedule_type_box_single" style="margin-bottom:0px;">
				<label for="wt_productfeed_schedule_wordpress_cron"><input type="radio" name="wt_productfeed_schedule_type" id="wt_productfeed_schedule_wordpress_cron" value="wordpress_cron" checked="checked"> <?php _e('Wordpress Cron');?> </label>
				<p class="wt_productfeed_schedule_type_desc"><?php _e('This type of scheduler depends on the Wordpress for scheduling your job at the specified time. However this model is dependent on your website visitors. Upon a visit Wordpress cron will check to see if the time/date is later than the scheduled event/s, and if it is– it will fire those events.', 'webtoffee-product-feed');?></p>
			</div>
			<div class="wt_productfeed_schedule_type_box_single">
				<label for="wt_productfeed_schedule_server_cron"><input type="radio" name="wt_productfeed_schedule_type" id="wt_productfeed_schedule_server_cron" value="server_cron"> <?php _e('Server Cron', 'webtoffee-product-feed');?> </label>
				<p class="wt_productfeed_schedule_type_desc">
					<?php _e('You can use this option if you have a separate system to trigger the scheduled events. This method will generate a unique URL which can be added to your system inorder to trigger the events. You may need to trigger the URL every minute depending on the volume of data to be processed.', 'webtoffee-product-feed');?>					
				</p>
			</div>
		</div>

		<?php
		if( 'export' == $this->to_cron )
		{
		?>
		<label><?php _e('File name', 'webtoffee-product-feed');?></label>
		<div class="wt_productfeed_schedule_now_formrow">
			<input type="text" name="wt_productfeed_cron_file_name" value="" /> <span class="wt_productfeed_cron_file_ext">.csv</span>
			<br />	
			<?php _e('Specify a filename for the exported file(the contents of this file will be overwritten for every export). If left blank the system generates a default name(a new filename is generated for every export).', 'webtoffee-product-feed'); ?>	
		</div>
		<?php
		}
		?>

		<label><?php _e('Interval');?></label>
		<div class="wt_productfeed_schedule_now_formrow">			
			<div class="wt_productfeed_schedule_now_interval_radio_block">
				<label for="wt_productfeed_cron_interval_day"><input type="radio" id="wt_productfeed_cron_interval_day" name="wt_productfeed_cron_interval" value="day" checked="checked"> <?php _e('Every day', 'webtoffee-product-feed');?></label>
				<label for="wt_productfeed_cron_interval_week"><input type="radio" id="wt_productfeed_cron_interval_week" name="wt_productfeed_cron_interval" value="week"> <?php _e('Every week', 'webtoffee-product-feed');?></label>
				<!-- <label for="wt_productfeed_cron_interval_biweek"><input type="radio" id="wt_productfeed_cron_interval_biweek" name="wt_productfeed_cron_interval" value="biweek"> <?php _e('Biweekly', 'webtoffee-product-feed');?></label> -->
				<label for="wt_productfeed_cron_interval_month"><input type="radio" id="wt_productfeed_cron_interval_month" name="wt_productfeed_cron_interval" value="month"> <?php _e('Every month', 'webtoffee-product-feed');?></label>
				<label for="wt_productfeed_cron_interval_custom"><input type="radio" id="wt_productfeed_cron_interval_custom" name="wt_productfeed_cron_interval" value="custom"> <?php _e('Custom', 'webtoffee-product-feed');?></label>
			</div>
			<div class="wt_productfeed_schedule_now_interval_sub_block wt_productfeed_schedule_custom_interval_block">
				<label><?php _e('Custom interval');?></label>
				<input type="number" step="1" min="1" name="wt_productfeed_cron_interval_val" value="" placeholder="<?php _e('Interval in minutes.');?>">
				<span class="wt-pfd_form_help" style="margin-top:3px;"><?php _e('Recommended: Minimum 2 hour(120 minutes)');?></span>
			</div>
			<div class="wt_productfeed_schedule_now_interval_sub_block wt_productfeed_schedule_day_block">
				<label><?php _e('Which day?');?></label>
				<div class="wt_productfeed_schedule_now_interval_radio_block">				
					<?php
					$days = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
					foreach ($days as $day)
					{
						$day_vl=strtolower($day);
						$checked=($day_vl=='sun' ? ' checked="checked"' : '');
						?>
						<label for="wt_productfeed_cron_day_<?php echo $day_vl;?>"><input type="radio" value="<?php echo $day_vl;?>" id="wt_productfeed_cron_day_<?php echo $day_vl;?>" name="wt_productfeed_cron_day" <?php echo $checked;?>> <?php _e($day);?></label>
						<?php
					}
					?>
				</div>
			</div>
			<div class="wt_productfeed_schedule_now_interval_sub_block wt_productfeed_schedule_date_block">
				<label><?php _e('Day of the Month?');?></label>
				<select name="wt_productfeed_cron_interval_date">
					<?php
					for($i=1; $i<=28; $i++)
					{
						?>
						<option value="<?php echo $i;?>"><?php echo $i;?></option>
						<?php
					}
					?>
					<option value="last_day"><?php _e('Last day');?></option>
				</select>
			</div>
			<div class="wt_productfeed_schedule_now_interval_sub_block wt_productfeed_schedule_starttime_block">
				<label><?php _e('Start time');?></label> 
                                <div style="float:left">
                                    <input  type="number" step="1" min="1" max="12" name="wt_productfeed_cron_start_val" value="" />
                                    <span class="wt-pfd_form_help" style="display:block; margin-top: 1px">Hour</span>
                                </div>
                                <div style="float:left">
                                    <span class="wt_productfeed_cron_start_val_min">:</span><input type="number" step="1" min="0" max="59" name="wt_productfeed_cron_start_val_min" value="" onchange="if(parseInt(this.value,10)<10)this.value='0'+this.value;" />
                                    <span class="wt-pfd_form_help" style="display:block;  margin-top: 1px">Minute</span>
                                </div>
                                <div style="float:left">
                                    <select name="wt_productfeed_cron_start_ampm_val">
                                    <?php
                                    $am_pm=array('AM', 'PM');
                                    foreach($am_pm as $apvl)
                                    {
                                            ?>
                                            <option><?php echo $apvl;?></option>
                                            <?php
                                    }
                                    ?>
                                    </select>
                                </div>
			</div>
		</div>

		<div class="wt_productfeed_schedule_now_trigger_url">
			<label><?php _e('Trigger URL');?></label>
			<div class="wt_productfeed_schedule_now_formrow" style="margin-bottom:0px;">
				<input type="text" name="wt_productfeed_cron_url" value="" />
				<p style="color:red; margin:0px;"><?php _e('Use the generated URL to run cron.'); ?></p>
				<!-- <p>Eg: */2 * * * * wget -O /dev/null url >/dev/null 2>&1  </p> -->
			</div>
		</div>

		<div class="wt_productfeed_popup_footer">
			<button type="button" name="" class="button-secondary wt_productfeed_popup_cancel">
				<?php _e('Cancel');?> 
			</button>
			<button type="button" name="" class="button-primary wt_productfeed_save_schedule"><?php _e('Schedule now');?></button>	
		</div>
	</div>
</div>