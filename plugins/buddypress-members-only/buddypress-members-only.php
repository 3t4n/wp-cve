<?php
/*
Plugin Name: BuddyPress Members only
Plugin URI: https://membersonly.top/features/
Description: Only registered users can view your site, non members can only see a login/home page with no registration options. More amazing features? Login and Logout auto redirect to Referer page or Certain page based on user roles,  Restricts your BP standard Components and customized  Components to users,enable page level members only protect, only protect your buddypress and open all other WP area to users... etc, Get <a href='https://membersonly.top' target='blank'>BP Members Only Pro</a> now.  
Version: 3.3.5
Author: Tomas: <a href='https://membersonly.top/features/' target='_blank'>Features</a> | <a href='https://membersonly.top/forums' target='_blank'>Support Forum</a>
Author URI: https://membersonly.top/features/
Text Domain: bp-members-only
License: GPLv3 or later
Copyright 2015 - 2022  Tomas Zhu  (https://membersonly.top)
This program comes with ABSOLUTELY NO WARRANTY;
 https://www.gnu.org/licenses/gpl-3.0.html
 https://www.gnu.org/licenses/quick-guide-gplv3.html
*/
if (!defined('ABSPATH'))
{
	exit;
}

define('BP_MEMBERS_ONLY_FREE_ADMIN_PATH', plugin_dir_path(__FILE__).'admin'.'/');


$bpmoinitsetup = get_option ( 'bpmoinitsetup' );
if (empty($bpmoinitsetup))
{
	require_once("rules/bpmoinit.php");
}

$bpmofreecurrentversion = get_option ( 'bpmofreecurrentversion' );
$bpmofreecurrentversion = str_replace ( '.', '', $bpmofreecurrentversion );
if (empty ( $bpmofreecurrentversion ))
{

}
else
{
	define('BPMO_FREE_VERSION', '3.3.5');
	update_option ( 'bpmofreecurrentversion', BPMO_FREE_VERSION);
}


require_once("componentsettings.php");
require_once('rules/posttypes.php');  
require_once("rules/shortcoderestriction.php"); // 2.8.5
require_once('rules/rssrestrictssetting.php');
require_once("rules/activityrssrestrict.php");
require_once("rules/restrictwordpressrss.php"); //3.3.3

ob_start();
add_action('admin_menu', 'bp_members_only_option_menu');

/**** localization ****/
add_action('plugins_loaded','bp_members_only_load_textdomain');

function bp_members_only_load_textdomain()
{
	load_plugin_textdomain('bp-members-only', false, dirname( plugin_basename( __FILE__ ) ).'/languages/');
}

function bp_members_only_option_menu()
{

   add_menu_page(__('Buddypress Members Only', 'bp-members-only'), __('Buddypress Members Only', 'bp-members-only'), 'manage_options', 'bpmemberonly', 'buddypress_members_only_setting');
   add_submenu_page('bpmemberonly', __('Buddypress Members Only','bp-members-only'), __('Buddypress Members Only','bp-members-only'), 'manage_options', 'bpmemberonly', 'buddypress_members_only_setting');
   add_submenu_page ( 'bpmemberonly', __ ( 'Buddypress Members Only', 'bp-members-only' ), __ ( 'BP Components', 'bp-members-only' ), 'manage_options', 'bpmembercomponentonly', 'buddypress_members_component_setting_free' );
   add_submenu_page ( 'bpmemberonly', __ ( 'Buddypress Members Only', 'bp-members-only' ), __ ( 'Restrict Post Types','bp-members-only'),"manage_options", 'bpMembersOnlyFreePostTypesSettings','bpMembersOnlyFreePostTypesSettings');  //!!!
   add_submenu_page ( 'bpmemberonly', __ ( 'BP Members Only', 'bp-members-only' ), __ ( 'RSS Restricts', 'bp-members-only' ), 'manage_options', 'bpmemberrssrestricts', 'buddypress_members_rss_restricts_setting_free' );   
   add_submenu_page('bpmemberonly',__('Knowledge Base','bp-members-only'), __('Knowledge Base','bp-members-only'),"manage_options", 'membersonlyfaq','membersOnlyFreeFAQ');
}

//!!!start
$bpdisableallfeature = get_option('bpdisableallfeature');
if ('yes' == $bpdisableallfeature)
{
	return;
}
//!!!end

