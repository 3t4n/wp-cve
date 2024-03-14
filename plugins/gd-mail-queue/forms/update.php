<?php

if (!defined('ABSPATH')) { exit; }

$_classes = array('d4p-wrap', 'wpv-'.GDMAQ_WPV, 'd4p-page-update');

?>
<div class="<?php echo esc_attr(join(' ', $_classes)); ?>">
    <div class="d4p-header">
        <div class="d4p-plugin">
            GD Security Headers
        </div>
    </div>
    <div class="d4p-content">
        <div class="d4p-content-left">
            <div class="d4p-panel-title">
                <i aria-hidden="true" class="fa fa-magic"></i>
                <h3><?php esc_html_e("Update", "gd-mail-queue"); ?></h3>
            </div>
            <div class="d4p-panel-info">
                <?php esc_html_e("Before you continue, make sure plugin was successfully updated.", "gd-mail-queue"); ?>
            </div>
        </div>
        <div class="d4p-content-right">
            <div class="d4p-update-info">
                <?php

                include(GDMAQ_PATH.'forms/setup/database.php');

                gdmaq_settings()->set('install', false, 'info');
                gdmaq_settings()->set('update', false, 'info', true);

                ?>

                <h3><?php esc_html_e("All Done", "gd-mail-queue"); ?></h3>
                <?php esc_html_e("Update completed.", "gd-mail-queue"); ?>

                <br/><br/><a class="button-primary" href="<?php echo admin_url('admin.php?page=gd-mail-queue-about'); ?>"><?php esc_html_e("Click here to continue.", "gd-mail-queue"); ?></a>
            </div>
        </div>
    </div>
</div>