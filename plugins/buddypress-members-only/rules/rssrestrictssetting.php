<?php
if(!defined('WPINC'))
{
	exit ('Please do not access our files directly.');
}

function buddypress_members_rss_restricts_setting_free()
{
	global $wpdb;

	if (isset($_POST['bprssrestrictssubmit']))
	{
		
		check_admin_referer( 'bprssrestrictsnonce' );
		if (isset($_POST['bpenableaallbprssrestricts']))
		{
		    //3.2.3
		    $bpenableaallbprssrestricts = sanitize_text_field($_POST['bpenableaallbprssrestricts']);
			//$bpenableaallbprssrestricts = $_POST['bpenableaallbprssrestricts'];
			update_option('bpenableaallbprssrestricts',$bpenableaallbprssrestricts);
		}
		else
		{
			delete_option('bpenableaallbprssrestricts');
		}

		
		
//3.3.3
		if (isset($_POST['bpenablerssrestricts']))
		{
		    //3.2.3
		    $bpenablerssrestricts = sanitize_text_field($_POST['bpenablerssrestricts']);
		    update_option('bpenablerssrestricts',$bpenablerssrestricts);
		}
		else
		{
		    delete_option('bpenablerssrestricts');
		}
		
//end 3.3.3
		$bpmoMessageString =  __( 'Your changes has been saved.', 'bp-members-only' );
		buddypress_members_only_message($bpmoMessageString);
	}
	echo "<br />";
	?>

<div style='margin:10px 5px;'>
<div style='float:left;margin-right:10px;'>
<img src='<?php echo get_option('siteurl');  ?>/wp-content/plugins/buddypress-members-only/images/new.png' style='width:30px;height:30px;'>
</div> 
<div style='padding-top:5px; font-size:22px;'>Buddypress Members Only RSS Restricts Settings:</div>
</div>
<div style='clear:both'></div>		
		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:90%;">
								<div class="postbox">
									<h3 class='hndle' style='padding: 20px; !important'>
									<span>
									<?php 
											echo  __( 'RSS Restricts Settings Panel :', 'bp-members-only' );
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
										<form id="bpmoform" name="bpmoform" action="" method="POST">
										<table id="bpmotable" width="100%">
										
										<tr style="margin-top:30px;">
										<td width="30%" style="padding: 20px;" valign="top">
										<?php 
											echo  __( 'Enable BuddyPress Activity RSS Restricts:', 'bp-members-only' ); //!!! 4.8.4
										?>
										</td>
										<td width="70%" style="padding: 20px;">
										<?php 
										$bpenableaallbprssrestricts = get_option('bpenableaallbprssrestricts'); 
										if (!(empty($bpenableaallbprssrestricts)))
										{
											
										}
										else
										{
											$bpenableaallbprssrestricts = '';
										}
										?>
										<?php 
										if (!(empty($bpenableaallbprssrestricts)))
										{
											echo '<input type="checkbox" id="bpenableaallbprssrestricts" name="bpenableaallbprssrestricts"  style="" value="yes"  checked="checked"> Enable BP Activity RSS Restricts ';
										}
										else 
										{
											echo '<input type="checkbox" id="bpenableaallbprssrestricts" name="bpenableaallbprssrestricts"  style="" value="yes" > Enable BP Activity RSS Restricts ';
										}
										?>
										<p><font color="Gray"><i>
										<?php 
											echo  __( '# If you enabled this option,  ', 'bp-members-only' );
											echo  __( ' we will restricts rss feed for buddypress activity.', 'bp-members-only' );
										?>
										</i></p>
										</td>
										</tr>
										
<?php //3.3.3 ?>
										<tr style="margin-top:30px;">
										<td width="30%" style="padding: 20px;" valign="top">
										<?php 
											echo  __( 'Enable Wordpress RSS Restricts:', 'bp-members-only' ); //!!! 4.8.4
										?>
										</td>
										<td width="70%" style="padding: 20px;">
										<?php 
										$bpenablerssrestricts = get_option('bpenablerssrestricts');
										if (!(empty($bpenablerssrestricts)))
										{
											
										}
										else
										{
										    $bpenablerssrestricts = '';
										}
										?>
										<?php 
										if (!(empty($bpenablerssrestricts)))
										{
											echo '<input type="checkbox" id="bpenablerssrestricts" name="bpenablerssrestricts"  style="" value="yes"  checked="checked"> Enable Wordpress RSS Restricts ';
										}
										else 
										{
											echo '<input type="checkbox" id="bpenablerssrestricts" name="bpenablerssrestricts"  style="" value="yes" > Enable Wordpress Activity RSS Restricts ';
										}
										?>
										<p><font color="Gray"><i>
										<?php 
											echo  __( '# If you enabled this option,  ', 'bp-members-only' );
											echo  __( ' we will restricts rss feed for wordpress.', 'bp-members-only' );
										?>
										</i></p>
										</td>
										</tr>										
<?php //end 3.3.3 ?>										
										</table>
										<br />
										<?php
											wp_nonce_field('bprssrestrictsnonce');
										?>
										<input type="submit" id="bprssrestrictssubmit" name="bprssrestrictssubmit" value=" Submit " style="margin:1px 20px;">
										</form>
										
										<br />
									</div>
								</div>
							</div>
						</div>
					</div>
		    	</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<br />
		<?php
	
	
}
?>