<?php

global $product, $post;

use Elementor\Icons_Manager;
use Elementor\Plugin;

$post_type = $post->post_type; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

$get_all_product = array(
	'orderby'     => 'date',
	'numberposts' => - 1,
	'order'       => 'ASC',
	'return'      => 'ids',
	'status'      => 'publish'
);

$get_all_product_ids = wc_get_products( $get_all_product );

if ( empty( $product ) && Plugin::$instance->editor->is_edit_mode() && ( empty( $get_all_product_ids ) ) ) {
	?>
	<div class="xpro-alert xpro-alert-warning" role="alert">
			<span class="xpro-alert-title">
				<?php esc_html_e( 'Product Not Found', 'xpro-elementor-addons' ); ?>
			</span>
		<span class="xpro-alert-description">
				<?php esc_html_e( 'You dont have any product please add some product first. This text will disappear after closing the editor mode.', 'xpro-elementor-addons' ); ?>
			</span>
	</div>
	<?php
	return;
}
$args           = array(
	'limit'   => 1,
	'orderby' => 'date',
	'order'   => 'ASC',
	'return'  => 'ids',
	'status'  => 'publish'
);
$get_product_id = wc_get_products( $args );
if ( $get_product_id ) {
	$first_product_id = $get_product_id[0];
}
if ( is_single() && 'xpro-themer' !== $post_type && 'xpro_content' !== $post_type && 'product' === $post_type ) {
	$product_id = get_the_id();
} else {
	if ( ! empty( $get_product_id ) ) {
		$product_id = $first_product_id;
	}
}

$html_tag = $settings['title_tag'];
$class    = 'xpro-woo-product-title';
$class   .= ( $settings['icon']['value'] && $settings['icon_align'] ) ? ' xpro-woo-product-title-icon-' . $settings['icon_align'] : '';

if ( ! empty( $product_id ) ) {
	$title_text = get_the_title( $product_id );
}
?>

<?php if ( ! empty( $product_id ) ) : ?>
	<<?php echo esc_attr( $html_tag ); ?> class="<?php echo esc_attr( $class ); ?>">
	<?php if ( $settings['icon']['value'] ) : ?>
		<span class="xpro-woo-product-title-icon">
			<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
		</span>
	<?php endif; ?>
	<span class="xpro-woo-product-title-text">
				<?php
				if ( ! empty( $product_id ) ) {
					$title_text = get_the_title( $product_id );
					echo esc_html( $title_text );
				}
				?>
			</span>
	</<?php echo esc_attr( $html_tag ); ?>>
<?php endif; ?>
