<?php
/**
 * Waiting Page
 *
 * @package Sakolawp/Template
 * @version 1.0.0
 */
defined( 'ABSPATH' ) || exit;

get_header();
do_action( 'sakolawp_before_main_content' ); ?>
	
	<h3 class="sakolawp_header"><?php esc_html_e('Please wait until administrator approve your account.', 'sakolawp'); ?></h3>
	<h6 class="sakolawp_header"><?php esc_html_e('Or contact your school administrator.', 'sakolawp'); ?></h6>

<?php
do_action( 'sakolawp_after_main_content' );
get_footer();