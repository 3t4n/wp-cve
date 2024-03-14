<?php
/**
 * Social media icons for WalkerMag
 *
 * @package WalkerMag
 * @since version 1.0.0
 */

$theme = wp_get_theme();
if ('WalkerMag' == $theme->name || 'WalkerMag' == $theme->parent_theme):?>
<ul class="walkermag-social">
	<?php if(get_theme_mod('walkermag_facebook')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walkermag_facebook'));?>" target="_blank">
				<i class="fa fa-facebook" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walkermag_twitter')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walkermag_twitter'));?>" target="_blank">
				<i class="fa fa-twitter" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walkermag_youtube')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walkermag_youtube'));?>" target="_blank">
				<i class="fa fa-youtube-play" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walkermag_instagram')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walkermag_instagram'));?>" target="_blank">
				<i class="fa fa-instagram" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walkermag_linkedin')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walkermag_linkedin'));?>" target="_blank">
				<i class="fa fa-linkedin" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walkermag_google')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walkermag_google'));?>" target="_blank">
				<i class="fa fa-google" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walkermag_pinterest')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walkermag_pinterest'));?>" target="_blank">
				<i class="fa fa-pinterest-p" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walkermag_vk')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walkermag_vk'));?>" target="_blank">
				<i class="fa fa-vk" aria-hidden="true"></i></i>
			</a>
		</li>
	<?php }	?>
</ul>
<?php endif;