<?php

use WordPress\Plugin\GalleryManager\{
    I18n,
    Mocking_Bird,
    Options
}

?>
<table class="form-table">

    <tr valign="top">
        <th scope="row"><label for="lightbox"><?php I18n::_e('Lightbox') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option><?php I18n::_e('On') ?></option>
            </select><?php Mocking_Bird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Turn this off if you do not want to use the included lightbox.') ?></p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="continuous"><?php I18n::_e('Loop mode') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option><?php I18n::_e('Off') ?></option>
            </select><?php Mocking_Bird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Enables the user to get from the last image to the first one with the "Next &raquo;" button.') ?></p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="title_description"><?php I18n::_e('Title &amp; Description') ?></label></th>
        <td>
            <?php if (Version_Compare(LIBXML_DOTTED_VERSION, '2.7.8', '>=')) : ?>
                <select <?php disabled(true) ?>>
                    <option><?php I18n::_e('On') ?></option>
                </select><?php Mocking_Bird::printProNotice('unlock') ?>
                <p class="help"><?php I18n::_e('Turn this off if you do not want to display the image title and description in your lightbox.') ?></p>
            <?php else : ?>
                <p class="warning box"><?php I18n::_e('This feature won\'t work because the <a href="https://secure.php.net/manual/en/book.libxml.php" target="_blank">LibXML PHP extension</a> is not available on your webserver or it is too old.') ?></p>
            <?php endif ?>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="close_button"><?php I18n::_e('Close button') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option><?php I18n::_e('On') ?></option>
            </select><?php Mocking_Bird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Turn this off if you do not want to display a close button in your lightbox.') ?></p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="indicator_thumbnails"><?php I18n::_e('Indicator thumbnails') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option><?php I18n::_e('On') ?></option>
            </select><?php Mocking_Bird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Turn this off if you do not want to display small preview thumbnails below the lightbox image.') ?></p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="slideshow_button"><?php I18n::_e('Slideshow play/pause button') ?></label></th>
        <td>
            <select <?php disabled(true) ?>>
                <option><?php I18n::_e('On') ?></option>
            </select><?php Mocking_Bird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('Turn this off if you do not want to provide a slideshow function in the lightbox.') ?></p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="slideshow_speed"><?php I18n::_e('Slideshow speed') ?></label></th>
        <td>
            <input type="number" name="slideshow_speed" id="slideshow_speed" value="<?php echo IntVal(Options::get('slideshow_speed')) ?>" min="1" step="1">
            <?php I18n::_ex('ms', 'Abbr. Milliseconds') ?>
            <p class="help"><?php I18n::_e('The delay between two images in the slideshow.') ?></p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="preload_images"><?php I18n::_e('Preload images') ?></label></th>
        <td>
            <input type="number" <?php disabled(true) ?> value="<?php echo IntVal(Options::get('preload_images')) ?>" min="1" step="1"><?php Mocking_Bird::printProNotice('unlock') ?>
            <p class="help"><?php I18n::_e('The number of images which should be preloaded around the current one.') ?></p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="animation_speed"><?php I18n::_e('Animation speed') ?></label></th>
        <td>
            <input type="number" name="animation_speed" id="animation_speed" value="<?php echo IntVal(Options::get('animation_speed')) ?>" min="1" step="1">
            <?php I18n::_ex('ms', 'Abbr. Milliseconds') ?>
            <p class="help"><?php I18n::_e('The speed of the image change animation.') ?></p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="stretch_images"><?php I18n::_e('Stretch images') ?></label></th>
        <td>
            <select name="stretch_images" id="stretch_images">
                <option value="" <?php Selected(Options::get('stretch_images'), '') ?>><?php I18n::_e('No stretching') ?></option>
                <option value="contain" <?php Selected(Options::get('stretch_images'), 'contain') ?>><?php I18n::_e('Contain') ?></option>
                <option value="cover" <?php Selected(Options::get('stretch_images'), 'cover') ?>><?php I18n::_e('Cover') ?></option>
            </select>
            <p class="help"><?php I18n::_e('"Contain" means to scale the image to the largest size such that both its width and its height can fit the screen.') ?></p>
            <p class="help"><?php I18n::_e('"Cover" means to scale the image to be as large as possible so that the screen is completely covered by the image. Some parts of the image may be cropped and invisible.') ?></p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row"><label for="script_position"><?php I18n::_e('Script position') ?></label></th>
        <td>
            <select name="script_position" id="script_position">
                <option value="footer" <?php Selected(Options::get('script_position'), 'footer') ?>><?php I18n::_e('Footer of the website') ?></option>
                <option value="header" <?php Selected(Options::get('script_position'), 'header') ?>><?php I18n::_e('Header of the website') ?></option>
            </select>
            <p class="help"><?php I18n::_e('Please choose the position of the javascript. "Footer" is recommended. Use "Header" if you have trouble to make the lightbox work.') ?></p>
        </td>
    </tr>

</table>
