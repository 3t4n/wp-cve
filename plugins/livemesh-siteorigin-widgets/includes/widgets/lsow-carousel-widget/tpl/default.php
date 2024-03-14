<?php
/**
 * @var $settings
 */

if( !empty( $instance['title'] ) ) echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_carousel_' . $this->id . '_settings', $settings);

$carousel_settings = $settings['carousel_settings'];

if (!empty($settings['elements'])) :

    $output = '<div id="lsow-carousel-' . $this->id . '" class="lsow-carousel lsow-container" data-settings=\'' . wp_json_encode($carousel_settings) . '\'>';

    foreach ($settings['elements'] as $element) :

        $child_output = '<div class="lsow-carousel-item">';

        $child_output .= do_shortcode(wp_kses_post($element['text']));

        $child_output .= '</div><!-- .lsow-carousel-item -->';

        $output .= apply_filters('lsow_carousel_item_output', $child_output, $element, $settings);

    endforeach;

    $output .= '</div><!-- .lsow-carousel -->';

    echo apply_filters('lsow_carousel_output', $output, $settings);

endif;