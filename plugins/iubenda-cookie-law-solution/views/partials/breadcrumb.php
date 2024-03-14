<?php
/**
 * Breadcrumb - global - partial page.
 *
 * @var array $page_labels Breadcrumb pages.
 *
 * @package  Iubenda
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<ul class="breadcrumb text-gray">
	<li class="breadcrumb__item"><a href="<?php echo esc_url( add_query_arg( array( 'view' => 'products-page' ), iubenda()->base_url ) ); ?>"><?php esc_html_e( 'Products', 'iubenda' ); ?></a></li>
	<?php
	foreach ( $page_labels as $page_label ) :
		if ( end( $page_labels ) === $page_label ) {
			$text_style = 'text-bold';
		} else {
			$text_style = '';
		}

		$href = iub_array_get( $page_label, 'href' ) ?? 'javascript:void(0)';
		?>
		<li class='breadcrumb__item <?php echo esc_html( $text_style ); ?>'><a href='<?php echo esc_html( $href ); ?>'><?php echo esc_html( iub_array_get( $page_label, 'title' ) ); ?></a></li>
		<?php
	endforeach;
	?>
</ul>

<?php if ( iubenda()->notice->has_inside_plugin_notice() ) : ?>
	<div class="p-3 m-3">
		<?php iubenda()->notice->show_notice_inside_plugin(); ?>
	</div>
<?php endif; ?>
