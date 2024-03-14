<?php

/**
 * 
 * Template name: Member only template
 *
 */
//old options 
// Default value 
$pp_admin_page = get_option('pp_admin_page');
$pp_page_options = (!empty($pp_admin_page)) ? $pp_admin_page : '';

// All options
$pp_page_featureimg = (isset($pp_page_options['pp_page_featureimg'])) ? $pp_page_options['pp_page_featureimg'] : 'hide';
$pp_page_head = (isset($pp_page_options['pp_page_head'])) ? $pp_page_options['pp_page_head'] : 'on';
$pp_massage_title = (isset($pp_page_options['pp_massage_title'])) ? $pp_page_options['pp_massage_title'] : __('This content is password protected for members only', 'wp-edit-password-protected');
$pp_title_tag = (isset($pp_page_options['pp_title_tag'])) ? $pp_page_options['pp_title_tag'] : 'h2';
$pp_massage_desc = (isset($pp_page_options['pp_massage_desc'])) ? $pp_page_options['pp_massage_desc'] : __('<p>This content is password protected for members only. <br> If you want to see this content please login. </p>', 'wp-edit-password-protected');
$pp_content_shortcode = (isset($pp_page_options['pp_content_shortcode'])) ? $pp_page_options['pp_content_shortcode'] : '';
$pp_page_comment = (isset($pp_page_options['pp_page_comment'])) ? $pp_page_options['pp_page_comment'] : 'hide';
$pp_login_link = (isset($pp_page_options['pp_login_link'])) ? $pp_page_options['pp_login_link'] : 'show';
$pp_login_link_url = (isset($pp_page_options['pp_login_link_url'])) ? $pp_page_options['pp_login_link_url'] : wp_login_url();
$pp_login_btn_text = (isset($pp_page_options['pp_login_btn_text'])) ? $pp_page_options['pp_login_btn_text'] : __('Login', 'wp-edit-password-protected');
$pp_login_btn_class = (isset($pp_page_options['pp_login_btn_class'])) ? $pp_page_options['pp_login_btn_class'] : 'btn button';
$pp_page_class = (isset($pp_page_options['pp_page_class'])) ? $pp_page_options['pp_page_class'] : '';
$pp_page_text_align = (isset($pp_page_options['pp_page_text_align'])) ? $pp_page_options['pp_page_text_align'] : 'text-center';

function wppass_adminpage_front_default($item = '')
{
	if ($item == 'on' || $item == 1) {
		return 'on';
	}
	return 'off';
}



$wppasspro_page_fimg = get_option('wppasspro_page_fimg', $pp_page_featureimg);
$wpe_adpage_class = get_option('wpe_adpage_class', $pp_page_class);
$wpe_adpage_mode = get_option('wpe_adpage_mode', 'login');
$wpe_adpage_style = get_option('wpe_adpage_style', 's1');
$wpe_adpage_text_align = get_option('wpe_adpage_text_align', 'center');
$wpe_adpage_infotitle = get_option('wpe_adpage_infotitle', $pp_massage_title);
$pp_title_tag = get_option('wpe_adpage_titletag', $pp_title_tag);
$wpe_adpage_text = get_option('wpe_adpage_text', $pp_massage_desc);
$wpe_adpage_shortcode = get_option('wpe_adpage_shortcode', $pp_content_shortcode);
$wpe_adpage_login_mode = get_option('wpe_adpage_login_mode', 'form');
$wpe_adpage_login_url = get_option('wpe_adpage_login_url', $pp_login_link_url);
$wpe_adpage_btntext = get_option('wpe_adpage_btntext', $pp_login_btn_text);
$wpe_adpage_btnclass = get_option('wpe_adpage_btnclass', $pp_login_btn_class);
$wpe_adpage_form_head = get_option('wpe_adpage_form_head', esc_html__('Login Form', 'kirki'));
$wpe_adpage_user_placeholder = get_option('wpe_adpage_user_placeholder', esc_html__('username', 'kirki'));
$wpe_adpage_password_placeholder = get_option('wpe_adpage_password_placeholder', esc_html__('Password', 'kirki'));
$wpe_adpage_form_remember = get_option('wpe_adpage_form_remember', 'on');
$wpe_adpage_remember_text = get_option('wpe_adpage_remember_text', esc_html__('Remember Me', 'kirki'));
$wpe_adpage_wrongpassword = get_option('wpe_adpage_wrongpassword', esc_html__('The password you entered is incorrect, Please try again.', 'kirki'));
$wpe_adpage_errorlogin = get_option('wpe_adpage_errorlogin', esc_html__('Please enter both username and password.', 'kirki'));
$wpe_adpage_formbtn_text = get_option('wpe_adpage_formbtn_text', esc_html__('Login', 'kirki'));
$wpe_adpage_width = get_option('wpe_adpage_width', 'standard');
$pp_page_head = get_option('wpe_adpage_header_show', wppass_adminpage_front_default($pp_page_head));
$pp_page_comment = get_option('wpe_adpage_comment');


