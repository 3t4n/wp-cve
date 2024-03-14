<?php
include( 'public/inc/Captcha.php' );
include( 'public/inc/Session.php' );
if ( ! function_exists( 'getallheaders' ) ) {
    function getallheaders() {
        $headers = [];
        foreach ( $_SERVER as $name => $value ) {
            if ( 'HTTP_' == substr( $name, 0, 5 ) ) {
                $headers[str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', substr( $name, 5 ) ) ) ) )] = $value;
            }
        }
        return $headers;
    }
}
// Initialize Session
session_cache_limiter( false );
if ( '' === session_id() ) {
    session_start();
}
if ( isset( $_GET['namespace'] ) && ( '' !== $_GET['namespace'] ) ) {
	//$namespace = filter_input( INPUT_GET, 'namespace', FILTER_SANITIZE_STRING );
	$namespace = htmlspecialchars( $_GET['namespace'] );
    $session = new \visualCaptcha\Session( 'visualcaptcha_' . $namespace );
} else {
    $session = new \visualCaptcha\Session();
}
$_action = '';
if ( false === isset( $_GET['_a'] ) ) {
	$_action = '';
} else {
	//$_action = filter_input( INPUT_GET, '_a', FILTER_SANITIZE_STRING );
	$_action = htmlspecialchars( $_GET['_a'] );
}
switch ( $_action ) {
	case 'start': {
		$captcha = new \visualCaptcha\Captcha( $session, __DIR__ . '/public/assets/captcha' );
		$_img = isset( $_GET['_img'] ) ? filter_input( INPUT_GET, '_img', FILTER_SANITIZE_NUMBER_INT ) : '';
		$captcha->generate( $_img );
		header( 'Content-Type: application/json' );
		echo json_encode( $captcha->getFrontEndData() );
		break;
	}
	case 'image': {
		$captcha = new \visualCaptcha\Captcha( $session, __DIR__ . '/public/assets/captcha' );
		$_id = isset( $_GET['_id'] ) ? filter_input( INPUT_GET, '_id', FILTER_SANITIZE_NUMBER_INT ) : '';
		if ( ! $captcha->streamImage(
	            getallheaders(),
	            $_id,
	            0
	    ) ) {
			if ( 0 !== ob_get_level() ) {
	            ob_clean();
	        }
		}
        break;
	}
    case 'audio': {
		$captcha = new \visualCaptcha\Captcha( $session, __DIR__ . '/public/assets/captcha' );
		if ( ! $captcha->streamAudio( getallheaders(), 'mp3' ) ) {
			if ( 0 !== ob_get_level() ) {
	            ob_clean();
	        }
		}
        break;
	}
    default: {
        print( 'default' );
    }
}
