<?php

namespace AOP\App;

use AOP\App\User;
use AOP\App\Admin\Text;
use AOP\App\Database\DB;
use AOP\App\Admin\AdminPages\SubpageEdit;
use AOP\Lib\Illuminate\Support\Collection;
use AOP\App\Admin\AdminPages\SubpageCreate;
use AOP\App\Admin\AdminPages\SubpageMaster;

class Data extends DB
{
    /**
     * @var
     */
    private static $getAllFromPageValueColumn;

    /**
     * @var
     */
    private static $getAllOptionNamesExcludeCurrentPageFieldNames;

    /**
     * @var
     */
    private static $getAllRowsFromPluginTable;

    /**
     * @var
     */
    private static $getAllOptionNames;

    /**
     * @return Collection
     */
    public static function js()
    {
        return Collection::make([
            'text' => Text::data(),
            'existingAdminMenuTitles' => static::notUsedExistingMenuTitles(),
            'data' => static::pageData()
        ]);
    }

    /**
     * @return Collection|array
     */
    public static function pageData()
    {
        if ($_REQUEST['page'] === SubpageCreate::SLUG) {
            return Collection::make([
                'registered_options' => static::getAllOptionNamesExcludeCurrentPageFieldNames()
            ]);
        }

        if (!isset($_REQUEST['action']) && !isset($_REQUEST['optionpage']) || $_REQUEST['page'] === SubpageMaster::SLUG) {
            return [];
        }

        $currentPage = static::listTableData()->filter(function ($value) {
            return $_REQUEST['optionpage'] === $value['ID'];
        })->values();

        $allRows = $_POST ? DB::allRowsFromPluginTable() : static::getAllRowsFromPluginTable();

        $allRowsFromPluginTable = Collection::make($allRows)->filter(function ($row) use ($currentPage) {
            return $currentPage[0]['ID'] === $row->id;
        })->flatten(1)->map(function ($value) {
            return unserialize($value->page_value);
        })->first();

        if (!empty($allRowsFromPluginTable['existing_menu'])) {
            $allRowsFromPluginTable['existing_menu']['menu_name'] = __($allRowsFromPluginTable['existing_menu']['menu_name']);
        }

        return Collection::make($allRowsFromPluginTable)->merge([
            'id' => $currentPage[0]['ID'],
            'nonce' => wp_create_nonce($currentPage[0]['ID']),
            'page' => $_REQUEST['page'],
            'registered_options' => static::getAllOptionNamesExcludeCurrentPageFieldNames()
        ]);
    }

    /**
     * Existing admin menu titles.
     *
     * @return object
     * @global array $GLOBALS ['menu']
     */
    public static function existingAdminMenuTitles()
    {
        dump($GLOBALS['menu']);

        return Collection::make($GLOBALS['menu'])->filter(function ($value) {
            return in_array($value[2], static::defaultWordpressMenuPageSlugs());
        })->map(function ($value) {
            if (strpos($value[0], '</span>')) {
                $value[0] = substr($value[0], 0, strpos($value[0], ' <span'));
            }

            return ['title' => $value[0], 'slug' => $value[2]];
        })->values();
    }

    /**
     * Existing admin menu title minus the ones who are already used.
     *
     * @return object
     * @global array $GLOBALS ['menu']
     */
    public static function notUsedExistingMenuTitles()
    {
//        $parentSlug = isset($_REQUEST['existing_menu'])
//            ? [$_REQUEST['existing_menu']['menu_slug']]
//            : static::getParentSlugFromSubpages()->values()->toArray();

        $parentSlug = static::getParentSlugFromSubpages()->values()->toArray();

        if (isset($GLOBALS['menu'])) {
            global $menu;
        } else {
            $menu = get_option(Plugin::PREFIX_ . 'admin_menu_list');
        }

        // $data = Collection::make($menu)->filter(function ($value) use ($parentSlug) {
        //     return in_array($value[2], static::defaultWordpressMenuPageSlugs()) && !in_array($value[2], $parentSlug);
        // })->map(function ($value) {

        //     if (strpos($value[0], '</span>') !== false) {
        //         $value[0] = substr($value[0], 0, strpos($value[0], ' <span'));
        //     }

        //     return ['title' => $value[0], 'slug' => $value[2]];
        // })->values();

        // dump($data);

        // dump($_REQUEST);
        // dump($menu);
        // dump($parentSlug);
        // dump(static::getParentSlugFromSubpages()->values()->toArray());
        // wp_die();

        return Collection::make($menu)->filter(function ($value) use ($parentSlug) {
            return in_array($value[2], static::defaultWordpressMenuPageSlugs()) && !in_array($value[2], $parentSlug);
        })->map(function ($value) {
            if (strpos($value[0], '</span>') !== false) {
                $value[0] = substr($value[0], 0, strpos($value[0], ' <span'));
            }

            return ['title' => __($value[0]), 'slug' => $value[2]];
        })->values();
    }

