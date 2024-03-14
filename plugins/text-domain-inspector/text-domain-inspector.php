<?php
/*
Plugin Name: Text Domain Inspector
Description: This plugin allows to inspect text domains of translatable strings
Author: Sti3bas
Version: 1.1
Author URI: https://github.com/sti3bas
*/

require_once(__DIR__.'/Helpers.php');
require_once(__DIR__.'/Replace.php');
require_once(ABSPATH . 'wp-includes/pluggable.php');

use TextDomainInspector\Helpers;
use TextDomainInspector\Replace;

class TextDomainInspector
{
    const COOKIE_NAME = 'text-domain-inspector';
    const SHORTCODE = '/\[textdomain=(.+?);\]?/';
    const REPLACEMENT = '<span title="$1" style="border-radius:50%; background:red; color: white; min-width: 10px; max-width: 10px; width: 10px; min-height: 10px; height: 10px; max-height:10px; display: inline-block;"></span>';
    const REPLACEMENT_BRACKETS = '(text-domain: %s)';

    protected $replace;

    public function __construct($showInBrackets = true)
    {
        if ($this->isTurnedOn()) {
            add_filter('gettext', [$this, 'addShortcode'], 10, 3);
            add_action('wp_loaded', [$this, 'bufferStart']);
            add_action('shutdown', [$this, 'bufferEnd']);
        }

        $this->replace = new Replace(static::SHORTCODE, static::REPLACEMENT, static::REPLACEMENT_BRACKETS, $showInBrackets);

        add_action('admin_bar_menu', [$this, 'addAdminBarMenuItem'], 999);
        add_action('admin_post_tdi_turn_on', [$this, 'turnOn']);
        add_action('admin_post_tdi_turn_off', [$this, 'turnOff']);
    }
    
    public function isTurnedOn()
    {
        return isset($_COOKIE[static::COOKIE_NAME]) && current_user_can('administrator');
    }

    public function turnOn()
    {
        setcookie(static::COOKIE_NAME, '1', 0, SITECOOKIEPATH, COOKIE_DOMAIN);

        $this->redirectBack();
    }

    public function turnOff()
    {
        setcookie(static::COOKIE_NAME, '', time() - 3600, SITECOOKIEPATH, COOKIE_DOMAIN);

        $this->redirectBack();
    }

    public function addAdminBarMenuItem($adminBar)
    {
        $args = [
            'id' => 'text-domain-inspector-switcher',
            'title' => $this->buildAdminBarMenuTitle(),
            'href'  => $this->buildAdminMenuBarHref(),
        ];

        $adminBar->add_node($args);
    }
    
    public function addShortcode($translatedText, $text, $domain)
    {
        if(substr( $translatedText, 0, 4 ) === "http") {
            return add_query_arg( 'textdomain', $domain, $translatedText );
        }
        
        return $translatedText.'[textdomain='.$domain.';]';
    }
    
    public function bufferStart()
    {
        ob_start([$this, 'replaceShortcodes']);
    }
    
    public function bufferEnd()
    {
        if (ob_get_contents()) {
            ob_end_flush();
        }
    }

    public function replaceShortcodes($buffer)
    {
        if (Helpers::isJSON($buffer)) {
            return $this->replace->inJson($buffer);
        }

        if (! Helpers::isHTMLDocument($buffer)) {
            if (Helpers::isHTMLFragment($buffer)) {
                return $this->replace->inHTMLFragment($buffer);
            }
            
            return $this->replace->inPlainText($buffer);
        }
        
        return $this->replace->inHTMLDocument($buffer);
    }

    protected function redirectBack()
    {
        wp_safe_redirect($_SERVER['HTTP_REFERER']);
        
        exit();
    }

    protected function buildAdminMenuBarHref()
    {
        if ($this->isTurnedOn()) {
            return admin_url('admin-post.php?action=tdi_turn_off');
        }

        return admin_url('admin-post.php?action=tdi_turn_on');
    }

    protected function buildAdminBarMenuTitle()
    {
        return  $this->isTurnedOn() ? __('Turn off Text Domain Inspector', 'text-domain-inspector') : __('Inspect Text Domains', 'text-domain-inspector');
    }
}

new TextDomainInspector();
