<?php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', true);
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://profiles.wordpress.org/tiempoart/
 * @since             1.0.0
 * @package           Tiempo
 *
 * @wordpress-plugin
 * Plugin Name:       Tiempo
 * Plugin URI:        https://www.tiempo3.com/widgets
 * Description:       Beautiful Spanish weather forecasting widget
 * Version:           1.0.0
 * Author:            tiempo
 * Author URI:        https://profiles.wordpress.org/tiempo/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tiempo
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
define('METEO_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-tiempo-activator.php
 */
function activate_tiempo()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-tiempo-activator.php';
    Tiempo_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-tiempo-deactivator.php
 */
function deactivate_tiempo()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-tiempo-deactivator.php';
    Tiempo_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_tiempo');
register_deactivation_hook(__FILE__, 'deactivate_tiempo');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-tiempo.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_tiempo()
{

    $plugin = new Tiempo();
    $plugin->run();

}

run_tiempo();


class tiempo_widget extends WP_Widget
{
    // Set up the widget name and description.
    public function __construct()
    {
        $widget_options = array(
            'classname' => 'tiempo_widget',
            'description' => 'Spanish weather forecast widget. All locations around the world',
            'show_in_rest' => true  // Add this line to support block editor
        );
        parent::__construct('tiempo_widget', 'Tiempo Widget', $widget_options);
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
        $showCurrent = $instance['showCurrent'];

        echo '<div class="tiempo-widget weather_widget_wrap"
                 data-text-color="' . $textColor . '"
                 data-background="' . $backgroundColor . '"
                 data-width="' . $widgetWidth . '"
                 data-days="' . $days . '"
                 data-sunrise="' . $showSunrise . '"
                 data-wind="' . $showWind . '"
                 data-current="' . $showCurrent . '"
                 data-language="' . $language . '"
                 data-city="' . $city . '"
                 data-country="' . $country . '">
    
                <div class="weather_widget_placeholder"></div>
                <div style="font-size: 14px;text-align: center;padding-top: 6px;padding-bottom: 4px;background: rgba(0,0,0,0.03);">
                    Data from <a target="_blank" href="https://www.tiempo3.com">Tiempo3.com</a>
                </div>
            </div>';

        echo $args['after_widget'];
    }


    // Create the admin area widget settings form.
    public function form($instance)
    {
        // print_r($instance);
        $city = !empty($instance['city']) ? $instance['city'] : 'Madrid';
        $country = !empty($instance['country']) ? $instance['country'] : 'Spain';
        $backgroundColor = !empty($instance['backgroundColor']) ? $instance['backgroundColor'] : '#becffb';
        $textColor = !empty($instance['textColor']) ? $instance['textColor'] : '#000000';

        if (isset($instance['widgetWidth'])) {
            $widgetWidth = $instance['widgetWidth'];
        } else {
            $widgetWidth = '100';
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

        $showCurrent = !empty($instance['showCurrent']) ? $instance['showCurrent'] : 'on';

        ?>
        <div class="tiempo_form">
            <div class="form-section">
                <h3>Location</h3>
                <div class="form-line">
                    <label class="text-label" for="<?php echo $this->get_field_id('city'); ?>">City:</label>
                    <input type="text" id="<?php echo $this->get_field_id('city'); ?>"
                           name="<?php echo $this->get_field_name('city'); ?>"
                           value="<?php echo esc_attr($city); ?>"/>
                </div>
                <div class="form-line">
                    <label class="text-label" for="<?php echo $this->get_field_id('country'); ?>">Country:</label>
                    <input type="text" id="<?php echo $this->get_field_id('country'); ?>"
                           name="<?php echo $this->get_field_name('country'); ?>"
                           value="<?php echo esc_attr($country); ?>"/>
                </div>
            </div>

            <div class="form-section">
                <h3>Widget Language</h3>
                <div class="form-line">
                    <select name="<?php echo $this->get_field_name('language'); ?>">
                        <option value="english" <?php if ($language == "english") {
                            echo 'selected';
                        } ?>>English
                        </option>
                        <option value="spanish" <?php if ($language == "spanish") {
                            echo 'selected';
                        } ?>>Spanish
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-section">
                <h3>Weather Data</h3>
                <div class="form-line">
                    <input type="checkbox"
                        <?php if ($showCurrent == 'on') {
                            echo 'checked';
                        }; ?>
                           id="<?php echo $this->get_field_id('showCurrent'); ?>"
                           name="<?php echo $this->get_field_name('showCurrent'); ?>"/>
                    <label for="<?php echo $this->get_field_id('showCurrent'); ?>">Show: Current weather</label>
                </div>
                <div class="form-line">
                    <input type="checkbox"
                        <?php if ($showWind == 'on') {
                            echo 'checked';
                        }; ?>
                           id="<?php echo $this->get_field_id('showWind'); ?>"
                           name="<?php echo $this->get_field_name('showWind'); ?>"/>
                    <label for="<?php echo $this->get_field_id('showWind'); ?>">Show: Chance for rain, Wind and
                        Humidity</label>
                </div>
                <div class="form-line">
                    <input type="checkbox"
                        <?php if ($showSunrise == 'on') {
                            echo 'checked';
                        }; ?>
                           id="<?php echo $this->get_field_id('showSunrise'); ?>"
                           name="<?php echo $this->get_field_name('showSunrise'); ?>"/>
                    <label for="<?php echo $this->get_field_id('showSunrise'); ?>">Show: Sunrise and sunset time</label>
                </div>
            </div>
            <div class="form-section">
                <h3>Daily Forecast</h3>
                <div class="form-line">
                    <select name="<?php echo $this->get_field_name('days'); ?>">
                        <option value="0" <?php if ($days == 0) {
                            echo 'selected';
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
                    <label for="<?php echo $this->get_field_id('backgroundColor'); ?>">Background Color
                        (optional):</label>
                    <input type="color" id="<?php echo $this->get_field_id('backgroundColor'); ?>"
                           name="<?php echo $this->get_field_name('backgroundColor'); ?>"
                           value="<?php echo esc_attr($backgroundColor); ?>"/>
                </div>
                <div class="form-line">
                    <label for="<?php echo $this->get_field_id('textColor'); ?>">Text Color (optional):</label>
                    <input type="color" id="<?php echo $this->get_field_id('textColor'); ?>"
                           name="<?php echo $this->get_field_name('textColor'); ?>"
                           value="<?php echo esc_attr($textColor); ?>"/>
                </div>
                <div>
                    <div class="widget-width-line"><label for="<?php echo $this->get_field_id('widgetWidth'); ?>">Widget
                            Stretch (width):</label>
                    </div>
                    <div class="form-line">
                        <input type="radio" id="<?php echo $this->get_field_id('widgetWidth'); ?>"
                            <?php if ($widgetWidth == '100') {
                                echo 'checked';
                            }; ?>
                               name="<?php echo $this->get_field_name('widgetWidth'); ?>"
                               value="100"/> 100%
                        <input type="radio" id="<?php echo $this->get_field_id('widgetWidth'); ?>"
                            <?php if ($widgetWidth == 'tight') {
                                echo 'checked';
                            }; ?>
                               name="<?php echo $this->get_field_name('widgetWidth'); ?>"
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
        $instance = $old_instance;
        if (!empty($new_instance['city'])) {
            $instance['city'] = sanitize_text_field(strip_tags($new_instance['city']));
        }

        if (!empty($new_instance['country'])) {
            $instance['country'] = sanitize_text_field(strip_tags($new_instance['country']));
        }
        $instance['backgroundColor'] = sanitize_hex_color(strip_tags($new_instance['backgroundColor']));
        $instance['textColor'] = sanitize_hex_color(strip_tags($new_instance['textColor']));
        $instance['widgetWidth'] = sanitize_text_field(strip_tags($new_instance['widgetWidth']));
        $instance['showSunrise'] = sanitize_text_field($new_instance['showSunrise']);
        $instance['showWind'] = sanitize_text_field($new_instance['showWind']);
        $instance['showCurrent'] = sanitize_text_field($new_instance['showCurrent']);
        $instance['days'] = sanitize_text_field(strip_tags($new_instance['days']));
        $instance['language'] = sanitize_text_field(strip_tags($new_instance['language']));
        if ($new_instance['showSunrise'] != "on") {
            $instance['showSunrise'] = "false";
        }
        if ($new_instance['showWind'] != "on") {
            $instance['showWind'] = "false";
        }
        if ($new_instance['showCurrent'] != "on") {
            $instance['showCurrent'] = "false";
        }

        // Add this line at the end of your existing update function:
        // Save a copy of the widget's settings globally for the shortcode to access.
        update_option('tiempo_global_settings', $instance);

        return $instance;
    }
}

// Register the widget.

function jpen_register_tiempo_widget()
{
    register_widget('tiempo_widget');
}

add_action('widgets_init', 'jpen_register_tiempo_widget');


function tiempo_shortcode($atts = [], $content = null, $tag = '')
{
    // Fetch global settings
    $global_settings = get_option('tiempo_global_settings', []);

    // Define default values
    $defaults = [
        'city' => $global_settings['city'] ?? 'Madrid',
        'country' => $global_settings['country'] ?? 'Spain',
        'background_color' => $global_settings['backgroundColor'] ?? '#becffb',
        'text_color' => $global_settings['textColor'] ?? '#000000',
        'widget_width' => $global_settings['widgetWidth'] ?? '100',
        'days' => $global_settings['days'] ?? 3,
        'show_sunrise' => $global_settings['showSunrise'] ?? '',
        'show_wind' => $global_settings['showWind'] ?? '',
        'language' => $global_settings['language'] ?? 'spanish',
        'show_current' => $global_settings['showCurrent'] ?? 'on',
    ];

    // Override default attributes with user attributes
    $tiempo_atts = shortcode_atts($defaults, $atts, $tag);

    ob_start();
    // The widget output code using $tiempo_atts...

    echo '<div class="tiempo-widget weather_widget_wrap" data-text-color="' . esc_attr($tiempo_atts['text_color']) . '" data-background="' . esc_attr($tiempo_atts['background_color']) . '" data-width="' . esc_attr($tiempo_atts['widget_width']) . '" data-days="' . esc_attr($tiempo_atts['days']) . '" data-sunrise="' . esc_attr($tiempo_atts['show_sunrise']) . '" data-wind="' . esc_attr($tiempo_atts['show_wind']) . '" data-current="' . esc_attr($tiempo_atts['show_current']) . '" data-language="' . esc_attr($tiempo_atts['language']) . '" data-city="' . esc_attr($tiempo_atts['city']) . '" data-country="' . esc_attr($tiempo_atts['country']) . '"><div style="font-size: 14px;text-align: center;padding-top: 6px;padding-bottom: 4px;background: rgba(0,0,0,0.03);">Data from <a target="_blank" href="https://www.tiempo3.com">Tiempo.com</a></div></div>';

    return ob_get_clean();
}

add_shortcode('tiempo', 'tiempo_shortcode');


function tiempo_add_admin_menu_page()
{
    add_menu_page(
        __('Tiempo Weather Widget', 'tiempo'), // Page title
        __('Tiempo Weather Widget', 'tiempo'),          // Menu title
        'manage_options',               // Capability required
        'tiempo-settings',               // Menu slug
        'tiempo_admin_settings_page',    // Function to display the settings page
        'dashicons-cloud',              // Icon URL (use a dashicon name)
    );
}

add_action('admin_menu', 'tiempo_add_admin_menu_page');


function tiempo_admin_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <h2>Instructions and Documentation</h2>
        <p>Welcome to Tiempo, the comprehensive weather widget for your WordPress site! Below are instructions to help you set up and use the widget:</p>

        <h3>Adding the Widget to Your Site</h3>
        <p>Tiempo can be added to your site using the classic widget area or via the block editor:</p>
        <ul>
            <li><strong>Classic Widgets:</strong> Navigate to the Widgets section under Appearance in your WordPress dashboard, add the Tiempo widget to your desired sidebar or footer area, and configure it with your preferred settings.</li>
            <li><strong>Block Editor:</strong> While editing a page or a post, add the Tiempo block by searching for "Tiempo Widget" in the block inserter. Configure the block settings directly in the editor.</li>
        </ul>

        <h3>Using the Shortcode</h3>
        <p>The [tiempo] shortcode allows you to embed the weather widget into posts, pages, or even widget areas that support text/html. You can customize it using the following attributes:</p>
        <ul>
            <li><code>city</code>: The city for which you want to display the weather (default: "Madrid").</li>
            <li><code>country</code>: The country that the city is in (default: "Spain").</li>
            <li><code>days</code>: The number of days to show the weather forecast for (default: 3).</li>
            <li><code>show_current</code>: Whether to display current weather conditions ("on" or "off").</li>
            <li><code>show_wind</code>: Whether to show wind information ("on" or "off").</li>
            <li><code>show_sunrise</code>: Whether to display sunrise and sunset times ("on" or "off").</li>
            <li><code>background_color</code>: Background color of the widget (default: "#becffb").</li>
            <li><code>text_color</code>: Color of the text in the widget (default: "#000000").</li>
            <li><code>language</code>: Language of the weather information, such as "english" or "spanish" (default: "spanish").</li>
        </ul>
        <p>Example: [tiempo city="Madrid" country="Spain" days="3" show_current="on"]</p>

        <h3>Fair Use Policy</h3>
        <p>The Tiempo widget is provided as a free service for personal and commercial use. We encourage fair use of our services and reserve the right to limit or block access to any users who abuse the system, such as by making excessive requests or using the service for non-standard purposes. By using this widget, you agree to use it responsibly and within the usage limits established.</p>

        <h3>Help Us Grow</h3>
        <p>If you find the Tiempo widget useful, please consider helping us by leaving a review. Your feedback is not only greatly appreciated, but it also helps us to improve and provide you with the best service possible. Thank you for your support!</p>
        <a href="https://wordpress.org/support/plugin/tiempoart/reviews/#new-post" target="_blank" class="button button-primary">Leave a Review</a>


        <h3>Need More Help?</h3>
        <p>If you have any questions or need further assistance, please contact us.</p>

        <h3>Enjoy the Weather!</h3>
        <p>We hope you enjoy using Tiempo. Don't forget to check the weather before you go out!</p>
    </div>
    <?php
}



function tiempo_register_settings()
{
    // Register a new setting for "tiempo-settings" page.
    register_setting('tiempo-settings', 'tiempo_options');

    // Register a new section in the "tiempo-settings" page.
    add_settings_section(
        'tiempo_section_id',
        __('Tiempo Custom Settings', 'tiempo'),
        'tiempo_section_callback',
        'tiempo-settings'
    );

    // Register a new field in the "tiempo_section_id" section, inside the "tiempo-settings" page.
    add_settings_field(
        'tiempo_field_id',                          // As part of the section
        __('Tiempo Custom Field', 'tiempo'),         // Field title
        'tiempo_field_callback',                    // Callback for field markup
        'tiempo-settings',                          // Page to go on
        'tiempo_section_id'                         // Section to go in
    );
}

add_action('admin_init', 'tiempo_register_settings');

function tiempo_section_callback()
{
    echo '<p>' . __('This section description can be left blank, or used to describe your settings section.', 'tiempo') . '</p>';
}


function tiempo_load_textdomain()
{
    load_plugin_textdomain('tiempo', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

add_action('plugins_loaded', 'tiempo_load_textdomain');

function tiempo_field_callback()
{
    // Retrieve the option value from the database
    $options = get_option('tiempo_options');
    // Render the output for the "tiempo_field" field
    echo '<input type="text" id="tiempo_field" name="tiempo_options[tiempo_field]" value="' . esc_attr($options['tiempo_field'] ?? '') . '"/>';
}


function tiempo_enqueue_block_editor_assets()
{
    wp_enqueue_script(
        'tiempo-block-editor',
        plugins_url('public/js/tiempo-block-editor.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-editor'),
        filemtime(plugin_dir_path(__FILE__) . 'public/js/tiempo-block-editor.js'),
        true
    );

    // If you have localizations to pass to your script
    wp_localize_script('tiempo-block-editor', 'tiempoLocalize', array(
        'some_data' => 'Some value',
        // Add other data here
    ));

    wp_enqueue_style(
        'tiempo-style',
        plugins_url('public/css/tiempo-public.css', __FILE__),
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'public/css/tiempo-public.css')
    );
}

add_action('enqueue_block_editor_assets', 'tiempo_enqueue_block_editor_assets');


function register_tiempo_block()
{
    // Register the block editor script
    wp_register_script(
        'tiempo-block-editor',
        plugins_url('public/js/tiempo-block-editor.js', __FILE__),
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-i18n', 'wp-editor'),
        filemtime(plugin_dir_path(__FILE__) . 'public/js/tiempo-block-editor.js')
    );

    // Enqueue the block editor style
    wp_enqueue_style(
        'tiempo-block-editor-style',
        plugins_url('public/css/tiempo-block-editor.css', __FILE__), // Path to your custom CSS file
        array(),
        filemtime(plugin_dir_path(__FILE__) . 'public/css/tiempo-block-editor.css')
    );

    // Register the block with a render callback for server-side rendering
    register_block_type('tiempo/widget', array(
        'editor_script' => 'tiempo-block-editor',
        'render_callback' => 'render_tiempo_widget', // Specify the render callback function
    ));
}

// The render callback function to generate the block's HTML based on attributes
function render_tiempo_widget($attributes)
{
    // Default values for attributes
    $defaults = [
        'city' => 'Madrid',
        'country' => 'Spain',
        'backgroundColor' => '#becffb',
        'widgetWidth' => '100',
        'textColor' => '#000000',
        'days' => 3,
        'showSunrise' => '',
        'showWind' => '',
        'language' => 'spanish',
        'showCurrent' => 'on',
    ];

    // Merge defaults with actual attributes
    $attributes = shortcode_atts($defaults, $attributes);

    ob_start(); // Start output buffering to capture the HTML output
    ?>

    <div class="tiempo-widget weather_widget_wrap"
         data-text-color="<?php echo esc_attr($attributes['textColor']); ?>"
         data-background="<?php echo esc_attr($attributes['backgroundColor']); ?>"
         data-width="<?php echo esc_attr($attributes['widgetWidth']); ?>"
         data-days="<?php echo esc_attr($attributes['days']); ?>"
         data-sunrise="<?php echo esc_attr($attributes['showSunrise']); ?>"
         data-wind="<?php echo esc_attr($attributes['showWind']); ?>"
         data-current="<?php echo esc_attr($attributes['showCurrent']); ?>"
         data-language="<?php echo esc_attr($attributes['language']); ?>"
         data-city="<?php echo esc_attr($attributes['city']); ?>"
         data-country="<?php echo esc_attr($attributes['country']); ?>">
        <div style="font-size: 14px;text-align: center;padding-top: 6px;padding-bottom: 4px;background: rgba(0,0,0,0.03);">
            Data from <a target="_blank" href="https://www.tiempo3.com">Tiempo3.com</a>
        </div>
    </div>

    <?php
    $output = ob_get_clean(); // End output buffering and get the contents
    return $output; // Return the generated HTML to be rendered by the block
}


add_action('init', 'register_tiempo_block');
