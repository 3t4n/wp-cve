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
<table class="form-table">
    <tbody>
    <tr valign="top">
        <th>
            <?php _e('Auto Sync', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="auto_sync" name="auto_sync" class="large-text">
                <?php foreach ($auto_sync as $val=>$text):
                    $selected = '';
                    if ($val == $VRCalendarSettings->getSettings('auto_sync', 'daily') ) {
                        $selected = 'selected="selected"';
                    }
                    ?>
                    <option value="<?php echo esc_html($val); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($text); ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('Show Attribution Link Below Calendar?', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="attribution" name="attribution" class="large-text">
                <?php foreach ($attribution as $val=>$text):
                    $selected = '';
                    if ($val == $VRCalendarSettings->getSettings('attribution') ) {
                        $selected = 'selected="selected"';
                    }
                    ?>
                <option value="<?php echo esc_html($val); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($text); ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('Load jquery-ui.css?', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="load_jquery_ui_css" name="load_jquery_ui_css" class="large-text">
                <?php foreach($load_jquery_ui_css as $val=>$text):
                    $selected = '';
                    if ($val == $VRCalendarSettings->getSettings('load_jquery_ui_css', 'yes') ) {
                        $selected = 'selected="selected"';
                    }
                    ?>
                    <option value="<?php echo esc_html($val); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($text); ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    </tbody>
</table>
