<?php
if( !defined('ABSPATH') ){ exit();}
global $current_user;
$auth_varble=0;
wp_get_current_user();
$imgpath= plugins_url()."/linkedin-auto-publish/images/";
$heimg=$imgpath."support.png";

require( dirname( __FILE__ ) . '/authorization.php' );


if(!$_POST && isset($_GET['lnap_notice']) && $_GET['lnap_notice'] == 'hide')	
{
	if (! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'],'lnap-shw')){
		wp_nonce_ays( 'lnap-shw');
		exit;
	}
	update_option('xyz_lnap_dnt_shw_notice', "hide");
	?>
<style type='text/css'>
#lnap_notice_td
{
display:none !important;
}
</style>
<div class="system_notice_area_style1" id="system_notice_area">
<?php _e('Thanks again for using the plugin. We will never show the message again.','linkedin-auto-publish');?>
 &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss"> <?php _e('Dismiss','linkedin-auto-publish');?> </span>
</div>
<?php
}
if(!$_POST && isset($_GET['ln_auth_err']) && $_GET['ln_auth_err'] != '')
{
	?>
<style type='text/css'>
#lnap_notice_td
{
display:none !important;
}
</style>
<div class="system_notice_area_style0" id="system_notice_area">
<?php echo esc_html($_GET['ln_auth_err']);?>
 &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss" class="xyz_lnap_hide_ln_authErr"> <?php _e('Dismiss','linkedin-auto-publish');?> </span>
</div>

<?php
}
$lms1=$lms3="";
$lms2=$lms4="";
$lerf=0;$xyz_lnap_ln_company_ids='';

if(isset($_POST['linkdn']))
{
	if (! isset( $_REQUEST['_wpnonce'] )
			|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_lnap_ln_settings_nonce' )
			) {
	
				wp_nonce_ays( 'xyz_lnap_ln_settings_nonce' );
	
				exit();
	
			}
// 			print_r($_POST);//xyz_lnap_ln_share_post_company
	$xyz_lnap_lnshare_to_profile=get_option('xyz_lnap_lnshare_to_profile');
	$lnappikeyold=get_option('xyz_lnap_lnapikey');
	$lnapisecretold=get_option('xyz_lnap_lnapisecret');
	$xyz_lnap_ln_api_permission_old=get_option('xyz_lnap_ln_api_permission');
	$lnappikey=sanitize_text_field($_POST['xyz_lnap_lnapikey']);
	$lnapisecret=sanitize_text_field($_POST['xyz_lnap_lnapisecret']);
	$xyz_lnap_lnpost_method=intval($_POST['xyz_lnap_lnpost_method']);
	$lmessagetopost=trim($_POST['xyz_lnap_lnmessage']);
	$lnposting_permission=intval($_POST['xyz_lnap_lnpost_permission']);
	$xyz_lnap_ln_shareprivate=intval($_POST['xyz_lnap_ln_shareprivate']);
	if (isset($_POST['xyz_lnap_lnshare_to_profile']))
	$xyz_lnap_lnshare_to_profile=intval($_POST['xyz_lnap_lnshare_to_profile']);
	// $xyz_lnap_ln_sharingmethod=intval($_POST['xyz_lnap_ln_sharingmethod']);
	$xyz_lnap_ln_api_permission=intval($_POST['xyz_lnap_ln_api_permission']);
	$xyz_lnap_ln_share_post_company=get_option('xyz_lnap_ln_share_post_company');
	$lnaf=get_option('xyz_lnap_lnaf');
	$xyz_lnap_ln_signin_method=get_option('xyz_lnap_ln_signin_method');
	$xyz_lnap_enforce_og_tags=intval($_POST['xyz_lnap_enforce_og_tags']);
	$xyz_lnap_ln_signin_method=intval($_POST['xyz_lnap_ln_signin_method']);
	if($lnappikey!=$lnappikeyold || $lnapisecret!=$lnapisecretold || $xyz_lnap_ln_api_permission_old!=$xyz_lnap_ln_api_permission)
	{
	    update_option('xyz_lnap_lnaf',1);$lnaf=1;
	}
	if($lnappikey=="" && $lnposting_permission==1 && $xyz_lnap_ln_api_permission!=2)
	{
		$lms1= __('Please fill api key.','linkedin-auto-publish'); 
		$lerf=1;
	}
	elseif($lnapisecret=="" && $lnposting_permission==1 && $xyz_lnap_ln_api_permission!=2)
	{
		$lms2=  __('Please fill api secret','linkedin-auto-publish');
		$lerf=1;
	}
	elseif ($xyz_lnap_lnshare_to_profile==0 && empty($_POST['xyz_lnap_ln_share_post_company']) && $xyz_lnap_ln_api_permission!=2 && $lnposting_permission==1 && $lnaf==0)
	{
		$lms3=  __('Please select share post to profile (or company)','linkedin-auto-publish');
		$lerf=1;
	}
	elseif($lnaf==0 && $lmessagetopost=='' && $xyz_lnap_lnpost_method==1)
	{
		$lms4=  __('Please fill message format for posting','linkedin-auto-publish');
		$lerf=1;
	}
/* 	elseif($lmessagetopost=="" && $lnposting_permission==1)
	{
		$lms3= __('Please fill mssage format for posting.','linkedin-auto-publish');
		$lerf=1;
	} */
	else
	{

		$lerf=0;
		/* if($lmessagetopost=="")
		{
			$lmessagetopost="New post added at {BLOG_TITLE} - {POST_TITLE}";
		} */

		$xyz_lnap_ln_share_post_company=array();
		if($xyz_lnap_ln_api_permission==1 && isset($_POST['xyz_lnap_ln_share_post_company']) && get_option('xyz_lnap_lnaf')==0)
		{
			$xyz_lnap_ln_share_post_company=$_POST['xyz_lnap_ln_share_post_company'];
			if(!empty($xyz_lnap_ln_share_post_company))//count($xyz_lnap_ln_share_post_company)>0
			{
				for($i=0;$i<count($xyz_lnap_ln_share_post_company);$i++)
				{
					if($xyz_lnap_ln_share_post_company[$i] !=''){
						$xyz_lnap_ln_share_post_company_ids_and_names=explode('-',$xyz_lnap_ln_share_post_company[$i] );
						$xyz_lnap_ln_company_ids.=$xyz_lnap_ln_share_post_company_ids_and_names[0].',';
					}
				}
				$xyz_lnap_ln_company_ids=rtrim($xyz_lnap_ln_company_ids,',');
			}
		}
		elseif($xyz_lnap_ln_api_permission==2 && get_option('xyz_lnap_lnaf')==0)
		$xyz_lnap_ln_company_ids=get_option('xyz_lnap_ln_share_post_company');
		update_option('xyz_lnap_lnapikey',$lnappikey);
		update_option('xyz_lnap_lnapisecret',$lnapisecret);
		update_option('xyz_lnap_lnpost_permission',$lnposting_permission);
		update_option('xyz_lnap_ln_shareprivate',$xyz_lnap_ln_shareprivate);
		// update_option('xyz_lnap_ln_sharingmethod',$xyz_lnap_ln_sharingmethod);
		update_option('xyz_lnap_lnmessage',$lmessagetopost);
		update_option('xyz_lnap_lnshare_to_profile', $xyz_lnap_lnshare_to_profile);
		update_option('xyz_lnap_lnpost_method', $xyz_lnap_lnpost_method);
		update_option('xyz_lnap_ln_api_permission',$xyz_lnap_ln_api_permission);
		update_option('xyz_lnap_ln_share_post_company', $xyz_lnap_ln_company_ids);
		update_option('xyz_lnap_enforce_og_tags', $xyz_lnap_enforce_og_tags);
		update_option('xyz_lnap_ln_signin_method',$xyz_lnap_ln_signin_method);
}	
}
if(isset($_POST['linkdn']) && $lerf==0)
{?>
<div class="system_notice_area_style1" id="system_notice_area">
	<?php _e('Settings updated successfully','linkedin-auto-publish');?>.. &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss"> <?php _e('Dismiss','linkedin-auto-publish');?> </span>
</div>
<?php }
if(isset($_GET['msg']) && $_GET['msg']==1)
{ ?>
<div class="system_notice_area_style0" id="system_notice_area">
	<?php _e('Unable to authorize the linkedin application. Please check the details.','linkedin-auto-publish'); ?> &nbsp;&nbsp;&nbsp;<span
		id="system_notice_area_dismiss"> <?php _e('Dismiss','linkedin-auto-publish');?> </span>
</div>
<?php }
if(isset($_GET['msg']) && $_GET['msg'] == 4){
	?>
<div class="system_notice_area_style1" id="system_notice_area">
	
<?php _e('Account has been authenticated successfully.','linkedin-auto-publish'); ?> &nbsp;&nbsp;&nbsp;<span
id="system_notice_area_dismiss"> <?php _e('Dismiss','linkedin-auto-publish');?> </span>
</div>
<?php
}
if(isset($_GET['msg']) && $_GET['msg']==5)
{?>
<div class="system_notice_area_style1" id="system_notice_area">
	<?php $lnap_xyzscripts_name="xyzscripts";
	$lnap_xyz_success_msg=sprintf(__('Successfully connected to %s member area','linkedin-auto-publish'),$lnap_xyzscripts_name);
	echo $lnap_xyz_success_msg; ?>. &nbsp;&nbsp;&nbsp;<span
	id="system_notice_area_dismiss"> <?php _e('Dismiss','linkedin-auto-publish');?> </span>
</div>
<?php }
if(isset($_POST['linkdn']) && $lerf==1)
{
	?>
<div class="system_notice_area_style0" id="system_notice_area">
	<?php 
	 if(isset($_POST['linkdn']))
	{
		echo esc_html($lms1);echo esc_html($lms2);echo esc_html($lms3);echo esc_html($lms4);
	}
	?>
	&nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss"> <?php _e('Dismiss','linkedin-auto-publish');?>  </span>
</div>
<?php } ?>
<script type="text/javascript">
function detdisplay_lnap(id)
{
	document.getElementById(id).style.display='';
}
function dethide_lnap(id)
{
	document.getElementById(id).style.display='none';
}

