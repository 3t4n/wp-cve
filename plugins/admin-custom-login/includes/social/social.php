<!-- Dashboard Settings panel content --->
<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="row">
	<div class="post-social-wrapper clearfix">
		<div class="col-md-12 post-social-item">
			<div class="panel panel-default">
				<div class="panel-heading padding-none">
					<div class="post-social post-social-xs" id="post-social-5">
						<div class="text-center padding-all text-center">
							<div class="textbox text-white   margin-bottom settings-title">
								<?php esc_html_e('Social Settings',WEBLIZAR_ACL)?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Enable Social Icons',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<select id="enable_social_icon" class="standard-dropdown" name="enable_social_icon">
							<option value="no-icon" ><?php esc_html_e('No Icon',WEBLIZAR_ACL)?></option>
							<option value="inner" ><?php esc_html_e('Inner',WEBLIZAR_ACL)?></option>
							<option value="outer" ><?php esc_html_e('Outer',WEBLIZAR_ACL)?></option>
							<option value="both" ><?php esc_html_e('Both',WEBLIZAR_ACL)?></option>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Social Media Icon Size',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<span>
							<input type="radio" name="social_size" value="small" id="social_size1" <?php if($social_icon_size=="small")echo esc_attr("checked"); ?> />&nbsp;<label for="social_size1"><?php esc_html_e('Small',WEBLIZAR_ACL)?></label><br>
						</span>
						<span>
							<input type="radio" name="social_size" value="mediam" id="social_size2" <?php if($social_icon_size=="mediam")echo esc_attr("checked"); ?> />&nbsp;<label for="social_size2"><?php esc_html_e('Medium',WEBLIZAR_ACL)?></label><br>
						</span>
						<span>
							<input type="radio" name="social_size" value="large" id="social_size3"  <?php if($social_icon_size=="large")echo esc_attr("checked"); ?> />&nbsp;<label for="social_size3"><?php esc_html_e('Large',WEBLIZAR_ACL)?></label><br>
						</span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Social Media Icon Layout',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<span>
							<input type="radio" name="social_layout" value="rectangle" id="social_layout1" <?php if($social_icon_layout=="rectangle")echo esc_attr("checked"); ?>  />&nbsp;<label for="social_layout1"><?php esc_html_e('Rectangle',WEBLIZAR_ACL)?></label><br>
						</span>
						<span>
							<input type="radio" name="social_layout" value="circle" id="social_layout2" <?php if($social_icon_layout=="circle")echo esc_attr("checked"); ?>  />&nbsp;<label for="social_layout2"><?php esc_html_e('Circle',WEBLIZAR_ACL)?></label><br>
						</span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Social Media Icon Color',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td>
						<input id="social-icon-color" name="background-color" type="text" value="<?php echo esc_attr($social_icon_color); ?>" class="my-color-field" data-default-color="#ffffff"/>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Social Media Icon Color On Hover',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td>
						<input id="social-icon-color-onhover" name="background-color" type="text" value="<?php echo esc_attr($social_icon_color_onhover); ?>" class="my-color-field" data-default-color="#ffffff"/>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Social Media Icon Background Color',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td>
						<input id="social-bg-color" name="background-color" type="text" value="<?php echo esc_attr($social_icon_bg); ?>" class="my-color-field" data-default-color="#ffffff" />
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Social Media Background Color On Hover',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td>
						<input id="social-bg-color-onhover" name="background-color" type="text" value="<?php echo esc_attr($social_icon_bg_onhover); ?>" class="my-color-field" data-default-color="#ffffff"/>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Enable To Open Social Link In New Window', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<?php 
		                    $Social_page = unserialize(get_option('Admin_custome_login_Social'));
		                    $social_link_new_window = @$Social_page['social_link_new_window'];
		                    ?>
						<span>
							<input type="radio" name="social_link_new_window" value="yes" id="social_link_new_window1" <?php if($social_link_new_window=="yes")echo esc_attr("checked"); ?> <?php if(empty($social_link_new_window))echo esc_attr("checked"); ?> />&nbsp;<label for="social_link_new_window1"><?php esc_html_e('Yes', WEBLIZAR_ACL)?></label><br>
						</span>
						<span>
							<input type="radio" name="social_link_new_window" value="no" id="social_link_new_window2" <?php if($social_link_new_window=="no")echo esc_attr("checked"); ?> />&nbsp;<label for="social_link_new_window2"><?php esc_html_e('No', WEBLIZAR_ACL)?></label><br>
						</span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Social Profiles',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<p class="rcsp_p_admin"><?php esc_html_e('Enter your social profiles complete url here',WEBLIZAR_ACL)?></p>
						<ul class="rcp_social_profile_admin">
							<li><i class="fab fa-facebook-f"></i><input type="text" class="pro_text" id="facebook-link" name="facebook-link" placeholder="<?php esc_attr_e('Facebook', WEBLIZAR_ACL)?>" size="56" value="<?php echo esc_attr($social_facebook_link); ?>" /></li>
							<li><i class="fab fa-twitter"></i><input type="text" class="pro_text" id="twitter-link" name="twitter-link" placeholder="<?php esc_attr_e('Twitter', WEBLIZAR_ACL)?>" size="56" value="<?php echo esc_attr($social_twitter_link); ?>" /></li>
							<li><i class="fab fa-linkedin-in"></i><input type="text" class="pro_text" id="linkedin-link" name="linkedin-link" placeholder="<?php esc_attr_e('Linkedin', WEBLIZAR_ACL); ?>" size="56" value="<?php echo esc_attr($social_linkedin_link); ?>" /></li>
							<li><i class="fab fa-pinterest-p"></i><input type="text" class="pro_text" id="pinterest-link" name="pinterest-link" placeholder="<?php esc_attr_e('Pinterest', WEBLIZAR_ACL); ?>" size="56" value="<?php echo esc_attr($social_pinterest_link); ?>" /></li>
							<li><i class="fab fa-digg"></i><input type="text" class="pro_text" id="digg-link" name="digg-link" placeholder="<?php esc_attr_e('Digg', WEBLIZAR_ACL)?>" size="56" value="<?php echo esc_attr($social_digg_link); ?>" /></li>
							<li><i class="fab fa-youtube-square"></i><input type="text" class="pro_text" id="youtube-link" name="youtube-link" placeholder="<?php esc_attr_e('Youtube', WEBLIZAR_ACL)?>" size="56" value="<?php echo esc_attr($social_youtube_link); ?>" /></li>
							<li><i class="fab fa-flickr"></i><input type="text" class="pro_text" id="flickr-link" name="flickr-link" placeholder="<?php esc_attr_e('Flickr', WEBLIZAR_ACL)?>" size="56" value="<?php echo esc_attr($social_flickr_link); ?>" /></li>
							<li><i class="fab fa-tumblr"></i><input type="text" class="pro_text" id="tumblr-link" name="tumblr-link" placeholder="<?php esc_attr_e('Tumblr', WEBLIZAR_ACL)?>" size="56" value="<?php echo esc_attr($social_tumblr_link); ?>" /></li>
							<li><i class="fab fa-skype"></i><input type="text" class="pro_text" id="skype-link" name="skype-link" placeholder="<?php esc_attr_e('Skype', WEBLIZAR_ACL)?>" size="56" value="<?php echo esc_attr($social_skype_link); ?>" /></li> 
							<li><i class="fab fa-instagram"></i><input type="text" class="pro_text" id="instagram-link" name="instagram-link" placeholder="<?php esc_attr_e('Instagram', WEBLIZAR_ACL)?>" size="56" value="<?php echo esc_attr($social_instagram_link); ?>" /></li>
							<li><i class="fab fa-telegram-plane"></i><input type="text" class="pro_text" id="telegram-link" name="telegram-link" placeholder="<?php esc_attr_e('Telegram', WEBLIZAR_ACL)?>" size="56" value="<?php echo esc_attr($social_telegram_link); ?>" /></li>
							<li><i class="fab fa-whatsapp"></i><input type="text" class="pro_text" id="whatsapp-link" name="whatsapp-link" placeholder="<?php esc_attr_e('Whatsapp', WEBLIZAR_ACL)?>" size="56" value="<?php echo esc_attr($social_whatsapp_link); ?>" /></li>
						</ul>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<button data-dialog5="somedialog5" class="dialog-button5" style="display:none"><?php esc_html_e('Open Dialog', WEBLIZAR_ACL)?></button>
	<div id="somedialog5" class="dialog" style="position: fixed; z-index: 9999;">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" data-morph-open="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e('Social', WEBLIZAR_ACL)?></strong> <?php esc_html_e('Setting Save Successfully',WEBLIZAR_ACL)?></h2><div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button5"><?php esc_html_e('Close',WEBLIZAR_ACL)?></button></div>
			</div>
		</div>
	</div>
	<button data-dialog6="somedialog6" class="dialog-button6" style="display:none"><?php esc_html_e('Open Dialog', WEBLIZAR_ACL)?></button>
	<div id="somedialog6" class="dialog" style="position: fixed; z-index: 9999;">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" data-morph-open="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e('Social',WEBLIZAR_ACL)?></strong> <?php esc_html_e('Setting Reset Successfully',WEBLIZAR_ACL)?></h2><div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button6"><?php esc_html_e('Close',WEBLIZAR_ACL)?></button></div>
			</div>
		</div>
	</div>
	<div class="panel panel-primary save-button-block">
		<div class="panel-body">
				<button type="button" onclick="return Custom_login_social('socialSave', '');" class="btn btn-info btn-lg"><?php esc_html_e('Save Changes',WEBLIZAR_ACL)?></button>
				<button type="button" onclick="return Custom_login_social('socialReset', '');" class="btn btn-primary btn-lg"><?php esc_html_e('Reset Default',WEBLIZAR_ACL)?></button>
		</div>
	</div>
