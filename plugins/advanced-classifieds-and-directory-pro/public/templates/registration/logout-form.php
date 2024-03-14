<?php

/**
 * Logout Button.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp">
	<a href="<?php echo esc_url( wp_logout_url() ); ?>" class="acadp-button acadp-button-primary acadp-button-logout">
		<?php esc_html_e( 'Logout', 'advanced-classifieds-and-directory-pro' ); ?>
	</a>
</div>