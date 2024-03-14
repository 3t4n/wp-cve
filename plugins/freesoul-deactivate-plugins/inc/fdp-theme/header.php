<?php
/**
 * The Header template
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package Alma
 * @subpackage Templates
 * @since Alma 1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<!DOCTYPE html>
<html>
<head>
	<title>FDP Theme</title>
	<?php
	if ( isset( $_REQUEST['theme'] ) && 'fdp_naked' === $_REQUEST['theme'] ) { // @codingStandardsIgnoreLine. Nonce already verified.
		wp_head();
	}
	?>
</head>
<body>
<?php if ( isset( $_REQUEST['theme'] ) && 'fdp_naked' === $_REQUEST['theme'] ) { // @codingStandardsIgnoreLine. Nonce already verfified.?>
	<header>
		<nav>
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_class'     => 'main-menu',
					'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
					'echo'           => 'true',
				)
			);
			?>
		</nav>
	</header>
	<?php } ?>
	<div id="page" class="hfeed site">
		<main>
