<?php
/**
 * @var $this \luckywp\glossary\core\base\View
 * @var $countColumns int
 * @var $terms Term[]
 * @var $termsByLetter Term[]
 * @var $termsByColumn array
 */

use luckywp\glossary\core\helpers\Html;
use luckywp\glossary\plugin\Term;

/** @var Term $term */
?>
<div class="lwpglsArchive lwpglsArchive-columns-<?= $countColumns ?>">
    <?php foreach ($termsByColumn as $columnTermsByLetter) { ?>
        <div class="lwpglsArchive_column">
            <?php foreach ($columnTermsByLetter as $letter => $letterTerms) { ?>
                <div class="lwpglsArchive_letter"><?= $letter ?></div>
                <div class="lwpglsArchive_terms">
                    <?php foreach ($letterTerms as $name => $term) { ?>
                        <div class="lwpglsArchive_term">
                            <?= Html::a($name, $term->permalink) ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>