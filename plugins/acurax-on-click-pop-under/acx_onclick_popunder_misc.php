<?php 
if(ISSET($_POST['acx_onclick_popunder_misc_hidden']))
{
	$acx_onclick_popunder_misc_hidden = $_POST['acx_onclick_popunder_misc_hidden'];
} 
else
{
	$acx_onclick_popunder_misc_hidden = "";
}
if($acx_onclick_popunder_misc_hidden == 'Y') 
{	//Form data sent
	if (!isset($_POST['acx_onclick_popunder_misc'])) die("<br><br>Unknown Error Occurred, Try Again... <a href=''>Click Here</a>");
	if (!wp_verify_nonce($_POST['acx_onclick_popunder_misc'],'acx_onclick_popunder_misc')) die("<br><br>Unknown Error Occurred, Try Again... <a href=''>Click Here</a>");
	if(!current_user_can('manage_options')) die("<br><br>Sorry, You have no permission to do this action...</a>");

	$acx_onclick_popunder_service_banners = sanitize_text_field($_POST['acx_onclick_popunder_service_banners']);
	update_option('acurax_popunder_service_banners', $acx_onclick_popunder_service_banners);
	$acx_onclick_popunder_premium_ad=sanitize_text_field($_POST['acx_onclick_popunder_premium_ad']);
	update_option('acurax_popunder_premium_ad',$acx_onclick_popunder_premium_ad);
	?>
	<div class="updated"><p><strong><?php _e('Acurax Onclick Popunder Settings Saved!.' ); ?></strong></p></div>
	<?php
}
else
{	//Normal page display
	$acx_onclick_popunder_service_banners = get_option('acurax_popunder_service_banners');
	$acx_onclick_popunder_premium_ad = get_option('acurax_popunder_premium_ad');
	// Setting Defaults
	if ($acx_onclick_popunder_service_banners == "") {	$acx_onclick_popunder_service_banners = "yes"; }
	if ($acx_onclick_popunder_premium_ad == "") {	$acx_onclick_popunder_premium_ad = "yes"; }
} //Main else
?>
<div class="wrap">
<div style='background: none repeat scroll 0% 0% white; height: 100%; display: inline-block; padding: 8px; margin-top: 5px; border-radius: 15px; min-height: 450px; width: 100%;'>
<?php
$acx_onclick_popunder_service_banners = get_option('acurax_popunder_service_banners');
if ($acx_onclick_popunder_service_banners != "no"){?>
<div id="acx_ad_banners_onclick_popunder">
<a href="http://www.acurax.com/services/wordpress-designing-experts.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ocpu" target="_blank" class="acx_ad_onclick_popunder_1">
<div class="acx_ad_onclick_popunder_title">Need Help on Wordpress?</div> <!-- acx_ad_onclick_popunder_title -->
<div class="acx_ad_onclick_popunder_desc">Expert Support at Your Fingertip</div> <!-- acx_ad_onclick_popunder_desc -->
</a> <!--  acx_ad_onclick_popunder_1 -->

<a href="http://www.acurax.com/services/website-redesign.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ocpu" target="_blank" class="acx_ad_onclick_popunder_1">
<div class="acx_ad_onclick_popunder_title">Needs a Better Designed Website?</div> <!-- acx_ad_onclick_popunder_title -->
<div class="acx_ad_onclick_popunder_desc acx_ad_onclick_popunder_desc2" style="padding-top: 4px; height: 41px; font-size: 13px; text-align: center;">Get High Converting Website - 100% Satisfaction Guaranteed</div> <!-- acx_ad_onclick_popunder_desc -->
</a> <!--  acx_ad_onclick_popunder_1 -->

<a href="http://www.acurax.com/services/website-redesign.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ocpu" target="_blank" class="acx_ad_onclick_popunder_1">
<div class="acx_ad_onclick_popunder_title">Need More Business?</div> <!-- acx_ad_onclick_popunder_title -->
<div class="acx_ad_onclick_popunder_desc acx_ad_onclick_popunder_desc3" style="padding-top: 13px; height: 32px; font-size: 13px; text-align: center;">Get Your Website Optimized</div> <!-- acx_ad_onclick_popunder_desc -->
</a> <!--  acx_ad_onclick_popunder_1 -->

<a href="http://www.acurax.com/services/wordpress-designing-experts.php?utm_source=plugin-page&utm_medium=banner&utm_campaign=ocpu" target="_blank" class="acx_ad_onclick_popunder_1">
<div class="acx_ad_onclick_popunder_title">Quick Support</div> <!-- acx_ad_onclick_popunder_title -->
<div class="acx_ad_onclick_popunder_desc acx_ad_onclick_popunder_desc4" style="padding-top: 4px; height: 41px; font-size: 13px; text-align: center;">Get Explanation & Fix on Website Issues Instantly</div> <!-- acx_ad_onclick_popunder_desc -->
</a> <!--  acx_ad_onclick_popunder_1 -->
</div> <!--  acx_ad_banners_onclick_popunder -->
<?php } else { ?>
<p class="widefat" style="padding:8px;width:99%;">
<b>Acurax Services >> </b>
<a href="http://www.acurax.com/services/wordpress-designing-experts.php?utm_source=plugin-page&utm_medium=banner_link&utm_campaign=ocpu" target="_blank">Need Help on Wordpress?</a> | 
<a href="http://www.acurax.com/services/website-redesign.php?utm_source=plugin-page&utm_medium=banner_link&utm_campaign=ocpu" target="_blank">Needs a Better Designed Website?</a> | 
<a href="http://www.acurax.com/services/website-redesign.php?utm_source=plugin-page&utm_medium=banner_link&utm_campaign=ocpu" target="_blank">Need More Business?</a> | 
<a href="http://www.acurax.com/services/wordpress-designing-experts.php?utm_source=plugin-page&utm_medium=banner_link&utm_campaign=ocpu" target="_blank">Quick Support</a>
</p>
<?php } 
if($acx_onclick_popunder_premium_ad != "no")
{?>
<div id="acx_onclick_popunder_premium">
<a style="margin: 10px 0px 0px 10px; font-weight: bold; font-size: 14px; display: block;" href="#compare">Fully Featured - Premium Onclick Popunder is Available With Tons of Extra Features! - Click Here</a>
</div> <!-- acx_fsmi_premium -->
<?php
}
 echo "<h2>" . __( 'Coming Soon/Maintenance From Acurax Misc Settings', 'acx_popunder_config' ) . "</h2>"; ?>
