<?php
/**
 * Admin Item Edit Page.
 * Page: Items
 * Tab: Items
 *
 * @since       1.1.0
 * @subpackage  Admin/Views/Items
 * @package     EverAccounting
 * @var int $item_id
 */

defined( 'ABSPATH' ) || exit();

try {
	$item = new \EverAccounting\Models\Item( $item_id );
} catch ( Exception $e ) {
	wp_die( esc_html( $e->getMessage() ) );
}

$title = $item->exists() ? __( 'Update Item', 'wp-ever-accounting' ) : __( 'Add Item', 'wp-ever-accounting' );
?>
	<div class="ea-row">
		<div class="ea-col-7">
			<h1 class="wp-heading-inline"><?php esc_html_e( 'Items', 'wp-ever-accounting' ); ?></h1>
			<?php if ( $item->exists() ) : ?>
				<a href="
				<?php
				echo esc_url(
					add_query_arg(
						array(
							'tab'    => 'items',
							'page'   => 'ea-items',
							'action' => 'add',
						),
						admin_url( 'admin.php' )
					)
				);
				?>
							" class="page-title-action">
					<?php esc_html_e( 'Add New', 'wp-ever-accounting' ); ?>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( remove_query_arg( array( 'action', 'id' ) ) ); ?>" class="page-title-action"><?php esc_html_e( 'View All', 'wp-ever-accounting' ); ?></a>
			<?php endif; ?>
		</div>

		<div class="ea-col-5">

		</div>
	</div>
	<hr class="wp-header-end">

	<form id="ea-item-form" method="post" enctype="multipart/form-data">
		<div class="ea-card">
			<div class="ea-card__header">
				<h3 class="ea-card__title"><?php echo esc_html( $title ); ?></h3>
			</div>
			<div class="ea-card__inside">

				<div class="ea-row">
					<?php
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Name', 'wp-ever-accounting' ),
							'name'          => 'name',
							'placeholder'   => __( 'Enter Name', 'wp-ever-accounting' ),
							'tip'           => __( 'Enter Name', 'wp-ever-accounting' ),
							'value'         => $item->get_name(),
							'required'      => true,
						)
					);
					eaccounting_category_dropdown(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Category', 'wp-ever-accounting' ),
							'name'          => 'category_id',
							'value'         => $item->get_category_id(),
							'required'      => false,
							'type'          => 'item',
							'creatable'     => true,
							'ajax_action'   => 'eaccounting_get_item_categories',
							'modal_id'      => 'ea-modal-add-item-category',
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Sale price', 'wp-ever-accounting' ),
							'name'          => 'sale_price',
							'placeholder'   => __( 'Enter Sale price', 'wp-ever-accounting' ),
							'value'         => $item->get_sale_price(),
							'required'      => true,
						)
					);
					eaccounting_text_input(
						array(
							'wrapper_class' => 'ea-col-6',
							'label'         => __( 'Purchase price', 'wp-ever-accounting' ),
							'name'          => 'purchase_price',
							'placeholder'   => __( 'Enter Purchase price', 'wp-ever-accounting' ),
							'value'         => $item->get_purchase_price(),
							'required'      => true,
						)
					);
					if ( eaccounting_tax_enabled() ) :
						eaccounting_text_input(
							array(
								'wrapper_class' => 'ea-col-6',
								'label'         => __( 'Sales Tax (%)', 'wp-ever-accounting' ),
								'name'          => 'sales_tax',
								'placeholder'   => __( 'Enter Sale price', 'wp-ever-accounting' ),
								'value'         => $item->get_sales_tax(),
								'type'          => 'number',
								'attr'          => array(
									'step' => .01,
									'min'  => 0,
									'max'  => 100,
								),
							)
						);
						eaccounting_text_input(
							array(
								'wrapper_class' => 'ea-col-6',
								'label'         => __( 'Purchase Tax (%)', 'wp-ever-accounting' ),
								'name'          => 'purchase_tax',
								'placeholder'   => __( 'Enter Purchase price', 'wp-ever-accounting' ),
								'value'         => $item->get_purchase_tax(),
								'type'          => 'number',
								'attr'          => array(
									'step' => .01,
									'min'  => 0,
									'max'  => 100,
								),
							)
						);
					endif;
					eaccounting_textarea(
						array(
							'label'         => __( 'Description', 'wp-ever-accounting' ),
							'name'          => 'description',
							'value'         => $item->get_description(),
							'required'      => false,
							'wrapper_class' => 'ea-col-6',
							'placeholder'   => __( 'Enter description', 'wp-ever-accounting' ),
						)
					);

					eaccounting_file_input(
						array(
							'label'         => __( 'Product Image', 'wp-ever-accounting' ),
							'name'          => 'thumbnail_id',
							'value'         => $item->get_thumbnail_id(),
							'required'      => false,
							'allowed-types' => 'jpg,jpeg,png',
							'wrapper_class' => 'ea-col-6',
							'placeholder'   => __( 'Upload Image', 'wp-ever-accounting' ),
						)
					);

					eaccounting_hidden_input(
						array(
							'name'  => 'id',
							'value' => $item->get_id(),
						)
					);

					eaccounting_hidden_input(
						array(
							'name'  => 'action',
							'value' => 'eaccounting_edit_item',
						)
					);

					?>
				</div>
			</div>
			<div class="ea-card__footer">
				<?php
				wp_nonce_field( 'ea_edit_item' );
				submit_button( __( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' );
				?>
			</div>
		</div>
	</form>
<?php
$code     = eaccounting_get_default_currency();
$currency = eaccounting_get_currency( $code );
eaccounting_enqueue_js(
	"
	jQuery('#ea-item-form #purchase_price, #ea-item-form #sale_price').inputmask('decimal', {
			alias: 'numeric',
			groupSeparator: '" . $currency->get_thousand_separator() . "',
			autoGroup: true,
			digits: '" . $currency->get_precision() . "',
			radixPoint: '" . $currency->get_decimal_separator() . "',
			digitsOptional: false,
			allowMinus: false,
			prefix: '" . $currency->get_symbol() . "',
			placeholder: '0.000',
			rightAlign: 0,
			autoUnmask: true
		});
"
);
