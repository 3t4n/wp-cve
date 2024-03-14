<?php
/**
 * Social media icons for walker core
 *
 * @package walker_core
 * @since version 1.0.0
 */
$theme = wp_get_theme();
if ('Gridchamp' == $theme->name || 'Gridchamp' == $theme->parent_theme):?>
	<ul class="walker-core-social">
	<?php if(get_theme_mod('gridchamp_facebook')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_facebook'));?>" target="_blank">
				<i class="fa fa-facebook" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_twitter')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_twitter'));?>" target="_blank">
				<i class="fa fa-twitter" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_youtube')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_youtube'));?>" target="_blank">
				<i class="fa fa-youtube-play" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_instagram')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_instagram'));?>" target="_blank">
				<i class="fa fa-instagram" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_linkedin')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_linkedin'));?>" target="_blank">
				<i class="fa fa-linkedin" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_google')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_google'));?>" target="_blank">
				<i class="fa fa-google" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_pinterest')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_pinterest'));?>" target="_blank">
				<i class="fa fa-pinterest-p" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_github')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_github'));?>" target="_blank">
				<i class="fa fa-github" aria-hidden="true"></i>
			</a>
		</li>
	<?php }	
	if(get_theme_mod('gridchamp_yelp')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_yelp'));?>" target="_blank">
				<i class="fa fa-yelp" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_angellist')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_angellist'));?>" target="_blank">
				<i class="fa fa-angellist" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_reddit')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_reddit'));?>" target="_blank">
				<i class="fa fa-reddit-alien" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_flickr')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_flickr'));?>" target="_blank">
				<i class="fa fa-flickr" aria-hidden="true"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('gridchamp_dribble')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('gridchamp_dribble'));?>" target="_blank">
				<i class="fa fa-dribbble" aria-hidden="true"></i>
			</a>
		</li>
	<?php }?>
</ul>

<?php endif;
?>