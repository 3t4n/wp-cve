<?php
/**
 * @var $settings
 */

if (!empty($instance['title']))
    echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_clients_' . $this->id . '_settings', $settings);

list($animate_class, $animation_attr) = lsow_get_animation_atts($settings['animation']);

$output = '<div class="lsow-clients lsow-gapless-grid">';

$output .= '<div class="lsow-grid-container ' . lsow_get_grid_classes($settings) . '">';

foreach ($settings['clients'] as $client):

    $child_output = '<div class="lsow-grid-item lsow-client ' . $animate_class . '" ' . $animation_attr . '>';

    if (!empty($client['link'])) {
        $child_output .= '<a class="lsow-client-link" '
            . 'href="' . sow_esc_url($client['link'])
            . '" title="' . esc_html($client['name'])
            . '" target="_blank">';
    }

    if (!empty($client['image'])):

        $child_output .= wp_get_attachment_image($client['image'], 'full', false, array('class' => 'lsow-image full', 'alt' => $client['name']));

    endif;

    $child_output .= '<div class="lsow-client-name">' . wp_kses_post($client['name']) . '</div>';


    $child_output .= '<div class="lsow-image-overlay"></div>';

    if (!empty($client['link'])) {
        $child_output .= '</a><!-- .lsow-client-link -->';
    }

    $child_output .= '</div><!-- .lsow-client -->';

    $output .= apply_filters('lsow_client_item_output', $child_output, $client, $settings);

endforeach;

$output .= '</div>';

$output .= '</div><!-- .lsow-clients -->';

echo apply_filters('lsow_clients_output', $output, $settings);