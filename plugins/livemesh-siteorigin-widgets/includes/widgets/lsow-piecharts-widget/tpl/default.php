<?php
/**
 * @var $settings
 */

?>

<?php if( !empty( $instance['title'] ) ) echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_piecharts_' . $this->id . '_settings', $settings);

$bar_color = ' data-bar-color="' . esc_attr($settings['bar_color']) . '"';
$track_color = ' data-track-color="' . esc_attr($settings['track_color']) . '"';

$output = '<div class="lsow-piecharts lsow-grid-container ' . lsow_get_grid_classes($settings) . '">';

foreach ($settings['piecharts'] as $piechart):

    $child_output = '<div class="lsow-grid-item lsow-piechart">';

    $child_output .= '<div class="lsow-percentage"' 
        . $bar_color . $track_color 
        . ' data-percent="' . round($piechart['percentage']) . '">';

    $child_output .= '<span>' . round($piechart['percentage']) . '<sup>%</sup>' . '</span>';

    $child_output .= '</div>';

    $child_output .= '<div class="lsow-label">' . esc_html($piechart['stats_title']) . '</div>';

    $child_output .= '</div><!-- .lsow-piechart -->';

    $output .= apply_filters('lsow_piechart_output', $child_output, $piechart, $settings);

endforeach;

$output .= '</div><!-- .lsow-piecharts -->';

$output .= '<div class="lsow-clear"></div>';

echo apply_filters('lsow_piecharts_output', $output, $settings);