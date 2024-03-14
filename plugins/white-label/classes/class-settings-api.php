<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 * white_label_Settings_Api Settings API wrapper class.
 *
 * @author White Label
 * @version 1.0.0
 */
class white_label_Settings_Api
{ // phpcs:ignore
    /**
     * settings sections array.
     *
     * @var array
     */
    protected $settings_sections = [];
    /**
     * Settings fields array.
     *
     * @var array
     */
    protected $settings_fields = [];

    /**
     * Plugin config as array.
     *
     * @var array
     */
    protected $plugin;

    /**
     * Store args temp.
     *
     * @var array
     */
    protected $nested_multicheck_args;

    /**
     * Construct.
     */
    public function __construct($plugin)
    {
        if (!is_admin()) {
            return;
        }
        $this->plugin = $plugin;

        remove_editor_styles();
    }

    /**
     * Set settings sections.
     *
     * @param array $sections setting sections array.
     */
    public function set_sections($sections)
    {
        $this->settings_sections = $sections;
        return $this;
    }

    /**
     * Set settings sidebar.
     *
     * @param array $sections setting sections array.
     */
    public function set_sidebar($sidebar)
    {
        $this->settings_sidebar = $sidebar;
        return $this;
    }

    /**
     * Add a single section.
     *
     * @param array $section.
     */
    public function add_section($section)
    {
        $this->settings_sections[] = $section;
        return $this;
    }

    /**
     * Set settings fields.
     *
     * @param array $fields settings fields array.
     */
    public function set_fields($fields)
    {
        $this->settings_fields = $fields;
        return $this;
    }

    public function add_field($section, $field)
    {
        $defaults = [
            'name' => '',
            'label' => '',
            'desc' => '',
            'type' => 'text',
        ];

        $arg = wp_parse_args($field, $defaults);

        $this->settings_fields[$section][] = $arg;

        return $this;
    }

    /**
     * Initialize and registers the settings sections and fileds to WordPress.
     *
     * Usually this should be called at `admin_init` hook.
     *
     * This function gets the initiated settings sections and fields. Then
     * registers them to WordPress and ready for use.
     */
    public function admin_init()
    {
        // register settings sections.
        foreach ($this->settings_sections as $section) {
            if (false == get_option($section['id'])) {
                add_option($section['id']);
            }
            if (isset($section['desc']) && !empty($section['desc'])) {
                $section['desc'] = '<div class="inside white-label-tab-description">'.$section['desc'].'</div>';
                $callback = function () use ($section) {
                    echo $section['desc'];
                };
            } elseif (isset($section['callback'])) {
                $callback = $section['callback'];
            } else {
                $callback = null;
            }
            add_settings_section($section['id'], $section['title'], $callback, $section['id']);
        }
        // register settings fields.
        foreach ($this->settings_fields as $section => $field) {
            foreach ($field as $option) {
                $name = $option['name'];
                $type = isset($option['type']) ? $option['type'] : 'text';
                $label = isset($option['label']) ? $option['label'] : '';
                $callback = isset($option['callback']) ? $option['callback'] : [$this, 'callback_'.$type];
                $args = [
                    'id' => $name,
                    'class' => isset($option['class']) ? $option['class'] : $name,
                    'label_for' => "{$section}[{$name}]",
                    'desc' => isset($option['desc']) ? $option['desc'] : '',
                    'name' => $label,
                    'section' => $section,
                    'size' => isset($option['size']) ? $option['size'] : null,
                    'options' => isset($option['options']) ? $option['options'] : '',
                    'std' => isset($option['default']) ? $option['default'] : '',
                    'sanitize_callback' => isset($option['sanitize_callback']) ? $option['sanitize_callback'] : '',
                    'type' => $type,
                    'placeholder' => isset($option['placeholder']) ? $option['placeholder'] : '',
                    'min' => isset($option['min']) ? $option['min'] : '',
                    'max' => isset($option['max']) ? $option['max'] : '',
                    'step' => isset($option['step']) ? $option['step'] : '',
                ];
                add_settings_field("{$section}[{$name}]", $label, $callback, $section, $section, $args);
            }
        }
        // creates our settings in the options table.
        foreach ($this->settings_sections as $section) {
            register_setting($section['id'], $section['id'], [$this, 'sanitize_options']);
        }
    }

    /**
     * Get field description for display.
     *
     * @param array $args settings field args.
     */
    public function get_field_description($args)
    {
        if (!empty($args['desc'])) {
            $desc = sprintf('<p class="description">%s</p>', $args['desc']);
        } else {
            $desc = '';
        }

        return $desc;
    }