/*function drpdisplay()
{
	var shmethod= document.getElementById('xyz_lnap_ln_sharingmethod').value;
	if(shmethod==1)	
	{
		document.getElementById('shareprivate').style.display="none";
	}
	else
	{
		document.getElementById('shareprivate').style.display="";
	}
}*/
</script>
<div style="width: 100%">
<div class="xyz_lnap_tab">
  <button class="xyz_lnap_tablinks" onclick="xyz_lnap_open_tab(event, 'xyz_lnap_linkedin_settings')" id="xyz_lnap_default_tab_settings"> <?php _e('Linkedin Settings','linkedin-auto-publish');?> </button>
   <button class="xyz_lnap_tablinks" onclick="xyz_lnap_open_tab(event, 'xyz_lnap_basic_settings')" id="xyz_lnap_basic_tab_settings"> <?php _e('General Settings','linkedin-auto-publish');?> </button>
</div>
<div id="xyz_lnap_linkedin_settings" class="xyz_lnap_tabcontent">
<?php
$domain_name=trim(get_option('siteurl'));
$xyz_lnap_smapsoln_userid=intval(trim(get_option('xyz_lnap_smapsoln_userid')));//xyz_lnap_secret_key
$xyzscripts_hash_val=trim(get_option('xyz_lnap_xyzscripts_hash_val'));
$xyzscripts_user_id=trim(get_option('xyz_lnap_xyzscripts_user_id'));
$xyz_smap_accountId=0;
$xyz_smap_licence_key='';
$request_hash=md5($xyzscripts_user_id.$xyzscripts_hash_val);
$lnappikey=esc_html(get_option('xyz_lnap_lnapikey'));
$lnapisecret=esc_html(get_option('xyz_lnap_lnapisecret'));
$lmessagetopost=esc_textarea(get_option('xyz_lnap_lnmessage'));
$lnaf=get_option('xyz_lnap_lnaf');
$xyz_lnap_ln_company_ids=get_option('xyz_lnap_ln_share_post_company');
if (get_option('xyz_lnap_ln_api_permission')!=2)
{
	if($lnaf==1 && $lnappikey!="" && $lnapisecret!="" )
	{
	?>
	<span style="color:red; "> <?php _e('Application needs authorisation','linkedin-auto-publish'); ?> </span><br>	
            <form method="post" >
			 <?php wp_nonce_field( 'xyz_lnap_auth_nonce' );?>
			<input type="submit" class="submit_lnap_new" name="lnauth" value="<?php _e('Authorize','linkedin-auto-publish'); ?>" />
			<br><br>
			</form>
			<?php  }
			else if($lnaf==0 && $lnappikey!="" && $lnapisecret!="" )
			{
				?>
            <form method="post" >
			<?php wp_nonce_field( 'xyz_lnap_auth_nonce' );?>
			<input type="submit" class="submit_lnap_new" name="lnauth" value="<?php _e('Reauthorize','linkedin-auto-publish'); ?>" title="Reauthorize the account" />
			<br><br>
			</form>
	<?php } 
}
else 
{
	$auth_secret_key=md5('smapsolutions'.$domain_name.$xyz_smap_accountId.$xyz_lnap_smapsoln_userid.$xyzscripts_user_id.$request_hash.$xyz_smap_licence_key.'lnap');
	if($lnaf==1 )
	{
	?>
 	<span id='ajax-save' style="display:none;"><img	class="img"  title="Saving details"	src="<?php echo plugins_url('../images/ajax-loader.gif',__FILE__);?>" style="width:65px;height:70px; "></span>
 	<span id="auth_message">
 		<span style="color: red;" > <?php _e('Application needs authorisation','linkedin-auto-publish');?></span> <br> </span> <br>
 		<form method="post">
 		<?php wp_nonce_field( 'xyz_lnap_ln_auth_nonce' );?>
 		 <input type="hidden" value="<?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?>" id="parent_domain">
 		<input type="submit" class="submit_lnap_new" name="ln_auth"
 			value="<?php _e('Authorize','linkedin-auto-publish'); ?>" onclick="javascript:return lnap_popup_ln_auth('<?php echo urlencode($domain_name);?>','<?php echo $xyz_lnap_smapsoln_userid;?>','<?php echo $xyzscripts_user_id;?>','<?php echo $xyzscripts_hash_val;?>','<?php echo $auth_secret_key;?>','<?php echo $request_hash;?>');void(0);"/><br><br>
 		</form> 
 		</span>
 		<?php }
 		else if($lnaf==0 )
 		{
 		?>
 		<span id='ajax-save' style="display:none;"><img	class="img"  title="Saving details"	src="<?php echo plugins_url('../images/ajax-loader.gif',__FILE__);?>" style="width:65px;height:70px; "></span>
 		<form method="post" id="re_auth_message">
 		<?php wp_nonce_field( 'xyz_lnap_ln_auth_nonce' );?>
 		<input type="hidden" value="<?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?>" id="parent_domain">
 		<input type="submit" class="submit_lnap_new" name="ln_auth"
 		value="<?php _e('Reauthorize','linkedin-auto-publish'); ?> " title="Reauthorize the account" onclick="javascript:return lnap_popup_ln_auth('<?php echo urlencode($domain_name);?>','<?php echo $xyz_lnap_smapsoln_userid;?>','<?php echo $xyzscripts_user_id;?>','<?php echo $xyzscripts_hash_val;?>','<?php echo $auth_secret_key;?>','<?php echo $request_hash;?>');void(0);"/><br><br>
 		</form>
 	<?php }
}?>
			<table class="widefat" style="width: 99%;background-color: #FFFBCC"  id="xyz_linkedin_settings_note">
	<tr>
