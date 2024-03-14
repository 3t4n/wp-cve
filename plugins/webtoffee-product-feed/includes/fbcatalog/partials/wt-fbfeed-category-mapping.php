<?php
/**
 * Add New Category Mapping View
 *
 * @link       https://webtoffee.com/
 * @since      1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

$value           = array();

?>
<div class="wt-wrap">
	<h2><?php esc_html_e( 'Category Mapping', 'webtoffee-product-feed' ); ?></h2>

	<?php
	/*$isDismissible = 'is-dismissible';
	printf(
	'<div class="notice notice-%1$s %1$s%3$s"><p>%2$s</p></div>', esc_attr( $notice[ 'type' ] ), wp_kses_post( $notice[ 'notice' ] ), esc_attr( $isDismissible )
	);
	 * 
	 */
	?>
	
	<h4><?php esc_html_e( 'Map WooCommerce categories with Facebook categories.', 'webtoffee-product-feed' ); ?></h4>
	<span><?php esc_html_e( 'Facebook has a'); ?> <a target="_blank" href="https://www.facebook.com/products/categories/en_US.txt"><?php esc_html_e( 'pre-defined set of categories.'); ?></a> <?php esc_html_e( 'Mapping your store categories with the Facebook categories will give more visibility to your products in Facebook shops and dynamic ads. To edit the mapping go to the respective'); ?> <a target="_blank" href="<?php echo admin_url('edit-tags.php?taxonomy=product_cat&post_type=product'); ?>"><?php esc_html_e( 'categories page.'); ?></a></span>
	
	<form action="" name="feed" id="category-mapping-form" class="category-mapping-form" method="post" autocomplete="off">
		<?php wp_nonce_field( 'wt-category-mapping' ); ?>

		<br/>
		<table class="table tree widefat fixed wt-pf-category-default-mapping-tb">
			<thead>
			<tr>
				<th><?php esc_html_e( 'Store Categories', 'webtoffee-product-feed' ); ?></th>
				<th><?php esc_html_e( 'Facebook Category', 'webtoffee-product-feed' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<?php wt_fbfeed_render_categories( 0, '', $value ); ?>
			</tbody>
			<tfoot>
			<tr>
				<td colspan="2">
					<?php if(!isset($ajax_render)): ?>
					<button name="save_mapping" type="submit" class="button button-large button-primary"><?php esc_html_e( 'Save Mapping', 'webtoffee-product-feed' ); ?></button>
				<?php endif; ?>
				</td>
			</tr>
			</tfoot>
		</table>
	</form>
</div>