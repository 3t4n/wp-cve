<?php
/** @var string $canvas_page_title */
/** @var string $redirect_link Defined if $registered_and_logged_in_successed defined */
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type"
		  content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>"/>
	<title>
		<?php printf( __( '%1$s | %2$s' ), $canvas_page_title, get_bloginfo( 'name', 'display' ) ); ?>
	</title>
	<meta name='robots' content='noindex,noarchive'/>
	<meta name='referrer' content='strict-origin-when-cross-origin'/>
	<meta name="viewport" content="width=device-width"/>
	<?php if ( isset( $registered_and_logged_in_successed ) && $registered_and_logged_in_successed ) { ?>
		<meta http-equiv="Refresh" content="3; url='<?php echo $redirect_link; ?>'"/>
	<?php } ?>
	<?php
	do_action( 'canvas_login_register_style' );
	?>
</head>
<body class="canvas-login">
<div class="form-container">
