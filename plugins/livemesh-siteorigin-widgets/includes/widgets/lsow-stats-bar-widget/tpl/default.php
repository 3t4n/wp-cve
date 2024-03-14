<?php
/**
 * @var $settings
 */

if (!empty($instance['title']))
    echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_stats_bars_' . $this->id . '_settings', $settings);

$output = '<div class="lsow-stats-bars">';

foreach ($settings['stats_bars'] as $stats_bar) :

    $color_style = '';
    $color = $stats_bar['color'];
    if ($color)
        $color_style = ' style="background:' . esc_attr($color) . ';"';

    $child_output = '<div class="lsow-stats-bar">';

    $child_output .= '<div class="lsow-stats-title">';

    $child_output .= esc_html($stats_bar['title']);

    $child_output .= '<span>' . esc_attr($stats_bar['value']) . '%</span>';

    $child_output .= '</div>';

    $child_output .= '<div class="lsow-stats-bar-wrap">';

    $child_output .= '<div ' . $color_style . ' class="lsow-stats-bar-content" data-perc="' . esc_attr($stats_bar['value']) . '"></div>';

    $child_output .= '<div class="lsow-stats-bar-bg"></div>';

    $child_output .= '</div>';

    $child_output .= '</div><!-- .lsow-stats-bar -->';

    $output .= apply_filters('lsow_stats_bar_output', $child_output, $stats_bar, $settings);

endforeach;

$output .= '</div><!-- .lsow-stats-bars -->';

echo apply_filters('lsow_stats_bars_output', $output, $settings);