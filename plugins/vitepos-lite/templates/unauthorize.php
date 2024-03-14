<?php
/**
 * It is template of pos client
 *
 * @var \VitePos_Lite\Modules\POS_Settings $this
 *
 * @package vitepos
 */

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="icon" href="<?php $this->get_plugin_esc_url( 'templates/pos-assets/favicon.ico' ); ?>">
	<title>pos</title>
	<script>
		setTimeout(function () {
			window.location = "<?php echo esc_url( site_url() ); ?>";
		}, 10000)
	</script>
	<style>
		body{
			text-align: center
		}
	</style>
</head>
<body>
<h2><?php echo esc_html( $this->kernel_object->__( 'You do not have permission to access this link' ) ); ?></h2>
<h4><?php echo esc_html( $this->kernel_object->__( 'The page is redirecting to home page withing few seconds' ) ); ?></h4>
</body>
</html>
