<br/>
<br/>
<?php echo do_shortcode('[cminds_free_activation id="cmf"]'); ?>

<?php if (!empty($messages)): ?>
    <div class="updated" style="clear:both; margin-left: 0;"><p><?php echo $messages; ?></p></div>
<?php endif; ?>
<br/>

<?php

use com\cminds\footnotes\settings\CMF_Settings;

global $cmf_isLicenseOk;
// check permalink settings
if (CMF_Settings::get('permalink_structure') == '') {
    echo '<span style="color:red">Your WordPress Permalinks needs to be set to allow plugin to work correctly. Please Go to <a href="' . admin_url() . 'options-permalink.php" target="new">Settings->Permalinks</a> to set Permalinks to Post Name.</span><br><br>';
}
?>

<div class="clear"></div>

<div id="cminds_settings_container">

    <form method="post" id="cminds_settings_form">

        <div id="cminds_settings_search--container">
            <input id="cminds_settings_search" placeholder="Search in settings..."><span id="cminds_settings_search_clear">&times;</span>
        </div>

        <?php wp_nonce_field('update-options'); ?>
        <input type="hidden" name="action" value="update" />

        <div id="cm_settings_tabs" class="footnoteSettingsTabs">
            <div class="footnote_loading"></div>
            <?php
            CMF_Settings::renderSettingsTabsControls();
            CMF_Settings::renderSettingsTabs();
            ?>

            <p class="submit" style="clear:left">
                <input id="cminds_settings_save" type="submit"  value="<?php echo 'Save Changes' ?>" name="<?php echo CMF_Settings::abbrev('_saveSettings'); ?>" />
            </p>

        </div>
    </form>
</div>
<div class="clear"></div>