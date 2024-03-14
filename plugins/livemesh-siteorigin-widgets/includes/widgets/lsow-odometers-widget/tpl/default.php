<?php
/**
 * @var $settings
 */

?>

<?php if( !empty( $instance['title'] ) ) echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_odometers_' . $this->id . '_settings', $settings);

$output = '<div class="lsow-odometers lsow-grid-container ' . lsow_get_grid_classes($settings) . '">';

foreach ($settings['odometers'] as $odometer):

    $prefix = (!empty ($odometer['prefix'])) ? '<span class="prefix">' . $odometer['prefix'] . '</span>' : '';
    $suffix = (!empty ($odometer['suffix'])) ? '<span class="suffix">' . $odometer['suffix'] . '</span>' : '';

    $child_output = '<div class="lsow-grid-item lsow-odometer">';

    $child_output .= (!empty ($odometer['prefix'])) ? '<span class="lsow-prefix">' . $odometer['prefix'] . '</span>' : '';

    $child_output .= '<div class="lsow-number odometer" data-stop="' . intval($odometer['stop_value']) . '">';

    $child_output .= intval($odometer['start_value']);

    $child_output .= '</div><!-- .lsow-number -->';

    $child_output .= (!empty ($odometer['suffix'])) ? '<span class="lsow-suffix">' . $odometer['suffix'] . '</span>' : '';

    $icon_type = esc_html($odometer['icon_type']);

    if ($icon_type == 'icon_image') :

        $icon_image = $odometer['icon_image'];

        if (!empty($icon_image)):

            $icon_html = '<span class="lsow-image-wrapper">' . wp_get_attachment_image($icon_image, 'full', false, array('class' => 'lsow-image full')) . '</span>';

        endif;

    else :

        $icon_html = '<span class="lsow-icon-wrapper">' . siteorigin_widget_get_icon($odometer['icon']) . '</span>';

    endif;

    $child_output .= '<div class="lsow-stats-title-wrap">';

    $child_output .= '<div class="lsow-stats-title">' . $icon_html . esc_html($odometer['stats_title']) . '</div>';

    $child_output .= '</div>';

    $child_output .= '</div><!-- .lsow-odometer -->';

    $output .= apply_filters('lsow_odometer_output', $child_output, $odometer, $settings);

endforeach;

$output .= '</div><!-- .lsow-odometers -->';

$output .= '<div class="lsow-clear"></div>';

echo apply_filters('lsow_odometers_output', $output, $settings);