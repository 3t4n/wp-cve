<?php

/**
 * Messages tab
 *
 * This file is used to display the messages tab
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Admin\Page\Tab;

use Dotdigital_WordPress\Admin\Page\Dotdigital_WordPress_Page_Tab_Interface;
use Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form;
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Text_Input;
use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
class Dotdigital_WordPress_Messages_Admin implements Dotdigital_WordPress_Page_Tab_Interface
{
    private const URL_SLUG = 'messages';
    /**
     * @var Dotdigital_WordPress_Setting_Form
     */
    private $form;
    /**
     * @inheritDoc
     */
    public function initialise()
    {
        $this->form = new Dotdigital_WordPress_Setting_Form($this->get_slug(), 'Message settings', $this->get_slug());
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_form_title]', 'Form header', $this->get_slug()));
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_invalid_email]', 'Invalid email error message', $this->get_slug()));
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_fill_required]', 'Required field missing error message', $this->get_slug()));
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_success_message]', 'Submission success message', $this->get_slug()));
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_failure_message]', 'Submission failure message', $this->get_slug()));
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_nobook_message]', 'No newsletter selected message', $this->get_slug()));
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_subs_button]', 'Form subscribe button', $this->get_slug()));
        $this->form->initialise();
        add_filter("{$this->get_slug()}/save", array($this, 'save'), 10, 1);
    }
    /**
     * @inheritDoc
     */
    public function render()
    {
        $view = $this;
        $form = $view->form;
        require_once DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'admin/view/tabs/dotdigital-wordpress-messages-admin.php';
    }
    /**
     * @param array $options
     *
     * @return array|mixed
     */
    public function save($options = array())
    {
        do_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notice', 'Messages saved', 'success');
        return $options;
    }
    /**
     * @inheritDoc
     */
    public function get_slug() : string
    {
        return Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH;
    }
    /**
     * @inheritDoc
     */
    public function get_url_slug() : string
    {
        return self::URL_SLUG;
    }
    /**
     * @inheritDoc
     */
    public function get_title()
    {
        return __('Messages');
    }
}
