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
                <?php _e('Default BG Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" name="calendar_layout_options[default_bg_color]" value="<?php echo esc_html($cdata->calendar_layout_options['default_bg_color']); ?>" class="vrc-color-picker" data-default-color="<?php echo esc_html($cdata->calendar_layout_options['default_bg_color']); ?>">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Default Font Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" name="calendar_layout_options[default_font_color]" value="<?php echo esc_html($cdata->calendar_layout_options['default_font_color']); ?>" class="vrc-color-picker" data-default-color="<?php echo esc_html($cdata->calendar_layout_options['default_font_color']); ?>">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Calendar Border Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" name="calendar_layout_options[calendar_border_color]" value="<?php echo esc_html($cdata->calendar_layout_options['calendar_border_color']); ?>" class="vrc-color-picker" data-default-color="<?php echo esc_html($cdata->calendar_layout_options['calendar_border_color']); ?>">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Week Header BG Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" name="calendar_layout_options[week_header_bg_color]" value="<?php echo esc_html($cdata->calendar_layout_options['week_header_bg_color']); ?>" class="vrc-color-picker" data-default-color="<?php echo esc_html($cdata->calendar_layout_options['week_header_bg_color']); ?>">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Week Header Font Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" name="calendar_layout_options[week_header_font_color]" value="<?php echo esc_html($cdata->calendar_layout_options['week_header_font_color']); ?>" class="vrc-color-picker" data-default-color="<?php echo esc_html($cdata->calendar_layout_options['week_header_font_color']); ?>">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Available BG Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" name="calendar_layout_options[available_bg_color]" value="<?php echo esc_html($cdata->calendar_layout_options['available_bg_color']); ?>" class="vrc-color-picker" data-default-color="<?php echo esc_html($cdata->calendar_layout_options['available_bg_color']); ?>">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Available Font Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" name="calendar_layout_options[available_font_color]" value="<?php echo esc_html($cdata->calendar_layout_options['available_font_color']); ?>" class="vrc-color-picker" data-default-color="<?php echo esc_html($cdata->calendar_layout_options['available_font_color']); ?>">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Unavailable BG Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" name="calendar_layout_options[unavailable_bg_color]" value="<?php echo esc_html($cdata->calendar_layout_options['unavailable_bg_color']); ?>" class="vrc-color-picker" data-default-color="<?php echo esc_html($cdata->calendar_layout_options['unavailable_bg_color']); ?>">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Unavailable Font Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" name="calendar_layout_options[unavailable_font_color]" value="<?php echo esc_html($cdata->calendar_layout_options['unavailable_font_color']); ?>" class="vrc-color-picker" data-default-color="<?php echo esc_html($cdata->calendar_layout_options['unavailable_font_color']); ?>">
            </td>
        </tr>
    </tbody>
</table>
