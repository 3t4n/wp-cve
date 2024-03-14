<?php 
if( !defined('ABSPATH') ){ exit();}
?>
<div >
	<form method="post" name="xyz_smap_logs_form">
		<fieldset
			style="width: 99%; border: 1px solid #F7F7F7; padding: 10px 0px;">
<div style="text-align: left;padding-left: 7px;"><h3> <?php _e('Auto Publish Logs','linkedin-auto-publish'); ?> </h3></div>
	<span> <?php _e('Last ten logs','linkedin-auto-publish'); ?> </span>
		   <table class="widefat" style="width: 99%; margin: 0 auto; border-bottom:none;">
				<thead>
					<tr class="xyz_smap_log_tr">
						<th scope="col" width="1%">&nbsp;</th>
						<th scope="col" width="12%"> <?php _e('Post Id','linkedin-auto-publish'); ?> </th>
						<th scope="col" width="12%"> <?php _e('Post Title','linkedin-auto-publish'); ?> </th>
						<th scope="col" width="18%"> <?php _e('Published On','linkedin-auto-publish'); ?> </th>
						<th scope="col" width="15%"> <?php _e('Status','linkedin-auto-publish'); ?> </th>
					</tr>
					</thead>
					<?php 
					$post_ln_logsmain = get_option('xyz_lnap_post_logs' );
				if(is_array($post_ln_logsmain)) {	
					$post_ln_logsmain_array = array();
					foreach ($post_ln_logsmain as $logkey => $logval)
					{ $post_ln_logsmain_array[]=$logval; }
					if($post_ln_logsmain=='')
					{
						?>
						<tr><td colspan="4" style="padding: 5px;"> <?php _e('No logs Found','linkedin-auto-publish'); ?> </td></tr>
						<?php
					}
					if(is_array($post_ln_logsmain_array))
					   {
						for($i=9;$i>=0;$i--)
						{
							if(array_key_exists($i,$post_ln_logsmain_array)){
							if($post_ln_logsmain_array[$i]!='')
							{
								$post_ln_logs=$post_ln_logsmain_array[$i];
								$postid=$post_ln_logs['postid'];
								$acc_type=$post_ln_logs['acc_type'];
								$publishtime=$post_ln_logs['publishtime'];
								if($publishtime!="")
									$publishtime=xyz_lnap_local_date_time('Y/m/d g:i:s A',$publishtime);
								$status=$post_ln_logs['status'];
								?>
								<tr>
									<td>&nbsp;</td>
									<td  style="vertical-align: middle !important;padding: 5px;">
									<?php echo esc_html($postid);	?>
									</td>
									<td  style="vertical-align: middle !important;padding: 5px;">
									<?php echo get_the_title($postid);	?>
									</td>
									
									<td style="vertical-align: middle !important;padding: 5px;">
									<?php echo esc_html($publishtime);?>
									</td>
									
									<td class='xyz_lnap_td_custom' style="vertical-align: middle !important;padding: 5px;">
									<?php
									if($status=="1")
									echo "<span style=\"color:green\">Success</span>";
									else if($status=="0")
									echo '';
									else
									{
										$arrval=unserialize($status);
										print_r($arrval);
									}
									?>
									</td>
								</tr>
								<?php  
							}}
						}
					}
        }?>
           </table>
		</fieldset>
	</form>
	<div style="padding: 5px;color: #e67939;font-size: 14px;"> 
	<?php _e('For publishing a simple text message, it will take 1 API call, Upload image option will take 2-3 API calls and attach link option take 1 API call.','linkedin-auto-publish'); ?> </div>
</div>
				