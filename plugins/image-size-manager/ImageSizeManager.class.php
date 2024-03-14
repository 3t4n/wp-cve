<?php
class ImageSizeManager
{
    /**
     * Initializes actions and filters
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'create_plugin_settings_page']);
        add_action('admin_init', [$this, 'setup_sections']);
        add_action('admin_init', [$this, 'setup_fields']);
        add_action('admin_init', [$this, 'ism_scripts']);
    }

    /**
     * Sets up the admin page
     */
    public function create_plugin_settings_page()
    {
        $page_title = 'Image Size Manager Settings Page';
        $menu_title = 'Image Size Manager';
        $capability = 'manage_options';
        $slug = 'ism_settings';
        $callback = [$this, 'plugin_settings_page_content'];
        $icon = 'dashicons-admin-plugins';
        $position = 300;

        add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
    }

    /**
     * Basic page setup
     */
    public function plugin_settings_page_content()
    {?>
    <div class="wrap">
    	<h2>Image Size Manager Settings</h2>
        <?php
            if (isset($_GET['settings-updated']) && $_GET['settings-updated']) {
            self::admin_notice();
        }?>
    	<form method="POST" action="options.php">
        <?php
        settings_fields('ism_fields');
        do_settings_sections('ism_fields');
        submit_button();
        ?>
    	</form>
        <?php
            echo '<h2>Your Current Image Settings</h2>';
            echo '<div class="currentsizes">'. self::getAvailableSizes() .'</div>';
            echo '<div style="display:block;text-align:left;padding-top:1em;"><span><em>Image Size Manager</em> is a free plugin by <a href="https://plugins.codecide.net" target="blank">codecide.net</a></span></div>';
        ?>
        </div> 
        <?php
    }

    /**
     * Notification setup
     */
    public function admin_notice()
    {?>
        <div class="notice notice-success is-dismissible">
            <p>Your settings have been updated!</p>
        </div><?php
    }

    /**
     * Add page sections
     */
    public function setup_sections()
    {
        add_settings_section('main_section', 'General Settings', [$this, 'section_callback'], 'ism_fields');
    }

    /**
     * Page section router
     * @param array $arguments
     */
    public function section_callback($arguments)
    {
        switch ($arguments['id']) {
            case 'main_section':
                echo '<ul style="font-size: 1.2em;"><li>Configure the image scaling behavior below.</li><li>This plugin disables scaling if no option is chosen.</li><li>Note that these settings have no effect on PNG images; since 5.3.1, the latter are not subject to automatic scaling.</li><li>Refer to <a href="https://plugins.codecide.net/plugin/ism" target="_blank">the official documentation page</a> for details.</li></ul>';
                break;
        }
    }

    /**
     * Setup the fields
     */
    public function setup_fields()
    {
        $fields = [
            [
                'uid' => 'ism_scaleEnabled',
                'label' => 'Image scaling behavior',
                'section' => 'main_section',
                'type' => 'radio',
                'options' => [
                    'disable' => 'Disable Scaling',
                    'custom' => 'Use Custom Value',
                    'noaction' => 'Use WordPress Default Value (2560px)',
                ],
                'supplemental' => '[required] Select the preferred default scaling behavior for uploaded images.',
                'default' => ['disable'],
            ],
            [
                'uid' => 'ism_customSize',
                'label' => 'Max Image Size',
                'section' => 'main_section',
                'type' => 'number',
                'supplemental' => 'The maximum size of the uploaded images.',
                'placeholder' => 'Unlimited',
                'default' => self::customSize(),
            ],
        ];
        foreach ($fields as $field) {
            add_settings_field($field['uid'], $field['label'], [$this, 'field_callback'], 'ism_fields', $field['section'], $field);
            register_setting('ism_fields', $field['uid']);
        }
    }

