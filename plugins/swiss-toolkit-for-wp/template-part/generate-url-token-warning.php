<?php
	// If uninstall not called from WordPress, then exit.
	if (!defined('ABSPATH')) {
		exit;
	}

	// Ensure that WordPress head section is properly loaded.
    wp_head();
?>

<div class="warning-message">
    <h4><?php esc_html_e('Invalid Token', 'your-text-domain'); ?></h4>
    <p><?php esc_html_e('Request a new access link in order to obtain dashboard access', 'your-text-domain'); ?></p>
    <div>
        <a href="<?php echo esc_url(home_url()); ?>"><?php esc_html_e('Go To Homepage', 'your-text-domain'); ?></a>
    </div>
</div>

<?php
    // Ensure that WordPress footer section is properly loaded.
    wp_footer();
?>