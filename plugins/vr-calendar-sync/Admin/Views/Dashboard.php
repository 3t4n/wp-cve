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
?>
<div class="wrap vrcal-content-wrapper">
    <h2><?php echo esc_html(VRCALENDAR_PLUGIN_NAME); ?></h2>
    <p class="vc-dash-banner">
        <a href="http://vrcalendarsync.com/" target="_blank"><img src="<?php echo esc_url(VRCALENDAR_PLUGIN_URL.'/assets/images/dashboard-banner.png'); ?>" /></a>
    </p>
    <?php require VRCALENDAR_PLUGIN_DIR.'/Admin/Views/Part/Dashboard/MyCalendars.php'; ?>
</div>
