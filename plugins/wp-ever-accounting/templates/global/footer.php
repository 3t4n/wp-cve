<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * This template can be overridden by copying it to yourtheme/eaccounting/global/footer.php.
 *
 * @since 1.1.0
 * @package EverAccounting
 */

defined( 'ABSPATH' ) || exit;
$host = eaccounting_get_site_name();
?>
</div><!--/ea-body-->
<footer class="ea-footer ea-noprint">
	<div class="ea-container">
		<p class="ea-copyright-info">
			<?php echo esc_html( date_i18n( 'Y' ) ); ?>
			<?php
			echo sprintf(
					/* translators: %s: site name */
				esc_html__( 'Copyright %s', 'wp-ever-accounting' ),
				esc_html( $host )
			);
			?>
		</p>
	</div>
</footer>
