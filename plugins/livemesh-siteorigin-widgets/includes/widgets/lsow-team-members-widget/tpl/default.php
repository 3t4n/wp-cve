<?php
/**
 * @var $settings
 */

?>

<?php if( !empty( $instance['title'] ) ) echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];

$settings = apply_filters('lsow_team_members_' . $this->id . '_settings', $settings);

$item_style = '';

$container_style = 'lsow-container';

if ($settings['style'] == 'style1'):

    $item_style = 'lsow-grid-item';

    $container_style = 'lsow-grid-container ' . lsow_get_grid_classes($settings);

endif;

$output = '<div class="lsow-team-members lsow-' . $settings['style'] . ' ' . $container_style . '">';

foreach ($settings['team_members'] as $team_member):

    $child_output = '<div class="' . $item_style . ' lsow-team-member-wrapper">';

    list($animate_class, $animation_attr) = lsow_get_animation_atts($team_member['animation']);

    $child_output .= '<div class="lsow-team-member ' . $animate_class . '" ' . $animation_attr . '>';

    $child_output .= '<div class="lsow-image-wrapper">';

    if (!empty($team_member['image'])):

        $image_html = wp_get_attachment_image($team_member['image'], $settings['image_size'], false, array('class' => 'lsow-image'));;

        $child_output .= $image_html;

    endif;

    if ($settings['style'] == 'style1'):

        $child_output .= $this->social_profile($team_member, $settings);

    endif;

    $child_output .= '</div><!-- .lsow-image-wrapper -->';

    $child_output .= '<div class="lsow-team-member-text">';

    $child_output .= '<h3 class="lsow-title">' . esc_html($team_member['name']) . '</h3>';

    $child_output .= '<div class="lsow-team-member-position">';

    $child_output .= do_shortcode($team_member['position']);

    $child_output .= '</div>';

    $child_output .= '<div class="lsow-team-member-details">';

    $child_output .= do_shortcode($team_member['details']);

    $child_output .= '</div>';

    if ($settings['style'] == 'style2'):

        $child_output .= $this->social_profile($team_member, $settings);

    endif;

    $child_output .= '</div><!-- .lsow-team-member-text -->';

    $child_output .= '</div><!-- .lsow-team-member -->';

    $child_output .= '</div><!-- .lsow-team-member-wrapper -->';

    $output .= apply_filters('lsow_team_member_output', $child_output, $team_member, $settings);

endforeach;

$output .= '</div><!-- .lsow-team-members -->';

$output .= '<div class="lsow-clear"></div>';

echo apply_filters('lsow_team_members_output', $output, $settings);