    /**
     * Get all default WordPress menu titles and slugs.
     *
     * @return object
     */
    public static function getDefaultWordpressMenus()
    {
        if (isset($GLOBALS['menu'])) {
            global $menu;
        } else {
            $menu = get_option(Plugin::PREFIX_ . 'admin_menu_list');
        }

        return Collection::make($menu)->filter(function ($value) {
            return in_array($value[2], static::defaultWordpressMenuPageSlugs());
        })->map(function ($value, $key) {
            if (strpos($value[0], '</span>') !== false) {
                $value[0] = substr($value[0], 0, strpos($value[0], ' <span'));
            }

            return [
                'title' => $value[0],
                'slug' => $value[2],
                'position' => $key
            ];
        })->values();
    }

    public static function defaultWordpressMenuPageSlugs()
    {
        return [
            'index.php',
            'edit.php',
            'upload.php',
            'edit.php?post_type=page',
            'edit-comments.php',
            'themes.php',
            'plugins.php',
            'users.php',
            'tools.php',
            'options-general.php'
        ];
    }

    /**
     * @return Collection
     */
    public static function getParentSlugFromSubpages()
    {
        return static::getSubpages()
            ->pluck('parent_slug')
            ->unique();
    }

    /**
     * @param null $id
     *
     * @return Collection
     */
    public static function getSubpages($id = null)
    {
        $value = ($id === null)
            ? static::getAllFromPageValueColumn()
            : static::getPageValueFromSpecificId($id);

        // dump(static::getAllFromPageValueColumn());
        // dump($value);

        return Collection::make($value)
            ->pluck('pages')
            ->flatten(1);
    }

    /**
     * @return Collection|void
     */
    public static function requestGetSubpages()
    {
        if (!isset($_REQUEST['pages'])) {
            return;
        }

        return Collection::make($_REQUEST['pages']);
    }

    /**
     * @return Collection
     */
    public static function getFields()
    {
        return static::getSubpages()
            ->pluck('fields')
            ->flatten(1);
    }

    /**
     * @param $id
     *
     * @return Collection
     */
    public static function getFieldsfromId($id)
    {
        return static::getSubpages($id)
            ->pluck('fields')
            ->flatten(1);
    }

    /**
     * @param $id
     *
     * @return Collection
     */
    public static function getOptionNamesFromSettingPagesById($id)
    {
        return static::getFieldsfromId($id)->filter(function ($field) {
            return isset($field['field_name']);
        })->pluck('field_name')->flatten(1);
    }

    /**
     * @return Collection
     */
    public static function getPageNamesFromOptionsPages()
    {
        return static::getSubpages()->map(function ($page) {
            return $page['menu_slug'];
        });
    }

    /**
     * @return Collection
     */
    public static function adminPagesIds()
    {
        return Collection::make([
            'options-pages_page_' . SubpageCreate::SLUG,
            'admin_page_' . SubpageEdit::SLUG,
            'toplevel_page_' . SubpageMaster::SLUG
        ]);
    }

    /**
     * Get all options names from options table.
     *
     * @return array
     */
    public static function getAllOptionNames()
    {
        if (static::$getAllOptionNames === null) {
            static::$getAllOptionNames = Collection::make(DB::allOptionNamesFromOptionsTable())
                ->pluck('option_name')
                ->merge(static::getAllCreatedOptionNames());
        }

        return static::$getAllOptionNames;
    }

