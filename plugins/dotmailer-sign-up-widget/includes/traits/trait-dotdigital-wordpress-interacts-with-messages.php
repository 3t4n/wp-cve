<?php

/**
 * The file that defines the Dotdigital_WordPress_Interacts_With_Messages_Trait class
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Traits;

use Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config;
trait Dotdigital_WordPress_Interacts_With_Messages_Trait
{
    /**
     * Get form title.
     *
     * @return string
     */
    public function get_form_title() : string
    {
        return Dotdigital_WordPress_Config::get_option(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_form_title]');
    }
    /**
     * Get invalid email message.
     *
     * @return string
     */
    public static function get_invalid_email_message() : string
    {
        return Dotdigital_WordPress_Config::get_option(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_invalid_email]');
    }
    /**
     * Get fill required message.
     *
     * @return string
     */
    public static function get_fill_required_message() : string
    {
        return Dotdigital_WordPress_Config::get_option(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_fill_required]');
    }
    /**
     * Get success message.
     *
     * @return string
     */
    public static function get_success_message() : string
    {
        return Dotdigital_WordPress_Config::get_option(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_success_message]');
    }
    /**
     * Get failure message.
     *
     * @return string
     */
    public static function get_failure_message() : string
    {
        return Dotdigital_WordPress_Config::get_option(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_failure_message]');
    }
    /**
     * Get no book message.
     *
     * @return string
     */
    public static function get_nobook_message() : string
    {
        return Dotdigital_WordPress_Config::get_option(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_nobook_message]');
    }
    /**
     * Get subscribe button title.
     *
     * @return string
     */
    public function get_subscribe_button_title() : string
    {
        return Dotdigital_WordPress_Config::get_option(Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH . '[dm_API_subs_button]');
    }
}
