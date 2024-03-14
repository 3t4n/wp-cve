<?php
if(!defined( 'ABSPATH' )){
	exit;
}

global $MWXS_L;
$page_url = 'admin.php?page=myworks-wc-xero-sync-connection';
$pfn = MW_WC_XERO_SYNC_PLUGIN_NAME;

$mw_wc_xero_license = $MWXS_L->get_option('mw_wc_xero_license','');
$mw_wc_xero_localkey = $MWXS_L->get_option('mw_wc_xero_localkey','');

$connection_key_section = false;
$f_xc_key = '';
$pls = $MWXS_L->get_license_status();
?>

<div class="ncd-cnt body-content">
<div class="margin">

<?php 
	if($MWXS_L->is_valid_license($mw_wc_xero_license,$mw_wc_xero_localkey)):
	$pls = $MWXS_L->get_license_status();
	$ldfcpv = $MWXS_L->get_ldfcpv();
	
	$is_xero_connected = $MWXS_L->is_xero_connected();
	#$is_xero_connected = false;
	$xero_company_info = false;
	
	$x_c_status_txt = 'Not Connected';
	
	if($is_xero_connected){
		$xero_company_info = $MWXS_L->get_connected_xero_cd();
		#$MWXS_L->_p($xero_company_info);
		$x_c_status_txt = 'Connected';		
	}
	
	$x_p_d_url = 'https://support.myworks.software/hc/en-us/categories/10141789949335-WooCommerce-Sync-for-Xero';
	$f_xc_key = $MWXS_L->get_option('mw_wc_xero_f_xc_key');
?>