    /**
     * Get all options names from options table exclude current subpage field names.
     *
     * @return array
     */
    public static function getAllOptionNamesExcludeCurrentPageFieldNames()
    {
        if (static::$getAllOptionNamesExcludeCurrentPageFieldNames === null) {
            static::$getAllOptionNamesExcludeCurrentPageFieldNames = Collection::make(static::getAllOptionNames())
                ->diff(static::getAllRegisteredFieldNames())
                ->all();
        }

        return static::$getAllOptionNamesExcludeCurrentPageFieldNames;
    }

    /**
     * Get all options names from options table created by this plugin.
     * To be clear: field_name === option_name.
     *
     * @return object
     */
    public static function getAllCreatedOptionNames()
    {
        if (is_null(static::$getAllFromPageValueColumn)) {
            return;
        }

        return static::$getAllFromPageValueColumn
            ->pluck('pages')->flatten(1)
            ->pluck('fields')->flatten(1)
            ->pluck('field_name');
    }

    /**
     * Get all field names which already registered.
     *
     * @return array
     */
    public static function getAllRegisteredFieldNames()
    {
        if (!isset($_REQUEST['optionpage'])) {
            return [];
        }

        return static::getFieldsfromId($_REQUEST['optionpage'])->pluck('field_name');
    }

    /**
     * @return mixed
     */
    public static function getAllCreatedOptionNamesAfterEdit()
    {
        return Data::getAllCreatedOptionNames()
            ->diff(Data::getFieldNamesCurrentPage())
            ->merge(Data::getAllRegisteredFieldNames());
    }

    /**
     * @return mixed
     */
    public static function getAllCreatedOptionNamesAfterSave()
    {
        return Data::getAllCreatedOptionNames()
            ->diff(Data::getFieldNamesFromSession())
            ->merge(Data::getAllRegisteredFieldNames());
    }

    /**
     * @return Collection
     */
    public static function getFieldsWithFieldNames()
    {
        return Collection::make($_REQUEST['pages'])
            ->pluck('fields')->flatten(1)
            ->pluck('field_name')->filter();

//        return Collection::make($_REQUEST['pages'])->flatMap(function ($page) {
//            return isset($page['fields']) ? $page['fields'] : null;
//        })->pluck('field_name');
    }

    public static function getFieldNamesCurrentPage()
    {
        if (!$_REQUEST['menu_id']) {
            return;
        }

        return static::$getAllFromPageValueColumn->where('menu_id', $_REQUEST['menu_id'])
            ->pluck('pages')->flatten(1)
            ->pluck('fields')->flatten(1)
            ->pluck('field_name');
    }

    public static function getFieldNamesFromSession()
    {
        // dump(Session::get('request_on_save'));

        // wp_die();
        if (!Session::get('request_on_save')) {
            return;
        }

        // return;

        return Collection::make(Session::get('request_on_save')['pages'])
            ->pluck('fields')->flatten(1)
            ->pluck('field_name');

        // if (!$_SESSION['aop_request_on_save']) {
        //     return;
        // }

        // dump($_SESSION['aop_request_on_save']['pages']);

        // wp_die();

        // return Collection::make($_SESSION['aop_request_on_save']['pages'])
        //     ->pluck('fields')->flatten(1)
        //     ->pluck('field_name');
    }

    /**
     * All data from column page_value.
     *
     * @return object
     */
    public static function getAllFromPageValueColumn()
    {
        if (static::$getAllFromPageValueColumn === null) {
            static::$getAllFromPageValueColumn = Collection::make(DB::getAllFromColumnPageValue())
                ->pluck('page_value')
                ->map(function ($item) {
                    $item = unserialize($item);

                    if (!isset($item['menu'])) {
                        return $item;
                    }

                    $pages = Collection::make($item['pages'])->filter(function ($item) {
                        return User::hasCapability($item['capability']);
                    });

                    if ($pages->isEmpty()) {
                        return $item;
                    }

                    $firstPage = $pages->first();

                    if ($item['menu']['menu_slug'] === $firstPage['menu_slug']) {
                        return $item;
                    }

                    $item['menu']['menu_slug'] = $firstPage['menu_slug'];
                    $item['menu']['menu_slug_suffix'] = $firstPage['subpage_slug_suffix'];

                    $item['pages'] = $pages->map(function ($page) use ($item) {
                        $page['parent_slug'] = $item['menu']['menu_slug'];

                        return $page;
                    });

                    return $item;
                });
        }

        return static::$getAllFromPageValueColumn;
    }