if ($wpe_adpage_mode == 'login') {
	$wppss_pagelogin_condition = (is_user_logged_in() && !(is_customize_preview()));
} else {
	$wppss_pagelogin_condition = is_user_logged_in();
}




get_header(); ?>

<div class="wpepa-adminonly-page wpepa-wrap-<?php echo esc_attr($wpe_adpage_width); ?>  <?php echo esc_attr($wpe_adpage_class); ?>">
	<?php
	if ($wppasspro_page_fimg == 'admin' && is_user_logged_in()) : ?>
		<div class="feature-img">
			<?php the_post_thumbnail(); ?>
		</div>
	<?php
	endif;
	if ($wppasspro_page_fimg == 'all') :
	?>
		<div class="feature-img">
			<?php the_post_thumbnail(); ?>
		</div>

	<?php endif; ?>


	<div id="wppm-primary" class="wppm-content-area">
		<main id="wppm-main" class="wppm-site-main">
			<?php
			if ($wppss_pagelogin_condition) :
				while (have_posts()) : the_post(); ?>

					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php if ($pp_page_head == 'on') : ?>
							<header class="entry-header">
								<?php the_title('<h1 class="entry-title">', '</h1>'); ?>
							</header><!-- .entry-header -->
						<?php endif; ?>
						<div class="wppm-entry-content">
							<?php
							the_content();

							?>
						</div><!-- .entry-content -->

					</article><!-- #post-<?php the_ID(); ?> -->
				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if (comments_open() || get_comments_number() && $pp_page_comment) :
						comments_template();
					endif;

				endwhile; // End of the loop.
			else :
				?>
				<div class="wpp-not-login text-<?php echo esc_attr($wpe_adpage_text_align); ?> wpp-loginpage-<?php echo esc_attr($wpe_adpage_style); ?>">
					<?php if (!empty($wpe_adpage_infotitle)) : ?>
						<<?php echo esc_attr($pp_title_tag); ?>><?php echo esc_html($wpe_adpage_infotitle); ?></<?php echo esc_attr($pp_title_tag); ?>>
					<?php endif; ?>
					<?php
					echo wp_kses_post($wpe_adpage_text);

					?>
					<?php
					if ($wpe_adpage_shortcode) {
						echo do_shortcode($wpe_adpage_shortcode);
					}

					?>
					<?php if ($wpe_adpage_login_mode == 'link') : ?>

						<a class="wepp-btn <?php echo esc_attr($wpe_adpage_btnclass); ?>" href="<?php echo esc_url($wpe_adpage_login_url); ?>" title="<?php echo esc_attr($wpe_adpage_btntext); ?>"><?php echo esc_html($wpe_adpage_btntext); ?></a>
					<?php else : ?>
						<?php
						$redirect_to = get_the_permalink();
						?>
						<div class="wepp-loginform">
							<h2><?php echo esc_html($wpe_adpage_form_head); ?></h2>
							<div class="wepp-loginform-error">
								<?php if (isset($_GET['login']) && $_GET['login'] == 'failed') { ?>
									<p><?php echo esc_html($wpe_adpage_wrongpassword); ?></p>
								<?php } else if (isset($_GET['login']) && $_GET['login'] == 'empty') { ?>
									<p><?php echo esc_html($wpe_adpage_errorlogin); ?></p>
								<?php } ?>
							</div>
							<form name="loginform" id="loginform" action="<?php echo site_url('/wp-login.php'); ?>" method="post">
								<input class="wepp-linput" id="user_login" type="text" size="20" value="" name="log" placeholder="<?php echo esc_attr($wpe_adpage_user_placeholder); ?>">
								<div class="wp-pwd">

									<input class="wepp-linput" id="user_pass" type="password" size="20" value="" name="pwd" placeholder="<?php echo esc_attr($wpe_adpage_password_placeholder); ?>">
									<!-- <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="Show password">
										<span class="dashicons dashicons-visibility" aria-hidden="true"></span>
									</button> -->

								</div>


								<?php if ($wpe_adpage_form_remember == 'on') : ?>
									<p class="wepp-chebox-wrap">
										<input class="wepp-linput-checkbox" id="rememberme" type="checkbox" value="forever" name="rememberme">
										<span><?php echo esc_html($wpe_adpage_remember_text); ?></span>
									</p>
								<?php endif; ?>
								<input class="wepp-linput" id="wp-submit" type="submit" value="<?php echo esc_attr($wpe_adpage_formbtn_text); ?>" name="wp-submit">

								<input type="hidden" value="<?php echo esc_attr($redirect_to); ?>" name="redirect_to">

							</form>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- #main-container -->
<?php

get_footer();