<!--Design-->
<div class="rows">
	<div class="cols-md-8 left-row">
		<div class="left-outer">
			<div class="left-iner">
				<div class="lt-top">
					<div class="lkq">
						<div class="lkg-img">
							<img src="<?php echo esc_url(plugins_url( $pfn.'/admin/image/quick.png' )) ?>" class="img-res" alt="">
						</div>
						<div class="lkg-content">
							<h3><?php _e('Xero Connection','myworks-sync-for-xero');?></h3>
							<span><?php _e('WooCommerce Sync for Xero','myworks-sync-for-xero');?></span>
						</div>
					</div>
				</div>
				
				<div class="md-licence">
					<div class="inr-l">
						<div class="value-key">
							<label for="mw_wc_xero_license"><?php _e('License key','myworks-sync-for-xero');?></label>
							<input title="<?php echo __('To update your license key, deactivate and re-activate the plugin. All your settings and mappings will be saved.','myworks-sync-for-xero');?>" type="text" name="mw_wc_xero_license" id="mw_wc_qbo_sync_license_update" value="<?php echo esc_attr($mw_wc_xero_license);?>" disabled>
							
							<div class="refresh-key" style="display:none;">
								<img src="<?php echo esc_url(plugins_url( $pfn.'/admin/image/refresh-key.png' )) ?>" class="img-res">
								<span>
									<a id="mwqs_dllk" title="<?php echo __('Refresh your license information','mw_wc_qbo_sync');?>" href="javascript:void(0);"><?php _e('Refresh License','myworks-sync-for-xero');?></a>
								</span>
								<?php 
									wp_nonce_field( 'myworks_wc_qbo_sync_del_license_local_key', 'del_license_local_key' );
								?>
							</div>
						</div>
					</div>
					
					<div class="licence-list">
						<ul>
							<li class="current">
								<div class="left-status">
									<?php _e('Status','myworks-sync-for-xero');?>
								</div>
								<div class="right-status">
									<?php echo (isset($ldfcpv['status']))?$MWXS_L->escape($ldfcpv['status']):''?>
								</div>
							</li>
							
							<li>
								<div class="left-status">
									<?php _e('Plan','myworks-sync-for-xero');?>
								</div>
								<div class="right-status">
									<?php echo (isset($ldfcpv['plan']))?$MWXS_L->escape($ldfcpv['plan']):''?>
								</div>
							</li>
							
							<li>
								<div class="left-status">
									<?php _e('Next Due Date','myworks-sync-for-xero');?>
								</div>
								<div class="right-status">
									<?php echo (isset($ldfcpv['nextduedate']) && !empty($ldfcpv['nextduedate']) && $ldfcpv['nextduedate'] != '0000-00-00')?date('M j, Y',strtotime($ldfcpv['nextduedate'])):''?>
								</div>
							</li>
							
							<li>
								<div class="left-status">
									<?php _e('Billing Cycle','myworks-sync-for-xero');?>
								</div>
								<div class="right-status">
									<?php echo (isset($ldfcpv['billingcycle']))?$MWXS_L->escape($ldfcpv['billingcycle']):''?>
								</div>
							</li>

							<li>
								<div class="left-status">
									<?php _e('Monthly Orders','myworks-sync-for-xero');?>
								</div>								
								<div class="right-status">
									0 of 0
								</div>
							</li>
						</ul>
					</div>
				</div>
				
				<?php  if($is_xero_connected):?>
				<div class="quick-book">
					<div class="quick-pdng">
						<h3><?php _e('Manage Xero Connection','myworks-sync-for-xero');?></h3>
						<p>
						<?php _e('You\'re already connected to Xero, you can manage your connection here.','myworks-sync-for-xero');?></p>
						<div class="Connect-now">
							<a target="_blank" href="<?php echo esc_url($MWXS_L->get_xcd_url());?>" class="CmnBtn"><?php _e('Manage Connection','myworks-sync-for-xero');?></a>
						</div>
					</div>
				</div>
				<?php else:?>
				
				<div class="quick-book">
					<div class="quick-pdng">
						<h3><?php _e('Connect to Xero','myworks-sync-for-xero');?></h3>
						<p>
							<?php _e('Your license key is active, click here to connect to your Xero account.','myworks-sync-for-xero');?>
						</p>
						<div class="Connect-now">
							<a  target="_blank" href="<?php echo esc_url($MWXS_L->get_xcd_url());?>" class="CmnBtn"><?php _e('Connect','myworks-sync-for-xero');?></a>
						</div>
						
						<?php if($connection_key_section):?>
						<div>
							<br>
							<p><?php _e('Already connected? Enter your connection key here','myworks-sync-for-xero');?></p>
							<div>
								<input style="min-width:300px;" type="text" id="f_xc_key" value="<?php echo esc_attr($f_xc_key);?>" placeholder="<?php _e('Connection Key','myworks-sync-for-xero');?>">								
							</div>
							<div class="Connect-now">
								<a id="a_sxck" href="#" class="CmnBtn"><?php _e((empty($f_xc_key))?'Save':'Update','myworks-sync-for-xero');?></a>
								<?php 
									wp_nonce_field( 'myworks_wc_xero_sync_save_xero_c_key', 'save_xero_c_key' );
								?>
							</div>
							<p id="sxk_am"></p>
						</div>
						<?php endif;?>

					</div>
				</div>
				<?php endif;?>				
			</div>
		</div>
	</div>
	
	<div class="cols-md-4 right-row">
		<div class="side-bar">
			<div class="i-img">
				<img src="<?php echo esc_url(plugins_url( $pfn.'/admin/image/i.png' )) ?>" class="img-res" alt="">
			</div>
			<h3><?php _e('Connection Info','myworks-sync-for-xero');?></h3>
			<?php  if($is_xero_connected):?>
			<?php if(is_array($xero_company_info) && !empty($xero_company_info)):?>
			<div class="usa-block">
				<h3>
					<?php
						echo $MWXS_L->escape($xero_company_info['Name']);
						if(!empty($xero_company_info['CountryCode'])){
							echo ' ['.$MWXS_L->escape($xero_company_info['CountryCode']).']';
						}
					?>
				</h3>
				<span>
					<?php echo $MWXS_L->escape($xero_company_info['Email']);?>
				</span>
			</div>
			<?php endif;?>
			
			<div class="licence-list">
				<ul>
					<li class="current">
						<div class="left-status">
							<?php _e('Status','myworks-sync-for-xero');?>
						</div>
						<div class="right-status">
							<?php echo $MWXS_L->escape($x_c_status_txt);?>
						</div>
					</li>					
				</ul>
			</div>
			<?php else:?>
			<div class="licence-list">
				<ul>
					<li class="current">
						<div class="left-status">
							<?php _e('Status','myworks-sync-for-xero');?>
						</div>
						<div class="right-status">
							<span style="color:red;"><?php echo $MWXS_L->escape($x_c_status_txt);?></span>
						</div>
					</li>					   
				</ul>
			</div>
			<!--Connection Refresh If Needed-->
			<?php endif;?>
		</div>
		
		<div class="side-bar dflt">
			<h3><?php _e('Check out our Documentation!','myworks-sync-for-xero');?></h3>
			<div class="usa-block">
				<p>
					<?php _e('Need help setting up or starting to use the sync? Check out our helpful documentation and videos to get up and running easily!','myworks-sync-for-xero');?>
					<br>
				</p>
				<div class="Connect-now">
					<a href="<?php echo esc_url($x_p_d_url);?>" target="_blank" class="CmnBtn">
						<?php _e('Documentation','myworks-sync-for-xero');?>
					</a>
				</div>
			</div>
		</div>
		
		<div class="side-bar dflt">
			<h3><?php _e('Still need help? Easily open a ticket.','myworks-sync-for-xero');?></h3>
			<div class="usa-block">
				<p>
					<?php _e('Have a question and can\'t find an answer in our documentation? Our helpful support team is always online via support ticket to give you a hand.','myworks-sync-for-xero');?>
				</p>
				<div class="Connect-now">
					<a href="http://myworks.software/account/submitticket.php" target="_blank" class="CmnBtn">Open Ticket</a>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	jQuery(document).ready(function($){
		$('#a_sxck').click(function(e){
			e.preventDefault();
			
			let f_xc_key = $('#f_xc_key').val();
			f_xc_key = $.trim(f_xc_key);
			if(f_xc_key == '' || f_xc_key.length != 35){
				$('#sxk_am').html('<br><span style="color:red;">Please enter a valid connection key</span>');
				return false;
			}
			
			var data = {
				"action": 'myworks_wc_xero_sync_save_xero_c_key',				
				"save_xero_c_key": $('#save_xero_c_key').val(),
				"f_xc_key": f_xc_key
			};
			
			$('#sxk_am').html('<br>Saving...');
			
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				data: data,
				cache:  false ,
				//datatype: "json",
				success: function(r){
					if(r!=0 && r!=''){
						$('#sxk_am').html(r);
						location.reload();
					}else{
						$('#sxk_am').html('<br><span style="color:red;">Something went wrong</span>');
					}					
				},
				error: function(r) {
					$('#sxk_am').html('<br><span style="color:red;">Something went wrong</span>');
				}
			});
		});
	});
