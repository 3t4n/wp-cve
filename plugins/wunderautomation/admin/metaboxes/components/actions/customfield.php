<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\ChangeCustomField'">

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Object', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.type" class="tw-w-full">
                <option v-for="item in $root.currentObjects(stepKey, ['post', 'order', 'user'])"
                        :value="item.id">
                    {{ item.id }}
                </option>
            </select>
            <br>
            <i>
                <?php _e(
                    'The workflow object to add / change custom field on',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Field', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.fieldName" class="tw-w-full"/>
            <br>
            <i>
                <?php _e(
                    'Custom field identifier',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('New value', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input v-model="step.action.value.newValue"
                   v-if="!step.action.value.multiLine"
                   class="tw-w-full"/>
            <textarea v-model="step.action.value.newValue"
                      v-if="step.action.value.multiLine"
                      rows="8" style="width: 100%;">
            </textarea>
            <br>
            Multiline: <input type="checkbox" v-model="step.action.value.multiLine"/>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Data type', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.dataType" class="tw-w-full">
                <option value = "string">
                    <?php _e('Standard / string', 'wunderauto')?>
                </option>
                <option value="int">
                    <?php _e('Integer', 'wunderauto')?>
                </option>
                <option value="float">
                    <?php _e('Floating point number', 'wunderauto')?>
                </option>
                <option value="json">
                    <?php _e('JSON', 'wunderauto')?>
                </option>
            </select>
            <br>
            <i v-if="['array', 'object'].includes(step.action.value.dataType)">
                <?php _e(
                    'JSON datatype assumes a valid JSON string (array or object) to be entered in the New value field',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>
</div>



