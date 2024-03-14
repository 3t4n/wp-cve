<?php

if (!defined('ABSPATH')) { exit; }

$panels = array(
    'index' => array(
        'title' => __("Settings Index", "gd-mail-queue"), 'icon' => 'cogs', 'scope' => 'blog', 
        'info' => __("All plugin settings are split into panels, and you access each starting from the right.", "gd-mail-queue")),
    'basic' => array(
        'title' => __("Control", "gd-mail-queue"), 'icon' => 'envelope', 'scope' => 'blog',
        'break' => __("Basic", "gd-mail-queue"),
        'info' => __("From this panel you control mailer processing settings.", "gd-mail-queue")),
    'extra' => array(
        'title' => __("Extra", "gd-mail-queue"), 'icon' => 'paper-plane', 'scope' => 'blog',
        'info' => __("From this panel you control some additional plugin settings.", "gd-mail-queue")),
    'log' => array(
        'title' => __("Log", "gd-mail-queue"), 'icon' => 'file-text-o', 'scope' => 'blog',
        'info' => __("From this panel you control email logging settings.", "gd-mail-queue")),
    'cleanup' => array(
        'title' => __("Cleanup", "gd-mail-queue"), 'icon' => 'trash', 'scope' => 'blog',
        'info' => __("From this panel you control cleanup of the queue and log.", "gd-mail-queue")),
    'queue' => array(
        'title' => __("Queue Processing", "gd-mail-queue"), 'icon' => 'share', 'scope' => 'blog',
        'break' => __("Queue", "gd-mail-queue"),
        'info' => __("From this panel you control queue processing settings.", "gd-mail-queue")),
    'queue_override' => array(
        'title' => __("Queue Overrides", "gd-mail-queue"), 'icon' => 'share', 'scope' => 'blog',
        'info' => __("From this panel you control some queue email sending values.", "gd-mail-queue")),
    'htmlfy' => array(
        'title' => __("Template", "gd-mail-queue"), 'icon' => 'html5', 'scope' => 'blog',
        'break' => __("HTMLfy", "gd-mail-queue"),
        'info' => __("From this panel you control turning email plain text into HTML settings.", "gd-mail-queue")),
    'htmlparts' => array(
        'title' => __("Template Parts", "gd-mail-queue"), 'icon' => 'code', 'scope' => 'blog',
        'info' => __("From this panel you control additional template replacement elements.", "gd-mail-queue")),
    'engine_phpmailer' => array(
        'title' => __("PHPMailer", "gd-mail-queue"), 'icon' => 'envelope-open-o', 'scope' => 'blog',
        'break' => __("Email Sending Engines", "gd-mail-queue"),
        'info' => __("From this panel you control PHPMailer engine for email sending.", "gd-mail-queue")),
    'buddypress' => array(
        'title' => __("BuddyPress", "gd-mail-queue"), 'icon' => 'plug', 'scope' => 'blog',
        'break' => __("Third Party Plugins", "gd-mail-queue"),
        'info' => __("From this panel you control settings related to BuddyPress plugin.", "gd-mail-queue")),
    'pause' => array(
        'title' => __("Pause", "gd-mail-queue"), 'icon' => 'pause', 'scope' => 'blog',
        'break' => __("Advanced", "gd-mail-queue"),
        'info' => __("From this panel you control over pausing the email sending.", "gd-mail-queue")),
    'misc' => array(
        'title' => __("Misc", "gd-mail-queue"), 'icon' => 'lightbulb-o', 'scope' => 'blog',
        'info' => __("From this panel you control various other settings.", "gd-mail-queue"))
);

include(GDMAQ_PATH.'forms/shared/top.php');

$scope = is_multisite() ? gdmaq_scope()->get_scope() : $panels[$_panel]['scope'];

?>

<form method="post" action="" autocomplete="off">
    <?php settings_fields('gd-mail-queue-settings'); ?>
    <input type="hidden" value="postback" name="gdmaq_handler" />
    <input type="hidden" value="<?php echo $scope; ?>" name="gdmaq_scope" />
    <input autocomplete="none" type="text" style="display:none;">

    <div class="d4p-content-left">
        <div class="d4p-panel-scroller d4p-scroll-active">
            <div class="d4p-panel-title">
                <i aria-hidden="true" class="fa fa-cogs"></i>
                <h3><?php esc_html_e("Settings", "gd-mail-queue"); ?></h3>
                <?php if ($_panel != 'index') { ?>
                <h4><i aria-hidden="true" class="fa fa-<?php echo $panels[$_panel]['icon']; ?>"></i> <?php echo $panels[$_panel]['title']; ?></h4>
                <?php } ?>
            </div>
            <div class="d4p-panel-info">
                <?php echo $panels[$_panel]['info']; ?>
            </div>
            <?php if ($_panel != 'index') { ?>
                <div class="d4p-panel-buttons">
                    <input type="submit" value="<?php esc_html_e("Save Settings", "gd-mail-queue"); ?>" class="button-primary">
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
                    <h5><?php echo esc_html($obj['title']); ?></h5>
                    <div>
                        <?php if (isset($obj['type'])) { ?>
                        <span><?php echo $obj['type']; ?></span>
                        <?php } ?>
                        <a class="button-primary" href="<?php echo $url; ?>"><?php esc_html_e("Settings Panel", "gd-mail-queue"); ?></a>
                    </div>
                </div>
        
                <?php if (isset($obj['break_next'])) { ?>

                <div style="clear: both"></div>
                <div class="d4p-panel-break d4p-clearfix">
                    <h4><?php echo $obj['break_next']; ?></h4>
                </div>
                <div style="clear: both"></div>

                <?php }
            }
        } else {
            d4p_includes(array(
                array('name' => 'settings', 'directory' => 'admin'),
                array('name' => 'walkers', 'directory' => 'admin'),
                array('name' => 'functions', 'directory' => 'admin')
            ), GDMAQ_D4PLIB);

            include(GDMAQ_PATH.'core/admin/options.php');

            $options = new gdmaq_admin_settings();

            $panel = gdmaq_admin()->panel;
            $groups = $options->get($panel);

            $render = new d4pSettingsRender($panel, $groups);
            $render->base = 'gdmaqvalue';
            $render->render();
        }

        ?>
    </div>
</form>

<?php 

include(GDMAQ_PATH.'forms/shared/bottom.php');
