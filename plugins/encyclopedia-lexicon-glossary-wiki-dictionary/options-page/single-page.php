<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    Options
};

?>
<table class="form-table">

    <tr>
        <th><label for="prefix_filter_for_singulars"><?php I18n::_e('Prefix filter') ?></label></th>
        <td>
            <select name="prefix_filter_for_singulars" id="prefix_filter_for_singulars">
                <option value="1" <?php selected(Options::get('prefix_filter_for_singulars')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('prefix_filter_for_singulars')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables the prefix filter above the title in the single view.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="prefix_filter_singular_depth"><?php I18n::_e('Prefix filter depth') ?></label></th>
        <td>
            <input type="number" name="prefix_filter_singular_depth" id="prefix_filter_singular_depth" value="<?php echo Options::get('prefix_filter_singular_depth') ?>" min="1" max="<?php echo PHP_INT_MAX ?>" step="1">
            <p class="help"><?php I18n::_e('The depth of the prefix filter is usually the number of rows with prefixes which are shown.') ?></p>
        </td>
    </tr>

</table>