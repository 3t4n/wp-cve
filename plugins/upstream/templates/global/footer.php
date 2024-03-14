<?php
/**
 * Footer template
 *
 * @package UpStream
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$page_title  = get_bloginfo( 'name' );
$footer_text = sprintf( '&copy; %s %s', $page_title, gmdate( 'Y' ) );
$footer_text = apply_filters( 'upstream_footer_text', $footer_text );

?>
<footer>
	<div class="pull-right"><?php echo esc_html( $footer_text ); ?></div>
	<div class="clearfix"></div>
</footer>
</div>
</div>

<?php wp_footer(); ?>

</body>
</html>