</div>
<!-- /row -->
<?php
if(isset($_POST['Action'])) {
	$Action = sanitize_text_field($_POST['Action']);
	if( ! wp_verify_nonce( $_POST['nonce_ajax'], 'weblizar_admin_nonce' ) ) {
		die('Not authorized');
	}
	else {
		//Save
		if($Action == "socialSave") {

			$enable_social_icon 	   = sanitize_text_field($_POST['enable_social_icon']);
			$social_icon_size 		   = sanitize_text_field($_POST['social_icon_size']);
			$social_icon_layout        = sanitize_text_field($_POST['social_icon_layout']);
			$social_link_new_window    = sanitize_text_field($_POST['social_link_new_window']);
			$social_icon_color 		   = sanitize_hex_color($_POST['social_icon_color']);
			$social_icon_color_onhover = sanitize_text_field($_POST['social_icon_color_onhover']);
			$social_icon_bg 		   = sanitize_text_field($_POST['social_icon_bg']);
			$social_icon_bg_onhover    = sanitize_text_field($_POST['social_icon_bg_onhover']);

			
			
			$social_facebook_link 	 = sanitize_url($_POST['social_facebook_link']);
			$social_twitter_link 	 = sanitize_url($_POST['social_twitter_link']);
			$social_linkedin_link    = sanitize_url($_POST['social_linkedin_link']);
			$social_pinterest_link   = sanitize_url($_POST['social_pinterest_link']);
			$social_digg_link 		 = sanitize_url($_POST['social_digg_link']);
			$social_youtube_link 	 = sanitize_url($_POST['social_youtube_link']);
			$social_flickr_link 	 = sanitize_url($_POST['social_flickr_link']);
			$social_tumblr_link 	 = sanitize_url($_POST['social_tumblr_link']);
			$social_skype_link       = sanitize_url($_POST['social_skype_link']);
			$social_instagram_link   = sanitize_url($_POST['social_instagram_link']);
			$social_telegram_link    = sanitize_url($_POST['social_telegram_link']);
			$social_whatsapp_link    = sanitize_url($_POST['social_whatsapp_link']);
			
			$Social_page= serialize(array(
				'enable_social_icon'        => $enable_social_icon,
				'social_icon_size'          => $social_icon_size,
				'social_icon_layout'        => $social_icon_layout,
				'social_link_new_window'    => $social_link_new_window,
				'social_icon_color'         => $social_icon_color,
				'social_icon_color_onhover' => $social_icon_color_onhover,
				'social_icon_bg'            => $social_icon_bg,
				'social_icon_bg_onhover'    => $social_icon_bg_onhover,
				'social_facebook_link'      => $social_facebook_link,
				'social_twitter_link'       => $social_twitter_link,
				'social_linkedin_link'      => $social_linkedin_link,
				'social_pinterest_link'     => $social_pinterest_link,
				'social_digg_link'          => $social_digg_link,
				'social_youtube_link'       => $social_youtube_link,
				'social_flickr_link'        => $social_flickr_link,
				'social_tumblr_link'        => $social_tumblr_link,
				'social_skype_link'         => $social_skype_link,
				'social_instagram_link'     => $social_instagram_link,
				'social_telegram_link'      => $social_telegram_link,
				'social_whatsapp_link'      => $social_whatsapp_link,
			));
			update_option('Admin_custome_login_Social', $Social_page);
		}		
		
		if($Action == "socialReset") {
			$Social_page= serialize(array(
				'enable_social_icon'=> 'outer' ,
				'social_icon_size'=> 'mediam' ,
				'social_icon_layout'=> 'rectangle' ,
				'social_link_new_window'=> 'yes' ,
				'social_icon_color'=> '#ffffff' ,
				'social_icon_color_onhover'=> '#1e73be' ,
				'social_icon_bg'=> '#1e73be',
				'social_icon_bg_onhover'=> '#ffffff' ,
				'social_facebook_link'=> 'http://facebook.com' ,
				'social_twitter_link'=> 'https://twitter.com/',
				'social_linkedin_link'=> '' ,
				'social_pinterest_link'=> '',
				'social_digg_link'=> '',
				'social_youtube_link'=> 'https://youtube.com/',
				'social_flickr_link'=> 'https://flickr.com/',
				'social_tumblr_link'=> '',
				'social_skype_link'=> '',
				'social_instagram_link'=> 'https://instagram.com/',
				'social_telegram_link'=> 'https://telegram.org/',
				'social_whatsapp_link'=> 'https://whatsapp.com/',
			));
			update_option('Admin_custome_login_Social', $Social_page);
		}
	}		
}