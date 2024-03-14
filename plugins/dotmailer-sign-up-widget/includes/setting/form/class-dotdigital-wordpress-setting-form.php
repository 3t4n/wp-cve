<?php

/**
 * A model for front end forms.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Setting\Form;

use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Input_Interface;
class Dotdigital_WordPress_Setting_Form
{
    /**
     * The name of the form.
     *
     * @var string
     */
    private $identifier = '';
    /**
     * The inputs of the form.
     *
     * @var array
     */
    protected $inputs = array();
    /**
     * The title of the form.
     *
     * @var string
     */
    private $title = '';
    /**
     * The page of the form.
     *
     * @var string
     */
    private $page = '';
    /**
     * The action of the form.
     *
     * @var string
     */
    private $action = '';
    /**
     * The method of the form.
     *
     * @var string
     */
    private $method = '';
    /**
     * @param string $identifier
     * @param string $title
     * @param string $page
     * @param string $action
     * @param string $method
     */
    public function __construct($identifier, $title, $page, $action = 'options.php', $method = 'post')
    {
        $this->identifier = $identifier;
        $this->title = $title;
        $this->page = $page;
        $this->action = $action;
        $this->method = $method;
    }
    /**
     * Add an input to the form.
     *
     * @param Dotdigital_WordPress_Setting_Form_Input_Interface $input
     * @param string|null                                       $key
     */
    public function add_input(Dotdigital_WordPress_Setting_Form_Input_Interface $input, string $key = null)
    {
        if (!empty($input->get_group())) {
            $this->inputs[] = $input;
            return;
        }
        if (!empty($key)) {
            $this->inputs[$key] = $input;
            return;
        }
        $this->inputs[] = $input;
    }
    /**
     * Get the inputs of the form.
     *
     * @return array
     */
    public function get_grouped_inputs()
    {
        $grouped_inputs = array();
        foreach ($this->inputs as $input) {
            if (!\is_a($input, Dotdigital_WordPress_Setting_Form_Input_Interface::class)) {
                continue;
            }
            if (!empty($input->get_group())) {
                $grouped_inputs[$input->get_group()][] = $input;
            }
        }
        return $grouped_inputs;
    }
    /**
     * Get the name of the form.
     *
     * @return string
     */
    public function get_name()
    {
        return $this->identifier;
    }
    /**
     * Get the inputs of the form.
     *
     * @return array
     */
    public function get_inputs()
    {
        return $this->inputs;
    }
    /**
     * Get the title of the form.
     *
     * @return string
     */
    public function get_title()
    {
        return $this->title;
    }
    /**
     * Get the page of the form.
     *
     * @return string
     */
    public function get_page()
    {
        return $this->page;
    }
    /**
     * Get the action of the form.
     *
     * @return string
     */
    public function get_action()
    {
        return $this->action;
    }
    /**
     * Get the method of the form.
     *
     * @return string
     */
    public function get_method()
    {
        return $this->method;
    }
    /**
     * Get the callback of the form.
     *
     * @return void
     */
    public function render()
    {
        $form = $this;
        require_once DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'admin/view/form/dotdigital-wordpress-setting-form.php';
    }
    /**
     * Initialise the form.
     */
    public function initialise()
    {
        register_setting($this->get_page(), $this->get_name(), array($this, 'save'));
        add_settings_section($this->get_name(), $this->get_title(), array($this, 'render'), $this->get_page());
        foreach ($this->get_inputs() as $input) {
            $input->initialise();
        }
    }
    /**
     * Save the form.
     *
     * @param array $options
     * @return array
     */
    public function save($options = array())
    {
        return apply_filters("{$this->get_name()}/save", $options);
    }
}
