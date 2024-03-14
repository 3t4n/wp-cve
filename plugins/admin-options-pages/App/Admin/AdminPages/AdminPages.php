<?php

namespace AOP\App\Admin\AdminPages;

use AOP\App\Data;
use AOP\App\Plugin;
use AOP\App\Session;
use AOP\App\Singleton;
use AOP\App\Validation;
use AOP\App\Admin\Input;
use AOP\App\Admin\Notice;
use AOP\App\Admin\AdminPages\Settings\SubpageSettings;

final class AdminPages
{
    use Singleton;

    /**
     * @var Input
     */
    private $input;

    private function __construct()
    {
        $this->input = new Input();

        add_action('init', function () {
            $this->handleInputDataForCreatePage();
            $this->noticeAfterCreatePages();
            $this->updateOptionNamesAfterFirstSave();
            $this->noticeAfterDeletePages();
            $this->updateOptionNamesAfterDeletePages();
            $this->deletePages();
            Session::destroy();
        });

        add_action('admin_menu', function () {
            $this->setPages();
        });

        add_action('wp_ajax_notUsedExistingMenuTitles', function () {
            return wp_send_json(Data::notUsedExistingMenuTitles());
        });
    }

    private function setPages()
    {
        add_menu_page(
            MenuPage::PAGE_TITLE,
            MenuPage::MENU_TITLE,
            MenuPage::CAPABILITY,
            SubpageMaster::SLUG,
            '',
            MenuPage::ICON_URL,
            MenuPage::POSITION
        );

        // Master Page
        add_submenu_page(
            SubpageMaster::SLUG,
            SubpageMaster::PAGE_TITLE,
            SubpageMaster::MENU_TITLE,
            MenuPage::CAPABILITY,
            SubpageMaster::SLUG,
            function () {
                SubpageMaster::view();
            }
        );

        // Create Page
        add_submenu_page(
            SubpageMaster::SLUG,
            SubpageCreate::PAGE_TITLE,
            _x(SubpageCreate::MENU_TITLE, 'Customize Changeset'),
            MenuPage::CAPABILITY,
            SubpageCreate::SLUG,
            function () {
                $this->checkOptionAopStandardWpAdminMenu();

                SubpageCreate::view();
            }
        );

        // Edit Page
        add_submenu_page(
            null,
            SubpageEdit::PAGE_TITLE,
            SubpageEdit::MENU_TITLE,
            MenuPage::CAPABILITY,
            SubpageEdit::SLUG,
            function () {
                $this->input->prepareDataForPages(new Validation);

                SubpageEdit::view();
            }
        );

        // Settings Page
        add_submenu_page(
            SubpageMaster::SLUG,
            SubpageSettings::PAGE_TITLE,
            __(SubpageSettings::MENU_TITLE),
            MenuPage::CAPABILITY,
            Plugin::_NAME . '_settings',
            function () {
                SubpageSettings::view();
            }
        );

        SubpageSettings::allOptions();
    }

    private function deletePages()
    {
        Data::deleteRowFromSpecificId();
    }

    private function handleInputDataForCreatePage()
    {
        if (!SubpageCreate::isCurrentPage()) {
            return;
        }

        if (!isset($_POST['existing_menu']) && !isset($_POST['menu'])) {
            return;
        }

        $this->input->prepareDataForPages(new Validation);
    }

    private function noticeAfterCreatePages()
    {
        if (!SubpageEdit::isCurrentPage()) {
            return;
        }

        if (!Session::get('pages_saved')) {
            return;
        }

        add_action('admin_notices', function () {
            Notice::succes(__('Saved.'), true);
        });
    }

    private function updateOptionNamesAfterFirstSave()
    {
        if (!SubpageEdit::isCurrentPage()) {
            return;
        }

        if (!Session::get('request_on_save')) {
            return;
        }

        update_option(Plugin::PREFIX_ . 'option_names', Data::getAllCreatedOptionNamesAfterSave()->all());

        $optionsNames = Data::getFieldsfromId($_REQUEST['optionpage'])
            ->whereIn('field_name', get_option(Plugin::PREFIX_ . 'option_names'));

        $optionsNames->filter(function ($item) {
            return isset($item['default_value']) && isset($item['field_name']);
        })->map(function ($item) {
            add_option($item['field_name'], $item['default_value']);
        });
    }

    private function noticeAfterDeletePages()
    {
        if (!SubpageMaster::isCurrentPage()) {
            return;
        }

        if (!Session::get('pages_deleted')) {
            return;
        }

        add_action('admin_notices', function () {
            $message = Session::get('pages_deleted_multiple') ? __('Pages deleted.') : __('Page deleted.');

            Notice::succes($message, true);
        });
    }

    private function updateOptionNamesAfterDeletePages()
    {
        if (is_null(Data::getAllCreatedOptionNames())) {
            return;
        }

        if (!Session::get('pages_deleted')) {
            return;
        }

        update_option(Plugin::PREFIX_ . 'option_names', Data::getAllCreatedOptionNames()->all());
    }

    private function checkOptionAopStandardWpAdminMenu()
    {
        if (!get_option(Plugin::PREFIX_ . 'admin_menu_list')) {
            add_option(Plugin::PREFIX_ . 'admin_menu_list', $GLOBALS['menu'], '', 'no');
        }

        update_option(Plugin::PREFIX_ . 'admin_menu_list', $GLOBALS['menu'], 'no');
    }
}
