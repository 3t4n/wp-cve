<?php

use luckywp\glossary\core\Core;

?>
<div class="wrap">
    <h1><?= esc_html__('Glossary Settings', 'luckywp-glossary') ?></h1>
    <?php Core::$plugin->settings->showPage() ?>
</div>