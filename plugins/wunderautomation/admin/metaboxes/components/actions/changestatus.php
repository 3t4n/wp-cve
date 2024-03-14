<?php
$wp_post_statuses = wa_get_wp_post_statuses();
?>
<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\ChangeStatus'">
    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Object type', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.type" class="tw-w-full">
                <option v-for="item in $root.currentObjects(stepKey, ['post', 'order', 'comment'])"
                        :value="item.id">
                    {{ item.id }}
                </option>
            </select>
            <br>
            <i>
                <?php _e(
                    'The object to change status on',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Set new status', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-if="step.action.value.type !== 'comment'"
                    v-model="step.action.value.newStatus" class="tw-w-full">
                <option v-for="item in $root.shared.postStatuses"
                        :value="item.value">
                    {{ item.label }}
                </option>
            </select>
            <select v-if="step.action.value.type === 'comment'"
                    v-model="step.action.value.newStatus" class="tw-w-full">
                <option value="0"><?php esc_html_e(__('Unapproved'));?></option>
                <option value="1"><?php esc_html_e(_x('Approved', 'comment status'));?></option>
                <option value="spam"><?php esc_html_e(_x('Spam', 'comment status'));?></option>
                <option value="trash"><?php esc_html_e(_x('Trash', 'comment status'));?></option>
            </select>
            <br>
            <i>
                <?php _e(
                    'The new status to set',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Manual', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input type="checkbox" v-model="step.action.value.manualStatusChange">
            <br>
            <i>
                <?php _e(
                    'Check this if you want the order status change to appear as being manual',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>


</div>



