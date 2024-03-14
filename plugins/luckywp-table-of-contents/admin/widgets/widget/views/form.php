<?php
/**
 * @var $id string
 * @var $inputName string
 * @var $value string
 * @var $attrs array
 */

use luckywp\tableOfContents\admin\widgets\widget\Widget;
use luckywp\tableOfContents\core\admin\helpers\AdminHtml;
use luckywp\tableOfContents\core\helpers\Html;

?>
<div class="lwptocWidget lwptocWidget-<?= $id ?>" data-id="<?= $id ?>">
    <div class="lwptocWidget_override">
        <?= Widget::overrideHtml($attrs) ?>
    </div>
    <p>
        <?= AdminHtml::button(__('Customize', 'luckywp-table-of-contents'), [
            'class' => 'lwptocWidget_customize',
        ]) ?>
    </p>
    <?= Html::hiddenInput($inputName, $value, ['class' => 'lwptocWidget_input']) ?>
</div>
<script>
    jQuery.lwptocWidget.init();
</script>