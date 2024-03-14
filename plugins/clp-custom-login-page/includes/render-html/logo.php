<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$logo_type = get_option('clp_logo', 'image');

ob_start();

switch ( $logo_type ) {
    case 'text': ?>
        <h2 class="clp-login-logo">
            <?php CLP_Helper_Functions::customizer_icon_link( 'clp_logo', 'logo'); ?>
            <a href="<?php echo esc_html( get_option('clp_logo-url', get_bloginfo('url')) );?>"><?php echo esc_html( get_option('clp_logo-text', get_bloginfo('name')) );?></a>
        </h2>
        <?php 
        break;

    case 'image':
        $logo_url = !get_option('clp_logo-image', '') ? admin_url('images/wordpress-logo.svg?ver=20131107') : wp_get_attachment_url( get_option('clp_logo-image', '') ); ?>
        <h2 class="clp-login-logo">
            <?php CLP_Helper_Functions::customizer_icon_link( 'clp_logo', 'logo'); ?>
            <a href="<?php echo esc_html( get_option('clp_logo-url', get_bloginfo('url')) );?>">
                <img src="<?php echo esc_url( $logo_url );?>" alt="<?php echo esc_html( get_option('clp_logo-text', get_bloginfo('name')) );?> logo">
            </a>
        </h2>
        <?php
        break;

    case 'none':         
    default:
        break;
}

$html = ob_get_clean();