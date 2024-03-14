<?php
/**
 * @var array $attrs
 */

use luckywp\tableOfContents\core\Core;

$rows = Core::$plugin->admin->overrideSettingsToRows($attrs);
if ($rows) {
    ?>
    <p>
        <?= __('Overridden settings:', 'luckywp-table-of-contents') ?>
    </p>
    <div class="lwptocWidget_settings">
        <?php foreach ($rows as $row) { ?>
            <div class="lwptocWidget_settings_item">
                <b><?= $row[0] ?>:</b>
                <?= $row[1] === null ? '<i>' . __('empty', 'luckywp-table-of-contents') . '</i>' : $row[1] ?>
            </div>
        <?php } ?>
    </div>
    <?php
}
