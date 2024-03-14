<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
$hmcabStylesPost = $this->get_styles_post_settings();
foreach ( $hmcabStylesPost as $option_name => $option_value ) {
    if ( isset( $hmcabStylesPost[$option_name] ) ) {
        ${"" . $option_name} = $option_value;
    }
}
$cab_temp = ( get_option( 'cab_post_layout' ) ? get_option( 'cab_post_layout' ) : 'classic' );
?>
<div class="hmcabw-main-wrapper <?php 
esc_attr_e( $hmcabw_select_template );
?> <?php 
esc_attr_e( $cab_temp );
?>">
<?php 
if ( 'classic' === $cab_temp ) {
    include 'template/classic.php';
}
if ( 'simple' === $cab_temp ) {
    include 'template/simple.php';
}
if ( 'left-aligned' === $cab_temp ) {
    include 'template/left-aligned.php';
}
if ( 'centered' === $cab_temp ) {
    include 'template/left-aligned.php';
}
if ( 'mango' === $cab_temp ) {
    include 'template/mango.php';
}
?>
</div>