<?php
/**
 * Order Downloads.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$w_title = shop_ready_gl_get_setting('woo_ready_thankyou_order_details_download_heading','Downloads');

?>
<div class="woocommerce-order-downloads woo-ready-order-downloads">
	<?php if (  $show_title  ) : ?>
		<h2 class="woocommerce-order-downloads__title"><?php echo esc_html($w_title); ?></h2>
	<?php endif; ?>

	<table class="woocommerce-table woocommerce-table--order-downloads shop_table shop_table_responsive order_details">
		<thead>
			<tr>
				<?php foreach ( wc_get_account_downloads_columns() as $column_id => $column_name ) : ?>
				<th class="<?php echo esc_attr( $column_id ); ?>"><span class="nobr"><?php echo esc_html( $column_name ); ?></span></th>
				<?php endforeach; ?>
			</tr>
		</thead>

		<?php foreach ( $downloads as $download ) : ?>
			<tr>
				<?php foreach ( wc_get_account_downloads_columns() as $column_id => $column_name ) : ?>
					<td class="<?php echo esc_attr( $column_id ); ?>" data-title="<?php echo esc_attr( $column_name ); ?>">
						<?php
						if ( has_action( 'woocommerce_account_downloads_column_' . $column_id ) ) {
							do_action( 'woocommerce_account_downloads_column_' . $column_id, $download );
						} else {
							
							switch ( $column_id ) {
								
								case 'download-product':

									if ( $download['product_url'] ) {
										echo wp_kses_post('<a href="' . esc_url( $download['product_url'] ) . '">' . esc_html( $download['product_name'] ) . '</a>');
									} else {
										echo esc_html( $download['product_name'] );
									}
									break;
								case 'download-file':

									echo wp_kses_post('<a href="' . esc_url( $download['download_url'] ) . '" class="woocommerce-MyAccount-downloads-file button alt">' . esc_html( $download['download_name'] ) . '</a>');
									break;
								case 'download-remaining':

									echo is_numeric( $download['downloads_remaining'] ) ? esc_html( $download['downloads_remaining'] ) : esc_html__( '&infin;', 'shopready-elementor-addon' );
									break;
								case 'download-expires':

									if ( ! empty( $download['access_expires'] ) ) {
										echo wp_kses_post('<time datetime="' . esc_attr( date( 'Y-m-d', strtotime( $download['access_expires'] ) ) ) . '" title="' . esc_attr( strtotime( $download['access_expires'] ) ) . '">' . esc_html( date_i18n( get_option( 'date_format' ), strtotime( $download['access_expires'] ) ) ) . '</time>');
									} else {
										esc_html_e( 'Never', 'shopready-elementor-addon' );
									}
									break;
							}
						}
						?>
					</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
	</table>
</div>
