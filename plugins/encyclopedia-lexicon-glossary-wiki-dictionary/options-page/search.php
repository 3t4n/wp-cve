<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    MockingBird
};

?>
<table class="form-table">

    <tr>
        <th><label><?php I18n::_e('Query matches directly') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option <?php disabled(true) ?>><?php I18n::_e('On') ?></option>
                <option <?php selected(true) ?>><?php I18n::_e('Off') ?></option>
            </select><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Enable this feature to redirect the user to the matched item if the user searched for an exact title.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Autocomplete min length') ?></label></th>
        <td>
            <input type="number" value="2" <?php disabled(true) ?>>
            <?php I18n::_e('characters', 'characters unit') ?><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('The minimum number of characters a user must type before suggestions will be shown.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Autocomplete delay') ?></label></th>
        <td>
            <input type="number" value="400" <?php disabled(true) ?>>
            <?php I18n::_e('ms', 'milliseconds time unit') ?><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('The delay in milliseconds between a keystroke occurs and the suggestions will be shown.') ?></p>
        </td>
    </tr>

</table>