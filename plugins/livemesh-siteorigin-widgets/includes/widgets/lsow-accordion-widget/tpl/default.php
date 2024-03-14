<?php

/**
 * @var $settings
 */
?>

<?php 
if ( !empty($instance['title']) ) {
    echo  $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'] ;
}
$settings = apply_filters( 'lsow_accordion_' . $this->id . '_settings', $settings );
$output = '<div class="lsow-accordion ' . $settings['style'] . '" data-toggle="' . (( $settings['toggle'] ? "true" : "false" )) . '" data-expanded="' . (( $settings['expanded'] ? "true" : "false" )) . '">';
foreach ( $settings['accordion'] as $panel ) {
    $panel_id = '';
    $child_output = '<div class="lsow-panel" id="' . $panel_id . '">';
    $child_output .= '<div class="lsow-panel-title">' . htmlspecialchars_decode( esc_html( $panel['title'] ) ) . '</div>';
    $child_output .= '<div class="lsow-panel-content">' . do_shortcode( $panel['panel_content'] ) . '</div>';
    $child_output .= '</div><!-- .lsow-panel -->';
    $output .= apply_filters(
        'lsow_accordion_item_output',
        $child_output,
        $panel,
        $settings
    );
}
$output .= '</div><!-- .lsow-accordion -->';
echo  apply_filters( 'lsow_accordion_output', $output, $settings ) ;