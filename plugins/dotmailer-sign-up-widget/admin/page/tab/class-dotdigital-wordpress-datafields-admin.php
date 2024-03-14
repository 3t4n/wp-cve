<?php

/**
 * Data fields tab
 *
 * This file is used to display the data fields tab
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Admin\Page\Tab;

use Dotdigital_WordPress\Admin\Dotdigital_WordPress_Admin;
use Dotdigital_WordPress\Admin\Page\Dotdigital_WordPress_Page_Tab_Interface;
use Dotdigital_WordPress\Admin\Page\Dotdigital_WordPress_Settings_Admin;
use Dotdigital_WordPress\Includes\Client\Dotdigital_WordPress_Datafields;
use Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form;
use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Checkbox_Input;
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Text_Input;
class Dotdigital_WordPress_Datafields_Admin implements Dotdigital_WordPress_Page_Tab_Interface
{
    private const URL_SLUG = 'data_fields';
    /**
     * @var Dotdigital_WordPress_Setting_Form
     */
    private $form;
    /**
     * @var Dotdigital_WordPress_Datafields
     */
    private $dotdigital_data_fields;
    /**
     * @var string
     */
    private $sort_order;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->dotdigital_data_fields = new Dotdigital_WordPress_Datafields();
    }
    /**
     * @inheritDoc
     */
    public function initialise()
    {
        $data_fields = get_option($this->get_slug());
        try {
            $available_data_fields = $this->dotdigital_data_fields->get();
        } catch (\Dotdigital_WordPress_Vendor\Dotdigital\Exception\ResponseValidationException $exception) {
            $available_data_fields = array();
        }
        $this->form = new Dotdigital_WordPress_Setting_Form(Dotdigital_WordPress_Config::SETTING_DATAFIELDS_PATH, 'Contact data field settings', $this->get_slug());
        $this->set_sort_order();
        foreach ($this->sort($available_data_fields) as $data_field) {
            $data_field_name = $data_field->getName();
            $data_field_type = $data_field->getType();
            $configuration_path = Dotdigital_WordPress_Config::SETTING_DATAFIELDS_PATH . "[{$data_field_name}]";
            $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Checkbox_Input("{$configuration_path}[name]", $data_field_name, "{$this->get_slug()}", $data_field_name));
            $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input("{$configuration_path}[label]", $data_field_name, "{$this->get_slug()}", $data_field_name));
            $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input("{$configuration_path}[type]", $data_field_name, "{$this->get_slug()}", $data_field_name));
            $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Checkbox_Input("{$configuration_path}[isRequired]", $data_field_name, "{$this->get_slug()}", $data_field_name));
            /**
             * Filters the value of the checkbox input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[name]/value", function ($value) use($data_field_name) {
                return $data_field_name;
            });
            /**
             * Filters the state of the checkbox input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[name]/checked", function ($value) use($data_field_name, $data_fields) {
                if (isset($data_fields[$data_field_name]) && \array_key_exists('name', $data_fields[$data_field_name])) {
                    return \true;
                }
                return \false;
            });
            /**
             * Filters the css classes of the checkbox input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[name]/attributes", function (string $value) {
                return $value . ' toggle-row-inputs=true';
            });
            /**
             * Filters the value of the type input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[type]/value", function ($value) use($data_field_type) {
                return $data_field_type;
            });
            /**
             * Filters the state of the type input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[type]/attributes", function ($value) use($data_field_name, $data_fields) {
                if (!isset($data_fields[$data_field_name])) {
                    return $value . ' hidden disabled';
                }
                return $value . ' hidden';
            });
            /**
             * Filters the value of the type input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[type]/value", function ($value) use($data_field_type) {
                return $data_field_type;
            });
            /**
             * Filters the state of the type input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[label]/value", function ($value) use($data_field_name, $data_fields) {
                if (isset($data_fields[$data_field_name]) && \array_key_exists('label', $data_fields[$data_field_name])) {
                    return $data_fields[$data_field_name]['label'];
                }
                return $data_field_name;
            });
            /**
             * Filters the attributes of the label input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[label]/attributes", function ($value) use($data_field_name, $data_fields) {
                if (!isset($data_fields[$data_field_name])) {
                    return $value . ' disabled';
                }
                return $value;
            });
            /**
             * Filters the value of the isRequired input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[isRequired]/value", function ($value) {
                return 'on';
            });
            /**
             * Filters the state  of the isRequired input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[isRequired]/checked", function ($value) use($data_field_name, $data_fields) {
                if (isset($data_fields[$data_field_name]) && \array_key_exists('isRequired', $data_fields[$data_field_name])) {
                    return \filter_var($data_fields[$data_field_name]['isRequired'] ?? \false, \FILTER_VALIDATE_BOOLEAN);
                }
                return \false;
            });
            /**
             * Filters the state  of the visibility input.
             *
             * @param string $value The value.
             */
            add_filter("{$this->get_slug()}/{$configuration_path}[isRequired]/attributes", function ($value) use($data_field_name, $data_fields) {
                if (!isset($data_fields[$data_field_name])) {
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
        require_once DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'admin/view/tabs/dotdigital-wordpress-datafields-admin.php';
    }
    /**
     * @param array|null $options
     *
     * @return array|array[]
     */
    public function save($options)
    {
        $array_structure = array('label' => '', 'name' => '', 'type' => '', 'isRequired' => \false);
        $options = $options ?? array();
        $options = \array_map(function ($list_option) use($array_structure) {
            $current_option = \array_merge($array_structure, $list_option);
            $current_option['isRequired'] = \filter_var($current_option['isRequired'], \FILTER_VALIDATE_BOOLEAN);
            return $current_option;
        }, $options);
        do_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notice', 'Data fields saved', 'success');
        return $options;
    }
    /**
     * @inheritDoc
     */
    public function get_slug() : string
    {
        return Dotdigital_WordPress_Config::SETTING_DATAFIELDS_PATH;
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
    public function get_title()
    {
        return __('My contact data fields');
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
        $this->sort_order = isset($_GET['order']) ? esc_url_raw(wp_unslash($_GET['order'])) : 'asc';
    }
    /**
     * Sorts the data fields by the supplied order.
     *
     * @param array $data_fields
     * @return mixed|null
     */
    private function sort($data_fields)
    {
        Dotdigital_WordPress_Admin::sort($data_fields, $this->sort_order);
        return apply_filters("{$this->get_slug()}/data-fields/sort", $data_fields);
    }
}
