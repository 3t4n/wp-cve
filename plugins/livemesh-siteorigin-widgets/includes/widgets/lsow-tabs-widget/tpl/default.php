<?php

/**
 * @var $settings
 */
if ( !empty($instance['title']) ) {
    echo  $args['before_title'] . esc_html( $instance['title'] ) . $args['after_title'] ;
}
$settings = apply_filters( 'lsow_tabs_' . $this->id . '_settings', $settings );
$plain_styles = array( 'style2', 'style6', 'style7' );

if ( in_array( $settings['style'], $plain_styles, true ) ) {
    $settings['icon_type'] = 'none';
    // do not display icons for plain styles even if chosen by the user
}

$vertical_class = '';
$vertical_styles = array(
    'style7',
    'style8',
    'style9',
    'style10'
);
if ( in_array( $settings['style'], $vertical_styles, true ) ) {
    $vertical_class = 'lsow-vertical';
}
foreach ( $settings['tabs'] as $tab ) {
    $tab_id = '';
    $tab_element = '<a class="lsow-tab-label" href="#' . $tab_id . '">';
    
    if ( $settings['icon_type'] == 'icon_image' ) {
        $tab_element .= '<span class="lsow-image-wrapper">';
        $icon_image = $tab['icon_image'];
        $tab_element .= wp_get_attachment_image(
            $icon_image,
            'thumbnail',
            false,
            array(
            'class' => 'lsow-image',
        )
        );
        $tab_element .= '</span>';
    } elseif ( $settings['icon_type'] == 'icon' ) {
        $tab_element .= '<span class="lsow-icon-wrapper">';
        $tab_element .= siteorigin_widget_get_icon( $tab['icon'] );
        $tab_element .= '</span>';
    }
    
    $tab_element .= '<span class="lsow-tab-title">';
    $tab_element .= esc_html( $tab['title'] );
    $tab_element .= '</span>';
    $tab_element .= '</a>';
    $tab_nav = '<div class="lsow-tab">' . $tab_element . '</div>';
    $tab_content = '<div id="' . $tab_id . '" class="lsow-tab-pane">' . do_shortcode( $tab['tab_content'] ) . '</div>';
    $tab_elements[] = apply_filters(
        'lsow_tab_nav_output',
        $tab_nav,
        $tab,
        $settings
    );
    $tab_panes[] = apply_filters(
        'lsow_tab_content_output',
        $tab_content,
        $tab,
        $settings
    );
}
$output = '<div class="lsow-tabs ' . $vertical_class . ' ' . esc_attr( $settings['style'] ) . '" data-mobile-width="' . intval( $settings['mobile_width'] ) . '">';
$output .= '<a href="#" class="lsow-tab-mobile-menu"><i class="lsow-icon-menu"></i>&nbsp;</a>';
$output .= '<div class="lsow-tab-nav">';
foreach ( $tab_elements as $tab_nav ) {
    $output .= $tab_nav;
}
$output .= '</div><!-- .lsow-tab-nav -->';
$output .= '<div class="lsow-tab-panes">';
foreach ( $tab_panes as $tab_pane ) {
    $output .= $tab_pane;
}
$output .= '</div><!-- .lsow-tab-panes -->';
$output .= '</div><!-- .lsow-tabs -->';
echo  apply_filters( 'lsow_tabs_output', $output, $settings ) ;