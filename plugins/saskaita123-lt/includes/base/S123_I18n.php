<?php
/**
 * @link https://www.invoice123.com
 * @package Saskaita123Plugin
 */

namespace S123\Includes\Base;

if (!defined('ABSPATH')) exit;

class S123_I18n
{
    public function s123_register()
    {
        add_action('init', array($this, 's123_set_locale'));
    }

    public function s123_set_locale()
    {
        load_plugin_textdomain(
            's123-invoices',
            false,
            dirname(plugin_basename(__FILE__), 3) . '/languages/'
        );
    }
}