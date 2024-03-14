<?php

namespace Hurrytimer;

/**
 * The template for display the recurring campaigns settings
 *
 * @package hurrytimer/admin/templates
 */
?>
<div class="hidden mode-settings" data-for="hurrytModeRecurring" <?php //removeIf(pro) 
                                                                    ?> data-hurryt-pro="wrap" <?php //endRemoveIf(pro) 
                                                                                                ?>>
    <?php //removeIf(pro) 
    ?>
    <div class="hurryt-upgrade-alert hurryt-upgrade-alert-inline" data-hurryt-pro="notice">
        <div class="hurryt-upgrade-alert-header">
            <span class="dashicons dashicons-lock"></span>
            <h3>Recurring Campaigns is a PRO feature</h3>
        </div>
        <div class="hurryt-upgrade-alert-body">Unlock to create unlimited and customizable recurring campaigns.
        </div>
        <div class="hurryt-upgrade-alert-footer">
            <a class="hurryt-button button" href="https://hurrytimer.com/pricing?utm_source=plugin&utm_medium=recurring_mode&utm_campaign=upgrade">Upgrade now</a>
            <a href="https://hurrytimer.com?utm_source=plugin&utm_medium=recurring_mode&utm_campaign=learn_more" class="button">Learn more</a>
        </div>
    </div>
    <?php //endRemoveIf(pro) 
    ?>
    <table class="form-table" <?php //removeIf(pro) 
                                ?> data-hurryt-pro="feature" <?php //endRemoveIf(pro) 
                                                                ?>>
        <tr class="form-field">
            <td><label><?php _e('Recur every', "hurrytimer") ?></label></td>
            <td>
                <div class="hurryt-flex">
                    <div class="hurryt-w-16">
                        <input type="number" min="1" name="recurring_interval" value="<?php echo $campaign->recurringInterval ?>" id="hurrytRecurringInterval" />
                    </div>
                    <div class="hurryt-flex-grow">
                        <select name="recurring_frequency" id="hurrytRecurringFrequency" class="hurryt-w-full">
                            <option value="<?php echo C::RECURRING_MINUTELY ?>" <?php echo selected($campaign->recurringFrequency, C::RECURRING_MINUTELY) ?>>
                                Minute(s)
                            </option>
                            <option value="<?php echo C::RECURRING_HOURLY ?>" <?php echo selected($campaign->recurringFrequency, C::RECURRING_HOURLY) ?>>
                                Hour(s)
                            </option>
                            <option value="<?php echo C::RECURRING_DAILY ?>" <?php echo selected($campaign->recurringFrequency, C::RECURRING_DAILY) ?>>
                                Day(s)
                            </option>
                            <option value="<?php echo C::RECURRING_WEEKLY ?>" <?php echo selected($campaign->recurringFrequency, C::RECURRING_WEEKLY) ?>>
                                Week(s)
                            </option>
                            <option value="<?php echo C::RECURRING_MONTHLY ?>" <?php echo selected($campaign->recurringFrequency, C::RECURRING_MONTHLY) ?>>
                                Month(s)
                            </option>
                        </select>
                    </div>
                </div>
            </td>
        </tr>


        <tr class="form-field" id="hurrytRecurDuration">
            <td>
                <label><?php _e('Duration', "hurrytimer") ?></label>
            </td>
            <td>
               <div id="ht-recurring-duration-option">
               <label >
                    <input type="radio" value="none" name="recurring_duration_option" <?php checked($campaign->recurringDurationOption, 'none') ?> /><span id="ht-monthly-recur-interval"><?php echo $campaign->recurringInterval ?></span> month(s)
                </label>&nbsp;
                <label >
                    <input type="radio" value="custom" name="recurring_duration_option" <?php checked($campaign->recurringDurationOption, 'custom') ?> />Custom...
                </label>
               </div>
                <div class="hurryt-flex" id="ht-recurring-duration">
                    <label class="hurryt-uppercase hurryt-text-gray-700 hurryt-text-xs   hurryt-pr-2">
                        <?php _e("Days", "hurrytimer") ?>
                        <input type="number" class="hurrytimer-duration" name="recurring_duration[]" id="hurrytRecurringDays" class="hurryt-w-full" min="0" value="<?php echo $campaign->recurringDuration[0] ?>">
                    </label>
                    <label class="hurryt-uppercase hurryt-text-gray-700 hurryt-text-xs hurryt-pr-2">
                        <?php _e("Hours", "hurrytimer") ?>
                        <input type="number" name="recurring_duration[]" id="hurrytRecurringHours" class="hurryt-w-full" min="0" value="<?php echo $campaign->recurringDuration[1] ?>">
                    </label>
                    <label class="hurryt-uppercase hurryt-text-gray-700 hurryt-text-xs  hurryt-pr-2">
                        <?php _e("minutes", "hurrytimer") ?>
                        <input type="number" id="hurrytRecurringMinutes" name="recurring_duration[]" class="hurryt-w-full" min="0" value="<?php echo $campaign->recurringDuration[2] ?>">
                    </label>
                    <label class="hurryt-uppercase hurryt-text-gray-700 hurryt-text-xs">
                        <?php _e("seconds", "hurrytimer") ?>
                        <input type="number" id="hurrytRecurringSeconds" name="recurring_duration[]" class="hurryt-w-full" min="0" value="<?php echo $campaign->recurringDuration[3] ?>">
                    </label>
                </div>
            </td>
        </tr>
        <tr class="form-field" id="hurrytRecurPauseDuration">
            <td>
                <label><?php _e('Time before restarting', "hurrytimer") ?></label>
            </td>
            <td>
                <div class="hurryt-flex" >
                    <label class="hurryt-uppercase hurryt-text-gray-700 hurryt-text-xs hurryt-pr-2">
                        <?php _e("Days", "hurrytimer") ?>
                        <input type="number" class="hurrytimer-duration" name="recurring_pause_duration[days]" id="hurrytRecurringPauseDays" class="hurryt-w-full" min="0" value="<?php echo $campaign->recurringPauseDuration['days'] ?>">
                    </label>
                    <label class="hurryt-uppercase hurryt-text-gray-700 hurryt-text-xs hurryt-pr-2">
                        <?php _e("Hours", "hurrytimer") ?>
                        <input type="number" name="recurring_pause_duration[hours]" id="hurrytRecurringPauseHours" class="hurryt-w-full" min="0" value="<?php echo $campaign->recurringPauseDuration['hours'] ?>">
                    </label>
                    <label class="hurryt-uppercase hurryt-text-gray-700 hurryt-text-xs  hurryt-pr-2">
                        <?php _e("minutes", "hurrytimer") ?>
                        <input type="number" id="hurrytRecurringPauseMinutes" name="recurring_pause_duration[minutes]" class="hurryt-w-full" min="0" value="<?php echo $campaign->recurringPauseDuration['minutes'] ?>">
                    </label>
                    <label class="hurryt-uppercase hurryt-text-gray-700 hurryt-text-xs">
                        <?php _e("seconds", "hurrytimer") ?>
                        <input type="number" id="hurrytRecurringPauseSeconds" name="recurring_pause_duration[seconds]" class="hurryt-w-full" min="0" value="<?php echo $campaign->recurringPauseDuration['seconds'] ?>">
                    </label>
                </div>
            </td>
        </tr>
        <tr class="form-field" id="hurrytRecurDaysList">
            <td><label><?php _e('Recur on', "hurrytimer") ?></label></td>
            <td>
                <div class="hurrytimer-field hurryt-flex hurryt-flex-wrap">
                    <?php $locale = new \WP_Locale();

                    $weekdays = [];
                    for ($i = 0; $i <= 6; $i++) {
                        $weekdays[$i] = $locale->get_weekday($i);
                    }

                    ?>
                    <?php foreach ($weekdays as $k => $v) : ?>
                        <label for="" class="hurryt-block hurryt-mb-3 hurryt-w-1/3"><input type="checkbox" <?php echo in_array($k, $campaign->recurringDays)
                                                                                                                ? 'checked' : '' ?> name="recurring_days[]" value="<?php echo $k ?>"><?php echo $v ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div id="hurrytimer-recurring-unselected-days-action" style="display: none;">
                    <p style="font-weight: bold;">On the unselected day(s):</p>
                    <p>
                        <label for="hurrytimer-recurring-unselected-days-action-skip"><input type="radio" value="skip" <?php checked($campaign->recurringUnselectedDaysAction, 'skip') ?> name="recurring_unselected_days_action" id="hurrytimer-recurring-unselected-days-action-skip">Countown to the next selected day</label>
                    </p>
                    <p>
                        <label for="hurrytimer-recurring-unselected-days-action-hide"><input type="radio" value="hide" <?php checked($campaign->recurringUnselectedDaysAction, 'hide') ?> name="recurring_unselected_days_action" id="hurrytimer-recurring-unselected-days-action-hide">Hide the countdown timer</label>
                    </p>
                    </p>
                </div>
            </td>
        </tr>
        <!-- Start time { -->
        <tr class="form-field">
            <td>
                <label><?php _e('Start date/time', "hurrytimer") ?> <span title="This uses the WordPress timezone set under Settings → General. Current timezone: <?php echo hurryt_current_timezone_string() ?>" class="hurryt-icon" data-icon="help" style="vertical-align: middle;"></span></label>
            </td>
            <td>
                <label for="hurrytimer-end-datetime" class="date hurryt-w-full">
                    <input type="text" name="recurring_start_time" autocomplete="off" class="hurrytimer-datepicker hurryt-w-full" placeholder="Select Date/Time" value="<?php echo $campaign->recurringStartTime ?>">
                </label>
            </td>
        </tr>
        <tr class="form-field" id="hurrytRecurMonthlyDayType">

            <td><label><?php _e('On the', "hurrytimer") ?></label></td>
            <td>
                <div class="hurryt-flex">
                    <label class="hurryt-mr-3 hurryt-text-md">
                        <input type="radio" name="recurring_monthly_day_type" value="<?php echo C::RECURRING_MONTHLY_DAY_OF_MONTH ?>" <?php checked($campaign->recurringMonthlyDayType, C::RECURRING_MONTHLY_DAY_OF_MONTH) ?>>
                        <span id="recurDayOfMonth"></span>
                    </label>
                    <label class="hurryt-mr-3 hurryt-text-md">
                        <input type="radio" name="recurring_monthly_day_type" value="<?php echo C::RECURRING_MONTHLY_DAY_OF_WEEK ?>" <?php checked($campaign->recurringMonthlyDayType, C::RECURRING_MONTHLY_DAY_OF_WEEK) ?>>
                        <span id="recurDayOfWeek"></span>

                    </label>
                </div>
            </td>
        </tr>

        <tr class="form-field">
            <td><label for="active"><?php _e("End", "hurrytimer") ?></label></td>
            <td>
                <div class="hurryt-flex hurryt-flex-col">
                    <label for="" class="hurryt-mr-2 hurryt-mb-2"><input type="radio" name="recurring_end" value="<?php echo C::RECURRING_END_NEVER ?>" <?php echo checked(
                                                                                                                                                            $campaign->recurringEnd,
                                                                                                                                                            C::RECURRING_END_NEVER
                                                                                                                                                        ) ?>>Never</label>
                    <label for="" class="hurryt-mb-2"><input type="radio" name="recurring_end" value="<?php echo C::RECURRING_END_OCCURRENCES ?>" <?php echo checked(
                                                                                                                                                        $campaign->recurringEnd,
                                                                                                                                                        C::RECURRING_END_OCCURRENCES
                                                                                                                                                    ) ?>>After <input type="text" name="recurring_count" autocomplete="off" id="hurrytimer-recurring_end_date" style="width: 3em" value="<?php echo $campaign->recurringCount ?>"> recurrences</label>
                    <label for="" class="hurryt-mb-2 date"><input type="radio" name="recurring_end" value="<?php echo C::RECURRING_END_TIME ?>" <?php echo checked(
                                                                                                                                                    $campaign->recurringEnd,
                                                                                                                                                    C::RECURRING_END_TIME
                                                                                                                                                ) ?>>On <input type="text" name="recurring_until" autocomplete="off" class="hurrytimer-datepicker" placeholder="Select Date/Time" style="width: 12em" value="<?php echo $campaign->recurringUntil ?>"></label>
                </div>
            </td>
        </tr>
    </table>
</div>