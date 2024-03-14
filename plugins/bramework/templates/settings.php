<?php

$domain = home_url();

if ( isset( $_POST['connect'] ) ) {
	//Get current user email
	$user       = wp_get_current_user();
	$user_email = $user->user_email;
	//Generate hash
	$user_token = bin2hex( openssl_random_pseudo_bytes( 16 ) );
	//Get stored passwords
	$bramework_passwords = get_option( 'wpb_bramework_tokens' );

	if ( is_array( $bramework_passwords ) ) {
		$bramework_passwords[ $user_email ] = $user_token;
	} else {
		$bramework_passwords = array(
			$user_email => $user_token
		);
	}
	//Update or add new passwords
	update_option( 'wpb_bramework_tokens', $bramework_passwords );
	//Create bramework redirect url
	$redirect_url = sprintf( '%s?domain=%s&user=%s&token=%s', BRAMEWORK_CONNECT_URL, $domain, $user_email, $user_token );
//	header( "Location: " . esc_url( $redirect_url ), true, 302 );
//	exit;
}

if ( isset( $_POST['disconnect'] ) && isset( $_POST['token'] ) ) {
	$bramework_tokens = get_option( 'wpb_bramework_tokens', array() );
	$token            = sanitize_text_field( $_POST['token'] );
	$email            = array_search( $token, $bramework_tokens );
	if ( $email && is_array( $bramework_tokens ) && ! empty( $bramework_tokens ) ) {
		unset( $bramework_tokens[ $email ] );
		update_option( 'wpb_bramework_tokens', $bramework_tokens );
	}
}

$current_user     = wp_get_current_user();
$bramework_tokens = get_option( 'wpb_bramework_tokens', array() );
$user_connected   = false;
$user_token       = '';
if ( is_array( $bramework_tokens ) && array_key_exists( $current_user->user_email, $bramework_tokens ) ) {
	$user_connected = true;
	$user_token     = $bramework_tokens[ $current_user->user_email ];
}

$bramework_domain_authorized = WPM_Bramework_Plugin::checkAuthorization( $user_token, $domain );

?>
<div class="wrap bramework">
    <div class="header-banner">
        <img class="header-image" src="<?php echo esc_attr( plugin_dir_url( __FILE__ ) . '../images/bramework-logo-purple-svg.png' ) ?>">
    </div>
    <div class="form-wrapper">
		<?php if ( $user_connected && $bramework_domain_authorized ) { ?>
            <div class="form-text"><span class="bold"><?php echo __( 'Website connected', 'bramework' ); ?></span></div>
		<?php } elseif ( $user_connected ) { ?>
            <div class="form-text"><span class="bold"><?php echo __( 'Waiting for your authorization', 'bramework' ); ?></span></div>
		<?php } else { ?>
            <div class="form-text" class="subheader"><span class="bold"><?php _e( 'Connect website', 'bramework' ); ?></span><br> <span class="small">
                    <?php _e( 'Don\'t have a Bramework account?', 'bramework' ); ?>
                    <a href="https://app.bramework.com/register" target="_blank"><?php _e( 'Sign up', 'bramework' ); ?></a>
                </span>
            </div>
		<?php } ?>
        <form class="bramework-form" action="" method="post">
			<?php settings_fields( 'bramework' ); ?>
			<?php if ( $user_connected && $bramework_domain_authorized ) { ?>
                <br><label for="user_token"><?php _e( 'User token', 'bramework' ); ?></label><br>
                <input type="text" id="user_token" name="token" readonly="readonly" value="<?php echo esc_attr( $user_token ); ?>">
                <input type="hidden" name="disconnect" value="true">
				<?php submit_button( __( 'Disconnect', 'bramework' ), 'small' ); ?>
            <?php } elseif(isset( $_POST['connect'] )) { ?>
                <br><span class="small"><?php _e( 'Redirecting to', 'bramework' ); ?> <a href="<?php echo esc_attr( $redirect_url ); ?>">Bramework</a>...</span><br><br>
                <script>
                    setTimeout(function(){
                        window.location = '<?php echo esc_attr( BRAMEWORK_CONNECT_URL ) ?>?domain=<?php echo esc_attr( $domain ) ?>&user=<?php echo esc_attr( $user_email ) ?>&token=<?php echo esc_attr( $user_token ) ?>';
                    }, 2000);
                </script>
            <?php } else { ?>
                <input type="hidden" name="connect" value="true">
				<?php submit_button( __( 'Connect', 'bramework' ) ); ?>
            <?php } ?>
        </form>
    </div>
</div>