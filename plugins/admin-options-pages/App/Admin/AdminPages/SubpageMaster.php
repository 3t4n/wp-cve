<?php

namespace AOP\App\Admin\AdminPages;

use AOP\App\Plugin;
use AOP\App\Admin\ListTable;

class SubpageMaster
{
    const MENU_TITLE  = 'All Options Pages';
    const PAGE_TITLE  = 'Options Pages';
    const PAGE_BUTTON = 'Add new';
    const SLUG        = Plugin::_NAME . '_master';

    public function __construct()
    {
        add_action('wp_loaded', [$this, 'onSubmitBulkAction']);
    }

    public function onSubmitBulkAction()
    {
        if (!isset($_REQUEST['page'])) {
            return;
        }

        if (!empty($_REQUEST['_wp_http_referer']) && $_REQUEST['page'] === $this->slug) {
            wp_redirect(remove_query_arg(['_wp_http_referer'], wp_unslash($_SERVER['REQUEST_URI'])));
            exit;
        }
    }

    public static function url()
    {
        return admin_url('admin.php?page=' . static::SLUG);
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
        $listTable = new ListTable();

        printf('<div id="%s"><div class="wrap">', Plugin::PREFIX . 'root');

        $listTable->prepare_items();

        printf(
            '<h1 class="wp-heading-inline">%s</h1>',
            SubpageMaster::PAGE_TITLE
        );

        printf(
            '<a href="%s" class="page-title-action">%s</a>',
            SubpageCreate::url(),
            _x(SubpageCreate::MENU_TITLE, 'Customize Changeset')
        );

        print('<hr class="wp-header-end">');

        print('<div id="poststuff"><div id="post-body" class="metabox-holder columns-2">');

        printf(
            '<div id="post-body-content"><form id="pages-filter" method="get"><input type="hidden" name="page" value="%s" />',
            $_REQUEST['page']
        );

        $listTable->display();

        print('</form></div>');

        print('<div id="postbox-container-1" class="postbox-container">');

        print('<div class="meta-box-sortables">');

        print('<div class="postbox">');

        print('<h2 style="font-size: 23px;font-weight: 600;"><span>Admin Options Pages</span></h2>');

        print('<div class="inside">');
        print('<blockquote><em>Creating options pages has never been easier.</em></blockquote>');

        print('<h3 style="margin-bottom: -10px;">Documentation</h3>');

        $svgLink = '<svg style="margin-bottom: -5px" aria-hidden="true" role="img" focusable="false" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" class=""><path fill="currentColor" d="M9 3h8v8l-2-1V6.92l-5.6 5.59-1.41-1.41L14.08 5H10zm3 12v-3l2-2v7H3V6h8L9 8H5v7h7z"></path></svg>';

        vprintf('<p><a target="_blank" rel="external noreferrer noopener" href="https://docs.adminoptionspages.com/" class="c-link"><span>docs.adminoptionspages.com</span>%s</a></p>', [
            $svgLink
        ]);

        print('<div style="border-bottom: 1px solid #ccd0d4;"></div>');

        $svgHeart = '<svg style="margin-bottom: -4px;" width="16" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><g><path fill="#e5005f" d="M10 17.12c3.33-1.4 5.74-3.79 7.04-6.21 1.28-2.41 1.46-4.81.32-6.25-1.03-1.29-2.37-1.78-3.73-1.74s-2.68.63-3.63 1.46c-.95-.83-2.27-1.42-3.63-1.46s-2.7.45-3.73 1.74c-1.14 1.44-.96 3.84.34 6.25 1.28 2.42 3.69 4.81 7.02 6.21z"/></g></svg>';
        $svgStar  = '<svg style="margin-bottom: -3px;" width="16" height="16" mlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><g><path fill="#ffb900" d="M10 1l3 6 6 .75-4.12 4.62L16 19l-6-3-6 3 1.13-6.63L1 7.75 7 7z"/></g></svg>';

        vprintf('<p>If you realy %s this plugin, consider giving some <a href="%s" target="_blank">%s</a> to help make <a href="%s" target="_blank">AOP</a> more known.</p>', [
            $svgHeart,
            'https://login.wordpress.org/?redirect_to=https%3A%2F%2Fwordpress.org%2Fsupport%2Fplugin%2Fadmin-options-pages%2Freviews%2F',
            $svgStar . $svgStar . $svgStar . $svgStar . $svgStar,
            'https://wordpress.org/plugins/admin-options-pages/'
        ]);

        print('</div></div></div></div>');

        print('</div></div>');

        print('<modal></modal>');

        print('</div></div>');
    }
}