</script>

<?php else:?>

<?php
	$ilm = '';
	switch($pls){
		case 'Invalid':
			$ilm = __('Please enter a valid license key to proceed.','myworks-sync-for-xero');
			break;
		
		case 'Expired':
			$ilm = __('Your license key is expired. Please check your account with us to renew your plan or enter a valid license key.','myworks-sync-for-xero');
			break;
		
		case 'Suspended':
			$ilm = __('Your license key is suspended. Please check your account with us to renew your plan or enter a valid license key.','myworks-sync-for-xero');
			break;
		
		default:
			$ilm = __('Please enter a valid license key to proceed.','myworks-sync-for-xero');
	}
?>

<?php if($pls != 'Active'):?>
<div class="qbd_input_license">
	<p><?php echo $MWXS_L->escape($ilm);?></p>
	<div class="mwqs_conection_license_check">
		<form method="post" id="myworks_wc_xero_sync_check_license">
			<label for ="mw_wc_xero_license">Please enter your license key below. Don't have one? Sign up for a MyWorks account. </label>
			<input type="text" name="mw_wc_xero_license" id="mw_wc_xero_license" value="<?php echo esc_attr($mw_wc_xero_license);?>">
			<?php wp_nonce_field( 'myworks_wc_xero_sync_check_license', 'check_plugin_license' ); ?>
			
			<input size="30" type="submit" value="Enter" class="button button-primary">
			<span id="mwqs_license_chk_loader" style="visibility:hidden;">
				<img src="<?php echo esc_url( plugins_url( 'image/ajax-loader.gif', dirname(__FILE__) ) );?>" alt="Loading..." />
			</span>
		</form>
	</div>
</div>
<?php endif;?>

<?php endif;?>
</div>
</div>