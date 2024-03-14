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
<div class="lwptocWidthField">
    <?= Html::dropDownList(null, $type, Core::$plugin->getWidthsList(), [
        'class' => 'lwptocWidthField_typeInput',
    ]) ?>
    <div class="lwptocWidthField_custom"<?= $type == 'custom' ? '' : ' style="display:none;"' ?>>
        <?= Html::textInput(null, $size, [
            'class' => 'lwptocWidthField_sizeInput',
        ]) ?>
        <?= Html::dropDownList(null, $unit, Core::$plugin->blockSizeUnitsList, [
            'class' => 'lwptocWidthField_unitInput',
        ]) ?>
    </div>
    <?= Html::hiddenInput($name, $value, ['class' => 'lwptocWidthField_input']) ?>
</div>