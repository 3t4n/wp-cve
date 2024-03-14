<?php
/**
 * @var $term \luckywp\glossary\plugin\Term
 * @var $model \luckywp\glossary\admin\forms\TermSynonymsForm
 */

use luckywp\glossary\core\admin\helpers\AdminHtml;
use luckywp\glossary\core\helpers\Html;

$model->nonceField();
?>
<p>
    <?= esc_html__('Enter the synonyms (separated by a comma).', 'luckywp-glossary') ?>
</p>
<p>
    <?= AdminHtml::textInput(Html::getInputName($model, 'synonyms'), $model->synonyms, [
        'size' => AdminHtml::TEXT_INPUT_SIZE_LARGE,
    ]) ?>
</p>