<?php

namespace AOP\App\Admin;

use AOP\App\Data;
use AOP\App\Plugin;
use AOP\App\Validation;
use AOP\App\Database\DB;
use AOP\App\Admin\AdminPages\SubpageEdit;
use AOP\Lib\Illuminate\Support\Collection;
use AOP\App\Admin\AdminPages\SubpageCreate;
use AOP\App\Session;

class Input extends DB
{
    /**
     * @param Validation $validate
     */
    public function prepareDataForPages(Validation $validate)
    {
        if (!isset($_REQUEST['page'])) {
            return;
        }

        if ($_REQUEST['page'] !== SubpageCreate::SLUG && $_REQUEST['page'] !== SubpageEdit::SLUG) {
            return;
        }

        if ($_REQUEST['page'] === SubpageCreate::SLUG) {
            $nonceName   = SubpageCreate::$nonceName;
            $nonceAction = SubpageCreate::$nonceAction;
        }

        if ($_REQUEST['page'] === SubpageEdit::SLUG) {
            $nonceName   = SubpageEdit::$nonceName;
            $nonceAction = SubpageEdit::$nonceAction;
        }

        if (!$validate->validateNonce($nonceName, $nonceAction)) {
            return;
        }

        if (!isset($_REQUEST['existing_menu']) && !isset($_REQUEST['menu'])) {
            return $validate->wpDieMessage();
        }

        if (!$validate->pages()) {
            return;
        }

        if (isset($_REQUEST['existing_menu'])) {
            return $this->existingMenu($validate);
        }

        if (isset($_REQUEST['menu'])) {
            return $this->customMenu($validate);
        }

        return;
    }

    /**
     * @param Validation $validate
     */
    private function existingMenu(Validation $validate)
    {
        if (!$validate->existingMenu()) {
            return;
        }

        $this->processingDeleteOptionNames();
        $this->processingFields($this->exitingMenuFields($validate));
    }

    /**
     * @param Validation $validate
     */
    private function customMenu(Validation $validate)
    {
        if (!$validate->customMenu()) {
            return;
        }

        $this->processingDeleteOptionNames();
        $this->processingFields($this->customMenuFields($validate));
    }

    /**
     * Get the serialized data/fields from the pages with a custom menu.
     *
     * @param Validation $validate
     *
     * @return array
     */
    public function customMenuFields(Validation $validate)
    {
        $menuPage = $this->menuPageForCustomMenu($validate);

        return [
            'menu_slug' => $menuPage['menu_slug'],
            'serialized_value' => serialize([
                'menu_id' => $validate->number($_POST['menu_id']),
                'menu' => $menuPage,
                'pages' => $this->subpagesForCustomMenu($validate),
                'side_bar_visible' => ($validate->toggle($_POST['side_bar_visible']) === 'true') ? true : false
            ])
        ];
    }

    /**
     * Get the serialized data/fields from the pages with an exiting menu.
     *
     * @param Validation $validate
     *
     * @return array
     */
    public function exitingMenuFields(Validation $validate)
    {
        return [
            'menu_slug' => $this->existingMenuParentSlug($validate),
            'serialized_value' => serialize([
                'menu_id' => $validate->number($_POST['menu_id']),
                'existing_menu' => [
                    'menu_name' => $this->existingMenuMenuName($validate),
                    'menu_slug' => $this->existingMenuParentSlug($validate)
                ],
                'pages' => $this->subpagesForExistingMenu($validate),
                'side_bar_visible' => $validate->toggle($_POST['side_bar_visible']) === 'true'
            ])
        ];
    }

    /**
     * Posting or updating the exiting menu fields.
     *
     * @param $fields
     */
    public function processingFields($fields)
    {
        if ($_REQUEST['page'] === SubpageCreate::SLUG) {
            $this->insertNewPage(
                $fields['menu_slug'],
                $fields['serialized_value']
            );

            // session_start();
            // $_SESSION = [];

            // $_SESSION['aop_pages_saved']     = true;
            // $_SESSION['aop_request_on_save'] = $_REQUEST;

            Session::add([
                'pages_saved' => true,
                'request_on_save' => $_REQUEST
            ]);

            // update_option('aop_session_faker', [
            //     'aop_pages_saved' => true,
            //     'aop_request_on_save' => $_REQUEST
            // ]);

            wp_safe_redirect(add_query_arg('optionpage', $this->insertId(), SubpageEdit::url()));
            exit;
        }

        if ($_REQUEST['page'] === SubpageEdit::SLUG) {
            $this->updateEditPage(
                Data::IdEditPage(),
                $fields['menu_slug'],
                $fields['serialized_value']
            );

            update_option(Plugin::PREFIX_ . 'option_names', Data::getAllCreatedOptionNamesAfterEdit()->all());

            $optionsNames = Data::getFieldsfromId($_REQUEST['optionpage'])
                ->whereIn('field_name', get_option(Plugin::PREFIX_ . 'option_names'));

            $optionsNames->filter(function ($item) {
                return isset($item['default_value']) && isset($item['field_name']);
            })->map(function ($item) {
                $autoload = isset($item['toggle_autoload']) ? $item['toggle_autoload'] : 'yes';

                $optionValue = get_option($item['field_name']) ? get_option($item['field_name']) : $item['default_value'];

                Plugin::updateOption($item['field_name'], $optionValue, $autoload);
            });

            $optionNames = (get_current_screen()->id === 'admin_page_' . SubpageEdit::SLUG)
                ? Data::getAllOptionNamesExcludeCurrentPageFieldNames()
                : Data::getAllOptionNames();

            wp_localize_script(Plugin::PREFIX . 'app-js', Plugin::PREFIX_ . 'option_names_js', $optionNames);
            wp_localize_script(Plugin::PREFIX . 'app-js', Plugin::PREFIX_ . 'script_data_js', Data::js()->toArray());

            Notice::succes(__('Updated.'));
        }
    }

