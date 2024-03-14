<?php
/**
 * @var $settings
 */

if (!empty($settings['target']))
    $target = 'target="_blank"';
else
    $target = '';

?>

<?php if( !empty( $instance['title'] ) ) echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_icon_list_' . $this->id . '_settings', $settings);

list($animate_class, $animation_attr) = lsow_get_animation_atts($settings['animation']);

$output = '<div class="lsow-icon-list lsow-align' . $settings['align'] . '">';

foreach ($settings['icon_list'] as $icon_item):

    $icon_type = esc_html($settings['icon_type']);

    $icon_title = esc_html($icon_item['title']);

    $icon_url = sow_esc_url($icon_item['href']);

    $child_output = '<div class="lsow-icon-list-item ' . $animate_class . '" ' . $animation_attr . ' title="' . $icon_title . '">';

    if (($icon_type == 'icon_image') && !empty($icon_item['icon_image'])) :

        if (empty($icon_url)) :

            $child_output .= '<div class="lsow-image-wrapper">';

            $child_output .= wp_get_attachment_image($icon_item['icon_image'], 'full', false, array('class' => 'lsow-image full', 'alt' => $icon_title));

            $child_output .= '</div>';

        else :

            $child_output .= '<a class="lsow-image-wrapper" href="' . $icon_url . '" ' . $target . '>';

            $child_output .= wp_get_attachment_image($icon_item['icon_image'], 'full', false, array('class' => 'lsow-image full', 'alt' => $icon_title));

            $child_output .= '</a>';

        endif;

    else :

        if (empty($icon_url)) :

            $child_output .= '<div class="lsow-icon-wrapper">';

            $child_output .= siteorigin_widget_get_icon($icon_item['icon']);

            $child_output .= '</div>';

        else :

            $child_output .= '<a class="lsow-icon-wrapper" href="' . $icon_url . '" ' . $target . '>';

            $child_output .= siteorigin_widget_get_icon($icon_item['icon']);

            $child_output .= '</a>';

        endif;

    endif;

    $child_output .= '</div><!-- .lsow-icon-list-item -->';

    $output .= apply_filters('lsow_icon_list_item_output', $child_output, $icon_item, $settings);

endforeach;

$output .= '</div><!-- .lsow-icon-list -->';

echo apply_filters('lsow_icon_list_output', $output, $settings);