<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$footer = get_option('clp_footer-enable', '1');

if ( is_customize_preview() ) {
    $footer = true;
}

if ( !$footer  ) {
    return;
}

$clp_footer = array(
    'left' => array(),
    'center' => array(),
    'right' => array(),
);

$clp_footer = apply_filters('clp_filter_page_footer', $clp_footer);
array_push($clp_footer[get_option('clp_footer-copyright_pos', 'left')], 'clp_footer-copyright');
array_push($clp_footer[get_option('clp_footer-niteothemes_pos', 'right')], 'clp_footer-niteothemes');
ob_start(); ?>

<div class="clp-page-footer">
    <?php 
    foreach ( $clp_footer as $footer_pos => $option ) { ?>
        <div class="clp-footer-content <?php echo esc_attr($footer_pos);?>"><?php 
            foreach ($option as $option_name) {

                if ( !is_array($option_name)) {
                    if ( $option_name === 'clp_footer-niteothemes' && get_option($option_name, '1') ) {
                        echo '<div class="clp-niteothemes-msg"><p>Made by <a href="https://wordpress.org/plugins/clp-custom-login-page/">CLP - Custom Login Page</a> / <a href="https://niteothemes.com">NiteoThemes</a></p></div>';
                    } else {
                        echo get_option($option_name, '') ? '<div class="'.esc_attr($option_name).'">' . get_option($option_name, '') . '</div>' : ''; 
                    }

                } else {
                    echo $option_name['html'];
                }
            } ?></div>
        <?php 
    } ?>

</div>

<?php 
$html = ob_get_clean();