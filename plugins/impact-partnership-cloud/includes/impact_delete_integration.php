<div class="wrap">
    <?php settings_errors(); ?>
    <form method="post" action="options.php" class="new_integration_setting" id="impact_integration_delete">
        <?php
        settings_fields('impact_integration_delete_option_group');
        do_settings_sections('impact-integration-delete');
        ?>
        <p class="submit"><input type="submit" name="submit" id="submit" value="Delete credentials" class="btn btn-danger"></p>
    </form>
    <a href="<?php echo home_url() . '/wp-admin/admin.php?page=impact-settings'; ?>"><< Impact Settings</a>
</div>
