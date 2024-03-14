<?php

if (!empty($_SERVER['SCRIPT_FILENAME']) && basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    die('Please do not load this screen directly. Thanks!');
}

use Rockschtar\WordPress\ColoredAdminPostList\Enums\Setting;

?>
<div class="wrap">
    <div id="icon-themes" class="icon32"><br></div>
    <h2><?php echo __("Colored Admin Post List Settings", "colored-admin-post-list") ?></h2>
    <form method="post" action="options.php">
        <?php settings_fields(Setting::PAGE_DEFAULT); ?>
        <?php do_settings_sections(Setting::PAGE_DEFAULT); ?>
        <?php submit_button(); ?>
    </form>
</div>
