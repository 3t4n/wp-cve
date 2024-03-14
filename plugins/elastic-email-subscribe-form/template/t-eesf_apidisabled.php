<div class="connection-error-container">
    <img src="<?php echo esc_url(plugins_url('/src/img/connect_apikey.png', dirname(__FILE__))) ?>">
    <p class="ee_p"><?php _e('Sending via Elastic Email API is disabled.', 'elastic-email-subscribe-form') ?></p>
    <p class="user-info">
        <?php _e('You are currently sending through the basic Wordpress settings', 'elastic-email-subscribe-form') ?> <code>WP_MAIL()</code>.
        <?php _e('This screen is only available for sending via Elastic Email API. ', 'elastic-email-subscribe-form') ?>
        <?php _e('You can change it ', 'elastic-email-subscribe-form') ?> <a href="
        <?php echo get_admin_url() . 'admin.php?page=elasticemail-settings'; ?>"> <?php _e('here', 'elastic-email-subscribe-form') ?></a> <?php _e('(option: Select mailer)', 'elastic-email-subscribe-form') ?>.
    </p>
</div>
