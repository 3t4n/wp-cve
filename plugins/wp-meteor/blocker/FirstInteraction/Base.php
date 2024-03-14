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

namespace WP_Meteor\Blocker\FirstInteraction;

use WP_Meteor\Blocker\Base as Blocker_Base;

/**
 * Provide Import and Export of the settings of the plugin
 */
abstract class Base extends Blocker_Base
{
    /* triggered from wpmeteor_load_settings */
    public function load_settings($settings)
    {
        $settings[$this->id] = isset($settings[$this->id])
            ? $settings[$this->id]
            : ['enabled' => $this->defaultEnabled];

        $settings[$this->id]['id'] = $this->id;
        $settings[$this->id]['regexp'] = $this->pattern;
        $settings[$this->id]['after'] = 'FIRSTINTERACTION';
        if ($this->description)
            $settings[$this->id]['description'] = $this->description;
        return $settings;
    }

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

    public function frontend_adjust_wpmeteor($wpmeteor, $settings)
    {
        if ($settings[$this->id]['enabled']) {
            $wpmeteor['blockers'] = @$wpmeteor['blockers'] ?: [];
            $wpmeteor['blockers'][] = [
                'id' => $this->id,
                'regexp' => $this->pattern,
                'after' => 'FIRSTINTERACTION',
            ];
        }
        return $wpmeteor;
    }
}