    /**
     * Field templates
     * @param array $arguments An array of properties to build fields from
     */
    public function field_callback($arguments)
    {
        $value = get_option($arguments['uid']);
        if (!$value) {
            $value = $arguments['default'];
        }
        switch ($arguments['type']) {
            case 'text':
            case 'password':
            case 'number':
                printf('<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], @$arguments['placeholder'], $value);
                break;
            case 'textarea':
                printf('<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value);
                break;
            case 'select':
            case 'multiselect':
                if (!empty($arguments['options']) && is_array($arguments['options'])) {
                    $attributes = '';
                    $options_markup = '';
                    foreach ($arguments['options'] as $key => $label) {
                        $options_markup .= sprintf('<option value="%s" %s>%s</option>', $key, selected($value[array_search($key, $value, true)], $key, false), $label);
                    }
                    if ($arguments['type'] === 'multiselect') {
                        $attributes = ' multiple="multiple" ';
                    }
                    printf('<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup);
                }
                break;
            case 'radio':
            case 'checkbox':
                if (!empty($arguments['options']) && is_array($arguments['options'])) {
                    $options_markup = '';
                    $iterator = 0;
                    foreach ($arguments['options'] as $key => $label) {
                        $iterator++;
                        $options_markup .= sprintf('<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked($value[array_search($key, $value, true)], $key, false), $label, $iterator);
                    }
                    printf('<fieldset>%s</fieldset>', $options_markup);
                }
                break;
        }
        if ($helper = @$arguments['helper']) {
            printf('<span class="helper"> %s</span>', $helper);
        }
        if ($supplemental = $arguments['supplemental']) {
            printf('<p class="description">%s</p>', $supplemental);
        }
    }

    /**
     * Getters
     */
    public function customSize()
    {
        return get_option('ism_customSize');
    }

    public function scaleEnabled()
    {
        return get_option('ism_scaleEnabled');
    }
    /**
     *
     * Script injector
     */
    public function ism_scripts()
    {
        // wp_register_script('ImageSizeManager-admin', plugins_url('js/ImageSizeManager.admin.js', __FILE__), filemtime(plugin_dir_path(__FILE__) . 'js/ImageSizeManager.admin.js'), true);
        wp_register_script( 'ImageSizeManager-admin',  plugins_url('js/ImageSizeManager.admin.js', __FILE__ ),"1.0", true);
        wp_enqueue_style('ImageSizeManager-admin', plugins_url('css/ImageSizeManager.admin.css', __FILE__));
        wp_enqueue_script('ImageSizeManager-admin');
    }

    protected function getAvailableSizes()
    {
        $head = '<table><thead><tr><th>&nbsp;</th><th colspan="2">Maximum</th></tr><tr><th>Name</th><th>Width</th><th>Height</th></tr></thead>';
        $body = '<tbody>';
        foreach (self::get_image_sizes() as $name => $size) {
            $body .= "<tr><td>{$name}</td><td>{$size['width']}</td><td>{$size['height']}</td></tr>";
        }
        $body .= '</tbody></table>';
        return "<div>$head.$body</div>";
    }

    public function get_image_sizes()
    {
        global $_wp_additional_image_sizes;

        $sizes = array();

        foreach (get_intermediate_image_sizes() as $_size) {
            if (in_array($_size, array('thumbnail', 'medium', 'medium_large', 'large'))) {
                $sizes[$_size]['width'] = get_option("{$_size}_size_w");
                $sizes[$_size]['height'] = get_option("{$_size}_size_h");
                $sizes[$_size]['crop'] = (bool) get_option("{$_size}_crop");
            } elseif (isset($_wp_additional_image_sizes[$_size])) {
                $sizes[$_size] = array(
                    'width' => $_wp_additional_image_sizes[$_size]['width'],
                    'height' => $_wp_additional_image_sizes[$_size]['height'],
                    'crop' => $_wp_additional_image_sizes[$_size]['crop'],
                );
            }
        }

        return $sizes;
    }

    /**
     * Get size information for a specific image size.
     *
     * @uses   get_image_sizes()
     * @param  string $size The image size for which to retrieve data.
     * @return bool|array $size Size data about an image size or false if the size doesn't exist.
     */
    public function get_image_size($size)
    {
        $sizes = self::get_image_sizes();

        if (isset($sizes[$size])) {
            return $sizes[$size];
        }

        return false;
    }

    /**
     * Get the width of a specific image size.
     *
     * @uses   get_image_size()
     * @param  string $size The image size for which to retrieve data.
     * @return bool|string $size Width of an image size or false if the size doesn't exist.
     */
    public function get_image_width($size)
    {
        if (!$size = self::get_image_size($size)) {
            return false;
        }

        if (isset($size['width'])) {
            return $size['width'];
        }

        return false;
    }

    /**
     * Get the height of a specific image size.
     *
     * @uses   get_image_size()
     * @param  string $size The image size for which to retrieve data.
     * @return bool|string $size Height of an image size or false if the size doesn't exist.
     */
    public function get_image_height($size)
    {
        if (!$size = self::get_image_size($size)) {
            return false;
        }

        if (isset($size['height'])) {
            return $size['height'];
        }

        return false;
    }
}
