<?php

/**
 * Credentials tab
 *
 * This file is used to display the credentials tab
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Admin\Page\Tab;

use Dotdigital_WordPress_Vendor\Dotdigital\Exception\ResponseValidationException;
use Dotdigital_WordPress\Includes\Exceptions\Dotdigital_WordPress_Password_Validation_Exception;
use Dotdigital_WordPress\Includes\Exceptions\Dotdigital_WordPress_Username_Validation_Exception;
use Dotdigital_WordPress\Includes\Exceptions\Dotdigital_WordPress_Validation_Exception;
use Dotdigital_WordPress\Admin\Page\Dotdigital_WordPress_Page_Tab_Interface;
use Dotdigital_WordPress\Includes\Client\Dotdigital_WordPress_Account_Info;
use Dotdigital_WordPress\Includes\Client\Dotdigital_WordPress_Client;
use Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form;
use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Text_Input;
use Dotdigital_WordPress\Includes\Setting\Form\Fields\Dotdigital_WordPress_Setting_Form_Password_Input;
class Dotdigital_WordPress_Credentials_Admin implements Dotdigital_WordPress_Page_Tab_Interface
{
    private const URL_SLUG = 'api_credentials';
    /**
     * @var Dotdigital_WordPress_Account_Info
     */
    private $account_info;
    /**
     * @var Dotdigital_WordPress_Setting_Form
     */
    private $form;
    /**
     * @var bool
     */
    private $has_sanitized;
    /**
     * @var Dotdigital_WordPress_Client
     */
    private $dotdigital_client;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->account_info = new Dotdigital_WordPress_Account_Info();
        $this->dotdigital_client = new Dotdigital_WordPress_Client();
    }
    /**
     * @inheritDoc
     */
    public function initialise()
    {
        $this->form = new Dotdigital_WordPress_Setting_Form($this->get_slug(), 'API credentials', $this->get_slug());
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Text_Input(\sprintf('%s[%s]', Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH, Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_USERNAME), __('Username'), "{$this->get_slug()}"));
        $this->form->add_input(new Dotdigital_WordPress_Setting_Form_Password_Input(\sprintf('%s[%s]', Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH, Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_PASSWORD), __('Password'), "{$this->get_slug()}"));
        $this->form->initialise();
        add_filter("{$this->get_slug()}/save", array($this, 'save'), 10, 1);
    }
    /**
     * @inheritDoc
     */
    public function render()
    {
        try {
            $account_info = \array_reduce($this->dotdigital_client->get_client()->accountInfo->show()->getProperties(), function ($account_info, $item) {
                $account_info[$item['name']] = $item['value'];
                return $account_info;
            }, array());
        } catch (ResponseValidationException $e) {
            $account_info = array();
        }
        $view = $this;
        $form = $view->form;
        require_once DOTDIGITAL_WORDPRESS_PLUGIN_PATH . 'admin/view/tabs/dotdigital-wordpress-credentials-admin.php';
    }
    /**
     * @inheritDoc
     */
    public function get_slug() : string
    {
        return Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH;
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
        return __('API credentials');
    }
    /**
     * @return Dotdigital_WordPress_Setting_Form
     */
    public function form() : Dotdigital_WordPress_Setting_Form
    {
        return $this->form;
    }
    /**
     * @param array $credentials
     *
     * @return array|false|mixed|null
     */
    public function save($credentials = array())
    {
        if ($this->has_sanitized) {
            return $credentials;
        }
        $old_credentials = get_option(Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH);
        $username_diff = $this->diff_option($old_credentials, $credentials, Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_USERNAME);
        $password_diff = $this->diff_option($old_credentials, $credentials, Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_PASSWORD);
        if (!$username_diff && !$password_diff) {
            return $old_credentials;
        }
        $credentials = array(Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_USERNAME => \trim($credentials[Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_USERNAME]), Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_PASSWORD => \trim($credentials[Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_PASSWORD]));
        try {
            $this->account_info->validate_credentials($credentials);
        } catch (ResponseValidationException $e) {
            do_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notice', $e->getMessage(), 'error');
            $credentials = array();
        } catch (Dotdigital_WordPress_Password_Validation_Exception $e) {
            do_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notice', $e->getMessage(), 'error');
            $credentials[Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_PASSWORD] = '';
        } catch (Dotdigital_WordPress_Username_Validation_Exception $e) {
            do_action(DOTDIGITAL_WORDPRESS_PLUGIN_NAME . '_settings_notice', $e->getMessage(), 'error');
            $credentials[Dotdigital_WordPress_Config::SETTING_CREDENTIALS_PATH_USERNAME] = '';
        } catch (Dotdigital_WordPress_Validation_Exception $e) {
            $credentials = array();
        } finally {
            $this->has_sanitized = \true;
        }
        return $credentials;
    }
    /**
     * Checks if credentials are different.
     *
     * @param   array|bool $old_value The old value.
     * @param   array|bool $value    The new value.
     * @param   string     $key     The key.
     * @return  bool
     */
    private function diff_option($old_value, $value, string $key) : bool
    {
        if (!\is_array($old_value) || !\is_array($value)) {
            return \true;
        }
        if (!isset($old_value[$key])) {
            return \true;
        }
        if (!isset($value[$key])) {
            return \false;
        }
        if ($old_value[$key] !== $value[$key]) {
            return \true;
        }
        return \false;
    }
}
