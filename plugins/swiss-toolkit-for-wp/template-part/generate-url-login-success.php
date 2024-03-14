<?php
	// If uninstall not called from WordPress, then exit.
	if (!defined('ABSPATH')) {
		exit;
	}

	// Ensure that WordPress head section is properly loaded.
    wp_head();
?>

<div class="warning-message">
    <h2><?php esc_html_e('Authentication Success', 'swiss-toolkit-for-wp'); ?></h2>
    <p><?php esc_html_e('You are being redirected to the dashboard', 'swiss-toolkit-for-wp'); ?></p>
    <div>
        <a href="<?php echo esc_url(home_url()); ?>"><?php esc_html_e('Go To Homepage', 'swiss-toolkit-for-wp'); ?></a>
    </div>
</div>

<?php
    // Ensure that WordPress footer section is properly loaded.
    wp_footer();
?>