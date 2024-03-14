<?php 
if( !defined('ABSPATH') ){ exit();}
?>
<div >


	<form method="post" name="xyz_smap_logs_form">
		<fieldset
			style="width: 99%; border: 1px solid #F7F7F7; padding: 10px 0px;">
			


<div style="text-align: left;padding-left: 7px;"><h3> <?php _e('Auto Publish Logs','social-media-auto-publish');?> </h3></div>
	<span> <?php _e('Last ten logs of each social media account','social-media-auto-publish');?> </span>
		   <table class="widefat" style="width: 99%; margin: 0 auto; border-bottom:none;">
				<thead>
					<tr class="xyz_smap_log_tr">
						<th scope="col" width="1%">&nbsp;</th>
						<th scope="col" width="12%"> <?php _e('Post Id','social-media-auto-publish');?> </th>
						<th scope="col" width="12%"> <?php _e('Post Title','social-media-auto-publish');?> </th>
						<th scope="col" width="18%"> <?php _e('Account type','social-media-auto-publish');?> </th>
						<th scope="col" width="18%"> <?php _e('Published On','social-media-auto-publish');?> </th>
						<th scope="col" width="15%"> <?php _e('Status','social-media-auto-publish');?> </th>
					</tr>
				</thead>
				<?php 
				
				
				$post_fb_logsmain = get_option('xyz_smap_fbap_post_logs' );
				$post_tw_logsmain = get_option('xyz_smap_twap_post_logs' );
				$post_ln_logsmain = get_option('xyz_smap_lnap_post_logs' );
				$post_ig_logsmain = get_option('xyz_smap_igap_post_logs' );
				$post_tmb_logsmain = get_option('xyz_smap_tbap_post_logs' );
				$post_fb_logsmain_array = array();$post_tmb_logsmain_array = array();
				$post_tw_logsmain_array = array();$post_ln_logsmain_array = array();$post_ig_logsmain_array = array();
                                if(is_array($post_fb_logsmain))
                                {
				foreach ($post_fb_logsmain as $logkey1 => $logval1)
				{
					$post_fb_logsmain_array[]=$logval1;
				
				}
                 }
                if(is_array($post_tmb_logsmain))
               {
                   foreach ($post_tmb_logsmain as $logkey => $logval)
                    {
                        $post_tmb_logsmain_array[]=$logval;
                    }
               } //echo"<pre>"; print_r($post_tmb_logsmain_array);die;
                 if(is_array($post_tw_logsmain))
                 {
                 	
                 	foreach ($post_tw_logsmain as $logkey2 => $logval2)
                 	{
                 		$post_tw_logsmain_array[]=$logval2;
                 	}
                 }
                 if(is_array($post_ln_logsmain))
                 {
                 
                 	foreach ($post_ln_logsmain as $logkey3 => $logval3)
                 	{
                 		$post_ln_logsmain_array[]=$logval3;
                 	}
                 }
                 if(is_array($post_ig_logsmain))
                 {
                     foreach ($post_ig_logsmain as $logkey1 => $logval1)
                     {
                         $post_ig_logsmain_array[]=$logval1;
                         
                     }
                 }
				
                 if((is_array($post_fb_logsmain_array))||(is_array($post_tw_logsmain_array))||(is_array($post_ln_logsmain_array))||(is_array($post_ig_logsmain_array))||(is_array($post_tmb_logsmain_array)))
				{
					for($i=9;$i>=0;$i--)
					{
						if(!empty($post_fb_logsmain_array) && array_key_exists($i,$post_fb_logsmain_array)){
						if($post_fb_logsmain_array[$i]!='')
							{
								$post_fb_logs=$post_fb_logsmain_array[$i];
						
								$postid=$post_fb_logs['postid'];
							    $acc_type=$post_fb_logs['acc_type'];
								$publishtime=$post_fb_logs['publishtime'];
								if($publishtime!="")
									$publishtime=xyz_smap_local_date_time('Y/m/d g:i:s A',$publishtime);
								$status=$post_fb_logs['status'];
							
							?>
							<tr>
							    <td>&nbsp;</td>
							    <td  style="vertical-align: middle !important;">
								<?php echo esc_html($postid);	?>
								</td>
								<td  style="vertical-align: middle !important;">
								<?php echo get_the_title($postid);	?>
								</td>
								
								<td  style="vertical-align: middle !important;">
								<?php echo esc_html($acc_type);?>
								</td>
								
								<td style="vertical-align: middle !important;">
								<?php echo esc_html($publishtime);?>
								</td>
								
								<td class='xyz_smap_td_custom' style="vertical-align: middle !important;">
								<?php
									if($status=="1"){
										echo "<span style=\"color:green\">Success</span>";
									}
									else if($status=="0")
										echo '';
										else
										{
											$arrval=unserialize($status);
											foreach ($arrval as $a=>$b)
												echo $b;
										}
								 ?>
								</td>
							</tr>
							<?php  
							}}
							if(!empty($post_tw_logsmain_array) && array_key_exists($i,$post_tw_logsmain_array)){
							if($post_tw_logsmain_array[$i]!='')
							{
								$post_tw_logs=$post_tw_logsmain_array[$i];
								$postid=$post_tw_logs['postid'];
								$acc_type=$post_tw_logs['acc_type'];
								$publishtime=$post_tw_logs['publishtime'];
								if($publishtime!="")
									$publishtime=xyz_smap_local_date_time('Y/m/d g:i:s A',$publishtime);
								$status=$post_tw_logs['status'];
								?>
								<tr>
									<td>&nbsp;</td>
									 <td  style="vertical-align: middle !important;">
								     <?php echo esc_html($postid);	?>
								     </td>
									<td  style="vertical-align: middle !important;">
									<?php echo get_the_title($postid);	?>
									</td>
									
									<td  style="vertical-align: middle !important;">
									<?php echo esc_html($acc_type);?>
									</td>
									
									<td style="vertical-align: middle !important;">
									<?php echo esc_html($publishtime);?>
									</td>
									
									<td class='xyz_smap_td_custom' style="vertical-align: middle !important;">
									<?php
									
									
									if($status=="1")
									echo "<span style=\"color:green\">Success</span>";
									else if($status=="0")
									echo '';
									else
									{
									$arrval=unserialize($status);print_r($arrval);
									//foreach ($arrval as $a=>$b)
								//	echo $b;
									
									}
									
									?>
									</td>
								</tr>
								<?php  
							}}
							
							if(!empty($post_tmb_logsmain_array) && array_key_exists($i,$post_tmb_logsmain_array)) {
							    if($post_tmb_logsmain_array[$i]!='')
							        {
							            $post_tmb_logs=$post_tmb_logsmain_array[$i];
							            $postid=$post_tmb_logs['postid'];
							            $acc_type=$post_tmb_logs['acc_type'];
							            $publishtime=$post_tmb_logs['publishtime'];
							            if($publishtime!="")
							                $publishtime=xyz_smap_local_date_time('Y/m/d g:i:s A',$publishtime);
							                $status=$post_tmb_logs['status'];
							                ?>
								<tr>	
									<td>&nbsp;</td>
									<td  style="vertical-align: middle !important;">
									<?php echo esc_html($postid);	?>
									</td>
									<td  style="vertical-align: middle !important;">
									<?php echo get_the_title($postid);	?>
									</td>
									<td  style="vertical-align: middle !important;">
									<?php echo esc_html($acc_type);?>
									</td>
									<td style="vertical-align: middle !important;">
									<?php echo esc_html($publishtime);?>
									</td>
									<td class='xyz_smap_td_custom' style="vertical-align: middle !important;">
									<?php
									if($status=="1")
									echo "<span style=\"color:green\">Success</span>";
									else if($status=="0")
									echo '';
									else
									{
										$arrval=unserialize($status);
										//foreach ($arrval as $a=>$b)
										echo $arrval;
									}
									?>
									</td>
								</tr>
								<?php  
							}
					 }
							if(!empty($post_ln_logsmain_array) && array_key_exists($i,$post_ln_logsmain_array)){
								if($post_ln_logsmain_array[$i]!='')
								{
									$post_ln_logs=$post_ln_logsmain_array[$i];
									$postid=$post_ln_logs['postid'];
									$acc_type=$post_ln_logs['acc_type'];
									$publishtime=$post_ln_logs['publishtime'];
									if($publishtime!="")
										$publishtime=xyz_smap_local_date_time('Y/m/d g:i:s A',$publishtime);
										$status=$post_ln_logs['status'];
										
										?>
								<tr>
									<td>&nbsp;</td>
									 <td  style="vertical-align: middle !important;">
								     <?php echo esc_html($postid);	?>
								     </td>
									<td  style="vertical-align: middle !important;">
									<?php echo get_the_title($postid);	?>
									</td>
									
									<td  style="vertical-align: middle !important;">
									<?php echo esc_html($acc_type);?>
									</td>
									
									<td style="vertical-align: middle !important;">
									<?php echo esc_html($publishtime);?>
									</td>
									
									<td class='xyz_smap_td_custom' style="vertical-align: middle !important;">
									<?php
									
									
									if($status=="1")
									echo "<span style=\"color:green\">Success</span>";
									else if($status=="0")
									echo '';
									else
									{
									$arrval=unserialize($status);
									print_r($arrval);
									/*foreach ($arrval as $a=>$b)
									echo "<span style=\"color:red\">".$a." : ".$b."</span><br>";*/
									
									}
									
									?>
									</td>
								</tr>
								<?php  
							}}
							if(!empty($post_ig_logsmain_array) && array_key_exists($i,$post_ig_logsmain_array)){
							    if($post_ig_logsmain_array[$i]!='')
							    {
							        $post_ig_logs=$post_ig_logsmain_array[$i];
							        $postid=$post_ig_logs['postid'];
							        $acc_type=$post_ig_logs['acc_type'];
							        $publishtime=$post_ig_logs['publishtime'];
							        if($publishtime!="")
							            $publishtime=xyz_smap_local_date_time('Y/m/d g:i:s A',$publishtime);
							            $status=$post_ig_logs['status'];
							            
							            ?>
								<tr>
									<td>&nbsp;</td>
									 <td  style="vertical-align: middle !important;">
								     <?php echo esc_html($postid);	?>
								     </td>
									<td  style="vertical-align: middle !important;">
									<?php echo get_the_title($postid);	?>
									</td>
									
									<td  style="vertical-align: middle !important;">
									<?php echo esc_html($acc_type);?>
									</td>
									
									<td style="vertical-align: middle !important;">
									<?php echo esc_html($publishtime);?>
									</td>
									
									<td class='xyz_smap_td_custom' style="vertical-align: middle !important;">
									<?php
									
									
									if($status=="1")
									echo "<span style=\"color:green\">Success</span>";
									else if($status=="0")
									echo '';
									else
									{
									$arrval=unserialize($status);
									print_r($arrval);
									/*foreach ($arrval as $a=>$b)
									echo "<span style=\"color:red\">".$a." : ".$b."</span><br>";*/
									
									}
									
									?>
									</td>
								</tr>
								<?php  
							}}
						}
						
                                     }
                                     if($post_fb_logsmain=="" && $post_tw_logsmain=="" && $post_ln_logsmain=="" && $post_ig_logsmain=="" && $post_tmb_logsmain==""){?>
						<tr><td colspan="5" style="padding: 5px;"> <?php _e('No logs Found','social-media-auto-publish');?> </td></tr>
					<?php }?>
				
           </table>
			
		</fieldset>

	</form>
	<div style="padding: 5px;color: #e67939;font-size: 14px;"> 
	<?php _e('For publishing a simple text message, it will take 1 API call, Upload image option will take 2-3 API calls in Facebook and 4 api calls in Linkedin and attach link option take 1 API call(2 api calls for facebook if enabled option for clearing cache).','social-media-auto-publish');?> </div>

</div>
				
