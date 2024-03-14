<?php

namespace AOP\App\Options;

use AOP\App\Data;
use AOP\App\User;
use AOP\App\Singleton;
use AOP\App\Validation;
use AOP\App\Admin\Input;
use AOP\App\Database\DB;
use AOP\Lib\Illuminate\Support\Collection;

class OptionsPages extends DB
{
    use Singleton;

    /**
     * @return void
     */
    private function __construct()
    {
        if (!DB::tableExist()) {
            return;
        }

        $this->postRequestCustomMenu();
        $this->postRequestExistingMenu();

        return $this->getAllOptionsPagesAndFields();
    }

    /**
     * @return mixed
     */
    public function getAllOptionsPagesAndFields()
    {
        $data = Data::getAllFromPageValueColumn();

        if (isset($_POST['menu_id'])) {
            $data = $data->whereNotIn('menu_id', [$_POST['menu_id']]);
        }

        return $data->map(function ($item) {
            if (isset($item['menu'])) {
                (new MenuPage)->run($item['menu']);
            }

            return $item['pages'];
        })->flatten(1)->filter(function ($page) {
            (new SingleOptionsPage)->run($page);

            $cleanSlug = strtok($_SERVER['REQUEST_URI'], '?');
            $prefix = parse_url(site_url(), PHP_URL_PATH) !== null ? parse_url(site_url(), PHP_URL_PATH) : '';

            if (!isset($_REQUEST['page']) && $cleanSlug !== $prefix . '/wp-admin/options.php') {
                return null;
            }

            if (!isset($_REQUEST['page']) && !isset($_REQUEST['option_page'])) {
                return null;
            }

            return $_REQUEST['page'] === $page['menu_slug'] || $cleanSlug === $prefix . '/wp-admin/options.php';
        })->map(function ($page) {
            return (new OptionsFields)->run($page['fields']);
        });
    }

    /**
     * @return Collection|void
     */
    public function postRequestCustomMenu()
    {
        if (!isset($_POST['menu']) || !isset($_POST['pages'])) {
            return;
        }

        $validate = new Validation;

        if (!$validate->customMenu()) {
            return;
        }

        (new MenuPage)->run((new Input)->menuPageForCustomMenu($validate));

        return Collection::make($_POST['pages'])->map(function ($subpage, $key) use ($validate) {
            $validatedSubpageTab = isset($subpage['subpage_tab']) && $subpage['subpage_tab'] === '1';

            $validatedParentSlug = $validate->alphanumericDashesAll($_POST['pages'][0]['subpage_slug']);
            $validatedMenuSlug = ($key === 0) ? $validatedParentSlug : $validate->alphanumericDashesAll($subpage['subpage_slug']);

            (new SingleOptionsPage)->run([
                'parent_slug' => $validatedParentSlug,
                'menu_title' => $validate->textField($subpage['menu_title']),
                'capability' => $validate->capabilityPage($subpage['role']),
                'menu_slug' => $validatedMenuSlug,
                'include_tab' => $validatedSubpageTab,
                'select_title' => $validate->textField($subpage['select_title']),
                'subpage_position' => $validate->integerOrEmpty($subpage['subpage_position'])
            ]);
        });
    }

    /**
     * @return Collection|void
     */
    public function postRequestExistingMenu()
    {
        if (!isset($_POST['existing_menu']) || !isset($_POST['pages'])) {
            return;
        }

        $validate = new Validation;

        return Collection::make($_POST['pages'])->map(function ($subpage, $key) use ($validate) {
            $validatedSubpageTab = isset($subpage['subpage_tab']) && $subpage['subpage_tab'] === '1';

            $validatedParentSlug = $_POST['existing_menu']['menu_slug'];

            (new SingleOptionsPage)->run([
                'parent_slug' => $validatedParentSlug,
                'menu_title' => $validate->textField($subpage['menu_title']),
                'capability' => $validate->capabilityPage($subpage['role']),
                'menu_slug' => $validate->alphanumericDashesAll($subpage['subpage_slug']),
                'include_tab' => $validatedSubpageTab,
                'select_title' => $validate->textField($subpage['select_title']),
                'subpage_position' => $validate->integerOrEmpty($subpage['subpage_position'])
            ]);
        });
    }
}
