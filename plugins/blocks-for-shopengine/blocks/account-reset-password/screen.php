<?php

defined( 'ABSPATH' ) || exit;

if ( \ShopEngine\Core\Template_Cpt::TYPE || $block->is_editor) {
    $args = array(
        'key'   => 'dummy',
        'login' => 'dummy',
    );
    include 'dummy-reset-password.php';
    
}else{
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    if ( ! empty( $_GET['show-reset-form'] ) ) { 
        if ( isset( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ) && 0 < strpos(sanitize_text_field(wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] )) , ':' ) ) {  
            list( $rp_id, $rp_key ) = array_map( 'wc_clean', explode( ':', sanitize_text_field(wp_unslash( $_COOKIE[ 'wp-resetpass-' . COOKIEHASH ] ), 2 ) ));
            $userdata               = get_userdata( absint( $rp_id ) );
            $rp_login               = $userdata ? $userdata->user_login : '';
            $user                   = check_password_reset_key( $rp_key, $rp_login );
    
            // Reset key / login is correct, display reset password form with hidden key / login values.
            if ( is_object( $user ) ) {
                $args = array(
                    'key'   => $rp_key,
                    'login' => $rp_login,
                );
               
                include 'content.php';
            }
        }else{
            
            /**
             * wp_redirect function is not working here. That is why using js to redirect
             */
            $url = home_url().'/my-account/lost-password'; 
            ?>
            <script>
                location.href = '<?php echo esc_url($url); ?>';
            </script>
            <?php           
        }
    }
}
