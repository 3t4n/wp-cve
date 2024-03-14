<?php

defined( 'ABSPATH' ) or die(':)');


function video_popup_shortcode_callback(){
	?>
		<div class="wrap">

			<?php if( !get_option('vp_green_bg_menu') ) : ?>
				<?php update_option('vp_green_bg_menu', 'true'); ?>
				<style type="text/css">
					body a.toplevel_page_video_popup_general_settings{
    					background: #0073aa !important;
					}
				</style>
			<?php endif; ?>


			<h1 style="margin-bottom: 20px !important;"><span><?php _e('Video PopUp Shortcode', 'video-popup'); ?></span></h1>

			<div class="vp-clear-fix">
				<div class="vp-left-col">
					

				<div class="postbox">
					<h2 style="border-bottom: 1px solid #eee !important;padding: 12px !important;margin: 0 !important;"><span><?php _e('Shortcode Usage', 'video-popup'); ?></span></h2>
					<div class="inside" style="padding: 12px !important;margin: 0 !important;">

						<div class="main">

							<p style="margin: 0 !important;"><?php _e('Default Usage:', 'video-popup'); ?> <strong style="background-color: #ddd;">[video_popup url="" text=""]</strong></p>

						</div>

					</div>
				</div>


				<div class="postbox">
					<h2 style="border-bottom: 1px solid #eee !important;padding: 12px !important;margin: 0 !important;"><span><?php _e('Shortcode Attributes', 'video-popup'); ?></span></h2>
					<div class="inside" style="padding: 12px !important;margin: 0 !important;">

						<div class="main">

							<p style="margin: 0 !important;"><?php _e('Full Shortcode:', 'video-popup'); ?> <strong style="background-color: #ddd;">[video_popup url="" text="" title="" auto="" n="" p="" wrap="" rv="" w="" h="" co="" dc="" di="" img="" iv=""]</strong></p>

							<p><strong style="background-color: #ddd;">url=""</strong> <?php _e('enter YouTube, Vimeo, SoundCloud, or MP4 Video link.', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">text=""</strong> <?php _e('enter your text.', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">title=""</strong> <?php _e('enter your title.', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">auto=""</strong> <?php _e('enter "1" for no Autoplay, default is Autoplay.', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">n=""</strong> <?php _e('enter "1" for rel nofollow.', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">p=""</strong> <?php _e('enter "1" to adding the video link inside a paragraph.', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">wrap=""</strong> <?php _e('enter "1" to removing the white border.', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">img=""</strong> <?php _e('enter image URL to display it as thumbnail.', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">rv=""</strong> <?php _e('enter "1" to disable YouTube Related videos (changed*), for YouTube only (in the Premium Extension only).', 'video-popup'); ?> <strong><a href="https://developers.google.com/youtube/player_parameters#release_notes_08_23_2018" target="_blank"><?php _e('Changed*: Please read this announcement from YouTube.', 'video-popup'); ?></a></strong></p>

							<p><strong style="background-color: #ddd;">w=""</strong> <?php _e('enter width size for the video (in the Premium Extension only).', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">h=""</strong> <?php _e('enter height size for the video (in the Premium Extension only).', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">co=""</strong> <?php _e('enter the color of overlay, enter full HEX code only, for example #ffffff. (in the Premium Extension only).', 'video-popup'); ?></p>

							<p><strong style="background-color: #ddd;">dc=""</strong> <?php _e('enter "1" to disable YouTube player controls, for YouTube only (in the Premium Extension only).', 'video-popup'); ?></p>

							<p style="margin-bottom: 0 !important;"><strong style="background-color: #ddd;">iv=""</strong> <?php _e('enter "1" to disable video annotations, for YouTube only (in the Premium Extension only).', 'video-popup'); ?></p>

						</div>

					</div>
				</div>


			</div>


			<div class="vp-right-col">
				
				<div class="postbox vp-no-premium-ext">
                    <h2 style="border-bottom: 1px solid #eee !important;padding: 12px !important;margin: 0 !important;"><span><?php _e('Get The Premium Extension!', 'video-popup'); ?></span></h2>
                    <div class="inside" style="padding: 12px !important;margin: 0 !important;">

                        <div class="main">

                            <p style="margin: 0 !important;"><?php _e("Get it at a low price! Unlock all the features. Easy to use, download it, install it, activate it, and enjoy! Get it now!", 'video-popup'); ?></p>

                            <p style="margin-bottom: 0 !important;"><a href="https://wp-plugins.in/Get-VP-Premium-Extension" class="vp-settings-btn vp-get-premium-su" target="_blank"><?php _e('Get The Premium Extension', 'video-popup'); ?></a></p>

                        </div>

                    </div>
                </div>


                <div class="postbox">
                    <h2 style="border-bottom: 1px solid #eee !important;padding: 12px !important;margin: 0 !important;"><span><?php _e('Explanation of Use', 'video-popup'); ?></span></h2>
                    <div class="inside" style="padding: 12px !important;margin: 0 !important;">

                        <div class="main">

                            <p style="margin: 0 !important;"><?php _e('Need help? Support? Questions? Read the Explanation of Use.', 'video-popup'); ?></p>

                            <p style="margin-bottom: 0 !important;"><a href="https://wp-plugins.in/VideoPopUp-Usage" class="vp-settings-btn vp-read-expofuse-su" target="_blank"><?php _e('Explanation of Use', 'video-popup'); ?></a></p>

                        </div>

                    </div>
                </div>


			</div>


			</div>

		</div>
	<?php
}