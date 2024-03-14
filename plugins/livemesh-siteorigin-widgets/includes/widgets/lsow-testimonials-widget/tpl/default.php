<?php
/**
 * @var $settings
 */
?>

<?php if( !empty( $instance['title'] ) ) echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_testimonials_' . $this->id . '_settings', $settings);

$output = '<div class="lsow-testimonials lsow-grid-container ' . lsow_get_grid_classes($settings) . '">';

foreach ($settings['testimonials'] as $testimonial) :

    list($animate_class, $animation_attr) = lsow_get_animation_atts($testimonial['animation']);

    $child_output = '<div class="lsow-grid-item lsow-testimonial ' . $animate_class . '" ' . $animation_attr . '>';

    $child_output .= '<div class="lsow-testimonial-text">';

    $child_output .= do_shortcode($testimonial['text']);

    $child_output .= '</div>';

    $child_output .= '<div class="lsow-testimonial-user">';

    $child_output .= '<div class="lsow-image-wrapper">';

    $client_image = $testimonial['image'];

    if (!empty($client_image)):

        $child_output .= wp_get_attachment_image($client_image, 'thumbnail', false, array('class' => 'lsow-image full'));

    endif;

    $child_output .= '</div>';

    $child_output .= '<div class="lsow-text">';

    $child_output .= '<h3 class="lsow-author-name">' . esc_html($testimonial['name']) . '</h3>';

    $child_output .= '<div class="lsow-author-credentials">' . wp_kses_post($testimonial['credentials']) . '</div>';

    $child_output .= '</div><!-- .lsow-text -->';

    $child_output .= '</div><!-- .lsow-testimonial-user -->';

    $child_output .= '</div><!-- .lsow-testimonial -->';

    $output .= apply_filters('lsow_testimonial_output', $child_output, $testimonial, $settings);

endforeach;

$output .= '</div><!-- .lsow-testimonials -->';

$output .= '<div class="lsow-clear"></div>';

echo apply_filters('lsow_testimonials_output', $output, $settings);