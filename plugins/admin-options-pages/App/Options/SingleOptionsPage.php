<?php

namespace AOP\App\Options;

use AOP\App\Data;
use AOP\App\Admin\AdminPages\SubpageEdit;
use AOP\App\User;
use AOP\Lib\Illuminate\Support\Collection;
use AOP\App\Admin\AdminPages\Settings\SubpageSettings;

class SingleOptionsPage
{
    /**
     * @var
     */
    private $parentSlug;

    /**
     * @var
     */
    private $subpageTitle;

    /**
     * @var
     */
    private $menuTitle;

    /**
     * @var
     */
    private $capability;

    /**
     * @var
     */
    private $menuSlug;

    /**
     * @var
     */
    private $hasTab;

    /**
     * @var
     */
    private $subpagePosition;

    /**
     * @param $args
     */
    public function run($args)
    {
        $this->parentSlug = $args['parent_slug'];
        $this->menuTitle  = $args['menu_title'];
        $this->capability = $args['capability'];
        $this->menuSlug   = $args['menu_slug'];
        $this->hasTab     = $args['include_tab'];

        $this->subpageTitle = $args['select_title'] ? $args['select_title'] : $this->menuTitle;

        $this->subpagePosition = isset($args['subpage_position']) ? (int)$args['subpage_position'] : null;

        add_action('admin_menu', function () {
            $this->submenuPage();
        });

        add_filter("option_page_capability_{$this->menuSlug}", function () {
            return $this->capability;
        });
    }

    private function submenuPage()
    {
        add_submenu_page(
            $this->parentSlug,
            $this->subpageTitle,
            $this->menuTitle,
            $this->capability,
            $this->menuSlug,
            function () {
                return $this->optionsDisplay();
            },
            $this->subpagePosition
        );
    }

    /**
     * Show the option page.
     *
     * @return mixed
     */
    private function optionsDisplay()
    {
        $submenuPages = Data::getSubpages();

        if (!$submenuPages) {
            return;
        }

        $activePage = $_GET['page'] ?: $this->menuSlug;

        print('<div class="wrap">');

        if (!strstr($_SERVER['REQUEST_URI'], '/wp-admin/options-general.php')) {
            settings_errors();
        }

        if (User::isAdministrator()) {
            $this->editButton();
        }

        $this->showTitle($submenuPages);

        $this->showTabs($submenuPages, $activePage);

        $this->showForm($submenuPages, $activePage);

        return print('</div>');
    }

    /**
     * Show the title if checked.
     *
     * @param object $submenuPages
     *
     * @return mixed
     */
    private function showTitle($submenuPages)
    {
        $subpageHasCheckedTitle = $submenuPages->filter(function ($subpage) {
            return $subpage['menu_slug'] === $this->menuSlug && $subpage['select_title'] !== 'None';
        });

        if (!$subpageHasCheckedTitle->toArray()) {
            return print('<h1 style="height:29px;"></h1>');
        }

        return printf('<h1>%s</h1>', $subpageHasCheckedTitle->pluck('select_title')->first());
    }

    /**
     * Show tabs if checked.
     *
     * @param object $submenuPages
     * @param string $activePage
     *
     * @return mixed
     */
    private function showTabs($submenuPages, $activePage)
    {
        if (!$this->hasTab) {
            return;
        }

        $currentSubmenuPages = $submenuPages->filter(function ($subpage) {
            return $subpage['parent_slug'] === $this->parentSlug;
        })->values();

        print('<h2 class="nav-tab-wrapper">');

        $currentSubmenuPages->each(function ($subpage) use ($activePage, $currentSubmenuPages) {
            if (!User::hasCapability($subpage['capability'])) {
                return;
            }

            $activeClass = ($subpage['menu_slug'] === $activePage) ? ' nav-tab-active' : '';

            $symbol = strpos($subpage['parent_slug'], '?') ? '&amp;' : '?';

            $firstPage = $currentSubmenuPages[0];

            $parentSlugCheck = ($firstPage['parent_slug'] !== $firstPage['menu_slug']) ? $subpage['parent_slug'] : '';

            $slug = $parentSlugCheck . $symbol . 'page=' . $subpage['menu_slug'];

            printf(
                '<a href="%s" class="nav-tab%s">%s</a>',
                $slug,
                $activeClass,
                $subpage['menu_title']
            );
        });

        print('</h2>');
    }

    /**
     * Show the form with fields and submit button.
     *
     * @param object $submenuPages
     * @param string $activePage
     *
     * @return mixed
     */
    private function showForm($submenuPages, $activePage)
    {
        $submenuPageHasFields = $submenuPages->filter(function ($subpage) use ($activePage) {
            return $subpage['menu_slug'] === $activePage && $subpage['fields'];
        });

        if (!$submenuPageHasFields->all()) {
            return;
        }

        $menuSlug = $submenuPageHasFields->first()['menu_slug'];

        print('<form method="post" action="options.php">');

        settings_fields($menuSlug);
        $this->doSettingsSections($menuSlug);
        $this->submitButtonSection($submenuPageHasFields);

        print('</form>');
    }

    /**
     * Show the edit button on top right of the screen.
     *
     * @return void
     */
    private function editButton()
    {
        $buttonHideEditButtonCheckbox = get_option(SubpageSettings::HIDE_EDIT_BUTTON);

        if ($buttonHideEditButtonCheckbox === '1') {
            return;
        }

        $titleSlugPart = str_replace(' ', '-', $this->menuTitle) . '-';
        $slug          = str_replace($titleSlugPart, '', $this->menuSlug);

        $id = (new Data())->getIdfromOptionPage($this->parentSlug);

        printf(
            '<a class="alignright button" href="%s?page=%s&optionpage=%s%s" style="margin-top: 10px;">%s</a>',
            admin_url() . 'admin.php',
            SubpageEdit::SLUG,
            $id,
            '#' . $slug,
            'Edit page'
        );
    }

    /**
     * A modified version of the do_settings_sections() function.
     * The table tag element is now wrapped around the foreach construct.
     *
     * @link https://developer.wordpress.org/reference/functions/do_settings_sections/
     *
     * @param $page
     *
     * @return void
     */
    private function doSettingsSections($page)
    {
        global $wp_settings_sections, $wp_settings_fields;

        if (!isset($wp_settings_sections[$page])) {
            return;
        }

        print('<table class="form-table" role="presentation">');

        $pageSectionCollection = Collection::make($wp_settings_sections[$page]);

        $sectionHasCallback = false;

        foreach ((array)$wp_settings_sections[$page] as $section) {
            if ($section['callback']) {
                if ($pageSectionCollection->first()['callback'] !== $section['callback'] && !$sectionHasCallback) {
                    print('</table>');
                    call_user_func($section['callback'], $section);
                    print('<table class="form-table" role="presentation">');
                } else {
                    call_user_func($section['callback'], $section);
                }

                $sectionHasCallback = true;
            } else {
                $sectionHasCallback = false;
            }

            if (!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']])) {
                continue;
            }

            do_settings_fields($page, $section['id']);
        }

        print('</table>');
    }

    /**
     * Show the submit button if there are fields to submit.
     *
     * @param $submenuPageHasFields
     *
     * @return void
     */
    private function submitButtonSection($submenuPageHasFields)
    {
        $fieldHasFieldName = $submenuPageHasFields
            ->pluck('fields')
            ->flatten(1)
            ->filter(function ($field) {
                return isset($field['field_name']);
            })->all();

        if ($fieldHasFieldName) {
            submit_button();
        }
    }
}
