<?php defined('WPINC') || die; ?>

<form action="options.php" method="POST">

    <?php
        do_settings_sections($pageSlug);
        settings_fields('multicurrency_settings');
    ?>

    <a href="?page=<?php echo $pageSlug; ?>&tab=advanced_settings"><?php _e('Advanced Settings', 'premmerce-woocommerce-multicurrency'); ?></a>

    <?php submit_button(); ?>

</form>

