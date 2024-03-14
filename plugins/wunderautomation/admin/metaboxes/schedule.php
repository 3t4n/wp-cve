<?php

if (!defined('ABSPATH')) {
    exit;
}

$data = json_encode(
    (object)[
        'schedule' => isset($this->workflowSettings->schedule) ? $this->workflowSettings->schedule : null
    ]
);
?>

<div id="schedulemetabox"></div>

<template id="schedule-component">
    <div class="wunderauto-schedule">
        <select v-model="schedule.when">
            <option value="direct"><?php _e('Run direct (default)', 'wunderauto');?></option>
            <option value="delayed"><?php _e('Delayed for', 'wunderauto');?></option>
        </select>

        <div v-if="schedule.when=='delayed'">
            <input v-model="schedule.delayFor" type="number" step="1" min="1" max="99999999" maxlength="3" width="60">
            <select v-model="schedule.delayTimeUnit">
                <option value="minutes"><?php _e('Minutes', 'wunderauto');?></option>
                <option value="hours"><?php _e('Hours', 'wunderauto');?></option>
                <option value="days"><?php _e('Days', 'wunderauto');?></option>
                <option value="weeks"><?php _e('Weeks', 'wunderauto');?></option>
            </select>
        </div>
    </div>
</template>

<div id="schedule-data" style="display:none;">
    <?php echo $data !== false ? esc_attr($data) : '' ?>
</div>
