<?php
/**
 * @var $this \luckywp\glossary\core\base\View
 * @var $synonyms string[]
 */
?>
<p>
    <small>
        <b><?= esc_html__('Synonyms', 'luckywp-glossary') ?>:</b>
        <i><?= implode(', ', $synonyms) ?></i>
    </small>
</p>