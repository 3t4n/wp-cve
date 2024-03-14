<?php
/**
 * The template for displaying blog posts page
 *
 * @package Alma
 * @subpackage Templates
 * @since alma 1.0
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

get_header();
?>
<section>
<?php
if ( isset( $_REQUEST['theme'] ) && 'fdp_naked' === $_REQUEST['theme'] ) { // @codingStandardsIgnoreLine. Nonce already verified.
	while ( have_posts() ) :
		the_post();
		the_content();
	endwhile;
}
?>
</section>
<?php
get_footer();
exit;