    public static function getAllRowsFromPluginTable()
    {
        if (static::$getAllRowsFromPluginTable === null) {
            static::$getAllRowsFromPluginTable = DB::allRowsFromPluginTable();
        }

        return static::$getAllRowsFromPluginTable;
    }

    // public function getIdFromTableRow()
    // {
    //     return Collection::make(static::getAllRowsFromPluginTable())->pluck('id');
    // }

    public function getIdfromOptionPage($slug)
    {
        return Collection::make(static::getAllRowsFromPluginTable())
            ->where('menu_slug', $slug)
            ->pluck('id')
            ->first();
    }

    /**
     * The menu_id from all created menus.
     *
     * @return object
     */
    public function menuIdFromAllCreatedMenus()
    {
        return static::getAllFromPageValueColumn()->map(function ($page) {
            return [
                'menu_id' => $page['menu_id'],
                'menu_type' => $page['menu'] ? 'menu' : 'existing_menu'
            ];
        });
    }

    /**
     * The menu_id from all created menus.
     *
     * @return object
     */
    public function menuIdFromAllExistingMenus()
    {
        return static::getAllFromPageValueColumn()->filter(function ($page) {
            return $page['existing_menu'];
        })->pluck('menu_id');
    }

    /**
     * Get page_value from specific id.
     *
     * @return object
     */
    private static function getPageValueFromSpecificId($id)
    {
        return Collection::make(DB::rowFromPluginTable($id))->map(function ($row) {
            return unserialize($row->page_value);
        });
    }

    /**
     * @return Collection
     */
    public static function listTableData()
    {
        return Collection::make(static::getAllRowsFromPluginTable())->map(function ($row) {
            $pageValue = unserialize($row->page_value);

            if (isset($pageValue['existing_menu'])) {
                $menuItems = Collection::make($GLOBALS['menu'])->filter(function ($value) {
                    return in_array($value[2], static::defaultWordpressMenuPageSlugs());
                })->filter(function ($item) use ($pageValue) {
                    return $item[2] === $pageValue['existing_menu']['menu_slug'];
                })->map(function ($item, $key) {
                    $item[0] = __($item[0]);

                    return array_merge($item, ['position' => $key]);
                })->flatten(1);

                $dashicon = $menuItems[6];
                $position = $menuItems->last();

                $menuName = '<span style="padding-right:10px;" class="dashicons ' . $dashicon . '"></span>' . __($pageValue['existing_menu']['menu_name']);
            } else {
                $position = $pageValue['menu']['position'];
                $menuName = '<span style="padding-right:10px;" class="dashicons ' . $pageValue['menu']['icon_url'] . '"></span>' . $pageValue['menu']['menu_title'];
            }

            $totalPages = count($pageValue['pages']);

            return [
                'ID' => $row->id,
                'title' => $menuName,
                'position' => (float)$position,
                'pages' => (string)$totalPages
            ];
        });
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function deleteRowFromSpecificId()
    {
        if (!isset($_REQUEST['action'], $_REQUEST['optionpage'], $_REQUEST['nonce'])) {
            return;
        }

        list($action, $id, $nonce) = [$_REQUEST['action'], $_REQUEST['optionpage'], $_REQUEST['nonce']];

        if (!wp_verify_nonce($nonce, $id) && $action !== 'delete') {
            return;
        }

        static::getOptionNamesFromSettingPagesById($id)->map(function ($optionName) {
            return delete_option($optionName);
        });

        // dump('hello');

        // session_start();

        Session::add([
            'pages_deleted' => true,
            'pages_deleted_multiple' => static::getSubpages($id)->count() > 1 ? true : false
        ]);

        // $_SESSION['aop_pages_deleted']          = true;
        // $_SESSION['aop_pages_deleted_multiple'] = static::getSubpages($id)->count() > 1 ? true : false;

        DB::deleteRowById($id);

        wp_safe_redirect(SubpageMaster::url());
        exit;
    }

    /**
     * Page ID from specific edit page.
     *
     * @return int
     */
    public static function IdEditPage()
    {
        if (!isset($_REQUEST['optionpage'])) {
            return;
        }

        return static::listTableData()->filter(function ($value) {
            return $_REQUEST['optionpage'] === $value['ID'];
        })->values()[0]['ID'];
    }
}
