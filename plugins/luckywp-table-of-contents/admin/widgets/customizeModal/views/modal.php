<?php
/**
 * @var $post WP_Post|null
 * @var $widgetId string|null
 * @var $action string
 * @var $model \luckywp\tableOfContents\admin\forms\CustomizeForm
 * @var $context string
 * @var $onlyBody bool
 */

use luckywp\tableOfContents\admin\widgets\fontSizeField\FontSizeField;
use luckywp\tableOfContents\admin\widgets\OverrideColorBadge;
use luckywp\tableOfContents\admin\widgets\skipHeadingLevelField\SkipHeadingLevelField;
use luckywp\tableOfContents\admin\widgets\widthField\WidthField;
use luckywp\tableOfContents\core\admin\helpers\AdminHtml;
use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\Html;

$labelClickToOverrideDefaultValue = esc_html__('Click to override default value', 'luckywp-table-of-contents');
$labelDefault = esc_html__('default', 'luckywp-table-of-contents');

echo $onlyBody ? '' : '<div class="lwptocCustomize lwptocCustomize-metabox">';
?>
<form
    action="<?= admin_url('admin-ajax.php?_ajax_nonce=' . wp_create_nonce(Core::$plugin->prefix . 'adminMain') . '&action=' . $action . ($post ? '&postId=' . $post->ID : '') . ($widgetId ? '&widgetId=' . $widgetId : '')) ?>"
    data-ajax-form="1"
    class="lwptocModalBox"
