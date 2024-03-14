<?php
// Block direct access
if ( !defined('ABSPATH') ) {
    exit;
}

do_action( 'enteraddons/template/before_footer_content' );
echo '<footer class="enteraddons-footer-wrapper">';
    $templateId = $args;
    echo Enteraddons\Classes\Helper::elementor_content_display( $templateId );
echo '</footer>';
do_action( 'enteraddons/template/after_footer_content' );

wp_footer(); 
?>
</body>
</html>