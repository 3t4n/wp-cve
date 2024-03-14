<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\OrderNote'">

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Object', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.object" class="tw-w-full">
                <option v-for="item in $root.currentObjects(stepKey, ['post', 'order'])"
                        :value="item.id">
                    {{ item.id }}
                </option>
            </select>
            <br>
            <i>
                <?php _e(
                    'Object to add the note to',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>


    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Note type', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.type" class="tw-w-full">
                <option value="private">
                    <?php _e('Private note', 'wunderauto')?>
                </option>
                <option value="customer">
                    <?php _e('Note to customer', 'wunderauto')?>
                </option>
            </select>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Message', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <textarea v-model="step.action.value.content"
                      rows="6" style="width: 100%;">
            </textarea>
        </div>
    </div>

</div>



