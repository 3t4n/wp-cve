<?php
	// If this file is called directly, abort.
	if ( ! defined( 'ABSPATH' ) ) {exit;}
	echo 'ĐÃ load file w2w-smtp.php';
	add_action( 'phpmailer_init', 'w2w_init_smtp' );
	function w2w_init_smtp($phpmailer){
		error_log( print_r( 'ĐÃ Ở TRONG ĐÂY SMTP INIT', true ) );
		
		$phpmailer->isSMTP();
		$phpmailer->Host       = sanitize_text_field(w2w_get_option('txtSmtpHost'));
		$phpmailer->Port       = sanitize_text_field(w2w_get_option('txtSmtpPort'));
		if ( 'yes' === w2w_get_option('opt-smtp-authentication') ) {
			$phpmailer->SMTPAuth = 1;
			$phpmailer->Username   = sanitize_text_field(w2w_get_option('txtSmtpUserName'));
			$phpmailer->Password   = sanitize_text_field(w2w_get_option('txtSmtpPassword'));
		}
		/* Set the SMTPSecure value */
		if ( 'none' !== w2w_get_option('opt-encryption') ) {
			$phpmailer->SMTPSecure = sanitize_text_field(w2w_get_option('opt-encryption'));
		}
		$phpmailer->From       = sanitize_text_field(w2w_get_option('txtFromEmail'));
		$phpmailer->FromName   = sanitize_text_field(w2w_get_option('txtFromName'));
		$phpmailer->SetFrom( $phpmailer->From, $phpmailer->FromName );
		
		//set reasonable timeout
		$phpmailer->Timeout = 10;
    }

	function w2w_CatchPhpMailerFail($error){
        error_log( $error->get_error_message(), 3, WP_CONTENT_DIR . '/debug.log' );
    } add_action( 'wp_mail_failed', 'w2w_CatchPhpMailerFail' );
	
	function w2w_SetMailContentType(){
        return "text/html";
    } add_filter( 'wp_mail_content_type', 'w2w_SetMailContentType' );	
