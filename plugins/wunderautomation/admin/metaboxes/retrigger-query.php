<?php

use WunderAuto\Types\Internal\ReTriggerState;

assert(isset($this) && $this instanceof WunderAuto\Admin);
$settings = $this->getSettingsForView('automation-retrigger');
assert($settings instanceof ReTriggerState);

$data = json_encode(
    (object)[
        'query'    => $settings->query,
        'triggers' => [
            'post'    => new \WunderAuto\Types\Triggers\Post\ReTriggered(),
            'user'    => new \WunderAuto\Types\Triggers\User\ReTriggered(),
            'comment' => new \WunderAuto\Types\Triggers\Comment\ReTriggered(),
            'order'   => new \WunderAuto\Types\Triggers\Order\ReTriggered(),
        ]
    ]
);
?>

<div id="retriggerquery-metabox"></div>

<template id="retriggerquery-component">
    <div class="wunderauto-trigger">
        <div class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <div class="tw-w-32 tw-text-base">
                <?php esc_html_e('Object type', 'wunderauto')?>
            </div>

            <div class="tw-w-full tw-mt-2 md:tw-mt-0 tw-ml-0 md:tw-ml-2">
                <select v-model="query.objectType">
                    <option value="post"><?php _e('Post', 'wunderauto')?></option>
                    <option value="user"><?php _e('User', 'wunderauto')?></option>
                    <option value="comment"><?php _e('Comment', 'wunderauto')?></option>
                    <option value="order"><?php _e('Order', 'wunderauto')?></option>
                </select>

            </div>
        </div>

        <div v-if="query.objectType == 'post'"
             class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <div class="tw-w-32 tw-text-base">
                <?php esc_html_e('Post type', 'wunderauto')?>
            </div>

            <div class="tw-w-full tw-mt-2 md:tw-mt-0 tw-ml-0 md:tw-ml-2">
                <select v-model="query.postType">
                    <option v-for="item in $root.shared.postTypes"
                            :value="item.value">{{ item.label }}</option>
                </select>

            </div>
        </div>

        <div v-if="query.objectType == 'post'"
            class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <div class="tw-w-32 tw-text-base">
                <?php esc_html_e('Post status', 'wunderauto')?>
            </div>

            <div class="tw-w-full tw-mt-2 md:tw-mt-0 tw-ml-0 md:tw-ml-2">
                <select v-model="query.postStatus">
                    <option value="any"><?php _e('Any post status', 'wunderauto')?></option>
                    <option v-for="item in $root.shared.postStatuses"
                            :value="item.value">{{ item.label }}</option>
                </select>
            </div>
        </div>

        <div v-if="query.objectType == 'order'"
             class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <div class="tw-w-32 tw-text-base">
                <?php esc_html_e('Order status', 'wunderauto')?>
            </div>

            <div class="tw-w-full tw-mt-2 md:tw-mt-0 tw-ml-0 md:tw-ml-2">
                <select v-model="query.postStatus">
                    <option value="any"><?php _e('Any post status', 'wunderauto')?></option>
                    <option v-for="item in $root.shared.postStatuses"
                            :value="item.value">{{ item.label }}</option>
                </select>
            </div>
        </div>

        <div class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <div class="tw-w-32 tw-text-base">
                <?php esc_html_e('Created', 'wunderauto')?>
            </div>

            <div class="tw-w-full tw-mt-2 md:tw-mt-0 tw-ml-0 md:tw-ml-2">
                <input v-model="query.created" type="number" step="1" min="1" max="99999999"
                       maxlength="3" width="60">
                <select v-model="query.createdTimeUnit">
                    <option value="minutes"><?php _e('Minutes', 'wunderauto');?></option>
                    <option value="hours"><?php _e('Hours', 'wunderauto');?></option>
                    <option value="days"><?php _e('Days', 'wunderauto');?></option>
                    <option value="weeks"><?php _e('Weeks', 'wunderauto');?></option>
                </select>
                Ago
                <br>
                <i>
                    <?php _e(
                        'Limits the number of returned objects by creation time.',
                        'wunderauto'
                    ); ?>
                </i>
            </div>
        </div>
    </div>
</template>

<div id="retriggerquery-query-data" style="display:none;">
    <?php echo $data !== false ? esc_attr($data) : '' ?>
</div>



