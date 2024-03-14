<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package Alma
 * @subpackage Templates
 * @since alma 1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
	</main>
</div>
<footer></footer>
<?php
if ( isset( $_REQUEST['theme'] ) && 'fdp_naked' === $_REQUEST['theme'] ) { // @codingStandardsIgnoreLine. Nonce already verified.
	wp_footer();
}
?>
</body>
</html>
<?php
exit;
