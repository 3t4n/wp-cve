<?php

$wunderAuto   = wa_wa();
$filters      = $wunderAuto->getObjects('filter');
$actions      = $wunderAuto->getObjects('action');
$filterGroups = $wunderAuto->getGroups($filters);
$actionGroups = $wunderAuto->getGroups($actions);

use WunderAuto\Types\Internal\WorkflowState;
use WunderAuto\Types\Internal\ReTriggerState;

assert(isset($this) && $this instanceof WunderAuto\Admin);
$settings = $this->getSettingsForView('automation-workflow');
assert($settings instanceof WorkflowState || $settings instanceof ReTriggerState);

$data = json_encode(
    (object)[
        'steps'        => $settings->steps,
        'filters'      => $filters,
        'filterGroups' => $filterGroups,
        'actions'      => $actions,
        'actionGroups' => $actionGroups,
    ]
);
?>

<div id="stepsmetabox"></div>

<template id="steps-component">
    <div v-if="steps.length === 0">
        <p class="tw-px-20 tw-mb-16">
            Begin by adding steps
        </p>
    </div>

    <transition-group name="flip-list" tag="div">
    <div v-for="(step, stepKey) in steps"
         class="wa-step-item tw-border-2 tw-border-solid tw-border-gray-300 tw-mb-4 tw-pb-2 tw-border-rounded-md"
         :key="step.key">
        <div class="tw-p-1 tw-bg-gray-200 tw-flex tw-justify-between tw-items-center">
            <div class="tw-pl-3">
                <span style="text-transform: capitalize">
                    {{ step.type }}
                </span>
                <span>
                    {{ stepCaption(stepKey) }}
                </span>
                <span v-if="$root.delayed && step.type == 'filters' && stepKey == 0">
                    &nbsp;
                   <input type="checkbox" v-model="step.evalBeforeScheduling">
                    <?php _e('Evaluate before scheduling execution  ', 'wunderauto');?>
                </span>
            </div>
            <div class="tw-mr-1">
                <button class="tw-ml-2" title="Move this step up"
                        :disabled="stepKey < 1"
                        @click.prevent="reorderStep(stepKey, -1)">
                    <span class="dashicons dashicons-arrow-up"></span>️
                </button>
                <button class="tw-ml-2" title="Move this step down"
                        :disabled="stepKey >= (steps.length - 1)"
                         @click.prevent="reorderStep(stepKey, 1)">
                    <span class="dashicons dashicons-arrow-down"></span>️
                </button>
                <button class="tw-ml-2" title="Minimize"
                        @click.prevent="toggleStep(stepKey)"
                        v-if="!step.minimized">
                    <span class="dashicons dashicons-minus"></span>️
                </button>
                <button class="tw-ml-2" title="Restore"
                        @click.prevent="toggleStep(stepKey)"
                        v-if="step.minimized">
                    <span class="dashicons dashicons-editor-expand"></span>️
                </button>
                <button class="tw-ml-2"
                        @click.prevent="removeStep(stepKey)"
                        title="<?php _e('Delete this step', 'wunderauto');?>">
                    <span class="dashicons dashicons-no"></span>
                </button>
            </div>
        </div>

        <div v-if="step.type === 'action' && !step.minimized" :set="currentAction=step.action">
            <?php include __DIR__ . '/components/action.php'?>
        </div>

        <div v-if="step.type === 'filters' && !step.minimized" :set="currentFilters=step.filterGroups">
            <?php include __DIR__ . '/components/filter.php'?>
        </div>

    </div>

    </transition-group>

    <div class="tw-my-4 tw-flex tw-justify-evenly">
        <button
                class="wunder-default-button tw-ml-2"
                @click.prevent="addFilterStep()"
        >
            <?php _e('Add filter step', 'wunderauto');?>
        </button>
        <button
                class="wunder-default-button tw-ml-2"
                @click.prevent="addActionStep()"
        >
            <?php _e('Add action step', 'wunderauto');?>
        </button>
    </div>
</template>

<div id="steps-data" style="display:none;">
    <?php echo $data !== false ? esc_attr($data) : '' ?>
</div>

<?php include __DIR__ . '/components/parameters.php'?>