    /**
     * @return Collection
     */
    public function processingDeleteOptionNames()
    {
        return Collection::make(Data::getAllRegisteredFieldNames())
            ->diff(Data::getFieldsWithFieldNames())
            ->values()
            ->map(function ($name) {
                return delete_option($name);
            });
    }

    /**
     * Get the menu name for the exiting menu.
     *
     * @param Validation $validate
     *
     * @return string
     */
    public function existingMenuMenuName(Validation $validate)
    {
        return Data::getDefaultWordpressMenus()
            ->where('slug', $this->existingMenuParentSlug($validate))
            ->values()
            ->flatten(1)
            ->first();
    }

    /**
     * Get the parent slug for the exiting menu.
     *
     * @param Validation $validate
     *
     * @return string
     */
    public function existingMenuParentSlug(Validation $validate)
    {
        return $validate->existingMenuMenuSlug();
    }

    /**
     * Get the parent slug for the custom menu.
     *
     * @param Validation $validate
     *
     * @return string
     */
    public function parentSlugForCustomMenu(Validation $validate)
    {
        return $validate->alphanumericDashesAll($_POST['pages'][0]['subpage_slug']);
    }

    /**
     * Get the parent slug for the custom menu.
     *
     * @param Validation $validate
     *
     * @return string
     */
    public function parentSlugSuffixForCustomMenu(Validation $validate)
    {
        return $validate->alphanumeric($_POST['pages'][0]['subpage_slug_suffix']);
    }

    /**
     * Get menu items for custom menu.
     *
     * @param Validation $validate
     *
     * @return array
     */
    public function menuPageForCustomMenu(Validation $validate)
    {
        return [
            'page_title' => $validate->already($_POST['menu']['menu_title'], $validate->customMenu()),
            'menu_title' => $validate->already($_POST['menu']['menu_title'], $validate->customMenu()),
            'capability' => $validate->capabilityMenu(),
            'menu_slug' => $validate->already($this->parentSlugForCustomMenu($validate)),
            'menu_slug_suffix' => $validate->already($this->parentSlugSuffixForCustomMenu($validate)),
            'icon_url' => $validate->already($_POST['menu']['menu_icon'], $validate->customMenu()),
            'position' => $validate->already($_POST['menu']['menu_position'], $validate->customMenu()),
        ];
    }

    /**
     * Get sub pages for existing menu types.
     *
     * @param Validation $validate
     *
     * @return array
     */
    protected function subpagesForExistingMenu(Validation $validate)
    {
        if (!isset($_POST['pages'])) {
            return [];
        }

        return Collection::make($_POST['pages'])->map(function ($subpage, $key) use ($validate) {
            $validatedSubpageTab = isset($subpage['subpage_tab']) && $subpage['subpage_tab'] === '1';

            $validatedParentSlug = $validate->already($this->existingMenuParentSlug($validate), $validate->existingMenu());

            $validatedSubpageToggle = $validate->toggle($subpage['subpage_toggle']) === 'true';

            return [
                'parent_slug' => $validatedParentSlug,
                'page_title' => '',
                'subpage_title' => $validate->textField($subpage['subpage_title']),
                'menu_title' => $validate->textField($subpage['menu_title']),
                'capability' => $validate->capabilityPage($subpage['role']),
                'menu_slug' => $validate->alphanumericDashesAll($subpage['subpage_slug']),
                'subpage_slug_suffix' => $validate->alphanumeric($subpage['subpage_slug_suffix']),
                'include_tab' => $validatedSubpageTab,
                'select_title' => $validate->textField($subpage['select_title']),
                'subpage_toggle' => $validatedSubpageToggle,
                'fields' => $this->fields($subpage, $validate),
                'subpage_position' => $validate->integerOrEmpty($subpage['subpage_position'])
            ];
        })->toArray();
    }

