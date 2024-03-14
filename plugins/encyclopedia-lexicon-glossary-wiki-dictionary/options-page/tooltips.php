<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    MockingBird,
    Options
};

?>
<table class="form-table">

    <tr>
        <th><label for="activate_tooltips"><?php I18n::_e('Tooltips') ?></label></th>
        <td>
            <select name="activate_tooltips" id="activate_tooltips">
                <option value="1" <?php selected(Options::get('activate_tooltips')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('activate_tooltips')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables the tooltips for item links on the frontend.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Animation duration') ?></label></th>
        <td>
            <input type="number" value="350" <?php disabled(true) ?>>
            <?php I18n::_e('ms', 'milliseconds time unit') ?>
            <?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('The duration for the opening and closing animations, in milliseconds.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Delay') ?></label></th>
        <td>
            <input type="number" value="300" <?php disabled(true) ?>>
            <?php I18n::_e('ms', 'milliseconds time unit') ?>
            <?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Upon mouse interaction, this is the delay before the tooltip starts its opening and closing animations, in milliseconds.') ?></p>
        </td>
    </tr>

    <tr>
        <th><?php I18n::_e('Click-Event') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true) ?>>
                <?php I18n::_e('Show the tooltips only if the user <strong>clicks</strong> on it. This option will <strong>disable the link</strong> to the cross linked entry.') ?>
            </label><?php MockingBird::printProNotice('unlock') ?>
        </td>
    </tr>

</table>