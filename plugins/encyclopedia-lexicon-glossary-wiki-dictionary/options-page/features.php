<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    MockingBird,
    Options
};

?>
<table class="form-table">

    <tr>
        <th><label for="enable_editor"><?php I18n::_e('Text Editor') ?></label></th>
        <td>
            <select name="enable_editor" id="enable_editor">
                <option value="1" <?php selected(Options::get('enable_editor')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('enable_editor')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables the text editor.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="enable_block_editor"><?php I18n::_e('Block Editor') ?></label></th>
        <td>
            <select name="enable_block_editor" id="enable_block_editor">
                <option value="1" <?php selected(Options::get('enable_block_editor')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('enable_block_editor')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables the block editor (Gutenberg).') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="enable_excerpt"><?php I18n::_e('Excerpt') ?></label></th>
        <td>
            <select name="enable_excerpt" id="enable_excerpt">
                <option value="1" <?php selected(Options::get('enable_excerpt')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('enable_excerpt')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables the text excerpt input field.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Revisions') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option <?php disabled(true) ?>><?php I18n::_e('On') ?></option>
                <option <?php selected(true) ?>><?php I18n::_e('Off') ?></option>
            </select><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Enables or disables revisions.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Comments &amp; Trackbacks') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option <?php disabled(true) ?>><?php I18n::_e('On') ?></option>
                <option <?php selected(true) ?>><?php I18n::_e('Off') ?></option>
            </select><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Enables or disables comments and trackbacks.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Featured Image') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option <?php disabled(true) ?>><?php I18n::_e('On') ?></option>
                <option <?php selected(true) ?>><?php I18n::_e('Off') ?></option>
            </select><?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Enables or disables the featured image.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="enable_custom_fields"><?php I18n::_e('Custom Fields') ?></label></th>
        <td>
            <select name="enable_custom_fields" id="enable_custom_fields">
                <option value="1" <?php selected(Options::get('enable_custom_fields')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('enable_custom_fields')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables custom fields.') ?></p>
        </td>
    </tr>

</table>