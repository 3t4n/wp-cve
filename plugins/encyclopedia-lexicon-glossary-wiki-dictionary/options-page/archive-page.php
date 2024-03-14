<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    MockingBird,
    Options,
    PostTypeLabels
};

?>
<table class="form-table">

    <tr>
        <th><label for="enable_archive"><?php I18n::_e('Enable Archive') ?></label></th>
        <td>
            <select name="enable_archive" id="enable_archive">
                <option value="1" <?php selected(Options::get('enable_archive')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('enable_archive')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables the global archive. This does not affect the taxonomy archives.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label><?php printf(I18n::__('%s per page'), PostTypeLabels::getItemPluralName()) ?></label></th>
        <td>
            <input type="number" value="<?php echo get_Option('posts_per_page') ?>" <?php disabled(true) ?> min="1" max="<?php echo PHP_INT_MAX ?>" step="1"><?php MockingBird::printProNotice('unlock') ?>
            <p class="help">
                <?php printf(I18n::__('This option affects all %s archive pages.'), PostTypeLabels::getEncyclopediaType()) ?>
                <?php printf(I18n::__('You can use "-1" to disable the pagination and show all %s in one page.'), PostTypeLabels::getItemPluralName()) ?>
            </p>
        </td>
    </tr>

    <tr>
        <th><label for="prefix_filter_for_archives"><?php I18n::_e('Prefix filter') ?></label></th>
        <td>
            <select name="prefix_filter_for_archives" id="prefix_filter_for_archives">
                <option value="1" <?php selected(Options::get('prefix_filter_for_archives')) ?>><?php I18n::_e('On') ?></option>
                <option value="0" <?php selected(!Options::get('prefix_filter_for_archives')) ?>><?php I18n::_e('Off') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Enables or disables the prefix filter above the first item in the archive.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="prefix_filter_archive_depth"><?php I18n::_e('Prefix filter depth') ?></label></th>
        <td>
            <input type="number" name="prefix_filter_archive_depth" id="prefix_filter_archive_depth" value="<?php echo Options::get('prefix_filter_archive_depth') ?>" min="1" max="<?php echo PHP_INT_MAX ?>" step="1">
            <p class="help"><?php I18n::_e('The depth of the prefix filter is usually the number of rows with prefixes which are shown.') ?></p>
        </td>
    </tr>

</table>