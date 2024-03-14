<?php
/**
 * @var $title string
 * @var $titleTag string
 * @var $toggle bool
 * @var $labelShow string
 * @var $labelHide string
 * @var $hideItems bool
 * @var $containerOptions array
 * @var $innerContainerOptions array
 * @var $headerStyles array
 * @var $titleStyles array
 * @var $itemsStyles array
 * @var $items array
 * @var $before string
 * @var $after string
 */

use luckywp\tableOfContents\core\helpers\Html;

echo $before . Html::beginTag('div', $containerOptions) . Html::beginTag('div', $innerContainerOptions);
?>
<?php if ($toggle || $title) { ?>
    <div class="lwptoc_header"<?= $headerStyles ? ' style="' . implode('', $headerStyles) . '"' : '' ?>>
        <?php
        if ($title) {
            echo Html::tag($titleTag, $title, [
                'class' => 'lwptoc_title',
                'style' => $titleStyles ? implode('', $titleStyles) : null,
            ]);
        }
        ?>
        <?php if ($toggle) { ?>
            <span class="lwptoc_toggle">
                <a href="#" class="lwptoc_toggle_label" data-label="<?= $hideItems ? $labelHide : $labelShow ?>"><?= $hideItems ? $labelShow : $labelHide ?></a>
            </span>
        <?php } ?>
    </div>
<?php } ?>
<div class="lwptoc_items<?= $hideItems ? '' : ' lwptoc_items-visible' ?>"<?= $itemsStyles ? ' style="' . implode('', $itemsStyles) . '"' : '' ?>>
    <?php lwptoc_items($items) ?>
</div>
<?= '</div></div>' . $after ?>
