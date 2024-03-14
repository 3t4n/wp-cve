<?php
/**
 * Add New Attribute Mapping View
 *
 * @link       https://webtoffee.com/
 * @since      1.0.0
 *
 */
if ( !defined( 'ABSPATH' ) ) {
	die();
}

$value = array();
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Attribute Mapping', 'webtoffee-product-feed' ); ?></h2>

<?php
/* $isDismissible = 'is-dismissible';
  printf(
  '<div class="notice notice-%1$s %1$s%3$s"><p>%2$s</p></div>', esc_attr( $notice[ 'type' ] ), wp_kses_post( $notice[ 'notice' ] ), esc_attr( $isDismissible )
  );
 * 
 */
?>


	<form action="" name="feed" id="category-mapping-form" method="post" autocomplete="off">
<?php wp_nonce_field( 'wt-attribute-mapping' ); ?>

		<br/>
		<table class="table tree widefat fixed ">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Local Attributes', 'webtoffee-product-feed' ); ?></th>
					<th><?php esc_html_e( 'Facebook Attributes', 'webtoffee-product-feed' ); ?></th>
				</tr>
			</thead>
			<tbody>
<?php wt_fbfeed_render_attributes( 0, '', $value ); ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2">
						<button  type="submit" class="button button-large button-primary"><?php esc_html_e( 'Save Mapping', 'webtoffee-product-feed' ); ?></button>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>