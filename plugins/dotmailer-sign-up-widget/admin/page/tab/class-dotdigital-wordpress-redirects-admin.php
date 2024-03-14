<?php

/**
 * Redirections tab
 *
 * This file is used to display the redirections tab
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Admin\Page\Tab;

use Dotdigital_WordPress\Admin\Page\Dotdigital_WordPress_Page_Tab_Interface;
use Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form;
use Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form_Radio_Group;
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Select_Input;
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Text_Input;
use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
class Dotdigital_WordPress_Redirects_Admin implements Dotdigital_WordPress_Page_Tab_Interface
{
    private const URL_SLUG = 'redirections';
    /**
     * @var Dotdigital_WordPress_Setting_Form
     */
    private $form;
    /**
     * @inheritDoc
     */
    public function initialise()
    {
        $this->form = new Dotdigital_WordPress_Setting_Form_Radio_Group($this->get_slug(), __('Redirection settings'), $this->get_slug());
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input("{$this->get_slug()}[noRedirection]", 'No redirection', "{$this->get_slug()}"), 'noRedirection');
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Select_Input("{$this->get_slug()}[page]", 'Local page', "{$this->get_slug()}", \array_reduce(get_pages(), function ($carry, $page) {
            $carry[$page->ID] = $page->post_title;
            return $carry;
        }, array())), 'page');
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input("{$this->get_slug()}[url]", 'Custom URL', "{$this->get_slug()}"), 'url');
        add_filter("{$this->get_slug()}/{$this->get_slug()}[noRedirection]/attributes", function (string $value) {
            return $value . ' hidden';
        });
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
        require_once DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'admin/view/tabs/dotdigital-wordpress-redirects-admin.php';
    }
    /**
     * @param array $options
     *
     * @return array
     */
    public function save(array $options = array())
    {
        do_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notice', 'Redirections saved', 'success');
        return $options;
    }
    /**
     * @inheritDoc
     */
    public function get_slug() : string
    {
        return Dotdigital_WordPress_Config::SETTING_REDIRECTS_PATH;
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
    public function get_title() : string
    {
        return __('Redirections');
    }
}
