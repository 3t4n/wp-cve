<?php
/**
 * Short description: Booking calendar created by Innate Images, LLC
 * PHP Version 8.0
 
 * @category  Views
 * @package   Views
 * @author    Innate Images, LLC <info@innateimagesllc.com>
 * @copyright 2015 Innate Images, LLC
 * @license   GPL-2.0+ http://www.vrcalendarsync.com
 * @link      http://www.vrcalendarsync.com
 */

 /**
  * Short description: Booking calendar created by Innate Images, LLC
  * Views
  * 
  * Views
  * 
  * @category  Views
  * @package   Views
  * @author    Innate Images, LLC <info@innateimagesllc.com>
  * @copyright 2015 Innate Images, LLC
  * @license   GPL-2.0+ http://www.vrcalendarsync.com
  * @link      http://www.vrcalendarsync.com
  */
$VRCalendarEntity = VRCalendarEntity::getInstance();
if (isset($_GET['cal_id'])) {
    $calid = sanitize_text_field($_GET['cal_id']);
    /* Fetch Calendar Details */
    $cdata = $VRCalendarEntity->getCalendar($calid);
} else {
    $cdata = $VRCalendarEntity->getEmptyCalendar();
}

$layout_option_size = array(
    'small'=>__('Small', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
    'medium'=>__('Medium', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
    'large'=>__('Large', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
);
?>
<div class="wrap vrcal-content-wrapper">
    <h2><?php _e('Add Calendar', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></h2>
    <div class="tabs-wrapper">
        <h2 class="nav-tab-wrapper">
            <a class='nav-tab nav-tab-active' href='#general-options'>General</a>
            <a class='nav-tab' href='#booking-options'>Booking Options</a>
            <a class='nav-tab' href='#color-options'>Color Options</a>
        </h2>
        <div class="tabs-content-wrapper">
            <form method="post" action="" >
                <div id="general-options" class="tab-content tab-content-active">
                    <?php require VRCALENDAR_PLUGIN_DIR.'/Admin/Views/Part/AddCalendar/General.php'; ?>
                </div>
                <div id="booking-options" class="tab-content">
                    <div class="update-nag">
                        <?php _e('Booking is not enabled with the free version, please upgrade to PRO to take bookings via PayPal or Stripe', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                    </div>
                </div>
                <div id="color-options" class="tab-content">
                    <?php require VRCALENDAR_PLUGIN_DIR.'/Admin/Views/Part/AddCalendar/Color.php'; ?>
                </div>
                <div>
                    <?php wp_nonce_field( 'vr-calendar-sync-nonce','vr-calendar-sync-nonce' ); ?>
                    <input type="hidden" name="vrc_cmd" id="vrc_cmd" value="saveCalendar" />
                    <input type="hidden" name="calendar_id" id="calendar_id" value="<?php echo esc_html($cdata->calendar_id); ?>" />
                    <input type="submit" value="<?php _e('Save', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" class="button button-primary">
                </div>
            </form>
        </div>
    </div>
</div>
