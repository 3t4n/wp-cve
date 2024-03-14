<?php
// ===============  [box] ===================
function wvr_compat_do_box( $args = '', $text ) {
    extract(shortcode_atts(array(
        'align'         =>  '',
        'border'        =>  true,
        'border_rule'   => '1px solid black',
        'border_radius' => '',
        'color'         => '',
        'background'    => '',
        'margin'        => '',
        'padding'       => '1',
        'shadow'        => '',
        'style'         => '',
        'width'         => ''
    ), $args));

    $sty = 'style="';

    if ( $align ) {
        $align = strtolower($align);
        switch ( $align ) {
            case 'center':
                $sty .= 'display:block;margin-left:auto;margin-right:auto;';
                break;
            case 'right':
                $sty .= 'float:right;';
                break;
            default:
                $sty .= 'float:left;';
                break;
        }
    }

    if ( $border )
        $sty .= "border:{$border_rule};";
    if ( $border_radius )
        $sty .= "border-radius:{$border_radius}px;";
    if ( $shadow ) {
        if ( $shadow < 1 ) $shadow = 1;
        if ( $shadow > 5 ) $shadow = 5;
        $sty .= "box-shadow:0 0 4px {$shadow}px rgba(0,0,0,0.25);";
    }
    if ( $color )
        $sty .= "color:{$color};";
    if ( $background )
        $sty .= "background-color:{$background};";
    if ( $margin )
        $sty .= "margin:{$margin}em;";
    if ( $padding )
        $sty .= "padding:{$padding}em;";
    if ( $width )
        $sty .= "width:{$width}%;";
    if ( $sty )
        $sty .= $style;
    $sty .= '"';    // finish it

    return "<div {$sty}><!--[box]-->" . do_shortcode( $text ) . '</div><!--[box]-->';
}
?>