<form name="acurax_onclick_popunder_misc_form" method="post" action="<?php echo esc_url(str_replace( '%7E', '~', $_SERVER['REQUEST_URI'])); ?>">
<input type="hidden" name="acx_onclick_popunder_misc_hidden" value="Y">
<p class="widefat" style="padding:8px;width:99%;margin-top:8px;">	<?php _e("Acurax Service Banners: " ); ?>
<select name="acx_onclick_popunder_service_banners">
<option value="yes"<?php if ($acx_onclick_popunder_service_banners == "yes") { echo 'selected="selected"'; } ?>>Yes, Show Them </option>
<option value="no"<?php if ($acx_onclick_popunder_service_banners == "no") { echo 'selected="selected"'; } ?>>No, Hide Them </option>
</select>
<?php _e("Show Acurax Service Banners On Plugin Settings Page?" ); ?>
</p>
<p class="widefat" style="padding:8px;width:99%;margin-top:8px;">	<?php _e("Hide Premium Version ads : " ); ?>
<select name="acx_onclick_popunder_premium_ad">
<option value="yes"<?php if ($acx_onclick_popunder_premium_ad == "yes") { echo 'selected="selected"'; } ?>>Yes, Show Them </option>
<option value="no"<?php if ($acx_onclick_popunder_premium_ad == "no") { echo 'selected="selected"'; } ?>>No, Hide Them </option>
</select>
</p>
<p class="submit">
<input type="submit" name="Submit" class="button" value="<?php _e('Save Settings', 'acx_popunder_config' ) ?>" />
</p>
<input name="acx_onclick_popunder_misc" type="hidden" value="<?php echo wp_create_nonce('acx_onclick_popunder_misc'); ?>" />
</form>
<br>
<hr />
<?php
$acx_onclick_popunder_premium_ad = get_option('acurax_popunder_premium_ad');
if ($acx_onclick_popunder_premium_ad != "no"){ 
acx_onclick_popunder_comparison(1);
}
?>
<br>
<p class="widefat" style="padding:8px;width:99%;">
Something Not Working Well? Have a Doubt? Have a Suggestion? - <a href="http://www.acurax.com/contact.php" target="_blank">Contact us now</a> | Need a Custom Designed Theme For your Blog or Website? Need a Custom Header Image? - <a href="http://www.acurax.com/contact.php" target="_blank">Contact us now</a>
</p>
</div>
</div>