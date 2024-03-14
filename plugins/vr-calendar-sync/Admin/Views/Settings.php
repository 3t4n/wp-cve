<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  Settings
 * @package   Settings
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * Settings
  * 
  * Settings
  * 
  * @category  Settings
  * @package   Settings
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
$VRCalendarSettings = VRCalendarSettings::getInstance();
$auto_sync = array(
    'none'=>__('Disable', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
    'hourly'=>__('Hourly', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
    'twicedaily'=>__('Twice Daily', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
    'daily'=>__('Daily', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
);
$attribution = array(
    'yes'=>__('Yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
    'no'=>__('No', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
);
$load_jquery_ui_css = array(
    'yes'=>__('Yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
    'no'=>__('No', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
);
?>
<div class="wrap vrcal-content-wrapper">
    <h2>Settings</h2>
    <div class="tabs-wrapper">
        <h2 class="nav-tab-wrapper">
            <a class='nav-tab nav-tab-active' href='#general-options'><?php _e('General', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
        </h2>
        <div class="tabs-content-wrapper">
            <form method="post" action="" >
                <div id="general-options" class="tab-content tab-content-active">
                    <?php require VRCALENDAR_PLUGIN_DIR.'/Admin/Views/Part/Settings/General.php'; ?>
                </div>
                <div>
                    <?php wp_nonce_field( 'vr-calendar-sync-nonce','vr-calendar-sync-nonce' ); ?>
                    <input type="hidden" name="vrc_cmd" id="vrc_cmd" value="updateSettings" />
                    <input type="submit" value="<?php _e('Save', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" class="button button-primary">
                </div>
            </form>
        </div>
    </div>
</div>
