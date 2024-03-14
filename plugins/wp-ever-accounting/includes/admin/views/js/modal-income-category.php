<?php
/**
 * Add Category Modal.
 *
 * @package     EverAccounting
 * @subpackage  Admin/Js Templates
 * @since       1.0.2
 */

defined( 'ABSPATH' ) || exit();
?>
<script type="text/template" id="tmpl-ea-modal-add-category">
	<div class="ea-modal">
		<div class="ea-modal-content">
			<section class="ea-modal-main" role="main">
				<form id="ea-modal-account-form" action="" method="post">

					<header class="ea-modal-header">
						<h1><?php esc_html_e( 'Add Category', 'wp-ever-accounting' ); ?></h1>
						<button class="modal-close modal-close-link dashicons">
							<span class="screen-reader-text"><?php esc_html_e( 'Close', 'wp-ever-accounting' ); ?>></span>
						</button>
					</header>

					<article>
						<div class="ea-row">
							<?php
							eaccounting_text_input(
								array(
									'wrapper_class' => 'ea-col-12',
									'label'         => __( 'Category Name', 'wp-ever-accounting' ),
									'name'          => 'name',
									'value'         => '',
									'required'      => true,
								)
							);
							eaccounting_text_input(
								array(
									'wrapper_class' => 'ea-col-12',
									'label'         => __( 'Color', 'wp-ever-accounting' ),
									'name'          => 'color',
									'data_type'     => 'color',
									'value'         => eaccounting_get_random_color(),
									'required'      => true,
								)
							);
							eaccounting_hidden_input(
								array(
									'name'  => 'type',
									'value' => 'income',
								)
							);
							eaccounting_hidden_input(
								array(
									'name'  => 'action',
									'value' => 'eaccounting_edit_category',
								)
							);
							wp_nonce_field( 'ea_edit_category' );
							?>
						</div>
					</article>

					<footer>
						<div class="inner">
							<button type="submit" class="button button-primary button-large"><?php esc_html_e( 'Add', 'wp-ever-accounting' ); ?></button>
						</div>
					</footer>
				</form>
			</section>
		</div>
	</div>
	<div class="ea-modal-backdrop modal-close"></div>
</script>