    /**
     * Displays a  2 colspan subheading field for a settings field.
     *
     *   @param array $args settings field args.
     */
    public function callback_subheading($args)
    {
        $html = '<h3 class="white-label-subheading">'.$args['name'].'</h3>';
        $html .= $this->get_field_description($args);
        $html .= '<hr />';
        echo $html;
    }

    /**
     * Displays a text field for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_text($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $type = isset($args['type']) ? $args['type'] : 'text';
        $placeholder = empty($args['placeholder']) ? '' : ' placeholder="'.$args['placeholder'].'"';
        $html = sprintf('<input type="%1$s" class="%2$s-text" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder);
        $html .= $this->get_field_description($args);
        echo $html;
    }

    /**
     * Displays a url field for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_url($args)
    {
        $this->callback_text($args);
    }

    /**
     * Displays a number field for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_number($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $type = isset($args['type']) ? $args['type'] : 'number';
        $placeholder = empty($args['placeholder']) ? '' : ' placeholder="'.$args['placeholder'].'"';
        $min = ($args['min'] == '') ? '' : ' min="'.$args['min'].'"';
        $max = ($args['max'] == '') ? '' : ' max="'.$args['max'].'"';
        $step = ($args['step'] == '') ? '' : ' step="'.$args['step'].'"';
        $html = sprintf('<input type="%1$s" class="%2$s-number" id="%3$s[%4$s]" name="%3$s[%4$s]" value="%5$s"%6$s%7$s%8$s%9$s/>', $type, $size, $args['section'], $args['id'], $value, $placeholder, $min, $max, $step);
        $html .= $this->get_field_description($args);
        echo $html;
    }

    /**
     * Displays a checkbox for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_checkbox($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $html = '<fieldset>';
        $html .= sprintf('<label for="white-label-%1$s[%2$s]">', $args['section'], $args['id']);
        $html .= sprintf('<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id']);
        $html .= sprintf('<input type="checkbox" class="checkbox" id="white-label-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %3$s />', $args['section'], $args['id'], checked($value, 'on', false));
        $html .= sprintf('%1$s</label>', $args['desc']);
        $html .= '</fieldset>';
        echo $html;
    }

    /**
     * Displays a multicheckbox for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_multicheck($args)
    {
        $value = $this->get_option($args['id'], $args['section'], $args['std']);
        $html = '<fieldset>';
        $html .= sprintf('<input type="hidden" name="%1$s[%2$s]" value="" />', $args['section'], $args['id']);
        foreach ($args['options'] as $key => $label) {
            $checked = isset($value[$key]) ? $value[$key] : '0';
            $html .= sprintf('<label for="white-label-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key);
            $html .= sprintf('<input type="checkbox" class="checkbox" id="white-label-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked($checked, $key, false));
            $html .= sprintf('%1$s</label><br>', $label);
        }
        $html .= $this->get_field_description($args);
        $html .= '</fieldset>';
        echo $html;
    }

    /**
     * Displays a table for dashboard widget settings.
     *
     * @param array $args settings field args.
     */
    public function callback_dashboard_widgets($args)
    {
        $value = $this->get_option($args['id'], $args['section'], $args['std']);

        $html = '';

        $html .= sprintf('<input type="hidden" name="%1$s[%2$s]" value="" />', $args['section'], $args['id']);

        $html .= '<table class="form-table widefat wl-table" style="width:100%;">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<td class="wl-center" style="width:8%;">'.__('Remove', 'white-label').'</td>';
        $html .= '<td style="width:auto">'.__('Dashboard Widget', 'white-label').'</td>';
        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody>';
        foreach ($args['options'] as $key => $label) {
            $checked = isset($value[$key]) ? $value[$key] : '';

            $html .= '<tr>';
            $html .= '<td class="wl-center">'.sprintf('<input type="checkbox" class="checkbox" id="white-label-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" %4$s />', $args['section'], $args['id'], $key, checked($checked, 'on', false)).'</td>';
            $html .= '<td>'.sprintf('<label for="white-label-%1$s[%2$s][%3$s]">%4$s</label><br>', $args['section'], $args['id'], $key, $label).'</td>';
        }
        $html .= '</tbody>';

        $html .= '</table>';
        $html .= $this->get_field_description($args);

        echo $html;
    }

