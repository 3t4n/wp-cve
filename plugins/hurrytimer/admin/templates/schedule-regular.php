<?php namespace Hurrytimer; ?>

 <table class="form-table  hidden mode-settings" data-for="hurrytModeRegular">
<tr class="form-field" >
            <td><label><?php _e("End date/time", "hurrytimer") ?> <span
                            title="This uses the WordPress timezone set under Settings → General. Current timezone: <?php echo hurryt_current_timezone_string() ?>"
                            class="hurryt-icon" data-icon="help" style="vertical-align: middle;"></span></label></label></td>
            <td>
                <label for="hurrytimer-end-datetime" class="date">
                    <input type="text" name="end_datetime" autocomplete="off"
                           id="hurrytimer-end-datetime"
                           class="hurrytimer-datepicker"
                           value="<?php echo $campaign->endDatetime ?>"
                    >
                </label>
            </td>
        </tr>
        
 </table>
