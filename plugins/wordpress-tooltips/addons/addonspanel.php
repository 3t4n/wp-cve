<?php
if (!defined('ABSPATH'))
{
	exit;
}

function tooltipFreeAddonSettings()
{
	if (isset($_POST['enableLanguageCustomizationSubmit']))
	{
		check_admin_referer('fucwpexpertglobalsettings');
		if (isset($_POST['enableLanguageCustomization']))
		{
			$enableLanguageCustomization = sanitize_textarea_field($_POST['enableLanguageCustomization']);
			update_option("enableLanguageCustomization",$enableLanguageCustomization);
			//update_option("enableLanguageCustomization",$_POST['enableLanguageCustomization']);
		}
	
		if (file_exists(TOOLTIPS_ADDONS_PATH.'tooltips_languages.php'))
		{
			$enableLanguageCustomization = get_option("enableLanguageCustomization");
			if ('YES' == $enableLanguageCustomization)
			{
				$tooltipsMessageProString =  __( 'Changes saved, please <a href="'. get_option("siteurl") .'/wp-admin/edit.php?post_type=tooltips&page=tooltipsFreeLanguageMenu">click here to customize languages for glossary</a>', 'wordpress-tooltips' );
			}
			else
			{
				$tooltipsMessageProString =  __( 'Changes saved.', 'wordpress-tooltips' );
			}
		}
		else
		{
			$tooltipsMessageProString =  __( 'Changes saved but you did not installed "tooltips language customization addon" yet, please <a href="https://tooltips.org/contact-us" target="_blank">contact tooltips.org for support</a>', 'wordpress-tooltips' );
		}

		tooltipsMessage($tooltipsMessageProString);
	}
	$enableLanguageCustomization = get_option("enableLanguageCustomization");
	if (empty($enableLanguageCustomization)) $enableLanguageCustomization = 'NO';

	//!!!start

	//!!!start
	if (isset($_POST['enableTooltipsForOceanWPSubmit']))
	{
		check_admin_referer('fucwpexpertglobalsettings');
		if (isset($_POST['enableTooltipsForOceanWP']))
		{
			$enableTooltipsForOceanWP = sanitize_textarea_field($_POST['enableTooltipsForOceanWP']);
			update_option("enableTooltipsForOceanWP",$enableTooltipsForOceanWP);
			//update_option("enableTooltipsForOceanWP",$_POST['enableTooltipsForOceanWP']);
		}
	
		if (file_exists(TOOLTIPS_ADDONS_PATH.'tooltips_for_oceanwp.php'))
		{
			$tooltipsMessageProString =  __( 'Changes saved', 'wordpress-tooltips' );
		}
		else
		{
			$tooltipsMessageProString =  __( 'Changes saved but you did not installed "tooltips for oceanwp addon" yet, please contact tooltips.org for support', 'wordpress-tooltips' );
		}
	
		tooltipsMessage($tooltipsMessageProString);
	}
	$enableTooltipsForOceanWP = get_option("enableTooltipsForOceanWP");
	if (empty($enableTooltipsForOceanWP)) $enableTooltipsForOceanWP = 'NO';
	//!!!end
	
	if (isset($_POST['enableTooltipsForContactForm7Submit']))
	{
		check_admin_referer('fucwpexpertglobalsettings');
	    if (isset($_POST['enableTooltipsForContactForm7']))
	    {
	        $enableTooltipsForContactForm7 = sanitize_textarea_field($_POST['enableTooltipsForContactForm7']);
	        
	        update_option("enableTooltipsForContactForm7",$enableTooltipsForContactForm7);
	    }
	    
	    if (file_exists(TOOLTIPS_ADDONS_PATH.'tooltips_contactform7.php'))
	    {
	        $tooltipsMessageProString =  __( 'Changes saved', 'wordpress-tooltips' );
	    }
	    else
	    {
	        $tooltipsMessageProString =  __( 'Changes saved but you did not installed "tooltips for contact form 7 addon" yet, please contact tooltips.org for support', 'wordpress-tooltips' );
	    }
	    
	    tooltipsMessage($tooltipsMessageProString);
	}
	$enableTooltipsForContactForm7 = get_option("enableTooltipsForContactForm7");
	if (empty($enableTooltipsForContactForm7)) $enableTooltipsForContactForm7 = 'NO';
	
	?>

<div class="wrap tooltipsaddonclass">
<div id="icon-options-general" class="icon32"><br></div>
<h2>Tooltips Addon Settings</h2>
</div>

<div style='clear:both'></div>		
		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:90%;">
								<div class="postbox">
									<h3 class='hndle' style='padding: 10px 0px;'><span>
									<?php
										$knowledgeBaseURL = get_option('siteurl'). '/wp-admin/edit.php?post_type=tooltips&page=tooltipsfaq';
										echo __( "Enable/Disable Tooltips Language Customization Addon", 'wordpress-tooltips' )."<i> <font color='Gray'></font></i>";
										echo __(" <font color='gray'><i>(please check 'How to Use Language Addon to Custom Language of Your Glossary' in <a href='". esc_url($knowledgeBaseURL)."' target='_blank'>'Knowledge Base'</a> menu first )</i></font>")
										//7.9.1 echo __(" <font color='gray'><i>(please check 'How to Use Language Addon to Custom Language of Your Glossary' in <a href='$knowledgeBaseURL' target='_blank'>'Knowledge Base'</a> menu first )</i></font>")
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:5px;'>
										<form class="toolstipsform" name="toolstipsform" action="" method="POST">
										<?php
										wp_nonce_field ( 'fucwpexpertglobalsettings' );
										?>											
										<table id="toolstipstable" width="100%">

										<tr style="text-align:left;">
										<td width="25%"  style="text-align:left;">
										<?php
											$addtipto = 'span.questionlanguagecustomizationaddon';
											$questiontip = '<div class="tooltiplanguagecustomizationaddon"><p>Allow custom language / letter in tooltip / glossary, once enabled this option, you will find a language sub menu under tooltips menu </p></div>';
											$tipadsorbent = '.questionlanguagecustomizationaddon';
											$adminTip = showAdminTip($addtipto,$questiontip,'div.tooltiplanguagecustomizationaddon',$tipadsorbent);
											echo $adminTip;
											echo __( 'Tooltips Language Customization: ', 'wordpress-tooltips' ).'<span class="questionlanguagecustomizationaddon">?</span>';											
										?>
										</td>
										<td width="30%"  style="text-align:left;">
										<select id="enableLanguageCustomization" name="enableLanguageCustomization" style="width:300px;">
										<option id="enableLanguageCustomizationOption" value="YES" <?php if ($enableLanguageCustomization == 'YES') echo "selected";   ?>> <?php echo __('Enable Tooltips Language Customization Addon', "wordpress-tooltips");?> </option>
										<option id="enableLanguageCustomizationOption" value="NO" <?php if ($enableLanguageCustomization == 'NO') echo "selected";   ?>>   <?php echo __('Disable Tooltips Language Customization Addon', "wordpress-tooltips");?> </option>
										</select>
										</td>
										<td width="30%"  style="text-align:left;">
										<input type="submit" class="button-primary" id="enableLanguageCustomizationSubmit" name="enableLanguageCustomizationSubmit" value=" <?php echo __('Update Now', "wordpress-tooltips");?> ">
										</td>
										</tr>

										</table>
										</form>
										
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
<?php //!!!start ?>		
<div style='clear:both'></div>		
		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:90%;">
								<div class="postbox">
									<h3 class='hndle' style='padding: 10px 0px;'><span>
									<?php
										echo __( "Enable/Disable Tooltips for OceanWP Theme", 'wordpress-tooltips' )."<i> <font color='Gray'></font></i>";
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:5px;'>
										<form class="toolstipsform" name="toolstipsform" action="" method="POST">
										<?php
										wp_nonce_field ( 'fucwpexpertglobalsettings' );
										?>
										<table id="toolstipstable" width="100%">

										<tr style="text-align:left;">
										<td width="25%"  style="text-align:left;">
										<?php
											echo __( 'Tooltips for OceanWP Theme: ', 'wordpress-tooltips' ).'<span class="questionoceanwp">?</span>';
										?>
										<?php
										$admin_tip = __('Enable tooltips effects for OceanWP Theme', "wordpress-tooltips");
										?>
										<script type="text/javascript"> 
										jQuery(document).ready(function () {
										  jQuery("span.questionoceanwp").hover(function () {
										    jQuery(this).append('<div class="tooltipforoceanwp"><p><?php echo $admin_tip; ?></p></div>');
										  }, function () {
										    jQuery("div.tooltipforoceanwp").remove();
										  });
										});
										</script>
										</td>
										<td width="30%"  style="text-align:left;">
										<select id="enableTooltipsForOceanWP" name="enableTooltipsForOceanWP" style="width:300px;">
										<option id="enableTooltipsForOceanWPOption" value="YES" <?php if ($enableTooltipsForOceanWP == 'YES') echo "selected";   ?>> <?php echo __('Enable Tooltips for OceanWP', "wordpress-tooltips");?> </option>
										<option id="enableTooltipsForOceanWPOption" value="NO" <?php if ($enableTooltipsForOceanWP == 'NO') echo "selected";   ?>>   <?php echo __('Disable Tooltips for OceanWP', "wordpress-tooltips");?> </option>
										</select>
										</td>
										<td width="30%"  style="text-align:left;">
										<input type="submit" class="button-primary" id="enableTooltipsForOceanWPSubmit" name="enableTooltipsForOceanWPSubmit" value=" <?php echo __('Update Now', "wordpress-tooltips");?> ">
										</td>
										</tr>

										</table>
										</form>
										
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
		

<div style='clear:both'></div>		
		<div class="wrap">
			<div id="dashboard-widgets-wrap">
			    <div id="dashboard-widgets" class="metabox-holder">
					<div id="post-body">
						<div id="dashboard-widgets-main-content">
							<div class="postbox-container" style="width:90%;">
								<div class="postbox">
									<h3 class='hndle' style='padding: 10px 0px;'><span>
									<?php
										echo __( "Enable/Disable Tooltips for Contact Form Fields in Contact Form 7", 'wordpress-tooltips' )."<i> <font color='Gray'></font></i>";
									?>
									</span>
									</h3>
								
									<div class="inside" style='padding-left:5px;'>
										<form class="toolstipsform" name="toolstipsform" action="" method="POST">
										<?php
										wp_nonce_field ( 'fucwpexpertglobalsettings' );
										?>											
										<table id="toolstipstable" width="100%">

										<tr style="text-align:left;">
										<td width="25%"  style="text-align:left;">
										<?php
											echo __( 'Tooltips for Contact Form 7: ', 'wordpress-tooltips' ).'<span class="questioncontactform7">?</span>';
										?>
										<?php
										$admin_tip = __('Enable tooltips effects for contact form fields in contact form 7 plugin.', "wordpress-tooltips");
										?>
										<script type="text/javascript"> 
										jQuery(document).ready(function () {
										  jQuery("span.questioncontactform7").hover(function () {
										    jQuery(this).append('<div class="tooltip6"><p><?php echo $admin_tip; ?></p></div>');
										  }, function () {
										    jQuery("div.tooltip6").remove();
										  });
										});
										</script>
										</td>
										<td width="30%"  style="text-align:left;">
										<select id="enableTooltipsForContactForm7" name="enableTooltipsForContactForm7" style="width:300px;">
										<option id="enableTooltipsForContactForm7Option" value="YES" <?php if ($enableTooltipsForContactForm7 == 'YES') echo "selected";   ?>> <?php echo __('Enable Tooltips for Contact Form 7', "wordpress-tooltips");?> </option>
										<option id="enableTooltipsForContactForm7Option" value="NO" <?php if ($enableTooltipsForContactForm7 == 'NO') echo "selected";   ?>>   <?php echo __('Disable Tooltips for Contact Form 7', "wordpress-tooltips");?> </option>
										</select>
										</td>
										<td width="30%"  style="text-align:left;">
										<input type="submit" class="button-primary" id="enableTooltipsForContactForm7Submit" name="enableTooltipsForContactForm7Submit" value=" <?php echo __('Update Now', "wordpress-tooltips");?> ">
										</td>
										</tr>

										</table>
										</form>
										
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
//!!!end
?>
		<a class=""  target="_blank" href="https://paypal.me/sunpayment">
		<span>
		Buy me a coffee 								
		</span>
		</a>
		?
		<span style="margin-right:20px;">
		Thank you :)
		</span>		
<?php
}

tooltipFreeAddonSettings();