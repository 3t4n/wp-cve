<?php
/**
 * @var $name string
 * @var $value string
 * @var $type string
 * @var $size int
 * @var $unit string
 */

use luckywp\tableOfContents\core\Core;
use luckywp\tableOfContents\core\helpers\Html;

?>
<div class="lwptocFontSizeField">
    <?= Html::dropDownList(null, $type, [
        'default' => esc_html__('Default', 'luckywp-table-of-contents'),
        'custom' => esc_html__('Custom Value', 'luckywp-table-of-contents'),
    ], [
        'class' => 'lwptocFontSizeField_typeInput',
    ]) ?>
    <div class="lwptocFontSizeField_custom"<?= $type == 'custom' ? '' : ' style="display:none;"' ?>>
        <?= Html::textInput(null, $size, [
            'class' => 'lwptocFontSizeField_sizeInput',
        ]) ?>
        <?= Html::dropDownList(null, $unit, Core::$plugin->fontSizeUnitsList, [
            'class' => 'lwptocFontSizeField_unitInput',
        ]) ?>
    </div>
    <?= Html::hiddenInput($name, $value, ['class' => 'lwptocFontSizeField_input']) ?>
</div>