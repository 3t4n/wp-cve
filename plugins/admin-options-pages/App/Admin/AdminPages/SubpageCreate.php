<?php

namespace AOP\App\Admin\AdminPages;

use AOP\App\Plugin;

class SubpageCreate
{
    const MENU_TITLE = 'Add New';
    const PAGE_TITLE = 'Create new subpages';
    const SLUG       = Plugin::_NAME . '_create';

    public static $nonceName   = 'create_page_nonce';
    public static $nonceAction = '_' . Plugin::PREFIX_ . 'create_nonce_action';

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

        print('</div>');
    }
}
