<?php

/**
 * Lists tab
 *
 * This file is used to display the lists tab
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Admin\Page\Tab;

use Dotdigital_WordPress\Admin\Dotdigital_WordPress_Admin;
use Dotdigital_WordPress\Admin\Page\Dotdigital_WordPress_Page_Tab_Interface;
use Dotdigital_WordPress\Admin\Page\Dotdigital_WordPress_Settings_Admin;
use Dotdigital_WordPress\Includes\Client\Dotdigital_WordPress_Lists;
use Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form;
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Checkbox_Input;
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Text_Input;
use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
class Dotdigital_WordPress_Lists_Admin implements Dotdigital_WordPress_Page_Tab_Interface
{
    private const URL_SLUG = 'lists';
    /**
     * @var Dotdigital_WordPress_Setting_Form
     */
    private $form;
    /**
     * @var Dotdigital_WordPress_Lists
     */
    private $dotdigital_lists;
    /**
     * @var string
     */
    private $sort_order;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->dotdigital_lists = new Dotdigital_WordPress_Lists();
    }
    /**
     * @inheritDoc
     */
    public function initialise()
    {
        $lists = get_option($this->get_slug());
        try {
            $available_lists = $this->dotdigital_lists->get();
        } catch (\Dotdigital_WordPress_Vendor\Dotdigital\Exception\ResponseValidationException $exception) {
            $available_lists = array();
        }
        $this->form = new Dotdigital_WordPress_Setting_Form(Dotdigital_WordPress_Config::SETTING_LISTS_PATH, 'List settings', $this->get_slug());
        $this->set_sort_order();
        foreach ($this->sort($available_lists) as $list) {
            $list_name = $list->getName();
            $list_id = $list->getId();
            $configuration_path = Dotdigital_WordPress_Config::SETTING_LISTS_PATH . "[{$list_name}]";
            $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Checkbox_Input("{$configuration_path}[id]", $list_name, "{$this->get_slug()}", $list_id));
            $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input("{$configuration_path}[label]", $list_name, "{$this->get_slug()}", $list_id));
            $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Checkbox_Input("{$configuration_path}[isVisible]", $list_name, "{$this->get_slug()}", $list_id));
            /**
             * Filters the value of the checkbox input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[id]/value", function () use($list_id) {
                return $list_id;
            });
            /**
             * Filters the css classes of the checkbox input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[id]/attributes", function (string $value) {
                return $value . ' toggle-row-inputs=true';
            });
            /**
             * Filters the value of the checkbox input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[id]/checked", function () use($lists, $list_name) {
                if (isset($lists[$list_name]) && \array_key_exists('id', $lists[$list_name])) {
                    return \true;
                }
                return \false;
            });
            /**
             * Filters the attributes of the label input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[label]/value", function () use($lists, $list_name) {
                if (isset($lists[$list_name]) && \array_key_exists('label', $lists[$list_name])) {
                    return $lists[$list_name]['label'];
                }
                return $list_name;
            });
            /**
             * Filters the value of the visibility input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[isVisible]/value", function () {
                return 'on';
            });
            /**
             * Filters the state  of the visibility input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[isVisible]/checked", function () use($lists, $list_name) {
                return \filter_var($lists[$list_name]['isVisible'] ?? \false, \FILTER_VALIDATE_BOOLEAN);
            });
            /**
             * Filters the attributes of the checkbox input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[label]/attributes", function ($value) use($lists, $list_name) {
                if (!isset($lists[$list_name])) {
                    return $value . ' disabled';
                }
                return $value;
            });
            /**
             * Filters the attributes of the checkbox input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[isVisible]/attributes", function ($value) use($lists, $list_name) {
                if (!isset($lists[$list_name])) {
                    return $value . ' disabled';
                }
                return $value;
            });
        }
        $this->form->initialise();
        add_filter("{$this->get_slug()}/save", array($this, 'save'), 10, 1);
    }
    /**
     * @inheritDoc
     */
    public function render()
    {
        $form = $this->form;
        $view = $this;
        require_once DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'admin/view/tabs/dotdigital-wordpress-lists-admin.php';
    }
    /**
     * @param array|null $options
     *
     * @return array|array[]
     */
    public function save($options)
    {
        $array_structure = array('id' => '', 'label' => '', 'isVisible' => \false);
        $options = $options ?? array();
        $options = \array_map(function ($list_option) use($array_structure) {
            $current_option = \array_merge($array_structure, $list_option);
            $current_option['isVisible'] = \filter_var($current_option['isVisible'], \FILTER_VALIDATE_BOOLEAN);
            return $current_option;
        }, $options);
        do_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notice', 'Lists saved', 'success');
        return $options;
    }
    /**
     * @inheritDoc
     */
    public function get_slug() : string
    {
        return Dotdigital_WordPress_Config::SETTING_LISTS_PATH;
    }
    /**
     * @inheritDoc
     */
    public function get_url_slug() : string
    {
        return self::URL_SLUG;
    }
    /**
     * @return string
     */
    public function get_page_slug() : string
    {
        return Dotdigital_WordPress_Settings_Admin::URL_SLUG;
    }
    /**
     * @inheritDoc
     */
    public function get_title() : string
    {
        return __('My lists');
    }
    /**
     * Get the sort order.
     *
     * @return string
     */
    public function get_sort_order()
    {
        return $this->sort_order;
    }
    /**
     * Set the sort order.
     *
     * @return void
     */
    private function set_sort_order()
    {
        $this->sort_order = isset($_GET['order']) ? sanitize_text_field(wp_unslash($_GET['order'])) : 'asc';
    }
    /**
     * Sorts the lists by the supplied order.
     *
     * @param array $lists
     * @return mixed|null
     */
    private function sort($lists)
    {
        Dotdigital_WordPress_Admin::sort($lists, $this->sort_order);
        return apply_filters("{$this->get_slug()}/lists/sort", $lists);
    }
}
