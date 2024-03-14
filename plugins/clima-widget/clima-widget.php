<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/danielogo/
 * @since             1.0.0
 * @package           Clima_Widget
 *
 * @wordpress-plugin
 * Plugin Name:       Clima Widget
 * Plugin URI:        https://www.tiempo3.com/widgets
 * Description:       Beautiful, free and SEO friendly weather forecast widget (Spanish and English).
 * Version:           1.0.0
 * Author:            danielogo
 * Author URI:        https://profiles.wordpress.org/danielogo/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       clima-widget
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('CLIMA_WIDGET_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-clima-widget-activator.php
 */
function activate_clima_widget()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-clima-widget-activator.php';
    Clima_Widget_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-clima-widget-deactivator.php
 */
function deactivate_clima_widget()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-clima-widget-deactivator.php';
    Clima_Widget_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_clima_widget');
register_deactivation_hook(__FILE__, 'deactivate_clima_widget');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-clima-widget.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_clima_widget()
{

    $plugin = new Clima_Widget();
    $plugin->run();

}

run_clima_widget();


class clima_widget_widget extends WP_Widget
{
    // Set up the widget name and description.
    public function __construct()
    {
        $widget_options = array('classname' => 'clima_widget_widget', 'description' => 'The best looking weather widget for your wordpress website.');
        parent::__construct('clima_widget_widget', 'Clima', $widget_options);
    }


    // Create the widget output.
    public function widget($args, $instance)
    {
        // Keep this line
         echo $args['before_widget'];

        $city = $instance['city'];
        $country = $instance['country'];
        $backgroundColor = $instance['backgroundColor'];
        $widgetWidth = $instance['widgetWidth'];
        $textColor = $instance['textColor'];
        $days = $instance['days'];
        $showSunrise = $instance['showSunrise'];
        $showWind = $instance['showWind'];
        $language = $instance['language'];
//        $showCurrent = $instance['showCurrent'];
        ?>

        <div id=""
             class="weather_widget_wrap clima-widget weather_widget_style2"
             data-text-color='<?php echo esc_attr($textColor) ?>'
             data-background="<?php echo esc_attr($backgroundColor) ?>"
             data-width="<?php echo esc_attr($widgetWidth) ?>"
             data-days="<?php echo esc_attr($days) ?>"
             data-sunrise="<?php echo esc_attr($showSunrise) ?>"
             data-wind="<?php echo esc_attr($showWind) ?>"
             data-language="<?php echo esc_attr($language) ?>"
             data-city="<?php echo esc_attr($city) ?>"
             data-country="<?php echo esc_attr($country) ?>">
        </div>
        <?php echo $args['after_widget'];
    }


    // Create the admin area widget settings form.
    public function form($instance)
    {
        $city = !empty($instance['city']) ? $instance['city'] : 'Madrid';
        $country = !empty($instance['country']) ? $instance['country'] : 'Spain';
        $backgroundColor = !empty($instance['backgroundColor']) ? $instance['backgroundColor'] : '#16a2d0';
        $textColor = !empty($instance['textColor']) ? $instance['textColor'] : '#ffffff';


        if (isset($instance['widgetWidth'])) {
            $widgetWidth = $instance['widgetWidth'];
        } else {
            $widgetWidth = 'tight';
        }

        if (isset($instance['days'])) {
            $days = $instance['days'];
        } else {
            $days = 3;
        }

        if (isset($instance['language'])) {
            $language = $instance['language'];
        } else {
            $language = "spanish";
        }


        if (isset($instance['showSunrise'])) {
            $showSunrise = $instance['showSunrise'];
        } else {
            $showSunrise = "";
        }

        if (isset($instance['showWind'])) {
            $showWind = $instance['showWind'];
        } else {
            $showWind = "";
        }

//        $showCurrent = !empty($instance['showCurrent']) ? $instance['showCurrent'] : 'on';

        ?>
        <div class="weather-widget-form">
            <div class="form-section">
                <h3>Location</h3>
                <div class="form-line">
                    <label class="text-label" for="<?php echo esc_attr($this->get_field_id('city')) ?>">City:</label>
                    <input class="city_name" data-widget-id="<?php echo esc_attr($this->id) ?>" type="text"
                           id="<?php echo esc_attr($this->get_field_id('city')); ?>"
                           name="<?php echo esc_attr($this->get_field_name('city')); ?>"
                           value="<?php echo esc_attr($city); ?>"/>
                </div>
                <div class="form-line">
                    <label class="text-label"
                           for="<?php echo esc_attr($this->get_field_id('country')); ?>">Country:</label>
                    <input type="text" id="<?php echo esc_attr($this->get_field_id('country')); ?>"
                           name="<?php echo esc_attr($this->get_field_name('country')); ?>"
                           value="<?php echo esc_attr($country); ?>"/>
                </div>
            </div>

            <div class="form-section">
                <h3>Widget Language</h3>
                <div class="form-line">
                    <select class="language-selection" data-widget-id="<?php echo esc_attr($this->id) ?>"
                            name="<?php echo esc_attr($this->get_field_name('language')) ?>">
                        <option value="english" <?php if ($language == "english") {
                            echo esc_attr('selected');
                        } ?>>English
                        </option>
                        <option value="spanish" <?php if ($language == "spanish") {
                            echo esc_attr('selected');
                        } ?>>Spanish
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h3>Weather Data</h3>
                <div class="form-line">
                    <input type="checkbox"
                        <?php if ($showWind == 'on') {
                            echo 'checked';
                        }; ?>
                           id="<?php echo esc_attr($this->get_field_id('showWind')) ?>"
                           name="<?php echo esc_attr($this->get_field_name('showWind')); ?>"/>
                    <label for="<?php echo esc_attr($this->get_field_id('showWind')) ?>">Show: Chance for rain, Wind and
                        Humidity</label>
                </div>
                <div class="form-line">
                    <input type="checkbox"
                        <?php if ($showSunrise == 'on') {
                            echo 'checked';
                        }; ?>
                           id="<?php echo esc_attr($this->get_field_id('showSunrise')); ?>"
                           name="<?php echo esc_attr($this->get_field_name('showSunrise')); ?>"/>
                    <label for="<?php echo esc_attr($this->get_field_id('showSunrise')); ?>">Show: Sunrise and sunset
                        time</label>
                </div>
            </div>
            <div class="form-section">
                <h3>Daily Forecast</h3>
                <div class="form-line">
                    <select name="<?php echo esc_attr($this->get_field_name('days')) ?>">
                        <option value="0" <?php if ($days == 0) {
                            echo esc_attr('selected');
                        } ?>>No Daily Forecast
                        </option>
                        <option value="2" <?php if ($days == 2) {
                            echo 'selected';
                        } ?>>2 Days
                        <option value="3" <?php if ($days == 3) {
                            echo 'selected';
                        } ?>>3 Days
                        </option>
                        <option value="4" <?php if ($days == 4) {
                            echo 'selected';
                        } ?>>4 Days
                        </option>
                        <option value="5" <?php if ($days == 5) {
                            echo 'selected';
                        } ?>>5 Days
                        </option>
                        <option value="6" <?php if ($days == 6) {
                            echo 'selected';
                        } ?>>6 Days
                        </option>
                    </select>
                </div>
            </div>


            <div class="form-section">
                <h3>Look & Feel</h3>

                <div class="form-line">
                    <label for="<?php echo esc_attr($this->get_field_id('backgroundColor')); ?>">Background Color
                        (optional):</label>
                    <input type="color" id="<?php echo esc_attr($this->get_field_id('backgroundColor')); ?>"
                           name="<?php echo esc_attr($this->get_field_name('backgroundColor')); ?>"
                           value="<?php echo esc_attr($backgroundColor); ?>"/>
                </div>
                <div class="form-line">
                    <label for="<?php echo esc_attr($this->get_field_id('textColor')); ?>">Text Color
                        (optional):</label>
                    <input type="color" id="<?php echo esc_attr($this->get_field_id('textColor')); ?>"
                           name="<?php echo esc_attr($this->get_field_name('textColor')); ?>"
                           value="<?php echo esc_attr($textColor); ?>"/>
                </div>

                <div class="form-line">
                    <div class="line-label"><label for="<?php echo esc_attr($this->get_field_id('widgetWidth')); ?>">Widget
                            Stretch (width):</label></div>
                    <div>
                        <input type="radio" id="<?php echo esc_attr($this->get_field_id('widgetWidth')); ?>"
                            <?php if ($widgetWidth == '100') {
                                echo 'checked';
                            }; ?>
                               name="<?php echo esc_attr($this->get_field_name('widgetWidth')); ?>"
                               value="100"/> 100%
                        <input type="radio" id="<?php echo esc_attr($this->get_field_id('widgetWidth')); ?>"
                            <?php if ($widgetWidth == 'tight') {
                                echo 'checked';
                            }; ?>
                               name="<?php echo esc_attr($this->get_field_name('widgetWidth')); ?>"
                               value="tight"/> Tight as possible
                    </div>
                </div>
            </div>
        </div>
        <?php
    }


    // Apply settings to the widget instance.
    public function update($new_instance, $old_instance)
    {
        // print_r($old_instance);
        $instance = $old_instance;
        $instance['city'] = strip_tags($new_instance['city']);
        $instance['country'] = strip_tags($new_instance['country']);
        $instance['backgroundColor'] = strip_tags($new_instance['backgroundColor']);
        $instance['textColor'] = strip_tags($new_instance['textColor']);
        $instance['widgetWidth'] = strip_tags($new_instance['widgetWidth']);
        $instance['widgetHeader'] = strip_tags($new_instance['widgetHeader']);
        $instance['showSunrise'] = $new_instance['showSunrise'];
        $instance['showWind'] = $new_instance['showWind'];
//        $instance['showCurrent'] = $new_instance['showCurrent'];
        $instance['days'] = strip_tags($new_instance['days']);
        $instance['language'] = strip_tags($new_instance['language']);
        if ($new_instance['showSunrise'] != "on") {
            $instance['showSunrise'] = "false";
        }
        if ($new_instance['showWind'] != "on") {
            $instance['showWind'] = "false";
        }
//        if ($new_instance['showCurrent'] != "on") {
//            $instance['showCurrent'] = "false";
//        }

        return $instance;
    }

}

// Register the widget.
function jpen_register_clima_widget_widget()
{
    register_widget('clima_widget_widget');
}

add_action('widgets_init', 'jpen_register_clima_widget_widget');
