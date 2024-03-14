<?php
/**
 * Main page for eaccounting.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/eaccounting.php.
 *
 * @version 1.1.0
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<link rel="profile" href="https://gmpg.org/xfn/11"/>
	<meta name="robots" content="noindex,nofollow">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php do_action( 'eaccounting_head' ); ?>
</head>

<body class="wp-core-ui eaccounting">
<?php eaccounting_get_template( 'global/header.php' ); ?>

<?php do_action( 'eaccounting_before_body' ); ?>
<div class="ea-container">
	<?php do_action( 'eaccounting_body' ); ?>
</div>
<?php do_action( 'eaccounting_after_body' ); ?>

<?php eaccounting_get_template( 'global/footer.php' ); ?>
</body>

</html>
