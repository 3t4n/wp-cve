<?php

$wunderAuto = wa_wa();
$triggers   = $wunderAuto->getObjects('trigger');
$groups     = $wunderAuto->getGroups($triggers);
$utm        = '?utm_source=dashboard&utm_medium=workfloweditor&utm_campaign=installed_users';

$data = json_encode(
    (object)[
        'triggers' => $triggers,
        'groups'   => $groups,
        'trigger'  => isset($this->workflowSettings->trigger) ?
            $this->workflowSettings->trigger :
            (object)['trigger' => null, 'value' => null],
    ]
);
?>

<div id="triggersmetabox"></div>

<template id="triggers-component">
    <div class="wunderauto-trigger">
        <div class="tw-p-2 tw-flex tw-flex-col md:tw-flex-row">
            <div class="tw-w-32 tw-text-base">
                <?php esc_html_e('Trigger', 'wunderauto')?>
            </div>

            <div class="tw-w-full tw-mt-2 md:tw-mt-0 tw-ml-0 md:tw-ml-2">
                <select v-model="trigger.trigger">
                    <option>[<?php _e('Select trigger', 'wunderauto') ?>...]</option>
                    <optgroup v-for="(group, key) in groups" :label="key">
                        <option v-for="item in group"
                                :value="item.class"
                        >
                            {{ item.title }}
                        </option>
                    </optgroup>
                </select>

                <br>
                <br>
                <div v-if="trigger.trigger">
                    <div v-if="triggers[trigger.trigger].supportsOnlyOnce"
                         class="tw-mt-2 md:tw-mt-1"
                    >
                        <input type="checkbox"
                               v-model="trigger.value.onlyOnce">
                        <?php _e('Only run once per', 'wunderauto');?>
                        <span class="object-pill">{{ triggers[trigger.trigger].providedObjects[0].id }} </span>
                    </div>

                    <p>{{ triggers[trigger.trigger].description }}</p>
                    <hr>

                    <?php do_action('wunderauto_trigger_fields') ?>

                    <div v-if="triggers[trigger.trigger].providedObjects.length > 0">
                        <br><strong><?php _e('Objects provided by this trigger', 'wundeauto');?>:</strong><br>
                        <div v-for="(object, key) in triggers[trigger.trigger].providedObjects"
                             class="tw-flex tw-flex-col" >
                            <div>
                                <span class="object-pill">{{ object.id }}</span> :
                                <span>{{ object.description }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="tw-flex tw-flex-row">
                    <div class="tw-flex-none">
                        <a href="<?php echo esc_url(wa_make_link('/docs/triggers/', $utm))?>"
                           target="_blank">
                            <?php _e('Triggers documentation', 'wunderauto');?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<div id="trigger-data" style="display:none;">
    <?php echo $data !== false ? esc_attr($data) : '' ?>
</div>