<td id="bottomBorderNone" style="border: 1px solid #FCC328;">
	<div>
		<b> <?php _e('Note','linkedin-auto-publish'); ?>:</b> <?php _e('You have to create a Linkedin application before filling the following details.','linkedin-auto-publish'); ?>
		<b><a href="https://www.linkedin.com/secure/developer?newapp" target="_blank"> <?php _e('Click here </a></b> to create new Linkedin application.','linkedin-auto-publish'); ?>  
		<br> <?php _e('Specify the website url for the application as :','linkedin-auto-publish'); ?> 
		<span style="color: red;"><?php echo  (is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST']; ?></span>
		<br> <?php _e('Specify the authorized redirect url as :','linkedin-auto-publish'); ?> 
		<span style="color: red;"><?php echo  admin_url().'admin.php'; ?></span>
		<br> <?php $lnap_create_lnapp="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-create-linkedin-application/"; $lnap_inst_link=sprintf(__('For detailed step by step instructions <b><a href="%s" target="_blank"> Click here.','linkedin-auto-publish'),$lnap_create_lnapp); echo $lnap_inst_link; ?> </a></b>
		</div>
		</td>
		</tr>
	</table>
	<form method="post" >
<?php wp_nonce_field( 'xyz_lnap_ln_settings_nonce' );?>
	<div style="font-weight: bold;padding: 3px;"> <?php _e('All fields given below are mandatory','linkedin-auto-publish'); ?> </div> 
	<table class="widefat xyz_lnap_widefat_table" style="width: 99%">
	
	<tr valign="top"><td> <?php _e('Enable auto publish posts to my linkedin account','linkedin-auto-publish'); ?></td>
		<td  class="switch-field">
			<label id="xyz_lnap_lnpost_permission_yes"><input type="radio" name="xyz_lnap_lnpost_permission" value="1" <?php  if(get_option('xyz_lnap_lnpost_permission')==1) echo 'checked';?>/> <?php _e('Yes','linkedin-auto-publish'); ?> </label>
			<label id="xyz_lnap_lnpost_permission_no"><input type="radio" name="xyz_lnap_lnpost_permission" value="0" <?php  if(get_option('xyz_lnap_lnpost_permission')==0) echo 'checked';?>/> <?php _e('No','linkedin-auto-publish'); ?> </label>
		</td>
	</tr>	
	
	<tr valign="top"><td width="50%"> <?php $v2api="V2 API"; $v2api_usage=sprintf(__('%s usage','linkedin-auto-publish'),$v2api);echo $v2api_usage; ?> <span class="mandatory">*</span>
	</td>
	<td>
	<input type="radio" name="xyz_lnap_ln_api_permission" id="xyz_lnap_ln_api_permission_basic" value="0" <?php if (get_option('xyz_lnap_ln_api_permission')==0) echo 'checked';?>/>
	<span style="color: #a7a7a7;font-weight: bold;"> <?php _e('Own app-Basic profile fields only','linkedin-auto-publish'); ?> </span><br>
	<input type="radio" name="xyz_lnap_ln_api_permission" id="xyz_lnap_ln_api_permission_company" value="1" <?php if (get_option('xyz_lnap_ln_api_permission')==1) echo 'checked';?>/>
	<span style="color: #a7a7a7;font-weight: bold;"> <?php _e('Own app-Basic profile fields + company pages','linkedin-auto-publish'); ?> <br/><span style='padding-left: 25px;'> <?php _e('(requires app submission and LinkedIn review)','linkedin-auto-publish'); ?> </span></span><br/>
	<span style="padding-left: 25px;"><a href="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-create-linkedin-application/" target="_blank"> <?php _e('How can I create a Linkedin Application','linkedin-auto-publish'); ?>? </a></span><br/>
	<input type="radio" name="xyz_lnap_ln_api_permission" id="xyz_lnap_ln_api_permission_lnapsolutions" value="2" <?php if(get_option('xyz_lnap_ln_api_permission')==2) echo 'checked';?>>
	<span style="color: #000000;font-size: 13px;background-color: #f7a676;font-weight: 500;padding: 3px 5px;"><i class="fa fa-star-o" aria-hidden="true" style="margin-right:5px;"></i> <?php $lnap_smap_site="SMAPsolution.com's"; $lnap_ready_pub=sprintf(__('%s App ( ready to publish )','linkedin-auto-publish'),$lnap_smap_site); echo $lnap_ready_pub; ?> <i class="fa fa-star-o" aria-hidden="true" style="margin-right:5px;"></i></span><br/><span style="padding-left: 25px;"> 
				<?php _e('Starts from 10 USD per year','linkedin-auto-publish'); ?> </span><br>
	<?php if(get_option('xyz_lnap_smapsoln_userid')==0)
	{?>
	<span style="color: #ff5e00;padding-left: 27px;font-size: small;"> <b> <?php _e('30 DAYS FREE TRIAL AVAILABLE','linkedin-auto-publish'); ?> </b></span>
	<br/>
	<?php }?>
	
	</td></tr>
<tr valign="top" class="xyz_linkedin_settings">
	<td scope="row" colspan="1" width="50%"> <?php _e('LinkedIn Sign-In Method','linkedin-auto-publish'); ?> <img src="<?php echo $heimg?>" onmouseover="detdisplay_lnap('xyz_lnap_sign_in')" onmouseout="dethide_lnap('xyz_lnap_sign_in')" style="width:13px;height:auto;">
	<div id="xyz_lnap_sign_in" class="lnap_informationdiv" style="display: none;width: 400px;">
	<?php _e('Starting from August 1, 2023, "<b>Sign In with LinkedIn</b>" (SIWL) has been deprecated. For all new apps, we strongly recommend using "<b>Sign In with LinkedIn using OpenID Connect</b>" as the preferred Sign-In method.
<br/>Existing apps that are currently utilizing "Sign In with LinkedIn," can continue to do so. If you decide transition to "Sign In with LinkedIn using OpenID Connect," please ensure that you add this option under your LinkedIn Developer App"s product section.','linkedin-auto-publish'); ?> </b>
	</div>
	</td>
	<td>
		<input type="radio" name="xyz_lnap_ln_signin_method" value="1" <?php  if($xyz_lnap_ln_signin_method==1) echo 'checked';?>/> <?php _e('Sign In with LinkedIn','linkedin-auto-publish'); ?>
<br/>
		<input type="radio" name="xyz_lnap_ln_signin_method" value="0" <?php  if($xyz_lnap_ln_signin_method==0) echo 'checked';?>/> <?php _e('Sign In with LinkedIn using OpenID Connect','linkedin-auto-publish'); ?>
	</td>
</tr>
	<?php 
	if($xyzscripts_user_id =='' || $xyzscripts_hash_val=='' && get_option('xyz_lnap_ln_api_permission')==2)
	{  ?>
	<tr valign="top" id="xyz_lnap_conn_to_xyzscripts">
	<td width="50%">	</td>
	<td width="50%">
	<span id='ajax-save-xyzscript_acc' style="display:none;"><img	class="img"  title="Saving details"	src="<?php echo plugins_url('../images/ajax-loader.gif',__FILE__);?>" style="width:65px;height:70px; "></span>
	<span id="connect_to_xyzscripts"style="background-color: #1A87B9;color: white; padding: 4px 5px;
    text-align: center; text-decoration: none;   display: inline-block;border-radius: 4px;">
	<a href="javascript:lnap_popup_connect_to_xyzscripts();void(0);" style="color:white !important;"> <?php $lnap_var_xyz="xyzscripts"; $lnap_connect_xyz=sprintf(__('Connect your %s account','linkedin-auto-publish'),$lnap_var_xyz); echo $lnap_connect_xyz; ?> </a>
	</span>
	</td>
	</tr>
	<?php }?>
	<tr valign="top" class="xyz_linkedin_settings">
	<td width="50%"> <?php _e('Client ID','linkedin-auto-publish'); ?> </td>					
	<td>
		<input id="xyz_lnap_lnapikey" name="xyz_lnap_lnapikey" type="text" value="<?php if($lms1=="") {echo esc_html(get_option('xyz_lnap_lnapikey'));}?>"/>
	<a href="http://help.xyzscripts.com/docs/social-media-auto-publish/faq/how-can-i-create-linkedin-application/" target="_blank"> <?php _e('How can I create a Linkedin Application','linkedin-auto-publish'); ?>?</a>
	</td></tr>
	<tr valign="top"  class="xyz_linkedin_settings"><td> <?php _e('Client secret','linkedin-auto-publish'); ?> </td>
	<td>
		<input id="xyz_lnap_lnapisecret" name="xyz_lnap_lnapisecret" type="text" value="<?php if($lms2=="") { echo esc_html(get_option('xyz_lnap_lnapisecret')); }?>" />
	</td></tr>
	<tr valign="top">
		<td> <?php _e('Posting method','linkedin-auto-publish'); ?>
		</td>
		<td>
		<select id="xyz_lnap_lnpost_method" name="xyz_lnap_lnpost_method">
				<option value="1"
				<?php 
				if(get_option('xyz_lnap_lnpost_method')==1) echo 'selected';?>> <?php _e('Simple text message','linkedin-auto-publish');?> </option>
				<option value="2"
				<?php  if(get_option('xyz_lnap_lnpost_method')==2) echo 'selected';?>> <?php _e('Attach your blog post','linkedin-auto-publish'); ?> </option>
				<option value="3"
				<?php  if(get_option('xyz_lnap_lnpost_method')==3) echo 'selected';?>> <?php _e('Text message with image','linkedin-auto-publish'); ?> </option>
		</select>
		</td>
	</tr>
	<tr valign="top">
	<td> <?php _e('Enforce og tags for LinkedIn','linkedin-auto-publish'); ?> <img src="<?php echo $heimg?>" onmouseover="detdisplay_lnap('xyz_lnap_enforce_og')" onmouseout="dethide_lnap('xyz_lnap_enforce_og')" style="width:13px;height:auto;">
	<div id="xyz_lnap_enforce_og" class="lnap_informationdiv" style="display: none;width: 400px;">
	<?php _e('If you enable, Open Graph tags will be generated while posting to LinkedIn, when using the posting method,<b> Attach your blog post.','linkedin-auto-publish'); ?> </b>
	</div>
	</td>
	<td  class="switch-field">
		<label id="xyz_lnap_enforce_og_tags_yes" class="xyz_lnap_toggle_off"><input type="radio" name="xyz_lnap_enforce_og_tags" value="1" <?php  if(get_option('xyz_lnap_enforce_og_tags')==1) echo 'checked';?>/> <?php _e('Yes','linkedin-auto-publish'); ?> </label>
		<label id="xyz_lnap_enforce_og_tags_no" class="xyz_lnap_toggle_on"><input type="radio" name="xyz_lnap_enforce_og_tags" value="0" <?php  if(get_option('xyz_lnap_enforce_og_tags')==0) echo 'checked';?>/> <?php _e('No','linkedin-auto-publish'); ?> </label>
    </td>
	</tr> 
	<tr valign="top">
					<td> <?php _e('Message format for posting','linkedin-auto-publish'); ?> <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_lnap('xyz_ln')" onmouseout="dethide_lnap('xyz_ln')" style="width:13px;height:auto;">
						<div id="xyz_ln" class="lnap_informationdiv"
							style="display: none; font-weight: normal;">
							{POST_TITLE} - <?php _e('Insert the title of your post.','linkedin-auto-publish'); ?><br/>
							{PERMALINK} - <?php _e('Insert the URL where your post is displayed.','linkedin-auto-publish'); ?><br/>
							{POST_EXCERPT} - <?php _e('Insert the excerpt of your post.','linkedin-auto-publish'); ?><br/>
							{POST_CONTENT} - <?php _e('Insert the description of your post.','linkedin-auto-publish'); ?><br/>
							{BLOG_TITLE} - <?php _e('Insert the name of your blog.','linkedin-auto-publish'); ?><br/>
							{USER_NICENAME} - <?php _e('Insert the nicename of the author.','linkedin-auto-publish'); ?><br/>
							{POST_ID} - <?php _e('Insert the ID of your post.','linkedin-auto-publish'); ?><br/>
							{POST_PUBLISH_DATE} - <?php _e('Insert the publish date of your post.','linkedin-auto-publish'); ?><br/>
							{USER_DISPLAY_NAME} - <?php _e('Insert the display name of the author.','linkedin-auto-publish'); ?>
						</div><br/><span style="color: #0073aa;">[<?php _e('Optional','linkedin-auto-publish'); ?>]</span></td>
	<td>
	<select name="xyz_lnap_info" id="xyz_lnap_info" onchange="xyz_lnap_info_insert(this)">
		<option value ="0" selected="selected"> --<?php _e('Select','linkedin-auto-publish'); ?>-- </option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value= "9">{USER_DISPLAY_NAME}</option>
		</select> </td></tr><tr><td>&nbsp;</td><td>
		<textarea id="xyz_lnap_lnmessage"  name="xyz_lnap_lnmessage" style="height:80px !important;" ><?php echo esc_textarea(get_option('xyz_lnap_lnmessage'));?></textarea>
	</td></tr>
<?php if (get_option('xyz_lnap_ln_api_permission')==2 && $lnaf==0 ){
	//////////////////////////////////////////////
	?>
	<tr valign="top"><td> <?php _e('Share post to profile','linkedin-auto-publish'); ?> </td>
	<td  class="switch-field">
	<?php  if(get_option('xyz_lnap_lnshare_to_profile')==0){?>
		<label id="xyz_lnap_lnshare_to_profile_smap_yes" class="xyz_lnap_toggle_off"><input type="radio" name="xyz_lnap_lnshare_to_profile_smap" value="1" disabled/> <?php _e('Yes','linkedin-auto-publish'); ?> </label>
		<label id="xyz_lnap_lnshare_to_profile_lnap_no" class="xyz_lnap_toggle_on"><input type="radio" name="xyz_lnap_lnshare_to_profile_smap" value="0" checked/> <?php _e('No','linkedin-auto-publish'); ?> </label>
		<?php }
		elseif(get_option('xyz_lnap_lnshare_to_profile')==1){
			?>
		<label id="xyz_smap_lnshare_to_profile_smap_yes" class="xyz_lnap_toggle_on"><input type="radio" name="xyz_lnap_lnshare_to_profile_smap" value="1" checked/> <?php _e('Yes','linkedin-auto-publish'); ?> </label>
		<label id="xyz_lnap_lnshare_to_profile_smap_no" class="xyz_lnap_toggle_off"><input type="radio" name="xyz_lnap_lnshare_to_profile_smap" value="0" disabled/> <?php _e('No','linkedin-auto-publish'); ?> </label>
			<?php 
		}?>
		<span style="width: 10px;color: #ce5c19;font-size: 20px;">*</span>
		</td>
	</tr> 
			<?php 
	///////////////////////////////////////////////////
}
else {?>
	<tr valign="top">
	<td> <?php _e('Share post to profile','linkedin-auto-publish'); ?> </td>
	<td  class="switch-field">
		<label id="xyz_lnap_lnshare_to_profile_yes" ><input type="radio" name="xyz_lnap_lnshare_to_profile" value="1" <?php  if(get_option('xyz_lnap_lnshare_to_profile')==1) echo 'checked';?>/> <?php _e('Yes','linkedin-auto-publish'); ?> </label>
		<label id="xyz_lnap_lnshare_to_profile_no" ><input type="radio" name="xyz_lnap_lnshare_to_profile" value="0" <?php  if(get_option('xyz_lnap_lnshare_to_profile')==0) echo 'checked';?>/> <?php _e('No','linkedin-auto-publish'); ?> </label>
	</td>
	</tr>
	<?php }?>
	<tr valign="top" id="shareprivate">
	<!-- <input type="hidden" name="xyz_lnap_ln_sharingmethod" id="xyz_lnap_ln_sharingmethod" value="0"> -->
	<td> <?php _e('Share post content with','linkedin-auto-publish'); ?> </td>
	<td  class="switch-field">
		<label id="xyz_lnap_ln_shareprivate_yes" ><input type="radio" name="xyz_lnap_ln_shareprivate" value="1" <?php  if(get_option('xyz_lnap_ln_shareprivate')==1) echo 'checked';?>/> <?php _e('Connections','linkedin-auto-publish'); ?> </label>
		<label id="xyz_lnap_ln_shareprivate_no" ><input type="radio" name="xyz_lnap_ln_shareprivate" value="0" <?php  if(get_option('xyz_lnap_ln_shareprivate')==0) echo 'checked';?>/> <?php _e('Public','linkedin-auto-publish'); ?> </label>
	</td>
	</tr>
<!-- 	new////////////////////////// linkedin company pages///////////////////////////////// -->
		<?php if(get_option('xyz_lnap_lnaf')==0 && get_option('xyz_lnap_ln_api_permission')==1){?>
		<tr valign="top" id="share_post_company"><td> <?php _e('Select pages for auto publish','linkedin-auto-publish'); ?> </td>
		<td>
			<?php 
			$ln_acc_tok_arr='';
			$xyz_lnap_application_lnarray=get_option('xyz_lnap_application_lnarray');
			if ($xyz_lnap_application_lnarray!='')
			$ln_acc_tok_arr=json_decode($xyz_lnap_application_lnarray);
			//if ($ln_acc_tok_arr)
			//$xyz_lnap_application_lnarray=$ln_acc_tok_arr->access_token;
			$ln_publish_status=array();
			$xyz_lnap_ln_company_idArray=explode(',',$xyz_lnap_ln_company_ids);
			?><div class="xyz_lnap_scroll_checkbox" style="width:220px !important;" >
				<?php if(isset($ln_acc_tok_arr->access_token))
				{
				$ln_err_flag=0;
				$url="https://api.linkedin.com/v2/organizationalEntityAcls?q=roleAssignee&role=ADMINISTRATOR&projection=(elements*(*,roleAssignee~(localizedFirstName,%20localizedLastName),%20organizationalTarget~(localizedName)))&oauth2_access_token=".$ln_acc_tok_arr->access_token;
				$ar=wp_remote_get($url);
				if (is_object( $ar ) &&  is_a( $ar, 'wp_Error' ))
						_e('Failed to fetch company details.','linkedin-auto-publish');
				elseif (is_array($ar))
				{
					$ar=json_decode($ar['body'],true);
							if (isset($ar['elements'])){
					$ar=$ar['elements'];//print_r($xyz_lnap_ln_company_idArray);
					foreach ($ar as $ark)
					{ 
						if (strpos($ark['organizationalTarget'], 'urn:li:organizationBrand') !== false)
							$comp_id=str_replace('urn:li:organizationBrand:', '',$ark['organizationalTarget']);
						else
						$comp_id=str_replace('urn:li:organization:', '',$ark['organizationalTarget']);
						?>
					<input type="checkbox" name="xyz_lnap_ln_share_post_company[]"  value="<?php echo $comp_id."-".$ark['organizationalTarget~']['localizedName']; ?>" <?php if(in_array($comp_id, $xyz_lnap_ln_company_idArray)) echo "checked" ?>><?php echo $ark['organizationalTarget~']['localizedName']; ?><br/>
   				 <?php } }
   				 else $ln_err_flag=1;
   				 if ($ln_err_flag==1){
   				     _e('No companies found.','linkedin-auto-publish');
   				 	if (isset($ar['body']))print_r($ar['body']);}
   				 }
					}
					else {_e('No companies found.','linkedin-auto-publish');}
				?></div>
		</td>
	</tr>
	<?php }elseif (get_option('xyz_lnap_ln_api_permission')==2 && get_option('xyz_lnap_lnaf')==0 ){
			/////////////////////////////////////////////////////////////////////////////////////////
			$xyz_lnap_ln_company_names=get_option('xyz_lnap_page_names');
			$xyz_lnap_ln_company_names=unserialize(base64_decode($xyz_lnap_ln_company_names));
			?>
			<tr valign="top" id="share_post_company"><td> <?php _e('Share post to company page','linkedin-auto-publish');?> </td>
			<td><div>
				<div class="xyz_lnap_scroll_checkbox" id="xyz_lnap_selected_pages_ln_tr" style="float:left;"><?php
				if (!empty($xyz_lnap_ln_company_names)){
					foreach ($xyz_lnap_ln_company_names as $xyz_ln_company_id => $xyz_ln_company_name)
					   {?>
   				<input type="checkbox" name="xyz_lnap_ln_share_post_company[]" id="xyz_lnap_selected_pages_ln"  value="<?php echo $xyz_ln_company_id."-".$xyz_ln_company_name; ?>" <?php echo "checked" ?> disabled><?php echo $xyz_ln_company_name; ?><br/>
   				       <?php 	$ln_company_name[$xyz_ln_company_id]=$xyz_ln_company_name;
					   } 
				}
				else {_e('No companies found.','linkedin-auto-publish');}
				?>
                 </div>
                 <div style="float: left;width: 10px;color: #ce5c19;font-size: 20px;">*</div>
                 </div>
				</td>
			</tr> 
		<?php }?>
<!-- 	new////////////////////////// linkedin company pages///////////////////////////////// -->	
	
		<tr>
			<td   id="bottomBorderNone"></td>
					<td   id="bottomBorderNone"><div style="height: 50px;">
							<input type="submit" class="submit_lnap_new"
								style=" margin-top: 10px; "
								name="linkdn" value="<?php _e('Save','linkedin-auto-publish'); ?>" /></div>
					</td>
				</tr>
				<?php if(get_option('xyz_lnap_smapsoln_userid')==0){?>
				<tr><td style='color: #ce5c19;padding-left:0px;'>*<?php _e('Free trial is available only for first time users','linkedin-auto-publish')?> </td></tr>
				<?php }
				else{?>
				<tr><td style='color: #ce5c19;padding-left:0px;'>*<?php _e('Use reauthorize button to change selected values','linkedin-auto-publish')?> </td></tr>
				<?php }?>
</table>
</form></div>
	<?php 
	if(isset($_POST['bsettngs']))
	{
		if (! isset( $_REQUEST['_wpnonce'] )
				|| ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'xyz_lnap_basic_settings_nonce' )
				) {
		
					wp_nonce_ays( 'xyz_lnap_basic_settings_nonce' );
		
					exit();
		
				}
		$xyz_lnap_include_pages=intval($_POST['xyz_lnap_include_pages']);
// 		$xyz_lnap_utf_decode_enable=intval($_POST['xyz_lnap_utf_decode_enable']);
		$xyz_lnap_include_posts=intval($_POST['xyz_lnap_include_posts']);
		$lnap_category_ids='';
		if($_POST['xyz_lnap_cat_all']=="All")
			$lnap_category_ids=$_POST['xyz_lnap_cat_all'];
			else if(isset($_POST['xyz_lnap_catlist']) && !empty($_POST['xyz_lnap_catlist']))
		{
			$lnap_category_ids=$_POST['xyz_lnap_catlist'];
			$lnap_category_ids=implode(',', $lnap_category_ids);
		}
		$xyz_customtypes="";
		
        if(isset($_POST['post_types']))
		$xyz_customtypes=$_POST['post_types'];
        
        $xyz_lnap_peer_verification=intval($_POST['xyz_lnap_peer_verification']);
        $xyz_lnap_premium_version_ads=intval($_POST['xyz_lnap_premium_version_ads']);
        $xyz_lnap_default_selection_edit=intval($_POST['xyz_lnap_default_selection_edit']);
        $xyz_lnap_default_selection_create=intval($_POST['xyz_lnap_default_selection_create']);
        
        //$xyz_lnap_future_to_publish=$_POST['xyz_lnap_future_to_publish'];
        $lnap_customtype_ids="";
        
        $xyz_lnap_applyfilters="";
        if(isset($_POST['xyz_lnap_applyfilters']))
        	$xyz_lnap_applyfilters=$_POST['xyz_lnap_applyfilters'];
        
        
        
        
		$lnap_customtype_ids="";

		if($xyz_customtypes!="")
		{
			for($i=0;$i<count($xyz_customtypes);$i++)
			{
				$lnap_customtype_ids.=$xyz_customtypes[$i].",";
			}

		}
		$lnap_customtype_ids=rtrim($lnap_customtype_ids,',');

		
		$xyz_lnap_applyfilters_val="";
		if($xyz_lnap_applyfilters!="")
		{
			for($i=0;$i<count($xyz_lnap_applyfilters);$i++)
			{
				$xyz_lnap_applyfilters_val.=$xyz_lnap_applyfilters[$i].",";
			}
		}
		$xyz_lnap_applyfilters_val=rtrim($xyz_lnap_applyfilters_val,',');
		
		update_option('xyz_lnap_apply_filters',$xyz_lnap_applyfilters_val);
		update_option('xyz_lnap_include_pages',$xyz_lnap_include_pages);
		
		update_option('xyz_lnap_include_posts',$xyz_lnap_include_posts);
		if($xyz_lnap_include_posts==0)
			update_option('xyz_lnap_include_categories',"All");
		else
			update_option('xyz_lnap_include_categories',$lnap_category_ids);
		update_option('xyz_lnap_include_customposttypes',$lnap_customtype_ids);
		update_option('xyz_lnap_peer_verification',$xyz_lnap_peer_verification);
		update_option('xyz_lnap_premium_version_ads',$xyz_lnap_premium_version_ads);
		update_option('xyz_lnap_default_selection_edit',$xyz_lnap_default_selection_edit);
		update_option('xyz_lnap_default_selection_create',$xyz_lnap_default_selection_create);
// 		update_option('xyz_lnap_utf_decode_enable',$xyz_lnap_utf_decode_enable);
		//update_option('xyz_lnap_future_to_publish',$xyz_lnap_future_to_publish);
	}

	//$xyz_lnap_future_to_publish=get_option('xyz_lnap_future_to_publish');
	$xyz_credit_link=get_option('xyz_credit_link');
	$xyz_lnap_include_pages=get_option('xyz_lnap_include_pages');
	$xyz_lnap_include_posts=get_option('xyz_lnap_include_posts');
	$xyz_lnap_include_categories=get_option('xyz_lnap_include_categories');
	/*if ($xyz_lnap_include_categories!='All')
	$xyz_lnap_include_categories=explode(',', $xyz_lnap_include_categories);*/
	$xyz_lnap_include_customposttypes=get_option('xyz_lnap_include_customposttypes');
	$xyz_lnap_apply_filters=get_option('xyz_lnap_apply_filters');
	$xyz_lnap_peer_verification=get_option('xyz_lnap_peer_verification');
	$xyz_lnap_premium_version_ads=get_option('xyz_lnap_premium_version_ads');
	$xyz_lnap_default_selection_edit=get_option('xyz_lnap_default_selection_edit');
	$xyz_lnap_default_selection_create=get_option('xyz_lnap_default_selection_create');
	//$xyz_lnap_utf_decode_enable=get_option('xyz_lnap_utf_decode_enable');
	?>
	<div id="xyz_lnap_basic_settings" class="xyz_lnap_tabcontent">
		<form method="post">
<?php wp_nonce_field( 'xyz_lnap_basic_settings_nonce' );?>
			<table class="widefat xyz_lnap_widefat_table" style="width: 99%">
				<tr><td><h2> <?php _e('Basic Settings','linkedin-auto-publish'); ?> </h2></td></tr>
				<tr valign="top">

					<td  colspan="1" width="50%"> <?php _e('Publish wordpress `pages` to linkedin','linkedin-auto-publish'); ?>
					</td>
					<td  class="switch-field">
						<label id="xyz_lnap_include_pages_yes"><input type="radio" name="xyz_lnap_include_pages" value="1" <?php  if($xyz_lnap_include_pages==1) echo 'checked';?>/> <?php _e('Yes','linkedin-auto-publish');?> </label>
						<label id="xyz_lnap_include_pages_no"><input type="radio" name="xyz_lnap_include_pages" value="0" <?php  if($xyz_lnap_include_pages==0) echo 'checked';?>/> <?php _e('No','linkedin-auto-publish');?> </label>
					</td>
				</tr>

				<tr valign="top">

					<td  colspan="1"> <?php _e('Publish wordpress `posts` to linkedin','linkedin-auto-publish'); ?>
					</td>
					<td  class="switch-field">
						<label id="xyz_lnap_include_posts_yes"><input type="radio" name="xyz_lnap_include_posts" value="1" <?php  if($xyz_lnap_include_posts==1) echo 'checked';?>/> <?php _e('Yes','linkedin-auto-publish');?></label>
						<label id="xyz_lnap_include_posts_no"><input type="radio" name="xyz_lnap_include_posts" value="0" <?php  if($xyz_lnap_include_posts==0) echo 'checked';?>/> <?php _e('No','linkedin-auto-publish');?> </label>
					</td>
				</tr>
				
				
				<?php 
				$xyz_lnap_hide_custompost_settings='';
					$args=array(
							'public'   => true,
							'_builtin' => false
					);
					$output = 'names'; // names or objects, note names is the default
					$operator = 'and'; // 'and' or 'or'
					$post_types=get_post_types($args,$output,$operator);

					$ar1=explode(",",$xyz_lnap_include_customposttypes);
					$cnt=count($post_types);
					if($cnt==0)
					$xyz_lnap_hide_custompost_settings = 'style="display: none;"';//echo 'NA';
					?>
				<tr valign="top" <?php echo $xyz_lnap_hide_custompost_settings;?>>

					<td  colspan="1"> <?php _e('Select wordpress custom post types for auto publish','linkedin-auto-publish'); ?> </td>
					<td><?php 
					foreach ($post_types  as $post_type ) {
					
						echo '<input type="checkbox" name="post_types[]" value="'.$post_type.'" ';
						if(in_array($post_type, $ar1))
						{
							echo 'checked="checked"/>';
						}
						else
							echo '/>';
					
							echo $post_type.'<br/>';
					
					}
					?>
					</td>
				</tr>
				<tr><td><h2> <?php _e('Advanced Settings','linkedin-auto-publish'); ?> </h2></td></tr>
				<tr valign="top" id="selPostCat">

					<td  colspan="1"> <?php _e('Select post categories for auto publish','linkedin-auto-publish'); ?>
					</td>
					<td class="switch-field">
	                <input type="hidden" value="<?php echo esc_html($xyz_lnap_include_categories);?>" name="xyz_lnap_sel_cat" 
			id="xyz_lnap_sel_cat"> 
					<label id="xyz_lnap_include_categories_no">
					<input type="radio"	name="xyz_lnap_cat_all" id="xyz_lnap_cat_all" value="All" onchange="rd_cat_chn(1,-1)" <?php if($xyz_lnap_include_categories=="All") echo "checked"?>> <?php _e('All','linkedin-auto-publish'); ?> <font style="padding-left: 10px;"></font></label>
					<label id="xyz_lnap_include_categories_yes">
					<input type="radio"	name="xyz_lnap_cat_all" id="xyz_lnap_cat_all" value=""	onchange="rd_cat_chn(1,1)" <?php if($xyz_lnap_include_categories!="All") echo "checked"?>> <?php _e('Specific','linkedin-auto-publish'); ?> </label>
					<br /> <br /> <div class="xyz_lnap_scroll_checkbox"  id="cat_dropdown_span">
					<?php 
					$args = array(
							'show_option_all'    => '',
							'show_option_none'   => '',
							'orderby'            => 'name',
							'order'              => 'ASC',
							'show_last_update'   => 0,
							'show_count'         => 0,
							'hide_empty'         => 0,
							'child_of'           => 0,
							'exclude'            => '',
							'echo'               => 0,
							'selected'           => '1 3',
							'hierarchical'       => 1,
							'id'                 => 'xyz_lnap_catlist',
							'class'              => 'postform',
							'depth'              => 0,
							'tab_index'          => 0,
							'taxonomy'           => 'category');

					if(count(get_categories($args))>0)
					{
					    $xyz_lnap_include_categories=explode(',', $xyz_lnap_include_categories);
						$lnap_categories=get_categories($args);
						foreach ($lnap_categories as $lnap_cat)
						{
							$cat_id[]=$lnap_cat->cat_ID;
							$cat_name[]=$lnap_cat->cat_name;
							?>
							<input type="checkbox" name="xyz_lnap_catlist[]"  value="<?php  echo $lnap_cat->cat_ID;?>" <?php if(is_array($xyz_lnap_include_categories)) if(in_array($lnap_cat->cat_ID, $xyz_lnap_include_categories)) echo "checked"; ?>/><?php echo $lnap_cat->cat_name; ?>
							<br/><?php }
					}
					else
						_e('NIL','linkedin-auto-publish');
					?><br /> <br /> </div>
					</td>
				</tr>
				<tr valign="top">

					<td scope="row" colspan="1" width="50%"> <?php _e('Auto publish on editing posts/pages/custom post types','linkedin-auto-publish'); ?>
					</td>
					<td>
						<input type="radio" name="xyz_lnap_default_selection_edit" value="1" <?php  if($xyz_lnap_default_selection_edit==1) echo 'checked';?>/> <?php _e('Enabled','linkedin-auto-publish'); ?>
						<br/><input type="radio" name="xyz_lnap_default_selection_edit" value="0" <?php  if($xyz_lnap_default_selection_edit==0) echo 'checked';?>/> <?php _e('Disabled','linkedin-auto-publish'); ?>
						<br/><input type="radio" name="xyz_lnap_default_selection_edit" value="2" <?php  if($xyz_lnap_default_selection_edit==2) echo 'checked';?>/> <?php _e('Use settings from post creation or post updation','linkedin-auto-publish'); ?>
					</td>
				</tr>
				<tr valign="top">
					<td scope="row" colspan="1" width="50%"> <?php _e('Auto publish on creating posts/pages/custom post types','linkedin-auto-publish'); ?>
					</td>
					<td>
						<input type="radio" name="xyz_lnap_default_selection_create" value="1" <?php  if($xyz_lnap_default_selection_create==1) echo 'checked';?>/> <?php _e('Enabled','linkedin-auto-publish'); ?>
						<br/><input type="radio" name="xyz_lnap_default_selection_create" value="0" <?php  if($xyz_lnap_default_selection_create==0) echo 'checked';?>/> <?php _e('Disabled','linkedin-auto-publish'); ?>
					</td>
				</tr>

				<tr valign="top">
				
				<td scope="row" colspan="1" width="50%"> <?php _e('Enable SSL peer verification in remote requests','linkedin-auto-publish');?> </td>
				<td  class="switch-field">
					<label id="xyz_lnap_peer_verification_yes"><input type="radio" name="xyz_lnap_peer_verification" value="1" <?php  if($xyz_lnap_peer_verification==1) echo 'checked';?>/> <?php _e('Yes','linkedin-auto-publish'); ?> </label>
					<label id="xyz_lnap_peer_verification_no"><input type="radio" name="xyz_lnap_peer_verification" value="0" <?php  if($xyz_lnap_peer_verification==0) echo 'checked';?>/> <?php _e('No','linkedin-auto-publish'); ?> </label>
				</td>
				</tr>
				
					<tr valign="top">
					<td scope="row" colspan="1"> <?php _e('Apply filters during publishing','linkedin-auto-publish');?>	</td>
					<td>
					<?php 
					$ar2=explode(",",$xyz_lnap_apply_filters);
					for ($i=0;$i<3;$i++ ) {
						$filVal=$i+1;
						
						if($filVal==1)
							$filName='the_content';
						else if($filVal==2)
							$filName='the_excerpt';
						else if($filVal==3)
							$filName='the_title';
						else $filName='';
						
						echo '<input type="checkbox" name="xyz_lnap_applyfilters[]"  value="'.$filVal.'" ';
						if(in_array($filVal, $ar2))
						{
							echo 'checked="checked"/>';
						}
						else
							echo '/>';
					
						echo '<label>'.$filName.'</label><br/>';
					
					}
					
					?>
					</td>
				</tr>
			<!--  	<tr valign="top">

					<td  colspan="1" width="50%">Enable utf-8 decoding before publishing
					</td>
					<td  class="switch-field">
						<label id="xyz_lnap_utf_decode_enable_yes"><input type="radio" name="xyz_lnap_utf_decode_enable" value="1" <?php // if($xyz_lnap_utf_decode_enable==1) echo 'checked';?>/>Yes</label>
						<label id="xyz_lnap_utf_decode_enable_no"><input type="radio" name="xyz_lnap_utf_decode_enable" value="0" <?php // if($xyz_lnap_utf_decode_enable==0) echo 'checked';?>/>No</label>
					</td>
				</tr> -->
	<tr><td><h2> <?php _e('Other Settings','linkedin-auto-publish');?> </h2></td></tr>
				<tr valign="top">

					<td  colspan="1"> <?php _e('Enable credit link to author','linkedin-auto-publish');?>
					</td>
					<td  class="switch-field">
						<label id="xyz_credit_link_yes"><input type="radio" name="xyz_credit_link" value="lnap" <?php  if($xyz_credit_link=='lnap') echo 'checked';?>/> <?php _e('Yes','linkedin-auto-publish'); ?> </label>
						<label id="xyz_credit_link_no"><input type="radio" name="xyz_credit_link" value="<?php echo $xyz_credit_link!='lnap'?$xyz_credit_link:0;?>" <?php  if($xyz_credit_link!='lnap') echo 'checked';?>/> <?php _e('No','linkedin-auto-publish'); ?> </label>
					</td>
				</tr>
				
				<tr valign="top">

					<td  colspan="1"> <?php _e('Enable premium version ads','linkedin-auto-publish'); ?>
					</td>
					<td  class="switch-field">
						<label id="xyz_lnap_premium_version_ads_yes"><input type="radio" name="xyz_lnap_premium_version_ads" value="1" <?php  if($xyz_lnap_premium_version_ads==1) echo 'checked';?>/> <?php _e('Yes','linkedin-auto-publish'); ?> </label>
						<label id="xyz_lnap_premium_version_ads_no"><input type="radio" name="xyz_lnap_premium_version_ads" value="0" <?php  if($xyz_lnap_premium_version_ads==0) echo 'checked';?>/> <?php _e('No','linkedin-auto-publish'); ?> </label>
					</td>
				</tr>
				<tr>
					<td id="bottomBorderNone">
					</td>
					
<td id="bottomBorderNone"><div style="height: 50px;">
<input type="submit" class="submit_lnap_new" style="margin-top: 10px;"	value="<?php _e('Update Settings','linkedin-auto-publish'); ?>" name="bsettngs" /></div></td>
				</tr>
			</table>
		</form></div>
</div>		
<?php if (is_array($xyz_lnap_include_categories))
$xyz_lnap_include_categories1=implode(',', $xyz_lnap_include_categories);
else 
	$xyz_lnap_include_categories1=$xyz_lnap_include_categories;
	?>
	<script type="text/javascript">
	//drpdisplay(); 
var catval='<?php echo esc_html($xyz_lnap_include_categories1); ?>';
var custtypeval='<?php echo esc_html($xyz_lnap_include_customposttypes); ?>';
var get_opt_cats='<?php echo esc_html(get_option('xyz_lnap_include_posts'));?>';
jQuery(document).ready(function() {
	jQuery('.xyz_lnap_hide_ln_authErr').click(function() {
		 var base = '<?php echo admin_url('admin.php?page=linkedin-auto-publish-settings');?>';
		  window.location.href = base;
	});
	<?php 
	 if(isset($_POST['bsettngs'])) {?>
			document.getElementById("xyz_lnap_basic_tab_settings").click();	
			<?php }
			else {?>
			document.getElementById("xyz_lnap_default_tab_settings").click();
			<?php }?>

	  if(catval=="All")
		  jQuery("#cat_dropdown_span").hide();
	  else
		  jQuery("#cat_dropdown_span").show();

	  if(get_opt_cats==0)
		  jQuery('#selPostCat').hide();
	  else
		  jQuery('#selPostCat').show();
   var xyz_credit_link=jQuery("input[name='xyz_credit_link']:checked").val();
   if(xyz_credit_link=='lnap')
	   xyz_credit_link=1;
   else
	   xyz_credit_link=0;
   XyzLnapToggleRadio(xyz_credit_link,'xyz_credit_link');
   
   var xyz_lnap_cat_all=jQuery("input[name='xyz_lnap_cat_all']:checked").val();
   if (xyz_lnap_cat_all == 'All') 
	   xyz_lnap_cat_all=0;
   else 
	   xyz_lnap_cat_all=1;
   XyzLnapToggleRadio(xyz_lnap_cat_all,'xyz_lnap_include_categories'); 
  

   var toggle_element_ids=['xyz_lnap_ln_shareprivate','xyz_lnap_lnpost_permission','xyz_lnap_include_pages','xyz_lnap_include_posts','xyz_lnap_peer_verification','xyz_lnap_premium_version_ads','xyz_lnap_lnshare_to_profile','xyz_lnap_enforce_og_tags'];

   jQuery.each(toggle_element_ids, function( index, value ) {
		   checkedval= jQuery("input[name='"+value+"']:checked").val();
		   XyzLnapToggleRadio(checkedval,value); 
		   if(value=='xyz_lnap_lnshare_to_profile')
				xyz_lnap_show_visibility(checkedval);
   	});
   var xyz_lnap_app_sel_mode=jQuery("input[name='xyz_lnap_ln_api_permission']:checked").val();
   if(xyz_lnap_app_sel_mode ==2){
		jQuery('.xyz_linkedin_settings').hide();
		jQuery('#xyz_linkedin_settings_note').hide();
		jQuery('#xyz_lnap_conn_to_xyzscripts').show();
   }
   else{
	   	jQuery('.xyz_linkedin_settings').show();
		jQuery('#xyz_linkedin_settings_note').show();
		jQuery('#xyz_lnap_conn_to_xyzscripts').hide();
   }
   jQuery("input[name='xyz_lnap_ln_api_permission']").click(function(){
	   var xyz_lnap_app_sel_mode=jQuery("input[name='xyz_lnap_ln_api_permission']:checked").val();
	   if(xyz_lnap_app_sel_mode ==2){
			jQuery('.xyz_linkedin_settings').hide();
			jQuery('#xyz_linkedin_settings_note').hide();
			jQuery('#xyz_lnap_conn_to_xyzscripts').show();
	  		}
		   else{
		   	jQuery('.xyz_linkedin_settings').show();
		   	jQuery('#xyz_linkedin_settings_note').show();
		    jQuery('#xyz_lnap_conn_to_xyzscripts').hide();
		   }
	   });
   window.addEventListener('message', function(e) {
	   xyz_lnap_ProcessChildMessage_2(e.data);
	} , false);
   var xyz_lnap_lnshare_to_profile='<?php echo get_option('xyz_lnap_lnshare_to_profile'); ?>';
		xyz_lnap_show_visibility(xyz_lnap_lnshare_to_profile);
	}); 
	
function setcat(obj)
{
var sel_str="";
for(k=0;k<obj.options.length;k++)
{
if(obj.options[k].selected)
sel_str+=obj.options[k].value+",";
}


var l = sel_str.length; 
var lastChar = sel_str.substring(l-1, l); 
if (lastChar == ",") { 
	sel_str = sel_str.substring(0, l-1);
}

document.getElementById('xyz_lnap_sel_cat').value=sel_str;

}

function rd_cat_chn(val,act)
{//xyz_lnap_cat_all xyz_lnap_cust_all 
	if(val==1)
	{
		if(act==-1)
		  jQuery("#cat_dropdown_span").hide();
		else
		  jQuery("#cat_dropdown_span").show();
	}
}

function xyz_lnap_info_insert(inf){
	
    var e = document.getElementById("xyz_lnap_info");
    var ins_opt = e.options[e.selectedIndex].text;
    if(ins_opt=="0")
    	ins_opt="";
    var str=jQuery("textarea#xyz_lnap_lnmessage").val()+ins_opt;
    jQuery("textarea#xyz_lnap_lnmessage").val(str);
    jQuery('#xyz_lnap_info :eq(0)').prop('selected', true);
    jQuery("textarea#xyz_lnap_lnmessage").focus();

}
function xyz_lnap_show_postCategory(val)
{
	if(val==0)
		jQuery('#selPostCat').hide();
	else
		jQuery('#selPostCat').show();
}
function xyz_lnap_show_visibility(val)
{
	if(val==0)
		jQuery('#shareprivate').hide();
	else
		jQuery('#shareprivate').show();
}
var toggle_element_ids=['xyz_lnap_ln_shareprivate','xyz_lnap_lnpost_permission','xyz_lnap_include_pages','xyz_lnap_include_posts','xyz_lnap_peer_verification','xyz_credit_link','xyz_lnap_premium_version_ads','xyz_lnap_include_categories','xyz_lnap_lnshare_to_profile','xyz_lnap_enforce_og_tags'];

jQuery.each(toggle_element_ids, function( index, value ) {
	jQuery("#"+value+"_no").click(function(){
		XyzLnapToggleRadio(0,value);
		if(value=='xyz_lnap_include_posts')
			xyz_lnap_show_postCategory(0);
		if(value=='xyz_lnap_lnshare_to_profile')
			xyz_lnap_show_visibility(0);
	});
	jQuery("#"+value+"_yes").click(function(){
		XyzLnapToggleRadio(1,value);
		if(value=='xyz_lnap_include_posts')
			xyz_lnap_show_postCategory(1);
		if(value=='xyz_lnap_lnshare_to_profile')
			xyz_lnap_show_visibility(1);
	});
	});
function xyz_lnap_open_tab(evt, xyz_lnap_form_div_id) {
    var i, xyz_lnap_tabcontent, xyz_lnap_tablinks;
    tabcontent = document.getElementsByClassName("xyz_lnap_tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("xyz_lnap_tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(xyz_lnap_form_div_id).style.display = "block";
    evt.currentTarget.className += " active";
}
/////////////////////////////////////////newly added scripts///////////////////////
function lnap_popup_ln_auth(domain_name,xyz_lnap_smapsoln_userid,xyzscripts_user_id,xyzscripts_hash_val,auth_secret_key,xyz_request_hash)
{
	if(xyzscripts_user_id==''|| xyzscripts_hash_val==''){
		if(jQuery('#system_notice_area').length==0)
			jQuery('body').append('<div class="system_notice_area_style0" id="system_notice_area"></div>');
			jQuery("#system_notice_area").html(xyz_script_lnap_var.html3);
	    	jQuery("#system_notice_area").append('<span id="system_notice_area_dismiss"> <?php _e('Dismiss','linkedin-auto-publish'); ?> </span>');
			jQuery("#system_notice_area").show();
			jQuery('#system_notice_area_dismiss').click(function() {
				jQuery('#system_notice_area').animate({
					opacity : 'hide',
					height : 'hide'
				}, 500);
			});
			return false;
	}
	else{
	var childWindow = null;
	var lnap_licence_key='';
	var account_id=0;
	var smap_solution_url='<?php echo XYZ_SMAP_SOLUTION_AUTH_URL;?>';
	childWindow = window.open(smap_solution_url+"authorize_linkedIn/linkedin.php?smap_ln_auth_id="+xyz_lnap_smapsoln_userid+"&account_id="+account_id+
			"&domain_name="+domain_name+"&xyzscripts_user_id="+xyzscripts_user_id+"&smap_licence_key="+lnap_licence_key+"&auth_secret_key="+auth_secret_key+"&free_plugin_source=lnap&request_hash="+xyz_request_hash, "SmapSolutions Authorization", "toolbar=yes,scrollbars=yes,resizable=yes,left=500,width=600,height=600");
	return false;	}
}
function lnap_popup_connect_to_xyzscripts()
{
	var childWindow = null;
	var smap_xyzscripts_url='<?php echo "https://smap.xyzscripts.com/index.php?page=index/register";?>';
	childWindow = window.open(smap_xyzscripts_url, "Connect to xyzscripts", "toolbar=yes,scrollbars=yes,resizable=yes,left=500,width=600,height=600");
	return false;	
}
function xyz_lnap_ProcessChildMessage_2(message) {
	var messageType = message.slice(0,5);
	if(messageType==="error")
	{
		message=message.substring(6);
		if(jQuery('#system_notice_area').length==0)
		jQuery('body').append('<div class="system_notice_area_style0" id="system_notice_area"></div>');
		jQuery("#system_notice_area").html(message+' <span id="system_notice_area_dismiss"> <?php _e('Dismiss','linkedin-auto-publish'); ?> </span>');
		jQuery("#system_notice_area").show();
		jQuery('#system_notice_area_dismiss').click(function() {
			jQuery('#system_notice_area').animate({
				opacity : 'hide',
				height : 'hide'
			}, 500);
		});
	}
	var obj1=jQuery.parseJSON(message);
	if(obj1.content &&  obj1.userid && obj1.xyzscripts_user)
	{
		var xyz_userid=obj1.userid;var xyz_user_hash=obj1.content;
		var xyz_lnap_xyzscripts_accinfo_nonce= '<?php echo wp_create_nonce('xyz_lnap_xyzscripts_accinfo_nonce');?>';
		var dataString = { 
				action: 'xyz_lnap_xyzscripts_accinfo_auto_update', 
				xyz_userid: xyz_userid ,
				xyz_user_hash: xyz_user_hash,
				dataType: 'json',
				_wpnonce: xyz_lnap_xyzscripts_accinfo_nonce
			};
		jQuery("#connect_to_xyzscripts").hide();
		jQuery("#ajax-save-xyzscript_acc").show();
		jQuery.post(ajaxurl, dataString ,function(response) {
			 if(response==1)
			        alert(xyz_script_lnap_var.alert3);
			else{	
 		  var base_url = '<?php echo admin_url('admin.php?page=linkedin-auto-publish-settings');?>';//msg - 
  		 window.location.href = base_url+'&msg=5';
		}
		});
	}
	else if(obj1.ln_pages && obj1.smapsoln_userid)
	{
	var obj=obj1.ln_pages;
	var secretkey=obj1.secretkey;
	var xyz_ln_user_id=obj1.xyz_ln_user_id;
	var smapsoln_userid=obj1.smapsoln_userid;
	var list='';
	for (var key in obj) {
	  if (obj.hasOwnProperty(key)) {
	    var val = obj[key];
	    list=list+"<input type='checkbox' value='"+key+"' checked='checked' disabled>"+val+"<br>";
	  }
	}
	jQuery("#xyz_lnap_page_names").val(JSON.stringify(obj));
	jQuery("#xyz_lnap_selected_pages").html(list);
	jQuery("#xyz_lnap_selected_pages_ln_tr").show();
	jQuery("#auth_message").hide();
	jQuery("#re_auth_message").show();
	var xyz_lnap_selected_pages_nonce = '<?php echo wp_create_nonce('xyz_lnap_selected_pages_nonce');?>';
	//var pages_obj = JSON.stringify(obj);
	var dataString = { 
			action: 'xyz_lnap_selected_pages_auto_update', 
			pages: obj ,
			smap_secretkey: secretkey,
			xyz_ln_user_id: xyz_ln_user_id,
			smapsoln_userid:smapsoln_userid,
			dataType: 'json',
			_wpnonce: xyz_lnap_selected_pages_nonce
		};			
		jQuery("#re_auth_message").hide();
		jQuery("#auth_message").hide();
		jQuery("#ajax-save").show();
		jQuery.post(ajaxurl, dataString ,function(response) {
			 if(response==1)
			       	 alert(xyz_script_lnap_var.alert3);
			else{
		  var base_url = '<?php echo admin_url('admin.php?page=linkedin-auto-publish-settings');?>';//msg - 
		window.location.href = base_url+'&msg=4';
		}
		});
	}
}
/////////////////////////////////////////newly added scripts///////////////////////
</script>
	<?php 
?>