>
    <div class="lwptocModalBox_close lwptocModal-close" title="<?= __('Cancel', 'luckywp-table-of-contents') ?>"></div>
    <div class="lwptocModalBox_title"><?= __('Customize Table of Contents', 'luckywp-table-of-contents') ?></div>
    <div class="lwptocModalBox_body">

        <?php
        if ($model->hasErrors()) {
            echo '<div class="lwptocCustomize_errors">';
            foreach ($model->getErrorSummary() as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
        }
        ?>

        <div class="lwptocCustomize_tabs">
            <div class="lwptocCustomize_tab lwptocCustomize_tab-active" data-tab="general"><?= esc_html__('General', 'luckywp-table-of-contents') ?></div>
            <div class="lwptocCustomize_tab" data-tab="appearance"><?= esc_html__('Appearance', 'luckywp-table-of-contents') ?></div>
            <div class="lwptocCustomize_tab" data-tab="misc"><?= esc_html__('Misc.', 'luckywp-table-of-contents') ?></div>
        </div>

        <div class="lwptocCustomize_fields lwptocCustomize_fields-general" style="display:block;">

            <?php if ($model->isPostSettings) { ?>
                <div class="lwptocCustomize_field<?= $model->defaultPosition ? ' lwptocCustomize_field-default' : '' ?>">
                    <div class="lwptocCustomize_field_header">
                        <span class="lwptocCustomize_field_label"><?= __('Position', 'luckywp-table-of-contents') ?></span>
                        <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                    </div>
                    <div class="lwptocCustomize_field_override">
                        <?= $labelClickToOverrideDefaultValue ?>
                    </div>
                    <div class="lwptocCustomize_field_el">
                        <?= Html::dropDownList(Html::getInputName($model, 'position'), $model->defaultPosition ? Core::$plugin->settings->autoInsertPosition : $model->position, Core::$plugin->positionsList, [
                            'class' => 'lwptocCustomize_field_el_select',
                        ]) ?>
                        <?= Html::hiddenInput(Html::getInputName($model, 'defaultPosition'), $model->defaultPosition, [
                            'class' => 'lwptocCustomize_field_inputDefault',
                        ]) ?>
                    </div>
                    <div class="lwptocCustomize_field_defaultValue">
                        <?= Core::$plugin->positionsList[Core::$plugin->settings->autoInsertPosition] ?>
                    </div>
                </div>
            <?php } ?>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->min)) ? Core::$plugin->settings->generalMin : $model->postSettings->min ?>
            <div class="lwptocCustomize_field<?= $model->defaultMin ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Minimal Count of Headings', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::textInput(Html::getInputName($model, 'min'), $model->defaultMin ? $defaultValue : $model->min, ['class' => 'lwptocCustomize_field_el_textInput']) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultMin'), $model->defaultMin, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_desc">
                    <?= __('If the count of headings in the post is less, then table of contents is not displayed.', 'luckywp-table-of-contents') ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= $defaultValue ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->depth)) ? Core::$plugin->settings->generalDepth : $model->postSettings->depth ?>
            <div class="lwptocCustomize_field<?= $model->defaultDepth ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Depth', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::dropDownList(Html::getInputName($model, 'depth'), $model->defaultDepth ? $defaultValue : $model->depth, Core::$plugin->depthsList, [
                        'class' => 'lwptocCustomize_field_el_select',
                    ]) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultDepth'), $model->defaultDepth, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= Core::$plugin->depthsList[$defaultValue] ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->hierarchical)) ? Core::$plugin->settings->generalHierarchical : $model->postSettings->hierarchical ?>
            <div class="lwptocCustomize_field<?= $model->defaultHierarchical ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Hierarchical View', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <label>
                        <?= Html::checkbox(Html::getInputName($model, 'hierarchical'), $model->defaultHierarchical ? $defaultValue : $model->hierarchical, ['uncheck' => 0]) ?>
                        <?= __('Enable', 'luckywp-table-of-contents') ?>
                    </label>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultHierarchical'), $model->defaultHierarchical, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= $defaultValue ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents') ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->numeration)) ? Core::$plugin->settings->generalNumeration : $model->postSettings->numeration ?>
            <div class="lwptocCustomize_field<?= $model->defaultNumeration ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Numeration', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::dropDownList(Html::getInputName($model, 'numeration'), $model->defaultNumeration ? $defaultValue : $model->numeration, Core::$plugin->numerationsList, [
                        'class' => 'lwptocCustomize_field_el_select',
                    ]) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultNumeration'), $model->defaultNumeration, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= Core::$plugin->numerationsList[$defaultValue] ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->numerationSuffix)) ? Core::$plugin->settings->getGeneralNumerationSuffix() : $model->postSettings->numerationSuffix ?>
            <div class="lwptocCustomize_field<?= $model->defaultNumerationSuffix ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Numeration Suffix', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::dropDownList(Html::getInputName($model, 'numerationSuffix'), $model->defaultNumerationSuffix ? $defaultValue : $model->numerationSuffix, Core::$plugin->getNumerationSuffixsList(), [
                        'class' => 'lwptocCustomize_field_el_select',
                    ]) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultNumerationSuffix'), $model->defaultNumerationSuffix, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= Core::$plugin->getNumerationSuffixsList()[$defaultValue] ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->title)) ? Core::$plugin->settings->generalTitle : $model->postSettings->title ?>
            <div class="lwptocCustomize_field<?= $model->defaultTitle ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Title', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::textInput(Html::getInputName($model, 'title'), $model->defaultTitle ? $defaultValue : $model->title, ['class' => 'lwptocCustomize_field_el_textInput']) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultTitle'), $model->defaultTitle, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?php
                    if ('' == $defaultValue) {
                        echo '<i>' . __('Without title', 'luckywp-table-of-contents') . '</i>';
                    } else {
                        echo $defaultValue;
                    }
                    ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->toggle)) ? Core::$plugin->settings->generalToggle : $model->postSettings->toggle ?>
            <div class="lwptocCustomize_field<?= $model->defaultToggle ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Toggle Show/Hide', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <label>
                        <?= Html::checkbox(Html::getInputName($model, 'toggle'), $model->defaultToggle ? $defaultValue : $model->toggle, ['uncheck' => 0]) ?>
                        <?= __('Enable', 'luckywp-table-of-contents') ?>
                    </label>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultToggle'), $model->defaultToggle, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= $defaultValue ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents') ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->labelShow)) ? Core::$plugin->settings->generalLabelShow : $model->postSettings->labelShow ?>
            <div class="lwptocCustomize_field<?= $model->defaultLabelShow ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Label Show', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::textInput(Html::getInputName($model, 'labelShow'), $model->defaultLabelShow ? $defaultValue : $model->labelShow, ['class' => 'lwptocCustomize_field_el_textInput']) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultLabelShow'), $model->defaultLabelShow, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= $defaultValue ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->labelHide)) ? Core::$plugin->settings->generalLabelHide : $model->postSettings->labelHide ?>
            <div class="lwptocCustomize_field<?= $model->defaultLabelHide ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Label Hide', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::textInput(Html::getInputName($model, 'labelHide'), $model->defaultLabelHide ? $defaultValue : $model->labelHide, ['class' => 'lwptocCustomize_field_el_textInput']) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultLabelHide'), $model->defaultLabelHide, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= $defaultValue ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->hideItems)) ? Core::$plugin->settings->generalHideItems : $model->postSettings->hideItems ?>
            <div class="lwptocCustomize_field<?= $model->defaultHideItems ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('By default, items of contents will be hidden', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <label>
                        <?= Html::checkbox(Html::getInputName($model, 'hideItems'), $model->defaultHideItems ? $defaultValue : $model->hideItems, ['uncheck' => 0]) ?>
                        <?= __('Enable', 'luckywp-table-of-contents') ?>
                    </label>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultHideItems'), $model->defaultHideItems, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= $defaultValue ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents') ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->smoothScroll)) ? Core::$plugin->settings->generalSmoothScroll : $model->postSettings->smoothScroll ?>
            <div class="lwptocCustomize_field<?= $model->defaultSmoothScroll ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Smooth Scroll', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <label>
                        <?= Html::checkbox(Html::getInputName($model, 'smoothScroll'), $model->defaultSmoothScroll ? $defaultValue : $model->smoothScroll, ['uncheck' => 0]) ?>
                        <?= __('Enable', 'luckywp-table-of-contents') ?>
                    </label>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultSmoothScroll'), $model->defaultSmoothScroll, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= $defaultValue ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents') ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->smoothScrollOffset)) ? Core::$plugin->settings->generalSmoothScrollOffset : $model->postSettings->smoothScrollOffset ?>
            <div class="lwptocCustomize_field<?= $model->defaultSmoothScrollOffset ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= __('Smooth Scroll Offset Top', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::textInput(Html::getInputName($model, 'smoothScrollOffset'), $model->defaultSmoothScrollOffset ? $defaultValue : $model->smoothScrollOffset, ['class' => 'lwptocCustomize_field_el_textInput']) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultSmoothScrollOffset'), $model->defaultSmoothScrollOffset, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= $defaultValue ?>px
                </div>
            </div>

        </div>

        <div class="lwptocCustomize_fields lwptocCustomize_fields-appearance">

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->width)) ? Core::$plugin->settings->appearanceWidth : $model->postSettings->width ?>
            <div class="lwptocCustomize_field<?= $model->defaultWidth ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= esc_html__('Width', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= WidthField::widget([
                        'name' => Html::getInputName($model, 'width'),
                        'value' => $model->defaultWidth ? $defaultValue : $model->width,
                    ]) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultWidth'), $model->defaultWidth, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= Core::$plugin->widthToLabel($defaultValue) ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->float)) ? Core::$plugin->settings->appearanceFloat : $model->postSettings->float ?>
            <div class="lwptocCustomize_field<?= $model->defaultFloat ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= esc_html__('Float', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::dropDownList(Html::getInputName($model, 'float'), $model->defaultFloat ? $defaultValue : $model->float, Core::$plugin->floatsList, [
                        'class' => 'lwptocCustomize_field_el_select',
                    ]) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultFloat'), $model->defaultFloat, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= Core::$plugin->floatsList[$defaultValue] ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->titleFontSize)) ? Core::$plugin->settings->appearanceTitleFontSize : $model->postSettings->titleFontSize ?>
            <div class="lwptocCustomize_field<?= $model->defaultTitleFontSize ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= esc_html__('Title Font Size', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= FontSizeField::widget([
                        'name' => Html::getInputName($model, 'titleFontSize'),
                        'value' => $model->defaultTitleFontSize ? $defaultValue : $model->titleFontSize,
                        'defaultSize' => 100,
                    ]) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultTitleFontSize'), $model->defaultTitleFontSize, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= Core::$plugin->fontSizeToLabel($defaultValue) ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->titleFontWeight)) ? Core::$plugin->settings->appearanceTitleFontWeight : $model->postSettings->titleFontWeight ?>
            <div class="lwptocCustomize_field<?= $model->defaultTitleFontWeight ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= esc_html__('Title Font Weight', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::dropDownList(Html::getInputName($model, 'titleFontWeight'), $model->defaultTitleFontWeight ? $defaultValue : $model->titleFontWeight, Core::$plugin->fontWeightsList, [
                        'class' => 'lwptocCustomize_field_el_select',
                    ]) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultTitleFontWeight'), $model->defaultTitleFontWeight, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= Core::$plugin->fontWeightsList[$defaultValue] ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->itemsFontSize)) ? Core::$plugin->settings->appearanceItemsFontSize : $model->postSettings->itemsFontSize ?>
            <div class="lwptocCustomize_field<?= $model->defaultItemsFontSize ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= esc_html__('Items Font Size', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= FontSizeField::widget([
                        'name' => Html::getInputName($model, 'itemsFontSize'),
                        'value' => $model->defaultItemsFontSize ? $defaultValue : $model->itemsFontSize,
                        'defaultSize' => 90,
                    ]) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultItemsFontSize'), $model->defaultItemsFontSize, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= Core::$plugin->fontSizeToLabel($defaultValue) ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->colorScheme)) ? Core::$plugin->settings->appearanceColorScheme : $model->postSettings->colorScheme ?>
            <div class="lwptocCustomize_field<?= $model->defaultColorScheme ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= esc_html__('Color Scheme', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::dropDownList(Html::getInputName($model, 'colorScheme'), $model->defaultColorScheme ? $defaultValue : $model->colorScheme, Core::$plugin->colorSchemesList, [
                        'class' => 'lwptocCustomize_field_el_select',
                    ]) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultColorScheme'), $model->defaultColorScheme, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= Core::$plugin->colorSchemesList[$defaultValue] ?>
                </div>
            </div>

            <?php foreach ([
                               'backgroundColor' => 'Background Color',
                               'borderColor' => 'Border Color',
                               'titleColor' => 'Title Color',
                               'linkColor' => 'Link Color',
                               'hoverLinkColor' => 'Hover Link Color',
                               'visitedLinkColor' => 'Visited Link Color',
                           ] as $var => $label) { ?>
                <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->$var)) ? Core::$plugin->settings->{'appearance' . ucfirst($var)} : $model->postSettings->$var ?>
                <div class="lwptocCustomize_field<?= $model->{'default' . ucfirst($var)} ? ' lwptocCustomize_field-default' : '' ?>">
                    <div class="lwptocCustomize_field_header">
                        <span class="lwptocCustomize_field_label"><?= esc_html__($label, 'luckywp-table-of-contents') ?></span>
                        <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                    </div>
                    <div class="lwptocCustomize_field_override">
                        <?= $labelClickToOverrideDefaultValue ?>
                    </div>
                    <div class="lwptocCustomize_field_defaultValue">
                        <?= OverrideColorBadge::widget(['color' => $defaultValue]) ?>
                    </div>
                    <div class="lwptocCustomize_field_el">
                        <?= Html::textInput(Html::getInputName($model, $var), $model->{'default' . ucfirst($var)} ? Core::$plugin->settings->{'appearance' . ucfirst($var)} : $model->$var, ['class' => 'lwptoc_colorPicker']) ?>
                        <?= Html::hiddenInput(Html::getInputName($model, 'default' . ucfirst($var)), $model->{'default' . ucfirst($var)}, [
                            'class' => 'lwptocCustomize_field_inputDefault',
                        ]) ?>
                    </div>
                </div>
            <?php } ?>

        </div>

        <div class="lwptocCustomize_fields lwptocCustomize_fields-misc">

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->skipHeadingLevel)) ? Core::$plugin->settings->getMiscSkipHeadingLevel() : $model->postSettings->skipHeadingLevel ?>
            <div class="lwptocCustomize_field<?= $model->defaultSkipHeadingLevel ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= esc_html__('Skip heading by level', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= SkipHeadingLevelField::widget([
                        'name' => Html::getInputName($model, 'skipHeadingLevel'),
                        'value' => $model->defaultSkipHeadingLevel ? $defaultValue : $model->skipHeadingLevel,
                    ]) ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultSkipHeadingLevel'), $model->defaultSkipHeadingLevel, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= Core::$plugin->skipHeadingLevelToLabel($defaultValue) ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->skipHeadingText)) ? Core::$plugin->settings->getMiscSkipHeadingText() : $model->postSettings->skipHeadingText ?>
            <div class="lwptocCustomize_field<?= $model->defaultSkipHeadingText ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= esc_html__('Skip heading by text', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?php
                    $value = $model->defaultSkipHeadingText ? $defaultValue : $model->skipHeadingText;
                    echo Html::textarea(Html::getInputName($model, 'skipHeadingText'), Core::$plugin->skipHeadingTextToMultipleString((string)$value), [
                        'class' => 'regular-text',
                        'rows' => 5,
                    ]);
                    echo '<div class="lwptocCustomize_field_desc">';
                    echo esc_html__('Specify headings (one per line) to be excluded from the table of contents.', 'luckywp-table-of-contents');
                    echo '<br>';
                    echo sprintf(
                    /* translators: %s: Asterisk symbol (*) */
                        esc_html__('Use an asterisk %s as a wildcard to match any text.', 'luckywp-table-of-contents'),
                        '<code>*</code>'
                    );
                    echo '</div>';
                    ?>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultSkipHeadingText'), $model->defaultSkipHeadingText, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?php
                    if ('' == $defaultValue) {
                        echo '<i>' . esc_html__('empty', 'luckywp-table-of-contents') . '</i>';
                    } else {
                        echo esc_html($defaultValue);
                    }
                    ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->wrapNoindex)) ? Core::$plugin->settings->miscWrapNoindex : $model->postSettings->wrapNoindex ?>
            <div class="lwptocCustomize_field<?= $model->defaultWrapNoindex ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= sprintf(
                        /* translators: %s: <!--noindex--> */
                            esc_html__('Wrap table of contents with %s tag', 'luckywp-table-of-contents'),
                            '<code>&lt;!--noindex--&gt;</code>'
                        ) ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <label>
                        <?= Html::checkbox(Html::getInputName($model, 'wrapNoindex'), $model->defaultWrapNoindex ? $defaultValue : $model->wrapNoindex, ['uncheck' => 0]) ?>
                        <?= __('Enable', 'luckywp-table-of-contents') ?>
                    </label>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultWrapNoindex'), $model->defaultWrapNoindex, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= $defaultValue ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents') ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->useNofollow)) ? Core::$plugin->settings->getMiscUseNofollow() : $model->postSettings->useNofollow ?>
            <div class="lwptocCustomize_field<?= $model->defaultUseNofollow ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= sprintf(
                        /* translators: %s: rel="nofollow" */
                            esc_html__('Use %s for links', 'luckywp-table-of-contents'),
                            '<code>rel="nofollow"</code>'
                        ) ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <label>
                        <?= Html::checkbox(Html::getInputName($model, 'useNofollow'), $model->defaultUseNofollow ? $defaultValue : $model->useNofollow, ['uncheck' => 0]) ?>
                        <?= __('Enable', 'luckywp-table-of-contents') ?>
                    </label>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultUseNofollow'), $model->defaultUseNofollow, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?= $defaultValue ? __('Enabled', 'luckywp-table-of-contents') : __('Disabled', 'luckywp-table-of-contents') ?>
                </div>
            </div>

            <?php $defaultValue = ($model->isPostOrWidgetSettings || is_null($model->postSettings->containerClass)) ? Core::$plugin->settings->getMiscContainerClass() : $model->postSettings->containerClass ?>
            <div class="lwptocCustomize_field<?= $model->defaultContainerClass ? ' lwptocCustomize_field-default' : '' ?>">
                <div class="lwptocCustomize_field_header">
                    <span class="lwptocCustomize_field_label"><?= esc_html__('Additional CSS Class(es)', 'luckywp-table-of-contents') ?></span>
                    <span class="lwptocCustomize_field_default"><?= $labelDefault ?></span>
                </div>
                <div class="lwptocCustomize_field_override">
                    <?= $labelClickToOverrideDefaultValue ?>
                </div>
                <div class="lwptocCustomize_field_el">
                    <?= Html::textInput(Html::getInputName($model, 'containerClass'), $model->defaultContainerClass ? $defaultValue : $model->containerClass, ['class' => 'lwptocCustomize_field_el_textInput']) ?>
                    <div class="lwptocCustomize_field_desc">
                        <?= esc_html__('Separate multiple classes with spaces.', 'luckywp-table-of-contents') ?>
                    </div>
                    <?= Html::hiddenInput(Html::getInputName($model, 'defaultContainerClass'), $model->defaultContainerClass, [
                        'class' => 'lwptocCustomize_field_inputDefault',
                    ]) ?>
                </div>
                <div class="lwptocCustomize_field_defaultValue">
                    <?php
                    if ('' == $defaultValue) {
                        echo '<i>' . __('empty', 'luckywp-table-of-contents') . '</i>';
                    } else {
                        echo $defaultValue;
                    }
                    ?>
                </div>
            </div>

        </div>

    </div>
    <div class="lwptocModalBox_footer">
        <div class="lwptocModalBox_footer_buttons">
            <?= AdminHtml::button(__('Cancel', 'luckywp-table-of-contents'), [
                'context' => $context,
                'class' => 'lwptocModal-close' . (is_rtl() ? 'lwptocFloatRight' : ' lwptocFloatLeft')
            ]) ?>
            <?= AdminHtml::button(__('Save', 'luckywp-table-of-contents'), [
                'context' => $context,
                'theme' => AdminHtml::BUTTON_THEME_PRIMARY,
                'submit' => true,
                'class' => is_rtl() ? 'lwptocFloatLeft' : 'lwptocFloatRight',
            ]) ?>
        </div>
    </div>
</form>
<?= $onlyBody ? '' : '</div>' ?>
