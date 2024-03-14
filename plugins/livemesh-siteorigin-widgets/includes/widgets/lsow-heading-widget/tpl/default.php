<?php
/**
 * @var $settings
 */

$settings = apply_filters('lsow_heading_' . $this->id . '_settings', $settings);

list($animate_class, $animation_attr) = lsow_get_animation_atts($settings['animation']);

$output = '<div class="lsow-heading lsow-' . $settings['style'] . ' lsow-align' . $settings['align'] . ' ' . $animate_class . '" ' . $animation_attr . '>';

if ($settings['style'] == 'style2' && !empty($settings['subtitle'])):

    $output .= '<div class="lsow-subtitle">' . esc_html($settings['subtitle']) . '</div>';

endif;

$output .= '<h3 class="lsow-title">' . wp_kses_post($settings['heading']) . '</h3>';

if ($settings['style'] != 'style3' && !empty($settings['short_text'])):

    $output .= '<p class="lsow-text">' . wp_kses_post($settings['short_text']) . '</p>';

endif;

$output .= '</div><!-- .lsow-heading -->';

echo apply_filters('lsow_heading_output', $output, $settings);