<?php
/**
 * Admin Category Edit Page.
 *
 * @package     EverAccounting
 * @subpackage  Admin/Settings/Categories
 * @since       1.0.2
 */

defined( 'ABSPATH' ) || exit();
$category_id = filter_input( INPUT_GET, 'category_id', FILTER_VALIDATE_INT );
try {
	$category = new \EverAccounting\Models\Category( $category_id );
} catch ( Exception $e ) {
	wp_die( esc_html( $e->getMessage() ) );
}
$back_url = remove_query_arg( array( 'action', 'category_id' ) );
?>
<div class="ea-title-section">
	<div>
		<h1 class="wp-heading-inline"><?php esc_html_e( 'Categories', 'wp-ever-accounting' ); ?></h1>
		<?php if ( $category->exists() ) : ?>
			<a href="
			<?php
			echo esc_url(
				add_query_arg(
					array(
						'tab'    => 'categories',
						'page'   => 'ea-settings',
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
</div>
<hr class="wp-header-end">

<form id="ea-category-form" method="post">
<div class="ea-card">
	<div class="ea-card__header">
		<h3 class="ea-card__title">
			<?php echo $category->exists() ? esc_html__( 'Update Category', 'wp-ever-accounting' ) : esc_html__( 'Add Category', 'wp-ever-accounting' ); ?>
		</h3>
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
						'value'         => $category->get_name(),
						'required'      => true,
					)
				);

				eaccounting_select2(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Type', 'wp-ever-accounting' ),
						'name'          => 'type',
						'value'         => $category->get_type(),
						'options'       => eaccounting_get_category_types(),
						'placeholder'   => __( 'Select Type', 'wp-ever-accounting' ),
						'required'      => true,
					)
				);

				eaccounting_text_input(
					array(
						'wrapper_class' => 'ea-col-6',
						'label'         => __( 'Color', 'wp-ever-accounting' ),
						'name'          => 'color',
						'placeholder'   => __( 'Enter Color', 'wp-ever-accounting' ),
						'value'         => $category->get_color(),
						'default'       => eaccounting_get_random_color(),
						'data_type'     => 'color',
						'style'         => 'width: calc(100% - 3em) !important;',
						'required'      => true,
					)
				);

				eaccounting_hidden_input(
					array(
						'name'  => 'id',
						'value' => $category->get_id(),
					)
				);

				eaccounting_hidden_input(
					array(
						'name'  => 'action',
						'value' => 'eaccounting_edit_category',
					)
				);

				?>
			</div>

	</div>
	<div class="ea-card__footer">
		<?php
		wp_nonce_field( 'ea_edit_category' );
		submit_button( __( 'Submit', 'wp-ever-accounting' ), 'primary', 'submit' );
		?>
	</div>
</div>
</form>