    /**
     * Get subpages for custom menu types.
     *
     * @param Validation $validate
     *
     * @return array
     */
    protected function subpagesForCustomMenu(Validation $validate)
    {
        if (!isset($_POST['pages'])) {
            return [];
        }

        return Collection::make($_POST['pages'])->map(function ($subpage, $key) use ($validate) {
            $validatedSubpageTab = isset($subpage['subpage_tab']) && $subpage['subpage_tab'] === '1';

            $validatedParentSlug = $this->parentSlugForCustomMenu($validate);
            $validatedMenuSlug = ($key === 0) ? $validatedParentSlug : $validate->alphanumericDashesAll($subpage['subpage_slug']);

            return [
                'parent_slug' => $validatedParentSlug,
                'page_title' => $validate->textField($_POST['menu']['menu_title']),
                'subpage_title' => $validate->textField($subpage['subpage_title']),
                'menu_title' => $validate->textField($subpage['menu_title']),
                'capability' => $validate->capabilityPage($subpage['role']),
                'menu_slug' => $validatedMenuSlug,
                'subpage_slug_suffix' => $validate->alphanumeric($subpage['subpage_slug_suffix']),
                'include_tab' => $validatedSubpageTab,
                'select_title' => $validate->textField($subpage['select_title']),
                'subpage_toggle' => $validate->toggleBoolean($subpage['subpage_toggle']),
                'fields' => $this->fields($subpage, $validate)
            ];
        })->toArray();
    }

