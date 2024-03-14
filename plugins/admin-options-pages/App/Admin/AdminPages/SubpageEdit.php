<?php

namespace AOP\App\Admin\AdminPages;

use AOP\App\Plugin;

class SubpageEdit
{
    const MENU_TITLE = 'Edit Page';
    const PAGE_TITLE = 'Create new subpages';
    const SLUG       = Plugin::_NAME . '_edit';

    public static $nonceName   = 'edit_page_nonce';
    public static $nonceAction = '_' . Plugin::PREFIX_ . 'edit_nonce_action';

    public static function wpNonceFieldArray()
    {
        $nonceField = wp_nonce_field(static::$nonceAction, static::$nonceName, true, false);

        preg_match_all('/(?<=value="|id=")(.*?)(?=")/', $nonceField, $matches);

        return $matches[0];
    }

    public static function url()
    {
        return admin_url(add_query_arg('page', self::SLUG, 'admin.php'));
    }

    public static function isCurrentPage()
    {
        if (!isset($_REQUEST['page'])) {
            return false;
        }

        if ($_REQUEST['page'] !== static::SLUG) {
            return false;
        }

        return true;
    }

    public static function view()
    {
        printf('<div id="%s">', Plugin::PREFIX . 'root');

        vprintf(
            '<page-create noncename="%s" nonce="%s" slug="%s"></page-create>',
            self::wpNonceFieldArray()
        );

        print('<modal></modal>');

        print('</div>');
    }
}
