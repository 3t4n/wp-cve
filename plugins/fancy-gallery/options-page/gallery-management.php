<?php

use WordPress\Plugin\GalleryManager\{
    I18n,
    Mocking_Bird,
    Options
}

?>
<table class="form-table">
    <tr>
        <th><label for="enable_editor"><?php I18n::_e('Text Editor') ?></label></th>
        <td>
            <select name="enable_editor" id="enable_editor">
                <option value="1" <?php selected(Options::get('enable_editor')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('enable_editor')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables text editor for galleries.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Excerpts') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option><?php I18n::_e('Off') ?></option>
            </select><?php Mocking_Bird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Enables or disables text excerpts for galleries.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Revisions') ?></label></th>
        <td>
            <select <?php disabled(True) ?>>
                <option><?php I18n::_e('Off') ?></option>
            </select><?php Mocking_Bird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Enables or disables revisions for galleries.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Comments') ?></label></th>
        <td>
            <select <?php disabled(True) ?>>
                <option><?php I18n::_e('Off') ?></option>
            </select><?php Mocking_Bird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Enables or disables comments and trackbacks for galleries.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="enable_featured_image"><?php I18n::_e('Featured Image') ?></label></th>
        <td>
            <select name="enable_featured_image" id="enable_featured_image">
                <option value="1" <?php selected(Options::get('enable_featured_image')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('enable_featured_image')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables the "Featured Image" for galleries.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="enable_custom_fields"><?php I18n::_e('Custom Fields') ?></label></th>
        <td>
            <select name="enable_custom_fields" id="enable_custom_fields">
                <option value="1" <?php selected(Options::get('enable_custom_fields')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('enable_custom_fields')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables the "Custom Fields" for galleries.') ?></p>
        </td>
    </tr>

</table>
