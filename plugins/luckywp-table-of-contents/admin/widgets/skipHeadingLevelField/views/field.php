<?php
/**
 * @var $name string
 * @var $value array
 * @var $items array
 */

use luckywp\tableOfContents\core\helpers\Html;

?>
<div class="lwptocSkipHeadingLevelField">
    <?php
    echo Html::hiddenInput($name);
    if (is_rtl()) {
        $items = array_reverse($items);
    }
    foreach ($items as $id => $label) {
        $options = [
            'label' => $label,
            'value' => $id,
            'rtl' => is_rtl(),
        ];
        echo Html::checkbox($name . '[]', in_array($id, $value), $options) . ' ';
    }
    ?>
</div>