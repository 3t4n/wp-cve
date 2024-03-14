<?php
/**
 * Social media icons for Walker_Charity
 *
 * @package Walker_Charity
 * @since version 1.0.0
 */

$theme = wp_get_theme();
if ('Walker Charity' == $theme->name || 'Walker Charity' == $theme->parent_theme):?>
<ul class="walker-charity-social">
	<?php if(get_theme_mod('walker_charity_facebook')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walker_charity_facebook'));?>" target="_blank">
				<i class="fab fa-facebook-f"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walker_charity_twitter')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walker_charity_twitter'));?>" target="_blank">
				<i class="fab fa-twitter"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walker_charity_youtube')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walker_charity_youtube'));?>" target="_blank">
				<i class="fab fa-youtube"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walker_charity_instagram')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walker_charity_instagram'));?>" target="_blank">
				<i class="fab fa-instagram"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walker_charity_linkedin')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walker_charity_linkedin'));?>" target="_blank">
				<i class="fab fa-linkedin-in"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walker_charity_google')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walker_charity_google'));?>" target="_blank">
				<i class="fab fa-google"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walker_charity_pinterest')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walker_charity_pinterest'));?>" target="_blank">
				<i class="fab fa-pinterest-p"></i>
			</a>
		</li>
	<?php }
	if(get_theme_mod('walker_charity_vk')){?>
		<li>
			<a href="<?php echo esc_url(get_theme_mod('walker_charity_vk'));?>" target="_blank">
				<i class="fab fa-vk"></i>
			</a>
		</li>
	<?php }?>
</ul>
<?php endif; ?>