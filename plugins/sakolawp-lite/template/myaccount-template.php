<?php
/**
 * My Account
 *
 * @package Sakolawp/Template
 * @version 1.0.0
 */
defined( 'ABSPATH' ) || exit;

get_header();

do_action( 'sakolawp_before_main_content' );

$logo_id        	= get_theme_mod( 'custom_logo' );
$logo_image     	= wp_get_attachment_image_src( $logo_id, 'full' );

//register get link
$page = get_page_by_title('register');
?>

	<form id="sakolawp_login_form"  class="sakolawp_user_form sakolawp_form" action="" method="post">
		<fieldset class="skwp-form-inner">
			<?php if ( ! empty( $logo_image ) ) { ?>
				<img src="<?php echo esc_url( $logo_image[0] ); ?>" alt="<?php esc_html_e( 'logo', 'sakolawp' ); ?>" />
			<?php } ?>
			<h4 class="sakolawp_header"><?php esc_html_e('Login Account', 'sakolawp'); ?></h4>
			<?php sakolawp_show_error_messages(); ?>
			<p>
				<input name="sakolawp_user_login" id="sakolawp_user_login" class="required swkp-usr-form" type="text" placeholder="<?php esc_html_e('Username', 'sakolawp'); ?>"/>
			</p>
			<p>
				<input name="sakolawp_user_pass" id="sakolawp_user_pass" class="required swkp-usr-form" type="password"  placeholder="<?php esc_html_e('Password', 'sakolawp'); ?>"/>
			</p>
			<div class="login meta skwp-clearfix">
				<p class="forgetmenot float-left"><label for="rememberme"><input name="rememberme" type="checkbox" id="rememberme" value="forever"  /> <?php esc_html_e('Keep me logged in', 'sakolawp'); ?></label></p>
				<a href="<?php echo esc_url( get_permalink($page->ID) ); ?>" class="register float-right"><?php esc_html_e('Already a member?', 'sakolawp'); ?></a>
			</div>
			<p>
				<input id="skwp-login-btn" type="hidden" name="sakolawp_login_nonce" value="<?php echo wp_create_nonce('sakolawp-login-nonce'); ?>"/>
				<input id="sakolawp_login_submit" type="submit" value="<?php echo esc_html__('Login', 'sakolawp'); ?>"/>
			</p>
		</fieldset>
	</form>

<?php

do_action( 'sakolawp_after_main_content' );
get_footer();