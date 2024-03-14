<?php

if (!defined('ABSPATH')) { exit; }

$panels = array(
    'index' => array(
        'title' => __("Tools Index", "gd-mail-queue"), 'icon' => 'wrench', 
        'info' => __("All plugin tools are split into several panels, and you can access each starting from the right.", "gd-mail-queue")),
    'test' => array(
        'title' => __("Send Test Email", "gd-mail-queue"), 'icon' => 'envelope',
        'break' => __("Test", "gd-mail-queue"),
        'button' => 'button', 'button_text' => __("Send Test eMail", "gd-mail-queue"),
        'info' => __("Send test email and get the debug information from the test.", "gd-mail-queue")),
    'queue' => array(
        'title' => __("Queue Test Email", "gd-mail-queue"), 'icon' => 'envelope-open',
        'button' => 'button', 'button_text' => __("Queue Test eMail", "gd-mail-queue"),
        'info' => __("Add test email to the queue.", "gd-mail-queue")),
    'recheck' => array(
        'title' => __("Recheck and Update", "gd-mail-queue"), 'icon' => 'refresh',
        'break' => __("Maintenance", "gd-mail-queue"),
        'button' => 'none', 'button_text' => '',
        'info' => __("Recheck plugin database tables, check for new templates and clean cache.", "gd-mail-queue")),
    'export' => array(
        'title' => __("Export Settings", "gd-mail-queue"), 'icon' => 'download',
        'break' => __("Export / Import", "gd-mail-queue"),
        'button' => 'button', 'button_text' => __("Export", "gd-mail-queue"),
        'info' => __("Export all plugin settings into file.", "gd-mail-queue")),
    'import' => array(
        'title' => __("Import Settings", "gd-mail-queue"), 'icon' => 'upload',
        'button' => 'submit', 'button_text' => __("Import", "gd-mail-queue"),
        'info' => __("Import all plugin settings from export file.", "gd-mail-queue")),
    'remove' => array(
        'title' => __("Reset / Remove", "gd-mail-queue"), 'icon' => 'remove',
        'break' => __("Reset Plugin", "gd-mail-queue"),
        'button' => 'submit', 'button_text' => __("Remove", "gd-mail-queue"),
        'info' => __("Remove selected plugin settings, database tables and optionally disable plugin.", "gd-mail-queue"))
);

include(GDMAQ_PATH.'forms/shared/top.php');

?>

<form method="post" action="" enctype="multipart/form-data" id="gdmaq-tools-form">
    <?php settings_fields('gd-mail-queue-tools'); ?>
    <input type="hidden" value="<?php echo $_panel; ?>" name="gdmaqtools[panel]" />
    <input type="hidden" value="postback" name="gdmaq_handler" />

    <div class="d4p-content-left">
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
                <i aria-hidden="true" class="fa fa-wrench"></i>
                <h3><?php esc_html_e("Tools", "gd-mail-queue"); ?></h3>
                <?php if ($_panel != 'index') { ?>
                <h4><i aria-hidden="true" class="fa fa-<?php echo $panels[$_panel]['icon']; ?>"></i> <?php echo $panels[$_panel]['title']; ?></h4>
                <?php } ?>
            </div>
            <div class="d4p-panel-info">
                <?php echo $panels[$_panel]['info']; ?>
            </div>
            <?php if ($_panel != 'index' && $panels[$_panel]['button'] != 'none') { ?>
                <div class="d4p-panel-buttons">
                    <input id="gdmaq-tool-<?php echo $_panel; ?>" class="button-primary" type="<?php echo $panels[$_panel]['button']; ?>" value="<?php echo $panels[$_panel]['button_text']; ?>" />
                </div>
            <?php } ?>
            <div class="d4p-return-to-top">
                <a href="#wpwrap"><?php esc_html_e("Return to top", "gd-mail-queue"); ?></a>
            </div>
        </div>
    </div>
    <div class="d4p-content-right">
        <?php

        if ($_panel == 'index') {
            foreach ($panels as $panel => $obj) {
                if ($panel == 'index') continue;

                $url = 'admin.php?page=gd-mail-queue-'.$_page.'&panel='.$panel;

                if (isset($obj['break'])) { ?>

                    <div style="clear: both"></div>
                    <div class="d4p-panel-break d4p-clearfix">
                        <h4><?php echo $obj['break']; ?></h4>
                    </div>
                    <div style="clear: both"></div>

                <?php } ?>

                <div class="d4p-options-panel">
                    <i aria-hidden="true" class="fa fa-<?php echo $obj['icon']; ?>"></i>
                    <h5><?php echo $obj['title']; ?></h5>
                    <div>
                        <a class="button-primary" href="<?php echo $url; ?>"><?php esc_html_e("Tools Panel", "gd-mail-queue"); ?></a>
                    </div>
                </div>

                <?php
            }
        } else {
            include(GDMAQ_PATH.'forms/tools/'.$_panel.'.php');
        }

        ?>
    </div>
</form>

<?php 

include(GDMAQ_PATH.'forms/shared/bottom.php');
