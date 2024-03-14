<!DOCTYPE html>
<html <?php language_attributes(); ?>

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="stylesheet" type="text/css" href="<?php echo CATCHWEBTOOLS_URL . 'sscss/maintainance-message.css'; ?>" />
	<title><?php echo get_bloginfo( 'name' ) . ': ' . __( 'Site Under Maintainance', 'catch-web-tools' ); ?></title>
</head>

<body>
	<div class="pusher"><!-- centers next div --></div>
	<div class="content"><?php echo get_transient( 'catch_updater_update_message' ); ?></div>
</body>

</html>