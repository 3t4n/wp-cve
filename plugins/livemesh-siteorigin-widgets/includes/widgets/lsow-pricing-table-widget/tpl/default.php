<?php
/**
 * @var $settings
 */

if (!empty($instance['title']))
    echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_pricing_table_' . $this->id . '_settings', $settings);

if (empty($settings['pricing_plans']))
    return;

$output = '<div class="lsow-pricing-table lsow-grid-container ' . lsow_get_grid_classes($settings) . '">';

foreach ($settings['pricing_plans'] as $pricing_plan) :

    $pricing_title = esc_html($pricing_plan['pricing_title']);
    $tagline = esc_html($pricing_plan['tagline']);
    $price_tag = htmlspecialchars_decode(wp_kses_post($pricing_plan['price_tag']));
    $pricing_img = $pricing_plan['image'];
    $pricing_url = (empty($pricing_plan['url'])) ? '#' : sow_esc_url($pricing_plan['url']);
    $pricing_button_text = esc_html($pricing_plan['button_text']);
    $button_new_window = esc_html($pricing_plan['button_new_window']);
    $highlight = esc_html($pricing_plan['highlight']);

    $price_tag = (empty($price_tag)) ? '' : $price_tag;

    list($animate_class, $animation_attr) = lsow_get_animation_atts($pricing_plan['animation']);

    $child_output = '<div class="lsow-grid-item lsow-pricing-plan ' . ($highlight ? ' lsow-highlight' : '') . ' ' . $animate_class . '" ' . $animation_attr . '>';

    $child_output .= '<div class="lsow-top-header">';

    if (!empty($tagline))
        $child_output .= '<p class="lsow-tagline center">' . $tagline . '</p>';

    $child_output .= '<h3 class="lsow-plan-name lsow-center">' . $pricing_title . '</h3>';

    if (!empty($pricing_img)) :
        $child_output .= wp_get_attachment_image($pricing_img, 'full', false, array('class' => 'lsow-image full', 'alt' => $pricing_title));

    endif;

    $child_output .= '</div>';

    $child_output .= '<h4 class="lsow-plan-price lsow-plan-header lsow-center">';

    $child_output .= '<span class="lsow-text">' . wp_kses_post($price_tag) . '</span>';

    $child_output .= '</h4>';

    $child_output .= '<div class="lsow-plan-details">';

    foreach ($pricing_plan['items'] as $pricing_item) :

        $child_output .= '<div class="lsow-pricing-item">';

        $child_output .= '<div class="lsow-title">';

        $child_output .= htmlspecialchars_decode(wp_kses_post($pricing_item['title']));

        $child_output .= '</div>';

        $child_output .= '<div class="lsow-value-wrap">';

        if (!empty($pricing_item['icon_new'])) {
            $child_output .= siteorigin_widget_get_icon($pricing_item['icon_new']);
        }

        $child_output .= '<div class="lsow-value">';

        $child_output .= htmlspecialchars_decode(wp_kses_post($pricing_item['value']));

        $child_output .= '</div>';

        $child_output .= '</div>';

        $child_output .= '</div>';

    endforeach;

    $child_output .= '</div><!-- .lsow-plan-details -->';

    $child_output .= '<div class="lsow-purchase">';

    $child_output .= '<a class="lsow-button default" href="' . esc_url($pricing_url)
        . '"' . (!empty($button_new_window) ? ' target="_blank"' : '')
        . '>' . esc_html($pricing_button_text)
        . '</a>';

    $child_output .= '</div>';

    $child_output .= '</div><!-- .lsow-pricing-plan -->';

    $output .= apply_filters('lsow_pricing_plan_output', $child_output, $pricing_plan, $settings);

endforeach;

$output .= '</div><!-- .lsow-pricing-table -->';

$output .= '<div class="lsow-clear"></div>';

echo apply_filters('lsow_pricing_table_output', $output, $settings);