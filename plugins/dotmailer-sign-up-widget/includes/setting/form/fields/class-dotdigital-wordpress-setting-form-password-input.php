<?php

/**
 * A front end input model.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Setting\Form\Fields;

use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
class Dotdigital_WordPress_Setting_Form_Password_Input implements \Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Input_Interface
{
    /**
     * @var string
     */
    public const TYPE = 'password';
    /**
     * @var string
     */
    private $page;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $group;
    /**
     * @var string
     */
    private $template_path = DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'admin/view/form/fields/dotdigital-wordpress-setting-form-text-input.php';
    /**
     * @var bool
     */
    private $disabled = \false;
    /**
     * Dotdigital_WordPress_Setting_Form_Text_Input constructor.
     *
     * @param string $name
     * @param string $title
     * @param string $page
     * @param string $group
     */
    public function __construct(string $name, string $title, string $page, string $group = '')
    {
        $this->name = $name;
        $this->title = $title;
        $this->page = $page;
        $this->group = $group;
    }
    /**
     * @inheritDoc
     */
    public function initialise()
    {
        add_settings_field($this->get_name(), $this->get_label(), array($this, 'render'), $this->get_page(), $this->get_page());
        do_action("{$this->get_page()}/{$this->get_name()}/initialise", $this);
    }
    /**
     * @inheritDoc
     */
    public function render() : void
    {
        $form_field = $this;
        require $this->get_template_path();
    }
    /**
     * Validate the input.
     *
     * @param string $value
     *
     * @return mixed
     */
    public function validate($value)
    {
        return apply_filters("{$this->get_page()}/{$this->get_name()}/validate", $value);
    }
    /**
     * @inheritDoc
     */
    public function get_page() : string
    {
        return $this->page;
    }
    /**
     * @inheritDoc
     */
    public function get_name() : string
    {
        return $this->name;
    }
    /**
     * @inheritDoc
     */
    public function get_label() : string
    {
        return $this->title;
    }
    /**
     * @inheritDoc
     */
    public function get_type() : string
    {
        return self::TYPE;
    }
    /**
     * @inheritDoc
     */
    public function get_value() : string
    {
        $option = Dotdigital_WordPress_Config::get_option($this->get_name());
        return apply_filters("{$this->get_page()}/{$this->get_name()}/value", $option);
    }
    /**
     * @inheritDoc
     */
    public function get_group() : string
    {
        return $this->group;
    }
    /**
     * @inheritDoc
     */
    private function get_template_path() : string
    {
        return apply_filters("{$this->get_page()}/{$this->get_name()}/template", $this->template_path);
    }
    /**
     * @inheritDoc
     */
    public function is_disabled() : bool
    {
        return $this->disabled;
    }
    /**
     * @inheritDoc
     */
    public function set_is_disabled() : void
    {
        $this->disabled = \true;
    }
}
