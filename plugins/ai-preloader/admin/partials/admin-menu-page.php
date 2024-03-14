<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://atikul99.github.io/atikul
 * @since      1.0.0
 *
 * @package    Ai_Preloader
 * @subpackage Ai_Preloader/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

    <div class="wrap">
        <?php settings_errors(); ?>
        <h1>AI Preloader</h1>

        <form method="post" action="options.php" class="ai-general-form">
            <?php

            settings_fields("header_section");

            do_settings_sections("ai-menu-page");

            submit_button('Save Changes', 'primary', 'btnSubmit');

            ?>
        </form>

    </div>