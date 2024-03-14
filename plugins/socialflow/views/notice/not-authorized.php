<?php
/**
 * Template for displaying options page updated notice
 *
 * @package SocialFlow
 */

?>
<div id="sf-initial-nag" class="updated">
	<p>
		<strong><?php esc_html_e( 'NOTICE', 'socialflow' ); ?></strong> 
		<?php esc_html_e( 'Now that you have activated SocialFlow, please', 'socialflow' ); ?>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=socialflow' ) ); ?>"><?php esc_html_e( 'visit the settings page' ); ?></a>
		and connect to SocialFlow.
	</p>
	<p>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=socialflow' ) ); ?>"><?php esc_html_e( 'Take me to the settings page', 'socialflow' ); ?></a>
	</p>
</div>
