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

namespace WP_Meteor\Blocker\Exclusions;

/**
 * Provide Import and Export of the settings of the plugin
 */
class Exclude extends \WP_Meteor\Blocker\Base
{
    public $adminPriority = -1;
    public $priority = 99;
    public $tab = 'exclusions';
    public $title = 'Exclude scripts matching regexp from optimization';
    public $description = "Specify URLs or keywords or regular expressions, that can identify inline or src of javascript to be excluded from delaying execution (one per line). This is a good place to put your menus, hero area carousels, GA and GTM";
    public $defaultEnabled = false;

    public function __construct()
    {
        parent::__construct();

        $settings = wpmeteor_get_settings();
        $regexp = null;
        if (
            isset($settings[$this->id])
            && @$settings[$this->id]['enabled']
            && @$settings[$this->id]['value']
        ) {
            $value = array_map(function ($value) {
                return str_replace('#', '\#', $value);
                // return preg_quote(str_replace('#', '\#', $value), '#');
            }, $settings[$this->id]['value']);
            $regexp = "#(" . join('|', array_filter($value, function ($value) {
                return $value && @preg_match("#{$value}#", "") !== false;
            })) . ")#";
            if ($regexp !== '#()#') {
                add_filter('wpmeteor_exclude', function ($exclude, $content) use ($regexp) {
                    if ($exclude) {
                        return $exclude;
                    }
                    if (preg_match($regexp, $content)) {
                        return true;
                    }
                    return $exclude;
                }, null, 2);
            }
        }
    }

    public function backend_display_settings()
    {
        echo '<div id="' . $this->id . '" class="regexp-textarea"
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

        $value = explode("\n", $sanitized[$this->id]['value']);
        $value = array_map('trim', $value);
        $value = array_filter($value);
        $sanitized[$this->id]['value'] = $value;
        
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