function buddypress_members_only_setting()
{
		global $wpdb;
		
		$bpmemonlyredirectbppagesafterlogin = 'bpmemonlyredirectbppagesafterlogin_Subscriber';
		$bpmemonlyredirecttypeafterlogin = 'bpmemonlyredirecttypeafterlogin_Subscriber';
		$bpmemonlyredirecttypeafterloginoption = get_option($bpmemonlyredirecttypeafterlogin);
		$bpmemonlyredirectbppagesafterloginoption = get_option($bpmemonlyredirectbppagesafterlogin);
		
		$m_bpmoregisterpageurl = get_option('bpmoregisterpageurl');

		if (isset($_POST['bpmosubmitnew']))
		{
			// 1.8.3
			check_admin_referer( 'bpmo_tomas_bp_members_only_nonce' );			
			if (isset($_POST['bpmoregisterpageurl']))
			{
			    //3.2.3
			    $m_bpmoregisterpageurl = sanitize_url($_POST['bpmoregisterpageurl']);
				//$m_bpmoregisterpageurl = esc_url($_POST['bpmoregisterpageurl']);
			}
				
			update_option('bpmoregisterpageurl',$m_bpmoregisterpageurl);
			
			if (isset($_POST['bpopenedpageurl']))
			{
				$bpopenedpageurl = trim($_POST['bpopenedpageurl']);
				if (strlen($bpopenedpageurl) == 0)
				{
					delete_option('saved_open_page_url');
				}
				else 
				{
					if (function_exists('sanitize_textarea_field'))
					{	
						$bpopenedpageurl = sanitize_textarea_field($bpopenedpageurl);
					}
					
					update_option('saved_open_page_url',$bpopenedpageurl);
				}
			}


			if (isset($_POST[$bpmemonlyredirecttypeafterlogin]))
			{
			    //3.2.3
			    update_option($bpmemonlyredirecttypeafterlogin, sanitize_text_field($_POST[$bpmemonlyredirecttypeafterlogin]));
				//update_option($bpmemonlyredirecttypeafterlogin, $_POST[$bpmemonlyredirecttypeafterlogin]);
				if (isset($_POST[$bpmemonlyredirectbppagesafterlogin]))
				{
				    //3.2.3
				    update_option($bpmemonlyredirectbppagesafterlogin, sanitize_text_field($_POST[$bpmemonlyredirectbppagesafterlogin]));
					//update_option($bpmemonlyredirectbppagesafterlogin, $_POST[$bpmemonlyredirectbppagesafterlogin]);
				}
			}
			else
			{
				delete_option($bpmemonlyredirecttypeafterlogin);
				delete_option($bpmemonlyredirectbppagesafterlogin);
			}
			
			if (isset ( $_POST ['bprestrictsbuddypresssection'] )) {
			    //3.2.3
			    $m_bprestrictsbuddypresssection = sanitize_text_field($_POST ['bprestrictsbuddypresssection']);
				//$m_bprestrictsbuddypresssection = $_POST ['bprestrictsbuddypresssection'];
				update_option ( 'bprestrictsbuddypresssection', $m_bprestrictsbuddypresssection );
			} else {
				delete_option ( 'bprestrictsbuddypresssection' );
			}			
			
			$bpmoMessageString =  __( 'Your changes has been saved.', 'bp-members-only' );
			buddypress_members_only_message($bpmoMessageString);
			
			
			if (isset($_POST['bpdisableallfeature']))
			{
				$bpdisableallfeature = sanitize_text_field($_POST['bpdisableallfeature']);
				update_option('bpdisableallfeature',$bpdisableallfeature);
			}
			else
			{
				delete_option('bpdisableallfeature');
			}
			
			$bpdisableallfeature = get_option('bpdisableallfeature');
			
			if (isset ( $_POST ['bpenablepagelevelprotect'] )) 
			{
			    //3.2.3
			    
			    $m_bpenablepagelevelprotect = sanitize_text_field($_POST ['bpenablepagelevelprotect']);
				//$m_bpenablepagelevelprotect = $_POST ['bpenablepagelevelprotect'];
				update_option ( 'bpenablepagelevelprotect', $m_bpenablepagelevelprotect );
			} 
			else 
			{
				delete_option ( 'bpenablepagelevelprotect' );
			}
			$bpenablepagelevelprotect = get_option ( 'bpenablepagelevelprotect' );
			
			//tomas_bbp_member_only_free_custom_links_login
			if (isset ( $_POST ['tomas_bbp_member_only_free_custom_links_login'] ) && (!(empty($_POST ['tomas_bbp_member_only_free_custom_links_login']))))
			{
			    //3.2.3
			    $tomas_bbp_member_only_free_custom_links_login = sanitize_text_field($_POST ['tomas_bbp_member_only_free_custom_links_login']);
			    
				//$tomas_bbp_member_only_free_custom_links_login = $_POST ['tomas_bbp_member_only_free_custom_links_login'];
				update_option ( 'tomas_bbp_member_only_free_custom_links_login', $tomas_bbp_member_only_free_custom_links_login );
				add_rewrite_rule( $tomas_bbp_member_only_free_custom_links_login.'/?$', 'wp-login.php', 'top' );
				flush_rewrite_rules();
			}
			else
			{
				delete_option ( 'tomas_bbp_member_only_free_custom_links_login' );
				flush_rewrite_rules();
			}
			$tomas_bbp_member_only_free_custom_links_login = get_option ( 'tomas_bbp_member_only_free_custom_links_login' );			
		}
		echo "<br />";

		$saved_register_page_url = get_option('bpmoregisterpageurl');
		$bpmemonlyredirecttypeafterloginoption = get_option($bpmemonlyredirecttypeafterlogin);
		$bpmemonlyredirectbppagesafterloginoption = get_option($bpmemonlyredirectbppagesafterlogin);		
		?>

<div style='margin:10px 5px;'>
<div style='float:left;margin-right:10px;'>
<img src='<?php echo get_option('siteurl');  ?>/wp-content/plugins/buddypress-members-only/images/new.png' style='width:30px;height:30px;'>
</div> 
<div style='padding-top:5px; font-size:22px;'> <i></>Buddypress Members Only Setting:</i></div>
</div>
<div style='clear:both'></div>		
		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body"  style="width:60%;">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:98%;">
								<div class="postbox">
								<?php
								/*
									<h3 class='hndle' style='padding-top:10px;padding-bottom:10px;'><span>
									<?php 
											echo  __( 'Option Panel:', 'bp-members-only' );
									?>
									</span>
									</h3>
								*/
								?>
									<div class="inside" style='padding-left:10px;'>
										<form id="bpmoform" name="bpmoform" action="" method="POST">
											<?php
											// 1.8.3
											wp_nonce_field('bpmo_tomas_bp_members_only_nonce');
											?>										
										<table id="bpmotable" width="100%">
										<tr>
										<td width="100%" style="padding: 0px 20px;">
										<p>
										<?php echo  __( '<h3>Register Page URL:</h3>', 'bp-members-only' ); ?>
										</p>
										<input type="text" id="bpmoregisterpageurl" name="bpmoregisterpageurl"  style="width:500px;" size="70" value="<?php  echo esc_url($saved_register_page_url); ?>">										
										<?php
										//3.2.3
										/*
										<input type="text" id="bpmoregisterpageurl" name="bpmoregisterpageurl"  style="width:500px;" size="70" value="<?php  echo $saved_register_page_url; ?>">
										*/
										?>
										<p>
										<font color="Gray"><i><?php echo  __( '# When non-member users view your buddypress pages, they will be redirected to this page, you can input the URL of your register page, or login page.', 'bp-members-only' ); ?></i></font>
										</p>
										<p>
										<font color="Gray"><i><?php echo  __( '# Also you can input URL of other pages, for example, landing page or product page, please ensure checked "Only Protect My Buddypress Page" option at below.', 'bp-members-only' ); ?></i></font>
										</p>
										<p>
										<?php 
											$knowledgebasemenuurl = get_option('siteurl') . '/wp-admin/admin.php?page=membersonlyfaq';
										?>
										<font color="Gray"><i><?php echo  __( '# By default, your buddypress site will restricts to non-members users, but you can open specific pages or open all non-buddypress pages to users, please click "<a href="'.esc_url($knowledgebasemenuurl).'" target="_blank">knowledge Base</a>" menu item to understand how to use this plugin, thanks.', 'bp-members-only' ); ?></i></font>
										<?php
										//3.2.3
										/*
										<font color="Gray"><i><?php echo  __( '# By default, your buddypress site will restricts to non-members users, but you can open specific pages or open all non-buddypress pages to users, please click "<a href="'.$knowledgebasemenuurl.'" target="_blank">knowledge Base</a>" menu item to understand how to use this plugin, thanks.', 'bp-members-only' ); ?></i></font>
										*/
										?>
										</p>
										<p>
										<font color="Gray"><i>
										<?php echo  __( '# Any question, suggestion or feature request, just submit a <a href="https://membersonly.top/contact-us/" target="_blank">support ticket</a>, we are very happy to help you, thanks.', 'bp-members-only' ); ?>
										</i></font>
										</p>
										</td>
										</tr>
										
										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<p>
										<?php echo  __( '<h3>Opened Page URLs:</h3>', 'bp-members-only' ); ?>
										</p>
										<?php 
										$urlsarray = get_option('saved_open_page_url'); 
										?>
										<textarea name="bpopenedpageurl" id="bpopenedpageurl" cols="70" rows="10" style="width:500px;"><?php echo esc_textarea($urlsarray); ?></textarea>
										<?php
										/*
										<textarea name="bpopenedpageurl" id="bpopenedpageurl" cols="70" rows="10" style="width:500px;"><?php echo $urlsarray; ?></textarea>
										*/
										?>
										<p><font color="Gray"><i><?php echo  __( 'Enter one URL per line please.', 'bp-members-only' ); ?></i></p>
										<p><font color="Gray"><i><?php echo  __( 'These pages will opened for guest and guest will not be directed to register page.', 'bp-members-only' ); ?></i></p>					
										</td>
										</tr>

										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<p>
										<?php echo  __( '<h3>Redirect Logged in Users to:</h3>', 'bp-members-only' ); ?>
										</p>
										<?php 
										$bpmemonlyredirectbppagesafterloginoption = get_option($bpmemonlyredirectbppagesafterlogin);
										?>
										<input type="checkbox"  id="<?php echo esc_attr($bpmemonlyredirecttypeafterlogin); ?>" name="<?php echo esc_attr($bpmemonlyredirecttypeafterlogin); ?>" value="bppages" <?php checked( 'bppages', $bpmemonlyredirecttypeafterloginoption ); ?> />
										<?php
										//3.2.3
										/*
										<input type="checkbox"  id="<?php echo $bpmemonlyredirecttypeafterlogin; ?>" name="<?php echo $bpmemonlyredirecttypeafterlogin; ?>" value="bppages" <?php checked( 'bppages', $bpmemonlyredirecttypeafterloginoption ); ?> />
                                        <select name="<?php echo $bpmemonlyredirectbppagesafterlogin; ?>" id="<?php echo $bpmemonlyredirectbppagesafterlogin; ?>">
											<option value="bpprofile" <?php selected( $bpmemonlyredirectbppagesafterloginoption, 'bpprofile' ); ?>><?php echo 'BuddyPress Personal Profile Page' ?></option>
											<option value="bpmembers" <?php selected( $bpmemonlyredirectbppagesafterloginoption, 'bpmembers' ); ?>><?php echo 'BuddyPress Members Page' ?></option>
											<option value="bpuseractivity" <?php selected( $bpmemonlyredirectbppagesafterloginoption, 'bpuseractivity' ); ?>><?php echo 'BuddyPress Personal Activity Page' ?></option>
										</select>
										
                                        */
										?>
										<select name="<?php echo esc_attr($bpmemonlyredirectbppagesafterlogin); ?>" id="<?php echo esc_attr($bpmemonlyredirectbppagesafterlogin); ?>">
											<option value="bpprofile" <?php selected( esc_attr($bpmemonlyredirectbppagesafterloginoption), 'bpprofile' ); ?>><?php echo 'BuddyPress Personal Profile Page' ?></option>
											<option value="bpmembers" <?php selected( esc_attr($bpmemonlyredirectbppagesafterloginoption), 'bpmembers' ); ?>><?php echo 'BuddyPress Members Page' ?></option>
											<option value="bpuseractivity" <?php selected( esc_attr($bpmemonlyredirectbppagesafterloginoption), 'bpuseractivity' ); ?>><?php echo 'BuddyPress Personal Activity Page' ?></option>
										</select>

										<p><font color="Gray"><i><?php echo  __( 'You can setting redirect logged in users to buddypress profile page or buddypress members page.', 'bp-members-only' ); ?></i></p>
										<p><font color="Gray"><i><?php echo  __( 'If you did not install buddypress, this option will be ignored.', 'bp-members-only' ); ?></i></p>					
										</td>
										</tr>				
										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<p>
										<?php echo __ ( '<h3>Only Protect My Buddypress Pages:</h3>', 'bp-members-only' ); ?>
										</p>
										<p>
										<?php
	$bprestrictsbuddypresssection = get_option ( 'bprestrictsbuddypresssection' );
	if (! (empty ( $bprestrictsbuddypresssection ))) {
		echo '<input type="checkbox" id="bprestrictsbuddypresssection" name="bprestrictsbuddypresssection"  style="" value="yes"  checked="checked"> All Other Sections On Your Site Will be Opened to Guest ';
	} else {
		echo '<input type="checkbox" id="bprestrictsbuddypresssection" name="bprestrictsbuddypresssection"  style="" value="yes" > All Other Sections On Your Site Will be Opened to Guest ';
	}
	?>
										</p>
												<p>
													<font color="Gray"><i>
										<?php
	echo __ ( '# If you disabled this option, our plugin will protect all of your wordpress posts to non-member users, only home page / login / register / lost password page will be opened to guest.', 'bp-members-only' );
	?></i>
												
												</p>										
												<p>
													<font color="Gray"><i>
										<?php
	echo __ ( '# If you enabled this option, "opened Page URLs" setting in ', 'bp-members-only' );
	echo "<a  style='color:#4e8c9e;' href='" . get_option ( 'siteurl' ) . "/wp-admin/admin.php?page=bpmemberonly' target='_blank'>Opened Pages Panel</a>";
	echo __ ( ' will be ignored', 'bp-members-only' );
	?></i>
												
												</p>
												<p>
													<font color="Gray"><i>
										<?php
	echo __ ( '# All buddypress pages will be protected yet', 'bp-members-only' );
	?>
										
										</i>
												
												</p>
										</td>
										</tr>

										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<p>
										<?php 
											echo  __( '<h3>Temporarily Turn Off All Featrures:</h3>', 'bp-members-only' );
										?>
										</p>
										<p>
										<?php
										$bpdisableallfeature = get_option('bpdisableallfeature');
										if (!(empty($bpdisableallfeature)))
										{
											echo '<input type="checkbox" id="bpdisableallfeature" name="bpdisableallfeature"  style="" value="yes"  checked="checked"> Temporarily Turn Off All Featrures Of BuddyPress Members Only ';
 
										}
										else 
										{
											echo '<input type="checkbox" id="bpdisableallfeature" name="bpdisableallfeature"  style="" value="yes" > Temporarily Turn Off All Featrures Of BuddyPress Members Only ';
										}
										?>
										</p>
										<p><font color="Gray"><i>
										<?php 
										echo  __( '# If you enabled this option, all features of buddypress members only will be disabled, you site will open to all users', 'bp-members-only') ;
										?></i></p>
										</td>
										</tr>
										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<p>										
										<?php
	echo __ ( '<h3>Enable Page Level Protect:</h3>', 'bp-members-only' );
	?>
										</p>
										<p>
										<?php
	$bpenablepagelevelprotect = get_option ( 'bpenablepagelevelprotect' );
	if (! (empty ( $bpenablepagelevelprotect ))) {
	} else {
		$bpenablepagelevelprotect = '';
	}
	?>
										<?php
	if (! (empty ( $bpenablepagelevelprotect ))) {
		echo '<input type="checkbox" id="bpenablepagelevelprotect" name="bpenablepagelevelprotect"  style="" value="yes"  checked="checked"> Enable Page Level Protect Settings ';
	} else {
		echo '<input type="checkbox" id="bpenablepagelevelprotect" name="bpenablepagelevelprotect"  style="" value="yes" > Enable Page Level Protect Settings ';
	}
	?>
										
										<p>
													<font color="Gray"><i>
										<?php
	echo __ ( '# If you enabled this option,  in ', 'bp-members-only' );
	echo "<a style='color:#4e8c9e;' href='" . get_option ( 'siteurl' ) . "/wp-admin/post-new.php' target='_blank'>page / post  editor</a>";
	echo __ ( ', you will find "Members only for this page?" meta box at the right top of the wordpress standard editor.', 'bp-members-only in ' );
	?>
										</i>
												
												</p>
												<p>
													<font color="Gray"><i><?php echo  __( '# If you checked "Allow everyone to access the page" checkbox in meta box, the post will be opened to all guest users', 'bp-members-only' ); ?></i>
												
												</p>
												<p>
													<font color="Gray"><i>
										<?php
	echo __ ( '# By this way, you do not need enter page URLs to ', 'bp-members-only' );
	echo "<a  style='color:#4e8c9e;' href='" . get_option ( 'siteurl' ) . "/wp-admin/admin.php?page=bpmemberonly' target='_blank'>Opened Pages Panel</a>";
	echo __ ( ' always.', 'bp-members-only' );
	
	?></i>
												</p>
											</td>
										</tr>
<?php //!!!start ?>
										<tr style="margin-top:30px;">
										<td width="100%" style="padding: 20px;">
										<p>										
										<?php
	echo __ ( '<h3>Custom / Hide WordPress Login Link:</h3>', 'bp-members-only' );
	?>
										</p>
										<p>
										<?php
											$tomas_bbp_member_only_free_custom_links_login = get_option('tomas_bbp_member_only_free_custom_links_login');
										?>
	<span><font color='gray'><?php echo get_option('siteurl').'/'; ?></font></span> <input type="text" id="tomas_bbp_member_only_free_custom_links_login" name="tomas_bbp_member_only_free_custom_links_login" size='10' value="<?php  echo esc_attr($tomas_bbp_member_only_free_custom_links_login); ?>"> <font color='gray'>/</font>
										</p>
										<p>
													<font color="Gray"><i>
										<?php
	echo __ ( '# Why custom login link? A few users said they hope change wordpress login link for their private network, for stop spam bot', 'bp-members-only' );
	echo '<p>';
	echo __ ( '# By default, wordpress login link is https://yourdomain.com/wp-login.php, but you can custom it as https://yourdomain.com/signin, and so on', 'bp-members-only' );
	echo '</p>';
	echo '<p>';
	echo __ ( '# If you do not want to custom wordpress login link, just delete your value in this filed', 'bp-members-only' );
	echo '</p>';
	?>
										</i>
												</p>
											</td>
										</tr>
<?php //!!!end ?>
										</table>
										<br />
										<input type="submit" id="bpmosubmitnew" name="bpmosubmitnew" value=" Submit " style="margin:1px 20px;">
										</form>
										
										<br />
									</div>
								</div>
								<div>
								<a class="" style="font-size:16px;vertical-align: super;color: #999;" target="_blank" href="https://paypal.me/sunpayment">
								<span>
								Buy me a coffee?
								</span>
								</a>
								</div>								
							</div>
						</div>
					</div>
<?php if (function_exists('is_rtl'))
{
	//better rtl support
	if (is_rtl())
	{
		echo '<div id="post-body"  style="width:40%; float:left;">';
	}
	else
	{
		echo '<div id="post-body"  style="width:40%; float:right;">';
	}
}
else 
{
	echo '<div id="post-body"  style="width:40%; float:right;">';
}
?>
				
<?php 					
/*
 * no rtl
<div id="post-body"  style="width:40%; float:right;">
*/
?>
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:90%;">
							

								<div class="postbox">
									<h3 class='hndle' style='padding: 10px 0px; !important'>
									<span>
									<a class="" style="font-size:28px;" target="_blank" href="https://membersonly.top/features/">Members Only Pro Features</a>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
							<div class="inside">
									<ul>
									<li>
										* All functions in free version
									</li>
									<li>
										* Fine-grained access control for buddypress site, you can restrict each buddypress componets based on user roles, one click to build user roles for BP members automatically, control each user role's profile visibility, each menu visibility based on user roles, restricts BP messages based on user roles, opt to restricts each wordpress pages(even home page), opt to restrict site RSS feed, opt to restricts specific BuddyPress Component Pages from search engines, opt to only allow approved members login your buddypress private network, you can decide which section of your BP / WP site will be opened / closed to specific user roles... <b>more feature can be found at: <a class="" target="_blank" href="https://membersonly.top/features/">https://membersonly.top/features/</a></b>
									</li>
									<li>
										* Super easy to use, just a few clicks, build a membership buddypress network or build a privacy site quickly, with detailed guide.  
									</li>									
									<li>
										* <a href='https://membersonly.top/features/' target='_blank'>Restricts BP standard components / customization components to users based on user roles</a>, for example, you can disable BP components pages to guest, open members component page to subscriber user roles, open profile component page to paid user roles only…
									</li>
									<li>
										* <a href='https://membersonly.top/buddypress-menu-visibility-by-user-roles-demo/' target='_blank'>Menu Visibility Control by User Roles</a>, for example, you can only allow customer user role to see download menu, subscriber & customer user role can see product menu... and so on, so you do not need make a long menu lists to all users
									</li>
									<li>
										* <a href='https://membersonly.top/buddypress-membership-plugin-4-0-2-released-buddypress-profile-field-visibility-control-by-user-roles-addon-support-hide-buddypress-profile-fields/' target='_blank'>Members profile field visibility control by user roles</a>, for example, you can settings to only paid members can show their profile fileds publicly, and profile fields of subscriber user role will be hidden... and so on
									</li>																
									<li>
										* One click to add / remove 10 buddypress membership Levels, edit name of default membership levels, for example: Bronze, Silver, Gold… and so on
									</li>
									<li>
										* Just a simple clicks to charge membership fee via membership levels with [BuddyPress Membership WooCmmerce Payment Gateway Plugin](<a href='https://membersonly.top/features-of-buddypress-woocommerce-payment-gateway-plugin/' target='_blank'>https://membersonly.top/features-of-buddypress-woocommerce-payment-gateway-plugin/</a>)
									</li>									
									<li>
										* Approved Users Only, after enabled this option, when users register as members, they need awaiting administrator approve their account manually, only approved users can login your site, Admin user can approve or unapprove any users again at anytime.
									</li>
									<li>
										* Powerful Login redirect / Logout Redirect Based on **User Roles**: pro version will support more login / logout redirect options,  each user roles have options for redirect to the referer pages before they login, also support 12 buddypress components to redirect login / logout users, and support to redirect to any specific url based on your setup for each user roles... and so on
									</li>
									<li>
										* Customized Opened URLs Restricts based on user roles, For example, you can settings https://yourdomain.com/members/%username%/forums/ only opened for paid users, or open %sitename%/family/%username%/ for family user types… and so on.
									</li>									
									<li>
										* Customized Closed URLs Restricts based on user roles, for example, you can close https://yourdomain.com/support page to guest users, and only open it for customer user roles, at the same time, you can open https://yourdomain.com/shop for guest user role, support use placeholders %username% and %sitename% to protect your customized Closed URLs pages
									</li>									
									<li>
										* If you disable buddypress, our plugins will detect it automatically and continue to protect your wordpress pages.
									</li>
									<li>
										* Support Add Announcement on Buddypress Members Only register page, you can add announcement in editor with image, link, font style, videos… and so on, we will show announcement at top of register page.
									</li>
									<li>
										* …… more and more
									</li>
									<li>
									<b>more feature can be found at: <a class="" target="_blank" href="https://membersonly.top/features/">https://membersonly.top/features/</a></b>
									</li>
										</div>									
									</div>
								</div>

								<div class="postbox">
									<h3 class='hndle' style='padding: 20px 0px; !important'>
									<span>
									<?php 
											echo  __( 'More BuddyPress Plugins Maybe You Will Like', 'bp-members-only' );
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
							<div class="inside">
									<ul>
									<li>
										* <a class="" target="_blank" href="https://membersonly.top/features-of-buddypress-woocommerce-payment-gateway-plugin/">BuddyPress WooCommerce Payment Plugin</a>
									</li>
									<li>
								* <a class=""  target="_blank" href="https://membersonly.top/features-of-buddypress-blacklist-whitelist-security-plugin/">BuddyPress Blacklist Plugin</a>
								</li>
								<li>								
								* <a class=""  target="_blank" href="https://membersonly.top/features-of-buddypress-google-xml-sitemaps-generator-plugin/">BuddyPress Google XML Sitemaps Generator Plugin</a>
								</li>
						</div>									
									
									</div>
								</div>
								
								<div class="postbox">
									<h3 class='hndle' style='padding: 20px 0px; !important'>
									<span>
									<?php 
											echo  __( 'About This Plugin', 'bp-members-only' );
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
							<div class="inside">
									<ul>
									<li>
										* <a class="" target="_blank" href="https://membersonly.top/features/">Plugin Homepage, Help / FAQ</a>
									</li>
									<li>
								* <a class=""  target="_blank" href="https://membersonly.top/contact-us/">Support, Suggest a Feature, Report a Bug? Need Customize Plugin?</a>
								</li>
								<li>								
								* <a class=""  target="_blank" href="https://tomas.zhu.bz/category/my-share/">Sign UP for Free BuddyPress / WordPress Tips MailChimp List</a>
								</li>
						</div>									
									
									</div>
								</div>
																
								
								<div class="postbox">
									<h3 class='hndle' style='padding: 20px 0px; !important'>
									<span>
									<?php 
											echo  __( 'BuddyPress Wordpress Tips Feed:', 'bp-members-only' );
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:10px;'>
						<?php 
							wp_widget_rss_output('https://tomas.zhu.bz/feed/', array(
							'items' => 3, 
							'show_summary' => 0, 
							'show_author' => 0, 
							'show_date' => 1)
							);
						?>
										<br />
									</div>
								</div>
							</div>
						</div>
											
					</div>
					<div style='clear:both'></div>					
		    	</div>
			</div>
		</div>
		<div style="clear:both"></div>
		<br />

		
		
		<?php
		}				

	
function buddypress_members_only_message($p_message)
{

	echo "<div id='message' class='updated fade' style='line-height: 30px;margin-left: 0px;'>";

	echo $p_message;

	echo "</div>";

}

function buddypress_only_for_members()
{
	global $user_ID , $post;
	if (is_front_page()) return;
	if (function_exists('bp_is_register_page') && function_exists('bp_is_activation_page') )
	{
		if ( bp_is_register_page() || bp_is_activation_page() )
		{
			return;
		}
	}
	
	//3.2.3
	$current_url = sanitize_text_field($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	//$current_url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
	$current_url = str_ireplace('http://','',$current_url);
	$current_url = str_ireplace('https://','',$current_url);
	$current_url = str_ireplace('ws://','',$current_url);
	$current_url = str_ireplace('www.','',$current_url);
	$saved_register_page_url = get_option('bpmoregisterpageurl');

	$saved_register_page_url = str_ireplace('http://','',$saved_register_page_url);
	$saved_register_page_url = str_ireplace('https://','',$saved_register_page_url);
	$saved_register_page_url = str_ireplace('ws://','',$saved_register_page_url);
	$saved_register_page_url = str_ireplace('www.','',$saved_register_page_url);
	
	//!!!start
	$current_page_id = get_the_ID ();
	$bpenablepagelevelprotect = get_option ( 'bpenablepagelevelprotect' );
	if (! (empty ( $bpenablepagelevelprotect ))) 
	{
		$get_post_meta_value_for_this_page = get_post_meta ( $current_page_id, 'bp_members_only_access_to_this_page', true );
		if (strtolower ( $get_post_meta_value_for_this_page ) == 'yes') 
		{
			return;
		}
	}	
	//!!!end	
	
	//!!!start restrict post type
	$bp_in_post_type_have_access = false;
	if (isset($post->ID))
	{
		$bpmp_post_type = $post->post_type;
	
		if (!(empty($bpmp_post_type)))
		{
			if (is_user_logged_in () == false)
			{
				$bpmemonlypro_enabled_post_type = get_option("bpmemonlypro_enabled_post_type");
				if ((! (empty ( $bpmemonlypro_enabled_post_type ))) && (is_array ( $bpmemonlypro_enabled_post_type )) && (count ( $bpmemonlypro_enabled_post_type ) > 0) && (in_array ( $bpmp_post_type, $bpmemonlypro_enabled_post_type )))
				{
					return;
				}
			}
			/*
			else
			{
				$user = wp_get_current_user ();
				if (empty ( $user->roles ))
				{
					if ((! (empty ( $bpmemonlypro_enabled_post_type ))) && (is_array ( $bpmemonlypro_enabled_post_type )) && (count ( $bpmemonlypro_enabled_post_type ) > 0) && (in_array ( $bpmp_post_type, $bpmemonlypro_enabled_post_type )))
					{
						return;
					}
				}
				else
				{
					foreach ( $tomas_roles_all_array as $tomas_roles_single_key => $tomas_roles_single_array )
					{
						$bp_component_role_id = $tomas_roles_single_key;
							
						if (in_array ( $bp_component_role_id , $user->roles ))
						{
	
						}
						else
						{
							continue;
						}
						$bp_component_role_id = str_replace ( ' ', '-', $tomas_roles_single_key );
							
						$rolebasedstandardcomponent = 'bpmemonlypro_enabled_post_type_' . $bp_component_role_id;
	
						$rolebasedstandardcomponentoption = get_option ( $rolebasedstandardcomponent );
	
	
						if (! (empty ( $rolebasedstandardcomponentoption )))
						{
							foreach ( $rolebasedstandardcomponentoption as $rolebasedstandardcomponentoption_single )
							{
								if ($rolebasedstandardcomponentoption_single == $bpmp_post_type)
								{
									return;
								}
							}
						}
						else
						{
							continue;
						}
					}
				}
			}
			*/
		}
	}
	//!!!end restrict post type end
	
	$bprestrictsbuddypresssection = get_option ( 'bprestrictsbuddypresssection' );
	$m_bpstandardcomponent = get_option ( 'bpstandardcomponent' ); // !!!3.0.5
	
	if (function_exists ( 'bp_current_component' )) {
		$is_bp_current_component = bp_current_component ();
	} else {
		$is_bp_current_component = '';
	}
	$bp_in_componet_have_access = false; //!!! 3.0.5
	
	//!!!3.0.5
	if (is_user_logged_in () == false) {
	    if ((! (empty ( $m_bpstandardcomponent ))) && (is_array ( $m_bpstandardcomponent )) && (count ( $m_bpstandardcomponent ) > 0) && (in_array ( $is_bp_current_component, $m_bpstandardcomponent ))) 
	    {
	        return;
	    }
	} 
	//!!! end 3.0.5
	
	if (! (empty ( $bprestrictsbuddypresssection ))) {
		if (empty ( $is_bp_current_component )) {
			return;
		}
	}
	
	
	if (stripos($saved_register_page_url, $current_url) === false)
	{

	}
	else 
	{
		return;
	}
	
	// check is opened pages
	$saved_open_page_option = get_option('saved_open_page_url');

	$saved_open_page_url = explode("\n", trim($saved_open_page_option));

	if ((is_array($saved_open_page_url)) && (count($saved_open_page_url) > 0))
	{
		$root_domain = get_option('siteurl');
		foreach ($saved_open_page_url as $saved_open_page_url_single)
		{
			// start 1.6
			$saved_open_page_url_single = trim($saved_open_page_url_single); 

			if (reserved_url_free($saved_open_page_url_single) == true)
			{
				continue;
			}
			// end 1.6			
			$saved_open_page_url_single = pure_url_free($saved_open_page_url_single);

			//2.7.7
			if (empty($saved_open_page_url_single))
			{
				
			}
			else
			{
				if (stripos($current_url,$saved_open_page_url_single) === false)
				{
				
				}
				else
				{
				
					return;
				}				
			}
				
			/*
			 * !!!old
			if (stripos($current_url,$saved_open_page_url_single) === false)
			{

			}
			else 
			{

				return;
			}
			*/
		}
	}

	if ( is_user_logged_in() == false )
	{
		if (empty($saved_register_page_url))
		{
		    //3.2.3
		    $current_url = sanitize_text_field($_SERVER['REQUEST_URI']);
		    
			//$current_url = $_SERVER['REQUEST_URI'];
			$redirect_url = wp_login_url( );
			header( 'Location: ' . $redirect_url );
			die();			
		}
		else 
		{
			$saved_register_page_url = 'http://'.$saved_register_page_url;
			header( 'Location: ' . $saved_register_page_url );
			die();
		}
	}
}

function pure_url_free($current_url)
{
	if (empty($current_url)) return false;

	$current_url_array = parse_url($current_url); // 1.6

	
	
	// 1.3
	$current_url = str_ireplace('http://','',$current_url);
	// start 1.6
	$current_url = str_ireplace('https://','',$current_url);
	$current_url = str_ireplace('ws://','',$current_url);
	// end 1.6
		
	$current_url = str_ireplace('www.','',$current_url);
	$current_url = trim($current_url);
	return $current_url;
}

// 1.6
function reserved_url_free($url)
{
	$home_page = get_option('siteurl');
	$home_page = pure_url_free($home_page);
	$url = pure_url_free($url);
	if ($home_page == $url)
	{
		return true;
	}
	else
	{
		return false;
	}
} 


if (function_exists('bp_is_register_page') && function_exists('bp_is_activation_page') )
{
	add_action('wp','buddypress_only_for_members');
}
else 
{
	add_action('wp_head','buddypress_only_for_members');
}

function bpmo_free_tomas_login_redirect($redirect_to, $requested_redirect_to, $user)
{
	global  $wp_roles,$user_ID;

	$tomas_roles_all_array =  $wp_roles->roles;

	if (empty($user))
	{
		$user = wp_get_current_user();
	}

	if (empty($user->roles))
	{
		return $redirect_to;
	}

	
	$bpmemonlyredirectbppagesafterlogin = 'bpmemonlyredirectbppagesafterlogin_Subscriber';
	$bpmemonlyredirecttypeafterlogin = 'bpmemonlyredirecttypeafterlogin_Subscriber';
	$bpmemonlyredirecttypeafterloginoption = get_option($bpmemonlyredirecttypeafterlogin);
	$bpmemonlyredirectbppagesafterloginoption = get_option($bpmemonlyredirectbppagesafterlogin);
	
		if ($bpmemonlyredirecttypeafterloginoption == 'bppages')
		{
			$is_buddypress_plugin_activated = in_array( 'buddypress/bp-loader.php', (array) get_option( 'active_plugins', array() ) );
				
			if (true == $is_buddypress_plugin_activated )
			{
				$redirect_target_url_to_bppages = $bpmemonlyredirectbppagesafterloginoption;

				if ($redirect_target_url_to_bppages == 'bpmembers') {
					$bpmembersslug = bp_get_members_root_slug ();
					$redirect_target_url = home_url ( $bpmembersslug );
				}

				if ($redirect_target_url_to_bppages == 'bpprofile') {
					$redirect_target_url = tomas_bpmo_free_get_user_domain ( $user ) . '/' . bp_get_profile_slug ();
				}

				if ($redirect_target_url_to_bppages == 'bpuseractivity') {
					$redirect_target_url = bp_core_get_user_domain ( $user->ID );
				}
				
				wp_safe_redirect ( $redirect_target_url );
				die ();
				return $redirect_target_url;
			}
			else
			{
				return $redirect_to;
			}
		}
	return $redirect_to;
}

function tomas_bpmo_free_get_user_domain($user)
{
	$is_buddypress_plugin_activated = in_array( 'buddypress/bp-loader.php', (array) get_option( 'active_plugins', array() ) );

	if (true == $is_buddypress_plugin_activated )
	{
		$username = bp_core_get_username ( $user->ID );

		if (bp_is_username_compatibility_mode ()) {
			$username = rawurlencode ( $username );
		}

		$after_domain = bp_core_enable_root_profiles () ? $username : bp_get_members_root_slug () . '/' . $username;
		$domain = trailingslashit ( bp_get_root_domain () . '/' . $after_domain );
	}
	else
	{
		$domain = get_option('siteurl');
	}

	return $domain;
}

add_filter( 'login_redirect',  'bpmo_free_tomas_login_redirect', 10, 3 );
add_filter( 'bp_login_redirect',  'bpmo_free_tomas_login_redirect', 1, 3 );

function membersOnlyFreeFAQ()
{
	require_once(BP_MEMBERS_ONLY_FREE_ADMIN_PATH."howto.php");
}

function members_onlt_free_admin_css()
{
	wp_enqueue_style('members_onlt_free_admin_css', plugin_dir_url( __FILE__ ) .'asset/admin/css/admin.css');

	//3.2.3
	$current_edit_page = sanitize_text_field(strtolower($_SERVER['REQUEST_URI']));
	//$current_edit_page = strtolower($_SERVER['REQUEST_URI']);
	
	if (!(empty($current_edit_page)))
	{
		if (strpos($current_edit_page, 'membersonlyfaq') === false)
		{

		}
		else
		{
			wp_register_script( 'membersonly_free_admin_js', plugin_dir_url( __FILE__ ).'/asset/admin/js/admin.js', array('jquery'));
			wp_enqueue_script( 'membersonly_free_admin_js' );
		}
	}
}
add_action('admin_head', 'members_onlt_free_admin_css');

//!!!start
$bpenablepagelevelprotect = get_option ( 'bpenablepagelevelprotect' );
if (! (empty ( $bpenablepagelevelprotect ))) {
	add_action ( 'add_meta_boxes', 'add_bp_members_only_control_meta_box' );
	add_action ( 'save_post', 'save_wp_members_only_control_meta_box', 10, 3 );
}

function bp_members_only_control_meta_box() {
	$current_page_id = get_the_ID ();
	$get_post_meta_value_for_this_page = get_post_meta ( $current_page_id, 'bp_members_only_access_to_this_page', true );
	global $wpdb;

	?>
<table cellspacing="2" cellpadding="5" style="width: 100%;"
	class="form-table">
	<tbody>
		<tr class="form-field">
			<td><input name="bp_members_only_access_to_this_page" type="checkbox"
				value="yes"
				<?php  if(esc_attr( $get_post_meta_value_for_this_page ) == 'yes' ) {echo 'checked="checked"';} ?>><label><?php _e('Allow everyone to access the page', 'admin-tools') ?></label>
			</td>
		</tr>
	</tbody>
</table>
<?php
}
function add_bp_members_only_control_meta_box() {
	add_meta_box ( "bp_members_only_control_meta_box_id", __ ( 'Members only for this page?', 'bp-members-only' ), 'bp_members_only_control_meta_box', null, "side", "high", null );
}
function save_wp_members_only_control_meta_box($post_id, $post, $update) {
	$current_page_id = get_the_ID ();
	$meta_box_checkbox_value = '';

	//!!! start 2.9.5
	$bpmofunc_in_elementor_editor = false;
	$bpmofunc_in_elementor_editor = bpmo_in_elementor_editor();
	if ($bpmofunc_in_elementor_editor == true)
	{
	    return '';
	}
	//!!! end 2.9.5 
	
	if (isset ( $_POST ['bp_members_only_access_to_this_page'] ) != "") 
	{
	    //3.2.3
	    $meta_box_checkbox_value = sanitize_text_field($_POST['bp_members_only_access_to_this_page']);
	    
	    //$meta_box_checkbox_value = $_POST ['bp_members_only_access_to_this_page'];
		$get_post_meta_value_for_this_page = get_post_meta ( $current_page_id, 'bp_members_only_access_to_this_page', true );
	}
	
	if (isset ( $_POST ['bp_members_only_access_to_this_page'] ) != "") {
		update_post_meta ( $current_page_id, 'bp_members_only_access_to_this_page', $meta_box_checkbox_value );
	} else {
		update_post_meta ( $current_page_id, 'bp_members_only_access_to_this_page', '' );
	}
}
//!!!end

function deactivate_bp_members_only_free()
{
	flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'deactivate_bp_members_only_free');

//!!! 2.8.3
function bpmo_user_first_run_guide_bar_free()
{
	$is_user_first_run_guide_bar = get_option('bpmo_user_first_run_guide_bar_free');
	if (empty($is_user_first_run_guide_bar))
	{
		echo "<div class='notice bpmo-notice notice-info'><p>Thanks for installing <strong>BuddyPress Members Only</strong>! Please check <a href='" . admin_url() . "admin.php?page=membersonlyfaq' target='_blank'>Knowledge Base</a>, then set up via <a href='" . admin_url() . "admin.php?page=bpmemberonly' target='_blank'>Setting Panel</a>, Any question or feature request please contact <a href='https://membersonly.top/contact-us/'  target='_blank'>Support</a> :)</p></div>";
		update_option('bpmo_user_first_run_guide_bar_free','yes');
	}
}

add_action( 'admin_notices', 'bpmo_user_first_run_guide_bar_free' );

//!!! 2.9.5
function bpmo_in_elementor_editor()
{
    if (isset($_POST['actions']))
    {
        $check_elementor_editor = stripos($_POST['action'], 'elementor_ajax');
        if ($check_elementor_editor === false)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}

function members_only_free_setting_panel_head($title)
{
    ?>
        <div style='float:left;margin-right:10px;'>
        <img src='<?php echo get_option('siteurl');  ?>/wp-content/plugins/buddypress-members-only/images/new.png' style='width:30px;height:30px;'>
        </div> 	
		<div style='padding-top:5px; font-size:22px;'><?php echo $title; ?></div>
		<div style='clear:both'></div>
<?php 
}
