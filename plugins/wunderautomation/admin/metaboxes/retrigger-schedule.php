<?php

use WunderAuto\Types\Internal\ReTriggerState;

assert(isset($this) && $this instanceof WunderAuto\Admin);
$settings = $this->getSettingsForView('automation-retrigger');
assert($settings instanceof ReTriggerState);

$data = json_encode(
    (object)[
        'schedule' => $settings->schedule,
    ]
);
?>

<div id="retriggerschedule-metabox"></div>

<template id="retriggerschedule-component">
    <div class="wunderauto-trigger">
        <div class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <div class="tw-w-32 tw-text-base">
                <?php esc_html_e('Run every', 'wunderauto')?>
            </div>

            <div class="tw-w-full tw-mt-2 md:tw-mt-0 tw-ml-0 md:tw-ml-2">
                <select v-model="schedule.frequency">
                    <option value="manual"><?php _e('Manual', 'wunderauto');?></option>
                    <option disabled><?php _e('Hour (Pro only)', 'wunderauto');?></option>
                    <option value="day"><?php _e('Day', 'wunderauto');?></option>
                    <option disabled><?php _e('Week (Pro only)', 'wunderauto');?></option>
                    <option disabled><?php _e('Month (Pro only)', 'wunderauto');?></option>
                </select>
            </div>
        </div>

        <div v-if="schedule.frequency != 'manual'"
             class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <div class="tw-w-32 tw-text-base" v-if="schedule.frequency != 'hour'">
                <?php esc_html_e('Time of day', 'wunderauto')?>
            </div>

            <div class="tw-w-full tw-mt-2 md:tw-mt-0 tw-ml-0 md:tw-ml-2">
                <span class="tw-mr-2" v-if="['day', 'week', 'month'].includes(schedule.frequency)">
                    <select v-model="schedule.frequencyHour">
                        <?php for ($i = 0; $i < 24; $i++) :?>
                        <option value="<?php echo $i?>">
                            <?php echo str_pad((string)$i, 2, '0', STR_PAD_LEFT);?>
                        </option>
                        <?php endfor; ?>
                    </select>
                </span>
                <span>
                    <select v-model="schedule.frequencyMinute">
                        <option value="0">00</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                    </select>
                </span>
                <br>
                <i v-if="['day', 'week', 'month'].includes(schedule.frequency)">
                    <?php
                    _e(
                        'Use the hour and minute dropdowns to set an approximate time of day for when this ' .
                        're-trigger should run',
                        'wunderauto'
                    );
                    ?>
                </i>
            </div>
        </div>

        <div v-if="schedule.frequency == 'manual'"
             class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <div class="tw-w-32 tw-text-base">
            </div>

            <div class="tw-w-full tw-mt-2 md:tw-mt-0 tw-ml-0 md:tw-ml-2">
                <?php _e(
                    'Use the <strong>Run now</strong> link in the Save metabox to run this re-trigger',
                    'wunderauto'
                );?>
            </div>
        </div>
    </div>
</template>

<div id="retriggerquery-schedule-data" style="display:none;">
    <?php echo $data !== false ? esc_attr($data) : ''; ?>
</div>



