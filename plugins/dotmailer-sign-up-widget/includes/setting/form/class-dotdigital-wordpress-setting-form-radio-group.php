<?php

/**
 * Model for form radio groups.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Setting\Form;

use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
class Dotdigital_WordPress_Setting_Form_Radio_Group extends \Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form
{
    /**
     * @param string $identifier
     * @param string $title
     * @param string $page
     * @param string $action
     * @param string $method
     */
    public function __construct($identifier, $title, $page, $action = 'options.php', $method = 'post')
    {
        parent::__construct($identifier, $title, $page, $action, $method);
    }
    /**
     * Get the callback of the form.
     *
     * @return void
     */
    public function render()
    {
        $form = $this;
        require_once DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'admin/view/form/dotdigital-wordpress-setting-form-radio-group.php';
    }
    /**
     * @return array|bool
     */
    public function get_value()
    {
        $value = Dotdigital_WordPress_Config::get_option($this->get_name());
        return apply_filters("{$this->get_page()}/{$this->get_name()}/value", $value);
    }
    /**
     * Initialise the form.
     */
    public function initialise()
    {
        register_setting($this->get_page(), $this->get_name(), array($this, 'save'));
        add_settings_section($this->get_name(), $this->get_title(), array($this, 'render'), $this->get_page());
        foreach ($this->get_inputs() as $input) {
            register_setting($input->get_page(), $this->get_name(), array($this, 'save'));
            add_settings_field($this->get_name(), $input->get_label(), array($this, 'render'), $this->get_page(), $input->get_group());
            $input->initialise();
        }
    }
}
