<?php
/**
 * @var $items array
 * @var $depth int
 * @var $containerTag string
 * @var $itemTag string
 */

use luckywp\tableOfContents\core\helpers\Html;

echo Html::beginTag($containerTag, ['class' => 'lwptoc_itemWrap']);
foreach ($items as $item) {
    echo Html::beginTag($itemTag, ['class' => 'lwptoc_item']);
    ?>
    <a href="#<?= $item['id'] ?>"<?= $item['relNofollow'] ?>>
        <?php if ($item['number']) { ?>
            <span class="lwptoc_item_number"><?= $item['number'] . $item['numberSuffix'] ?></span>
        <?php } ?>
        <span class="lwptoc_item_label"><?= esc_html($item['label']) ?></span>
    </a>
    <?php
    lwptoc_items($item['childrens']);
    echo Html::endTag($itemTag);
}
echo Html::endTag($containerTag);