    /**
     * Displays a nested multicheckbox for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_nested_multicheck($args)
    {
        $value = $this->get_option($args['id'], $args['section'], $args['std']);
        $html = '<fieldset>';
        $html .= sprintf('<input type="hidden" name="%1$s[%2$s][parents]" value="" />', $args['section'], $args['id']);
        $html .= sprintf('<input type="hidden" name="%1$s[%2$s][children]" value="" />', $args['section'], $args['id']);

        foreach ($args['options'] as $key => $label) {
            $checked = isset($value['parents'][$key]) ? $value['parents'][$key] : '0';

            $name = isset($label['name']) ? $label['name'] : '';
            $submenus = isset($label['submenus']) ? $label['submenus'] : ' ';

            $html .= sprintf('<label for="white-label-%1$s[%2$s][%3$s][%4$s]">', $args['section'], $args['id'], 'parents', $key);
            $html .= sprintf('<input type="checkbox" class="checkbox" id="wl-%1$s[%2$s][%3$s][%4$s]" name="%1$s[%2$s][%3$s][%4$s]" value="%4$s" %5$s />', $args['section'], $args['id'], 'parents', $key, checked($checked, $key, false));
            $html .= sprintf('%1$s</label><br>', $name);

            $parent = $key;
            // Submenus.
            if ($submenus && is_array($submenus)) {
                foreach ($submenus as $slug => $name) {
                    $checked = isset($value['children'][$parent]) && isset($value['children'][$parent][$slug]) ? $value['children'][$parent][$slug] : '0';

                    $html .= sprintf('<label class="wl-nested" for="white-label-%1$s[%2$s][%3$s][%4$s][%5$s]">', $args['section'], $args['id'], 'children', $parent, $slug);
                    $html .= sprintf('<input type="checkbox" class="checkbox" id="wl-%1$s[%2$s][%3$s][%4$s][%5$s]" name="%1$s[%2$s][%3$s][%4$s][%5$s]" value="%5$s" %6$s />', $args['section'], $args['id'], 'children', $parent, $slug, checked($checked, $slug, false));
                    $html .= sprintf('%1$s</label><br>', $name);
                }
            }
        }
        $html .= $this->get_field_description($args);
        $html .= '</fieldset>';
        echo $html;
    }

    /**
     * Displays a table for plugin settings.
     *
     * @param array $args settings field args.
     */
    public function callback_plugins($args)
    {
        $html = '';

        $hidden_plugins = $this->get_option($args['id'], $args['section'], $args['std']);
        $html .= sprintf('<input type="hidden" name="%1$s[%2$s]" value="" />', $args['section'], $args['id']);


        $html .= '<table class="form-table widefat wl-table" style="width:100%;">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<td class="wl-center" style="width:10%;">'.__('Hide', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/hide-wordpress-plugins/"><span class="dashicons dashicons-editor-help"></span></a></td>';

        $html .= '<td style="width:90%;">'.__('Plugin', 'white-label').'</td>';


        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody>';
        $wl_plugin_counter = 0;

        foreach ($args['options'] as $key => $name) {
            $wl_plugin_counter++;
            $checked = isset($hidden_plugins[$key]) ? $hidden_plugins[$key] : '0';

            $html .= '<tr>';
            $html .= '<td class="wl-center" style="vertical-align:top;">'.sprintf('<input type="checkbox" class="checkbox" id="wl-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked($checked, $key, false)).'</td>';
            $html .= '<td style="vertical-align:top;">'.$name.'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';

        $html .= '</table>';
        $html .= $this->get_field_description($args);

        echo $html;
    }

    /**
     * Displays a table for theme settings.
     *
     * @param array $args settings field args.
     */
    public function callback_themes($args)
    {
        $html = '';

        $hidden_themes = $this->get_option($args['id'], $args['section'], $args['std']);
        $html .= sprintf('<input type="hidden" name="%1$s[%2$s]" value="" />', $args['section'], $args['id']);


        $html .= '<table class="form-table widefat wl-table" style="width:100%;">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<td class="wl-center" style="width:10%;">'.__('Hide', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/hide-wordpress-theme/"><span class="dashicons dashicons-editor-help"></span></a></td>';

        $html .= '<td style="width:90%;">'.__('Theme', 'white-label').'</td>';


        $html .= '</tr>';
        $html .= '</thead>';

        $html .= '<tbody>';
        $wl_theme_counter = 0;

        foreach ($args['options'] as $key => $name) {
            $wl_theme_counter++;
            $checked = isset($hidden_themes[$key]) ? $hidden_themes[$key] : '0';

            $html .= '<tr>';
            $html .= '<td class="wl-center" style="vertical-align:top;">'.sprintf('<input type="checkbox" class="checkbox" id="wl-%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked($checked, $key, false)).'</td>';
            $html .= '<td style="vertical-align:top;">'.$name.'</td>';
            $html .= '</tr>';
        }
        $html .= '</tbody>';

        $html .= '</table>';
        $html .= $this->get_field_description($args);

        echo $html;
    }

    /**
     * Displays a table for sidebar menu settings.
     *
     * @param array $args settings field args.
     */
    public function callback_sidebar_menus($args)
    {
        $hidden_sidebar_menus = $this->get_option('hidden_sidebar_menus', $args['section'], $args['std']);
        $renamed_sidebar_menus = $this->get_option('renamed_sidebar_menus', $args['section'], $args['std']);
        $dashicons_sidebar_menus = $this->get_option('dashicons_sidebar_menus', $args['section'], $args['std']);

        $html = '';

        $html .= sprintf('<input type="hidden" name="%1$s[%2$s][parents]" value="" />', $args['section'], 'hidden_sidebar_menus');
        $html .= sprintf('<input type="hidden" name="%1$s[%2$s][children]" value="" />', $args['section'], 'hidden_sidebar_menus');


        $html .= '<table class="form-table widefat wl-table" style="width:100%;">';
        $html .= '<thead>';
        $html .= '<tr>';
        $html .= '<td class="wl-center" style="width:10%;">'.__('Hide', 'white-label').'<a target="_blank" tabindex="-1" class="white-label-help" href="https://whitewp.com/documentation/article/hide-wordpress-admin-menus/"><span class="dashicons dashicons-editor-help"></span></a></td>';

        $html .= '<td style="width:90%;">'.__('Sidebar Menu', 'white-label').'</td>';


        $html .= '</tr>';
        $html .= '</thead>';

        // select input with every dashicon font as an option

        $html .= '<tbody>';
        foreach ($args['options'] as $key => $label) {
            $checked = isset($hidden_sidebar_menus['parents'][$key]) ? $hidden_sidebar_menus['parents'][$key] : '0';
            $text = isset($renamed_sidebar_menus['parents'][$key]) && isset($renamed_sidebar_menus['parents'][$key]) ? $renamed_sidebar_menus['parents'][$key] : '';
            $dashicon = isset($dashicons_sidebar_menus['parents'][$key]) && isset($dashicons_sidebar_menus['parents'][$key]) ? $dashicons_sidebar_menus['parents'][$key] : '';

            $name = isset($label['name']) ? $label['name'] : '';
            $submenus = isset($label['submenus']) ? $label['submenus'] : ' ';

            $html .= '<tr>';
            $html .= '<td class="wl-center">'.sprintf('<input type="checkbox" class="checkbox" id="wl-hidden-%1$s[%2$s][%3$s][%4$s]" name="%1$s[%2$s][%3$s][%4$s]" value="%4$s" %5$s />', $args['section'], 'hidden_sidebar_menus', 'parents', $key, checked($checked, $key, false)).'</td>';
            $html .= '<td class="white-label-depth-0">'.$name.'</td>';
            $html .= '</tr>';

            $parent = $key;
            // Submenus
            if ($submenus && is_array($submenus)) {
                foreach ($submenus as $slug => $name) {
                    $checked = isset($hidden_sidebar_menus['children'][$parent]) && isset($hidden_sidebar_menus['children'][$parent][$slug]) ? $hidden_sidebar_menus['children'][$parent][$slug] : '0';
                    $text = isset($renamed_sidebar_menus['children'][$parent]) && isset($renamed_sidebar_menus['children'][$parent][$slug]) ? $renamed_sidebar_menus['children'][$parent][$slug] : '';

                    $html .= '<tr>';
                    $html .= '<td class="wl-center">'.sprintf('<input type="checkbox" class="checkbox" id="wl-hidden-%1$s[%2$s][%3$s][%4$s][%5$s]" name="%1$s[%2$s][%3$s][%4$s][%5$s]" value="%5$s" %6$s />', $args['section'], 'hidden_sidebar_menus', 'children', $parent, $slug, checked($checked, $slug, false)).'</td>';
                    $html .= '<td class="wl-nested">'.$name.'</td>';
                    $html .= '</tr>';
                }
            }
        }
        $html .= '</tbody>';

        $html .= '</table>';
        $html .= $this->get_field_description($args);

        echo $html;
    }


    public function get_menu_title($menu)
    {
        if (!isset($menu->title)) {
            return '';
        }

        $title = trim(wp_strip_all_tags($menu->title));

        return ((!$title || empty($title)) ? '-' : $title).' ('.$menu->id.')';
    }

    /**
     * Displays a radio button for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_radio($args)
    {
        $value = $this->get_option($args['id'], $args['section'], $args['std']);
        $html = '<fieldset>';
        foreach ($args['options'] as $key => $label) {
            $html .= sprintf('<label for="white-label-%1$s[%2$s][%3$s]">', $args['section'], $args['id'], $key);
            $html .= sprintf('<input type="radio" class="radio" id="white-label-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s" %4$s />', $args['section'], $args['id'], $key, checked($value, $key, false));
            $html .= sprintf('%1$s</label><br>', $label);
        }
        $html .= $this->get_field_description($args);
        $html .= '</fieldset>';
        echo $html;
    }

    /**
     * Displays a selectbox for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_select($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $html = sprintf('<select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]">', $size, $args['section'], $args['id']);
        foreach ($args['options'] as $key => $label) {
            $html .= sprintf('<option value="%s"%s>%s</option>', $key, selected($value, $key, false), $label);
        }
        $html .= sprintf('</select>');
        $html .= $this->get_field_description($args);
        echo $html;
    }

    /**
     * Displays a textarea for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_textarea($args)
    {
        $value = $this->get_option($args['id'], $args['section'], $args['std']);
        $value = esc_textarea($this->get_option($args['id'], $args['section'], $args['std']));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $placeholder = empty($args['placeholder']) ? '' : ' placeholder="'.$args['placeholder'].'"';
        $html = sprintf('<textarea rows="10" cols="55" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]"%4$s>%5$s</textarea>', $size, $args['section'], $args['id'], $placeholder, $value);
        $html .= $this->get_field_description($args);
        echo $html;
    }

    /**
     * Displays the html for a settings field
     *
     * @param array $args settings field args
     * @return string
     */
    public function callback_html($args)
    {
        echo $this->get_field_description($args);
    }

    /**
     * Displays a rich text textarea for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_wysiwyg($args)
    {
        $value = $this->get_option($args['id'], $args['section'], $args['std']);
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : '100%';
        $editor_height = 500;
        if (isset($args['id'])) {
            switch ($args['id']) {
                case 'admin_welcome_panel_content':
                    $editor_height = 300;
                    break;

                case 'admin_footer_credit':
                    $editor_height = 100;
                    break;

                default:
                    $editor_height = 500;
                    break;
            }
        }

        $editor_settings = [
            'teeny' => false,
            'textarea_name' => $args['section'].'['.$args['id'].']',
            'editor_height' => $editor_height,
        ];

        echo '<h4 class="white-label-name">'.$args['name'].'</h4>';
        echo $this->get_field_description($args);

        echo '<div class="white-label-wysiwyg" style="max-width: '.$size.';">';
        if (isset($args['options']) && is_array($args['options'])) {
            $editor_settings = array_merge($editor_settings, $args['options']);
        }
        wp_editor($value, $args['section'].'-'.$args['id'], $editor_settings);
        echo '</div>';
    }

    /**
     * Displays a file upload field for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_file($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $id = $args['section'].'['.$args['id'].']';
        $label = isset($args['options']['button_label']) ? $args['options']['button_label'] : __('Choose File');
        $html = sprintf('<input type="text" class="%1$s-text wpsa-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value);
        $html .= '<input type="button" class="button wpsa-browse" value="'.$label.'" />';
        $html .= $this->get_field_description($args);
        echo $html;
    }

    /**
     * Displays a password field for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_password($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $html = sprintf('<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>', $size, $args['section'], $args['id'], $value);
        $html .= $this->get_field_description($args);
        echo $html;
    }

    /**
     * Displays a color picker field for a settings field.
     *
     * @param array $args settings field args.
     */
    public function callback_color($args)
    {
        $value = esc_attr($this->get_option($args['id'], $args['section'], $args['std']));
        $size = isset($args['size']) && !is_null($args['size']) ? $args['size'] : 'regular';
        $html = sprintf('<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />', $size, $args['section'], $args['id'], $value, $args['std']);
        $html .= $this->get_field_description($args);
        echo $html;
    }

    /**
     * Displays a select box for creating the pages select box.
     *
     * @param array $args settings field args.
     */
    public function callback_pages($args)
    {
        $dropdown_args = [
            'selected' => esc_attr($this->get_option($args['id'], $args['section'], $args['std'])),
            'name' => $args['section'].'['.$args['id'].']',
            'id' => $args['section'].'['.$args['id'].']',
            'echo' => 0,
        ];
        $html = wp_dropdown_pages($dropdown_args);
        echo $html;
    }

    /**
     * Displays a licence field for EDD.
     *
     * @param array $args settings field args.
     */
    public function callback_edd_license($args)
    {
        do_action('white_label_callback_edd_license', $args);
    }

    /**
     * Sanitize callback for Settings API.
     *
     * @return mixed
     */
    public function sanitize_options($options)
    {
        if (!$options) {
            return $options;
        }
        foreach ($options as $option_slug => $option_value) {
            $sanitize_callback = $this->get_sanitize_callback($option_slug);
            // If callback is set, call it
            if ($sanitize_callback) {
                $options[$option_slug] = call_user_func($sanitize_callback, $option_value);
                continue;
            }
        }
        return $options;
    }

    /**
     * Get sanitization callback for given option slug.
     *
     * @param string $slug option slug.
     *
     * @return mixed string or bool false.
     */
    public function get_sanitize_callback($slug = '')
    {
        if (empty($slug)) {
            return false;
        }
        // Iterate over registered fields and see if we can find proper callback.
        foreach ($this->settings_fields as $section => $options) {
            foreach ($options as $option) {
                if ($option['name'] != $slug) {
                    continue;
                }
                // Return the callback name
                return isset($option['sanitize_callback']) && is_callable($option['sanitize_callback']) ? $option['sanitize_callback'] : false;
            }
        }
        return false;
    }

    /**
     * Get the value of a settings field
     *
     * @param string $option  settings field name
     * @param string $section the section name this field belongs to
     * @param string $default default text if it's not found
     * @return string
     */
    public function get_option($option, $section, $default = '')
    {
        $options = get_option($section);
        if (isset($options[$option])) {
            return $options[$option];
        }
        return $default;
    }

    /**
     * Show navigations as tab.
     *
     * Shows all the settings section labels as tab.
     */
    public function show_navigation()
    {
        $html = '<h2 class="nav-tab-wrapper">';
        $count = count($this->settings_sections);
        // don't show the navigation if only one section exists.
        if ($count === 1) {
            return;
        }
        foreach ($this->settings_sections as $tab) {
            $html .= sprintf('<a href="#%1$s" class="nav-tab" id="%1$s-tab">%2$s</a>', $tab['id'], $tab['title']);
        }
        $html .= '</h2>';
        echo $html;
    }

    /**
     * Show the section settings forms.
     *
     * This function displays every sections in a different form.
     */
    public function show_forms()
    {
        echo '<div class="white-label-metabox-wrapper white-label-form-wrapper">';

        foreach ($this->settings_sections as $form) {
            echo '<div id="'.$form['id'].'" class="group" style="display: none;">';

            if (isset($form['custom_tab']) && $form['custom_tab'] === true) {
                // Allow custom tabs.
                do_action('white_label_settings_tab_'.$form['id'], $form);
            } else {
                // Standard form.
                echo '<form method="post" action="options.php">';
                // Display something at the top of the form.
                do_action('white_label_form_top_'.$form['id'], $form);
                // Display the settings.
                settings_fields($form['id']);
                do_settings_sections($form['id']);
                // Display something at the bottom of the form.
                do_action('white_label_form_bottom_'.$form['id'], $form);

                if (isset($this->settings_fields[$form['id']]) && (isset($form['id']) && $form['id'] != 'white_label_pro_license')) {
                    echo '<div class="white-label-submit-button">';
                    submit_button(__('Save Settings', 'white-label'), 'primary', 'submit', true, ['id' => $form['id']]);
                    echo '</div>';
                }
                echo '</form>';
                echo '</div>';
            }
        }

        echo '</div>'; // end wrap.
    }

    /**
     * Display sidebar and each box.
     */
    public function show_sidebar()
    {
        if (!$this->settings_sidebar) {
            return;
        }

        echo '<div class="white-label-sidebar">';

        do_action('white_label'.'_above_settings_sidebars');

        foreach ($this->settings_sidebar as $sidebar) {
            echo '<div class="white-label-metabox">';
            echo '<div class="postbox">';
            echo '<div class="inside">';
            echo '<h3>'.$sidebar['title'].'</h3>';
            echo '<p>'.$sidebar['content'].'</p>';
            echo '</div> ';
            echo '</div> ';
            echo '</div> ';
        }

        echo '</div> ';

        do_action('white_label'.'_below_settings_sidebars');
    }

}
