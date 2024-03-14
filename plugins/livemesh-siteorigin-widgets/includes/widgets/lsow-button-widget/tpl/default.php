<?php
/**
 * @var $settings
 */


$settings = apply_filters('lsow_button_' . $this->id . '_settings', $settings);

list($animate_class, $animation_attr) = lsow_get_animation_atts($settings['animation']);

$icon_html = '';

$id = (!empty($settings['id'])) ? ' id="' . $settings['id'] . '"' : '';

$class = (!empty($settings['class'])) ? ' ' . $settings['class'] : '';

$color_class = ' lsow-' . esc_attr($settings['color']);
if (!empty($settings['type']))
    $settings['type'] = ' lsow-' . esc_attr($settings['type']);

$rounded = (!empty($settings['rounded'])) ? ' lsow-rounded' : '';

if (!empty($settings['target']))
    $target = ' target="_blank"';
else
    $target = '';

if ($settings['color'] == 'default' || ($settings['color'] == 'custom' && empty($settings['custom_color']))) {
    $options = get_option('lsow_settings');

    if ($options && isset($options['lsow_theme_color'])) {
        $settings['custom_color'] = $options['lsow_theme_color'];
    }
    else {
        $settings['custom_color'] = '#f94213'; // default button color if none set in theme options
    }
}

$style = ($settings['style']) ? ' style="' . esc_attr($settings['style']) . '"' : '';

// Use the custom color only if user wants to use the custom color set
$color_attr = ($settings['color'] == 'custom') ? ' data-color=' . esc_html($settings['custom_color']) : '';

$hover_color_attr = ($settings['hover_color']) ? ' data-hover-color=' . esc_html($settings['hover_color']) : '';

if ($settings['icon_type'] == 'icon_image')
    $icon_html = wp_get_attachment_image($settings['icon_image'], 'thumbnail', false, array('class' => 'lsow-image lsow-thumbnail'));
elseif ($settings['icon_type'] == 'icon')
    $icon_html = siteorigin_widget_get_icon($settings['icon']);

$button_content = '<a' . $id . ' class= "lsow-button ' . ((!empty($icon_html)) ? ' lsow-with-icon' : '') . esc_attr($class) . $color_class . $settings['type'] . $rounded . $animate_class . '"' . $style . $color_attr . $hover_color_attr . $animation_attr . ' href="' . sow_esc_url($settings['href']) . '"' . esc_html($target) . '>' . $icon_html . esc_html($settings['text']) . '</a>';

if ($settings['align'] != 'none')
    $button_content = '<div class="lsow-button-wrap" style="clear: both; text-align:' . esc_attr($settings['align']) . ';">' . $button_content . '</div>';

$output = $button_content;

echo apply_filters('lsow_button_output', $output, $settings);