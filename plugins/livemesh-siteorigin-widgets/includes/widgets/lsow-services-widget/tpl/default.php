<?php
/**
 * @var $settings
 */

if (!empty($instance['title']))
    echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_services_' . $this->id . '_settings', $settings);

if (!empty($settings['target']))
    $target = 'target="_blank"';
else
    $target = '';

$id = 'id="' . $this->id . '"';

$output = '<div ' . $id . ' class="lsow-services lsow-' . $settings['style'] . ' lsow-grid-container ' . lsow_get_grid_classes($settings) . '">';

foreach ($settings['services'] as $service):

    list($animate_class, $animation_attr) = lsow_get_animation_atts($service['animation']);

    $service_url = sow_esc_url($service['href']);

    $child_output = '<div class="lsow-grid-item lsow-service-wrapper">';

    $child_output .= '<div class="lsow-service ' . $animate_class . '" ' . $animation_attr . '>';

    if ($settings['icon_type'] == 'icon_image') :

        if (!empty($service['icon_image'])):

            if (empty($service_url)) :

                $child_output .= '<div class="lsow-image-wrapper">';

                $image_html = wp_get_attachment_image($service['icon_image'], 'full', false, array('class' => 'lsow-image full'));

                $child_output .= $image_html;

                $child_output .= '</div>';

            else :

                $child_output .= '<a class="lsow-image-wrapper" href="' . $service_url . '" ' . $target . '>';

                $image_html = wp_get_attachment_image($service['icon_image'], 'full', false, array('class' => 'lsow-image full'));

                $child_output .= $image_html;

                $child_output .= '</a>';

            endif;

        endif;

    elseif ($settings['icon_type'] == 'icon') :

        if (empty($service_url)) :

            $child_output .= '<div class="lsow-icon-wrapper">';

            $child_output .= siteorigin_widget_get_icon($service['icon']);;

            $child_output .= '</div>';

        else :

            $child_output .= '<a class="lsow-icon-wrapper" href="' . $service_url . '" ' . $target . '>';

            $child_output .= siteorigin_widget_get_icon($service['icon']);;

            $child_output .= '</a>';

        endif;

    endif;

    $child_output .= '<div class="lsow-service-text">';

    if (empty($service_url)) :

        $child_output .= '<h3 class="lsow-title">' . esc_html($service['title']) . '</h3>';

    else:

        $child_output .= '<a class="lsow-title-link" href="' . $service_url . '" ' . $target . '>';

        $child_output .= '<h3 class="lsow-title">' . esc_html($service['title']) . '</h3>';

        $child_output .= '</a>';

    endif;

    $child_output .= '<div class="lsow-service-details">' . do_shortcode(wp_kses_post($service['excerpt'])) . '</div>';

    $child_output .= '</div><!-- .lsow-service-text -->';

    $child_output .= '</div><!-- .lsow-service -->';

    $child_output .= '</div><!-- .lsow-service-wrapper -->';

    $output .= apply_filters('lsow_service_item_output', $child_output, $service, $settings);

endforeach;

$output .= '</div><!-- .lsow-services -->';

$output .= '<div class="lsow-clear"></div>';

echo apply_filters('lsow_services_output', $output, $settings);