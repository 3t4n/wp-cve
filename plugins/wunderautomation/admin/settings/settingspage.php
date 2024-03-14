<?php

$wunderAuto   = wa_wa();
$settingsTabs = $wunderAuto->getSettings();
$link         = "https://www.wundermatics.com/docs-category/wunderautomation/" .
                "?utm_source=dashboard&utm_medium=settings&utm_campaign=installed_users";

$tabs = [];
foreach ($settingsTabs as $settingsTab) {
    $tabs[$settingsTab->id] = (object)[
        'caption' => $settingsTab->caption,
    ];
}

$active = 'wunderauto-general';
if (isset($_GET['tab'])) {
    $active = sanitize_key($_GET['tab']);
}

if (!isset($tabs[$active])) {
    wp_die('Tamper tamper');
}
?>

<div class="wrap">
    <h2><?php _e('WunderAutomation settings', 'wunderauto');?></h2>
    <a href="<?php esc_attr_e(admin_url('index.php?page=wunderauto-getting-started'))?>" class="button button-primary">
        <?php _e('Welcome page', 'wunderauto');?>
    </a>
    <?php settings_errors(); ?>

    <h2 class="nav-tab-wrapper">
        <?php foreach ($tabs as $id => $tab) :?>
            <a href="?page=wunderauto-settings&tab=<?php esc_html_e($id)?>"
               class="nav-tab <?php esc_attr_e($active == $id ? 'nav-tab-active' : '')?>">
               <?php esc_html_e($tab->caption);?>
            </a>
        <?php endforeach;?>
        <a href="<?php esc_attr_e($link);?>" target="_blank" class="nav-tab">
            <?php esc_html_e(__('Documentation', 'wunderauto'));?>
        </a>
    </h2>

    <form method="post" action="options.php">
        <?php
        settings_fields($active);
        do_settings_sections($active);
        if (current_user_can('manage_options')) {
            submit_button();
        }
        ?>
    </form>

</div><!-- .wrap -->
