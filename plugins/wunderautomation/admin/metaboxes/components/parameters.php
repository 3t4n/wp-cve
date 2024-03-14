<?php

if (!defined('ABSPATH')) {
    exit;
}
require_once __DIR__ . '/parameters/parameterfields.php';

$wunderAuto      = wa_wa();
$parameters      = $wunderAuto->getObjects('parameter');
$parameterFields = getParameterFields();
$utm             = '?utm_source=dashboard&utm_medium=workfloweditor&utm_campaign=installed_users"';
$data            = json_encode(
    (object)[
        'parameters' => $parameters,
    ]
);

$min = PHP_INT_MIN;
$max = PHP_INT_MAX;
?>

<script>
    var editorValues = {
        counter: 0,
        <?php foreach ($parameterFields as $field) : ?>
        '<?php echo esc_js($field->getModel())?>': '',
        <?php endforeach;?>
    };

    var editorConfig = {
        <?php foreach ($parameterFields as $field) : ?>
        '<?php echo esc_js($field->getModel())?>': {
            'variable': '<?php echo esc_js($field->getVariable()) ?>',
        },
        <?php endforeach;?>
    };
</script>

<div id="paramsmetabox"></div>

<template id="parameters-component">
    <div class="tw-flex tw-flex-col">
        <div>
            <span v-for="object in $root.currentObjects(this.stepKey - 1)">
                <span class="object-pill"
                      :class="{selected: selectedTab === object.id}"
                      @click="selectedTab = object.id === selectedTab ? false : object.id;
                              selectedGroup = object.type !== '*' ? object.type : 'general';">
                    {{ object.id }}
                </span>
                <wbr/>
            </span>
        </div>

        <div v-if="!selectedTab" class="tw-mt-2">
            Click on any of the available objects above to see its parameters.
        </div>
        <div v-for="object in $root.currentObjects(this.stepKey - 1)"
             class="tw-flex tw-flex-col sm:tw-flex-row">
            <div v-show="selectedTab === object.id"
                 class="tw-p-2 tw-mt-2 tw-border-2 tw-border-solid tw-border-gray-300 tw-w-full"
                 :set="parameterGroups = paramsForObjectIds(object.type)">
                <template v-if="Object.keys(parameterGroups).length > 1">
                    Groups:<br>
                    <span class="parameter-group-pill"
                          :class="{selected: selectedGroup === groupName}"
                          v-for="(group, groupName) in parameterGroups"
                          @click="selectedGroup = groupName === selectedGroup ? false : groupName">
                    {{ groupName }}
                    </span>
                    <br>
                </template>
                Parameters:
                <div v-for="(group, groupName) in paramsForObjectIds(object.type)">
                    <span v-for="(item, itemClass) in group">
                        <template v-if="groupName === selectedGroup">
                            <span class="parameter-pill"
                                  :class="{ispro: item.isPro && !$root.shared.isPro}"
                                  @click="openEditor(itemClass, object.id, item.isPro && !$root.shared.isPro);">
                                {{ item.title.replace(/\-/g, '\u2011') }}
                            </span>
                            <wbr/>
                        </template>
                    </span>
                </div>
            </div>


        </div>
        <hr>
        <a href="<?php echo esc_url(wa_make_link('/docs/parameters/', $utm))?>" target="_blank">
            <?php _e('Parameters documentation', 'wunderauto'); ?>
        </a>

        <template v-if="editor.visible == true">
            <!-- Parameter edtitor modal -->
            <!-- overlay -->
            <div
                    id="parameterEditor"
                    class="tw-overflow-auto tw-fixed tw-inset-0 tw-z-10 tw-flex tw-items-start tw-justify-center"
                    style="background-color: rgba(0,0,0,.5);"
                    @click="editor.visible = false"
            >
                <!-- dialog -->
                <div
                        class="tw-bg-white tw-shadow-2xl tw-mt-20 tw-m-4 tw-p-4"
                        style="width: 600px;"
                        @click.stop
                        @_keydown.escape.window="editor.visible = false"
                >
                    <!-- header -->
                    <div class="tw-flex tw-justify-between tw-items-center tw-border-b tw-p-2 tw-text-xl">
                        <span class="tw-my-0">{{ parameterTag }}</span>
                        <button
                            class="tw-mt-0 tw-rounded-full tw-bg-white"
                            type="button"
                            @click="editor.visible = false">✖
                        </button>
                    </div>

                    <!-- content -->
                    <template v-if="editor.phpClass != null">
                        <div class="tw-p-2">
                            <div v-if="editor.proPromo">
                                <p><b>
                                    <?php echo __(
                                        'This parameter is only available in WunderAutomation Pro',
                                        'wunderauto'
                                    ); ?>
                                    </b></p>
                                <br>
                            </div>
                            <span>{{ parameters[editor.phpClass].description }}</span>
                            <div v-if="editor.proPromo">
                                <br>
                                <a href="#">
                                    <?php echo __('Upgrade', 'wunderauto'); ?>
                                </a>
                                <br>
                            </div>
                            <div class="fields tw-flex tw-flex-col tw-mt-2">
                                <?php foreach ($parameterFields as $field) : ?>
                                    <?php $label = $field->isDynamic() ?
                                        '{{' . $field->getLabel() . '}}' :
                                        $field->getLabel();

                                    $description = $field->isDynamic() ?
                                        '{{' . $field->getDescription() . '}}' :
                                        $field->getDescription();

                                    $label       = htmlentities($label);
                                    $description = htmlentities($description);
                                    ?>

                                    <?php if ($field->getCondition() !== '') : ?>
                                        <template v-if="<?php esc_attr_e($field->getCondition()) ?>">
                                    <?php endif ?>
                                    <div class="tw-flex tw-mt-2 tw-flex-row">
                                        <div class="tw-w-32">
                                            <label for="<?php esc_attr_e($field->getModel())?>">
                                                <?php esc_html_e($label) ?>
                                            </label>
                                        </div>
                                        <div class="tw-ml-3 tw-w-full">

                                            <?php if ($field->getType() === 'text') : ?>
                                                <input
                                                    id="<?php esc_attr_e($field->getModel())?>"
                                                    type="text"
                                                    class="tw-w-full"
                                                    v-model="editor.values.<?php esc_attr_e($field->getModel())?>"
                                                >
                                            <?php endif ?>

                                            <?php if ($field->getType() === 'number') : ?>
                                                <input
                                                    id="<?php esc_attr_e($field->getModel())?>"
                                                    type="number"
                                                    class="tw-w-full"
                                                    step="1"
                                                    min="<?php echo (int)$field->getMin()?>"
                                                    max="<?php echo (int)$max?>"
                                                    v-model="editor.values.<?php esc_attr_e($field->getModel())?>"
                                                >
                                            <?php endif ?>

                                            <?php if ($field->getType() === 'checkbox') : ?>
                                                <input
                                                    id="<?php esc_attr_e($field->getModel())?>"
                                                    type="checkbox"
                                                    class="tw-w-full"
                                                    v-model="editor.values.<?php esc_attr_e($field->getModel())?>"
                                                >
                                            <?php endif ?>

                                            <?php if ($field->getType() === 'select') : ?>
                                                <select
                                                    id="<?php esc_attr_e($field->getModel())?>"
                                                    class="tw-w-full"
                                                    v-model="editor.values.<?php esc_attr_e($field->getModel())?>">
                                                    <?php foreach ($field->getOptionsArray() as $option) : ?>
                                                        <option value="<?php esc_attr_e($option->value)?>">
                                                            <?php esc_attr_e($option->label)?>
                                                        </option>
                                                    <?php endforeach ?>
                                                </select>
                                            <?php endif ?>

                                            <?php if ($field->getType() === 'dynamic-select') : ?>
                                                <select
                                                    id="<?php esc_attr_e($field->getModel()) ?>"
                                                    class="tw-w-full"
                                                    v-model="editor.values.<?php esc_attr_e($field->getModel()) ?>">
                                                    <template v-for="<?php esc_attr_e($field->getOptionsString())?>">
                                                        <option :value="key">{{ value }}</option>
                                                    </template>
                                                </select>
                                            <?php endif ?>

                                            <?php if ($field->getType() === 'dynamic-select2') : ?>
                                                <select
                                                    id="<?php esc_attr_e($field->getModel()) ?>"
                                                    class="tw-w-full"
                                                    v-model="editor.values.<?php esc_attr_e($field->getModel()) ?>">
                                                    <template v-for="<?php esc_attr_e($field->getOptionsString())?>">
                                                        <option :value="item.value">{{ item.label }}</option>
                                                    </template>
                                                </select>
                                            <?php endif ?>

                                            <?php if ($field->getType() === 'dynamic-select-objects') : ?>
                                                <select
                                                    id="<?php esc_attr_e($field->getModel()) ?>"
                                                    class="tw-w-full"
                                                    v-model="editor.values.<?php esc_attr_e($field->getModel())?>">
                                                    <option :value="item.id"
                                                            v-for="<?php esc_attr_e($field->getOptionsString())?>"
                                                    >{{ item.id }}
                                                    </option>
                                                </select>
                                            <?php endif ?>

                                            <?php if ($field->getType() === 'dynamic-select-grouped') : ?>
                                                <select
                                                    id="<?php esc_attr_e($field->getModel())?>"
                                                    class="tw-w-full"
                                                    v-model="editor.values.<?php esc_attr_e($field->getModel())?>">
                                                    <option>Select a value</option>
                                                    <optgroup v-for="<?php esc_attr_e($field->getOptionsString())?>"
                                                              :label="groupKey">
                                                        <option v-for="item in group" :value="item.value">
                                                            {{ item.label }}
                                                        </option>
                                                    </optgroup>
                                                </select>
                                            <?php endif ?>

                                            <?php if ($description !== '') : ?>
                                                <br/>
                                                <div class="tw-mt-0">
                                                    <i><span><?php esc_html_e($description)?></span></i>
                                                </div>
                                            <?php endif ?>
                                        </div>
                                    </div>

                                    <?php if ($field->getCondition() !== '') : ?>
                                        </template>
                                    <?php endif ?>
                                <?php endforeach; ?>
                            </div>
                            <div v-if="!editor.proPromo" class="result tw-mt-6 tw-py-5 tw-text-center tw-bg-gray-300">
                                <span>{{ editorResult }}</span>
                            </div>
                        </div>
                    </template>
                    <div class="tw-flex tw-flex-row tw-justify-end tw-space-between-2 tw-px-2">
                        <button class="wunder-default-button tw-ml-2"
                                @click.prevent="editor.visible = false">
                            <?php _e('Cancel', 'wunderauto'); ?>
                        </button>
                        <button v-if="!editor.proPromo"
                                class="wunder-default-button tw-ml-2"
                                @click.prevent="closeEditor('copy')">
                            <?php _e('Copy to clipboard', 'wunderauto'); ?>
                        </button>
                        <button v-if="!editor.proPromo"
                                class="wunder-default-button tw-ml-2"
                                @click.prevent="closeEditor('insert')">
                            <?php _e('Insert', 'wunderauto'); ?>
                        </button>
                    </div>
                </div><!-- /dialog -->
            </div><!-- /overlay -->
        </template>

    </div>
</template>

<div id="parameters-data" style="display:none;">
    <?php echo $data !== false ? esc_attr($data) : '' ?>
</div>