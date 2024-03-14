<?php
namespace DarklupLite\Admin;

/**
 *
 * @package    DarklupLite - WP Dark Mode
 * @version    1.0.0
 * @author
 * @Websites:
 *
 */

final class Settings_Fields
{

    public function fileInclude()
    {

        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/css-editor.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/image-switcher.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/button-switcher.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/color-scheme-switcher.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/select.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/switcher.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/text-area.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/text.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/color-picker.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/multiple-select.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/number.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/switch-margin.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/image-repeater.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/media-upload.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/image-effects-switcher.php';
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/slider.php';
        
        require_once DARKLUPLITE_DIR_ADMIN . 'setting-fields/class-settings-fields-base.php';

    }

}

$obj = new Settings_Fields();

$obj->fileInclude();