    /**
     * Get all fields from specific subpage.
     *
     * @param array      $subpage Subpage with fields.
     * @param Validation $validate
     *
     * @return array
     */
    protected function fields($subpage, Validation $validate)
    {
        if (!isset($subpage['fields'])) {
            return [];
        }

        $fields = Collection::make($subpage['fields']);

        $correctTypes = [
            'text_field',
            'textarea',
            'number',
            'checkbox',
            'radio',
            'select_field',
            'image',
            'color_picker',
            'title',
            'description',
            'horizontal_rule',
            'wysiwyg_editor'
        ];

        $validate->fieldTypes($fields, $correctTypes);

        /**
         * Get the wysiwyg editors.
         *
         * @var array
         */
        $wysiwygEditor = $fields->where('type', 'wysiwyg_editor')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'wysiwyg_editor',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'field_label' => $validate->textField($field['field_label']),
                'field_name' => $validate->already($field['field_name'], $validate->pages()),
                'toggle_autoload' => $validate->toggleAutoload($field),
                'default_value' => $validate->textField($field['default_value']),
                'class_attribute' => $validate->classAttribute($field['class_attribute']),
                'toggle_show_media_upload_buttons' => $validate->toggleOnOff($field['toggle_show_media_upload_buttons']),
                'description' => $validate->textArea($field['description']),
                'toolbar' => $validate->contains(['full', 'basic'], $field['toolbar'])
            ];
        })->all();

        /**
         * Get the text fields.
         *
         * @var array
         */
        $textField = $fields->where('type', 'text_field')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'text_field',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'field_label' => $validate->textField($field['field_label']),
                'field_name' => $validate->already($field['field_name'], $validate->pages()),
                'toggle_autoload' => $validate->toggleAutoload($field),
                'placeholder' => $validate->textField($field['placeholder']),
                'default_value' => $validate->textField($field['default_value']),
                'text_right' => $validate->textField($field['text_right']),
                'class_attribute' => $validate->classAttribute($field['class_attribute']),
                'field_style' => $validate->textFieldStyle($field['field_style']),
                'toggle_text_format' => $validate->toggleTextFormat($field),
                'description' => $validate->textArea($field['description'])
            ];
        })->all();

        /**
         * Get the textareas.
         *
         * @var array
         */
        $textArea = $fields->where('type', 'textarea')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'textarea',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'field_label' => $validate->textField($field['field_label']),
                'field_name' => $validate->already($field['field_name'], $validate->pages()),
                'toggle_autoload' => $validate->toggleAutoload($field),
                'placeholder' => $validate->textField($field['placeholder']),
                'default_value' => $validate->textField($field['default_value']),
                'class_attribute' => $validate->classAttribute($field['class_attribute']),
                'field_style' => $validate->textareaStyle($field['field_style']),
                'description' => $validate->textArea($field['description']),
                'description_italic' => $validate->toggleOnOff($field['description_italic'])
            ];
        })->all();

        /**
         * Get the numbers.
         *
         * @var array
         */
        $number = $fields->where('type', 'number')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'number',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'field_label' => $validate->textField($field['field_label']),
                'field_name' => $validate->already($field['field_name'], $validate->pages()),
                'toggle_autoload' => $validate->toggleAutoload($field),
                'min_value' => $validate->textField($field['min_value']),
                'max_value' => $validate->textField($field['max_value']),
                'default_value' => $validate->textField($field['default_value']),
                'decimals' => $validate->textField($field['decimals']),
                'text_right' => $validate->textField($field['text_right']),
                'class_attribute' => $validate->classAttribute($field['class_attribute']),
                'description' => $validate->textArea($field['description'])
            ];
        })->all();

        /**
         * Get the checkboxes.
         *
         * @var array
         */
        $checkboxe = $fields->where('type', 'checkbox')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'checkbox',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'field_label' => $validate->textField($field['field_label']),
                'field_name' => $validate->already($field['field_name'], $validate->pages()),
                'toggle_autoload' => $validate->toggleAutoload($field),
                'text_right' => $validate->textField($field['text_right']),
                'default_value' => '0',
                'class_attribute' => $validate->classAttribute($field['class_attribute']),
                'description' => $validate->textArea($field['description'])
            ];
        })->all();

        /**
         * Get the radio buttons.
         *
         * @var array
         */
        $radioButton = $fields->where('type', 'radio')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'radio',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'field_label' => $validate->textField($field['field_label']),
                'field_name' => $validate->already($field['field_name'], $validate->pages()),
                'toggle_autoload' => $validate->toggleAutoload($field),
                'table_list' => $validate->tableList($field['table_list']),
                'default_value' => Collection::make($validate->tableList($field['table_list']))->first()['value'],
                'class_attribute' => $validate->classAttribute($field['class_attribute']),
                'description' => $validate->textArea($field['description'])
            ];
        })->all();

        /**
         * Get the select buttons.
         *
         * @var array
         */
        $selectField = $fields->where('type', 'select_field')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'select_field',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'field_label' => $validate->textField($field['field_label']),
                'field_name' => $validate->already($field['field_name'], $validate->pages()),
                'toggle_autoload' => $validate->toggleAutoload($field),
                'table_list' => $validate->tableList($field['table_list']),
                'default_value' => Collection::make($validate->tableList($field['table_list']))->first()['value'],
                'class_attribute' => $validate->classAttribute($field['class_attribute']),
                'description' => $validate->textArea($field['description'])
            ];
        })->all();

        /**
         * Get the images.
         *
         * @var array
         */
        $image = $fields->where('type', 'image')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'image',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'field_label' => $validate->textField($field['field_label']),
                'field_name' => $validate->already($field['field_name'], $validate->pages()),
                'toggle_autoload' => $validate->toggleAutoload($field),
                'class_attribute' => $validate->classAttribute($field['class_attribute']),
                'default_value' => '',
                'description' => $validate->textArea($field['description']),
                'extensions' => $validate->imageExtensions(isset($field['extensions']) ? $field['extensions'] : [])
            ];
        })->all();

        /**
         * Get the color pickers.
         *
         * @var array
         */
        $colorPicker = $fields->where('type', 'color_picker')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'color_picker',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'field_label' => $validate->textField($field['field_label']),
                'field_name' => $validate->already($field['field_name'], $validate->pages()),
                'toggle_autoload' => $validate->toggleAutoload($field),
                'default_value' => $validate->colorHex($field['default_value']),
                'class_attribute' => $validate->classAttribute($field['class_attribute']),
                'description' => $validate->textArea($field['description'])
            ];
        })->all();

        /**
         * Get the titles.
         *
         * @var array
         */
        $title = $fields->where('type', 'title')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'title',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'field_title' => $validate->textField($field['field_title']),
                'class_attribute' => $validate->classAttribute($field['class_attribute'])
            ];
        })->all();

        /**
         * Get the descriptions.
         *
         * @var array
         */
        $description = $fields->where('type', 'description')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'description',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'description' => $validate->textArea($field['description']),
                'class_attribute' => $validate->classAttribute($field['class_attribute'])
            ];
        })->all();

        /**
         * Get the Horizontal rules.
         *
         * @var array
         */
        $horizontalRule = $fields->where('type', 'horizontal_rule')->map(function ($field) use ($validate) {
            return [
                'id' => $validate->fieldId($field['id']),
                'type' => 'horizontal_rule',
                'field_key' => $validate->number($field['field_key']),
                'page_name' => $validate->textField($field['page_slug']),
                'field_toggle' => $validate->toggle($field['field_toggle']),
                'class_attribute' => $validate->classAttribute($field['class_attribute'])
            ];
        })->all();

        /**
         * Wrap all fields.
         *
         * @var object
         */
        $allFields = Collection::make([
            $wysiwygEditor,
            $textField,
            $textArea,
            $number,
            $checkboxe,
            $radioButton,
            $selectField,
            $image,
            $colorPicker,
            $title,
            $description,
            $horizontalRule
        ])->flatten(1);

        return $allFields->sortBy('field_key')->values()->all();
    }
}
