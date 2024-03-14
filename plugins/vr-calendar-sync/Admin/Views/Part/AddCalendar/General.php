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
                <?php _e('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_name" name="calendar_name" value="<?php echo esc_html($cdata->calendar_name); ?>" class="large-text" placeholder="Name">
            </td>
        </tr>
        <tr valign="top">
            <th colspan="2">
                <?php _e('Calendar Links', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> <a href="javascript:void(0)" class="add-new-h2" id="add-more-calendar-links"><?php _e('Add More', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>

                <table class="form-table" id="calendar-links">

                    <?php
                    if (count($cdata->calendar_links)>0) {
                        foreach ($cdata->calendar_links as $clink) {
                            ?>
                            <tbody class="calendar_link_row">
                            <tr valign="top" >
                                <td>
                                    <table class="form-table">
                                        <tbody>
                                        <tr>
                                            <th>
                                                <?php _e('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                                            </th>
                                            <td>
                                                <input type="text" name="calendar_links[name][]" value="<?php echo esc_html($clink->name); ?>" class="large-text" placeholder="Name" />
                                                <a href="javascript:void(0)" class="remove-calendar-link vrc-remove-link"><?php _e('Remove', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <?php _e('Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                                            </th>
                                            <td>
                                                <input type="text" name="calendar_links[url][]" value="<?php echo esc_url($clink->url); ?>" class="large-text" placeholder="ics/ical Link" />
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                            <?php
                        }
                    }
                    ?>
                </table>
            </th>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Columns', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <select name="calendar_layout_options[columns]" class="large-text">
                <?php for ($i=1;$i<=12; $i++):
                    $selected = '';
                    if ($cdata->calendar_layout_options['columns'] == $i) {
                        $selected = 'selected="selected"';
                    }
                    ?>
                    <option value="<?php echo esc_html($i); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($i); ?></option>
                <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Rows', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <select name="calendar_layout_options[rows]" class="large-text">
                    <?php for ($i=1;$i<=12; $i++):
                        $selected = '';
                        if ($cdata->calendar_layout_options['rows'] == $i) {
                            $selected = 'selected="selected"';
                        }
                        ?>
                        <option value="<?php echo esc_html($i); ?>" <?php echo esc_html($selected); ?>><?php echo esc_html($i); ?></option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Size', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                    <?php foreach ($layout_option_size as $sizek=>$sizev):
                        $checked = '';
                        if ($sizek == $cdata->calendar_layout_options['size']) {
                            $checked = 'checked="checked"';
                        }
                        ?>
                        <label title='<?php echo esc_html($sizev); ?>'><input type="radio" name="calendar_layout_options[size]" value="<?php echo esc_html($sizek); ?>" <?php echo esc_html($checked); ?> /> <span><?php echo esc_html($sizev); ?></span></label> &nbsp;
                    <?php endforeach; ?>
                </fieldset>
            </td>
        </tr>
    </tbody>
</table>
<table class="form-table" id="calendar-links-cloner">
    <tbody class="calendar_link_row">
    <tr valign="top">
        <td>
            <table class="form-table">
                <tbody>
                <tr>
                    <th>
                        <?php _e('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                    </th>
                    <td>
                        <input type="text" name="calendar_links[name][]" value="" class="large-text" placeholder="Name" />
                        <a href="javascript:void(0)" class="remove-calendar-link vrc-remove-link"><?php _e('Remove', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php _e('Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                    </th>
                    <td>
                        <input type="text" name="calendar_links[url][]" value="" class="large-text" placeholder="ics/ical Link" />
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
