<?php

/**
 * WP_Meteor
 *
 * @package   WP_Meteor
 * @author    Aleksandr Guidrevitch <alex@excitingstartup.com>
 * @copyright 2020 wp-meteor.com
 * @license   GPL 2.0+
 * @link      https://wp-meteor.com
 */

namespace WP_Meteor\Blocker;

use Exception;

/**
 * Provide Import and Export of the settings of the plugin
 */
abstract class Base
{
    public $id;
    public $tab;
    public $title;
    public $pattern;
    public $priority = 10;
    public $adminPriority = 10;
    public $defaultEnabled = true;
    public $description;
    public $disabledInUltimateMode = true;

    public function __construct()
    {
        if (!$this->id) {
            $this->id = strtolower(preg_replace('/([a-z])([A-Z])/', '\1-\2', preg_replace('/.*\\\/', '', get_class($this))));
        }

        \add_filter(WPMETEOR_TEXTDOMAIN . '-load-settings', array($this, 'load_settings'), $this->adminPriority ?: $this->priority);
    }

    /**
     * Initialize the class.
     *
     * @return void
     */
    public function initialize()
    {
        if ($this->tab) {
            // error_log(print_r([get_class($this), $this->adminPriority ?: $this->priority, $this->adminPriority, $this->priority, ], true));
            \add_action(WPMETEOR_TEXTDOMAIN . '-backend-display-settings-' . $this->tab, array($this, 'backend_display_settings'), $this->adminPriority ?: $this->priority);
        }

        \add_filter(WPMETEOR_TEXTDOMAIN . '-backend-adjust-wpmeteor', array($this, 'backend_adjust_wpmeteor'), $this->priority, 2);
        \add_filter(WPMETEOR_TEXTDOMAIN . '-backend-save-settings', array($this, 'backend_save_settings'), $this->priority, 2);

        $settings = \wpmeteor_get_settings();
        \add_filter(WPMETEOR_TEXTDOMAIN . '-frontend-adjust-wpmeteor', array($this, 'frontend_adjust_wpmeteor'), $this->priority, 2);
    }

    abstract public function load_settings($settings);
    abstract public function backend_adjust_wpmeteor($wpmeteor, $settings);
    abstract public function backend_display_settings();
    
    public function backend_save_settings($sanitized, $settings)
    {
        $exists = isset($sanitized[$this->id]['enabled']);
        $sanitized[$this->id] = array_merge($settings[$this->id], $sanitized[$this->id] ?: []);
        $sanitized[$this->id]['enabled'] = $exists;
        return $sanitized;
    }

    abstract public function frontend_adjust_wpmeteor($config, $settings);
}
