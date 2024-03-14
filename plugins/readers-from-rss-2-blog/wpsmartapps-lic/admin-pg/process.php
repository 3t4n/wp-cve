<?php  
$form_1 = 'rfr2b_active_form_1';
$form_2 = 'rfr2b_active_form_2';
// Activate the plugin if email already on list
if ( trim($_GET['rfr2b_onlist']) == 1 ) {
	$this->rfr2b_freepluginreg = 22191;
	update_option('rfr2b_activate', $this->rfr2b_freepluginreg);
} 
// If registration form is successfully submitted
if ( ((trim($_GET['submit']) != '' && trim($_GET['email']) != '') || trim($_GET['activate_again']) != '') && $this->rfr2b_freepluginreg != '22191' ) { 
	update_option('rfr2b_name', $_GET['name']);
	update_option('rfr2b_email', $_GET['from']);
	$this->wsa_freepluginreg = 1;
	update_option('rfr2b_activate', $this->wsa_freepluginreg);
} 
$this->rfr2b_chkpluginreg = get_option('rfr2b_activate');
if ( intval($this->rfr2b_chkpluginreg) == 0 ) { 
	global $userdata;
	if( $userdata->first_name == '' || $userdata->last_name == '' )  $display_name = $userdata->display_name;
	else $display_name = $userdata->first_name.' '.$userdata->last_name;
	$name  = trim($display_name);
	$email = trim($userdata->user_email);
?>
<!--Start Of First Step-->	
<div style="padding-top:1px; padding-bottom:10px;">
	<div style="float:right; width:450px; color:#717171; font-weight:bold; background:#EBEB53; padding:10px; -webkit-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09);
	-moz-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09); box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09); -moz-border-radius: 5px; border-radius: 5px;">
		<div align="center" style="padding-bottom:5px; color:#FF3300; font-size:14px;">[100% FREE] Activate Hidden Pro Feature Now</div>		
		<center><?php $this->__rfr2b_PluginActivateForm($form_name,'Activate Now',$name,$email);?></center>
	</div>
	<h3><span style="padding-bottom:0px; color:#E68B01; font-weight:bold;">Global RSS Display Configuration</span></h3>
	<span style="font-size:11px;border-bottom:1px dotted #C2CFF1; padding-bottom:8px; width:90%;">(Below Settings Will Appear On Each FEED ITEMS)</span>
</div>
<!--Eof First Step-->	
<?php 
} else if ( intval($this->rfr2b_chkpluginreg) == 1 ) {  
	$name  = get_option('rfr2b_name');
	$email = get_option('rfr2b_email');
	$this->__rfr2b_StepTwoRegister($form_2,$name,$email);
	// Final Step
} else if ( intval($this->rfr2b_chkpluginreg) == 22191 ) { 
?>
<!--Final Value Call-->	
<div style="padding-top:1px; padding-bottom:10px;">
	<div style="float:right; width:450px; color:#717171; font-weight:bold; background:#F5F6F7; padding:10px; -webkit-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09);
	-moz-box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09); box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.09); -moz-border-radius: 5px; border-radius: 5px; line-height:20px;">
		<div align="center" style="padding-bottom:5px; color:#FF3300; font-size:14px;">One Pro Feature Feedburner Email Subscription Active <br> 
		<span style="color:#000000; font-size:12px; font-weight:normal;">Please go to <a href="<?php echo RFR2B_SITEURL; ?>/wp-admin/widgets.php" style="color:#0033CC; text-decoration:none;">widget</a> and drag and drop 'Readers From RSS 2 Blog' <br>to configure and activate this feature</span> </div>		
	</div>
	<h3><span style="padding-bottom:0px; color:#E68B01; font-weight:bold;">Global RSS Display Configuration</span></h3>
	<span style="font-size:11px;border-bottom:1px dotted #C2CFF1; padding-bottom:8px; width:90%;">(Below Settings Will Appear On Each FEED ITEMS)</span>
</div>
<!--Eof Final Value Call-->	
<?php  
}
?>