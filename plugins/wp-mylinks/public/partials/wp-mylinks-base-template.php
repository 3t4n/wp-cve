<?php

/**
 * The base template for the MyLinks page.
 *
 * @link       https://walterpinem.me/
 * @since      1.0.0
 *
 * @package    Wp_Mylinks
 * @subpackage Wp_Mylinks/public
 * @author     Walter Pinem <hello@walterpinem.me>
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
	<?php if (in_array('wordpress-seo/wp-seo.php' || 'wordpress-seo-premium/wp-seo-premium.php', apply_filters('active_plugins', get_option('active_plugins')))) :
		if ($meta_title = get_post_meta($post->ID, '_yoast_wpseo_title', true));
		elseif ($meta_title = get_post_meta(get_the_ID(), mylinks_prefix('meta-title'), true));
		elseif ($meta_title = get_option(sanitize_text_field('mylinks_meta_title')));
		else $meta_title = esc_html(get_the_title());
		if ($meta_description = get_post_meta($post->ID, '_yoast_wpseo_metadesc', true));
		elseif ($meta_description = get_post_meta(get_the_ID(), mylinks_prefix('meta-description'), true));
		else $meta_description = get_option(sanitize_text_field('mylinks_meta_description'));
	?>
		<?php
		if ($set_noindex = get_post_meta(get_the_ID(), mylinks_prefix('noindex'), true));
		else $set_noindex = get_option(sanitize_text_field('wp_mylinks_noindex'));
		if ($set_nofollow = get_post_meta(get_the_ID(), mylinks_prefix('nofollow'), true));
		else $set_nofollow = get_option(sanitize_text_field('wp_mylinks_nofollow'));
		?>
		<?php
		if ($set_noindex === "yes") {
			$noindex = "noindex";
		} else {
			$noindex = "index";
		}
		if ($set_nofollow === "yes") {
			$nofollow = "nofollow";
		} else {
			$nofollow = "follow";
		}
		?>
		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php echo esc_url(get_bloginfo('pingback_url')); ?>">
		<!-- Start Favicon -->
		<?php $single_favicon = get_post_meta(get_the_ID(), mylinks_prefix('single-favicon'), true); ?>
		<?php $global_favicon = get_option('mylinks_upload_favicon'); ?>
		<?php if (empty($single_favicon)) : ?>
			<link rel="icon" type="image/png" href="<?php echo esc_html($global_favicon); ?>">
		<?php else : ?>
			<link rel="icon" type="image/png" href="<?php echo esc_html($single_favicon); ?>">
		<?php endif; ?>
		<!-- End Favicon -->
		<title><?php echo $meta_title; ?></title>
		<meta name="description" content="<?php echo $meta_description; ?>">
		<meta name="robots" content="<?php echo $noindex ?>, <?php echo $nofollow ?>" />
		<meta name="googlebot" content="<?php echo $noindex ?>, <?php echo $nofollow ?>, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
		<meta name="bingbot" content="<?php echo $noindex ?>, <?php echo $nofollow ?>, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
		<?php
		wp_enqueue_style('mylinks-public-css');
		wp_styles()->do_item('mylinks-public-css');
		wp_enqueue_style('mylinks-youtube-css');
		wp_styles()->do_item('mylinks-youtube-css');
		$play_icon_path = esc_url(plugins_url('/public/images/play.png', dirname(__DIR__)));
		echo '<style type="text/css">.youtube-player .play{ background: url(' . $play_icon_path . ') no-repeat;}</style>';
		?>
	<?php endif; ?>
	<?php $analytics_script = get_option(sanitize_text_field('wp_mylinks_analytics'));
	echo $analytics_script;
	if ($header_script = get_post_meta(get_the_ID(), mylinks_prefix('mylinks-single-custom-header-script'), true));
	else $header_script = get_option(sanitize_text_field('wp_mylinks_header_script'));
	echo $header_script;
	?>
	<?php
	if ($custom_css = get_post_meta(get_the_ID(), mylinks_prefix('mylinks-single-custom-styles'), true));
	else $custom_css = get_option(sanitize_text_field('wp_mylinks_custom_css'));
	if ($custom_css == '') {
		echo '';
	} else {
		echo '<style type="text/css">' . $custom_css . '</style>';
	}
	?>
	<?php
	$avatar_style = get_post_meta(get_the_ID(), mylinks_prefix('avatar-style'), true);
	if ($avatar_style === "shadow") {
		echo '<style type="text/css">.mylinks .avatar{box-shadow:0 1px 2px rgba(0,0,0,.1),0 2px 4px rgba(0,0,0,.1),0 4px 8px rgba(0,0,0,.1),0 8px 16px rgba(0,0,0,.1),0 16px 32px rgba(0,0,0,.1),0 32px 64px rgba(0,0,0,.1)}</style>';
	} elseif ($avatar_style === "plain") {
		echo '<style type="text/css">.mylinks .avatar{background:transparent}</style>';
	} else {
		echo '<style type="text/css">.mylinks .avatar{background:#fdf497;background:radial-gradient(circle at 30% 107%,#fdf497 0,#fdf497 5%,#fd5949 45%,#d6249f 60%,#8a3fb6 90%);background:radial-gradient(circle at 30% 107%,#fdf497 0,#fdf497 5%,#fd5949 45%,#d6249f 60%,#8a3fb6 90%);background:radial-gradient(circle at 30% 107%,#fdf497 0,#fdf497 5%,#fd5949 45%,#d6249f 60%,#8a3fb6 90%)}</style>';
	}
	?>
</head>
<?php $theme_options =  get_option('mylinks_theme'); ?>
<?php $theme_value =  $theme_options; ?>
<?php
$metafield_id = get_the_ID();
$options = wp_mylinks_theme_callback();
$key = get_post_meta($metafield_id, mylinks_prefix('theme'), true);
$option_name = isset($options[$key]) ? $options[$key] : $options['default'];
// If in MyLinks post editor, None is selected
if ('none' === $key) {
	echo '<body class="mylinks-body ' . $theme_value . '">';
} else {
	echo '<body class="mylinks-body ' . $key . '">';
}
?>
<?php if (have_posts()) : ?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="mylinks">
			<div class="avatar">
				<?php
				// Grab the metadata from the database and define it
				$avatar = get_post_meta(get_the_ID(), mylinks_prefix('avatar'), true);
				$name = get_post_meta(get_the_ID(), mylinks_prefix('name'), true);
				?>
				<?php if (empty($avatar)) : ?>
				<?php else : ?>
					<img width="140" height="140" src="<?php echo esc_html($avatar); ?>" alt="<?php echo esc_html($name); ?>">
				<?php endif; ?>
			</div>
			<div class="name">
				<?php
				// Echo the name
				echo esc_html($name);
				?>
			</div>
			<div class="description">
				<?php
				// Grab the description from the database
				$description = get_post_meta(get_the_ID(), mylinks_prefix('description'), true);
				// Echo the description
				echo $description;
				?>
			</div>
			<?php $top = get_post_meta(get_the_ID(), mylinks_prefix('social-media-position'), true);
			if ($top === "top") :
			?>
				<div class="user-profile">
					<?php
					// Get the social media data ready for display
					$social_platforms = ['facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'pinterest', 'tiktok', 'discord'];
					$social_data = [];
					foreach ($social_platforms as $platform) {
						$social_data[$platform] = wp_mylinks_get_social_meta($platform);
					}
					$facebook_url = $social_data['facebook'][0];
					$facebook_icon = $social_data['facebook'][1];
					$twitter_url = $social_data['twitter'][0];
					$twitter_icon = $social_data['twitter'][1];
					$linkedin_url = $social_data['linkedin'][0];
					$linkedin_icon = $social_data['linkedin'][1];
					$instagram_url = $social_data['instagram'][0];
					$instagram_icon = $social_data['instagram'][1];
					$youtube_url = $social_data['youtube'][0];
					$youtube_icon = $social_data['youtube'][1];
					$pinterest_url = $social_data['pinterest'][0];
					$pinterest_icon = $social_data['pinterest'][1];
					$tiktok_url = $social_data['tiktok'][0];
					$tiktok_icon = $social_data['tiktok'][1];
					$discord_url = $social_data['discord'][0];
					$discord_icon = $social_data['discord'][1];
					?>
					<!-- Start Facebook -->
					<?php if (empty($facebook_url)) : ?>
					<?php else : ?>
						<a href="<?php echo esc_html($facebook_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
							<?php if (empty($facebook_icon)) : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/facebook.png', dirname(__DIR__))); ?>">
							<?php else : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($facebook_icon); ?>">
							<?php endif; ?>
						</a>
					<?php endif; ?>
					<!-- End Facebook -->
					<!-- Start Twitter -->
					<?php if (empty($twitter_url)) : ?>
					<?php else : ?>
						<a href="<?php echo esc_html($twitter_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
							<?php if (empty($twitter_icon)) : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/twitter.png', dirname(__DIR__))); ?>">
							<?php else : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($twitter_icon); ?>">
							<?php endif; ?>
						</a>
					<?php endif; ?>
					<!-- End Twitter -->
					<!-- Start Linkedin -->
					<?php if (empty($linkedin_url)) : ?>
					<?php else : ?>
						<a href="<?php echo esc_html($linkedin_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
							<?php if (empty($linkedin_icon)) : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/linkedin.png', dirname(__DIR__))); ?>">
							<?php else : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($linkedin_icon); ?>">
							<?php endif; ?>
						</a>
					<?php endif; ?>
					<!-- End Linkedin -->
					<!-- Start Instagram -->
					<?php if (empty($instagram_url)) : ?>
					<?php else : ?>
						<a href="<?php echo esc_html($instagram_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
							<?php if (empty($instagram_icon)) : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/instagram.png', dirname(__DIR__))); ?>">
							<?php else : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($instagram_icon); ?>">
							<?php endif; ?>
						</a>
					<?php endif; ?>
					<!-- End Instagram -->
					<!-- Start Youtube -->
					<?php if (empty($youtube_url)) : ?>
					<?php else : ?>
						<a href="<?php echo esc_html($youtube_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
							<?php if (empty($youtube_icon)) : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/youtube.png', dirname(__DIR__))); ?>">
							<?php else : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($youtube_icon); ?>">
							<?php endif; ?>
						</a>
					<?php endif; ?>
					<!-- End Youtube -->
					<!-- Start Pinterest -->
					<?php if (empty($pinterest_url)) : ?>
					<?php else : ?>
						<a href="<?php echo esc_html($pinterest_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
							<?php if (empty($pinterest_icon)) : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/pinterest.png', dirname(__DIR__))); ?>">
							<?php else : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($pinterest_icon); ?>">
							<?php endif; ?>
						</a>
					<?php endif; ?>
					<!-- End Pinterest -->
					<!-- Start TikTok -->
					<?php if (empty($tiktok_url)) : ?>
					<?php else : ?>
						<a href="<?php echo esc_html($tiktok_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
							<?php if (empty($tiktok_icon)) : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/tiktok.png', dirname(__DIR__))); ?>">
							<?php else : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($tiktok_icon); ?>">
							<?php endif; ?>
						</a>
					<?php endif; ?>
					<!-- End TikTok -->
					<!-- Start Discord -->
					<?php if (empty($discord_url)) : ?>
					<?php else : ?>
						<a href="<?php echo esc_html($discord_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
							<?php if (empty($discord_icon)) : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/discord.png', dirname(__DIR__))); ?>">
							<?php else : ?>
								<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($discord_icon); ?>">
							<?php endif; ?>
						</a>
					<?php endif; ?>
					<!-- End Discord -->
				</div>
			<?php endif; ?>
			<!-- End Top Social Media -->
			<div class="links">
				<?php
				// Now it's time to retrieve the saved data
				$links = get_post_meta(get_the_ID(), mylinks_prefix('links'), true);
				foreach ((array) $links as $key => $link) {
					$title = $url = $image = $youtube = $card_layout = $embed = '';
					if (isset($link['title'])) {
						$title = esc_html($link['title']);
					}
					if (isset($link['url'])) {
						$url = esc_html($link['url']);
					}
					if (isset($link['image'])) {
						$image = esc_html($link['image']);
					}
					if (isset($link['youtube-video'])) {
						$youtube = esc_url($link['youtube-video']);
					}
					if (isset($link['media-embed'])) {
						$embed = esc_url($link['media-embed']);
					}
					if ($link['card-layout'] === 'yes') {
						$card_layout = $link['card-layout'] === 'yes';
					}
				}
				?>
				<?php
				$links = get_post_meta(get_the_ID(), mylinks_prefix('links'), true);
				foreach ((array) $links as $key => $link) :
				?>
					<?php
					$youtube_url = $link['youtube-video'];
					$embed = $link['media-embed'];
					parse_str(parse_url($youtube_url, PHP_URL_QUERY), $video_id);
					if (isset($link['youtube-video']) && empty($link['title']) && empty($link['url']) && empty($link['image']) && $link['card-layout'] !== 'yes' && empty($link['media-embed'])) :
					?>
						<div class="link youtube-embed">
							<div class="youtube-player" data-id="<?php echo $video_id['v']; ?>"></div>
						</div>
					<?php elseif (isset($link['media-embed']) && empty($link['title']) && empty($link['url']) && empty($link['image']) && $link['card-layout'] !== 'yes' && empty($link['youtube-video'])) : ?>
						<div class="link media-embed-wrapper">
							<div class="media-embed">
								<?php // Tested: the following method is the better version (speed wise) than wp_oembed_get() function.
								global $wp_embed;
								$embed_html = $wp_embed->shortcode(array(), $embed);
								if (!$embed_html) {
									// Enable debugging for wp_oembed_get()
									add_filter('oembed_result', function ($result, $url, $args) {
										if (!$result) {
											return new WP_Error('invalid_url', __('The provided URL is not a valid oEmbed URL.', 'wp-mylinks'), $url);
										}
										return $result;
									}, 10, 3);
									$embed_html = wp_oembed_get($embed);
								}
								if ($embed_html && !is_wp_error($embed_html)) {
									$allowed_html = [
										'iframe' => [
											'src' => [],
											'width' => [],
											'height' => [],
											'frameborder' => [],
											'allowfullscreen' => [],
											'allow' => [],
										],
									];
									echo wp_kses($embed_html, $allowed_html);
								} else {
									if (is_wp_error($embed_html)) {
										echo __('Unable to embed the content. Reason: ', 'wp-mylinks') . $embed_html->get_error_message();
									} else {
										echo __('Unable to embed the content.', 'wp-mylinks');
									}
								}
								?>
							</div>
						</div>
					<?php elseif ($link['card-layout'] == 'yes' && isset($link['title']) && isset($link['url']) && isset($link['image']) && empty($link['youtube-video']) && empty($link['media-embed'])) : ?>
						<div class="card-wrapper">
							<div class="mylink-card">
								<a id="link_count" class="mylink-card-link" href="<?php echo $link['url']; ?>" target="_blank" rel="noopener">
									<img src="<?php echo $link['image']; ?>" alt="<?php echo $link['title']; ?>" class="mylink-card-background">
									<div class="mylink-card-title-wrapper">
										<h2 class="mylink-card-title"><?php echo $link['title']; ?></h2>
									</div>
								</a>
							</div>
						</div>
					<?php elseif (empty($link['image'])) : ?>
						<div class="link">
							<a id="link_count" class="button link-without-image inline-photo show-on-scroll" href="<?php echo $link['url']; ?>" target="_blank" rel="noopener">
								<span class="link-text"><?php echo $link['title']; ?></span>
							</a>
						</div>
					<?php else : ?>
						<div class="link">
							<a id="link_count" class="button link-with-image inline-photo show-on-scroll" href="<?php echo $link['url']; ?>" target="_blank" rel="noopener">
								<div class="thumbnail-wrap">
									<img src="<?php echo $link['image']; ?>" class="link-image" alt="thumbnail">
								</div>
								<span class="link-text"><?php echo $link['title']; ?></span>
							</a>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
		<?php wp_mylinks_track_mylink_page(get_the_ID()); ?>
	<?php endwhile; ?>
<?php endif; ?>
<?php $bottom = get_post_meta(get_the_ID(), mylinks_prefix('social-media-position'), true);
if ($bottom === "bottom") :
?>
	<div class="user-profile">
		<?php
		// Get the social media data ready for display
		$social_platforms = ['facebook', 'twitter', 'linkedin', 'instagram', 'youtube', 'pinterest', 'tiktok', 'discord'];
		$social_data = [];
		foreach ($social_platforms as $platform) {
			$social_data[$platform] = wp_mylinks_get_social_meta($platform);
		}
		$facebook_url = $social_data['facebook'][0];
		$facebook_icon = $social_data['facebook'][1];
		$twitter_url = $social_data['twitter'][0];
		$twitter_icon = $social_data['twitter'][1];
		$linkedin_url = $social_data['linkedin'][0];
		$linkedin_icon = $social_data['linkedin'][1];
		$instagram_url = $social_data['instagram'][0];
		$instagram_icon = $social_data['instagram'][1];
		$youtube_url = $social_data['youtube'][0];
		$youtube_icon = $social_data['youtube'][1];
		$pinterest_url = $social_data['pinterest'][0];
		$pinterest_icon = $social_data['pinterest'][1];
		$tiktok_url = $social_data['tiktok'][0];
		$tiktok_icon = $social_data['tiktok'][1];
		$discord_url = $social_data['discord'][0];
		$discord_icon = $social_data['discord'][1];
		?>
		<!-- Start Facebook -->
		<?php if (empty($facebook_url)) : ?>
		<?php else : ?>
			<a href="<?php echo esc_html($facebook_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
				<?php if (empty($facebook_icon)) : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/facebook.png', dirname(__DIR__))); ?>">
				<?php else : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($facebook_icon); ?>">
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<!-- End Facebook -->
		<!-- Start Twitter -->
		<?php if (empty($twitter_url)) : ?>
		<?php else : ?>
			<a href="<?php echo esc_html($twitter_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
				<?php if (empty($twitter_icon)) : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/twitter.png', dirname(__DIR__))); ?>">
				<?php else : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($twitter_icon); ?>">
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<!-- End Twitter -->
		<!-- Start Linkedin -->
		<?php if (empty($linkedin_url)) : ?>
		<?php else : ?>
			<a href="<?php echo esc_html($linkedin_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
				<?php if (empty($linkedin_icon)) : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/linkedin.png', dirname(__DIR__))); ?>">
				<?php else : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($linkedin_icon); ?>">
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<!-- End Linkedin -->
		<!-- Start Instagram -->
		<?php if (empty($instagram_url)) : ?>
		<?php else : ?>
			<a href="<?php echo esc_html($instagram_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
				<?php if (empty($instagram_icon)) : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/instagram.png', dirname(__DIR__))); ?>">
				<?php else : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($instagram_icon); ?>">
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<!-- End Instagram -->
		<!-- Start Youtube -->
		<?php if (empty($youtube_url)) : ?>
		<?php else : ?>
			<a href="<?php echo esc_html($youtube_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
				<?php if (empty($youtube_icon)) : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/youtube.png', dirname(__DIR__))); ?>">
				<?php else : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($youtube_icon); ?>">
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<!-- End Youtube -->
		<!-- Start Pinterest -->
		<?php if (empty($pinterest_url)) : ?>
		<?php else : ?>
			<a href="<?php echo esc_html($pinterest_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
				<?php if (empty($pinterest_icon)) : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/pinterest.png', dirname(__DIR__))); ?>">
				<?php else : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($pinterest_icon); ?>">
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<!-- End Pinterest -->
		<!-- Start TikTok -->
		<?php if (empty($tiktok_url)) : ?>
		<?php else : ?>
			<a href="<?php echo esc_html($tiktok_url); ?>" target="_blank" class="user-profile-link" rel="noopener noreferrer nofollow">
				<?php if (empty($tiktok_icon)) : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_url(plugins_url('/public/images/tiktok.png', dirname(__DIR__))); ?>">
				<?php else : ?>
					<img align="middle" class="mylinks-social-icons" width="32" height="32" src="<?php echo esc_html($tiktok_icon); ?>">
				<?php endif; ?>
			</a>
		<?php endif; ?>
		<!-- End TikTok -->
	</div>
<?php endif; ?>
<!-- End Top Social Media -->
<footer id="site-footer" role="contentinfo" class="mylinks-footer">
	<?php $enable_credits = get_option(sanitize_text_field('wp_mylinks_credits'));
	$credits = '<div class="wp-mylinks-credits">
		Made with ❤️ and ☕ by <a href="https://walterpinem.me" target="_blank" rel="noopener nofollow"><strong>Walter Pinem</strong></a></div>';
	?>
	<?php if ($enable_credits === 'yes') {
		echo $credits;
	}
	?>
	<?php
	if ($footer_script = get_post_meta(get_the_ID(), mylinks_prefix('mylinks-single-custom-footer-script'), true));
	else $footer_script = get_option(sanitize_text_field('wp_mylinks_footer_script'));
	echo $footer_script;
	?>
</footer>
<?php
wp_enqueue_script('mylinks-public-js');
wp_scripts()->do_item('mylinks-public-js');
?>
</body>

</html>