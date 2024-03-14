<?php
/**
 * @var $settings
 */

?>

<?php if( !empty( $instance['title'] ) ) echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_testimonials_slider_' . $this->id . '_settings', $settings);

$slider_options = [
    'slide_animation' => $settings['slide_animation'],
    'direction' => 'horizontal',
    'slideshow_speed' => absint($settings['slideshow_speed']),
    'animation_speed' => absint($settings['animation_speed']),
    'control_nav' => ($settings['control_nav']),
    'direction_nav' => ($settings['direction_nav']),
    'pause_on_hover' => ($settings['pause_on_hover']),
    'pause_on_action' => ($settings['pause_on_action'])
];

$slider_options = apply_filters('lsow_testimonials_slider_options', $slider_options, $settings);

$output = '<div class="lsow-testimonials-slider lsow-flexslider lsow-container" data-settings=\'' . wp_json_encode($slider_options) . '\'>';

$output .= '<div class="lsow-slides">';

foreach ($settings['testimonials'] as $testimonial) :

    $child_output = '<div class="lsow-slide lsow-testimonial-wrapper">';

    $child_output .= '<div class="lsow-testimonial">';

    $child_output .= '<div class="lsow-testimonial-text">';

    $child_output .= '<i class="lsow-icon-quote"></i>';

    $child_output .= do_shortcode($testimonial['text']);

    $child_output .= '</div>';

    $child_output .= '<div class="lsow-testimonial-user">';

    $child_output .= '<div class="lsow-image-wrapper">';

    $client_image = $testimonial['image'];

    if (!empty($client_image)):

        $child_output .= wp_get_attachment_image($client_image, 'thumbnail', false, array('class' => 'lsow-image full'));

    endif;

    $child_output .= '</div><!-- .lsow-image-wrapper -->';

    $child_output .= '<div class="lsow-text">';

    $child_output .= '<h3 class="lsow-author-name">' . esc_html($testimonial['name']) . '</h3>';

    $child_output .= '<div class="lsow-author-credentials">' . wp_kses_post($testimonial['credentials']) . '</div>';

    $child_output .= '</div>';

    $child_output .= '</div><!-- .lsow-testimonial-user -->';

    $child_output .= '</div><!-- .lsow-testimonial -->';

    $child_output .= '</div><!-- .lsow-testimonial-wrapper.lsow-slide -->';

    $output .= apply_filters('lsow_testimonials_slide_output', $child_output, $testimonial, $settings);

endforeach;

$output .= '</div><!-- .lsow-slides -->';

$output .= '</div><!-- .lsow-testimonials-slider -->';

echo apply_filters('lsow_testimonials_slider_output', $output, $settings);