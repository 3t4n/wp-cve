<?php

/**
 * @var string $title
 * @var bool $edit
 * @var string $previous_step
 * @var \WPDesk\Library\DropshippingXmlCore\Infrastructure\View\Abstraction\Displayable $sidebar
 * @var \WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView $form
 * @var bool $is_variable
 * @var WPDesk\View\Renderer\Renderer $renderer
 */

$is_pl = \get_locale() === 'pl_PL';

$renderer->output_render( 'Header', [ 'title' => $title ] );?>
<?php if ( ! $edit ) : ?>
	<h2>
	<?php
	/* translators: %s: page step */
		echo esc_html( sprintf( __( 'Step %s', 'dropshipping-xml-for-woocommerce' ), '4/4' ) );
	?>
		</h2>
<?php endif; ?>
<p><?php echo wp_kses_post( __( 'Set the product import cycle. Select the days of the week and hours for the cron process to run. The process will automatically synchronize the products in selected days and hours.', 'dropshipping-xml-for-woocommerce' ) ); ?></p>
<p style="font-weight: bold;"><?php echo wp_kses_post( __( 'Read more in the <a href="https://wpde.sk/dropshipping-sync-options" class="docs-url" target="_blank">plugin documentation &rarr;</a>.', 'dropshipping-xml-for-woocommerce' ) ); ?></p>
<hr>

<?php
$form->form_start(
	[
		'method'   => 'POST',
		'template' => 'form-start-no-table',
	]
);
?>
<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="postbox-container-1" class="postbox-container">
			<?php $sidebar->show(); ?>
		</div>
		<div id="postbox-container-2" class="postbox-container">
			<div id="post-body-content" style="position: relative;">
				<table class="form-table w-100">
					<tbody>
						<?php
						echo wp_kses(
							( $is_variable ? '<tr class="hidden"><td><table>' : '' ),
							[
								'table' => [],
								'tr'    => [
									'class' => [],
								],
								'td'    => [],
							]
						);
						?>
						<?php $form->show_field( 'unique_product_selector', [ 'parent_template' => 'form-field' ] ); ?>
						<?php
						echo wp_kses(
							( $is_variable ? '</table></td></tr>' : '' ),
							[
								'table' => [],
								'tr'    => [],
								'td'    => [],
							]
						);
						?>
						<?php $url = $is_pl? 'https://www.wpdesk.pl/sklep/dropshipping-xml-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=dropshipping-pro&utm_content=conditional-logic' : 'https://wpdesk.net/products/dropshipping-xml-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=dropshipping-pro&utm_content=conditional-logic'; ?>
						<?php $form->show_field( 'removed_products', [ 'parent_template' => 'form-field-free', 'url' => $url ] ); ?>
						<?php $form->show_field( 'turn_logical_condition', [ 'parent_template' => 'form-field-free-info' ] ); ?>
						<?php $form->show_field( 'logical_conditions', [ 'parent_template' => 'form-field' ] ); ?>
						<?php $form->show_field( 'update_only_existing', [ 'parent_template' => 'form-field' ] ); ?>
						<?php $form->show_field( 'create_new_products_as_draft', [ 'parent_template' => 'form-field' ] ); ?>
						<?php $form->show_field( 'sync_field', [ 'parent_template' => 'form-field' ] ); ?>
						<?php $form->show_field( 'cron_week_days', [ 'parent_template' => 'form-field' ] ); ?>
						<?php $url = $is_pl? 'https://www.wpdesk.pl/sklep/dropshipping-xml-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=dropshipping-pro&utm_content=cron-schedule' : 'https://wpdesk.net/products/dropshipping-xml-woocommerce/?utm_source=wp-admin-plugins&utm_medium=link&utm_campaign=dropshipping-pro&utm_content=cron-schedule' ; ?>
						<?php $form->show_field( 'cron_hours', [ 'parent_template' => 'form-field-free', 'url' => $url ] ); ?>
						<tr><th></th><td><?php echo wp_kses_post( __( '<b style="color:black">Read more about server requirements and cron settings in the</b> <a href="https://wpde.sk/dropshipping-req" class="docs-url" target="_blank">plugin documentation &rarr;</a>', 'dropshipping-xml-for-woocommerce' ) ); ?></p></td></tr>
					</tbody>
				</table>
			</div><!-- /post-body-content -->
			<br class="clear">
			<?php
			$renderer->output_render(
				'Steps',
				[
					'edit'          => $edit,
					'mode'          => $mode,
					'form'          => $form,
					'previous_step' => $previous_step,
				]
			);
			?>
		</div><!-- /poststuff -->
	</div>
</div>

<?php $form->form_fields_complete(); ?>
<?php $form->form_end( [ 'template' => 'form-end-no-table' ] ); ?>

<?php
$renderer->output_render( 'Footer' );
