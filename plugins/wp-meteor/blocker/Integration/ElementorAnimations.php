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

namespace WP_Meteor\Blocker\Integration;

/**
 * Provide Import and Export of the settings of the plugin
 */
class ElementorAnimations extends \WP_Meteor\Blocker\Base
{
    public $adminPriority = -1;
    public $priority = 99;
    public $tab = 'elementor';
    public $title = 'Run Elementor Entrance Animations in view';
    public $description = ""; 
    public $defaultEnabled = true;

    public function backend_display_settings()
    {
        echo '<div id="' . $this->id . '" class="simple"
                    data-prefix="' . $this->id . '" 
                    data-title="' . $this->title . '"></div>';
    }

    public function backend_adjust_wpmeteor($wpmeteor, $settings)
    {
        $wpmeteor['blockers'] = @$wpmeteor['blockers'] ?: [];
        $wpmeteor['blockers'][$this->id] = $settings[$this->id];
        return $wpmeteor;
    }

    public function backend_save_settings($sanitized, $settings)
    {
        $exists = isset($sanitized[$this->id]['enabled']);
        $sanitized[$this->id] = array_merge($settings[$this->id], $sanitized[$this->id] ?: []);
        $sanitized[$this->id]['enabled'] = $exists;
        return $sanitized;
    }

    /* triggered from wpmeteor_load_settings */
    public function load_settings($settings)
    {
        $settings[$this->id] = isset($settings[$this->id])
            ? $settings[$this->id]
            : ['enabled' => $this->defaultEnabled];

        $settings[$this->id]['id'] = $this->id;
        $settings[$this->id]['description'] = $this->description;
        return $settings;
    }

    public function frontend_adjust_wpmeteor($wpmeteor, $settings)
    {
        if ($settings[$this->id]['enabled']) {
            $wpmeteor[$this->id] = true;
        }
        return $wpmeteor;
    }

}
