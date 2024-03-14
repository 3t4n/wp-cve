<?php if (!defined('ABSPATH')) { exit; } ?>

<div class="d4p-group d4p-group-information">
    <h3><?php esc_html_e("Important", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <?php esc_html_e("This tool sends test email to check email sending settings, and capture sending status or errors. To make sure email is sent directly, queue intercept will be disabled for the test.", "gd-mail-queue"); ?>
    </div>
</div>

<div class="d4p-group d4p-group-tools">
    <h3><?php esc_html_e("Configure test", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">
        <label><?php esc_html_e("Send To", "gd-mail-queue"); ?></label>
        <input type="text" class="widefat" name="gdmaqtools[mailtest][email]" value="<?php echo esc_attr(get_option('admin_email')); ?>" />
        <br/><br/>
        <label><?php esc_html_e("Subject", "gd-mail-queue"); ?></label>
        <input type="text" class="widefat" name="gdmaqtools[mailtest][subject]" value="<?php echo __("Test email", "gd-mail-queue").': '.get_option('blogname'); ?>" />
    </div>
</div>

<div class="d4p-group d4p-group-tools" id="d4p-tools-test-results" style="display: none;">
    <h3><?php esc_html_e("Test results", "gd-mail-queue"); ?></h3>
    <div class="d4p-group-inner">

    </div>
</div>
