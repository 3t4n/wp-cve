<?php 
$style = '';
$mainSelector = '#twp-'. $id .'.'.$settings['type'];
if ( isset( $settings['themeColor'] ) && !empty( $settings['themeColor'] ) ) {

    $style .= $mainSelector .':before{
        border-color:'.$settings['themeColor'].';
    }';

    $style .= $mainSelector .':after{
        border-color:'.$settings['themeColor'].';
    }';
    $style .= $mainSelector .' .timeline-year:after{
        border-color:'.$settings['themeColor'].';
        border-left-color: transparent;
    }';
    $style .= $mainSelector .' .timeline-content{
        border-color:'.$settings['themeColor'].';
    }';
    $style .= 
    $mainSelector .' .title, '.
    $mainSelector .' .description {
        color:'.$settings['themeColor'].';
    }';
    $style .= 
    $mainSelector .' .timeline-content:after, '.
    $mainSelector .' .timeline-icon, '.
    $mainSelector .' .timeline-year {
        background-color:'.$settings['themeColor'].';
    }';
} 
if ( isset( $settings['titleColor'] ) && !empty( $settings['titleColor'] ) ) {
    $style .= 
    $mainSelector .' .title {
        color:'.$settings['titleColor'].';
    }';
}

if(  isset( $settings['titleFontSize'] ) && strval($settings['titleFontSize'] ) > 0 ) {
    $style .= 
    $mainSelector .' .title {
        font-size:'.$settings['titleFontSize'].'px;
    }';
}

if ( isset( $settings['hide_title'] ) && !empty( $settings['hide_title'] ) && $settings['hide_title'] === '1' ) {
    $style .= 
    $mainSelector .' .title {
        display:none;
    }';
}
if ( isset( $settings['iconColor'] ) && !empty( $settings['iconColor'] ) ) {
    $style .= 
    $mainSelector .' .timeline-icon {
        color:'.$settings['iconColor'].';
    }';
}
if( isset( $settings['iconFontSize'] )  && strval($settings['iconFontSize'] ) > 0 ) {
    $style .= 
    $mainSelector .' .timeline-icon {
        font-size:'.$settings['iconFontSize'].'px;
    }';
}
if ( isset( $settings['dateColor'] ) && !empty( $settings['dateColor'] ) ) {
    
    $style .= 
    $mainSelector .' .timeline-year{
        color:'.$settings['dateColor'].';
    }';
}

if( isset( $settings['dateFontSize'] ) && strval($settings['dateFontSize'] ) > 0 ) {
    $style .= 
    $mainSelector .' .timeline-year{
        font-size:'.$settings['dateFontSize'].'px;
    }';
}

if ( isset( $settings['captionColor'] ) && !empty( $settings['captionColor'] ) ) {
    $style .= 
    $mainSelector .' .description {
        color:'.$settings['captionColor'].';
    }';
}
if(  isset( $settings['captionFontSize'] ) && strval($settings['captionFontSize'] ) > 0 ) {
    $style .= 
    $mainSelector .' .description {
        font-size:'.$settings['captionFontSize'].'px;
    }';
}
if ( isset( $settings['hide_description'] ) && !empty( $settings['hide_description'] ) &&  $settings['hide_description'] === '1' ) {
    $style .= 
    $mainSelector .' .description {
        display:none;
    }';
}
if ( isset( $settings['style'] ) && !empty( $settings['style'] ) ) {
    $style .= $settings['style'];
}
?>
<style>
    <?php echo esc_attr( $style ); ?>
</style>


