<div id="v-wunderauto-schedule" style="display: none">
    <div class="wunderauto-schedule">
        <select v-model="schedule.when">
            <option value="direct"><?php _e('Run direct (default)', 'wunderauto');?></option>
            <option value="delayed"><?php _e('Delayed for', 'wunderauto');?></option>
        </select>

        <div v-if="schedule.when=='delayed'">
            <input v-model="schedule.ç" type="number" step="1" min="1" max="99999999" maxlength="3" width="60">
            <select v-model="schedule.delayTimeUnit">
                <option value="minutes"><?php _e('Minutes', 'wunderauto');?></option>
                <option value="hours"><?php _e('Hours', 'wunderauto');?></option>
                <option value="days"><?php _e('Days', 'wunderauto');?></option>
                <option value="weeks"><?php _e('Weeks', 'wunderauto');?></option>
            </select>
        </div>
    </div>
</div>