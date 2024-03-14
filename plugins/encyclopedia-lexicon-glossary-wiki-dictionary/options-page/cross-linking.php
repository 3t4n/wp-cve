<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    MockingBird,
    Options
};

$link_target = Options::get('link_item_target');
$exclude_post_type = ['attachment', 'wp_block'];
$post_types = get_Post_Types(['show_ui' => true], 'objects');

if (!Class_Exists('DOMDocument')) : ?>
    <p class="warning box"><?php I18n::_e('This feature won\'t work because the <a href="https://secure.php.net/manual/en/book.dom.php" target="_blank">DOM PHP extension</a> is not available on your webserver.') ?></p>
<?php endif ?>

<table class="form-table">

    <?php foreach ($post_types as $type) : if (in_Array($type->name, $exclude_post_type)) continue; ?>
        <tr>
            <th><?php echo $type->label ?></th>
            <td>
                <label>
                    <input type="checkbox" <?php disabled(true);
                                            checked(true) ?>>
                    <?php printf(I18n::__('Add links in %s'), $type->label) ?>
                    <?php MockingBird::printProNotice('unlock') ?>
                </label><br>

                <label>
                    <input type="checkbox" <?php disabled(true) ?>>
                    <?php I18n::_e('Open link in a new window/tab') ?>
                </label>
            </td>
        </tr>
    <?php endforeach ?>

    <tr>
        <th><?php I18n::_e('Text Widget') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true);
                                        checked(true) ?>>
                <?php I18n::_e('Add links in the default text widget') ?>
                <?php MockingBird::printProNotice('unlock') ?>
            </label><br>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Minimum length') ?></label></th>
        <td>
            <input type="number" value="1" <?php disabled(true) ?>>
            <?php I18n::_e('characters') ?>
            <?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('The minimum length of cross linked words. Shorter words <u>will not be</u> cross linked automatically.') ?></p>
        </td>
    </tr>

    <tr>
        <th><?php I18n::_e('Filter order') ?></th>
        <td>
            <label for="cross_linker_priority">
                <select id="cross_linker_priority" name="cross_linker_priority">
                    <option value="before_shortcodes" <?php selected(Options::get('cross_linker_priority') == 'before_shortcodes') ?>><?php I18n::_e('Before shortcodes') ?></option>
                    <option value="" <?php selected(Options::get('cross_linker_priority') == 'after_shortcodes') ?>><?php I18n::_e('After shortcodes') ?></option>
                </select>
            </label>
            <p class="help"><?php I18n::_e('By default the cross links should be added to the content after rendering all shortcodes. This works not for shortcodes which are calling the "the_content" filter while rendering. In this case please change this setting to "Before shortcodes".') ?></p>
        </td>
    </tr>

    <tr>
        <th><?php I18n::_e('Complete words') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true) ?>>
                <?php I18n::_e('Link complete words only.') ?>
                <?php MockingBird::printProNotice('unlock') ?>
            </label>
        </td>
    </tr>

    <tr>
        <th><?php I18n::_e('Case sensitivity') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true) ?>>
                <?php I18n::_e('Link items case sensitive.') ?>
                <?php MockingBird::printProNotice('unlock') ?>
            </label>
        </td>
    </tr>

    <tr>
        <th><?php I18n::_e('First match only') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true) ?>>
                <?php I18n::_e('Link the first match of each item only.') ?>
                <?php MockingBird::printProNotice('unlock') ?>
            </label>
        </td>
    </tr>

    <tr>
        <th><?php I18n::_e('Recursion') ?></th>
        <td>
            <label>
                <input type="checkbox" <?php disabled(true) ?>>
                <?php I18n::_e('Link the item in its own content.') ?>
                <?php MockingBird::printProNotice('unlock') ?>
            </label>
        </td>
    </tr>

    <tr>
        <th><label><?php I18n::_e('Link title length') ?></label></th>
        <td>
            <input type="number" value="<?php echo esc_Attr(Options::get('cross_link_title_length')) ?>" <?php disabled(true) ?>> <?php I18n::_e('words') ?>
            <?php MockingBird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('The number of words of the linked item used as link title. This option does not affect manually created excerpts.') ?></p>
        </td>
    </tr>

</table>